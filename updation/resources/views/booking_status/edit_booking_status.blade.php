@include('layouts/header')
<style>
  .card h4{
    background-color:#744df9; padding: 5px 0 0 20px; color: white; height: 30px;  
  }
  #body1{
    padding: 20px 25px;
  }
</style>
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
              <div class=" col-md-3">
                   <a href="{{url('/Booking-Status')}}"><button type="button" class="btn btn-primary btn-fw">Back</button></a>
              </div>
        <div class="col-md-6 col-sm-6 grid-margin stretch-card">
            <div class="main-panel">
             <div class="content-wrapper">
               <div class="card"><h4 class="">
                  Edit Booking Status
                   </h4>
                 <div class="card-body" id="body1">
                   @foreach($status_list as $key)
                   <form class="forms-sample" method="post" action="{{url('/Booking-Status')}}/{{$key->booking_status_id}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                      <label for="exampleInputName1">Booking Status Name</label>
                      <input type="text" class="form-control" id="" name="booking_status_name" placeholder="enter booking status name" value="{{$key->booking_status_name}}"  required="">
                    </div> 
                   <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <!-- <button class="btn btn-light">Cancel</button> -->
                  </form>
                  @endforeach
                 </div>
               </div>
             </div>
        </div>     
      </div>
    </div>
  </div>
</div>
@include('layouts/footer')
