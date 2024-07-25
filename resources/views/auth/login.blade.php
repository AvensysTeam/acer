@extends('layouts.app')
@section('styles')
<style>
    @media only screen and (max-width: 1000px) {
        .login_logo {
            display: none;
        }
        .login_logo_res {
            display: block !important;
        }
        .auth-card {
            width: 120%;
            margin-top: 150px;
        }
    }
    .middle {
        margin: 0 auto;
    }
    .login_logo {
        width: 300px;
        position: absolute;
        top:50px;
        left:100px;
    }
    .login_logo_res {
        width: 100%;
        display: none;
    }
    .acer-bar {
        width: 100%;
        border-bottom: 3px solid red;
    }
    .smart-bar {
        width: 100%;
        border-bottom: 3px solid #273583;
    }
    .acer,.smart {
        cursor: pointer;
    }
    #acer,#smart {
        display: none;
    }
    .acer-bar, .smart-bar{
        display: none;
    }
    .pointer {
        cursor: pointer;
    }
    .login_btn {
        background-color: rgba(90, 103, 103, var(--bg-opacity));
        color: white;
        width: 100%;
        border-radius: 5px;
        padding: 10px 0px;
    }
    .login_btn:hover {
        background-color: #596da7;
        transition: all .3s;
    }
    .login_btn:focus {
        background-color: #25408f;
        transition: all .3s;
    }
</style>
@endsection
@section('content')
    <?php
    /*
    $hostname = gethostname();
    
    $output = shell_exec('ipconfig /all');
    preg_match_all('/([a-fA-F0-9]{2}[-:]){5}[a-fA-F0-9]{2}/i', $output, $matches);
    $mac_address = $matches[0][0];
    
    $output = shell_exec('wmic cpu get name');
    $output = explode("\n", $output);
    $cpu_info = str_replace(["\r", '  '], '', $output[1]);
    */    
    $clientIP = $_SERVER['REMOTE_ADDR'];
    $pc_info = "{$clientIP}";
    $pc_info = hash('sha256', $pc_info);
    ?>

    <img src="{{ asset('img/login/logo_dark.png') }}" class="login_logo" />
    <div class="auth-card">
        @if (session('message'))
            <div class="alert success">
                {{ session('message') }}
            </div>
        @endif

        <form method="get" onsubmit="onSubmit(event)" action="">
            <img src="{{ asset('img/login/logo_dark.png') }}" class="login_logo_res" />
            <div class="flex flex-shrink-0 justify-center">
                <div style="width:50%;">
                    <label class="pointer" for="acer">
                        <img src="{{ asset('img/login/logo.png') }}" style="width:100%; heiht:180px;" />
                    </label>
                    <input value="acer" type="radio" id="acer" name="pageSelectButton" checked />
                    <div class="acer-bar"></div>
                </div>
                <div style="width:50%;">
                    <label class="pointer" for="smart">
                        <img src="{{ asset('img/login/logo_smart.png') }}" style="width:100%; heiht:180px;" />
                    </label>
                    <input value="smart" id="smart" type="radio" name="pageSelectButton" />
                    <div class="smart-bar"></div>
                </div>
            </div>
            <h4 class="pt-2" style="text-align: center; color: red;">currently under maintenance</h4>
            <h2 class="text-center">LOGIN</h2>
            @csrf
            <label class="d-block">
                <span class="text-gray-700 text-sm">{{ trans('global.login_email') }}</span>
                <input type="email" name="email" id="email-input" class="form-input {{ $errors->has('email') ? ' is-invalid' : '' }}"
                    value="{{ old('email') }}" autofocus required>
                @if ($errors->has('email'))
                    <p class="invalid-feedback">{{ $errors->first('email') }}</p>
                @endif
            </label>
            <label class="d-block mt-3 password-panel">
                <span class="text-gray-700 text-sm">{{ trans('global.login_password') }}</span>
                <input type="password" name="password" id="password-input"
                    class="form-input{{ $errors->has('password') ? ' is-invalid' : '' }}" required>
                @if ($errors->has('password'))
                    <p class="invalid-feedback">{{ $errors->first('password') }}</p>
                @endif
                <span id="toggle-password" onclick="togglePasswordVisibility()"><i class="fa fa-eye-slash"></i></span>
            </label>
            
            <div class="mt-3">
                <div id="recaptcha" class="g-recaptcha middle"
                    data-sitekey="6LeWhuIpAAAAAPRvdy3x68tMmbHrBOvfXO89OTBI"
                >
                </div>
            </div>
            <input type="hidden" name="pc_info" id="pc_info" value="{{ $pc_info }}">

            <div class="flex justify-end items-center mt-4">
                <div>
                    <button type="button" class="link" onclick="handleRegLink()">Create an Account</button>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="login_btn" id="submit_btn">
                    {{ trans('global.login') }}
                </button>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection

@section('scripts')
    
    <script>
        function handleRegLink() {
            var pageSelectedOption = document.querySelector("input[name=pageSelectButton]:checked").value;
            
            if(pageSelectedOption == "acer") {
                var linkElement = document.createElement("a");
                linkElement.href = "https://acer.avensys-srl.com/register";
                linkElement.click();
            } else if(pageSelectedOption == "smart") {
                var linkElement = document.createElement("a");
                linkElement.href = "http://test.avensys-srl.com/signup";
                linkElement.click();
            } else {
                alert("Something is wrong.");
                return false;
            }
        }

        function onSubmit(event) {
            event.preventDefault(); 
            var response = grecaptcha.getResponse();
            if(response.length == 0){
                return false;
            }
            else {
                
                // get sign in info 
                var pageSelectedOption = document.querySelector("input[name=pageSelectButton]:checked").value;
                var passwordValue = document.getElementById('password-input').value;
                var emailValue = document.getElementById('email-input').value;
                var pcInfoValue = document.getElementById('pc_info').value;

                if(pageSelectedOption == "acer") {
                    var linkElement = document.createElement("a");
                    linkElement.href = "https://acer.avensys-srl.com/other-login?email=" + emailValue + "&password=" + passwordValue + "&pc_info=" + pcInfoValue;
                    console.log();
                    linkElement.click();                
                } else if(pageSelectedOption == "smart") {
                    var linkElement = document.createElement("a");
                    linkElement.href = "http://test.avensys-srl.com/other-login?email=" + emailValue + "&password=" + passwordValue + "&pc_info=" + pcInfoValue;
                    linkElement.click();
                } else {
                    alert("Something is wrong.")
                    return false;
                }
            }
        }

        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password-input");
            var toggleIcon = document.getElementById("toggle-password").querySelector("i");

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
        ;
    </script>
    <script>
        var acerRadioButton = document.getElementById('acer');
        var smartRadioButton = document.getElementById('smart');
        var acerBarDiv = document.querySelector('.acer-bar');
        var smartBarDiv = document.querySelector('.smart-bar');
        acerBarDiv.style.display = 'block';
        acerRadioButton.addEventListener('change', function() {
            if (acerRadioButton.checked) {
                acerBarDiv.style.display = 'block';
                smartBarDiv.style.display = 'none';
            } else {
                acerBarDiv.style.display = 'none';
            }
        });
        smartRadioButton.addEventListener('change', function() {
            if (smartRadioButton.checked) {
                smartBarDiv.style.display = 'block';
                acerBarDiv.style.display = 'none';
            } else {
                smartBarDiv.style.display = 'none';
            }
        });
    </script>
@endsection