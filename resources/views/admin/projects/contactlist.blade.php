@extends('layouts.admin')
@section('content')
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min.js"></script>
<style type="text/css">
        .act_btn a{float: left;}
    </style>
<div class="row">
    <!-- <div class="col-md-12">
        <div class="main-card">
            <div class="header">
                @lang('Company selection')
            </div>
            <div class="body">
                <div class="w-full">
                <div class="">
                                <a class="btn " onclick="newCompany()">
                                <img class="new mb-2" src="{{asset('assets/icons/plus-circle-icon-original.svg')}}" width="25px" height="25px"/>
                                </a>
                                
                            </div> -->
                   
    <div class="col-md-12">
        <div class="main-card">
            <div class="header">
                @lang('Contact list')
                <div>
                <a href="#!" onclick="newContact()"><img class="new"
                    src="{{ asset('assets/icons/users-add-profile-avatar-user-account-svgrepo-com.svg') }}" width="25px"/></a>
                </div>
            </div>
            <div class="body">
                <div class="w-full">
                    <table class="display compact project-table datatable-contact" style="width:100%;">
                        <thead>
                            <tr>
                                <th>@lang('First Name')</th>
                                <!-- <th>Full name</th>
                                <th>@lang('Last Name')</th> -->
                                <!-- <th>@lang('Tel. No.')</th> -->
                                <th>@lang('Phone/Mobile')</th>
                                <th width="30%">@lang('Email')</th>
                                <th>@lang('Job Position')</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contact as $contact_list)

                            @php $contactdetail = $contact_list->id.'$$'.$contact_list->firstname.'$$'.$contact_list->phone.'$$'.$contact_list->mobile.'$$'.$contact_list->email.'$$'.$contact_list->job_position.'$$'.$contact_list->company_id; @endphp
                            <tr>
                                <td>{{$contact_list->firstname}}</td>
                                <!-- <td>{{$contact_list->phone}}</td> -->
                                <td>{{$contact_list->mobile}}</td>
                                <td width="30%">{{$contact_list->email}}</td>
                                <td>{{$contact_list->job_position}}</td>
                                <td class="act_btn">
                                   <a href="#!" onclick="updateContact('{{$contact_list->id}}','<?php echo $contactdetail; ?>')"><img class="new mb-2" src="{{asset('assets/icons/pencil-line-icon-original.svg')}}" width="25px" height="25px"/></a>
                                   <a href="#!" onclick="deleteContact('{{$contact_list->id}}')"><img class="new mb-2" src="{{asset('assets/icons/trash-icon-original.svg')}}" width="25px" height="25px"/></a>
                                   <a href="#!" onclick="goToDetail('{{$contact_list->id}}','{{$contact_list->company_id}}')">NEXT</a>
                               </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="companyModal" tabindex="-1" role="dialog">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">@lang('Company')</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <input type="hidden" id="company_id" name="company_id" class="form-control">                
            <div class="form-group">
                <label for="company_name" class="text-xs">@lang('Corporate name')</label>
                <input type="text" id="company_name" name="company_name" class="form-control">                
            </div>
            <div class="form-group">
                <label for="company_address" class="text-xs">@lang('Address')</label>
                <input type="text" id="company_address" name="company_address" class="form-control" value="NA">                
            </div>
            <div class="form-group">
                <label for="company_phone" class="text-xs">@lang('Tel. No.')</label>
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
            <input type="hidden" id="contact_id" name="conta" class="form-control">                
            <div class="form-group">
                <label for="first_name" class="text-xs">@lang('full name')</label>
                <input type="text" id="first_name" name="first_name" class="form-control">                
            </div>
            <div class="form-group">
                <!-- <label for="last_name" class="text-xs">@lang('Last Name')</label> -->
                <input type="hidden" id="last_name" name="last_name" class="form-control" value="NA">                
            </div>
            <div class="form-group">
                <!-- <label for="tel_no" class="text-xs">@lang('Tel. No.')</label> -->
                <input type="hidden" id="tel_no" name="tel_no" class="form-control" value="0">                
            </div>
            <div class="form-group">
                <label for="mobile_no" class="text-xs">@lang('Phone/Mobile')</label>
                <input type="text" id="mobile_no" name="mobile_no" class="form-control">                
            </div>
            <div class="form-group">
                <label for="email" class="text-xs">@lang('Email')</label>
                <input type="text" id="email" name="email" class="form-control">                
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
            <button type="button" class="btn btn-primary" onclick="saveContact()">@lang('Save')</button>
         </div>
      </div>
   </div>
