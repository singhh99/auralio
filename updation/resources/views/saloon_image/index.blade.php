@include('layouts/header')
<link rel="stylesheet" href="{{asset('vendors\lightgallery\css\lightgallery.css')}}">
<div class="container-fluid page-body-wrapper">
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="row grid-margin">
        <div class="col-lg-12">
          <div class="main-panel">
             <div class="content-wrapper">
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
                        <a href="{{asset('images/saloon_image')}}/{{$key->saloon_image}}" class="image-tile" target="_blank"><img src="{{asset('images/saloon_image')}}/{{$key->saloon_image}}" alt="image small" style="width: 300px;height: 200px"> <form method="Post" 
                         action="{{url('/saloon-images')}}/{{$key->saloon_image_id}}">
                              <input type="hidden" name="_method" value="DELETE">
                              <input type="hidden" name="Source" value="Web">
                              <input type="hidden" name="saloon_id" value="{{$key->saloon_id}}">
                              @csrf<button class="remove-image" href="#" title="delete image" style="display: inline;">&#215;</button> </form></a>
                      @else
                        <a href="{{asset('public/images/saloon_image')}}/{{$key->saloon_image}}" class="image-tile" target="_blank"><img src="{{asset('images/saloon_image')}}/{{$key->saloon_image}}" alt="image small" style="width: 300px;height: 200px"> <form method="Post" 
                         action="{{url('/saloon-images')}}/{{$key->saloon_image_id}}">
                              <input type="hidden" name="_method" value="DELETE">
                              <input type="hidden" name="Source" value="Web">
                              <input type="hidden" name="saloon_id" value="{{$key->saloon_id}}">
                              @csrf<button class="remove-image" href="#" title="delete image" style="display: inline;">&#215;</button> </form></a>
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
</div>

<!-- footer-->
@include('layouts/footer')
 
