@extends('layouts.app')

@section('styles')
<link href="{{ asset('css/slidercaptcha.min.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
<style>
  input#mobile_phone::placeholder {
    color: #8080804f
  }

  input.error {
    border: 1px solid red;
  }

  .error {
    color: red;
  }

  .iti {
    width: 100%;
  }

  .iti__flag-container {
    position: absolute;
    top: 11px !important;
    bottom: auto !important;
  }

  #toggle-password {
    position: absolute;
    top: 0px !important;
    bottom: auto !important;
  }

  .input-group1 {
    position: relative;
  }

  .register-form {
    max-width: 50rem !important;
    max-height: 95vh;
    overflow-y: auto;
  }

</style>

<link href="{{ asset('css/company-contact.css') }}" rel="stylesheet" />
@endsection
@section('content')
<?php
$clientIP = $_SERVER['REMOTE_ADDR'];
$pc_info = "{$clientIP}";
$pc_info = hash('sha256', $pc_info);
?>

<div class="auth-card register-form" style="">
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

  <form method="POST" action="{{ route('register') }}" id="register_form">
    @csrf
    <div class="form-row">
      <div class="col-md-4 ">
        <label for="validationCustom02">@lang('VAT Number')</label>
        <div class="input-group1">
          <input type="text" class="form-control {{ $errors->has('VAT') ? ' is-invalid' : '' }}" id="validationCustom02"
            placeholder="IT12345678901" name="VAT" id="VAT" required value="{{ old('VAT') }}">
          <div id="loader" class="d-none">
            <div class="lds-dual-ring"></div>
          </div>
          @if ($errors->has('VAT'))
          <p class="invalid-feedback">{{ $errors->first('VAT') }}</p>
          @endif
        </div>
      </div>
      <div class="col-md-4 ">
        <label for="validationCustom01">@lang('global.company_name')</label>
        <div class="input-group1">
          <input type="text" class="form-control {{ $errors->has('company_name') ? ' is-invalid' : '' }}" id="validationCustom01"
            placeholder="Tech Solutions LLC" name="company_name" required value="{{ old('company_name') }}" readonly>
          @if ($errors->has('company_name'))
          <p class="invalid-feedback">{{ $errors->first('company_name') }}</p>
          @endif
        </div>
      </div>
      <div class="col-md-4 ">
        <label for="validationCustom03">@lang("Contact Person's Name")</label>
        <div class="input-group1">
          <input type="text" class="form-control {{ $errors->has('contact_person_name') ? ' is-invalid' : '' }}"
            placeholder="Mario Rossi" name="contact_person_name" required value="{{ old('contact_person_name') }}">
          @if ($errors->has('contact_person_name'))
          <p class="invalid-feedback">{{ $errors->first('contact_person_name') }}</p>
          @endif
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="col-md-4 ">
        <div class="form-group">
          <label for="exampleSelect">@lang('Legal Form')</label>
          <input type="hidden" name="old_legal_form" value="{{ old('legal_form') }}">
          <select class="form-control" id="legal_form" name="legal_form" required>
            <option value="">@lang('Select a legal form')</option>
          </select>
        </div>
      </div>
      <div class="col-md-4 ">
        <div class="form-group">
          <label for="exampleSelect">@lang('Sector of Activity')</label>
          <select class="form-control" id="sector_activity" name="sector_activity" required>
            <option value="">@lang('Select a sector of Activity')</option>
            @foreach($servicesActivitys as $key => $service_activity)
            <option value="{{$key}}" @if(old("sector_activity") && old("sector_activity")==$key) selected @endif>{{$service_activity['name']}}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-4 ">
        <div class="form-group">
          <label for="exampleSelect">@lang('Company Size')</label>
          <select class="form-control" id="company_size" name="company_size" required>
            <option value="">@lang('Select a company size')</option>
            @foreach($company_sizes as $key => $size)
            <option value="{{$key}}" @if(old("company_size") && old("company_size")==$key) selected @endif>{{$size . ' ' . trans('employees')}}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="col-md-6 ">
        <label for="validationCustom03">@lang('Legal Address')</label>
        <div class="input-group1">
          <input type="text" class="form-control {{ $errors->has('legal_address') ? ' is-invalid' : '' }}"
            placeholder="10 Rome Street, 00100 Rome (RM)" name="legal_address" required value="{{ old('legal_address') }}" readonly>
          @if ($errors->has('legal_address'))
          <p class="invalid-feedback">{{ $errors->first('legal_address') }}</p>
          @endif
        </div>
      </div>

      <div class="col-md-6 ">
        <label for="validationCustom03">@lang('Operational Address(if different)')</label>
        <div class="input-group1">
          <input type="text" class="form-control {{ $errors->has('operational_address') ? ' is-invalid' : '' }}"
            placeholder="5 Milan Street, 00100 Rome (RM)" name="operational_address" value="{{ old('operational_address') }}">
          @if ($errors->has('operational_address'))
          <p class="invalid-feedback">{{ $errors->first('operational_address') }}</p>
          @endif
        </div>
      </div>

    </div>
    <div class="form-row">
      <div class="col-md-6">
        <label for="validationCustom04">@lang('Position/Role')</label>
        <select class="form-control" id="position" name="position">
          @foreach($roles as $role)
          <option value="{{$role}}">{{trans($role)}}</option>;
          @endforeach
        </select>
      </div>
      <div class="col-md-6">
        <label for="validationCustom03">@lang('Business Email')</label>
        <div class="input-group1">
          <input type="email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
            id="validationCustom03" placeholder="Email Address" name="email" required value="{{ old('email') }}">
          @if ($errors->has('email'))
          <p class="invalid-feedback">{{ $errors->first('email') }}</p>
          @endif
        </div>
      </div>
    </div>
    <div class="form-row">
      <div class="col-md-6">
        <label for="validationCustom04">@lang('Business Mobile Phone')</label>
        <div class="input-group1">
          <input type="tel" class="form-control {{ $errors->has('mobile_phone') ? ' is-invalid' : '' }}"
            name="mobile_phone" id="mobile_phone" required value="{{ old('full_mobile_phone') }}">
          @if ($errors->has('mobile_phone'))
          <p class="invalid-feedback">{{ $errors->first('mobile_phone') }}</p>
          @endif
        </div>
      </div>
      <div class="col-md-6">
        <label for="validationCustom04">@lang('global.login_name')</label>
        <div class="input-group1">
          <input type="text" class="form-control {{ $errors->has('username') ? ' is-invalid' : '' }}"
            placeholder="tech_admin" name="username" required value="{{ old('username') }}">
          @if ($errors->has('username'))
          <p class="invalid-feedback">{{ $errors->first('username') }}</p>
          @endif
        </div>
      </div>
    </div>
    <div class="form-row">

      <div class="col-md-6">
        <label for="validationCustom04">@lang('global.login_password')</label>
        <div class="input-group1">
          <input type="password" name="password"
            class="form-input{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="*********">
          @if ($errors->has('password'))
          <p class="invalid-feedback">{{ $errors->first('password') }}</p>
          @endif
          <span id="toggle-password"><i class="fa fa-eye-slash" onclick="togglePasswordVisibility(event)"></i></span>
        </div>
      </div>
      <div class="col-md-6">
        <label for="validationCustom04">@lang('global.login_password_confirmation')</label>
        <div class="input-group1">
          <input type="password" name="password_confirmation" class="form-input" required placeholder="*********">
          @if ($errors->has('password_confirmation'))
          <p class="invalid-feedback">{{ $errors->first('password_confirmation') }}</p>
          @endif
          <span id="toggle-password"><i class="fa fa-eye-slash" onclick="togglePasswordVisibility(event)"></i></span>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="form-check pt-2">
        <label class="d-flex flex-wrap ms-checkbox-wrap" for="accept_terms_conditions">
          <span class="w-100">@lang('Accept to terms and conditions')</span>
          <input class="form-check-input" type="checkbox" name="accept_terms" id="accept_terms_conditions" required>
        </label>
        <label class="d-flex flex-wrap ms-checkbox-wrap" for="accept_policy">
          <span class="w-100">@lang('Accept privacy policy')<a href="https://www.avensys-srl.com/img_mktg/privacy.pdf" class="btn-link" target="_blank">@lang('Terms and services')</a></span>
          <input class="form-check-input" type="checkbox" name="accept_privacy" id="accept_policy" required>
        </label>
      </div>
    </div>

    <label class="d-block mt-3">
      <div class="mt-3">
        <div id="recaptcha" class="g-recaptcha middle"
          data-sitekey="6LeWhuIpAAAAAPRvdy3x68tMmbHrBOvfXO89OTBI">
        </div>
      </div>
    </label>
    <input type="hidden" name="pc_info" value="{{ $pc_info }}">

    <div class="flex justify-center items-center mt-4">
      <div>
        <a class="link" href="{{ route('login') }}">@lang('Sign In Here')</a>
      </div>
    </div>

    <div class="mt-6">
      <button type="submit" class="button" id="submit_btn"
        style="background-color: rgba(90, 103, 103, var(--bg-opacity))">
        {{ trans('global.register') }}
      </button>
    </div>
    <input type="hidden" name="company_id" value="{{ old('company_id')?old('company_id'):0 }}" id="company_id">
    <input type="hidden" name="country_code" value="{{ old('country_code')}}" id="country_code">
    <input type="hidden" name="full_mobile_phone" value="{{ old('full_mobile_phone')}}" id="full_mobile_phone">
    <input type="hidden" name="vat_valid" value="{{ old('vat_valid')?old('vat_valid'):0 }}" id="vat_valid">
  </form>
