<script>
// VueApp
//-----------------------------------------------------------------------------
var repositorioInicioApp = createApp({
    data(){
        return{
            loading: false,
            fields: {},
            currentContenido: {},
            contenidos: <?= json_encode($list) ?>,
            arrFormato: <?= json_encode($arrFormato) ?>,
            arrSubtema: <?= json_encode($arrSubtema) ?>,
            arrArea: <?= json_encode($arrArea) ?>,
        }
    },
    methods: {
        setCurrent: function(contenidoId){
            this.currentContenido = this.contenidos.find(item => item.id == contenidoId)
        },
        setRandomCurrent(){
            var longitud = 20
            var randomKey = Math.floor(Math.random() * longitud)
            this.currentContenido = this.contenidos[randomKey]
        },
        formatoName: function(value = '', field = 'name'){
            var formatoName = ''
            var item = this.arrFormato.find(row => row.cod == value)
            if ( item != undefined ) formatoName = item[field]
            return formatoName
        },
        subtemaName: function(value = '', field = 'name'){
            var subtemaName = ''
            var item = this.arrSubtema.find(row => row.cod == value)
            if ( item != undefined ) subtemaName = item[field]
            return subtemaName
        },
        areaName: function(value = '', field = 'name'){
            var areaName = ''
            var item = this.arrArea.find(row => row.cod == value)
            if ( item != undefined ) areaName = item[field]
            return areaName
        },
    },
    mounted(){
        this.setRandomCurrent()
    }
}).mount('#repositorioInicioApp')


// Slick Carousel
//-----------------------------------------------------------------------------
$(document).ready(function(){
    $('.repo-carousel').slick({
        infinite: false,
        arrows: true,
        prevArrow: '<div class="slick-prev mr-3"> <div class="btn btn-circle btn-light d-flex justify-content-center align-items-center"><div><i class="fa fa-chevron-left"></div></div></div>',
        nextArrow: '<div class="slick-next"> <div class="btn btn-circle btn-light d-flex justify-content-center align-items-center"><div><i class="fa fa-chevron-right"></div></div></div>',
        dots: false,
        autoplay: false,
        speed: 1100,
        slidesToShow: 7,
        slidesToScroll: 6,
        responsive: [
            {
                breakpoint: 1500,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 4
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 1100,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 800,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 420,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});
</script>

<script src='https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js'></script>