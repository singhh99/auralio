@include('layouts/header')
@include('layouts/sidebar')
<link rel="stylesheet" href="{{asset('vendors\lightgallery\css\lightgallery.css')}}">
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row grid-margin">
        <div class="col-lg-12">
          <div class="main-panel">
             
              <div class="card">
                <div class="card-body">
                  <a href="{{url('Sallon-Images')}}/{{$saloon_id}}/add-images"> 
                    <button class="btn btn-primary btn-fw">Add Saloon Images</button>
                  </a>
                  <p class="card-text">
                    
                  </p>
                  <div id="lightgallery" class="row lightGallery">
                    @foreach($images as $key)
                      @if(env('APP_ENV') == 'local')
                        <a href="{{asset('public/images/saloon_image')}}/{{$key->saloon_image}}" class="image-tile" target="_blank"><img src="{{asset('public/images/saloon_image')}}/{{$key->saloon_image}}" alt="image small" style="border-radius: 15px;
                                                    border: 2px solid #c2c2a3;
                                                    padding: 2px;
                                                    width: 200px;
                                                    height: 150px;"> 
                                                    <form method="Post" 
                         action="{{url('/saloon-image')}}/{{$key->saloon_image_id}}">
                              <input type="hidden" name="_method" value="DELETE">
                              <input type="hidden" name="Source" value="Web">
                              <input type="hidden" name="saloon_id" value="{{$key->saloon_id}}">
                              @csrf<button class="btn btn-danger btn-sm pull-right" href="#" title="delete image" style="display: inline-block;"><i class="fas fa-trash-alt"></i></button> </form></a>
                      @else
                        <a href="{{asset('public/images/saloon_image')}}/{{$key->saloon_image}}" class="image-tile" target="_blank"><img src="{{asset('public/images/saloon_image')}}/{{$key->saloon_image}}" alt="image small" style="  border-radius: 15px;
                                                    border: 2px solid #c2c2a3;
                                                    padding: 2px;
                                                    width: 200px;
                                                    height: 150px;"> 
                        <form method="Post" 
                         action="{{url('/saloon-image')}}/{{$key->saloon_image_id}}">
                              <input type="hidden" name="_method" value="DELETE">
                              <input type="hidden" name="Source" value="Web">
                              <input type="hidden" name="saloon_id" value="{{$key->saloon_id}}">
                              @csrf<button class="btn btn-danger btn-sm pull-right " href="#" title="delete image" style=" display: inline-block;"><i class="fas fa-trash-alt"></i></button> </form></a>
                      @endif
                    @endforeach
                  </div>
                </div>
              </div>
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- footer-->
@include('layouts/footer')
 
