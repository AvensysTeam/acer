@extends('layouts.admin')
@section('content')
@can('role_create')
    <div class="block my-4">
        <a class="btn-md btn-success" href="{{ route('admin.roles.create') }}">
            <i class="fa fa-plus"></i> {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
        </a>
    </div>
@endcan
<div class="main-card">
    <div class="header">
        {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="body">
        <div class="w-full table-responsive">
            <form action="{{ route('admin.roles.selectDestroy') }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn-sm btn-red mb-1">Delete selected user</button>
            <table class="table stripe hover bordered datatable datatable-Role">
                <thead class="thead-dark">
                    <tr>
                        <th>
                            <input type="checkbox" id="select-all">
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.title') }}
                        </th>
                        <th>
                            {{ trans('cruds.role.fields.permissions') }}
                        </th>
                        <th>
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $key => $role)
                        <tr data-entry-id="{{ $role->id }}">
                            <td><input type="checkbox" name="ids[]" value="{{ $role->id }}"></td>
                            <td align="center">
                                {{ $role->id ?? '' }}
                            </td>
                            <td align="center">
                                {{ $role->title ?? '' }}
                            </td>
                            <td align="center">
                                <!-- @include('admin.roles.permissionGroup', ['isView' => 1]) -->
                                @foreach($role->permissions as $key => $item)
                                    <span class="badge blue">{{ $item->display }}</span>
                                @endforeach
                            </td>
                            <td align="center">
                                @can('role_show')
                                    <a class="btn-sm btn-primary" href="{{ route('admin.roles.show', $role->id) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                @endcan

                                @can('role_edit')
                                    <a class="btn-sm btn-info" href="{{ route('admin.roles.edit', $role->id) }}">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                @endcan
<!-- 
                                @can('role_delete')
                                    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn-sm btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan -->

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </form>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('role_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.roles.massDestroy') }}",
    className: 'btn-red',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Role:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})
    $(function () {
    // Handle 'Select All' checkbox click event
        $('#select-all').click(function(){
            $('input[type="checkbox"]').prop('checked', this.checked);
        });

        // Handle individual checkbox click event
        $('input[type="checkbox"]').click(function(){
            if (!this.checked) {
                $('#select-all').prop('checked', false);
            }
        });
    });

</script>
@endsection