
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>CombyCut</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="{{asset('vendors\iconfonts\mdi\css\materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{asset('vendors\css\vendor.bundle.base.css')}}">
  <link rel="stylesheet" href="{{asset('vendors\css\vendor.bundle.addons.css')}}">
  <link rel="stylesheet" href="{{asset('css\style.css')}}">
  <link rel="shortcut icon" href="{{asset('images\favicon.png')}}">
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth register-bg-1 theme-one">
        <div class="row w-100 mx-auto">
          <div class="col-lg-4 mx-auto">
             @if(isset($otp))
             <h2 class="text-center mb-4">OTP Verification<small>(Step-2)</small></h2>
            <div class="auto-form-wrapper">
              <form action="{{url('/verify-otp')}}" method="POST">
                 @csrf
                  <div id="success-alert"> 
                      @if(Session::has('message'))
                     <p class="alert {{ Session::get('alert-class', 'alert-warning') }}">{{ Session::get('message') }}</p>
                      @endif
                    </div>
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" placeholder="User registered mobile number" readonly="" name="mobile_no" value="{{$mobile_no }}" required  autofocus>
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="mdi  mdi-cellphone-android"></i></span>
                       
                    </div>
                    @error('mobile_no')
                        <span class="invalid-feedback" role="alert">
                         <strong>{{ $mobile_no }}</strong>
                        </span>
                      @enderror
                  </div>
                </div>
               
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control @error('otp') is-invalid @enderror" placeholder="Enter your OTP" name="otp" >
                    <div class="input-group-append" >
                      <span class="input-group-text"><i class="mdi mdi-key-variant" ></i></span>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                     <button type="submit" class="btn btn-primary submit-btn btn-block" id="sub">Submit</button>
                </div>
              </form>
            </div>
            @else
            <h2 class="text-center mb-4">OTP Verification<small>(Step-1)</small></h2>
            <div class="auto-form-wrapper">
              <form action="{{url('/forgot-password')}}" method="POST">
                 @csrf
                  <div id="success-alert"> 
                      @if(Session::has('message'))
                     <p class="alert {{ Session::get('alert-class', 'alert-warning') }}">{{ Session::get('message') }}</p>
                      @endif
                    </div>
                <div class="form-group">
                  <div class="input-group">
                    <input type="text" class="form-control @error('mobile_no') is-invalid @enderror" placeholder="User registered mobile number " name="mobile_no" value="{{ old('mobile_no') }}" max="10"  required  autofocus>
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="mdi  mdi-cellphone-android"></i></span>
                       
                    </div>
                    @error('mobile_no')
                        <span class="invalid-feedback" role="alert">
                         <strong>{{ $mobile_no }}</strong>
                        </span>
                      @enderror
                  </div>
                </div>
                <div class="form-group">
                     <button type="submit" class="btn btn-primary submit-btn btn-block" id="sub">Submit</button>
                </div>
              </form>
            </div>
            @endif
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <script src="{{asset('vendors\js\vendor.bundle.base.js')}}"></script>
  <script src="{{asset('vendors\js\vendor.bundle.addons.js')}}"></script>
  <script src="{{asset('js\template.js')}}"></script>
</body>

</html>
<script>
  $("#success-alert").fadeTo(2000, 500).slideUp(500, function(){
    $("#success-alert").slideUp(500);
});
</script>
