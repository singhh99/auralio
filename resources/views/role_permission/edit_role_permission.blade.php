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
                   <a href="{{url('/RolePermission')}}"><button type="button" class="btn btn-primary btn-fw">Back</button></a>
              </div>
        <div class="col-md-7 col-sm-6 grid-margin stretch-card">
            <div class="main-panel">
             
               <div class="card"><h4 class="">
                   Add New Role 
                   </h4>
                 <div class="card-body" id="body1">
                   <form class="forms-sample" method="post" action="{{url('/RolePermission')}}/{{$role_id}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                      <label for="RoleName">Role</label>
                      @foreach($role_list as $key)
                       <input type="text" class="form-control " name="role_name" placeholder="Role Name" value="{{ $key->role_name }}" readonly="" required="" >
                      @endforeach
                    </div>
                    <label for="PermissioList">Permssions List</label>
                      <div class="form-group  row">
                        @foreach($permission_list as $key)
                          <div class="col-md-6  ">
                            <div class="form-check form-check-flat">
                                <label class="form-check-label" >   
                                  <input type="checkbox" class="form-check-input" value="{{$key->permission_id}}" <?php foreach($role_details as $info){ if($info->permission_id == $key->permission_id) { ?> Checked <?php } }?> name="permission_id[]">
                                  {{$key->permission_name}}
                                </label>
                            </div>
                          </div>
                         @endforeach
                    </div> 
                   <button type="submit" class="btn btn-success mr-2">Submit</button>
                  </form>
                 </div>
               </div>
             
        </div>     
      </div>
    </div>
  </div>
</div>
@include('layouts/footer')