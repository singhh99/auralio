@include('layouts/header')
<div class="container-fluid page-body-wrapper">
   <div class="main-panel">
      <div class="content-wrapper">
         <div class="row">
            <div class=" col-md-2">
               <a href="{{url('/Saloon')}}"><button type="button" class="btn btn-primary btn-fw" 
                  style="float:right; transform: translate(-42px, -11px);">Back</button></a>
            </div>
            <div class="col-12 grid-margin">
               <div class="card">
                  <h4 class="" style="background: #744df9;color: white;  padding: 10px 0 10px 21px;">Add Owner Information</h4>
                  <div class="card-body" style="padding: 2px 25px 35px;">
                     <h4 class="card-title"></h4>
                     <form class="form-sample" method="post" action="{{url('/Saloon')}}" enctype="multipart/form-data">
                        <p class="card-description">   
                        </p>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner Name</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control @error('owner_name') is-invalid @enderror" name="owner_name" placeholder="Owner Name" value="{{ old('owner_name') }}" required="" >
                                    <input type="hidden" name="Source" value="Web">
                                    @csrf
                                     @error('owner_name')
                                    <small style="color: red">{{ $message }}</small>@enderror
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner Email</label>
                                 <div class="col-sm-9">
                                    <input type="email" class="form-control @error('owner_email') is-invalid @enderror" name="owner_email"  placeholder="Owner Email ID" required=""  value="{{ old('owner_email') }}">
                                    @error('owner_email')
                                    <small style="color: red">{{ $message }}</small>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner MobileNo</label>
                                 <div class="col-sm-9">
                                    <input type="number" class="form-control @error('owner_mobile') is-invalid @enderror" name="owner_mobile" placeholder="Owner Mobile Number" required="" value="{{ old('owner_mobile') }}">
                                     @error('owner_mobile')
                                    <small style="color: red">{{ $message }}</small>
                                      @enderror
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner Pan No.</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control @error('owner_pan_number') is-invalid @enderror" name="owner_pan_number" placeholder="Owner Pan Number" required="" value="{{ old('owner_pan_number') }}">
                                     @error('owner_pan_number')
                                    <small style="color: red">{{ $message }}</small>
                                      @enderror
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner Image</label>
                                 <div class="col-sm-9">
                                    <input type="file" name="owner_image" class="file-upload-default">
                                    <div class="input-group col-xs-12">
                                       <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image" required="">
                                       <span class="input-group-append">
                                       <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                       </span>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <p class="card-description" style="font-size: 15px">
                           FILL BANK DETAILS
                        </p>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner Bank Name</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control @error('owner_bank_name') is-invalid @enderror" name="owner_bank_name" placeholder="Owner Bank Name" value="{{ old('owner_bank_name') }}" required="">
                                    @error('owner_bank_name')
                                    <small style="color: red">{{$message}}}</small>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner IFSC Code</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control @error('owner_IFSC_code') is-invalid @enderror" name="owner_IFSC_code" value="{{ old('owner_IFSC_code') }}" placeholder="Owner IFSC Code" required="">
                                     @error('owner_IFSC_code')
                                    <small style="color: red">{{$message}}</small>
                                     @enderror
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label"> Owner Account No.</label>
                                 <div class="col-sm-9">
                                    <input type="number" class="form-control @error('owner_account_number') is-invalid @enderror" name="owner_account_number" placeholder="Owner Account Number" value="{{ old('owner_account_number') }}" required="">
                                     @error('owner_account_number')
                                    <small style="color: red">{{message}}</small>
                                     @enderror
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- Saloon -->
                        <p class="card-description" style="font-size: 15px">
                           SALON INFORMATION
                        </p>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Salon Name</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control @error('saloon_name') is-invalid @enderror" name="saloon_name" placeholder="Saloon Name" value="{{ old('saloon_name') }}" required="">
                                    @error('saloon_name')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Salon Type</label>
                                 <div class="col-sm-9">
                                    <select class="js-example-basic-single" id="width1" style="width:100%" name="saloon_type_id" required="">
                                       <option selected="" disabled="">---select saloon type---</option>
                                       @foreach($type_list as $key)
                                       <option value="{{$key->saloon_type_id}}">{{$key->saloon_type_name}}</option>
                                       @endforeach
                                    </select>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Search Locality</label>
                                 <div class="col-sm-9">
                                    <input type="text" id="pac-input" class="form-control @error('saloon_area') is-invalid @enderror" name="saloon_area" placeholder="Search Saloon Locality" required="" value="{{ old('saloon_area') }}">
                                    @error('saloon_area')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                     <input type="hidden" name="saloon_lattitude" id="saloon_lattitude" value="">
                                     <input type="hidden" name="saloon_longitude" id="saloon_longitude" value="">
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Salon Address</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control" required="" name="saloon_address" placeholder="Saloon address" value="{{ old('saloon_address') }}">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Salon Time</label>
                                 <div class="col-sm-4">
                                    <div class="input-group date" id="timepicker-example" data-target-input="nearest">
                                       <div class="input-group" data-target="#timepicker-example" data-toggle="datetimepicker">
                                          <input type="text" class="form-control datetimepicker-input @error('saloon_time_from') is-invalid @enderror" data-target="#timepicker-example" name="saloon_time_from" placeholder="Saloon Time From" value="{{ old('saloon_time_from') }}" required="" id="saloon_time_from" oninput="checkTime()" autocomplete="off">
                                           @error('saloon_time_from')
                                          <small style="color: red">{{$message}}</small>@enderror
                                          <div class="input-group-addon input-group-append"><i class="mdi mdi-clock input-group-text"></i></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-sm-1">
                                    <p>to</p>
                                 </div>
                                 <div class="col-sm-4">
                                    <div class="input-group date" id="timepicker-example1" data-target-input="nearest">
                                       <div class="input-group" data-target="#timepicker-example1" data-toggle="datetimepicker">
                                          <input type="text" class="form-control datetimepicker-input @error('saloon_time_to') is-invalid @enderror" data-target="#timepicker-example1" placeholder="Saloon Time To" name="saloon_time_to" value="{{old('saloon_time_to')}}" id="saloon_time_to" required="" oninput="checkTime()" autocomplete="off">
                                           @error('saloon_time_to')
                                          <small style="color: red">{{$message}}</small>
                                          @enderror
                                          
                                          <div class="input-group-addon input-group-append"><i class="mdi mdi-clock input-group-text"></i></div>
                                          <small id="errors" style="color: red"></small>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Working Days</label>
                                 <div class="col-sm-9">
                                    <div class="form-group">
                                       <select class="js-example-basic-multiple" name="saloon_working_days[]"  multiple="multiple" style="width:100%">
                                          <option value="Sunday">Sunday
                                          <option value="Monday">Monday
                                          <option value="Tuesday">Tuesday
                                          <option value="Wednesday">Wednesday
                                          <option value="Thursday">Thursday
                                          <option value="Friday">Friday
                                          <option value="Saturday">Saturday
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">No. of seats</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control @error('saloon_total_seats') is-invalid @enderror" name="saloon_total_seats" placeholder="No. of seats" value="{{old('saloon_total_seats')}}"  required="">
                                     @error('saloon_total_seats')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Availability</label>
                                 <div class="col-sm-9 icheck-flat">
                                    @foreach($feature_list as $key)
                                    <input tabindex="5" type="checkbox" name="saloon_feature_id[]" id="flat-checkbox-1" value="{{$key->feature_id}}" >
                                    <label for="flat-checkbox-1">{{$key->feature_name}}</label>
                                    @endforeach 
                                     @error('saloon_feature_id')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-6">
                            <div class="row" id="result">
                              <div class="form-group col-md-4">
                                <label for="service_time">Service Name</label>
                                <input type="text" class="form-control" name="service_name[]" id="service_name" required="">
                              </div>
                              <div class="form-group col-md-3">
                                <label for="service_time">Service Price</label>
                                <input type="text" class="form-control" name="service_price[]" id="service_price" required="">
                              </div>
                              <!-- <div class="form-group col-md-3">
                                <label for="service_time">Time (In Minutes)</label>
                                <input type="text" class="form-control" name="service_time[]" id="service_time">
                              </div> -->
                              <div class="form-group col-md-2">
                                <label>+/-</label>
                                <button type="button" onclick="add_row();" class="btn btn-info">Next</button>
                              </div>
                            </div>
                            <div id="addval">
                            </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Salon Images</label>
                                 <div class="col-sm-9">
                                    <input type="file" name="saloon_image[]" multiple accept=".jpg,.jpeg,.png" class="form-control" required="">
                                    <!-- <div class="input-group col-xs-12">
                                       <input type="text" class="form-control file-upload-info"  placeholder="Upload Image" required="">
                                       <span class="input-group-append">
                                       <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                                       </span>

                                    </div> -->
                                    <small style="color: grey">Multiple file can be uploaded</small>
                                 </div>
                              </div>
                           </div>
                        </div>
                       <!--  <div class="row">
                           <div class="col-md-6">
                              <div class="form-group icheck-square">
                                 <input tabindex="5" type="checkbox" id="square-checkbox-1"
                                 name="is_checked" class="@error('is_checked') is-invalid @enderror">
                                 <label for="square-checkbox-1"><a>Terms of use</a></label>
                                  @error('is_checked')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                              </div>
                           </div>
                        </div> -->
                        <div class="row">
                           <div class="col-md-12" style="text-align: right;">
                              <button type="submit" class="btn btn-success mr-2">Submit</button>
                              <!-- <button class="btn btn-light">Cancel</button> -->
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@include('layouts/footer')
<script>
   function checkTime()
   {
      var startTime = moment($('#saloon_time_from').val(), "HH:mm a");
      var endTime = moment($('#saloon_time_to').val(), "HH:mm a");
      var diff = (endTime - startTime)/3600000;
      // console.log(diff);
      if(startTime == endTime || startTime > endTime || diff < 1)
      {
         console.log('same');
         document.getElementById("errors").innerHTML="Invalid Salon time to and from";
      }
      else
      {
         document.getElementById("errors").innerHTML="";
      }
   }
