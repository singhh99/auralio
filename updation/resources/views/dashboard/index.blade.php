@include('layouts/header')
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
           <div id="success-alert"> 
                   @if(Session::has('message'))
                     <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                      @endif
                    </div>
          <div class="row">
            <div class="col-12 grid-margin">
              <div class="card card-statistics">
                <div class="row">
                  <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                          <i class="mdi mdi-account-multiple-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                          <div class="wrapper text-center text-sm-left">
                            <p class="card-text mb-0">Admin Users</p>
                            <div class="fluid-container">
                              <h3 class="card-title mb-0"></h3>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                          <i class="mdi mdi-checkbox-marked-circle-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                          <div class="wrapper text-center text-sm-left">
                            <p class="card-text mb-0">New Orders</p>
                            <div class="fluid-container">
                              <h3 class="card-title mb-0"></h3>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                          <i class="mdi mdi-trophy-outline text-primary mr-0 mr-sm-4 icon-lg"></i>
                          <div class="wrapper text-center text-sm-left">
                            <p class="card-text mb-0">Customers</p>
                            <div class="fluid-container">
                              <h3 class="card-title mb-0"></h3>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                  <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                          <i class="mdi mdi-target text-primary mr-0 mr-sm-4 icon-lg"></i>
                          <div class="wrapper text-center text-sm-left">
                            <p class="card-text mb-0">Vendors</p>
                            <div class="fluid-container">
                              <h3 class="card-title mb-0"></h3>
                            </div>
                          </div>
                        </div>
                      </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 col-sm-6 grid-margin stretch-card">
							<div class="card text-center">
							</div>
						</div>
            <div class="col-md-4 col-sm-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body pb-0">
                  <div class="wrapper ">
                    <h5 class="mb-0 text-gray"></h5>
                    <h1 class="mb-0"></h1>
                    <p class="mb-4"></p>
                  </div>
                  <div class="pt-4 wrapper">
                    <h5 class="mb-0 text-gray"></h5>
                    <h1 class="mb-0"></h1>
                    <p></p>
                  </div>
                </div>
                <!-- <canvas id="product-area-chart" height="200"></canvas> -->
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="w-75 mx-auto">
                    <div class="d-flex justify-content-between text-center mb-2">
                      <div class="wrapper">
                        <h4></h4>
                        <small class="text-muted"></small>
                      </div>
                      <div class="wrapper">
                        <h4></h4>
                        <small class="text-muted"></small>
                      </div>
                    </div>
                  </div>
                  <div id="morris-line-example" ></div>
                  <div class="w-75 mx-auto">
                    <div class="d-flex justify-content-between text-center mt-5">
                      <div class="wrapper">
                        <h4></h4>
                        <small class="text-muted"></small>
                      </div>
                      <div class="wrapper">
                        <h4></h4>
                        <small class="text-muted"></small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->   
        </div>
@include('layouts/footer')
<script>
 
  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>