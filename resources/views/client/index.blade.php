@include('layouts/header')
@include('layouts/sidebar')
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
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
                    <a href="{{url('/Saloon')}}"><button type="button" class="btn btn-primary btn-fw">BACK</button></a>
                   <h1>{{$status}} Orders</h1>
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Customer Id</th>
                        <th>Booking Order</th>
                        <th>Customer Name</th>
                        <th>Customer Mobile</th>
                        <th>Slot</th>
                        <th>Services</th>
                        <th>Amount</th>
                        <th>Payment Mode</th>
                        @if($id=='2')
                        <th>Cancel By</th>
                        <th>Cancel Reason</th> 
                        @endif     
                     </tr>
                  </thead>
                  <tbody>
                  @php $i=1 @endphp
                    @foreach($customer_detail as $key)

                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->customer_code}}</td>
                        <td>{{$key->customer_booking_code}}</td>
                         <td>{{$key->customer_name}}</td>
                        <td>{{$key->customer_mobile}}</td> 
                        <td>{{$key->slot_from}}-{{$key->slot_to}}</td>
                         <td> @foreach($key->services as $key1)
                          {{$key1->service_name.','}}@endforeach</td>
                        <td>{{$key->total_price}}</td>
                        <td>{{$key->payment_type}}</td>
                         @if($key1->booking_status_id='2')
                        <td>{{$key->cancel_by}}</td>
                        <td>{{$key->cancel_reason}}</td>
                        <td></td>
                        @endif
                        <td></td>
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