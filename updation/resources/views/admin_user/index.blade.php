@include('layouts/header')
<link rel="stylesheet" href="{{asset('vendors\iconfonts\mdi\css\materialdesignicons.min.css')}}">
 
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">        
        <div class="col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
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
                    <a href="{{url('/Admin-User/create')}}"><button type="button" class="btn btn-primary btn-fw">Add User</button></a>
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>User Name</th>
                        <th>User Mobile Number</th>
                        <th>User Email ID</th>
                       <!--  <th>Admin Status</th> -->
                        <th style="text-align:center;">Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php $i=1 @endphp
                    @foreach($user_list as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->name}}</td>
                        <td>{{$key->mobile_no}}</td>
                        <td>{{$key->email}}</td>
                        <!-- <td><select class="form-control">
                              <option>Active</option>
                              <option>Deactive</option>
                            </select></td> -->
                        <!-- <td style="text-align:center;">
                                    <a href="{{url('/Admin-User')}}/{{$key->id}}/edit">
                                    <i id="icon1" class="fa fa-pencil btn btn-success "></i> </a>
                                    <a> <form method="Post" action="{{url('/Admin-User')}}/{{$key->id}}}}">
                                      @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                     <i id="icon1" class="fa fa-trash btn btn-warning" style="transform: translate(51px, -18px);"></i>
                                   </form>  </a>
                                 </td> -->
                        <td style="text-align:center;" >
                           <a href="{{url('/Admin-User')}}/{{$key->id}}/edit">
                           <button class="btn btn-outline-success" style="margin-left: -41px">View</button>
                           </a>
                           <form method="Post" action="{{url('/Admin-User')}}/{{$key->id}}">
                            @csrf
                          <input type="hidden" name="_method" value="DELETE">
                          <button style="transform: translate(52px, -19px);" type="submit" class="btn btn-outline-danger" >Delete</button>
                         </form>
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
</div>

@include('layouts/footer')
@include('layouts/table')
<script>
  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>