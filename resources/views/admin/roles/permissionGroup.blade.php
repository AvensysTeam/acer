@php
$selectedPermissions = isset($role) ? $role->permissions->pluck('id')->toArray() : [];
$allPermissions = \App\Permission::get()->toArray();
$groups = collect($allPermissions)->unique('group');
@endphp


<div class="row">
    @if($groups->count())
    @foreach($groups as $group)
    @if(isset($group['group']) && $group['group'])
    <div class="col-md-3 mb-3 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title font-bold">{{ $group['group'] }}</h5>
                @php
                $groupPermission = collect($allPermissions)->where('group', $group['group']);
                @endphp
                @if($groupPermission->count())
                @foreach($groupPermission as $grpPer)
                <div class="card-description">
                    <div class="form-check">
                        <input class="form-check-input permissionChk" type="checkbox" name="permissions[]" value="{{ $grpPer['id'] }}" id="{{ $grpPer['display'] }}" @if(in_array($grpPer['id'], $selectedPermissions)) checked @endif @if($isView) disabled @endif>
                        <label class="form-check-label" for="{{ $grpPer['display'] }}">
                            {{ $grpPer['display'] }}
                        </label>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
    @endif
    @endforeach
    @else
    <div class="col-md-12">No permissions found.</div>
    @endif
</div>