<form accept-charset="utf-8" method="POST" id="fileForm" @submit.prevent="handleSubmit">
    <fieldset v-bind:disabled="loading">
        <div class="mb-3 row">
            <div class="col-md-8 mb-2">
                <input id="field-file" type="file" ref="file_field" name="file_field" required class="form-control"
                    v-on:change="handleFileUpload()"
                    >
            </div>
            <div class="col-md-4">
                <button class="btn w-100" type="submit"
                    v-bind:class="{'btn-success': file !== null, 'btn-secondary': file == null }"
                    v-bind:disabled="file == null"
                    >
                    <span v-show="!loading">Cargar</span>
                    <span v-show="loading">Cargando...</span>
                </button>
            </div>
        </div>
    <fieldset>
</form>

<div id="upload_response"></div>