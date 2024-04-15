@extends('layouts.admin')
@section('content')
<div class="main-card">
    <div class="header">
        {{ trans('global.edit') }} {{ trans('cruds.role.title_singular') }}
    </div>

    <form method="POST" action="{{ route("admin.roles.update", [$role->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="title" class="text-xs required">{{ trans('cruds.role.fields.title') }}</label>

                <div class="form-group">
                    <input type="text" id="title" name="title" class="{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title', $role->title) }}" required>
                </div>
                @if($errors->has('title'))
                    <p class="invalid-feedback">{{ $errors->first('title') }}</p>
                @endif
                <span class="block">{{ trans('cruds.role.fields.title_helper') }}</span>
            </div>
            <div class="mb-3">
                <label class="text-xs required" for="permissions">{{ trans('cruds.role.fields.permissions') }}</label>
                <div style="padding-bottom: 4px">
                    <div class="row">
                        <div class="col-md-6">
                            <span class="btn-sm btn-indigo chk-select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn-sm btn-indigo chk-deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <div class="col-md-6">
                            <div class="customer-dropdown">
                                <select class="form-control" id="custPermission">
                                    <option value="">Select customer</option>
                                    @foreach($customers as $custId => $custName)
                                    <option value="{{ $custId }}">{{ $custName }}</option>
                                    @endforeach
                                </select>
                                <span class="btn-sm btn-indigo" id="btnReset" style="border-radius: 0">Reset</span>
                            </div>
                        </div>
                    </div>                    
                </div>
                @include('admin.roles.permissionGroup', ['isView' => 0])
                @if($errors->has('permissions'))
                    <p class="invalid-feedback">{{ $errors->first('permissions') }}</p>
                @endif
                <span class="block">{{ trans('cruds.role.fields.permissions_helper') }}</span>
            </div>
        </div>

        <div class="footer">
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection
@section('scripts')
@parent
<style>
    .customer-dropdown {
        width: 35%;
        float: inline-end;
        display: flex;
        align-items: flex-start;
    }

    .customer-dropdown select {
        margin-right: 5px;
    }
</style>
<script>
    $(document).ready(function() {
        $('.chk-select-all').click(function() {
            $('.permissionChk').attr('checked', true)
        })

        $('.chk-deselect-all').click(function() {
            $('.permissionChk').attr('checked', false)
        })

        $('#btnReset').click(function(){
            let customer = $('#custPermission').val()
            if(customer) window.location.reload(true)
        })

        $('#custPermission').on('change', function() {
            let customer = $(this).val()
            
            if(customer){
                $.ajax({
                    method: 'GET',
                    headers: {'x-csrf-token': "{{ csrf_token() }}"},
                    url: '/admin/user/permission/' + customer
                }).done(function (res) { 
                    if(res?.success){
                        $('.permissionChk').each(function() {
                            let val = parseInt($(this).val());
                            if(val && res?.permissions?.includes(val)){
                                $(this).prop('checked', true)
                            }else{
                                $(this).prop('checked', false)
                            }
                        });
                    }
                }).catch(function(e){
                    if(e?.responseJSON?.message) alert(e?.responseJSON?.message)
                });
            }else{
                window.location.reload(true)
            }
        })
    })
</script>
@endsection