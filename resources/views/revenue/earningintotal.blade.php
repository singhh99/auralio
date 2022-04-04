@include('layouts/header')
@include('layouts/sidebar')

<style>
  .cash{
/* background-color: #5cd65c; */
font-weight: bold;
color: #26cc23;}
  .online{
/* background-color: #5cd65c; */
font-weight: bold;
color: #3a23cc;
}
  </style>
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    
      <div class="row">        
        <div class="col-md-12 col-sm-12 grid-margin stretch-card">
            <div class="main-panel">
             <div class="content-wrapper">
             <h3>{{$saloon_name}} Revenues                    
                    <a href="{{url('/revenue')}}"><button type="button" class="btn btn-primary btn-fw" style="float: right;" >back</button></a></h3>
               <div class="card">
                 <div class="card-body">
                  <div id="success-alert"> 
                   @if(Session::has('message'))
                     <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
                      @endif
                    </div>
                   <!-- <h4 class="card-title"> -->
                    <button type="button" class="btn btn-outline-primary btn-fw" style="float: right;">Total=₹{{$totalrevenue}}</button>
                   <!-- </h4> -->
                   <div class="card-body">
                   
                    <table id="example" class="table"  >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <!-- <th>Customer Id</th> -->
                        <!-- <th>Name</th> -->
                        <th>Order No.</th>
                        
                        <th>Order Date</th>
                        <!-- <th>Slot Time</th> -->
                        <!-- <th>Services</th> -->
                        <!-- <th>Booking Status</th> -->
                        <th>Payment Mode</th>
                        <th>Payment Amount</th>
                        <th>Commission Rate</th>
                        <th>Commission Build</th>
                        <th>Settlement Amount</th>
                     </tr>
                  </thead>
                  <tbody>
                    @php $i=1 @endphp
                    @foreach($bookings_detail as $key)
                   
                     <tr>

                        <td>{{$i++}}</td>
                        
                        
                        <td>{{$key->customer_booking_code}}</td>
                        
                        <td>{{$key->customer_booking_date}}</td>
                        <!-- <td>$key->slot_from-$key->slot_to</td> -->
                        <!-- <td> foreach($key->services as $key1) -->
                          <!-- $key1->service_name.','endforeach</td> -->
                        <!-- <td>$key->booking_status_name</td> -->
                        <td>{{$key->payment_type}}</td>
                        <td>{{$key->total_price}}</td>
                        @if($key->commission_rate!=NULL) <td>{{$key->commission_rate}}%</td>@else<td>{{$key->commission_rate}}</td>@endif
                        <td>{{$key->amount_build}}</td>
                        <td class="{{$key->payment_type}}">{{$key->settlement_amount}}</td>
                        
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