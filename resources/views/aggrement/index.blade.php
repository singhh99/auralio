@include('layouts/header')
@include('layouts/sidebar')
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    
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
                    <a href="{{url('/Aggrement/create')}}"><button type="button" class="btn btn-primary btn-fw">Add Aggrement</button></a>
                    
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Aggrement Type</th>
                        <th>Aggrement File</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($aggrement_list as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->aggrement_type}}</td>
                        @if($key->aggrement_file)
                          <td>
                            @if(env('APP_ENV') == 'local')
                              <a href="{{asset('images/aggrement_file')}}/{{$key->aggrement_file}}" height="100px" width="100px">vendor aggrement</a>
                            @else
                              <a href="{{asset('public/images/aggrement_file')}}/{{$key->aggrement_file}}" height="100px" width="100px">vendor aggrement</a>
                            @endif
                          </td>
                           @else <td></td>
                        @endif
                        <td >
                          <a href="{{url('/Aggrement')}}/{{$key->aggrement_id}}/edit">
                            <button class="btn btn-outline-info">Edit</button>
                          </a>
                          <!-- <form method="Post" action="{{url('/Aggrement')}}/{{$key->aggrement_id}}">
                          @csrf
                            <input type="hidden" name="_method" value="DELETE">
                            <button style="transform: translate(69px, -19px);" type="submit" class="btn btn-outline-danger" onclick="return confirm('Are You Sure You Want to Delete This Entry')" >Delete</button>
                          </form>   -->
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
<script src="{{asset('js\alerts.js')}}"></script>
<script>
  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>