@include('layouts/header')
@include('layouts/sidebar')
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    
      <div class="row">        
        <div class="col-md-12 col-sm-12 grid-margin stretch-card">
            <div class="main-panel">
             <div class="content-wrapper">
               <div class="card">
                 <div class="card-body">
                  <div id="success-alert"> 
                   @if(Session::has('message'))
                     <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                      @endif
                    </div>
                   <h4 class="card-title">
                    <a href="{{url('/RolePermission/create')}}"><button type="button" class="btn btn-primary btn-fw">Add New  Role </button></a>
                   </h4>
                    <table id="example" class="table table-bordered table-hover" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Role Name</th>
                        <th style="width: 65%;">Permissions</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($roles as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->role_name}}</td>
                        <td style="width: 65%;line-height: 20px">
                          @foreach($key->permissions as $key1)
                          {{$key1->permission_name.','}}@endforeach </td>
                        <td >
                          <div class="row" style="margin:0px !important;">
                           <a href="{{url('/RolePermission')}}/{{$key->role_id}}/edit">
                           <button class="btn btn-block btn-info btn-sm">View</button>
                           </a>
                           <form method="Post" action="{{url('RolePermission')}}/{{$key->role_id}}">
                            @csrf
                           <input type="hidden" name="_method" value="DELETE">
                           <button  type="submit" class="btn btn-block btn-danger btn-sm" onclick="return confirm('Are You Sure You Want to Delete This Entry')" >Delete</button>
                           </form>
                          </div>
                        </td>
                     </tr>
                    @endforeach
                  </tbody>
               </table>
                 </div>
               </div>
             </div>
        </div>     
      </div>
    
  </div>
</div>
@include('layouts/footer')
@include('layouts/table')
<script>
  $("#success-alert").fadeTo(3000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>