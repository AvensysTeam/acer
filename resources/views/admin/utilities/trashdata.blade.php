@extends('layouts.admin')
@section('content')

<div class="main-card">
    <div class="body">
        <h2>TRASH</h2>
        <input type="hidden" id="model" value="{{$model}}" name="model" />
        <div class="w-full table-responsive">
            <table class="display table compact project-table datatable-project">
                <thead>
                    <tr>
                        <th>@lang('Title')</th>
                        <th>@lang('Link')</th>
                        <th>@lang('Parent')</th>
                        <th>@lang('Users')</th>
                        <th>@lang('Action')</th>
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
                            <td class="action_btn" style="display: flex;">
                                <a href="javascript:void(0);" data-id="{{ $tool->id }}" class="trashRestore">
                                    <img class="new mb-2" src="{{asset('assets/icons/restore.png')}}" width="25px" height="25px" />
                                </a>
                                <a href="javascript:void(0);" data-id="{{ $tool->id }}" class="trashDelete">
                                    <img class="new mb-2" src="{{asset('assets/icons/trash-icon-original.svg')}}" width="25px" height="25px"/>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" class="text-center">No data found.</td>
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
@endsection
@section('scripts')
<script>    
    $(document).ready(function() {
        $('.trashRestore').click(function() {
            let id = $(this).data('id');
            let modelName = $('#model').val();
            if (id && modelName) {
                if (confirm('Are you sure you want to recover this record?')) {
                    $.ajax({
                        method: 'POST',
                        headers: {'x-csrf-token': _token},
                        url: `/admin/utilities/sale/trashrestore/${id}`,
                        data: {modelname: modelName},
                    }).done(function (res) {
                        window.location.reload(true);
                    }).catch(function(e){
                        if(e?.responseJSON?.result) alert(e?.responseJSON?.result);
                    });
                }
            }
            return false;
        });
        $('.trashDelete').click(function() {
            let id = $(this).data('id');
            let modelName = $('#model').val();
            if (id && modelName) {
                if (confirm('Are you sure you want to delete this record?')) {
                    $.ajax({
                        method: 'DELETE',
                        headers: {'x-csrf-token': _token},
                        url: `/admin/utilities/sale/trashdelete/${id}`,
                        data: {modelname: modelName},
                    }).done(function (res) {
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