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
                    <a href="{{url('/Saloon')}}"><button type="button" class="btn btn-primary btn-fw">back</button></a>
                   <h2>All Customers  Details</h2>
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Customer Code</th>
                        <th>Customer Name</th>
                        <th>Customer Mobile</th>
                        <th>Order Number </th>
                        <th>Booking Date</th>
                        <th>Slot Time</th>
                        <th>Services</th>
                        <th>Payment Amount</th>
                        <th>Order Status</th>
                        <th>Orders</th>

                     </tr> 
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($customer_detail as $key)
                     <tr>
                      
                        <td>{{$i++}}</td>
                        <td>{{$key->customer_code}}</td>
                        <td><a href="{{url('/Customer-Detail')}}/{{$key->customer_id}}">{{$key->customer_name}}</a></td>
                        <td>{{$key->customer_mobile}}</td>
                        <td>{{$key->customer_booking_code}}</td>
                        <td>{{$key->customer_booking_date}}</td>
                        <td width="123px">{{$key->slot_from}}-{{$key->slot_to}}</td>
                        <td> @foreach($key->services as $key1)
                          {{$key1->service_name.','}}@endforeach</td>
                        <td>{{$key->total_price}}</td>
                        <td>{{$key->booking_status_name}}</td>
                        <td><a href="{{url('/Order-History')}}/{{$key->customer_id}}"> <button type="button" style="height: 35px"  class="btn btn-outline-dark btn-rounded btn-fw" >Previous|Orders</button></a></td>
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
  $("#success-alert").fadeTo(3000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>