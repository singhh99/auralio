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
                   <h1> Order Details</h1>
                   </h4>
                    <table id="example" class="table" >
                  <thead>
                     <tr>
                        <th>Sr no.</th>
                        <th>Booking Order</th>
                        <th>Salon Name</th>
                        <th>Customer Name</th>
                       
                        <th>Slot</th>      
                     </tr>
                  </thead>
                  <tbody>
                  @php $i=1 @endphp
                  
                    @foreach($data as $key)

                     <tr>
                        <td>{{$i++}}</td>
                        <td>{{$key->customer_booking_code}}</a></td>
                         <td>{{$key->customer_name}}</td>
                        <td>{{$key->customer_mobile}}</td> 
                        <td>{{$key->slot_from}}-{{$key->slot_to}}</td>
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