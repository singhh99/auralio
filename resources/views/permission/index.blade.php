@include('layouts/header')
@include('layouts/sidebar')
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
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
                    <a href="{{url('/Permission/create')}}"><button type="button" class="btn btn-primary btn-fw">Add Permission</button></a>
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Permission Name</th>
                       
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($permission_list as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->permission_name}}</td>
                        <td >
                           <a href="{{url('/Permission')}}/{{$key->permission_id}}/edit">
                           <button class="btn btn-outline-success">View</button>
                           </a>
                           <!-- <a href="{{url('/Booking-Status')}}/{{$key->booking_status_id}}">
                           <button class="btn btn-outline-danger">Delete</button>
                           </a> -->
                           <form method="Post" action="{{url('Permission')}}/{{$key->permission_id}}">
                            @csrf
                          <input type="hidden" name="_method" value="DELETE">
                          <button style="transform: translate(69px, -19px);" type="submit" class="btn btn-outline-danger" onclick="return confirm('Are You Sure You Want to Delete This Entry')" >Delete</button>
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
  $("#success-alert").fadeTo(3000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>