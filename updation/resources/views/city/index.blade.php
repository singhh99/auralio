@include('layouts/header')
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">        
        <div class="col-md-12 col-sm-6 grid-margin stretch-card">
            <div class="main-panel">
             <div class="content-wrapper">
               <div class="card">
                 <div class="card-body">
                   <h4 class="card-title">
                   <a href="{{url('/City/create')}}"> <button class="btn btn-primary btn-fw" >Add New City</button></a>
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>State Name</th>
                        <th>City Name</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($city_list as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->state_name}}</td>
                        <td>{{$key->city_name}}</td>
                        <td >
                           <a href="{{url('/City')}}/{{$key->city_id}}/edit">
                           <button class="btn btn-outline-success">View</button>
                           </a>
                           <a> <form method="Post" action="{{url('/City')}}/{{$key->city_id}}">
                            <input type="hidden" name="_method" value="DELETE">@csrf
                           <button style="transform: translate(69px, -20px);" class="btn btn-outline-danger">Delete</button>
                          </form></a>
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
<!-- modal for add country -->
<div class="modal fade" id="exampleModal-4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-4" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header"  >
            <h5 class="modal-title" id="exampleModalLabel-4"  >Add New Country</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <form method="post" action="{{url('/AddCountry')}}">
           @csrf
            <div class="modal-body">
               <div class="form-group">
                  <label for="country-name" class="col-form-label">Country Name:</label>
                  <input type="text" class="form-control" id="country_name" value="" required="" name="country_name">
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-success">Sumbmit</button>
               <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
            </div>
         </form>
      </div>
   </div>
</div>

@include('layouts/footer')
@include('layouts/table')