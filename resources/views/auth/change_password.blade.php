@include('layouts/header')
@include('layouts/sidebar')
<style>
  .card h4{
    background-color:#3f4a54; padding: 5px 0 0 20px; color: white; height: 30px;  
  }
  #body1{
    padding: 20px 25px;
  } 
</style>
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class=" col-md-3">
          <a href="{{url('/')}}">
            <button type="button" class="btn btn-primary btn-fw">Back</button>
          </a>
        </div>
        <div class="col-md-6 col-sm-12 grid-margin stretch-card">
            <div class="main-panel">
            <div id="success-alert"> 
                   @if(Session::has('message'))
                     <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                      @endif
                    </div>
             <!-- <div class="content-wrapper"> -->
               <div class="card"><h4 class="">Change Password</h4>
                 <div class="card-body" id="body1">
                   <form class="forms-sample" method="post" action="{{url('/change-password')}}">
                   	@csrf
                    <div class="form-group">
                      <label for="previous-password">Previous Password</label>
                      <input type="password" class="form-control" id="" name="user_password" placeholder="enter previous password "  required="">
                    </div> 
                     <div class="form-group">
                      <label for="New-password">New Password</label>
                      <input type="password" class="form-control" id="" name="password" placeholder="enter new password"  required="">
                    </div> 
                   <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
                 </div>
               </div>
             <!-- </div> -->
        </div>     
      </div>
    </div>
  </div>
</div>
@include('layouts/footer')