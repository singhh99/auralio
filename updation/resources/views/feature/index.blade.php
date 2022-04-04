@include('layouts/header')
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
       <input type="hidden" name="Source" value="Web">
      <div class="row">
      <input type="hidden" name="Source" value="Web">        
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
                    <a href="{{url('/Feature/create')}}"><button type="button" class="btn btn-primary btn-fw">Add Feature</button></a>
                    
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Feature Name</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($feature_list as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->feature_name}}</td>
                        <td >
                           <a href="{{url('/Feature')}}/{{$key->feature_id}}/edit">
                           <button class="btn btn-outline-success">View</button>
                           </a>
                           <form method="Post" action="{{url('/Feature')}}/{{$key->feature_id}}">
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
<script src="{{asset('js\alerts.js')}}"></script>
<script>
  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>