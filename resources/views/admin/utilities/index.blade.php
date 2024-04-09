@extends('layouts.admin')
@section('content')

<div class="main-card">
    <div class="body">
        <div class="row">
            @can('utilities_sale_create')
            <div class="col-sm-12 mb-2">
                <div class="add-new text-right flex justify-end">
                    <a class="btn" id="addNew">
                        <img class="ne" src="{{ asset('assets/icons/plus-circle-icon-original.svg') }}" width="30px" height="30px" />
                    </a>
                </div>
            </div>
            @endcan
        </div>

        <div class="w-full">
            <table class="display table compact project-table datatable-project">
                <thead>
                    <tr>
                        <th>@lang('Title')</th>
                        <th>@lang('Link')</th>
                        <th>@lang('Parent')</th>
                        <th>@lang('Role')</th>
                        @canany(['utilities_sale_edit', 'utilities_sale_delete']) <th>Action</th> @endcan
                    </tr>
                </thead>
                <tbody>
                    @if($utilities_sale->count())
                    @foreach ($utilities_sale as $tool)
                        <tr>
                            <td class="nowrap">{{$tool->title}}</td>
                            <td class="nowrap">{{$tool->link ?? '-'}}</td>
                            <td class="nowrap">{{$tool->parent_folder_id ? (collect($parentUtilities)->where('id', $tool->parent_folder_id)->first()['title'] ?? '-') : '-'}}</td>
                            <td class="nowrap"> {{ $tool->salePermissionRole->pluck('role.title')->implode(', ') ?? '-' }}</td>
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
                        <div id="role-form-control" class="col-md-12 form-group">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control select2" name="roles[]" id="role" placeholder="Select Role" multiple>
                                @foreach($roles as $roleId => $name)
                                    <option value="{{ $roleId }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <div class="validationAlert" id="role_alert"></div>
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
                $('#role-form-control').hide();
            } else {
                $("label[for=link]").addClass("required")
                $('#link').val("").attr("disabled", false);
                $('#role-form-control').show();
            }
        })

        modal.on('hidden.bs.modal', function (e) {
            $('#utilityForm')[0].reset()
            modal.find(`.validationAlert`).empty().hide()
            $('#parent').find('option').show()
            $('#role').val(null).change()
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
            let inputs = {};
            let serialize =  $(this).serializeArray();
            serialize.forEach(field => {
                if(field.name === "is_folder") 
                    inputs[field.name] = field.value === "on" ? 1 : 0;
                else
                    inputs[field.name] = field.value;
            })
            if($(this).find('#role').val()?.length){
                inputs['roles'] = []
                $(this).find('#role').val()?.map((v, i) => {
                    inputs['roles'][i] = v 
                })
            }
            let id = inputs?.id;

            $.ajax({
                method: 'POST',
                headers: {'x-csrf-token': _token},
                url: id ? `/admin/utilities/sale/update/${id}` : '{{ route("admin.utilities.sale.create") }}',
                data: inputs
            }).done(function (res) { 
                modal.modal('hide')
                window.location.reload(true)
            }).catch(function(e){
                if(e?.responseJSON?.result) alert(e?.responseJSON?.result)
                let errors = e?.responseJSON?.errors;
                if(errors && Object.keys(errors)?.length){
                    Object?.keys(errors).map(key => {
                        modal.find(`#${key}_alert`).text(errors[key][0]).show()
                    })
                }
            });
        })

        $('.btnEdit').click(function() {
            let id = $(this).data('id')
            if(id){
                $.ajax({
                    method: 'GET',
                    headers: {'x-csrf-token': _token},
                    url: `/admin/utilities/sale/show/${id}`,
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
                            if(key === 'sale_permission_role' && dt[key] && dt[key]?.length){
                                let rolesIds = dt[key].map(r => r.role_id)
                                if(rolesIds?.length) $('#role').val(rolesIds).change()
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
            let id = $(this).data('id')
            if(id){
                if(confirm('Are you are want to delete this record?')){
                    $.ajax({
                        method: 'DELETE',
                        headers: {'x-csrf-token': _token},
                        url: `/admin/utilities/sale/delete/${id}`,
                    }).done(function (res) { 
                        modal.modal('hide')
                        window.location.reload(true)
                    }).catch(function(e){
                        if(e?.responseJSON?.result) alert(e?.responseJSON?.result)
                    });
                }
            }
            return false;
        })
    })
</script>
@endsection
