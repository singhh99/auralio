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
          <a href="{{url('/Saloon-Images')}}/{{$saloon_id}}/all-images">
            <button type="button" class="btn btn-primary btn-fw">Back</button>
          </a>
        </div>
        <div class="col-md-6 col-sm-12 grid-margin stretch-card">
            <div class="main-panel" style="padding: 0px">
             <div class="content-wrapper">
               <div class="card"><h4 class="">
                  Add Saloon Images
                   </h4>
                 <div class="card-body" id="body1">
                   <form class="forms-sample" method="post" action="{{url('/Saloon-Images/{saloon_id}/web')}}" enctype="multipart/form-data">
                   	@csrf
                    <div class="form-group">
                      <label for="exampleInputName1">Saloon Images</label>
                      <input type="hidden" name="saloon_id" value="{{$saloon_id}}">
                      <input type="hidden" name="Source" value="Web">
                      <input type="file" name="saloon_image[]" multiple accept=".jpeg,.png,.jpg" id="saloon_image"   class="form-control" required="">
                       <!--  <div class="input-group col-xs-12">
                          <input type="text" class="form-control file-upload-info"  placeholder="Upload saloon Image" required="">
                            <span class="input-group-append">
                              <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                            </span>
                             @error('saloon_image')
                             <small style="color: red">{{$message}}</small>
                            @enderror
                       </div>  -->
                    
                     </div>
                      <button type="submit" class="btn btn-success mr-2">Submit</button>
                      <!-- <button class="btn btn-light">Cancel</button> -->
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