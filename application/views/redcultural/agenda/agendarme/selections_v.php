<div class="mb-3 row">
    <div class="col-md-8 offset-md-4">
        <button class="btn btn-primary w120p" type="button" v-on:click="step = 'group'">Atrás</button>
    </div>
</div>

<div class="mb-3 row">
    <label for="email" class="col-md-4 col-form-label text-end text-right">Correo electrónico</label>
    <div class="col-md-8">
        <input name="email" type="email" class="form-control" required v-on:change="setDisplayName"
            title="Correo electrónico" placeholder="Correo electrónico" v-model="fields.email">
        <small class="text-muted">Con el que se registró en el directorio</small>
    </div>
</div>
<div class="mb-3 row">
    <label for="display_name" class="col-md-4 col-form-label text-end text-right">Nombre y apellido</label>
    <div class="col-md-8">
        <input name="display_name" type="text" class="form-control" required title="Nombre y apellido"
            placeholder="Nombre y apellido" v-model="fields.display_name">
    </div>
</div>

<hr>

<div class="mb-3 row">
    <label for="viernes_tarde" class="col-md-4 col-form-label text-end text-right color-text-1">
        Viernes 19 sept.<br>
        <small class="text-muted">Jordada de la tarde</small>
    </label>
    <div class="col-md-8">
        Seleccione una de las siguientes opciones de mesas temáticas en las que desea participar:
        <br>
        <small class="text-muted">En caso de que el equipo organizador le haya contactado para participar como moderador o activador de alguna mesa, seleccionela.</small>
    </div>
</div>


<div class="mb-3 row">
    <label for="viernes_tarde" class="col-md-4 col-form-label text-end text-right">Opción 1</label>
    <div class="col-md-8">
        
        <select name="viernes_tarde" v-model="fields.viernes_tarde" class="form-select form-control" required>
            <option v-for="optionViernesTarde in mesasViernesTarde" v-bind:value="optionViernesTarde.title">
                {{ optionViernesTarde.title }}</option>
        </select>
    </div>
</div>

<div class="mb-3 row">
    <label for="viernes_tarde_opcion_2" class="col-md-4 col-form-label text-end text-right">Opción 2</label>
    <div class="col-md-8">
        <small class="text-muted">En caso que el aforo de su primera selección se complete</small>
        <select name="viernes_tarde_opcion_2" v-model="fields.viernes_tarde_opcion_2" class="form-select form-control"
            required>
            <option v-for="optionViernesTarde in mesasViernesTarde" v-bind:value="optionViernesTarde.title">
                {{ optionViernesTarde.title }}</option>
        </select>

    </div>
</div>

<hr>

<div class="mb-3 row">
    <label for="viernes_tarde" class="col-md-4 col-form-label text-end text-right color-text-1">
        Sábado 20 sept.
    </label>
    <div class="col-md-8">
        Seleccione una de las siguientes opciones de mesas temáticas en las que desea participar:
        <br>
        <small class="text-muted">En caso de que el equipo organizador le haya contactado para participar como moderador o activador de alguna mesa, seleccionela.</small>
    </div>
</div>

<div class="mb-3 row">
    <label for="sabado_manana_opcion_1" class="col-md-4 col-form-label text-end text-right">Opción 1</label>
    <div class="col-md-8">
        <select name="sabado_manana_opcion_1" v-model="fields.sabado_manana_opcion_1" class="form-select form-control"
            required>
            <option v-for="optionSabado in mesasSabado" v-bind:value="optionSabado.title" v-show="optionSabado.grupo == fields.grupo_edad">{{ optionSabado.title }}
            </option>
        </select>
    </div>
</div>


<div class="mb-3 row" v-show="fields.grupo_edad != 'jovenes'">
    <label for="sabado_manana_opcion_2" class="col-md-4 col-form-label text-end text-right">Opción 2</label>
    <div class="col-md-8">
        <small class="text-muted">En caso que el aforo de su primera selección se complete</small>
        <select name="sabado_manana_opcion_2" v-model="fields.sabado_manana_opcion_2" class="form-select form-control"
            required>
            <option v-for="optionSabado in mesasSabado" v-bind:value="optionSabado.title" v-show="optionSabado.grupo == fields.grupo_edad">{{ optionSabado.title }}
            </option>
        </select>
    </div>
</div>

<hr v-show="fields.grupo_origen != 'bogota'">

<div class="mb-3 row" v-show="fields.grupo_origen != 'bogota'">
    <label for="recorrido_domingo" class="col-md-4 col-form-label text-end text-right color-text-1">
        Recorrido del domingo 21
    </label>
    <div class="col-md-4">
        <select name="recorrido_domingo" v-model="fields.recorrido_domingo" class="form-select form-control" required>
            <option v-for="optionDomingo in recorridosDomingo" v-bind:value="optionDomingo.title">
                {{ optionDomingo.title }}</option>
        </select>
    </div>
    <div class="col-md-4">
        <p v-html="recorridoDescripcion"></p>
    </div>
</div>

<div class="mb-3 row">
    <div class="col-md-8 offset-md-4">
        <button class="btn btn-primary w120p" type="submit">Guardar</button>
    </div>
</div>