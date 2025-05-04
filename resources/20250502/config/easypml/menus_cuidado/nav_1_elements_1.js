var nav_1_elements = [
    {
        text: 'Contenidos',
        active: false,
        icon: 'fa fa-user',
        cf: 'contenidos/explorar',
        subelements: [],
        sections: ['contenidos/explorar', 'contenidos/leer'],
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
                sections: ['mediciones/explorar', 'mediciones/detalles', 'mediciones/edit', 'mediciones/powerbi'],
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
        text: 'Monitoreo',
        active: false,
        icon: '',
        cf: '',
        subelements: [
            {
                text: 'Acciones CC',
                active: false,
                icon: '',
                cf: 'acciones/explorar',
                sections: ['acciones/explorar', 'acciones/info', 'acciones/edit', 'acciones/add'],
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
        ],
        sections:[],
        anchor: true
    },
    {
        text: 'Configuración',
        active: false,
        icon: '',
        cf: '',
        subelements: [
            {
                text: 'Parámetros',
                active: false,
                icon: '',
                cf: 'parametros/valores',
                sections: ['parametros/valores'],
                anchor: true
            },
        ],
        sections: ['parametros/valores'],
        anchor: true
    },
    {
        text: 'Ejecución',
        active: false,
        icon: '',
        cf: '',
        subelements: [
            {
                text: 'Obligaciones',
                active: false,
                icon: '',
                cf: 'ejecucion/obligaciones',
                sections: ['ejecucion/obligaciones'],
                anchor: true
            },
            {
                text: 'Plan de acción',
                active: false,
                icon: '',
                cf: 'ejecucion/plan_accion',
                sections: ['ejecucion/plan_accion'],
                anchor: true
            },
            {
                text: 'Bitácora',
                active: false,
                icon: '',
                cf: 'ejecucion/bitacora',
                sections: ['ejecucion/bitacora'],
                anchor: true
            },
        ],
        sections: [],
        anchor: true
    },
    {
        text: 'Prototipos',
        active: false,
        icon: '',
        cf: '',
        subelements: [
            {
                text: 'Noticias',
                active: false,
                icon: '',
                cf: 'noticias/explorar',
                sections: ['noticias/explorar', 'noticias/revisar'],
                anchor: true
            },
        ],
        sections: [],
        anchor: true
    },
];