@extends('layouts.admin')
@section('content')
<style>
    .icon{
        display:flex;
        justify-content: space-between;
    }
</style>
<div class="main-card">
    <div class="header">
        {{ trans('global.edit') }} Language
    </div>

    <form method="POST" action="{{ route('admin.language.update', [$language->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="body">
            <div class="mb-3">
                <label for="name" class="text-xs required">Language Name</label>

                <div class="form-group">
                    <input type="text" id="name" name="name" class="{{ $errors->has('name') ? ' is-invalid' : '' }}" value="{{ old('name', $language->name) }}" required>
                </div>
                @if($errors->has('name'))
                    <p class="invalid-feedback">{{ $errors->first('name') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricetype.fields.name_helper') }}</span>
            </div>

            <div class="mb-3">
                <label for="code" class="text-xs required">Language Code</label>

                <div class="form-group">
                    <input type="text" id="code" name="code" class="{{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ old('code', $language->code) }}" required>
                </div>

                <div class="form-group icon">
                    <input type="file" id="country-flag" name="country-flag" class="{{ $errors->has('code') ? ' is-invalid' : '' }}" value="{{ old('country_flag', $language->country_flag) }}" >
                    <img src="{{ asset('img/country-flag/'.$language->country_flag ) }}" width="100px" />
                </div>
               
                @if($errors->has('code'))
                    <p class="invalid-feedback">{{ $errors->first('code') }}</p>
                @endif
                <span class="block">{{ trans('cruds.pricetype.fields.name_helper') }}</span>
            </div>

            <div>
                <input type="hidden" value="{{ old('is_default', $language->is_default) }}" name="is_default" id="is_default" />
            </div>
        </div>

        <div class="footer">
        <a href="javascript:history.go(-1);" class="btn btn-default mr-3">{{ trans('global.back_to_list') }}</a>
            <button type="submit" class="submit-button">{{ trans('global.save') }}</button>
        </div>
    </form>
</div>
@endsection