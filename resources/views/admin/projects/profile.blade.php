@extends('layouts.admin')
@section('content')
<style>
    .act_btn a{
       /* display:flex; */
      float:left;
    }
    .act_btn{
        display:flex;
        justify-content:space-around;
        align-items: center;
        height: 36px;
    }
    .hide {
       display: none;
    }
    #valid-msg {
  /* color: #00c900; */
  fill:green;
     }
     .contact{
        display:flex;
        justify-content:space-between;
        align-items:center;
     }
     .dataTables_wrapper .dataTables_filter input {
       border:none;
       background-color:#f9f9f9;
       transition: border 0.3s ease;
       justify-content:end;
      }
      .dataTables_wrapper .dataTables_filter input:focus {
        outline: none; 
        box-shadow: none; 
      }
      .next{
        float:right;
      }
      input#mobile_no::placeholder{
        color:#8080804f
      }
      .contact #error-msg {
        color: red
      }
      .border-color-red {
        border-color: red;
      }
</style>
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
                            if(isset($company_list)) {
                                foreach($company_list as $company) {
                                        $companydetail = $company->name.'$$'.$company->address.'$$'.$company->phone.'$$'.$company->VAT.'$$'.$company->description;
                                    ?>
                            <tr data-id="{{$company->id}}" class="jsselected">
                                <td class="nowrap">{{$company->name}}</td>
                                <td class="nowrap">{{$company->address}}</td>
                                <td class="nowrap">{{$company->phone}}</td>                        
                                <td class="nowrap">{{$company->VAT}}</td>
                                <td class="nowrap">{{$company->description}}</td>
                                <td class="nowrap act_btn" >
                                        <a href="#!"
                                            onclick="editCompany('{{ $company->id }}','<?php echo $companydetail; ?>')"><img
                                                class="new"
                                                src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}"
                                                width="25px" height="25px" /></a>
                                        <a href="#!" onclick="deleteCompany('{{ $company->id }}')"><img
                                                class="new" src="{{ asset('assets/icons/trash-icon-original.svg') }}"
                                                width="25px" height="25px" /></a>
                                      
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
                src="{{ asset('assets/icons/plus-circle-icon-original.svg') }}" width="30px"/></a>
            </div>
            <div class="body " >
                <div class="w-full" style="overflow: auto;">
                    <table class="display compact project-table datatable-contact" style="width:100%;" > 
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
                    <a href="#!" class="next" onclick="goToDetail('${res.result[i].id}','${res.result[i].company_id}')"><img class="new mb-2" src="{{asset('assets/icons/nextArrow.png')}}" width="35px" height="35px"/></a>
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
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <input type="hidden" id="company_id" name="company_id" class="form-control">                
            <div class="form-group">
                <label for="company_name" class="text-xs">@lang('company name')<span style="padding-left:5px;">(*)</span></label>
                <input type="text" id="company_name" name="company_name" class="form-control">                
            </div>
            <div class="form-group">
                <label for="company_address" class="text-xs">@lang('Address')</label>
                <input type="text" id="company_address" name="company_address" class="form-control">                
            </div>
            <div class="form-group">
                <label for="company_phone" class="text-xs">@lang('Office telephone no')<span style="padding-left:5px;">(*)</span></label>
                <input type="text" id="company_phone" name="company_phone" class="form-control">                
            </div>
            <div class="form-group">
                <label for="company_VAT" class="text-xs">@lang('VAT')</label>
                <input type="text" id="company_VAT" name="company_VAT" class="form-control">                
            </div>
            <div class="form-group">
                <label for="company_desc" class="text-xs">@lang('Description')</label>
                <input type="text" id="company_desc" name="company_desc" class="form-control">                
            </div>            
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
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <input type="hidden" id="contact_id" name="contact_id" class="form-control"> 
            <input type="hidden" id="cont_company_id" name="company_id" class="form-control">               
            <div class="form-group">
                <label for="first_name" class="text-xs">@lang('contact person')<span style="padding-left:5px;">(*)</span></label>
                <input type="text" id="first_name" name="first_name" class="form-control">                
            </div>
           
            <div class="form-group">
                <label for="mobile_no" class="text-xs">@lang('Phone Mobile')<span style="padding-left:5px;">(*)</span></label>
               <div class="contact">
                <input type="tel" id="mobile_no" name="mobile_no" class="form-control" style="width:425px;">
                 <span id="valid-msg" class="hide">
                    <img class="new mb-2" src="{{asset('assets/icons/check-circle-icon-original.svg')}}" width="25px" height="25px"/> 
                </span>
                 <span id="error-msg" class="hide"></span>
                </div>
                
            </div>
            <div class="form-group">
                <label for="email" class="text-xs">@lang('Email')</label>
                <input type="email" id="email" name="email" class="form-control">                
            </div>
            <div class="form-group">
                <label for="job_position" class="text-xs">@lang('Job Position')</label>                
                <select class="form-control" id="job_position" name="job_position">
                    @foreach($job_list as $key => $job)
                    <option value="{{$job->name}}">{{$job->name}}</option>
                    @endforeach
                </select>
            </div>
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
        $(function () {            
            table1 = $('.datatable-customer').DataTable({
                dom:'ftlp',
                pageLength: 100,
                scrollCollapse: true,
                scrollX:false,
                responsive: false,
                select: {
                    style: 'single' // or 'multi'
                }
            });
            
            table1.on('select', function(e, dt, type, indexes) {
                if(type === 'row') {
                    // console.log('Row '+indexes[0]+' selected');
                    var id = $(dt.row(indexes[0]).node()).data('id');
                    drawContactTable(id);
                    table1.draw(false);
                }
            });
            
            
            table2 = $('.datatable-contact').DataTable({
                dom:'ftlp',                
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

        function show(){
            $('.contactlist').show();
        }

        function drawContactTable(company_id) {
            $.ajax({
                method: 'GET',
                url: `{{route('admin.projects.get.contactlist')}}?id=` + company_id,
                // data: {id: company_id}
            }).done(function (res) { 
                
                contact_list = res.result;
                table2.rows().remove();
                for(var i=0;i<res.result.length;i++) {      
                     var contactdetail = res.result[i].id+'$$'+res.result[i].firstname+'$$'+res.result[i].mobile+'$$'+res.result[i].email+'$$'+res.result[i].job_position+'$$'+res.result[i].company_id;                      
                    var $tr = $(`<tr data-id="${res.result[i].id}" id='row_${i}' onclick=select_row(${i}); >\
                    <td  >${res.result[i].firstname}</td>\
                    
                    <td>${res.result[i].mobile}</td>\
                    <td>${res.result[i].email}</td>\
                    <td>${res.result[i].job_position}</td>\
                    <td class="nowrap act_btn">
                                   <a href="#!" onclick="updateContact('${res.result[i].id}','${contactdetail}','${res.result[i].company_id}')"><img class="new" src="{{asset('assets/icons/pencil-line-icon-original.svg')}}" width="25px" height="25px"/></a>
                                   <a href="#!" onclick="deleteContact('${res.result[i].id}','${res.result[i].company_id}')"><img class="new" src="{{asset('assets/icons/trash-icon-original.svg')}}" width="25px" height="25px"/></a>
                            
                               </td>\
                    </tr>`);                            
                    table2.row.add($tr);
                }
                table2.draw(false);
                if(temp_func !== null) {
                    temp_func();
                    temp_func = null;
                }

                
                
            });
        }

        $('.next').hide();
        $('.datatable-contact tbody ').on('click',function(){
            $('.next').show();
        })


        function goToDetail(contact_id,company_id) {
            var company_id = $(table1.row({selected: true}).node()).data('id');
            var contact_id = $(table2.row({selected: true}).node()).data('id');
            if(company_id === undefined || contact_id === undefined) {
                alert("@lang('You must select Company and Contact people')");
                return false;
            }
            location.href = `{{route('admin.projects.detail')}}/{{$pid}}/${company_id}/${contact_id}`;
        }

        function newContact() {
            // alert(company_id);
            var company_id = $(table1.row({selected: true}).node()).data('id');
            $(document).on("click", '.jsselected', function(){

            })
            if(company_id === undefined) {
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
function updateContact(cid,contactdetails,company_id) {
            
            var contact_id = $(table1.row({selected: true}).node()).data('id');
            // // if(contact_id === undefined) {
            // //     alert("@lang('You must select Contact people to update')");
            // //     return false;
            // // }
             $('#contactModal .modal-title').text("@lang('Edit Contact People')");
             var contact_user = contactdetails.split('$$');
            // let contact_user = contact_list.filter(c => c.id == contact_id)[0];            
            global_company_id = contact_user[5];
            console.log(cid,contact_user);
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
        if(!regex.test(email)) {
           return false;
        }else{
           return true;
        }
      }

const input = document.querySelector("#mobile_no");
const errorMsg = document.querySelector("#error-msg");
const validMsg = document.querySelector("#valid-msg");
// var warningIcon = '{{ asset("assets/icons/warning-icon-original.svg") }}';

// here, the index maps to the error code returned from getValidationError - see readme
 const errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];



// initialise plugin
const iti = window.intlTelInput(input, {
    initialCountry: "auto",
    geoIpLookup: function(callback) {
        fetch("https://ipapi.co/json")
            .then(function(res) { return res.json(); })
            .then(function(data) { callback(data.country_code); })
            .catch(function() { callback("us"); });
    },
    onlyCountries: ["AT","BE","BG","HR","CY","CZ","DK","EE","FI","FR","DE"
                    ,"GR","HU","IE","IT","LV","LT","LU","MT","NL","NO","PL","PT","RO","SK","SI","ES","SE","CH","GB"],
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
input.addEventListener('blur', () => {
	reset();
	if (input.value.trim()) {
		if (iti.isValidNumber()) {
			validMsg.classList.remove("hide");
            $('#hide').show();
            contact_validate = true;

		} else {
			input.classList.add("error");
			const errorCode = iti.getValidationError();
			errorMsg.innerHTML = errorMap[errorCode];
			errorMsg.classList.remove("hide");
            contact_validate = false;
            
            
		}
	}
});

// on keyup / change flag: reset
input.addEventListener('change', reset);
input.addEventListener('keyup', reset);
        function saveContact(thisatt) { 
    
            //alert(thisatt);
            //return false;
            var flag = true;
            //var company_id = $(table1.row({selected: true}).node()).data('id');
            //console.log(thisatt.text());
            var company_id = global_company_id;
            //var company_id = $("#cont_company_id").val();
           
            //alert(company_id);
            // var postData = {company_id: company_id};
           
       
            var email = $('#email').val();
            
            if(email != "" && IsEmail(email)==false){
                alert("Enter valid email");
                flag = false;
                return false;
            }

            if( contact_validate == false){
                alert("Enter valid number");
                flag = false;
                return false;
            }
        
            var postData = {company_id: company_id};

            $('#contactModal .modal-body input,#contactModal .modal-body select').each(function() {
                const _v = $(this).val().trim();
                
                      if(_v === "") {
                      alert("@lang('You must input all fields.')");
                      $(this).focus();
                      $(this).addClass('border-color-red')
                      flag = false;
                      return false;
                    } else {
                        $(this).removeClass('border-color-red')
                    }
                
                
                postData[$(this).attr('name')] = _v;
            });
            if(!flag)
                return false;
            
            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: `{{route('admin.projects.store.contact')}}`,
                data: postData
            }).done(function (res) {
                drawContactTable(postData.company_id);
                $("#phone").val("");
                $('.modal').modal('hide');
            });
        }

        function deleteContact(contact_id,company_id) {
            /*var company_id = $(table1.row({selected: true}).node()).data('id');
            var contact_id = $(table2.row({selected: true}).node()).data('id');
            if(contact_id === undefined) {
                alert("@lang('You must select Contact people to delete')");
                return false;
            }*/

            if(!confirm("@lang('Are you sure?')"))
                return false;

            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.projects.delete.contact')}}' + '/' + contact_id,
            }).done(function (res) { 
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
            var flag = true;
            var postData = {};
            $('#companyModal .modal-body input').each(function() {
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
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: `{{route('admin.projects.save.company')}}`,
                data: postData
            }).done(function (res) { 
                $('.modal').modal('hide');
                var editIcon = '{{ asset("assets/icons/pencil-line-icon-original.svg") }}';
                var deleteIcon = '{{ asset("assets/icons/trash-icon-original.svg") }}';
                var editAction = `<a href="#!" onclick="editCompany(${res.result.id}, '${res.result.company_detail}')"><img class="new" src="${editIcon}" width="25px" height="25px" /></a>`;
                var deleteAction = `<a href="#!" onclick="deleteCompany(${res.result.id}, '${res.result.company_detail}')"><img class="new" src="${deleteIcon}" width="25px" height="25px" /></a>`;
                var finalAction = editAction+" "+deleteAction;
                let data = [postData.company_name,postData.company_address,postData.company_phone,postData.company_VAT,postData.company_desc, finalAction];
                 //location.reload();
                if(postData.company_id > 0) {
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

        }

        function deleteCompany(company_id) {
            // var company_id = $(table1.row({selected: true}).node()).data('id');
            // if(company_id === undefined) {
            //     alert("@lang('You must select company to delete')");
            //     return false;
            // }

            if(!confirm("@lang('Are you sure?')"))
                return false;

            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.projects.delete.company')}}',
                data: {id: company_id}
            }).done(function (res) { 
                if(res.result == true) {
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

        $(document).ready(function(){            
            _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            <?php
            if($cid > 0 && $uid > 0) {
                ?>
            //table1.row('[data-id={{$cid}}]').select();
            temp_func = function() {
                //table2.row('[data-id={{$uid}}]').select();                
            }
            <?php } else { ?>
            var firstRowIndex = table1.rows({ order: 'current' }).indexes()[0];
            table1.row(firstRowIndex).select();
            <?php } ?>
            table1.draw(false);
            table2.draw(false);

            $('#keyword_customer').on('keyup', function(e) {
                if(e.keyCode === 13)
                    searchCustomer();
            })
        });
        
        $('.firstrow').hide();

    </script>

    @endsection