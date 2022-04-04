@include('layouts/header')
@include('layouts/sidebar')
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
              <div class=" col-md-3">
                  <a href="{{url('/Country')}}"><button type="button" class="btn btn-primary btn-fw">Back</button></a>
              </div>
        <div class="col-md-6 col-sm-12 grid-margin stretch-card">
            <div class="main-panel">
             <div class="content-wrapper">
               <div class="card">
                 <div class="card-body">
                   <h4 class="card-title">
                    Edit Country
                   </h4>
                   @foreach($data as $key)
                   <form class="forms-sample" method="post" action="{{url('/Country')}}/{{$key->country_id}}">
                   	@csrf
                    @method('PUT')
                    <div class="form-group">
                      <label for="exampleInputName1">Country Name</label>
                      <input type="text" class="form-control" id="" name="country_name" placeholder="enter country name " value="{{$key->country_name}}" required="">
                    </div>   
                    <button type="submit" class="btn btn-success mr-2">Submit</button>
                    <button class="btn btn-light">Cancel</button>
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