</div>
@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@include('layouts.register-script', ['legalForms' => $legalForms])

<script>

  const phone_input = document.querySelector("#mobile_phone");

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

  $(document).ready(function() {

    const iti = window.intlTelInput(phone_input, {
      initialCountry: "auto",
      nationalMode: true,
      geoIpLookup: function(callback) {
        fetch("https://ipapi.co/json")
          .then(function(res) {
            console.log(res);
            return res.json();
          })
          .then(function(data) {
            let code = data.country_code;
            localStorage.setItem('country_code', data.country_code);
            $('input[name=country_code]').val(code);
            if (availableCountries.indexOf(code) === -1) {
              code = 'GB';
            }

            let forms = legalForms[code] ? legalForms[code] : legalForms['GB']; // Get legal forms for the selected country

            // Display the legal forms
            const legalFormSelect = document.getElementById('legal_form');
            legalFormSelect.innerHTML = '<option value="">Select a legal form</option>'; // Clear previous options

            forms.forEach((form, index) => {
              const option = document.createElement('option');
              option.value = data.country_code + '_' + index; // Use index or any unique identifier
              option.textContent = form; // Set the legal form text

              console.log('old value', $('input[name=old_legal_form]').val());

              if (option.value == $('input[name=old_legal_form]').val()) {
                option.setAttribute('selected', 'selected');
              }
              legalFormSelect.appendChild(option);
            });

            callback(code);
          })
          .catch(function(e) {
            // if the country is not the list
            callback("GB");
          });
      },
      onlyCountries: availableCountries,
      utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"
    });


    $(document).on('change', 'input[name=VAT]', function() {
      $('input[name=company_id]').val(0);
      $('input[name=company_name]').val('');
      $('input[name=legal_address]').val('');
      $('input[name=vat_valid]').val(0);
    })

    $(document).on('blur', 'input[name=VAT]', function() {
      console.log('blur event triggered');
      const vatNumber = $(this).val(); // Get VAT number value
      const country = localStorage.getItem('country_code'); // Get selected country
      const vatData = vatPatterns[country]; // Get pattern and format
      if (!vatData.pattern.test(vatNumber)) {
        console.log('wrong vat number');
        return false;
      }

      if ($('input[name=vat_valid]').val() == 1) {
        return true;
      }

      $('#loader').removeClass('d-none');

      if (vatNumber) {
        // Send AJAX request to backend
        $.ajax({
          url: `{{route('autofill.company')}}`, // Replace with your API endpoint
          type: 'POST',
          data: {
            _token: `{{csrf_token()}}`,
            vat_number: vatNumber,
          },
          success: function(response) {
            if (response.status === 'success') {
              // Auto-fill form fields
              $('input[name=company_name]').val(response.data.name);
              $('input[name=full_mobile_phone]').val(response.data.phone);
              iti.setNumber(response.data.phone);
              $('select[name=legal_form]').val(response.data.legal_form);
              $('select[name=sector_activity]').val(response.data.sector_activity);
              $('input[name=legal_address]').val(response.data.address);
              $('input[name=operational_address]').val(response.data.operational_address);
              $('select[name=company_size]').val(response.data.company_size);
              $('input[name=contact_person_name]').val(response.data.contact_person_name);
              $('input[name=company_id]').val(response.data.id);
              $('input[name=vat_valid]').val(1);
            } else {
              if (response.data.valid) {
                $('input[name=company_id]').val(0);
                $('input[name=vat_valid]').val(1);
                $('input[name=company_name]').val(response.data.name);
                $('input[name=legal_address]').val(response.data.address);
              } else {
                $('input[name=company_id]').val(0);
                $('input[name=company_name]').val('');
                $('input[name=legal_address]').val('');
                $('input[name=vat_valid]').val(0);
                $('#register_form').validate().showErrors({
                  "VAT": "The VAT Number is wrong."
                });
              }
            }
          },
          error: function() {
            $('input[name=company_id]').val(0);
            $('input[name=company_name]').val('');
            $('input[name=legal_address]').val('');
            $('input[name=vat_valid]').val(0);
            $('#register_form').validate().showErrors({
              "VAT": "The VAT Number is wrong."
            });
          },
          complete: function() {
            $('#loader').addClass('d-none');
          }
        });
      }
    });

    $.validator.addMethod(
      "vatPattern",
      function(value, element) {
        const country = localStorage.getItem('country_code'); // Get selected country
        const vatData = vatPatterns[country]; // Get pattern and format
        if (!vatData) return false; // If country not selected
        return vatData.pattern.test(value); // Validate VAT
      },
      function(params, element) {
        const country = localStorage.getItem('country_code'); // Get selected country
        const vatData = vatPatterns[country]; // Get format
        return vatData ? `The VAT number should be in the format: ${vatData.format}` : "Invalid VAT number.";
      }
    );

    $.validator.addMethod(
      "validPhone",
      function(value, element) {
        // Validate only if the field has value
        if (value.trim() === "") {
          return false;
        }

        $('input[name=full_mobile_phone]').val(iti.getNumber());
        // Use intl-tel-input's isValidNumber()
        return iti.isValidNumber();
      },
      "<?php echo trans('Please enter a valid phone number.'); ?>"
    );

    $('#register_form').validate({ // initialize the plugin
      rules: {
        mobile_phone: {
          required: true,
          validPhone: true, // Use the custom validation method
        },
        VAT: {
          required: true,
          vatPattern: true, // Use the custom validation method
        },
      },
      messages: {
        mobile_phone: {
          required: "<?php echo trans('The phone number is required.'); ?>",
          validPhone: "<?php echo trans('Please enter a valid phone number.'); ?>",
        },
      },
      submitHandler: function(form) { // for demo
        // form.submit();
        var response = grecaptcha.getResponse();
        if (response.length == 0) {
          console.log(response.length, "false")
          return false;
        } else {
          console.log(response.length, "true");
          form.submit();
        }
      }
    });

  });
</script>
@endsection