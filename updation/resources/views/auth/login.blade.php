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
            <h2 class="text-center mb-4">Combycut Login</h2>
            <div class="auto-form-wrapper">
              <form action="{{route('login') }}" method="POST">
                 @csrf
                <div class="form-group">
                  <div class="input-group">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" placeholder="User email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="mdi mdi-email"></i></span>
                       
                    </div>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                         <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" id="myInput">
                    <div class="input-group-append" onclick="myFunction()">
                      <span class="input-group-text"><i class="mdi mdi-eye" ></i></span>
                    </div>
                     @error('password')
                      <span class="invalid-feedback" role="alert">
                       <strong>{{ $message }}</strong>
                      </span>
                      @enderror
                  </div>
                </div>
                <div class="form-group">
                     <button type="submit" class="btn btn-primary submit-btn btn-block">Login</button>
                </div>
              </form>
            </div>
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
 function myFunction() {
  var x = document.getElementById("myInput");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>