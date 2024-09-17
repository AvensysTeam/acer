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

    <div class="auth-card">
            <div class="flex flex-shrink-0 justify-center">
                <div>
                    <label class="pointer" for="acer">
                        <img src="{{ asset('img/login/logo.png') }}" width="150" />
                    </label>
                    <input value="acer" type="radio" id="acer" name="pageSelectButton" checked />
                    <div class="acer-bar"></div>
                </div>
            </div>
            <h4 class="pt-2 text-center">Thanks for your joining</h4>
            <p class="text-center">Please check your email address, once your account is approved, you will get email from the system.</p>
            <form id="logoutform" action="{{ route('logout') }}" method="POST" >
                {{ csrf_field() }}
                <button class="btn btn-dangerbtn btn-danger">Logout</button>
            </form>
        </form>
    </div>
@endsection

@section('script')
@endsection

@section('scripts')    
@endsection