</div>
@endsection
@section('scripts')
    @parent
    <script>
        var table1, table2;
        var contact_list = null;
        var temp_func = null;
        var _token = null;
        $(function () {            
            table1 = $('.datatable-customer').DataTable({
                dom:'ftlp',
                pageLength: 10,
                scrollCollapse: true,
                scrollX:false,
                responsive: true,
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
                              
                pageLength: 10,
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

        function drawContactTable(company_id) {
            $.ajax({
                method: 'GET',
                // url: '{{route('admin.projects.get.contactlist')}}',
                url: '{{route('admin.projects.get.contactlist')}}',
                data: {id: company_id}
            }).done(function (res) { 
                // console.log(res);
                contact_list = res.result;
                table2.rows().remove();
                for(var i=0;i<res.result.length;i++) {                            
                    var $tr = $(`<tr data-id="${res.result[i].id}">\
                    <td>${res.result[i].firstname}</td>\
                    
                    <td>${res.result[i].phone}</td>\
                    <td>${res.result[i].mobile}</td>\
                    <td>${res.result[i].email}</td>\
                    <td>${res.result[i].job_position}</td>\
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

        function goToDetail(contact_id,company_id) {
            var pid = "{{ Session::get('pid') }}";
           
            // var company_id = $(table1.row({selected: true}).node()).data('id');
            // var contact_id = $(table2.row({selected: true}).node()).data('id');
            // if(company_id === undefined || contact_id === undefined) {
            //     alert("@lang('You must select Company and Contact people')");
            //     return false;
            // }
            //location.href = `{{route('admin.projects.detail')}}/{{$pid}}/${company_id}/${contact_id}`;
            location.href = `{{route('admin.projects.detail')}}/${pid}/${company_id}/${contact_id}`;
        }

        function newContact() {
            // var company_id = $(table1.row({selected: true}).node()).data('id');
            // if(company_id === undefined) {
            //     alert("@lang('You must select Company first')");
            //     return false;
            // }
            $('#contactModal .modal-title').text("@lang('Create Contact People')");
            $('#contactModal #contact_id').val(0);
            $('#contactModal #first_name').val("");
            $('#contactModal #last_name').val("");
            $('#contactModal #tel_no').val("");
            $('#contactModal #mobile_no').val("");
            $('#contactModal #email').val("");
            $('#contactModal #job_position').val("");
            $('#contactModal').modal('show');
        }

        function updateContact(cid,contactdetails) {
            
            var contact_id = $(table1.row({selected: true}).node()).data('id');
            // // if(contact_id === undefined) {
            // //     alert("@lang('You must select Contact people to update')");
            // //     return false;
            // // }
             $('#contactModal .modal-title').text("@lang('Edit Contact People')");
             var contact_user = contactdetails.split('$$');
            // let contact_user = contact_list.filter(c => c.id == contact_id)[0];            
            console.log(cid,contact_user);
            $('#contactModal #contact_id').val(contact_user[0]);
            $('#contactModal #first_name').val(contact_user[1]);
            // $('#contactModal #last_name').val(contact_usersecondname);
            $('#contactModal #tel_no').val(contact_user[2]);
            $('#contactModal #mobile_no').val(contact_user[3]);
            $('#contactModal #email').val(contact_user[4]);
            $('#contactModal #job_position').val(contact_user[5]);
            $('#contactModal').modal('show');
        }

        function saveContact(contactdetails) {
            
            var flag = true;
            //var company_id = $(table1.row({selected: true}).node()).data('id');
            var company_id = "{{$id}}";
            // alert(company_id);
            // var postData = {company_id: company_id};
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
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.projects.store.contact')}}',
                data: postData
            }).done(function (res) { 
                //drawContactTable(company_id);
                location.reload();
                $('.modal').modal('hide');
            });
        }

       

        function deleteContact(id) {
            
            // var company_id = $(table1.row({selected: true}).node()).data('id');
            // var contact_id = $(table2.row({selected: true}).node()).data('id');
            // if(contact_id === undefined) {
            //     alert("@lang('You must select Contact people to delete')");
            //     return false;
            // }
            var contact_id = id;
            if(!confirm("@lang('Are you sure?')"))
                return false;

            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.projects.delete.contact')}}' + '/' + contact_id,
            }).done(function (res) { 
              //  drawContactTable(company_id);
              location.reload();
            });
        }

        function newCompany() {
            $('#companyModal .modal-title').text("@lang('Create Company')");
            $('#companyModal #company_id').val(0);
            $('#companyModal #company_name').val("");
            $('#companyModal #company_address').val("");
            $('#companyModal #company_phone').val("");
            $('#companyModal #company_VAT').val("");
            $('#companyModal #company_desc').val("");
            $('#companyModal').modal('show');
        }

        function editCompany() {
            var company_id = $(table1.row({selected: true}).node()).data('id');

            if(company_id === undefined) {
                alert("@lang('You must select company to update')");
                return false;
            }
            $('#companyModal .modal-title').text("@lang('Edit Company')");

            var company_data = table1.row({selected: false}).data();            
             alert(company_data);
            $('#companyModal #company_id').val(company_id);
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
            console.log(postData);
            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: '{{route('admin.projects.save.company')}}',
                data: postData
            }).done(function (res) { 
                $('.modal').modal('hide');
                let data = [postData.company_name,postData.company_address,postData.company_phone,postData.company_VAT,postData.company_desc];
                if(postData.company_id > 0) {
                    table1.row({selected:true}).data(data).draw(false);
                } else {
                    var new_tr = $('<tr data-id="' + res.result.id + '">' + data.map((item) => '<td>' + item + '</td>').join("") + '<tr>');
                    table1.row.add(new_tr).draw(false);
                }
            });
        }

        function deleteCompany() {
            var company_id = $(table1.row({selected: true}).node()).data('id');
            if(company_id === undefined) {
                alert("@lang('You must select company to delete')");
                return false;
            }

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
            table1.row('[data-id={{$cid}}]').select();
            temp_func = function() {
                table2.row('[data-id={{$uid}}]').select();                
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
    </script>
@endsection