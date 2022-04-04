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
                    <a href="{{url('/Service/create')}}"><button type="button" class="btn btn-primary btn-fw">Add Service</button></a>
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Service Name</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($service_list as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->service_name}}</td>
                        <td >
                          <div class="row">
                           <a href="{{url('/Service')}}/{{$key->service_id}}/edit">
                           <button style="margin-right:8px;" class="btn btn-block btn-info btm-sm">edit</button>
                           </a>
                          <form method="Post" action="{{url('/Service')}}/{{$key->service_id}}">
                            @csrf
                          <input type="hidden" name="_method" value="DELETE">
                          <button style="margin-right:auto;" type="submit" class="btn btn-block btn-danger btm-sm" onclick="return confirm('Are You Sure You Want to Delete This Entry')" >Delete</button>
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
</script>