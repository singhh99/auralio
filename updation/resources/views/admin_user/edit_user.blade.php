@include('layouts/header')
<div class="container-fluid page-body-wrapper">
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
              <div class=" col-md-3">
                  <a href="{{url('/Admin-User')}}"><button type="button" class="btn btn-primary btn-fw" 
                  style="float:right;">Back</button></a>
              </div>
             
              <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
              <h4 class="" style="background: linear-gradient(30deg, #464de4, #814eff);color: white;padding: 8px 0 8px 21px;">Edit User</h4>
                <div class="card-body" style="padding: 20px 25px;">
                  @foreach($user_list as $key)
                  <form class="forms-sample" method="post" action="{{url('/Admin-User')}}/{{$key->id}}">
                     @csrf
                     @method('PUT')
                    <div class="form-group">
                      <label for="exampleInputName1">User Name</label>
                      <input type="text" class="form-control" name="name" required="" id="exampleInputName1" placeholder="enter name" value="{{$key->name}}">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">User Mobile Number</label>
                      <input type="text" class="form-control" name="mobile_no" required="" id="exampleInputNumber3" placeholder="enter mobile no." value="{{$key->mobile_no}}">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputEmail3">User Email ID</label>
                      <input type="email" class="form-control" name="email" required="" id="exampleInputNumber3" placeholder="enter email id" value="{{$key->email}}">
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
@include('layouts/footer')
