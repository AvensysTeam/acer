<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link rel="icon" type="image/x-icon" href="img/favicon.png">
    <link href="https://cdn.jsdelivr.net/npm/@tailwindcss/custom-forms@0.2.1/dist/custom-forms.min.css" rel="stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('script')
    @yield('styles')
    <style>
        #main-content{
            display: none;
        }
        #preloader{
            position: fixed;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        /** css spinner */
        .lds-dual-ring {
        color: #1c4c5b
        }
        .lds-dual-ring, .lds-dual-ring:after {
            box-sizing: border-box;
        }
        .lds-dual-ring {
            display: inline-block;
            width: 80px;
            height: 80px;
        }
        .lds-dual-ring:after {
            content: " ";
            display: block;
            width: 64px;
            height: 64px;
            margin: 8px;
            border-radius: 50%;
            border: 6.4px solid currentColor;
            border-color: currentColor transparent currentColor transparent;
            animation: lds-dual-ring 1.2s linear infinite;
        }
        @keyframes lds-dual-ring {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div id="preloader">
        <div class="lds-dual-ring"></div>
    </div>
    <div class="flex justify-center items-center h-screen bg-gray-200 px-6" id="main-content">
        @yield("content")
    </div>
    @yield('scripts')
    <script>
        var allowed = sessionStorage.getItem('allowed');
        if(allowed == "1") {
            showcontent();
        } else if(allowed == "0") {
            location.href="<?php echo route('ip.banned'); ?>"
        } else {
            getip();
        }
        function getip() {
            fetch("https://api.ipify.org?format=json")
                .then(response => response.json())
                .then(data => {
                    getipdetail(data.ip);
                })
                .catch(error => {
                    console.error("Error fetching IP address:", error);
                    showcontent();
                });
        }
            
        function getipdetail(ip) {
            fetch("https://ipinfo.io/"+ip+"/json")
                .then(response => response.json())
                .then(data => {
                    var countrycode = "AT,BE,BG,HR,CY,CZ,DK,EE,FI,FR,DE,GR,HU,IE,IT,LV,LT,LU,MT,NL,NO,PL,PT,RO,SK,SI,ES,SE,CH,GB".split(',');
                    if(!countrycode.includes(data.country)) {
                        sessionStorage.setItem( 'allowed', '0' );
                        location.href="<?php echo route('ip.banned'); ?>"
                    } else {
                        showcontent();
                    }

                })
                .catch(error => {
                    console.error("Error fetching IP address:", error);
                    showcontent();
                });

        }

        function showcontent() {
            document.getElementById('preloader').style.display = 'none';
            document.getElementById('main-content').style.display = 'flex';
            sessionStorage.setItem( 'allowed', '1' );
        }

    </script>
</body>

</html>