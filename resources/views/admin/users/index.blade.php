


@extends('layouts.admin')
@section('content')
@can('user_create')
    <div class="block my-4">
        <a class="btn-md btn-green" href="{{ route('admin.users.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
        </a>
    </div>
@endcan
<div class="main-card">
    <div class="header">
        {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="body">
        <div class="w-full table-responsive">

            @if (session('success'))
                <div class="alert success">
                    {{ session('success') }}
                </div>
            @endif
            
            <form action="{{ route('admin.users.selectDestroy') }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                @method('DELETE')
                @csrf
                <button type="submit" class="btn-sm btn-red mb-1">Delete selected user</button>
                <table class="stripe hover bordered datatable datatable-User" style="overflow-x: scroll; display: block;">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>{{ trans('cruds.user.fields.id') }}</th>
                            <th>{{ trans('cruds.user.fields.name') }}</th>
                            <th>{{ trans('cruds.user.fields.email') }}</th>
                            <th>{{ trans('cruds.user.fields.active') }}</th>
                            <th>{{ trans('cruds.user.fields.email_verified') }}</th>
                            <th>{{ trans('cruds.user.fields.phone') }}</th>
                            <th>{{ trans('cruds.user.fields.roles') }}</th>
                            <th>{{ trans('cruds.user.fields.company_name') }}</th>
                            <th>{{ trans('Position/Role') }}</th>
                            <th>{{ trans('cruds.user.fields.company_address') }}</th>
                            <!-- <th>{{ trans('cruds.user.fields.company_post_code') }}</th>
                            <th>{{ trans('cruds.user.fields.company_city') }}</th>
                            <th>{{ trans('cruds.user.fields.company_tel') }}</th>
                            <th>{{ trans('cruds.user.fields.company_mobile') }}</th>
                            <th>{{ trans('cruds.user.fields.company_web_address') }}</th>
                            <th>{{ trans('cruds.user.fields.company_state') }}</th>
                            <th>{{ trans('cruds.user.fields.company_country') }}</th> -->
                            <th>{{ trans('cruds.user.fields.company_vat') }}</th>
                            <!-- <th>{{ trans('cruds.user.fields.delivery_address') }}</th>
                            <th>{{ trans('cruds.user.fields.delivery_condition') }}</th> -->
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $user)
                            <tr data-entry-id="{{ $user->id }}">
                                <td>
                                    @if ($user->approved != 'contact_people')
                                    <input type="checkbox" name="ids[]" value="{{ $user->id }}">
                                    @endif
                                </td>
                                <td align="center">{{ $user->id ?? '' }}</td>
                                <td align="center"><a href="/admin/users/{{ $user->id ?? '' }}/edit">{{ $user->name ?? '' }}</a></td>
                                <td align="center">{{ $user->email ?? '' }}</td>
                                <td align="center">
                                    <?php
                                        if ($user->approved == 'contact_people') {
                                            echo '<span class="bg-success badge text-white">'.trans('CONTACT PEOPLE').'</>';
                                        } else if($user->approved == 0) {
                                            echo '<span class="badge bg-danger text-white">'.trans('inactive').'</>';
                                        } else {
                                            echo '<span class="bg-success badge text-white">'.trans('active').'</>';
                                        }
                                    ?>
                                </td>
                                <td align="center">
                                    <?php
                                        if($user->email_verified_at == 'verified'){
                                            echo '<span class="bg-success badge text-white">'.trans('COMPANY').'</>';
                                        }else if(!$user->email_verified_at) {
                                            echo '<span class="badge bg-danger text-white">'.trans('not yet').'</>';
                                        } else {
                                            echo '<span class="bg-success badge text-white">'.trans('done').'</>';
                                        }
                                    ?>
                                </td>
                                <td align="center">{{ $user->phone ?? '' }}</td>
                                <td align="center">
                                    @foreach($user->roles as $key => $item)
                                        <a href="/admin/roles/{{ $user->roles[0]->id ?? '' }}/edit"><span class="badge blue">{{ $item->title }}</span></a>
                                    @endforeach
                                </td>
                                <td align="center">{{ $user->company_name ?? '' }}</td>
                                <td align="center">{{ $user->position ?? '' }}</td>
                                <td align="center">{{ $user->company_address ?? '' }}</td>
                                <!-- <td align="center">{{ $user->company_post_code ?? '' }}</td>
                                <td align="center">{{ $user->company_city ?? '' }}</td>
                                <td align="center">{{ $user->company_tel ?? '' }}</td>
                                <td align="center">{{ $user->company_mobile ?? '' }}</td>
                                <td align="center">{{ $user->company_web_address ?? '' }}</td>
                                <td align="center">{{ $user->company_state ?? '' }}</td>
                                <td align="center">{{ $user->company_country ?? '' }}</td> -->
                                <td align="center">{{ $user->company_vat ?? '' }}</td>
                                <!-- <td align="center">{{ $user->delivery_address_data ?? '' }}</td>
                                <td align="center">{{ $user->delivery_condition_data ?? '' }}</td> -->
                                <td align="center">
                                    @can('user_show')
                                        @if ($user->approved != 'contact_people')
                                        <a class="btn  button-boxed btn-backward" href="{{ route('admin.users.show', $user->id) }}">
                                            <span> <img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/preview-eye.png')}}" width="25px" height="25px"></span>
                                        </a>
                                        @endif
                                    @endcan
                                    @can('user_edit')
                                        @if ($user->approved != 'contact_people')
                                        <a class="btn  button-boxed p-0" href="{{ route('admin.users.edit', $user->id) }}">
                                            <span> <img class="new mb-2" src="{{asset('/assets/icons/pencil-line-icon-original.svg')}}" width="25px" height="25px"></span>
                                        </a>
                                        @endif
                                    @endcan
                                    <!-- @can('user_delete')
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" class="btn-sm btn-red" value="{{ trans('global.delete') }}">
                                        </form>
                                    @endcan -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
        @can('user_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.users.massDestroy') }}",
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
                        data: { ids: ids, _method: 'DELETE' }
                    })
                    .done(function () { location.reload() })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[ 1, 'desc' ]],
            pageLength: 10,
        });
        let table = $('.datatable-User:not(.ajaxTable)').DataTable({ buttons: dtButtons })
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