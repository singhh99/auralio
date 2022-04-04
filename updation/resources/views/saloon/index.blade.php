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
   .card-body{
    overflow: auto;
  }
  .table{
    text-align: center;
  }
  #icon1{
    padding: 10px;
  }
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
                      <div id="success-alert"> 
                   @if(Session::has('message'))
                     <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                      @endif
                    </div>
                        <h4 class="card-title">
                           <a href="{{url('/Saloon/create')}}"><button type="button" class="btn btn-primary btn-fw">Add New Salon</button></a>
                        </h4>
                        <table id="example" class="table" >
                           <thead>
                              <tr>
                                 <th>Sr no.</th>
                                 <th>Owner Name</th>
                                 <th>Owner Mobile</th>
                                 <th>Salon Area </th>
                                 <th>Salon Services</th>
                                 <th>Salon Images</th>
                                 <th>Admin Approval</th>
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
                                 <td>{{$key->saloon_area}}</td>
                                 <td> <button type="button" style="height: 35px"  class="btn btn-inverse-primary btn-fw" data-toggle="modal" id="{{$key->saloon_id}}" onclick="services_list(this.id)" data-target="#exampleModal-4" data-whatever="@mdo">Services</button></td>
                                 <td><a href="{{url('/Saloon-Images')}}/{{$key->saloon_id}}/all-images/{{'Web'}}">
                                  
                                  <button type="button" style="height: 35px;margin-left: 8px" class="btn btn-inverse-success btn-fw">Images</button></a></td>
                                 <td> <meta name="csrf-token" content="{{ csrf_token() }}">
                                  <select class="form-control" style="width: 140px;margin-left: 8px"  name="admin_approval" id="{{$key->saloon_id}}" onchange="update_status(this)">
                                  <option  value="1" <?php if($key->admin_approval=="1") echo "selected"; ?> onclick="showSwal('warning-message-and-cancel')" onchange="update_status(this)" >Approved</option>
                                  <option value="0" <?php if($key->admin_approval=="0") echo "selected"; ?> onclick="showSwal('warning-message-and-cancel')">Not Approved</option>
                               </select>
                              </td>
                                 <td >
                                    <a href="{{url('/Saloon')}}/{{$key->saloon_id}}/edit">
                                    <i id="icon1" class="fa fa-pencil btn btn-success "></i> </a>
                                   <!--  <a href="{{url('/Country')}}/{{$key->country_id}}/edit">
                                    <i id="icon1" class="fa fa-trash-o btn btn-warning"></i></button>
                                    </a> -->
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
<div class="modal fade" id="exampleModal-4" tabindex="-1" role="dialog" 
   aria-labelledby="exampleModalLabel-4" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <button type="button" id="saloon_idd"  name="saloon_idd" class="btn btn-success" data-toggle="modal"  data-target="#exampleModal-3" data-whatever="@mdo" onclick="add_service(this)">Salon Service</button>
            <button type="button" onclick="javascript:window.location.reload()" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body"style="height: 300px!important;padding: 0px 26px;" >
            <table class="table">
               <thead>
                  <tr>
                     <th>Sr no.</th>
                     <th>Service Name</th>
                     <th>Service Price</th>
                     <th>Actions</th>
                  </tr>
               </thead>
               <tbody id="here1">
               </tbody>
            </table>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-light"   data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="exampleModal-3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-3" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel-3">Add Service</h5>
            <button type="button" class="close"   data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="post" action="{{url('/add-service')}}">
               <div class="form-group">
                  <label for="service-name" class="col-form-label">Service Name:</label>
                  @csrf
                  <input type="text" class="form-control" required="" name="service_name" id="service-name" placeholder="service name">
                  <input type="hidden" name="saloon_id" id="saloon_id" value="">
                  <input type="hidden" name="Source" value="Web">
               </div>
               <div class="form-group">
                  <label for="service-price" class="col-form-label">Service Price:</label>
                  <input type="number" class="form-control" required="" name="service_price" id="service_price" placeholder="service price">                           
               </div>
         </div>
         <div class="modal-footer">
         <button type="submit" class="btn btn-success">Submit</button>
         <button type="button" class="btn btn-light" onclick="javascript:window.location.reload()"  data-dismiss="modal">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>

<!-- edit modal for saloon service -->
<div class="modal fade" id="exampleModal-5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-5" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel-5">Edit Service</h5>
            <button type="button" class="close" onclick="javascript:window.location.reload()"  data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <form method="post" action="{{url('/update-service')}}">
               <div class="form-group">
                  <label for="service-name" class="col-form-label">Service Name:</label>
                  @csrf
                  <input type="text" class="form-control" required="" name="service_name" 
                  id="service_names">
                 <input type="hidden" name="Source" value="Web">
                  <input type="hidden" name="saloon_id" id="saloon_ids" value="">
                  <input type="hidden" name="saloon_service_id" id="saloon_service_id" value="  "  required="">
               </div>
               <div class="form-group">
                  <label for="service-price" class="col-form-label">Service Price:</label>
                  <input type="number" class="form-control" required="" name="service_price" id="service_prices" required="">                           
               </div>
         </div>
         <div class="modal-footer">
         <button type="submit" class="btn btn-success">Submit</button>
         <button type="button" class="btn btn-light" onclick="javascript:window.location.reload()"  data-dismiss="modal">Close</button>
         </div>
         </form>
      </div>
   </div>
