@include('layouts/header')
@include('layouts/sidebar')

<style>
/* Important part */
.modal-dialog {
    overflow-y: initial !important
}

.modal-body {
    height: 250px;
    overflow-y: auto;
}

.card-body {
    overflow: auto;
}

.table {
    text-align: center;
}

#icon1 {
    padding: 10px;
}



</style>

<div class="container-fluid page-body-wrapper">
    <div class="main-panel">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12 grid-margin stretch-card">
                <div class="main-panel">
                    <div class="content-wrapper">
                    <h3>Salons Revenues                     
                    <a href="{{url('/')}}"><button type="button" class="btn btn-primary btn-fw" style="float: right;" >back</button></a></h3>



                        <div class="card">
                            <div class="card-body">
                                <div id="success-alert">
                                    @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">
                                        {{ Session::get('message') }}</p>
                                    @endif
                                </div>
                                
 

                                <table id="example1" class="table table-bordered table-hover" style="font-weight:normal !important;">
                                    <thead>
                                        <tr>
                                            <th>Sr no.</th>
                                            <th>Saloon Code</th>
                                            <th>Salon Name</th>
                                            <th>Owner Mobile</th>
                                            <!-- <th>Salon Area </th> -->
                                           
                                            <!-- <th>Salon Images</th> -->
                                            <th>Revenue in Cash </th>
                                            <th>Revenue via online </th>
                                            <th>Total Revenue</th>
                                          
                                            <!-- <th>Actions</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($saloon_list as $key)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$key->saloon_code}}</td>
                                            <td>{{$key->saloon_name}}</td>
                                            <td>{{$key->owner_mobile}}</td>
                                            <!-- <td>$key->saloon_area</td> -->
                                            
                                            <!-- <td><a
                                                    href="{{url('/Saloon-Images')}}/{{$key->saloon_id}}/all-images/{{'Web'}}">

                                                    <button type="button" style="height: 35px;margin-left: 8px"
                                                        class="btn btn-block btn-outline-warning btn-sm">Images</button></a></td> -->
                                            <td> <a href="{{url('/earningbycash')}}/{{$key->saloon_id}}">{{$key->cash_revenue}}</a></td>            
                                            <td><a href="{{url('/earningviaonline')}}/{{$key->saloon_id}}">{{$key->online_revenue}}</a></td> 
                                            <td><a href="{{url('/earningintotal')}}/{{$key->saloon_id}}">{{$key->whole_revenue}}</a></td>           
                                           
                                            
                                           

                                            
                                            <!-- <td>
                                                <div style="display:flex;">
                                                <a href="{{url('/Saloon')}}/{{$key->saloon_id}}/edit">
                                                    <i id="icon1" class="fa fa-eye btn btn-dark "></i> </a>
                                                <form method="Post" action="{{url('/Saloon')}}/{{$key->saloon_id}}">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button
                                                        style="padding: initial;"
                                                        type="submit" class="btn btn-danger "
                                                        onclick="return confirm('Are You Sure You Want to Delete This Entry')"><i
                                                            id="icon1"
                                                            class="fa fa-trash btn btn-danger "></i></button>
                                                </form>
                                                 </div>
                                            </td> -->
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
   
   

    <!-- edit modal for saloon service -->
    <div class="modal fade" id="exampleModal-5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel-5"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel-5">Edit Service</h5>
                    <button type="button" class="close" onclick="javascript:window.location.reload()"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{url('/update-service')}}">
                        <div class="form-group">
                            <label for="service-name" class="col-form-label">Service Name:</label>
                            @csrf
                            <input type="text" class="form-control" readonly="" required="" name="service_name"
                                id="service_names">
                            <input type="hidden" name="service_name" id="service_id">
                            <input type="hidden" name="Source" value="Web">
                            <input type="hidden" name="saloon_id" id="saloon_ids" value="">
                            <input type="hidden" name="saloon_service_id" id="saloon_service_id" value="  " required="">
                        </div>
                        <div class="form-group">
                            <label for="service_time">other service</label>
                            <input type="text" class="form-control" name="other" id="others" readonly="">
                        </div>
                        <div class="form-group">
                            <label for="service-price" class="col-form-label">Service Price:</label>
                            <input type="number" class="form-control" required="" name="service_price"
                                id="service_prices" required="">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-light" onclick="javascript:window.location.reload()"
                        data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>    
    @include('layouts/footer')
    @include('layouts/table')
    <script src="{{asset('js\alerts.js')}}"></script>
    <script>
    //function to show service list  
    function services_list(id) {
        // console.log(id);
          var saloon_id = id
        //var web="web"
        document.getElementById('saloon_idd').value = id;
        $.ajax({
            dataType: 'json',
            type: 'get',
            url: './service_list/' + saloon_id + '/web',
            //data:{saloon_id},
            success: function(data) {
                console.log(data);
                var res = '';
                var i = 1;
                $.each(data['data'], function(key, value) {

                    res +=
                        '<tr>' +
                        '<td>' + i++ + '</td>' +
                        '<td  name="service_name">' +
                        `${value.service_name ? value.service_name : `Other (${value.other})`}` +
                        '</td>' +

                        '<td  name="service_price">' + value.service_price + '</td>' +
                        '<input type="hidden" name="saloon_id" value=' + value.saloon_id + '>' +
                        '<td ><a id=' + value.saloon_service_id +
                        ' data-toggle="modal"  data-target="#exampleModal-5" onclick="editService(this.id)"><i id="icon1" class="fa fa-pencil btn btn-primary " "></i></a><a id=' +
                        value.saloon_service_id +
                        ' onclick="deleteRecord(this.id)"><i id="icon1" class="fa fa-trash btn btn-danger "></i></a' +
                        +'</td>' +
                        '{{ csrf_field() }}'
                    '</tr>';
                });
                $("#here1").html(res);
            }
        });
    }

    // function to add new service
    function add_service(id) {
        // var id=  document.getElementsById('saloon_idd')[0].value
        var id = document.getElementById('saloon_idd').value
        $('#exampleModal-4').modal('hide');
        console.log(id);
        document.getElementById('saloon_id').value = id
    }

    // function to delete  service record
    function deleteRecord(saloon_service_id) {
        
        var saloon_service_id = saloon_service_id;
        var Source = 'Source';
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "./delete-service",
            type: 'DELETE',
            data: {
                "saloon_service_id": saloon_service_id,
                "Web": Source,
                "_token": token,
            },
            success: function(data) {
                var url = window.location.href;
                // window.alert(url);
                swal("Done!", data['message']);
                // window.open(url+'_self')
                window.open(url + '', '_self')
            }
        });

    }

    //function to edit service record
    function editService(saloon_service_id) {
        $('#exampleModal-4').modal('hide');
        var saloon_service_id = saloon_service_id;
        console.log(saloon_service_id);
        // console.log($('input[name="_token"]').val());
        var token = $("meta[name='csrf-token']").attr("content");
        $.ajax({
            url: "./edit-service",
            type: 'GET',
            data: {
                "saloon_service_id": saloon_service_id,
            },
            success: function(data) {
                console.log(data);
                $.each(data, function(key, value) {
                    console.log(value.saloon_id);
                    document.getElementById('service_names').value = value.service_name;
                    document.getElementById('service_prices').value = value.service_price;
                    document.getElementById('others').value = value.other;
                    document.getElementById('saloon_ids').value = value.saloon_id;
                    document.getElementById('saloon_service_id').value = value.saloon_service_id;
                    document.getElementById('service_id').value = value.service_id;

                });
                // setInterval('location.reload()', 500);     
            }
        });

    }
    </script>
    <script>
    $("#success-alert").fadeTo(2000, 500).slideUp(500, function() {
        $("#success-alert").slideUp(500);
    });


    function update_status(element) {
        var saloon_id = element.id;
        var admin_approval = element.value;
        var token = $("meta[name='csrf-token']").attr("content");
        console.log(saloon_id, admin_approval);
        swal({
                text: "Are you sure you want to approve this saloon?",
                icon: "warning",
                buttons: ['NO', 'YES'],
                dangerMode: true
            })
            .then(function(value) {
                console.log('returned value:', value);
                if (value) {
                    $.ajax({
                        url: "{{url('/Admin-approval')}}",
                        type: 'POST',
                        data: {
                            "saloon_id": saloon_id,
                            "admin_approval": admin_approval,
                            "_token": token,
                        },
                        success: function(data) {

                            console.log(data);
                            if (data == "Saloon Status updated" || data ==
                                "Saloon Status not updated") {
                                alert(data);
                                swal("Done!", "Status Updated Succesfully", "success");
                            } else {
                                var url = window.location.origin;
                                window.open(url + '/permisiondenied', '_self')
                            }

                        }
                    });
                } else {
                    window.open('{{url("/Saloon")}}', '_self')
                }
            });
    }
    </script>
    <script>
    var No = 1;

    function update_statuss(element, no) {
        console.log('hlo');
        var service_id = element.value;
        console.log(service_id);
        if (service_id == 'Other') {
            $("#hlo").show();
        } else {
            $("#hlo").hide();
        }
        No++;

    }
    </script>
    <script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    