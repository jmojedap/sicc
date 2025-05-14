<fieldset v-bind:disabled="loading">
    <input type="hidden" name="laboratorio_id" value="<?= $row->id ?>">
    <input type="hidden" name="tipo_detalle" value="17101">

    <div class="mb-3 row">
        <div class="col-md-8 offset-md-4 text-end">
            <button class="btn btn-primary w120p" type="submit">
                Guardar
            </button>
        </div>
    </div>

    <div class="mb-1 row">
        <label for="titulo_3" class="col-md-4 col-form-label text-end text-right">Número actividad</label>
        <div class="col-md-8">
            <input name="titulo_3" type="text" class="form-control" title="Interacción" placeholder="Interacción"
                v-model="fields.titulo_3" maxlength="5">
        </div>
    </div>

    <div class="mb-1 row">
        <label for="nombre" class="col-md-4 col-form-label text-end text-right">Nombre actividad</label>
        <div class="col-md-8">
            <input name="nombre" type="text" class="form-control" required title="Nombre actividad"
                placeholder="Nombre actividad" v-model="fields.nombre">
        </div>
    </div>

    <div class="mb-1 row">
        <label for="fecha_1" class="col-md-4 col-form-label text-end text-right">Fecha</label>
        <div class="col-md-8">
            <input name="fecha_1" type="date" class="form-control" required title="Fecha" v-model="fields.fecha_1">
        </div>
    </div>

    <div class="mb-1 row">
        <label for="hora_1" class="col-md-4 col-form-label text-end text-right">Hora</label>
        <div class="col-md-4">
            <input
                name="hora_1" type="time" class="form-control"
                required
                title="Hora de inicio"
                v-model="fields.hora_1"
            >
        </div>
        <div class="col-md-4">
            <input
                name="hora_2" type="time" class="form-control" title="Hora de finalización"
                required
                v-model="fields.hora_2"
            >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="categoria_1" class="col-md-4 col-form-label text-end text-right">Fase Barrios Vivos</label>
        <div class="col-md-8">
            <select name="categoria_1" v-model="fields.categoria_1" class="form-select form-control" required>
                <option v-for="optionFase in arrFase" v-bind:value="optionFase.name">{{ optionFase.name }}</option>
            </select>
        </div>
    </div>

    <div class="mb-1 row">
        <label for="titulo_2" class="col-md-4 col-form-label text-end text-right">Fase laboratorio</label>
        <div class="col-md-8">
            <input name="titulo_2" type="text" class="form-control" title="Fase laboratorio"
                placeholder="Fase laboratorio" v-model="fields.titulo_2">
            <small class="form-text">Nombre que tiene la fase que agrupa interacciones o sesiones de acuerdo con la
                metodología particular del laboratorio.
            </small>
        </div>
    </div>

    <div class="mb-1 row">
        <label for="texto_2" class="col-md-4 col-form-label text-end text-right">Lugar</label>
        <div class="col-md-8">
            <input
                name="texto_2" type="text" class="form-control"
                title="Lugar" placeholder="Ej. Casa de la Cultura"
                v-model="fields.texto_2"
            >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="texto_3" class="col-md-4 col-form-label text-end text-right">Dirección</label>
        <div class="col-md-8">
            <input
                name="texto_3" type="text" class="form-control"
                title="Dirección" placeholder="Ej. Calle 1 # 2-3"
                v-model="fields.texto_3"
            >
        </div>
    </div>

    <div class="mb-3 row">
        <label for="descripcion" class="col-md-4 col-form-label text-end text-right">Descripción</label>
        <div class="col-md-8">
            <textarea
                name="descripcion" class="form-control" rows="3"
                title="Descripción" placeholder="Añade una descripción de la actividad"
                v-model="fields.descripcion"
            ></textarea>
        </div>
    </div>

    <div class="mb-1 row">
        <label for="entero_1" class="col-md-4 col-form-label text-end text-right">Participantes | H | M | ND |</label>
        <div class="col-md-2">
            <input
                name="entero_1" type="number" class="form-control" min="0" step="1" title="Hombres"
                v-model="fields.entero_1"
            >
        </div>
        <div class="col-md-2">
            <input
                name="entero_2" type="number" class="form-control" min="0" step="1" title="Mujeres"
                v-model="fields.entero_2"
            >
        </div>
        <div class="col-md-2">
            <input
                name="entero_3" type="number" class="form-control" min="0" step="1" title="Sexo no disponible"
                v-model="fields.entero_3"
            >
        </div>
        <div class="col-md-1">
            {{ Number(fields.entero_1 || 0) + Number(fields.entero_2 || 0) + Number(fields.entero_3 || 0) }}
        </div>
    </div>

    <div class="mb-3 row">
        <label for="num_radicacion" class="col-md-4 col-form-label text-end text-right">Radicado ORFEO</label>
        <div class="col-md-8">
            <input
                name="num_radicacion" type="text" class="form-control"
                title="Radicado ORFEO" placeholder="Radicado ORFEO"
                v-model="fields.num_radicacion"
            >
        </div>
    </div>



    <div class="mb-1 row">
        <label for="url_1" class="col-md-4 col-form-label text-end text-right">Link evidencia</label>
        <div class="col-md-8">
            <input
                name="url_1" type="url" class="form-control"
                
                title="Link evidencia" placeholder="Link evidencia"
                v-model="fields.url_1"
            >
        </div>
    </div>
    <div class="mb-1 row">
        <label for="url_2" class="col-md-4 col-form-label text-end text-right">Link presentación</label>
        <div class="col-md-8">
            <input
                name="url_2" type="url" class="form-control"
                
                title="Link presentación" placeholder="Link presentación"
                v-model="fields.url_2"
            >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="url_3" class="col-md-4 col-form-label text-end text-right">Link asistencia</label>
        <div class="col-md-8">
            <input
                name="url_3" type="url" class="form-control"
                
                title="Link asistencia" placeholder="Link asistencia"
                v-model="fields.url_3"
            >
        </div>
    </div>

    <div class="mb-1 row">
        <label for="url_4" class="col-md-4 col-form-label text-end text-right">Link carpeta archivos</label>
        <div class="col-md-8">
            <input
                name="url_4" type="url" class="form-control"
                
                title="Link carpeta archivos" placeholder="Link carpeta archivos"
                v-model="fields.url_4"
            >
        </div>
    </div>

    <div class="mb-3 row">
        <label for="notas" class="col-md-4 col-form-label text-end text-right">Notas</label>
        <div class="col-md-8">
            <textarea
                name="notas" class="form-control" rows="4"
                title="Notas" placeholder="Notas adicionales sobre la actividad"
                v-model="fields.notas"
            ></textarea>
        </div>
    </div>

<fieldset>