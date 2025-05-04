<?php $this->load->view('app/observatorio/demo/ambiente_style_v') ?>

<div id="demoAmbiente">
    <div class="center_box_920">
        <p class="lead text-center">
            Tabla de Criterios para Priorizar Puntos Críticos de Arrojo de Basuras en Bogotá
        </p>

        <div class="mb-2 d-flex justify-content-between">
            <div class="py-2">
                Cantidad de criterios:
                <span class="text-primary">
                    {{ criterios.length }}
                </span>
            </div>
            <div>
                <button class="btn btn-main btn-lg" v-on:click="validateSubmit">
                    Calcular
                </button>
            </div>
        </div>
        <table class="table bg-white">
            <thead>
                <th>Criterio</th>
                <th>Peso</th>
                <th></th>
            </thead>
            <tbody>
                <tr v-for="(criterio, key) in criterios">
                    <td>
                        <b class="text-main">
                            {{ criterio.nombre }}
                        </b>
                        <p class="">
                            {{ criterio.descripcion }}
                        </p>
                    </td>
                    <td>
                        {{ criterio.peso }}
                    </td>
                    <td>
                        <input class="range" type="range" min="1" max="5" v-model="criterio.peso"
                        class="slider w-100" v-bind:name="criterio.nombre">
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
var demoAmbiente = createApp({
    data(){
        return{
            loading: false,
            criterios: <?= json_encode($criterios) ?>,
            fields: {},
        }
    },
    methods: {
        
    },
    mounted(){
        //this.getList()
    }
}).mount('#demoAmbiente')
</script>