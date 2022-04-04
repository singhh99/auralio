@include('layouts/header')
@include('layouts/sidebar')
<style>
  .card h4{
    background-color:#3f4a54; padding: 5px 0 0 20px; color: white; height: 30px;  
  }
  #body1{
    padding: 20px 25px;
  }
  .card-body{
    overflow: auto;
  }
</style>

<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class=" col-md-3">
          <a href="{{url('/Aggrement')}}">
            <button type="button" class="btn btn-primary btn-fw">Back</button>
          </a>
        </div>
        <div class="col-md-6 col-sm-12 grid-margin stretch-card">
            <div class="main-panel" style="padding: 0px">
             
               <div class="card"><h4 class="">
                  Edit Salon Aggrement
                   </h4>
                 <div class="card-body" id="body1">
                   @foreach($aggrement_list as $key)
                   <form class="forms-sample" method="post" action="{{url('/Aggrement')}}/{{$key->aggrement_id}}" enctype="multipart/form-data">
                   	@csrf
                     @method('PUT')
                    <div class="form-group">
                      <label for="exampleInputName1">Aggrement Type</label>
                      <input type="text" class="form-control" id="" name="aggrement_type"  value="{{$key->aggrement_type}}"  required="" readonly="">
                     </div>
                    <div class="form-group">
                      <label for="exampleInputName1">Aggrement File</label>
                      <input type="file" name="aggrement_file"  id="aggrement_file"   class="form-control" value="{{$key->aggrement_file}}">
                      <span>{{$key->aggrement_file}}</span>
                     </div>
                      <button type="submit" class="btn btn-success mr-2">Submit</button>
                    </form>
                    @endforeach
                   </div>
                 </div>
               </div>
               
        </div>
      </div>
    </div>
  </div>
@include('layouts/footer')