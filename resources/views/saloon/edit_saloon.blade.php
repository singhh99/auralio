@include('layouts/header')
@include('layouts/sidebar')
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
<div class="container-fluid page-body-wrapper">
   <div class="main-panel">
      <div class="content-wrapper">
         <div class="row">
            <div class=" col-md-2">
               <a href="{{url('/Saloon')}}"><button type="button" class="btn btn-primary btn-fw" 
                  style="margin:2px">Back</button></a>
            </div>
            <div class="col-12 grid-margin">
               <div class="card">
                  <h4 class="" style="background: #3f4a54;color: white;  padding: 10px 0 10px 21px;">Edit Owner Information</h4>
                  <div class="card-body" style="padding: 2px 25px 35px;">
                     <h4 class="card-title"></h4>
                     @foreach($saloon_details as $info)
                     <form class="form-sample" method="post" action="{{url('/Saloon')}}/{{$info->saloon_id}}" enctype="multipart/form-data">
                         @php $days= json_decode($info->saloon_working_days); @endphp
                        <p class="card-description">   
                        </p>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner Name</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control @error('owner_name') is-invalid @enderror" name="owner_name" placeholder="Owner Name" value="{{$info->owner_name}}" required="">
                                    @csrf
                                    @method('PUT')
                                    @error('owner_name')
                                    <small style="color: red">{{ $message }}</small>@enderror
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner Email</label>
                                 <div class="col-sm-9">
                                    <input type="email" class="form-control @error('owner_email') is-invalid @enderror" name="owner_email" placeholder="Owner Email ID" value="{{$info->owner_email}}" required="">
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
                                    <input type="number" class="form-control @error('owner_mobile') is-invalid @enderror" name="owner_mobile" placeholder="Owner Mobile Number" value="{{$info->owner_mobile}}" required="">
                                    <small style="color: red"></small>
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Owner Pan No.</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control @error('owner_pan_number') is-invalid @enderror" name="owner_pan_number" placeholder="Owner Pan Number" value="{{$info->owner_pan_number}}" >
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
                                    <input type="file" name="owner_image" class="file-upload-default" value="{{$info->owner_image}}">
                                    <div class="input-group col-xs-12">
                                       <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload Image" value="{{$info->owner_image}}" >
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
                                    <input type="text" class="form-control @error('owner_bank_name') is-invalid @enderror" name="owner_bank_name" placeholder="Owner Bank Name" value="{{$info->owner_bank_name}}"  >
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
                                    <input type="text" class="form-control @error('owner_IFSC_code') is-invalid @enderror" name="owner_IFSC_code" placeholder="Owner IFSC Code" value="{{$info->owner_IFSC_code}}">
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
                                    <input type="number" class="form-control @error('owner_account_number') is-invalid @enderror" name="owner_account_number" placeholder="Owner Account Number" value="{{$info->owner_account_number}}">
                                    @error('owner_account_number')
                                    <small style="color: red">{{message}}</small>
                                     @enderror
                                 </div>
                              </div>
                           </div>
                        </div>
                        <!-- Saloon -->
                        <p class="card-description" style="font-size: 15px">
                           SALOON INFORMATION
                        </p>
                        <div class="row">
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Salon Name</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control @error('saloon_name') is-invalid @enderror" name="saloon_name" placeholder="Saloon Name" value="{{$info->saloon_name}}" required="">
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
                                    <select class="" id="width1" style="width:100%" name="saloon_type_id" required="">
                                       <option selected="" disabled="">---select saloon type---</option>
                                       @foreach($type_list as $key)
                                         <option value="{{$key->saloon_type_id}}" <?php if($info->saloon_type_id== $key->saloon_type_id) { ?> selected <?php } ?> >{{$key->saloon_type_name}}</option>
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
                                    <input type="text" class="form-control @error('saloon_area') is-invalid @enderror" id="pac-input" name="saloon_area" placeholder="Search Saloon Locality"  value="{{$info->saloon_area}}" required="">
                                     @error('saloon_area')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                    <input type="hidden" name="saloon_lattitude" id="saloon_lattitude" value="{{$info->saloon_lattitude}}">
                                     <input type="hidden" name="saloon_longitude" id="saloon_longitude" value="{{$info->saloon_longitude}}">
                                 </div>
                              </div>
                           </div>
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Salon Address</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control" name="saloon_address" placeholder="Saloon Address" value="{{$info->saloon_address}}" required="">
                                    <small style="color: red"></small>
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
                                          <input type="text" class="form-control datetimepicker-input" data-target="#timepicker-example" name="saloon_time_from"
                                           placeholder="Saloon Time From" value="{{$info->saloon_time_from}}" id="saloon_time_from" oninput="checkTime()" autocomplete="off" >
                        
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
                                          <input type="text" class="form-control datetimepicker-input" data-target="#timepicker-example1" placeholder="Saloon Time To" name="saloon_time_to" value="{{$info->saloon_time_to}}" id="saloon_time_to" required="" oninput="checkTime()" autocomplete="off">
                                          <small style="color: red"></small>
                                          <div class="input-group-addon input-group-append"><i class="mdi mdi-clock input-group-text"></i></div>
                                           <small id="errors" style="color: red"></small>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                         
                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">No. of seats</label>
                                 <div class="col-sm-9">
                                    <input type="text" required="" class="form-control @error('saloon_total_seats') is-invalid @enderror" name="saloon_total_seats" placeholder="No. of seats" value="{{$info->saloon_total_seats}}">
                                     @error('saloon_total_seats')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                          
                        </div>
                        <div class="row">
                           
                          <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Availability</label>
                                 <div class="col-sm-9 ">
                                    @foreach($feature_list as $key)
                                    <div class="custom-control custom-checkbox">
                                     <input tabindex="5" type="checkbox" name="saloon_feature_id[]"  id="flat-checkbox-1" value="{{$key->feature_id}}"  @foreach($info->saloon_feature_id ? json_decode($info->saloon_feature_id) : [] as $itm) {{ $itm == $key->feature_id ? "checked" : "" }}@endforeach>
                                    <label style="margin-right:20px" for="flat-checkbox-1">{{$key->feature_name}}</label>
                                    </div>
                                    @endforeach 
                                    @error('saloon_feature_id')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                 </div>
                              </div>
                           </div>

                           <div class="col-md-6">
                              <div class="form-group row">
                                 <label class="col-sm-3 col-form-label">Working Days</label>
                                 <div class="col-sm-9">
                                    @foreach($days_list as $key)
                                    <div class="custom-control custom-checkbox">
                                     <input tabindex="5" type="checkbox" name="saloon_working_days[]" id="flat-checkbox-1" value="{{$key->day_id}}"  
                                     @foreach($info->saloon_working_days ? json_decode($info->saloon_working_days) : [] as $itm) {{ $itm == $key->day_id ? "checked" : "" }}
                                     @endforeach>
                                    <label for="flat-checkbox-1">{{$key->day_name}}</label>
                                    </div>   
                                    @endforeach 
                                    @error('saloon_working_days')
                                    <small style="color: red">{{$message}}</small>
                                    @enderror
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-md-12" style="text-align: right;">
                              <button type="submit" class="btn btn-success mr-2">Submit</button>
                              <!-- <button class="btn btn-light">Cancel</button> -->
                           </div>
                        </div>
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
          console.log(lot);
          console.log(place);
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