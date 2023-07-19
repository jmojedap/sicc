var nav_1_elements = [
    {
        text: 'Repositorio',
        active: false,
        icon: '',
        cf: '',
        subelements: [
            {
                text: 'Inicio',
                active: false,
                icon: '',
                cf: 'repositorio/inicio/',
                sections: ['repositorio/inicio'],
                anchor: true
            },
            {
                text: 'Explorar',
                active: false,
                icon: '',
                cf: 'repositorio/explorar/1/',
                sections: ['repositorio/explorar', 'repositorio/detalles', 'repositorio/edit', 'repositorio/crear'],
                anchor: true
            },
            {
                text: 'Documentación',
                active: false,
                icon: '',
                cf: 'repositorio/especificaciones',
                sections: ['repositorio/especificaciones'],
                anchor: true
            },
        ],
        sections: [],
        anchor: true
    },
    {
        text: 'Analítica',
        active: false,
        icon: '',
        cf: '',
        subelements: [
            {
                text: 'Mediciones',
                active: false,
                icon: '',
                cf: 'mediciones/explorar',
                sections: ['mediciones/explorar', 'mediciones/detalles', 'mediciones/edit', 'mediciones/powerbi',
                    'mediciones/formulario'
                ],
                anchor: true
            },
            {
                text: 'Diccionarios',
                active: false,
                icon: '',
                cf: 'mediciones/diccionario_de_datos',
                sections: ['mediciones/diccionario_de_datos'],
                anchor: true
            },
        ],
        sections: ['mediciones/explorar', 'mediciones/diccionario_de_datos', 'mediciones/detalles'],
        anchor: true
    },
    {
        text: 'Monitoreo ECC',
        active: false,
        icon: '',
        cf: '',
        subelements: [
            {
                text: 'Acciones CC',
                active: false,
                icon: '',
                cf: 'acciones/explorar/1/?m=202306&',
                sections: ['acciones/explorar', 'acciones/info', 'acciones/edit', 'acciones/add', 'acciones/usuarios', 'acciones/asistentes_itinerantes'],
                anchor: true
            },
            {
                text: 'Diccionarios',
                active: false,
                icon: '',
                cf: 'acciones/diccionario_de_datos',
                sections: ['acciones/diccionario_de_datos'],
                anchor: true
            },
            {
                text: 'Registrar participante',
                active: false,
                icon: '',
                cf: 'acciones/registro_usuario',
                sections: ['acciones/registro_usuario'],
                anchor: true
            },
        ],
        sections:[],
        anchor: true
    },
    {
        text: 'Otros',
        active: false,
        icon: '',
        cf: '',
        subelements: [
            {
                text: 'Variables y opciones',
                active: false,
                icon: '',
                cf: 'parametros/valores',
                sections: ['parametros/valores'],
                anchor: true
            },
            {
                text: 'Tableros Power Bi',
                active: false,
                icon: '',
                cf: 'info/visualizaciones_datos',
                sections: ['info/visualizaciones_datos'],
                anchor: true
            },
            {
                text: 'Asistentes LEO Filbo 2023',
                active: false,
                icon: '',
                cf: 'data_science/filbo2023',
                sections: ['data_science/filbo2023'],
                anchor: true
            },
            {
                text: 'Noticias',
                active: false,
                icon: '',
                cf: 'noticias/explorar',
                sections: ['noticias/explorar', 'noticias/revisar'],
                anchor: true
            },
            {
                text: 'Home CultuRed_Bogotá',
                active: false,
                icon: '',
                cf: 'app/cultured_bogota',
                sections: ['app/cultured_bogota'],
                anchor: true
            }
        ],
        sections: [],
        anchor: true
    }
];