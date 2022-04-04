@include('layouts/header')
@include('layouts/sidebar')
<style>
  .card h4{
    background-color:#3f4a54; padding: 5px 0 0 20px; color: white; height: 30px;  
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
                  <a href="{{url('/City')}}"><button type="button" class="btn btn-primary btn-fw">Back</button></a>
              </div>
        <div class="col-md-6 col-sm-12 grid-margin stretch-card">
            <div class="main-panel">
             <div class="content-wrapper">
               <div class="card"><h4 class="">
                   Add City
                   </h4>
                 <div class="card-body" id="body1">
                   <form class="forms-sample" method="post" action="{{url('/City')}}">
                   	@csrf
                    <div class="form-group">
                        <label>State Name</label>
                        <select class="js-example-basic-single" id="width1" style="width:100%" name="state_id">
                          <option selected="" disabled="">---select state----</option>
                          @foreach($data as $key)
                           <option value="{{$key->state_id}}">{{$key->state_name}}</option>
                          @endforeach
                          
                        </select>
                      </div>
                    <div class="form-group">
                      <label for="exampleInputName1">City Name</label>
                      <input type="text" class="form-control" id="" name="city_name" placeholder="enter city name "  required="">
                    </div> 
                   <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
                  </form>
                 </div>
               </div>
             </div>
        </div>     
      </div>
    </div>
  </div>
</div>
@include('layouts/footer')