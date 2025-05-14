<div id="cartasApp">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="list-group">
                    <a class="list-group-item list-group-item-action" v-for="localidad in localidades"
                        v-bind:href="`<?= URL_APP . "barrios_vivos/cartas_localidad/" ?>` + localidad.localidad_cod"
                        >
                        {{ localidad.localidad }}
                    </a>
                </div>
            </div>
            <div class="col-md-8">
                <div class="center_box_750">
                    <div class="card">
                        <div class="card-body p-5">
                            <p>
                                Bogotá, D.C., 22 de agosto de 2024
                            </p>

                            Señor(a)
                            <br>
                            <strong>{{ currLocalidad.nombre_alcalde_local }}</strong>
                            <br>
                            Alcalde(sa) Local {{ currLocalidad.localidad }}
                            <br>
                            Ciudad
                            <br>

                            <p>
                                Reciba un especial saludo.
                            </p>

                            <p>
                                Desde la Secretaría de Cultura, Recreación y Deporte (SCRD) queremos manifestarle
                                nuestro compromiso por
                                el trabajo en red con su administración local y poner a su disposición toda nuestra
                                capacidad
                                institucional para que Bogotá siga siendo nuestra Ciudad, nuestra Casa.
                            </p>
                            <p>
                                En esta oportunidad, desde mi despacho quiero presentarle la Estrategia de Innovación
                                Cultural Barrios
                                Vivos: Acordemos estar de Acuerdo, que tiene por objetivo potenciar la acción cultural
                                integral en los
                                barrios de Bogotá, por medio de laboratorios de oportunidades que impulsan experiencias
                                locales exitosas
                                y de laboratorios de transformación cultural, recreativa y deportiva que aportan
                                soluciones a
                                problemáticas sociales específicas en los territorios. Todo esto con el fin de que la
                                vida cultural
                                basada en un enfoque comunitario y en proximidad, sea la promotora de una sociedad más
                                segura,
                                cohesionada y diversa.
                            </p>
                            <p>
                                En este sentido, Barrios Vivos llega a su localidad el próximo sábado 31 de agosto de
                                2024 de 9:00am a
                                12:00m (lugar) y pretende en el marco de un proceso colaborativo y de cocreación con las
                                comunidades,
                                brindar apoyo desde la SCRD a esas oportunidades barriales exitosas y a esas soluciones
                                frente a
                                problemáticas sociales y culturales. En todo este proceso, la Alcaldía jugará un rol
                                trascendental. Por
                                esta razón, queremos invitarle a usted y a la comunidad de la localidad, a esta
                                interacción barrial.

                            </p>

                            <p>
                                Esperamos contar con la participación de miembros de su administración y por favor
                                siéntase libre de
                                convocar a la comunidad en general a participar para continuar por la senda de la
                                diversidad que nos
                                une.
                            </p>
                            <p>
                                Para cualquier detalle puede contactarse con (email y celular del gerente o miembro de
                                la dupla)
                            </p>
                            <p>
                                Con sentimientos de gratitud y aprecio.
                            </p>
                            <p>
                                Cordialmente,
                            </p>
                            <p>
                                <strong>
                                    Santiago Trujillo
                                </strong>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php $this->load->view('app/barrios_vivos/cartas_localidad/vue_v') ?>