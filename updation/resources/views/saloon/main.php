@include('layouts/header')
<style>
/* Important part */
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    height: 250px;
    overflow-y: auto;
}
/*#icon1{
  padding: 23px 0px !important;
}*/
</style>
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
                   <h4 class="card-title">
                    <a href="{{url('/Saloon/create')}}"><button type="button" class="btn btn-primary btn-fw">Add New Saloon</button></a>
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Owner Name</th>
                        <th>Owner Mobile</th>
                        <th>Saloon Address</th>
                        <th>Saloon Area </th>
                        <th>Saloon Services</th>
                        <th>Saloon Images
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($saloon_list as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->owner_name}}</td>
                        <td>{{$key->owner_mobile}}</td>
                        <td>{{$key->saloon_address}}</td>
                        <td>{{$key->saloon_area}}</td>
                        <td> <button type="button" style="height: 35px" class="btn btn-inverse-primary btn-fw" id="{{$key->saloon_id}}" onclick="service_table(this.id)" data-toggle="modal"  data-target="#exampleModal-4" data-whatever="@mdo" >Services</button></td><td><button type="button" style="height: 35px" class="btn btn-inverse-success btn-fw">Images</button></td>
                        <td >
                           <a href="{{url('/Saloon')}}/{{$key->saloon_id}}/edit">
                          <i id="icon1" class="fa fa-pencil btn btn-success "></i> </a>
                           <a href="">
                           <i id="icon1" class="fa fa-trash-o btn btn-warning"></i></button>
                           </a>
                          </td>
                          @endforeach
                     </tr>
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
  @php  $service=DB::table('saloons_services')
        ->where('saloon_id', $key->saloon_id)->get();
   @endphp
 <div class="modal fade" id="exampleModal-4" tabindex="-1" role="dialog" 
          aria-labelledby="exampleModalLabel-4" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" id="{{$key->saloon_id}}"  class="btn btn-success" data-toggle="modal"  data-target="#exampleModal-3" data-whatever="@mdo" onclick="add_service(this.id)">Add Service</button>
                          <button type="button" onclick="javascript:window.location.reload()" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body"style="height: 300px!important;padding: 0px 26px;" >
                          <table id="example" class="table" >
                            <thead>
                               <tr>
                                  <th>Sr no.</th>
                                  <th>Service Name</th>
                                  <th>Service Price</th>
                                  <th>Actions</th>
                               </tr>
                            </thead>
                            @php $i=1 @endphp
                            @foreach($service as $key)
                              <tbody>
                                <td>{{$i++}}</td>
                                <td>{{$key->service_name}}</td>
                                <td>{{$key->service_price}}</td>
                                <td >
                                   <a href="">
                                  <i id="icon1" class="fa fa-pencil btn btn-success "></i> </a>
                                   <a href="">
                                   <i id="icon1" class="fa fa-trash-o btn btn-warning"></i></button>
                                   </a>
                                </td>
                              </tbody>
                              @endforeach
                         </table> 
                        </div>
                        <!-- <div class="modal-footer">
                         <button type="button" onclick="javascript:window.location.reload()" class="close" data-dismiss="modal" aria-label="Close">Close
                          </button>
                        </div>
 -->                      </div>
                    </div>
            </div>

                  <div class="modal fade" id="exampleModal-3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-3" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel-3">Add Service</h5>
                          <button type="button" class="close" onclick="javascript:window.location.reload()" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <form method="post" action="{{url('/add_service')}}">
                            <div class="form-group">
                              <label for="service-name" class="col-form-label">Service Name:</label>
                                @csrf
                              <input type="text" class="form-control" name="service_name" id="service-name">
                              <input type="hidden" name="saloon_id" id="saloon_id" value="">
                            </div>
                            <div class="form-group">
                              <label for="message-text" class="col-form-label">Service Price:</label>
                               <input type="text" class="form-control" name="service_price" id="service_price">                           
                           </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-success">Submit</button>
                        <button type="button" onclick="javascript:window.location.reload()" class="close" data-dismiss="modal" aria-label="Close">Close
                          </button>
                        </div>
                        </form>
                      </div>
                    </div>
                  </div>

              
@include('layouts/footer')
@include('layouts/table')
<script >
  function add_service(id)
   {
     $('#exampleModal-4').modal('hide');
     console.log(id);
     document.getElementById('saloon_id').value=id
  }

</script>