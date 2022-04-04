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
                   <!-- <h4 class="card-title"> -->
                    <a href="{{url('/Saloon')}}"><button type="button" class="btn btn-primary btn-fw">Back</button></a>
                   <!-- </h4> -->
                   <div class="card-body">
                   <h1>All Orders</h1>
                    <table id="example" class="table"  >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Customer Id</th>
                        <th>Name</th>
                        <th>Order No.</th>
                        <th>Salon Name</th>
                        <th>Order Date</th>
                        <th>Slot Time</th>
                        <th>Services</th>
                        <th>Booking Status</th>
                        <th>Payment Mode</th>
                        <th>Payment Amount</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($customer_detail as $key)
                   
                     <tr>

                        <td>{{$i++}}</td>
                        <td><a href="{{url('/Customer-Detail')}}/{{$key->customer_id}}">{{$key->customer_code}}</a></td>
                        <td>{{$key->customer_name}}</td>
                        <td>{{$key->customer_booking_code}}</td>
                        <td>{{$key->saloon_name}}</td>
                        <td>{{$key->customer_booking_date}}</td>
                        <td>{{$key->slot_from}}-{{$key->slot_to}}</td>
                        <td> @foreach($key->services as $key1)
                          {{$key1->service_name.','}}@endforeach</td>
                        <td>{{$key->booking_status_name}}</td>
                        <td>{{$key->payment_type}}</td>
                        <td>{{$key->total_price}}</td>
                        
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
<script>
  $("#success-alert").fadeTo(3000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>