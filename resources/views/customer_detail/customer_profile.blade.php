@include('layouts/header')
@include('layouts/sidebar')
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
              <div class=" col-md-3">
                  <a href="{{url('/Lastest-Orders')}}/{{$saloon_id}}"><button type="button" class="btn btn-primary btn-fw" 
                  style="float:right;">Back</button></a>
              </div>
             
              <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
              <h4 class="" style="background: linear-gradient(30deg, #464de4, #814eff);color: white;padding: 8px 0 8px 21px;">Customer Profile</h4>
                <div class="card-body" style="padding: 20px 25px;">
                 @foreach($customer_detail as $key)
                  <form class="forms-sample">
                    @csrf
                    @if($key->customer_image && env('APP_ENV') == 'local')
                      <div class="form-group" style="text-align: center;">
                        <img src="{{asset('images/customer_image')}}/{{$key->customer_image}}" height="100px" width="100px">
                      </div>
                    @elseif($key->customer_image)
                    <div class="form-group" style="text-align: center;">
                        <img src="{{asset('public/images/customer_image')}}/{{$key->customer_image}}" height="100px" width="100px">
                      </div>
                      @else
                      <div class="form-group" style="text-align: center;">
                        <label for="Preview">Image Not Available</label>
                      </div>
                    @endif
                   
                    <div class="form-group">
                      <label for="exampleInputName1">Customer Name</label>
                      <input type="text" class="form-control" name="customer_name" required="" id="exampleInputName1" readonly="" placeholder="enter name" value="{{$key->customer_name}}">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Customer Mobile Number</label>
                      <input type="text" class="form-control" name="customer_mobile" required="" id="exampleInputNumber3" readonly="" placeholder="enter mobile no." value="{{$key->customer_mobile}}">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">Customer Email </label>
                      <input type="email" class="form-control" name="customer_email" required="" id="exampleInputNumber3" readonly="" placeholder="enter email id" value="{{$key->customer_email}}">
                    </div>
                     <div class="form-group">
                      <label for="exampleInputEmail3">Customer Gender </label>
                      <input type="email" class="form-control" name="customer_gender" required="" id="exampleInputNumber3" readonly="" placeholder="enter email id" value="{{$key->customer_gender}}">
                    </div>
                  </form>
                  @endforeach
                </div>
              </div>
          </div>
        </div>
      </div>
</div>
@include('layouts/footer')
