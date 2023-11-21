var nav_1_elements = [
    {
        text: 'Observatorio',
        active: false,
        icon: '',
        cf: 'observatorio/inicio',
        subelements: [],
        sections: ['observatorio/inicio', 'observatorio/mapas', 'observatorio/pai'],
        anchor: true
    },
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
        sections: ['mediciones/explorar', 'mediciones/diccionario_de_datos'],
        anchor: true
    },
    {
        text: 'Configuración',
        active: false,
        icon: '',
        cf: '',
        subelements: [
            {
                text: 'Variables',
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
];