<?php $this->load->view('app/geofocus/geofocus_style_v') ?>

<div id="capasBaseApp" class="geofocus-base-page">
    <div class="geofocus-base-layout">
        <aside class="geofocus-base-sidebar" aria-labelledby="geofocus-base-list-title">
            <div class="geofocus-base-sidebar-header">
                <span class="geofocus-base-kicker">Configuración territorial</span>
                <h2 id="geofocus-base-list-title">Esquemas territoriales</h2>
                <p>
                    Selecciona el conjunto de polígonos sobre el que se proyectan las variables.
                </p>
            </div>

            <div class="geofocus-base-options" role="list">
                <button
                    v-for="capa in capasBase"
                    v-bind:key="capa.key_capa"
                    type="button"
                    class="geofocus-base-option"
                    v-bind:class="{ 'is-active': isCurrentCapa(capa) }"
                    v-on:click="selectCapa(capa)"
                    v-bind:aria-pressed="isCurrentCapa(capa)"
                >
                    <span class="geofocus-base-option-icon" aria-hidden="true">
                        <i class="fas fa-layer-group"></i>
                    </span>
                    <span class="geofocus-base-option-copy">
                        <strong>{{ capa.nombre }}</strong>
                        <small>{{ capa.key_capa }}</small>
                    </span>
                    <i class="fas fa-chevron-right geofocus-base-option-arrow" aria-hidden="true"></i>
                </button>

                <p v-if="capasBase.length === 0" class="geofocus-base-empty">
                    No hay esquemas territoriales disponibles.
                </p>
            </div>
        </aside>

        <main v-if="currentCapa" class="geofocus-base-content">
            <header class="geofocus-base-content-header">
                <div>
                    <span class="geofocus-base-kicker">Esquema seleccionado</span>
                    <h1>{{ currentCapa.nombre }}</h1>
                    <p>
                        Este esquema define la unidad territorial de referencia para asociar y consultar
                        variables calculadas por polígono.
                    </p>
                </div>
                <span class="geofocus-base-status">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                    Actual
                </span>
            </header>

            <section class="geofocus-base-summary" aria-label="Datos del esquema territorial">
                <div class="geofocus-base-summary-item">
                    <span>Identificador</span>
                    <strong>{{ displayValue(currentCapa.id) }}</strong>
                </div>
                <div class="geofocus-base-summary-item geofocus-base-summary-item-wide">
                    <span>Clave de relación</span>
                    <strong>{{ displayValue(currentCapa.key_capa) }}</strong>
                </div>
                <div class="geofocus-base-summary-item geofocus-base-summary-item-accent">
                    <span>Variables disponibles</span>
                    <strong>{{ variablesDeCapa.length }}</strong>
                </div>
            </section>

            <section class="geofocus-base-variables" aria-labelledby="geofocus-variables-title">
                <div class="geofocus-base-section-heading">
                    <div>
                        <span class="geofocus-base-kicker">Contenido asociado</span>
                        <h2 id="geofocus-variables-title">Variables incluidas</h2>
                    </div>
                    <span class="geofocus-base-count">
                        {{ variablesDeCapa.length }} variable<span v-if="variablesDeCapa.length !== 1">s</span>
                    </span>
                </div>

                <div class="table-responsive geofocus-base-table-container">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">ID</th>
                                <th scope="col">Clave</th>
                                <th scope="col">Variable</th>
                                <th scope="col">Tema</th>
                                <th scope="col" class="text-center">Año</th>
                                <th scope="col">Unidad de medida</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="variable in variablesDeCapa" v-bind:key="variable.id">
                                <td class="text-center">
                                    <strong>{{ displayValue(variable.id) }}</strong>
                                </td>
                                <td><code>{{ displayValue(variable.clave) }}</code></td>
                                <td><span class="geofocus-base-variable-name">{{ displayValue(variable.nombre) }}</span></td>
                                <td>{{ displayValue(variable.tema) }}</td>
                                <td class="text-center">{{ displayValue(variable.anio_valores) }}</td>
                                <td>{{ displayValue(variable.unidad_medida) }}</td>
                            </tr>
                            <tr v-if="variablesDeCapa.length === 0">
                                <td colspan="6" class="text-center text-muted py-4">
                                    Este esquema aún no tiene variables asociadas.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>

        <main v-else class="geofocus-base-content geofocus-base-no-selection">
            <i class="fas fa-layer-group" aria-hidden="true"></i>
            <h1>Sin esquemas territoriales</h1>
            <p>No hay una capa base disponible para consultar.</p>
        </main>
    </div>
</div>

<script>
var capasBaseApp = createApp({
    data(){
        return{
            loading: false,
            capasBase: <?= json_encode(
                $capasBase,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ) ?>,
            variables: <?= json_encode(
                $variables,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ) ?>,
            currentKeyCapa: null,
        }
    },
    computed: {
        currentCapa: function(){
            return this.capasBase.find(capa => String(capa.key_capa) === String(this.currentKeyCapa)) || null
        },
        variablesDeCapa: function(){
            if ( ! this.currentCapa ) return []

            return this.variables.filter(variable =>
                String(variable.key_capa) === String(this.currentCapa.key_capa)
            )
        }
    },
    methods: {
        selectCapa: function(capa){
            this.currentKeyCapa = capa.key_capa
        },
        isCurrentCapa: function(capa){
            return String(capa.key_capa) === String(this.currentKeyCapa)
        },
        displayValue: function(value){
            return value === null || value === undefined || value === '' ? '—' : value
        }
    },
    mounted(){
        if ( this.capasBase.length > 0 ) {
            this.currentKeyCapa = this.capasBase[0].key_capa
        }
    }
}).mount('#capasBaseApp')
</script>