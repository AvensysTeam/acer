<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />    
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-xWMNpZa8lWg10fGXGQe2NRwHngbpf91Nh0aDFlZjX9A1+OzJrWSeYCM+y2/pFBNyhuertM6rzOlU6+NlOIbRg==" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" />
    <!-- For Modal -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <!-- End For Modal -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/css/intlTelInput.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/0.8.2/css/flag-icon.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @yield('styles')
    <style>
        
        .check{
            display:flex;
        } 
        .raw{ 
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .rowcontant{
            display:flex;
            justify-content:space-between;
            margin-left:-15px;
            align-items: center;
        }

        .dropbtn { 
            background-color: white; 
            color: black; 
            padding: 5px 12px 4px 12px; 
            font-size: 16px; 
            border: none; 
            cursor: pointer; 
            margin-right: 40px;
            /* border: 1px solid #808080; */
        } 

        .dropdown { 
            position: relative; 
            display: inline-block;
            
        } 

        .dropdown-content { 
            display: none; 
            position: absolute; 
            background-color: #f9f9f9; 
            min-width: 60px; 
            box-shadow: 0px 8px 16px  
                0px rgba(0, 0, 0, 0.2); 
            z-index: 1; 
            /* left: -8px; */
            top: 37px;
            height: auto;
            overflow:auto;
            border-radius: 4px;
            border: 1px solid #e3e3e3;
        } 

        .dropdown-content::-webkit-scrollbar {
            width: 1px;
        }

        .dropdown-content::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 3px rgba(0,0,0,0); 
            border-radius: 5px;
        }

        .dropdown-content::-webkit-scrollbar-thumb {
            border-radius: 5px;
            -webkit-box-shadow: inset 0 0 3px rgba(0,0,0,0); 
        }
  
        .dropdown-content a { 
            color: black; 
            padding: 5px; 
            text-decoration: none; 
            display: block; 
            display:flex;
            justify-content:center;
        } 
  
        .dropdown-content a:hover { 
            background-color: #f1f1f1 
        } 
  
        /* .dropdown:hover .dropdown-content { 
            display: block; 
        }  */
        .flagIcon{
           width:25px;
           height:25px;
        }
  
        .dropbtn:hover { 
            background-color: #00000014;
            border-radius:3px;
        } 
        .dropbtn:after{
            content: '';
            border: 4px solid transparent;
            border-top: 4px solid black;
            margin-left: 2px;
            margin-bottom: 6px;
            display: inline-block;
            vertical-align: bottom;
        }
        #selected-lang{
            background-Color:#007bff;
        }
        .backnext{
            right: 132px;
            position: absolute;
            top: 6px;
        }
        .items-center{
            display: flex;
            flex-direction: column;
        }
        .items-center h6{
            margin-right: 200px;
        }
        .title{
            display:flex;
        }
        .heading{
           /* font-size: 1.75rem; */
           font-size: 19px;
           margin-top: -4px;
           margin-left: 5px;
        }
        .items-center h6{
           margin-right: 116px;
        }
        
    </style>
</head>
<!-- <style>
    
