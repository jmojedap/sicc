<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= $head_title ?></title>
<link rel="shortcut icon" href="<?= RCI_URL_BRAND ?>favicon.png"> 


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<!-- Bootstrap CSS 5.2.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>

<!-- Font Awesome -->
<script src="<?= URL_RESOURCES ?>js/fa_f45fca298e.js"></script>

<!-- Vue.js -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0-alpha.1/axios.min.js" integrity="sha512-xIPqqrfvUAc/Cspuj7Bq0UtHNo/5qkdyngx6Vwt+tmbvTLDszzXM0G6c91LXmGrRx8KEPulT+AfOOez+TeVylg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>const {createApp} = Vue;</script>

<!-- Alertas y notificaciones -->
<link href="<?= URL_RESOURCES ?>templates/redcultural/toastr.css" rel="stylesheet" type="text/css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="<?= URL_RESOURCES ?>config/admin_pml/toastr-options.js"></script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js" integrity="sha256-H9jAz//QLkDOy/nzE9G4aYijQtkLt9FvGmdUTwBk6gs=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js" integrity="sha256-bETP3ndSBCorObibq37vsT+l/vwScuAc9LRJIQyb068=" crossorigin="anonymous"></script>

<!-- PML Tools -->
<link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES . 'css/pacarina.css' ?>">
<script src="<?= URL_RESOURCES . 'js/pcrn.js' ?>"></script>
<script>
    const URL_APP = '<?= RCI_URL_APP ?>'; const URL_API = '<?= URL_API ?>'; const RCI_URL_FRONT= '<?= URL_FRONT ?>';
    var app_cf = '<?= $this->uri->segment(2) . '/' . $this->uri->segment(3); ?>';

    <?php if ( $this->session->userdata('logged') ) : ?>    
        const APP_RID = '<?= $this->session->userdata('role') ?>';
        const APP_UID = '<?= $this->session->userdata('user_id') ?>';
    <?php else: ?>
        const APP_RID = 99;
        const APP_UID = 0;
    <?php endif; ?>
</script>
<script src="<?= URL_RESOURCES ?>js/bs5_routing.js"></script>


<!-- navbar elements -->
<script src="<?= URL_RESOURCES ?>config/redcultural/menus/nav_1_elements_<?= $this->session->userdata('role') ?>.js"></script>

<link rel="stylesheet" href="<?= URL_RESOURCES ?>css/sicc/app.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/redcultural/style.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/redcultural/theme-v1.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/redcultural/app.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/redcultural/mobile.css">