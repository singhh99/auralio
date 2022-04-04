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
                   <a href="{{url('/State/create')}}"> <button class="btn btn-primary btn-fw" >Add New State</button></a>
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Country Name</th>
                        <th>State Name</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($state_list as $key)
                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->country_name}}</td>
                        <td>{{$key->state_name}}</td>
                        <td >
                            <a href="{{url('/State')}}/{{$key->state_id}}/edit">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" style="width: 41px; height: 20px;" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                              <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                            </svg>
                            </a>
                            <a href="{{url('/State')}}/{{$key->state_id}}/destroy">
                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash" style="width: 41px; height: 20px; color: red;" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                              <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                             <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg></a>
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
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.colVis.min.js"></script>
<script>
 $(document).ready(function() {
   var table = $('#example').DataTable( {
       lengthChange: false,
       buttons: [ 'copy', 'excel', 'pdf', 'colvis' ]
   } );

   table.buttons().container()
       .appendTo( '#example_wrapper .col-md-6:eq(0)' );
 } );
</script>