</style> -->
<body>
    <div class="flex h-screen bg-gray-200">
        @include('partials.menu')

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="main-header">
                <div class="flex items-center">
                    <button id="sidebar-enable" class="sidebar-enable">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    

                    @if(isset($_page_title))
                   <div class="title"><h3 style="font-size: 19px">{{$_page_title}}</h3><span class="heading"></span></div>
                    @endif
                    @if(isset($Latest_date))
                     <div>
                        <h6>{{$Latest_date}}</h6>
                     </div>
                     @endif
                </div>
                
            
                @if(request()->is('admin/projects/detail*'))
                <div class="d-inline-block mr-3 backnext">
                    <!-- <a href="{{route('admin.projects.profile')}}/{{$pid}}/{{$cid}}/{{$uid}}" class="btn  button-boxed" style="">
                        
                        <img class="new mb-2" src="{{asset('assets/icons/nextArrow.png')}}" width="35px" style="transform: rotate(180deg) "/>

                    </a>
                    <a class="btn  button-boxed btn-first-unit-next" onclick="onNewUnit()" style="display: none; padding: 0px;">
                        
                        <img class="new mb-2" src="{{asset('assets/icons/nextArrow.png')}}" width="35px" height="35px"/>
    
                    </a> -->
                    {{-- <a class="btn-preview" onclick="Email_sending()" style="display: none;">
                        <small>Send to mail</small>
                    </a>
                    <a class="btn  button-boxed btn-preview" onclick="preview2PDF()" style="display: none;">
                        <i class="fa fa-eye" aria-hidden="true"></i>
                        <small>@lang('Preview')</small>
                    </a>
                    <a class="btn btn-outline-success button-boxed btn-unit-save" onclick="onSaveUnit()" style="display: none;">
                        <i class="fa fa-save"></i>
                        <small>@lang('Save')</small>
                    </a>
                    <a class="btn btn-outline-info button-boxed btn-offer-save" onclick="showMultipleModal()" style="display: none;">
                        <i class="fa fa-save"></i>
                        <small>@lang('Save Offer')</small>
                    </a>
                    <a href="#" class="btn btn-outline-primary button-boxed btn-report" onclick="pdfReport()" style="display: none;">
                        <i class="fa fa-book"></i>
                        <small>@lang('PDF Report')</small>
                    </a> --}}
                </div>
                @endif
                @if(request()->is('admin/projects'))
                <div class="d-inline-block mr-3">
                    <!-- <a href="{{route('admin.projects.profile')}}" class="btn btn-outline-success button-boxed">
                        <i class="fa fa-plus"></i>
                        <small>@lang('New')</small>
                    </a> 
                    <a class="btn btn-outline-success button-boxed" onclick="modify()">
                        <i class="fa fa-edit"></i>
                        <small>@lang('Modify')</small>
                    </a>
                    <a class="btn btn-outline-info button-boxed" onclick="duplicate()">
                        <i class="fa fa-paste"></i>
                        <small>@lang('Duplicate')</small>
                    </a>
                    <a class="btn btn-outline-danger button-boxed" onclick="del()">
                        <i class="fa fa-trash"></i>
                        <small>@lang('Delete')</small>
                    </a> -->
                </div>
                @endif
                @if(request()->is('admin/customer') || request()->is('admin/projects/profile*'))
                <!-- <div class="d-inline-block mr-3">
                    <div id="customer_manager_company_buttons">
                        <h5 class="d-inline-block mr-2">Company</h5>
                        <div class="d-inline-block mr-3">
                            <a class="btn btn-outline-success button-boxed" onclick="newCompany()">
                                <i class="fa fa-plus"></i>
                                <small>@lang('New')</small>
                            </a>
                            <a class="btn btn-outline-success button-boxed" onclick="editCompany()">
                                <i class="fa fa-edit"></i>
                                <small>@lang('Modify')</small>
                            </a>
                            <a class="btn btn-outline-danger button-boxed mr-3" onclick="deleteCompany()">
                                <i class="fa fa-trash"></i>
                                <small>@lang('Delete')</small>
                            </a>
                        </div>
                    </div> -->
                    <!-- <div id="customer_manager_contact_buttons" style="display: none;">
                        <h5 class="d-inline-block mr-2">Contact</h5>
                        <div class="d-inline-block mr-3">
                            <a href="#" class="btn btn-outline-success button-boxed" onclick="newContact()">
                                <i class="fa fa-plus"></i>
                                <small>@lang('New')</small>
                            </a>
                            <a href="#" class="btn btn-outline-success button-boxed" onclick="updateContact()">
                                <i class="fa fa-edit"></i>
                                <small>@lang('Modify')</small>
                            </a>
                            <a href="#" class="btn btn-outline-danger button-boxed" onclick="deleteContact()">
                                <i class="fa fa-trash"></i>
                                <small>@lang('Delete')</small>
                            </a>                    
                        </div>
                    </div> -->
                <!-- </div> -->
                
                    @if(request()->is('admin/projects/profile*'))
                    <!-- <a href="{{route('admin.projects')}}" class="btn btn-outline-danger button-boxed">
                        <i class="fa fa-reply"></i>
                        <small>@lang('Back')</small>
                    </a> -->
                    <!-- <a class="btn btn-outline-success button-boxed" onclick="goToDetail()">
                        <i class="fa fa-check"></i>
                        <small>@lang('Next')</small>
                    </a> -->
                    @endif
                @endif
                <!-- <select class="language langSel" style="width:75px">
                    @foreach($languagelists as $item)
                        <option value="{{ $item->code }}" @if(session('lang') == $item->code) selected @endif>
                            {{ $item->name }}
                            
                        </option>
                    @endforeach
                </select> -->

                <!-- username for edit role page -->
                @if(isset($role->title))
                <div style="font-weight: bold;">
                    {{ $role->title }}
                </div>
                @endif

                <div class="dropdown"> 
                    <button class="dropbtn"> 
                    @foreach($languagelists as $item)
                        <a href="javascript:void(0)" > @if(session('lang') == $item->code) <span class="flag-icon flag-icon-{{ strtolower($item->country_flag) }}"></span> @endif</a>
                    @endforeach 
                    </button> 
                    
                    <div class="dropdown-content"> 
                    @foreach($languagelists as $item)
                        <a href="javascript:void(0)" class="langSel" data-code="{{$item->code}}" @if(session('lang') == $item->code) id="selected-lang" @endif ><span class="flag-icon flag-icon-{{ strtolower($item->country_flag) }}"></span></a> 
                    @endforeach
                    </div> 
                </div>

                <!-- @php 
                    echo count($languagelists);
                    echo session('lang');
                @endphp
                             -->

                @if(count(config('panel.available_languages', [])) > 1)
                    <div class="flex items-center">
                        <div class="languages">
                            <select onchange="window.location.href = $(this).val()">
                                @foreach(config('panel.available_languages') as $langLocale => $langName)
                                    <option
                                        value="{{ url()->current() }}?change_language={{ $langLocale }}"
                                        @if(strtoupper($langLocale) ==strtoupper(app()->getLocale())) selected @endif
                                    >{{ strtoupper($langLocale) }} ({{ $langName }})</option>
                                @endforeach
                            </select>
                            <div class="icon">
                                <i class="fa fa-caret-down fill-current h-4 w-4" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                @endif
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="mx-auto px-6 py-8" style="">
                    @if(session('message'))
                        <div class="alert success">
                            {{ session('message') }}
                        </div>
                    @endif
                    @if($errors->count() > 0)
                        <div class="alert danger">
                            <ul class="list-none">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')

                </div>
            </main>
            <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/intlTelInput.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>    
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <!-- For Modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.0/js/bootstrap.min.js"></script>  
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<!-- DataTables -->
<!-- <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>   -->
    <!-- end For Modal -->
    <script src="{{ asset('js/main.js') }}"></script>
    @yield('scripts')

    <script>

