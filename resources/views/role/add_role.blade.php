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
                   <a href="{{url('/Role')}}"><button type="button" class="btn btn-primary btn-fw">Back</button></a>
              </div>
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="main-panel">
             <div class="content-wrapper">
               <div class="card"><h4 class="">
                   Add New Role
                   </h4>
                 <div class="card-body" id="body1">
                   <form class="forms-sample" method="post" action="{{url('/Role')}}">
                   	@csrf
                    <div class="form-group">
                      <label for="RoleName">Role Name</label>
                      <input type="text" class="form-control" id="" name="role_name" placeholder="enter role name"  required="">
                    </div> 
                   <button type="submit" class="btn btn-success mr-2">Submit</button>
                   <!--  <button class="btn btn-light">Cancel</button> -->
                  </form>
                 </div>
               </div>
             </div>
        </div>     
      </div>
    </div>
  </div>
</div>
@include('layouts/footer')