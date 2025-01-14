@extends('layouts.admin')
@section('content')
<style>
    .act_btn a {
        /* display:flex; */
        float: left;
    }

    .act_btn {
        display: flex;
        justify-content: space-around;
        align-items: center;
        height: 36px;
    }

    .hide {
        display: none;
    }

    #valid-msg {
        /* color: #00c900; */
        fill: green;
    }

    .contact {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: none;
        background-color: #f9f9f9;
        transition: border 0.3s ease;
        justify-content: end;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        box-shadow: none;
    }

    .next {
        float: right;
    }

    input.intlphone-input::placeholder {
        color: #8080804f
    }

    .iti {
        width: 100%;
    }

    .contact #error-msg, .error {
        color: red
    }

    .border-color-red {
        border-color: red;
    }

    .required-badge {
        padding-left: 5;
    }
    


</style>

<link href="{{ asset('css/company-contact.css') }}" rel="stylesheet" />
<div class="row">
    <div class="col-md-6">
        <div class="main-card">
            <div class="header" style="font-size:18px;">
                @lang('Company selection')

                <a class="" onclick="newCompany()">
                    <img class="ne" src="{{ asset('assets/icons/plus-circle-icon-original.svg') }}"
                        width="30px" height="30px" />
                </a>

            </div>
            <div class="body">
                <div class="w-full" style="overflow:auto;">
                    <table class="display compact project-table datatable-customer ">
                        <thead>
                            <tr>
                                <th>@lang('company name')</th>
                                <th>@lang('Address')</th>
                                <th>@lang('Tel. No.')</th>
                                <th>@lang('VAT')</th>
                                <th>@lang('Description')</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody onclick="show()">
                            <tr class="firstrow">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <?php
                            if (isset($company_list)) {
                                foreach ($company_list as $company) {
                                    $companydetail = $company->name . '$$' . $company->address . '$$' . $company->phone . '$$' . $company->VAT . '$$' . $company->description;
                            ?>
                                    <tr data-id="{{$company->id}}" class="jsselected">
                                        <td class="nowrap">{{$company->name}}</td>
                                        <td class="nowrap">{{$company->address}}</td>
                                        <td class="nowrap">{{$company->phone}}</td>
                                        <td class="nowrap">{{$company->VAT}}</td>
                                        <td class="nowrap">{{$company->description}}</td>
                                        <td class="nowrap">
                                            <div class="act_btn">
                                                <a href="#!" onclick="editCompany('{{ $company->id }}','<?php echo $companydetail; ?>')">
                                                    <img
                                                        class="new"
                                                        src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}"
                                                        width="25px" height="25px" /></a>
                                                <a href="#!" onclick="deleteCompany('{{ $company->id }}')">
                                                    <img
                                                        class="new" src="{{ asset('assets/icons/trash-icon-original.svg') }}"
                                                        width="25px" height="25px" /></a>

                                            </div>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="main-card contactlist" style="display:none;">
            <div class="header" style="font-size:18px;">
                @lang('Contact list')
                <a href="#!" onclick="newContact()"><img class="new"
                        src="{{ asset('assets/icons/plus-circle-icon-original.svg') }}" width="30px" /></a>
            </div>
            <div class="body ">
                <div class="w-full" style="overflow: auto;">
                    <table class="display compact project-table datatable-contact" style="width:100%;">
                        <thead>
                            <tr>
                                <th>@lang('contact person')</th>
                                <!-- <th>@lang('Last Name')</th> -->
                                <!-- <th>@lang('Tel. No.')</th> -->
                                <th>@lang('Phone Mobile')</th>
                                <th width="30%">@lang('Email')</th>
                                <th>@lang('Job Position')</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <a href="#!" class="next" onclick="goToDetail('${res.result[i].id}','${res.result[i].company_id}')"><img class="new mb-2" src="{{asset('assets/icons/nextArrow.png')}}" width="35px" height="35px" /></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="companyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('new company')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="company_form">
                    <input type="hidden" id="company_id" name="company_id" class="form-control">
                    <div class="form-group">
                        <label for="company_VAT" class="text-xs">@lang('VAT')<span class="required-badge">(*)</span></label>
                        <div class="position-relative">
                            <input type="text" id="company_VAT" name="company_VAT" class="form-control" required>
                            <div id="loader" class="d-none">
                                <div class="lds-dual-ring"></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_name" class="text-xs">@lang('company name')<span class="required-badge">(*)</span></label>
                        <input type="text" id="company_name" name="company_name" class="form-control" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="company_address" class="text-xs">@lang('Address')<span class="required-badge">(*)</span></label>
                        <input type="text" id="company_address" name="company_address" class="form-control" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="company_phone" class="text-xs">@lang('Office telephone no')<span class="required-badge">(*)</span></label>
                        <div>
                            <input type="hidden" name="full_mobile_phone" id="full_mobile_phone">
                            <input type="text" id="company_phone" name="company_phone" class="form-control intlphone-input" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="legal_form" class="text-xs">@lang('Legal Form')<span class="required-badge">(*)</span></label>
                        <div>
                            <select class="form-control" id="legal_form" name="legal_form" required>
                                <option value="">@lang('Select a legal form')</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="sector_activity" class="text-xs">@lang('Sector of Activity')<span class="required-badge">(*)</span></label>
                        <div>
                            <select class="form-control" id="sector_activity" name="sector_activity" required>
                                <option value="">@lang('Select a sector of Activity')</option>
                                @foreach($servicesActivitys as $key => $service_activity)
                                <option value="{{$key}}" @if(old("sector_activity") && old("sector_activity")==$key) selected @endif>{{$service_activity['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="company_size" class="text-xs">@lang('Company Size')<span class="required-badge">(*)</span></label>
                        <div>
                            <select class="form-control" id="company_size" name="company_size" required>
                                <option value="">@lang('Select a company size')</option>
                                @foreach($company_sizes as $key => $size)
                                <option value="{{$key}}" @if(old("company_size") && old("company_size")==$key) selected @endif>{{$size . ' ' . trans('employees')}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                <button type="button" class="btn btn-primary" onclick="saveCompany()">@lang('Save')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="contactModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Contact People')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span >&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="#" id="contact_form">
                    <input type="hidden" id="contact_id" name="contact_id" class="form-control">
                    <input type="hidden" id="cont_company_id" name="company_id" class="form-control">
                    <input type="hidden" id="full_contact_mobile_no" name="full_contact_mobile_no" class="form-control">
                    <div class="form-group">
                        <label for="first_name" class="text-xs">@lang('contact person')<span style="padding-left:5px;">(*)</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="mobile_no" class="text-xs">@lang('Phone Mobile')<span style="padding-left:5px;">(*)</span></label>
                        <div class="contact">
                            <input type="tel" id="mobile_no" name="mobile_no" class="form-control intlphone-input">
                            <span id="valid-msg" class="hide">
                                <img class="new mb-2" src="{{asset('assets/icons/check-circle-icon-original.svg')}}" width="25px" height="25px" />
                            </span>
                            <span id="error-msg" class="hide"></span>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="email" class="text-xs">@lang('Email')</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="job_position" class="text-xs">@lang('Job Position')</label>
                        <select class="form-control" id="job_position" name="job_position" required>
                            @foreach($roles as $role)
                            <option value="{{$role}}">{{trans($role)}}</option>;
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('Close')</button>
                <button type="button" class="btn btn-primary" id="saveContact" onclick="saveContact(this)">@lang('Save')</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@include('layouts.register-script', ['legalForms' => $legalForms])

<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<script>
    function select_row(id) {
        // Get the total number of rows in the table
        var total_rows = $("#DataTables_Table_1 tr").length;
        // Iterate through each row
        for (var i = 0; i < total_rows; i++) {
            // Check if the current row does not have the specified id
            if (i !== id) {
                // Hide the row
                $('#DataTables_Table_1 tbody tr#row_' + i).hide();
            }
        }
    }
</script>




@parent
<script>
    var table1, table2;
    var contact_list = null;
    var temp_func = null;
    var _token = null;
    var global_company_id;
    $(function() {
        table1 = $('.datatable-customer').DataTable({
            dom: 'ftlp',
            pageLength: 100,
            scrollCollapse: true,
            scrollX: false,
            responsive: false,
            select: {
                style: 'single' // or 'multi'
            }
        });

        table1.on('select', function(e, dt, type, indexes) {
            if (type === 'row') {
                // console.log('Row '+indexes[0]+' selected');
                var id = $(dt.row(indexes[0]).node()).data('id');
                drawContactTable(id);
                table1.draw(false);
            }
        });


        table2 = $('.datatable-contact').DataTable({
            dom: 'ftlp',
            pageLength: 100,
            scrollCollapse: true,
            responsive: true,
            select: {
                style: 'single' // or 'multi'
            }
        });
    });

    $(document).on('click', 'tbody tr', (e) => {
        var classlist = $(e.target).parents('table').attr('class').split(' ');
        if (classlist.includes('datatable-customer')) {
            $('#customer_manager_company_buttons').show();
            $('#customer_manager_contact_buttons').hide();
        } else {
            $('#customer_manager_company_buttons').hide();
            $('#customer_manager_contact_buttons').show();
        }
    });

    function show() {
        $('.contactlist').show();
    }

    function drawContactTable(company_id) {
        $.ajax({
            method: 'GET',
            url: `{{route('admin.projects.get.contactlist')}}?id=` + company_id,
            // data: {id: company_id}
        }).done(function(res) {

            contact_list = res.result;
            table2.rows().remove();
            for (var i = 0; i < res.result.length; i++) {
                var contactdetail = res.result[i].id + '$$' + res.result[i].name + '$$' + res.result[i].phone + '$$' + res.result[i].email + '$$' + res.result[i].position + '$$' + res.result[i].company_id;
                var $tr = $(`<tr data-id="${res.result[i].id}" id='row_${i}' onclick=select_row(${i}); >\
                    <td  >${res.result[i].name}</td>\
                    
                    <td>${res.result[i].phone}</td>\
                    <td>${res.result[i].email}</td>\
                    <td>${res.result[i].position}</td>\
                    <td class="nowrap act_btn">
                                   <a href="#!" onclick="updateContact('${res.result[i].id}','${contactdetail}','${res.result[i].company_id}')"><img class="new" src="{{asset('assets/icons/pencil-line-icon-original.svg')}}" width="25px" height="25px"/></a>
                                   <a href="#!" onclick="deleteContact('${res.result[i].id}','${res.result[i].company_id}')"><img class="new" src="{{asset('assets/icons/trash-icon-original.svg')}}" width="25px" height="25px"/></a>
                            
                               </td>\
                    </tr>`);
                table2.row.add($tr);
            }
            table2.draw(false);
            if (temp_func !== null) {
                temp_func();
                temp_func = null;
            }



        });
    }

    $('.next').hide();
    $('.datatable-contact tbody ').on('click', function() {
        $('.next').show();
    })


    function goToDetail(contact_id, company_id) {
        var company_id = $(table1.row({
            selected: true
        }).node()).data('id');
        var contact_id = $(table2.row({
            selected: true
        }).node()).data('id');
        if (company_id === undefined || contact_id === undefined) {
            alert("@lang('You must select Company and Contact people')");
            return false;
        }
        location.href = `{{route('admin.projects.detail')}}/{{$pid}}/${company_id}/${contact_id}`;
    }

    function newContact() {
        // alert(company_id);
        var company_id = $(table1.row({
            selected: true
        }).node()).data('id');
        $(document).on("click", '.jsselected', function() {

        })
        if (company_id === undefined) {
            alert("@lang('You must select Company first')");
            return false;
        }
        $('#contactModal .modal-title').text("@lang('Create Contact People')");
        $('#contactModal #contact_id').val(0);
        $('#contactModal #company_id').val(company_id);
        $('#contactModal #cont_company_id').val(company_id);
        $('#contactModal #first_name').val("");
        $('#contactModal #last_name').val("");
        $('#contactModal #tel_no').val("");
        $('#contactModal #mobile_no').val("");
        $('#contactModal #email').val("");
        $('#contactModal #job_position').val("");
        $('#contactModal').modal('show');
    }

    /*  function updateContact() {
            var contact_id = $(table2.row({selected: true}).node()).data('id');
            if(contact_id === undefined) {
                alert("@lang('You must select Contact people to update')");
                return false;
            }
            $('#contactModal .modal-title').text("@lang('Edit Contact People')");

            //let contact_user = contact_list.filter(c => c.id == contact_id)[0];            

            $('#contactModal #contact_id').val(contact_user.id);
            $('#contactModal #first_name').val(contact_user.firstname);
            $('#contactModal #last_name').val(contact_user.secondname);
            $('#contactModal #tel_no').val(contact_user.phone);
            $('#contactModal #mobile_no').val(contact_user.mobile);
            $('#contactModal #email').val(contact_user.email);
            $('#contactModal #job_position').val(contact_user.job_position);
            $('#contactModal').modal('show');
        }
        

        function saveContact() {
            var flag = true;
            var company_id = $(table1.row({selected: true}).node()).data('id');
            var postData = {company_id: company_id};
            $('#contactModal .modal-body input,#contactModal .modal-body select').each(function() {
                const _v = $(this).val().trim();
                if(_v === "") {
                    alert("@lang('You must input all fields.')");
                    $(this).focus();
                    flag = false;
                    return false;
                }
                postData[$(this).attr('name')] = _v;
            });
            if(!flag)
                return false;
            
            $.ajax({
                method: 'POST',newConta
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.projects.store.contact')}}',
                data: postData
            }).done(function (res) { 
                drawContactTable(company_id);
                $('.modal').modal('hide');
            });
        }
*/
    function updateContact(cid, contactdetails, company_id) {

        var contact_id = $(table1.row({
            selected: true
        }).node()).data('id');
        // // if(contact_id === undefined) {
        // //     alert("@lang('You must select Contact people to update')");
        // //     return false;
        // // }
        $('#contactModal .modal-title').text("@lang('Edit Contact People')");
        var contact_user = contactdetails.split('$$');
        // let contact_user = contact_list.filter(c => c.id == contact_id)[0];            
        global_company_id = contact_user[5];
        console.log(cid, contact_user);
        //$('#contactModal #company_id').val(company_id);
        $('#contactModal #cont_company_id').val(company_id);
        $('#contactModal #contact_id').val(contact_user[0]);
        $('#contactModal #first_name').val(contact_user[1]);
        // $('#contactModal #last_name').val(contact_usersecondname);
        //$('#contactModal #tel_no').val(contact_user[2]);
        $('#contactModal #mobile_no').val(contact_user[2]);
        $('#contactModal #email').val(contact_user[3]);
        $('#contactModal #job_position').val(contact_user[4]);
        $('#contactModal').modal('show');
    }

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return false;
        } else {
            return true;
        }
    }

    const contact_phone_input = document.querySelector("#mobile_no");
    const errorMsg = document.querySelector("#error-msg");
    const validMsg = document.querySelector("#valid-msg");
    // var warningIcon = '{{ asset("assets/icons/warning-icon-original.svg") }}';

    // here, the index maps to the error code returned from getValidationError - see readme
    const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];


    /** contact form part */
    const phone_input = document.querySelector("#company_phone");

    const iti_company = window.intlTelInput(phone_input, {
      initialCountry: "auto",
      nationalMode: true,
      onlyCountries: availableCountries,
      utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"
    });

    // initialise plugin
    const iti_contact = window.intlTelInput(contact_phone_input, {
        initialCountry: "auto",
        onlyCountries: availableCountries,
        utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js"
    });

    const reset = () => {
        input.classList.remove("error");
        errorMsg.innerHTML = "";
        errorMsg.classList.add("hide");
        validMsg.classList.add("hide");
    };
    var contact_validate;
    // on blur: validate
    // input.addEventListener('blur', () => {
    //     reset();
    //     if (input.value.trim()) {
    //         if (iti.isValidNumber()) {
    //             validMsg.classList.remove("hide");
    //             $('#hide').show();
    //             contact_validate = true;

    //         } else {
    //             input.classList.add("error");
    //             const errorCode = iti.getValidationError();
    //             errorMsg.innerHTML = errorMap[errorCode];
    //             errorMsg.classList.remove("hide");
    //             contact_validate = false;


    //         }
    //     }
    // });

    // // on keyup / change flag: reset
    // input.addEventListener('change', reset);
    // input.addEventListener('keyup', reset);

    function saveContact(thisatt) {     

         var company_id = $(table1.row({selected: true}).node()).data('id');
         $("#cont_company_id").val(company_id);

        if($('#contact_form').valid()){

            postData = $('#contact_form').serialize();

            $.ajax({
                method: 'POST',
                headers: {
                    'x-csrf-token': _token
                },
                url: `{{route('admin.projects.store.contact')}}`,
                data: postData,
                success: function(response) {
                    $('.modal').modal('hide');
                    drawContactTable(company_id);
                },
                error: function(res) {
                    console.log(res);
                    // $('input[name=company_id]').val(0);
                    // $('input[name=company_name]').val('');
                    // $('input[name=company_address]').val('');
                    $('#contact_form').validate().showErrors({
                        "email": res.responseJSON.errors.email[0]
                    });
                },

            // }).done(function(res) {
            //    
            });
        }
    }

    function deleteContact(contact_id, company_id) {
        /*var company_id = $(table1.row({selected: true}).node()).data('id');
        var contact_id = $(table2.row({selected: true}).node()).data('id');
        if(contact_id === undefined) {
            alert("@lang('You must select Contact people to delete')");
            return false;
        }*/

        if (!confirm("@lang('Are you sure?')"))
            return false;

        $.ajax({
            method: 'POST',
            headers: {
                'x-csrf-token': _token
            },
            url: `{{route('admin.projects.delete.contact')}}` + '/' + contact_id,
        }).done(function(res) {
            drawContactTable(company_id);
        });
    }

    function newCompany() {
        // $('#companyModal .modal-title').text("@lang('Create Company')");
        $('#companyModal .modal-title').text("@lang('new company')");
        $('#companyModal #company_id').val(0);
        $('#companyModal #company_name').val("");
        $('#companyModal #company_address').val("");
        $('#companyModal #company_phone').val("");
        $('#companyModal #company_VAT').val("");
        $('#companyModal #company_desc').val("");
        $('#companyModal').modal('show');
    }

    function editCompany(cid, companydetail) {
        // alert(companydetail);
        // var company_id = $(table1.row({selected: true}).node()).data('id');

        // if(company_id === undefined) {
        //     alert("@lang('You must select company to update')");
        //     return false;
        // }
        $('#companyModal .modal-title').text("@lang('Edit Company')");
        var company_data = companydetail.split('$$');
        // console.log("omkar"+company_data);
        //var company_data = table1.row({selected: true}).data();            

        $('#companyModal #company_id').val(cid);
        $('#companyModal #company_name').val(company_data[0]);
        $('#companyModal #company_address').val(company_data[1]);
        $('#companyModal #company_phone').val(company_data[2]);
        $('#companyModal #company_VAT').val(company_data[3]);
        $('#companyModal #company_desc').val(company_data[4]);
        $('#companyModal').modal('show');
    }

    function saveCompany() {


        if($('#company_form').valid()){


            postData = $('#company_form').serialize();

            $.ajax({
                method: 'POST',
                headers: {
                    'x-csrf-token': _token
                },
                url: `{{route('admin.projects.save.company')}}`,
                data: postData
            }).done(function(res) {
                $('.modal').modal('hide');
                var editIcon = '{{ asset("assets/icons/pencil-line-icon-original.svg") }}';
                var deleteIcon = '{{ asset("assets/icons/trash-icon-original.svg") }}';
                var editAction = `<a href="#!" onclick="editCompany(${res.result.id}, '${res.result.company_detail}')"><img class="new" src="${editIcon}" width="25px" height="25px" /></a>`;
                var deleteAction = `<a href="#!" onclick="deleteCompany(${res.result.id}, '${res.result.company_detail}')"><img class="new" src="${deleteIcon}" width="25px" height="25px" /></a>`;
                var finalAction = editAction + " " + deleteAction;
                let data = [res.result.name, res.result.address, res.result.phone, res.result.VAT, res.result.description, finalAction];
                //location.reload();
                if (postData.company_id > 0) {
                    // table1.row({selected:true}).data(data).draw(false);
                    location.reload();
                } else {
                    var innerTr = data.map((item, index) => {
                        if (index == data.length - 1) {
                            return '<td class="nowrap act_btn">' + item + '</td>';
                        } else {
                            return '<td class="nowrap">' + item + '</td>';
                        }
                    }).join("");
                    var new_tr = $('<tr data-id="' + res.result.id + '">' + innerTr + '<tr>');
                    table1.row.add(new_tr).draw(false);

                }
            });

            return true;
        }

        // var flag = true;
        // var postData = {};
        // $('#companyModal .modal-body input').each(function() {
        //     const _v = $(this).val().trim();
        //     if (_v === "") {
        //         alert("@lang('You must input all fields.')");
        //         $(this).focus();
        //         flag = false;
        //         return false;
        //     }
        //     postData[$(this).attr('name')] = _v;
        // });
        // if (!flag)
        //     return false;

       

    }

    function deleteCompany(company_id) {
        // var company_id = $(table1.row({selected: true}).node()).data('id');
        // if(company_id === undefined) {
        //     alert("@lang('You must select company to delete')");
        //     return false;
        // }

        if (!confirm("@lang('Are you sure?')"))
            return false;

        $.ajax({
            method: 'POST',
            headers: {
                'x-csrf-token': _token
            },
            url: `{{route('admin.projects.delete.company')}}`,
            data: {
                id: company_id
            }
        }).done(function(res) {
            if (res.result == true) {
                table1.row('.selected').remove().draw(false);
                location.reload();
            }
        });
    }

    $('.modal [data-dismiss]').on('click', function() {
        $(this).closest('.modal').modal('hide');
    });

    function searchCustomer() {
        const keyword = $('#keyword_customer').val().trim();
        table1.search(keyword).draw(false);
        table2.search(keyword).draw(false);
    }


    $(document).ready(function() {


        _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        <?php
        if ($cid > 0 && $uid > 0) {
        ?>
            //table1.row('[data-id={{$cid}}]').select();
            temp_func = function() {
                //table2.row('[data-id={{$uid}}]').select();                
            }
        <?php } else { ?>
            var firstRowIndex = table1.rows({
                order: 'current'
            }).indexes()[0];
            table1.row(firstRowIndex).select();
        <?php } ?>
        table1.draw(false);
        table2.draw(false);

        $('#keyword_customer').on('keyup', function(e) {
            if (e.keyCode === 13)
                searchCustomer();
        })

        $.validator.addMethod(
            "vatFormat",
            function(value, element) {
                const pattern = /^[A-Z]{2}/;
                return pattern.test(value);
            },
            function(params, element) {
                return "Invalid VAT number.";
            }
        );

        $.validator.addMethod(
            "vatPattern",
            function(value, element) {
                const country = value.slice(0, 2); // Get selected country
                const vatData = vatPatterns[country]; // Get pattern and format
                if (!vatData) return false; // If country not selected
                return vatData.pattern.test(value); // Validate VAT
            },
            function(params, element) {
                const inputval = $(element).val();                
                const country = inputval.slice(0, 2); // Get selected country
                const vatData = vatPatterns[country]; // Get format
                return vatData ? `The VAT number should be in the format: ${vatData.format}` : "Invalid VAT number.";
            }
        );

        $.validator.addMethod(
            "validContactphone",
            function(value, element) {
                // Validate only if the field has value
                if (value.trim() === "") {
                    return false;
                }

                $('input[name=full_contact_mobile_no]').val(iti_contact.getNumber());
                // Use intl-tel-input's isValidNumber()
                return iti_contact.isValidNumber();
            },
            "<?php echo trans('Please enter a valid phone number.'); ?>"
        );

        $.validator.addMethod(
            "validPhone",
            function(value, element) {
                // Validate only if the field has value
                if (value.trim() === "") {
                    return false;
                }

                $('input[name=full_mobile_phone]').val(iti_company.getNumber());
                // Use intl-tel-input's isValidNumber()
                return iti_company.isValidNumber();
            },
            "<?php echo trans('Please enter a valid phone number.'); ?>"
        );

        $('#contact_form').validate({ // initialize the plugin
            rules: {
                mobile_no: {
                    required: true,
                    validContactphone: true, // Use the custom validation method
                },
            },
            messages: {
                mobile_no: {
                    required: "<?php echo trans('The phone number is required.'); ?>",
                    validPhone: "<?php echo trans('Please enter a valid phone number.'); ?>",
                },
            },
        });


        $('#company_form').validate({ // initialize the plugin
            rules: {
                company_phone: {
                    required: true,
                    validPhone: true, // Use the custom validation method
                },
                company_VAT: {
                    required: true,
                    vatFormat: true,
                    vatPattern: true, // Use the custom validation method
                    // uniqid:true
                },
            },
            messages: {
                company_phone: {
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


        $(document).on('blur', 'input[name=company_VAT]', function() {
            console.log('blur event triggered');
            const vatNumber = $(this).val();

            if($('#company_form').validate().element('#company_VAT')){
                console.log('valid');

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
                        if (response.status === 'success') { // existing company
                            $('#company_form').validate().showErrors({
                                "company_VAT": "The VAT Number is already existing"
                            });                            
                            $('input[name=company_VAT]').val('');
                        } else {
                            if (response.data.valid) {
                                $('input[name=company_id]').val(0);
                                $('input[name=company_name]').val(response.data.name);
                                $('input[name=company_address]').val(response.data.address);
                                const code = vatNumber.slice(0,2);

                                iti_company.setCountry(code);

                                let forms = legalForms[code] ? legalForms[code] : legalForms['GB']; // Get legal forms for the selected country

                                // Display the legal forms
                                const legalFormSelect = document.getElementById('legal_form');
                                legalFormSelect.innerHTML = '<option value="">Select a legal form</option>'; // Clear previous options

                                forms.forEach((form, index) => {
                                    const option = document.createElement('option');
                                    option.value = code + '_' + index; // Use index or any unique identifier
                                    option.textContent = form; // Set the legal form text
                                    legalFormSelect.appendChild(option);
                                });

                            } else {
                                $('input[name=company_id]').val(0);
                                $('input[name=company_name]').val('');
                                $('input[name=company_address]').val('');
                                $('#company_form').validate().showErrors({
                                "company_VAT": "The VAT Number is wrong."
                                });
                            }
                        }
                    },
                    error: function() {
                        $('input[name=company_id]').val(0);
                        $('input[name=company_name]').val('');
                        $('input[name=company_address]').val('');
                        $('#company_form').validate().showErrors({
                            "company_VAT": "The VAT Number is wrong."
                        });
                    },
                    complete: function() {
                        $('#loader').addClass('d-none');
                    }
                    });
                }
                return true; 
            }
           
        });




      

    });

    $('.firstrow').hide();
</script>

@endsection