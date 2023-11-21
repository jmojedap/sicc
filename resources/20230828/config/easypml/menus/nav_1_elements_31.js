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
                text: 'Diccionarios',
                active: false,
                icon: '',
                cf: 'mediciones/diccionario_de_datos',
                sections: ['mediciones/diccionario_de_datos'],
                anchor: true
            },
        ],
        sections: [],
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