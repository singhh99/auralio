@include('layouts/header')
@include('layouts/sidebar')

      <div class="main-panel">
        
                  <!-- <div id="success-alert"> 
                   @if(Session::has('message'))
                     <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                      @endif
                  </div>  -->
                   <!-- Content Wrapper. Contains page content -->
                    <div class="content-wrapper">
                      <!-- Content Header (Page header) -->
                      <div class="content-header">
                        <div class="container-fluid">
                          <div class="row mb-2">
                            <div class="col-sm-6">
                              <h1 class="m-0 text-dark">Dashboard</h1>
                            </div><!-- /.col -->
                            <div class="col-sm-6">
                              <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard v1</li>
                              </ol>
                            </div><!-- /.col -->
                          </div><!-- /.row -->
                        </div><!-- /.container-fluid -->
                      </div>
                      <!-- /.content-header -->

                      <!-- Main content -->
                      <section class="content">
                        <div class="container-fluid">
                          <!-- Small boxes (Stat box) -->
                          <div class="row">
                          <div class="col-lg-3 col-6">
                              <!-- small box -->
                              <div class="small-box bg-secondary">
                                <div class="inner">
                                  <h3>{{$bookings}}</h3>

                                  <p>Today's Bookings</p>
                                </div>
                                <div class="icon">
                                  <i class="ion ion-plus-circled"></i>
                                </div>
                                <a href="{{url('/bookingsinfo')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                              </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                              <!-- small box -->
                              <div class="small-box bg-dark">
                                <div class="inner">
                                  <h3>{{$salons}}<sup style="font-size: 20px"></sup></h3>

                                  <p>Salons</p>
                                </div>
                                <div class="icon">
                                  <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="{{url('/Saloon')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                              </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                              <!-- small box -->
                              <div class="small-box bg-secondary">
                                <div class="inner">
                                  <h3>{{$rescheduled}}</h3>

                                  <p>Today's Rescheduled Bookings</p>
                                </div>
                                <div class="icon">
                                  <i class="ion ion-refresh"></i>
                                </div>
                                <a href="{{url('/rescheduledbookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                              </div>
                            </div>
                            <!-- ./col -->
                            <div class="col-lg-3 col-6">
                              <!-- small box -->
                              <div class="small-box bg-dark">
                                <div class="inner">
                                  <h3>{{$cancelled}}</h3>

                                  <p>Today's Cancelled Bookings</p>
                                </div>
                                <div class="icon">
                                  <i class="ion ion-trash-a"></i>
                                </div>
                                <a href="{{url('/cancelledbookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                              </div>
                            </div>
                            <!-- ./col -->
                          </div>
                          
     
                          <div class="row">
                           <div class="col-lg-3 col-6">
                              <!-- small box -->
                              <div class="small-box bg-dark">
                                <div class="inner">
                                  <h3>₹{{$totalrevenue}}</h3>

                                  <p>Today's Revenue </p>
                                </div>
                                <div class="icon">
                                  <!-- <i class="ion ion-refresh"></i> -->
                                  <i class="fas fa-rupee"></i>
                                </div>
                                <a href="{{url('/revenue')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                              </div>
                            </div>
                            <!-- ./col -->
                            <!-- <div class="col-lg-3 col-6"> -->
                              <!-- small box -->
                              <!-- <div class="small-box bg-secondary">
                                <div class="inner">
                                  <h3>{{$cancelled}}</h3>

                                  <p>Cancelled Bookings</p>
                                </div>
                                <div class="icon">
                                  <i class="ion ion-trash-a"></i>
                                </div>
                                <a href="{{url('/cancelledbookings')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                              </div>
                            </div> -->
                            <!-- ./col -->
                          </div>
                          <!-- /.row -->
                          <!-- Main row -->

                          <!-- deleted cont. -->
                          
                          <!-- /.row (main row) -->
                        </div><!-- /.container-fluid -->
                      </section>
                      <!-- /.content -->
                    </div>
                    <!-- /.content-wrapper -->
        
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
     </div>
@include('layouts/footer')
<script>
 
  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>