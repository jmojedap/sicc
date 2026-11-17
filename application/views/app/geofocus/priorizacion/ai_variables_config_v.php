<!-- 2026-10-12: prompt, instrucciones y observaciones comparten la columna izquierda de Variables. -->
<section v-if="isEditable() && Number(userId) > 0" class="geofocus-priority-assistant"
    aria-labelledby="priority-ai-config-title" v-bind:aria-busy="aiVariablesConfig.loading">
    <?php
        // 2026-11-17: comparar consumo y límite sin dividir por cero; la barra ocupa como máximo el 100%.
        $tokens_used = max(0, (int) ($user_token_totals['total_tokens'] ?? 0));
        $token_limit = max(0, (int) ($max_tokens ?? 0));
        $token_usage_percentage = $token_limit > 0 ? round($tokens_used / $token_limit * 100, 1) : 0;
        $token_bar_percentage = min(100, $token_usage_percentage);
        // 2026-11-17: mostrar los datos solo en el tooltip y conservar una descripción accesible de la barra.
        $token_usage_tooltip = 'Uso de tokens (últimos 7 días): '
            . ($token_limit > 0 ? number_format($token_usage_percentage, 1, ',', '.') . '%' : 'límite no disponible')
            . '. Solicitudes realizadas: ' . number_format((int) ($user_token_totals['total_requests'] ?? 0), 0, ',', '.') . '.';
    ?>
    <div class="mb-3 py-1" title="<?= htmlspecialchars($token_usage_tooltip, ENT_QUOTES, 'UTF-8') ?>">
        <div class="progress geofocus-priority-token-progress" role="progressbar" tabindex="0"
            aria-label="Uso de tokens de los últimos 7 días"
            aria-valuemin="0" aria-valuemax="100" aria-valuenow="<?= $token_bar_percentage ?>"
            aria-valuetext="<?= htmlspecialchars($token_usage_tooltip, ENT_QUOTES, 'UTF-8') ?>">
            <div class="progress-bar <?= $token_limit > 0 && $tokens_used >= $token_limit ? 'bg-danger' : '' ?>"
                style="width: <?= $token_bar_percentage ?>%"></div>
        </div>
    </div>
    <form @submit.prevent="getAiVariablesConfig">
        <fieldset v-bind:disabled="loading || descripcion.loading || aiVariablesConfig.loading">
            <h2 id="priority-ai-config-title"><label for="priority-ai-config-prompt">¿Qué quieres priorizar?</label></h2>
            <div class="geofocus-priority-assistant-input">
                <textarea id="priority-ai-config-prompt" name="prompt" class="form-control" rows="6"
                    v-model="aiVariablesConfig.prompt" required aria-describedby="priority-ai-config-help"
                    placeholder="Por ejemplo: barrios con menor oferta cultural y mayor población infantil, dando más peso a la oferta cultural."></textarea>
                <div class="geofocus-priority-assistant-buttons">
                    <!-- 2026-10-12: ejemplo aleatorio con icono y tooltip; la selección con IA se alinea a la derecha. -->
                    <button type="button" class="btn btn-light geofocus-priority-example" v-on:click="setRandomPromptExample"
                        v-bind:disabled="!promptsExamples.length" aria-label="Usar un prompt de ejemplo al azar"
                        title="Coloca un prompt de ejemplo al azar en el campo. Puedes editarlo antes de seleccionar con IA.">
                        <i class="fas fa-random" aria-hidden="true"></i>
                    </button>
                    <button type="submit" class="btn btn-outline-secondary"
                        v-bind:disabled="!aiVariablesConfig.prompt.trim()">
                        <span v-if="aiVariablesConfig.loading">
                            <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                            Seleccionando…
                        </span>
                        <span v-else>Seleccionar con IA</span>
                    </button>
                </div>
            </div>
        </fieldset>
    </form>
    <aside class="geofocus-priority-assistant-info" aria-label="Información de la selección con IA">
        <p id="priority-ai-config-help" class="geofocus-priority-help">
            La IA reemplazará la selección, el sentido y la ponderación. Después puedes ajustarlos y pulsar <strong>Calcular</strong> para guardar y obtener los resultados.
        </p>
        <p v-if="aiVariablesConfig.error" class="text-danger mt-3 mb-0" role="alert">{{ aiVariablesConfig.error }}</p>
        <div v-else-if="aiVariablesConfig.mensaje" class="geofocus-priority-assistant-feedback" role="status" aria-live="polite">
            <p>{{ aiVariablesConfig.mensaje }}</p>
            <p v-if="aiVariablesConfig.observaciones" class="text-muted">{{ aiVariablesConfig.observaciones }}</p>
        </div>
    </aside>
</section>
