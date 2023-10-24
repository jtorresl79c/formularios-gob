<html>
    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/notifier.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    <style>
body {
  background-color: #fff;
  font-family: 'Karla', sans-serif; }
  
h1 > a {
    text-decoration:none;
    color:#fff !important;
}

.intro-section {
    background-image: url('storage/uploads/appLogo/auth_principal.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  padding: 75px 95px;
  min-height: 100vh;
  display: -webkit-box;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
          flex-direction: column;
  color: #ffffff; }
  @media (max-width: 991px) {
    .intro-section {
      padding-left: 50px;
      padding-rigth: 50px; } }
  @media (max-width: 767px) {
    .intro-section {
      padding: 28px; } }
  @media (max-width: 575px) {
    .intro-section {
      min-height: auto; } }

.brand-wrapper .logo {
  height: 35px; }

@media (max-width: 767px) {
  .brand-wrapper {
    margin-bottom: 35px; } }

.intro-content-wrapper {
  width: 410px;
  max-width: 100%;
  margin-top: auto;
  margin-bottom: auto; }
  .intro-content-wrapper .intro-title {
    font-size: 40px;
    font-weight: bold;
    margin-bottom: 17px; }
  .intro-content-wrapper .intro-text {
    font-size: 19px;
    line-height: 1.37; }
  .intro-content-wrapper .btn-read-more {
    background-color: #fff;
    padding: 13px 30px;
    border-radius: 0;
    font-size: 16px;
    font-weight: bold;
    color: #000; }
    .intro-content-wrapper .btn-read-more:hover {
      background-color: transparent;
      border: 1px solid #fff;
      color: #fff; }

@media (max-width: 767px) {
  .intro-section-footer {
    margin-top: 35px; } }

.intro-section-footer .footer-nav a {
  font-size: 20px;
  font-weight: bold;
  color: inherit; }
  @media (max-width: 767px) {
    .intro-section-footer .footer-nav a {
      font-size: 14px; } }
  .intro-section-footer .footer-nav a + a {
    margin-left: 30px; }

.form-section {
  display: -webkit-box;
  display: flex;
  -webkit-box-align: center;
          align-items: center;
  -webkit-box-pack: center;
          justify-content: center; }
  @media (max-width: 767px) {
    .form-section {
      padding: 35px; } }

.login-wrapper {
  width: 60%;
  max-width: 100%; }
  @media (max-width: 575px) {
    .login-wrapper {
      width: 100%; } }


    </style>
</head>

    <body >
        <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6 col-md-7 intro-section">
                <div class="intro-content-wrapper">
                    <h1 class="mt-1 mb-4 text-white display-2">
                        CLUSTERSIG
                    </h1>
                    <h3 class="text-white text-center d-flex align-items-center justify-content-center" style="z-index: 1;">
                      Sistema de Información Geográfica y Estadística Gubernamental.
                    </h3>
                </div>
              </div>
              <div class="col-sm-6 col-md-5 form-section">
                <div class="login-wrapper">
                   
                    @yield('content')
                </div>
              </div>
            </div>
          </div>
</body>
<!-- [ auth-signup ] end -->
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
</html>

