@extends('layouts.admin')
@section('content')
@can('scooter_create')
    <div class="block my-4">
        <a class="btn-md btn-green" href="{{ route('admin.pricetype.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.pricetype.title_singular') }}
        </a>
    </div>
@endcan
<div class="main-card">
    <div class="header">
        {{ trans('cruds.pricetype.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="body">
        <div class="w-full table-responsive">
            <table class="stripe hover bordered datatable datatable-Scooter">
                <thead>
                    <tr>
                        <th>
                            {{ trans('cruds.pricetype.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.pricetype.fields.name') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pricetypes as $key => $pricetype)
                        <tr data-entry-id="{{ $pricetype->id }}">
                            <td align="center">
                                {{ $pricetype->id ?? '' }}
                            </td>
                            <td align="center">
                                {{ $pricetype->name ?? '' }}
                            </td>
                            <td align="center">
                                @can('scooter_show')
                                    <a class="btn-sm btn-indigo" href="{{ route('admin.pricetype.show', $pricetype->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @can('scooter_edit')
                                    <a class="btn-sm btn-blue" href="{{ route('admin.pricetype.edit', $pricetype->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @can('scooter_delete')
                                    <form action="{{ route('admin.pricetype.destroy', $pricetype->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn-sm btn-red" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function () {
        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 0, 'asc' ]],
            pageLength: 10,
        });
        let table = $('.datatable-Scooter:not(.ajaxTable)').DataTable()
        
        
        $(".import_btn").click(function (e) {
            e.preventDefault();
            $('#selectedFile').click();
        });
        $("#selectedFile").change(function () {
            if (confirm('Are you sure ?')) {
                $("#import_form").submit()
            }
        });
    });

</script>
@endsection