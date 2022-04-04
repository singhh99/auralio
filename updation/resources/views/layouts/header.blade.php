<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>CombyCut</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('vendors\iconfonts\mdi\css\materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors\css\vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{asset('vendors\css\vendor.bundle.addons.css')}}">

  <link rel="stylesheet" href="{{asset('vendors\iconfonts\font-awesome\css\font-awesome.min.css')}}">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="{{asset('css\style.css')}}">
  <!-- endinject -->
  <link rel="shortcut icon" href="{{asset('images\favicon.png')}}">
  <!-- cdn for fa fa icon -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
</head>

  <div class="container-scroller">
    <!-- partial:partials/_horizontal
<body>-navbar.html -->
    <nav class="navbar horizontal-layout col-lg-12 col-12 p-0">
    <a class="navbar-brand brand-logo mr-0" href="index.php">
        <img src="{{asset('images\logo1.png')}}" class=""
             alt="logo" 
             style="margin-top: -208px; margin-left: -178px; margin-bottom: -374px; width: 438px; height: 354px;">
    </a>
      <div class="container d-flex flex-row" style="margin-bottom: -11px; margin-top: -46px;">
        <div class="text-center navbar-brand-wrapper d-flex align-items-top">
          <h1 style="color:#744df9;padding-top: 8px;"></h1>
          <!-- <a class="navbar-brand brand-logo" href="index.php"><img src="images\logo1.png" alt="logo"></a> -->
          <!-- <a class="navbar-brand brand-logo-mini" href="index.php"><img src="images\logo1.png" alt="logo"></a> -->
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
          <form class="search-field ml-auto" action="#">
          </form>
          <ul class="navbar-nav navbar-nav-right mr-0" style="margin-top: 25px">
           <!--  <li class="nav-item dropdown ml-4">
              <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                <i class="mdi mdi-bell-outline"></i>
                <span class="count bg-warning"></span>
              </a>
            </li> -->
             <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
               <!--  <img class="img-xs rounded-circle" src="" alt="Profile image"> -->
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown" style="padding: 15px 0 20px;">
               
                <a class="dropdown-item" href="{{url('/change-password')}}"><i class="mdi mdi-lock mr-0 text-gray" style="padding-right: 5px;"></i>
                  Change Password
                </a>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
                <i class="mdi mdi-logout mr-0 text-gray" style="padding-right: 5px;"></i><span>Logout</span>
              </a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
        </div>
      </div>
      <div class="nav-bottom">
        <div class="container">
          <ul class="nav page-navigation">
            <li class="nav-item">
              <a href="{{url('/home')}}" class="nav-link">
              <i class="link-icon mdi mdi-television">
                 </i><span class="menu-title">DASHBOARD</span></a>
            </li>
            <li class="nav-item">
              <a href="{{url('/Saloon')}}" class="nav-link">
              <i class=" link-icon fa fa-scissors"></i>
                 <span class="menu-title">Saloon</span></a>
            </li>
            <li class="nav-item">
              <a href="{{url('/Admin-User')}}" class="nav-link">
              <i class="link-icon fa fa-user-plus"></i>
                 <span class="menu-title">Admin Users</span></a>
            </li>
         
            <li class="nav-item">
              <a href="" class="nav-link"><i class="link-icon mdi mdi-asterisk"></i>
                 <span class="menu-title">Masters</span><i class="menu-arrow"></i></a>
              <div class="submenu">
                <ul class="submenu-item">
                  <!-- <li class="nav-item"><a class="nav-link" href="{{url('/AllCountries')}}">Countries</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{url('/State')}}">States</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{url('/City')}}">Cities</a></li> -->
                 <!--  <li class="nav-item"><a class="nav-link" href="{{url('/Service')}}">Services</a></li> -->
                  <li class="nav-item"><a class="nav-link" href="{{url('/Feature')}}">Features</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{url('/Booking-Status')}}">Booking Status</a></li>
                  <li class="nav-item"><a class="nav-link" href="{{url('/Type')}}">Saloon Type</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>