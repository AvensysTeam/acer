@extends('layouts.app')

@section('script')
    <script>
        function onSubmit(event) {
            event.preventDefault(); 
            var response = grecaptcha.getResponse();
            if(response.length == 0){
                console.log(response.length, "false")
                return false;
            }
            else {
                console.log(response.length, "true");
                event.target.submit();
            }
        }
    </script>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('styles')
    <link href="{{ asset('css/slidercaptcha.min.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <?php
    
    $clientIP = $_SERVER['REMOTE_ADDR'];
    $pc_info = "{$clientIP}";
    $pc_info = hash('sha256', $pc_info);
    ?>

    <div class="auth-card" style="max-width: 50rem !important;">
        <div class="flex flex-shrink-0 justify-center">
            <a href="{{ route('login') }}">
                <img class="responsive" src="{{ asset('img/logo.png') }}" alt="logo" width="150">
            </a>
        </div>

        @if (session('message'))
            <div class="alert success">
                {{ session('message') }}
            </div>
        @endif

        @if ($errors->has('ip_address'))
            <div class="alert danger">
                {{ $errors->first('ip_address') }}
            </div>
        @endif

        <form method="POST" onsubmit="onSubmit(event)" action="{{ route('register') }}">
            @csrf
              <div class="form-row">
                <div class="col-md-4 ">
                  <label for="validationCustom01">Company Name</label>
                  <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}" id="validationCustom01"
                     placeholder="Tech Solutions LLC" name="company_name" required  value="{{ old('company_name') }}" >
                    @if ($errors->has('company_name'))
                        <p class="invalid-feedback">{{ $errors->first('company_name') }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-md-4 ">
                  <label for="validationCustom02">VAT Number</label>
                  <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('VAT') ? ' is-invalid' : '' }}" id="validationCustom02" 
                    placeholder="IT12345678901" name="VAT" required  value="{{ old('VAT') }}" >
                    @if ($errors->has('VAT'))
                        <p class="invalid-feedback">{{ $errors->first('VAT') }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-md-4 ">
                  <label for="validationCustom03">Contact Person's Name</label>
                  <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('contact_person_name') ? ' is-invalid' : '' }}"
                     placeholder="Mario Rossi" name="contact_person_name" required  value="{{ old('contact_person_name') }}" >
                    @if ($errors->has('contact_person_name'))
                        <p class="invalid-feedback">{{  $errors->first('contact_person_name') }}</p>
                    @endif
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-4 ">
                  <div class="form-group">
                    <label for="exampleSelect">Legal Form</label>
                    <select class="form-control" id="exampleSelect" name="legal_form">
                      <option value="1">LLC</option>
                      <option value="2">LTD</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4 ">
                  <div class="form-group">
                    <label for="exampleSelect">Sector of Activity</label>
                    <select class="form-control" id="exampleSelect" name="sector_activity">
                      <option value="1">Technology and IT</option>
                      <option value="2">Engineering</option>
                      <option value="3">Mechanics</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4 ">
                  <div class="form-group">
                    <label for="exampleSelect">Company Size</label>
                    <select class="form-control" id="exampleSelect" name="company_size">
                      <option value="1">1 ~ 9 employees</option>
                      <option value="2">10 ~ 50 employees</option>
                      <option value="3">51 ~ 100 employees</option>
                      <option value="4">101 ~ 500 employees</option>
                      <option value="5">over 501 employees</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="col-md-6 ">
                  <label for="validationCustom03">Legal Address</label>
                  <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('legal_address') ? ' is-invalid' : '' }}" 
                    placeholder="10 Rome Street, 00100 Rome (RM)" name="legal_address" required  value="{{ old('legal_address') }}" >
                    @if ($errors->has('legal_address'))
                        <p class="invalid-feedback">{{ $errors->first('legal_address') }}</p>
                    @endif
                  </div>
                </div>
              
                <div class="col-md-6 ">
                  <label for="validationCustom03">Operational Address(if different)</label>
                  <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('operational_address') ? ' is-invalid' : '' }}" 
                    placeholder="5 Milan Street, 00100 Rome (RM)" name="operational_address" value="{{ old('operational_address') }}" >
                    @if ($errors->has('operational_address'))
                        <p class="invalid-feedback">{{ $errors->first('operational_address') }}</p>
                    @endif
                  </div>
                </div>
                
              </div>
              <div class="form-row">
                <div class="col-md-6">
                  <label for="validationCustom04">Position/Role</label>
                  <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('position') ? ' is-invalid' : '' }}" 
                    placeholder="IT Manager" name="position" required  value="{{ old('position') }}" >
                    @if ($errors->has('position'))
                        <p class="invalid-feedback">{{ $errors->first('position') }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="validationCustom03">Business Email</label>
                  <div class="input-group">
                    <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" 
                    id="validationCustom03" placeholder="Email Address" name="email" required  value="{{ old('email') }}" >
                    @if ($errors->has('email'))
                        <p class="invalid-feedback">{{ $errors->first('email') }}</p>
                    @endif
                  </div>
                </div>                
              </div>
              <div class="form-row">
                 <div class="col-md-6">
                  <label for="validationCustom04">Business Mobile Phone</label>
                  <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}"
                     placeholder="+390123456789" name="mobile_phone" required  value="{{ old('mobile_phone') }}"
                     pattern="\+\d{10,15}" >
                    @if ($errors->has('mobile_phone'))
                        <p class="invalid-feedback">{{ $errors->first('mobile_phone') }}</p>
                    @endif
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="validationCustom04">Username</label>
                  <div class="input-group">
                    <input type="text" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}" 
                    placeholder="tech_admin" name="username" required  value="{{ old('username') }}" >
                    @if ($errors->has('username'))
                        <p class="invalid-feedback">{{ $errors->first('username') }}</p>
                    @endif
                  </div>
                </div>
              </div>
              <div class="form-row">
                
                <div class="col-md-6">
                  <label for="validationCustom04">Password </label>
                  <div class="input-group">
                    <input type="password" name="password"
                        class="form-input{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                    @if ($errors->has('password'))
                        <p class="invalid-feedback">{{ $errors->first('password') }}</p>
                    @endif
                    <span id="toggle-password"><i class="fa fa-eye-slash" onclick="togglePasswordVisibility(event)"></i></span>
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="validationCustom04">Password Confirm</label>
                  <div class="input-group">
                        <input type="password" name="password_confirmation" class="form-input" required>
                        @if ($errors->has('password_confirmation'))
                            <p class="invalid-feedback">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                        <span id="toggle-password"><i class="fa fa-eye-slash" onclick="togglePasswordVisibility(event)"></i></span>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="form-check pt-2">
                  <label class="ms-checkbox-wrap" for="accept_terms_conditions">
                    <input class="form-check-input" type="checkbox" name="accept_terms" id="accept_terms_conditions" required>
                    Accept to terms and conditions 
                  </label>
                  <br>
                  <label class="ms-checkbox-wrap" for="accept_policy">
                    <input class="form-check-input" type="checkbox" name="accept_privacy" id="accept_policy" required>
                    <span>Accept privacy policy</span><a href="/terms" class="btn-link">Terms and services</a>
                  </label>
                </div>
              </div>            

            <label class="d-block mt-3">
                <div class="mt-3">
                    <div id="recaptcha" class="g-recaptcha middle"
                        data-sitekey="6LeWhuIpAAAAAPRvdy3x68tMmbHrBOvfXO89OTBI"
                    >
                    </div>
                </div>
            </label>
            <input type="hidden" name="pc_info" value="{{ $pc_info }}">

            <div class="flex justify-center items-center mt-4">
                <div>
                    <a class="link" href="{{ route('login') }}">Sign In Here</a>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="button" id="submit_btn" 
                    style="background-color: rgba(90, 103, 103, var(--bg-opacity))">
                    {{ trans('global.register') }}
                </button>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        function togglePasswordVisibility(event) {
            var toggleIcon = event.target;
            var passwordInput = toggleIcon.parentNode.parentNode.children[0];
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
@endsection
