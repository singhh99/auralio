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
                    <a href="{{url('/Type/create')}}"><button type="button" class="btn btn-primary btn-fw">Add Saloon Type</button></a>
                   </h4>
                   
                    <table id="example" class=" table table-hover text-nowrap" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>SaloonType Name</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($saloon_list as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->saloon_type_name}}</td>
                        <td >
                          <div class="row">
                           <a href="{{url('/Type')}}/{{$key->saloon_type_id}}/edit">
                           <button  style="margin-right:8px;" class="btn btn-block btn-info btn-sm">View</button>
                           </a>
                          <form method="Post" action="{{url('/Type')}}/{{$key->saloon_type_id}}">
                            <input type="hidden" name="_method" value="DELETE">@csrf
                            <button  type="submit" class="btn btn-block btn-danger btn-sm" onclick="return confirm('Are You Sure You Want to Delete This Entry')">Delete</button>
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