//         $(".langSel").on("change", function() {
//            
//             window.location.href = "{{route('admin.home')}}/languages/change/"+$(this).val();
//             // $.ajax({
//             //     url: "{{route('admin.home')}}/languages/change/"+$(this).val(),
//             //     method: "GET",
//             //     success: (res) => {
//             //         console.log(res);
//             //     },
//             //     error: (error) => {
//             //         console.error(error);
//             //     }
//             // })
//         });

        $(".langSel").on("click", function() {
            var code = $(this).data('code');
            window.location.href = "{{route('admin.home')}}/languages/change/"+code;
            // $.ajax({
            //     url: "{{route('admin.home')}}/languages/change/"+$(this).val(),
            //     method: "GET",
            //     success: (res) => {
            //         console.log(res);
            //     },
            //     error: (error) => {
            //         console.error(error);
            //     }
            // })
        });

        $(document).on('click', function(event) {

    if (!$(event.target).closest('.dropdown-content').length && !$(event.target).hasClass('dropbtn')) {
    
        $(".dropdown-content").css("display", "none");
    }
});

$(".dropbtn").on('click', function(event) {
    
    event.stopPropagation();
    $(".dropdown-content").css("display", "block");
});

 $('.dropbtn').on("click",function(){
    $('.dropbtn').css({
    'background-color': '#00000014',
    'border-radius': '3px'
});

$(document).click(function(event) {
    if (!$(event.target).closest('.dropdown').length) {
        // Click occurred outside .dropdown
        $('.dropbtn').css({
            'background-color': '',
            'border-radius': ''
        });
    }
});

 })




 
    </script>
    


    {{-- <script>
        $(document).ready(function() {
            $.ajax({
                url: 'https://ipinfo.io',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    var lan = ['DE', 'FR', 'NL', 'S', 'N', 'PL', 'EN'];
                    var changeUrl;
    
                    if (array_in(lan, data.country)) {
                        changeUrl = "{{ route('admin.home') }}/languages/change/" + data.country;
                    } else {
                        changeUrl = "{{ route('admin.home') }}/languages/change/EN";
                    }
    
                    window.location.href = changeUrl;
    
                    console.log('Success:', data.country);
                },
                error: function(xhr, status, error) {
                    console.error('Error:', status, error);
                }
            });
        });
    </script> --}}


    <script>
        $(document).ready(function() {
            // Check if the AJAX request has already been made in this session
            var isFirstTime = sessionStorage.getItem('isFirstTime');
    
            // If it's the first time, make the AJAX request
            if (isFirstTime !== 'false') {
                var apiKey = '0c19028b4312f1';
    
                $.ajax({
                    url: 'https://ipinfo.io/',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        token: apiKey,
                        timestamp: new Date().getTime()
                    },
                    success: function(data) {
                        var lan = ['DE', 'FR', 'NL', 'S', 'N', 'PL', 'EN'];
                        var changeUrl;
    
                        if (lan.indexOf(data.country) !== -1) {
                            changeUrl = "{{ route('admin.home') }}/languages/change/" + data.country;
                        } else {
                            changeUrl = "{{ route('admin.home') }}/languages/change/EN";
                        }
    
                        window.location.href = changeUrl;
    
                        console.log('Success:', data.country);
    
                        // Set isFirstTime to false to indicate that the AJAX request has been made
                        sessionStorage.setItem('isFirstTime', 'false');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', status, error);
                    }
                });
            }
        });
    </script>






<script>
    var APP_URL = {!! json_encode(url('/')) !!};
 </script>

</body>

</html>
