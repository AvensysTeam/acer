@extends('layouts.admin')
@section('content')

<div class="main-card">
    <div class="body">
        <div class="row">
            @can('utilities_sale_create')
            <div class="col-sm-12 mb-2">
                <div class="add-new text-right flex justify-end">
                    <a class="btn" href='{{ route("admin.utilities.trashdata",["modelname" => $model])}}'>
                        <img class="new mb-2" src="{{asset('assets/icons/trash-icon-original.svg')}}" width="30px" height="30px"/>
                    </a>
                    <a class="btn" id="addNew">
                        <img class="ne" src="{{ asset('assets/icons/plus-circle-icon-original.svg') }}" width="30px" height="30px" />
                    </a>
                </div>
            </div>
            @endcan
        </div>

        <div class="w-full table-responsive">
            <table class="display table compact project-table datatable-project">
                <thead>
                    <tr>
                        <th>@lang('Title')</th>
                        <th>@lang('Link')</th>
                        <th>@lang('Parent')</th>
                        <th>@lang('Users')</th>
                        @canany(['utilities_sale_edit', 'utilities_sale_delete']) <th>Action</th> @endcan
                    </tr>
                </thead>
                <tbody>
                    @if($utilities_sale->count())
                    @foreach ($utilities_sale as $tool)
                        @php
                        $userArr = $tool->saleUserPermission->pluck('user.name')->toArray();
                        if($userArr && count($userArr) > 3){
                            $userNames = implode(', ', collect($userArr)->take(3)->toArray());
                            $userNames .= '...more';
                        }else{
                            $userNames = implode(', ', $userArr) ?? '-';
                        }
                        @endphp
                        <tr>
                            <td class="nowrap">{{$tool->title}}</td>
                            <td class="nowrap">{{$tool->link ?? '-'}}</td>
                            <td class="nowrap">{{$tool->parent_folder_id ? (collect($parentUtilities)->where('id', $tool->parent_folder_id)->first()['title'] ?? '-') : '-'}}</td>
                            <td class="nowrap"> {{ $userNames }}</td>
                            @canany(['utilities_sale_edit', 'utilities_sale_delete']) 
                            <td class="action_btn" style="display: flex;">
                                @can('utilities_sale_edit')
                                    <a href="javascript:void(0);" data-id="{{ $tool->id }}" class="btnEdit">
                                        <img class="new mb-2" src="{{asset('assets/icons/pencil-line-icon-original.svg')}}" width="25px" height="25px" />
                                    </a>
                                @endcan
                                @can('utilities_sale_delete')
                                    <a href="javascript:void(0);" data-id="{{ $tool->id }}" class="btnDelete">
                                        <img class="new mb-2" src="{{asset('assets/icons/trash-icon-original.svg')}}" width="25px" height="25px"/>
                                    </a>                                    
                                @endcan
                            </td>
                            @endcan
                        </tr>
                    @endforeach
                    @else
                    <tr>
                        @canany(['utilities_sale_edit', 'utilities_sale_delete']) 
                            <td colspan="5" class="text-center">No data found.</td>
                        @elsecan
                            <td colspan="4" class="text-center">No data found.</td>
                        @endcan
                    </tr>
                    @endif
                </tbody>
            </table>
            
            <div class="next_previous">
                @if($utilities_sale->previousPageUrl())
                <a href="{{ $utilities_sale->previousPageUrl() }}">
                    <button id="customPreviousBtn" style="rotate: -90deg;">
                        <img class="new mb-2" src="{{asset('assets/icons/arrow-circle-up-icon-original.svg')}}" width="35px" height="35px" />
                    </button>
                </a>
                @endif
                @if($utilities_sale->nextPageUrl()) 
                <a href="{{ $utilities_sale->nextPageUrl() }}">
                    <button id="customNextBtn" style="margin-top:8px;">
                        <img class="new mb-2" src="{{asset('assets/icons/nextArrow.png')}}" width="35px" height="35px" />
                    </button>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="utilityModal" tabindex="-1" role="dialog" aria-labelledby="utilityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="utilityModalLabel">Create Utility</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="utilityForm">
                    <div class="row">
                        <input type="hidden" id="model" value="{{$model}}" name="model" />
                        <div class="col-md-12 form-group">
                            <label for="title" class="form-label required">Title</label>
                            <input type="text" class="form-control" name="title" id="title">
                            <div class="validationAlert" id="title_alert"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="is_folder" class="form-label">Is Folder</label>
                            <input type="checkbox" class="form-control" name="is_folder" id="is_folder">
                            <div class="validationAlert" id="is_folder_alert"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="parent" class="form-label">Parent</label>
                            <select class="form-control" name="parent" id="parent">
                                <option value="">Select Parent</option>
                                @foreach($parentUtilities as $pArr)
                                    <option value="{{ $pArr['id'] }}" data-category="{{ $pArr['title'] }}">{{ $pArr['title'] }}</option>
                                @endforeach
                            </select>
                            <div class="validationAlert" id="parent_alert"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="link" class="form-label">Link</label>
                            <input type="text" class="form-control" name="link" id="link">
                            <div class="validationAlert" id="link_alert"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="file" class="form-label">File</label>
                            <input type="file" class="form-control" name="file" id="file">
                            <div class="validationAlert" id="file_alert"></div>
                        </div>
                        <div id="user-form-control" class="col-md-12 form-group">
                            <label for="user" class="form-label">Users</label>
                            <select class="form-control select2" name="users[]" id="user" placeholder="Select User" multiple>
                                <option value="all" class="chosen-toggle select">All</option>    
                                <!-- <option value="alladmin" class="chosen-toggle select">All admin</option>  -->
                                @foreach($users as $userId => $name)
                                    <option value="{{ $userId }}" >{{ $name }}</option>
                                @endforeach
                            </select>
                            <div class="validationAlert" id="user_alert"></div>
                        </div>
                        <div class="col-md-12 text-right">
                            <input type="hidden" class="form-control" name="id" id="id">
                            <button type="submit" class="btn btn-primary" id="btnSubmit">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function() {
        let modal = $('#utilityModal')
        let _token = "{{ csrf_token() }}"

        $('.close').click(function() {
            modal.modal('hide')
        });

        $('#user').on('change', function() {
            var selectedValues = $(this).val();
            
            if (selectedValues.includes('all')) {
                // Select all options except "All"
                $(this).find('option').prop('selected', true);
                $(this).find('option[value="all"]').prop('selected', false);
                $(this).trigger('change.select2');
            } else {
                // Ensure "All" option is not selected
                $(this).find('option[value="all"]').prop('selected', false);
                $(this).find('option[value="alladmin"]').prop('selected', false);
                $(this).trigger('change.select2');
            }

            // if (selectedValues.includes('alladmin')) {
            //     // Select all options except "All"
            //     $(this).find('option').prop('selected', true);
            //     $(this).find('option[value="all"]').prop('selected', false);
            //     $(this).find('option[value="alladmin"]').prop('selected', false);
            //     $(this).trigger('change.select2');
            // } else {
            //     // Ensure "All" option is not selected
            //     $(this).find('option[value="alladmin"]').prop('selected', false);
            //     $(this).find('option[value="all"]').prop('selected', false);
            //     $(this).trigger('change.select2');
            // }
            

            // Enable or disable the submit button based on selection
            if (selectedValues.length > 0) {
                $('#btnSubmit').prop('disabled', false);
            } else {
                $('#btnSubmit').prop('disabled', true);
            }
        });

        // Initial check to disable the button if no options are selected
        if ($('#user').val().length === 0) {
            $('#btnSubmit').prop('disabled', true);
        }
        $('#addNew').click(function() {
            modal.modal('show')
            modal.find('#utilityModalLabel').text('Create Utility')
            modal.find('#btnSubmit').text('Create')
        })

        $('.form-control[name=is_folder]').change(function(e){
            console.log("$(this).is() === 1", $(this).is(":checked"));
            if($(this).is(":checked")) {
                $("label[for=link]").removeClass("required");
                $('#link').val("").attr("disabled", true);
                $('#file').val("").attr("disabled", true);
                $('#btnSubmit').prop('disabled', false);
                $('#user-form-control').hide();
            } else {
                $("label[for=link]").addClass("required")
                $('#link').val("").attr("disabled", false);
                $('#file').val("").attr("disabled", false);
                $('#btnSubmit').prop('disabled', true);
                $('#user-form-control').show();
            }
        })
        $('.form-control[name=file]').change(function(e){
            console.log("$(this).is() === 1", $(this).is(":checked"));
            if($(this).val()) {
                $("label[for=link]").removeClass("required");
                $('#link').val("").attr("disabled", true);
            } else {
                $("label[for=link]").addClass("required")
                $('#link').val("").attr("disabled", false);
            }
        })
        $('#btnSubmit').prop('disabled', true);
        
        modal.on('hidden.bs.modal', function (e) {
            $('#utilityForm')[0].reset()
            modal.find(`.validationAlert`).empty().hide()
            $('#parent').find('option').show()
            $('#user').val(null).change()
        })

        $('#parent').change(function() {
            let val = $(this).val();
            if(val){
                modal.find('#category').attr('readonly', true)
                let cate = $(this).find(`option[value="${val}"]`).data('category')
                modal.find('#category').val(cate).change()
            }else{
                modal.find('#category').attr('readonly', false)
            }
        })

        $('#utilityForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            // Add users to formData if they are selected
            let users = $(this).find('#user').val();
            if (users) {
                users.forEach((userId, index) => {
                    formData.append(`users[${index}]`, userId);
                });
            }

            let id = formData.get('id');

            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: id ? `/admin/utilities/sale/update/${id}` : '{{ route("admin.utilities.sale.create") }}',
                data: formData,
                processData: false,  // tell jQuery not to process the data
                contentType: false   // tell jQuery not to set contentType
            }).done(function (res) { 
                $('#utilityModal').modal('hide');
                window.location.reload(true);
            }).catch(function(e) {
                if (e?.responseJSON?.result) alert(e?.responseJSON?.result);
                let errors = e?.responseJSON?.errors;
                if (errors && Object.keys(errors)?.length) {
                    Object.keys(errors).forEach(key => {
                        $(`#${key}_alert`).text(errors[key][0]).show();
                    });
                }
            });
        });


        $('.btnEdit').click(function() {
            let id = $(this).data('id');
            let modelName = $('#model').val();
            if(id){
                $.ajax({
                    method: 'GET',
                    headers: {'x-csrf-token': _token},
                    url: `/admin/utilities/sale/show/${id}`,
                    data: {modelname: modelName},
                }).done(function (res) {
                    let dt = res?.result
                    if(dt && Object?.keys(dt)?.length){
                        modal.find('#utilityModalLabel').text('Update Utility')
                        modal.find('#btnSubmit').text('Update')
                        $('#parent').find('option').show()
                        Object?.keys(dt)?.map(key => {
                            if(key === 'parent_folder_id' && id){
                                $('#parent').find(`option[value="${id}"]`).hide()
                                $('#parent').val(dt[key]).change()
                            }
                            if(key === 'sale_user_permission' && dt[key] && dt[key]?.length){
                                let userIds = dt[key].map(r => r.user_id)
                                if(userIds?.length) $('#user').val(userIds).change()
                            }
                            modal.find(`#${key}`).val(dt[key])
                        })
                        modal.modal('show');
                    }
                }).catch(function(e){
                    if(e?.responseJSON?.result) alert(e?.responseJSON?.result)
                });
            }
            return false;
        })

        $('.btnDelete').click(function() {
            let id = $(this).data('id');
            let modelName = $('#model').val();
            if (id && modelName) {
                if (confirm('Are you sure you want to delete this record?')) {
                    $.ajax({
                        method: 'DELETE',
                        headers: {'x-csrf-token': _token},
                        url: `/admin/utilities/sale/delete/${id}`,
                        data: {modelname: modelName},
                    }).done(function (res) { 
                        modal.modal('hide');
                        window.location.reload(true);
                    }).catch(function(e){
                        if(e?.responseJSON?.result) alert(e?.responseJSON?.result);
                    });
                }
            }
            return false;
        });
    })
</script>
@endsection
