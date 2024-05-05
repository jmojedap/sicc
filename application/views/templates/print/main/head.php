<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= $head_title ?></title>
<link rel="shortcut icon" href="<?= URL_BRAND ?>favicon.png"> 

<!-- Bootstrap CSS 5.1.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

<!-- Font Awesome -->
<script src="https://kit.fontawesome.com/f45fca298e.js" crossorigin="anonymous"></script>

<!-- Vue.js -->
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.0.0-alpha.1/axios.min.js" integrity="sha512-xIPqqrfvUAc/Cspuj7Bq0UtHNo/5qkdyngx6Vwt+tmbvTLDszzXM0G6c91LXmGrRx8KEPulT+AfOOez+TeVylg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>const {createApp} = Vue;</script>

<!-- Moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.js" integrity="sha256-H9jAz//QLkDOy/nzE9G4aYijQtkLt9FvGmdUTwBk6gs=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/es.js" integrity="sha256-bETP3ndSBCorObibq37vsT+l/vwScuAc9LRJIQyb068=" crossorigin="anonymous"></script>

<!-- PML Tools -->
<link type="text/css" rel="stylesheet" href="<?= URL_RESOURCES . 'css/pacarina.css' ?>">
<script src="<?= URL_RESOURCES . 'js/pcrn.js' ?>"></script>
<script>
    const url_app = '<?= URL_APP ?>'; const url_api = '<?= URL_API ?>'; const url_front= '<?= URL_FRONT ?>';
    var app_cf = '<?= $this->uri->segment(2) . '/' . $this->uri->segment(3); ?>';

    <?php if ( $this->session->userdata('logged') ) : ?>    
        const app_rid = '<?= $this->session->userdata('role') ?>';
        const app_uid = '<?= $this->session->userdata('user_id') ?>';
    <?php else: ?>
        const app_rid = 99;
        const app_uid = 0;
    <?php endif; ?>
</script>

<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/print/theme-dogcc.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/print/style.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/print/app.css">
<link rel="stylesheet" href="<?= URL_RESOURCES ?>templates/print/mobile.css">