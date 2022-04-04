<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" rel="stylesheet">
  <aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- <a href="#" class="brand-link"> -->
            <span class="brand-text font-weight-light"></span>
    </a>

      <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
          <img src="{{url('public/dist/img/oneTickLogo.jpg')}}" alt="AURALIO"  class="brand-image img-circle elevation-3">
          </div>
          <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
          </div>
        </div>
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                  <a href="{{url('/')}}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                      Dashboard
                    </p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="{{url('/Admin-User')}}" class="nav-link">
                    <i class="nav-icon fas fa-user"></i>
                    <p>Users</p>
                  </a>
                </li>

                <li class="nav-item ">
                 <a href="{{url('/RolePermission')}}" class="nav-link">
                  <i class="nav-icon fas fa-user-tag"></i>
                  <p>Roles
                    <!-- <i class="right fas fa-angle-left"></i> -->
                  </p>
                 </a>
                </li>

                <li class="nav-item">
                  <a href="{{url('/Saloon')}}" class="nav-link">
                  <!--  <i class="nav-icon fas fa-copy"></i> -->
                    <i class="nav-icon fas fa-search-location"></i>
                    <p>
                    Salons
                    <!-- <i class="right fas fa-angle-left"></i> -->
                  </p>
                 </a>
                </li>
                
                <li class="nav-item has-treeview">
                  <a href="#" class="nav-link">
                  <!--  <i class="nav-icon fas fa-copy"></i> -->
                    <i class="nav-icon fas fa-calendar-check"></i>
                    <p>
                    Bookings
                    <i class="right fas fa-angle-left"></i>
                  </p>
                 </a>
                 <ul  class="nav nav-treeview">
                  <li class="">
                          <a href="{{url('/bookingsinfo')}}" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>All Bookings</p>
                          </a>
                        </li>  
                  <li class="">
                        <a href="{{url('/rescheduledbookings')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Rescheduled</p>
                        </a>
                      </li>                    
                      <li class="">
                        <a href="{{url('/cancelledbookings')}}" class="nav-link">
                          <i class="far fa-circle nav-icon"></i>
                          <p>Cancelled</p>
                        </a>
                      </li>    
                  </ul>
                </li>

                <li class="nav-item ">
                 <a href="{{url('/revenue')}}" class="nav-link">
                  <i class="nav-icon fas fa-rupee"></i>
                  <p>Revenues
                    <!-- <i class="right fas fa-angle-left"></i> -->
                  </p>
                 </a>
                </li>

                <li class="nav-item has-treeview">
                 <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-cogs"></i>
                  <p>Settings
                    <i class="right fas fa-angle-left"></i>
                  </p>
                 </a>

                 <ul class="nav nav-treeview">
                    <li class="">
                      <a href="{{url('/Aggrement')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Salon Agreement</p>
                      </a>
                    </li>
                    <li class="">
                      <a href="{{url('/Type')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Salon Type </p>
                      </a>
                    </li>
                    <li class="">
                      <a href="{{url('/Booking-Status')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Booking Status</p>
                      </a>
                    </li>
                    <li class="">
                      <a href="{{url('/Feature')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Features</p>
                      </a>
                    </li>
                    <li class="">
                      <a href="{{url('/Cancel-Reason')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Cancel Reason</p>
                      </a>
                    </li>
                    <li class="">
                      <a href="{{url('/Service')}}" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Services</p>
                      </a>
                    </li>                    
                  
                  </ul>
                </li>

                <li class="nav-item">
                  <a href="{{url('/change-password')}}" class="nav-link">
                    <i class="nav-icon fas fa-unlock-alt"></i>
                    <p>Change Password</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="nav-link">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                    </form>
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>Logout</p>
                  </a>
                </li>
                
          </ul>
        </nav>
      </div>
    </aside>     