</script>
<script>
  var No = 1;
  function add_row()
  {
    console.log('abc');
    $("#addval").append(
       `<div class="row" id="result`+No+`">
          <div class="form-group col-md-4">
            <input type="text" class="form-control" name="service_name[]" id="service_name">
          </div>
          <div class="form-group col-md-3">
            <input type="text" class="form-control" name="service_price[]" id="service_price">
          </div>
         
          <div class="form-group col-md-2">
            <button type="button" onclick="remove_row(`+No+`);" class="btn btn-danger"><i class="mdi mdi-delete"></i></button>
          </div>
        </div`
      );
    No++;
  }

  function remove_row(id)
  {
    // console.log(id);
    $('#result'+id).remove();
  }
</script>
 <script> 
      function initMap() {
         var input = document.getElementById('pac-input');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.setFields(
            ['address_components', 'geometry', 'icon', 'name']);
        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        autocomplete.addListener('place_changed', function() {
        
          var place = autocomplete.getPlace();
          var lot = {
            lattitude: place.geometry.location.lat(),
            longitude: place.geometry.location.lng()
          }
          // console.log(lot);
          // console.log(place);
          var lattitude=place.geometry.location.lat();
          var longitude=place.geometry.location.lng();
          document.getElementById('saloon_lattitude').value=lattitude;
          document.getElementById('saloon_longitude').value=longitude;
          // var lot = {
          //   lattitude: place.geometry.location.lat(),
          //   longitude: place.geometry.location.lng()
          // }
          // console.log(place);
         
        });      
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDu_2opSSHiN8dlqyRH6zOvvS7b5il2vkg&libraries=places&callback=initMap"
        async defer></script>