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
                    <a href="{{url('/')}}"><button type="button" class="btn btn-primary btn-fw" style="float:right;margin: right 20px ;">back</button></a>

                   <div class="row"> 
                    <h3>Cancelled Bookings</h3>
                   <div class="col-md-2">
                   <a href="{{url('/todaycancelledbookings')}}"><button type="button" class="btn btn-outline-primary btn-block"><i class="fa fa-book"></i>Todays({{$count_bookings}})</button></a>
                   </div>
                   </div>

                        <div class="card">
                            <div class="card-body">
                                <div id="success-alert">
                                    @if(Session::has('message'))
                                    <p class="alert {{ Session::get('alert-class', 'alert-success') }}">
                                        {{ Session::get('message') }}</p>
                                    @endif
                                </div>
                                <h4 class="card-title">
                                    <!-- <a href="{{url('/Saloon/create')}}"><button type="button"
                                            class="btn btn-primary btn-fw">Add New Salon</button></a> -->
                               

                                </h4>
 

                                <table id="example1" class="table table-bordered table-hover" style="font-weight:normal !important;">
                                    <thead>
                                        <tr>
                                            <th>Sr no.</th>
                                            <th>Customer Booking Code</th>
                                            <th>Salon Name</th>
                                            <!-- <th>Owner Mobile</th> -->
                                            <!-- <th>Salon Area </th> -->
                                            <th>Customer Name</th>
                                            <th>Customer Mobile</th>
                                            <th>Customer Booking Date</th>
                                            <!-- <th>Customer Schedule Date</th> -->

                                            <th>Salon Services</th>
                                            <th>Total Amount</th>
                                            <!-- <th>Salon Images</th> -->
                                            <th>Payment Type</th>
                                            <th>Cancelled By</th>
                                            <th>Cancel Reason</th>
                                            <!-- <th>Booking status</th> -->
                                            <!-- <th>Customer Bookings</th> -->
                                            <!-- <th>All Orders</th> -->
                                            <!-- <th>All Customers</th> -->
                                            <!-- <th>Bookings</th> -->
                                        
                                            <!-- <th>Actions</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i=1 @endphp
                                        @foreach($booking_list as $key)
                                        <tr>
                                            <td>{{$i++}}</td>
                                            <td>{{$key->customer_booking_code}}</td>
                                            <td>{{$key->saloon_name}}</td>
                                            <!-- <td>$key->owner_mobile</td> -->
                                            <!-- <td>$key->saloon_area</td> -->
                                            <td>{{$key->customer_name}}</td>
                                            <td>{{$key->customer_mobile}}</td>
                                            <td>{{$key->customer_booking_date}}</td>
                                            <!-- <td>{{$key->customer_schedule_date}}</td> -->
                                            <!-- <td> <button type="button" style="height: 35px"
                                                    class="btn btn-block btn-outline-dark btn-sm" data-toggle="modal"
                                                    id="{{$key->saloon_id}}" onclick="services_list(this.id)"
                                                    data-target="#exampleModal-4" data-whatever="@mdo">Services</button>
                                            </td> -->
                                            <td> @foreach($key->services as $key1)
                                                     {{$key1->service_name.','}}@endforeach</td>
                                            <td>{{$key->total_price}}</td>
                                            <!-- <td><a
                                                    href="{{url('/Saloon-Images')}}/{{$key->saloon_id}}/all-images/{{'Web'}}">

                                                    <button type="button" style="height: 35px;margin-left: 8px"
                                                        class="btn btn-block btn-outline-warning btn-sm">Images</button></a></td> -->
                                            <td>{{$key->payment_type}}</td> 
                                            <td>{{$key->cancel_by}}</td>
                                            <td>{{$key->cancel_reason}}</td>
                                            <!-- <td>{{$key->booking_status_id}}</td> -->
                                            <!-- <td><a href="{{url('/Order-History')}}/{{$key->customer_id}}"> <button type="button" style="height: 35px"  class="btn btn-outline-dark btn-rounded btn-fw" >Previous|Orders</button></a></td> -->
                                            <!-- <td><a href="{{url('/All-Ordrers')}}/{{$key->saloon_id}}/{{'Web'}}"> <button
                                                        type="button" style="height: 35px"
                                                        class="btn btn-outline-dark btn-rounded btn-fw">
                                                        Orders</button></a></td> -->
                                            <!-- <td><a href="{{url('/Lastest-Orders')}}/{{$key->saloon_id}}">
                                                    <button type="button" style="height: 35px"
                                                        class="btn btn-outline-info btn-rounded btn-fw">
                                                        Customers</button></a></td> -->
                                            <!-- <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary"
                                                        style="width: 65px;height: 38px">Type</button>

  

                                                    <button class="btn btn-primary dropdown-toggle dropdown-toggle-split" type="button"
                                                        id="dropdownMenuButton2" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        
                                                    </button>

                                                    <ul class="dropdown-menu dropdown-menu"
                                                        aria-labelledby="dropdownMenuButton2">
                                                        <li> <a class="dropdown-item"
                                                                href="{{url('/Vendors-Orders')}}/{{$key->saloon_id}}/3">UPCOMING</a>
                                                        </li>
                                                        <li> <a class="dropdown-item"
                                                                href="{{url('/Vendors-Orders')}}/{{$key->saloon_id}}/10">DELAYED</a>
                                                        </li>
                                                        <li> <a class="dropdown-item"
                                                                href="{{url('/Vendors-Orders')}}/{{$key->saloon_id}}/2">CANCELLED</a>
                                                        </li>
                                                      </ul>

                                                </div>
                                            </td> -->

                                            <!-- <td>
                                                <meta name="csrf-token" content="{{ csrf_token() }}">
                                                <select class="form-control" style="width: 140px;margin-left: 8px"
                                                    name="admin_approval" id="{{$key->saloon_id}}"
                                                    onchange="update_status(this)">
                                                    <option value="1"
                                                         if($key->admin_approval=="1") echo "selected"; ?>
                                                        onclick="showSwal('warning-message-and-cancel')"
                                                        onchange="update_status(this)">Approved</option>
                                                    <option value="0"
                                                         if($key->admin_approval=="0") echo "selected"; ?>
                                                        onclick="showSwal('warning-message-and-cancel')">Not Approved
                                                    </option>
                                                </select>
                                            </td> -->
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
    <!-- <script>
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
    </script> -->
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
    