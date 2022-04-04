@include('layouts/header')
@include('layouts/sidebar')
<link rel="stylesheet" href="{{asset('vendors\iconfonts\mdi\css\materialdesignicons.min.css')}}">
 
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    
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
                <table id="example" class="table table-bordered table-hover" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>User Name</th>
                        <th>Mobile Number</th>
                        <th>Email ID</th>
                        <th>User Role</th>
                        <!-- <th>Admin Status</th> -->
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
                        <td>{{$key->role_name}}</td>
                       <!--  <td>
                           <meta name="csrf-token" content="{{ csrf_token() }}">
                                  <select class="form-control" style="width: 140px;margin-left: 8px"  name="admin_approval" id="{{$key->id}}" onchange="update_status(this)">
                                  <option  value="1" <?php if($key->status=="1") echo "selected"; ?> onclick="showSwal('warning-message-and-cancel')" onchange="update_status(this)" >Active</option>
                                  <option value="0" <?php if($key->status=="0") echo "selected"; ?> onclick="showSwal('warning-message-and-cancel')">InActive</option>
                               </select>

                            </td>
                         -->
                        <td >
                         <div class="row" style="margin:0px !important;">
                           <a href="{{url('/Admin-User')}}/{{$key->id}}/edit">
                           <button class="btn btn-block btn-info btn-sm" >Edit</button>
                           </a>
                           <form method="Post" action="{{url('/Admin-User')}}/{{$key->id}}">
                            @csrf
                          <input type="hidden" name="_method" value="DELETE">
                          <button  type="submit"class="btn btn-block btn-danger btn-sm" 
                           onclick="return confirm('Are You Sure You Want to Delete This Entry')">Delete</button>
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
  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});

 function update_status(element)
   {
      var id = element.id;
      var status=element.value;
      var token = $("meta[name='csrf-token']").attr("content");
      console.log(id,status );
       $.ajax(
    {
        url: "/Update-User-Status",
        type: 'POST',
        data: {
             "id": id,
             "status":status,
            "_token": token,
        },
        success: function (data)
        {
          var url = window.location.origin;
          console.log(data);
          if(data=="User Status updated"&& data=="User Status not updated"){
           alert(data);     
          }
          else{
             window.open(url+'/permisiondenied', '_self')
          }
        }
    });

   }
</script>