</div>
@include('layouts/footer')
@include('layouts/table')
<script src="{{asset('js\alerts.js')}}"></script>
<script>
//function to show service list  
   function services_list(id)
   {
    console.log(id);
    var saloon_id=id
     document.getElementById('saloon_idd').value=id;
     $.ajax({
         dataType: 'json',
         type : 'get',
         url : '/service_list',
         data:{saloon_id},
         success:function(data)
         {
             console.log(data);
             var res='';
             var i=1;
             $.each (data['data'], function (key, value) {
              
             res +=
             '<tr>'+
                 '<td>'+ i++ +'</td>'+ 
                 '<td  name="service_name">'+value.service_name+'</td>'+
                 '<td  name="service_price">'+value.service_price+'</td>'+
                 '<input type="hidden" name="saloon_id" value='+value.saloon_id+'>'+
                 '<td ><a id='+value.saloon_service_id+' data-toggle="modal"  data-target="#exampleModal-5" onclick="editService(this.id)"><i id="icon1" class="fa fa-pencil btn btn-success " "></i></a><a id='+value.saloon_service_id+' onclick="deleteRecord(this.id)"><i id="icon1" class="fa fa-trash btn btn-warning "></i></a'+  +'</td>'+ 
                 '{{ csrf_field() }}'               
            '</tr>';  
             });
           $( "#here1" ).html(res);
         }
     });
   }

// function to add new service
   function add_service(id)
   {
     // var id=  document.getElementsById('saloon_idd')[0].value
     var id=  document.getElementById('saloon_idd').value
     $('#exampleModal-4').modal('hide');
     console.log(id);
     document.getElementById('saloon_id').value=id
   } 

// function to delete  service record
  function deleteRecord(saloon_service_id)
  {
    var saloon_service_id=saloon_service_id;
    var Source='Source';
    var token = $("meta[name='csrf-token']").attr("content");
    $.ajax(
    {
        url: "/delete-service",
        type: 'DELETE',
        data: {
            "saloon_service_id": saloon_service_id,
            "Web":Source,
            "_token": token,
        },
        success: function (data)
        {
          var url = window.location.origin;
          swal("Done!",data['message']);
          window.open(url+'/Saloon', '_self')
        }
    });

  }

//function to edit service record
  function editService(saloon_service_id)
  {
    $('#exampleModal-4').modal('hide');
     var saloon_service_id=saloon_service_id;
     console.log(saloon_service_id);
    // console.log($('input[name="_token"]').val());
    var token = $("meta[name='csrf-token']").attr("content");
    $.ajax(
    {
        url: "/edit-service",
        type: 'GET',
        data: {
            "saloon_service_id": saloon_service_id,
        },
        success: function (data)
        {
          console.log(data);
           $.each (data, function (key, value) {
            console.log(value.saloon_id);
             document.getElementById('service_names').value=value.service_name;
             document.getElementById('service_prices').value=value.service_price;
             document.getElementById('saloon_ids').value=value.saloon_id;
            document.getElementById('saloon_service_id').value=value.saloon_service_id;
           });
          // setInterval('location.reload()', 500);     
        }
    });

  }
</script>
<script>
  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});


 function update_status(element)
   {
      var saloon_id = element.id;
      var admin_approval=element.value;
      var token = $("meta[name='csrf-token']").attr("content");
      console.log(saloon_id,admin_approval );
     swal({
      text: "Are you sure you want to approve this saloon?",
      icon: "warning",
      buttons: ['NO', 'YES'],
      dangerMode: true
    })
    .then(function(value) {
      console.log('returned value:', value);
      if(value)
      {
         $.ajax({
               url: "/Admin-approval",
               type: 'POST',
               data: {
               "saloon_id": saloon_id,
               "admin_approval":admin_approval,
               "_token": token,
             },
              success: function (data)
              {
                swal("Done!","Status Updated Succesfully","success");     
               }
       }); 
      }
    });

    //   $.ajax(
    // {
    //     url: "/Admin-approval",
    //     type: 'POST',
    //     data: {
    //         "saloon_id": saloon_id,
    //          "admin_approval":admin_approval,
    //         "_token": token,
    //     },
    //     success: function (data)
    //     {
    //        alert(data);     
    //     }
    // });
         
    }

    </script>