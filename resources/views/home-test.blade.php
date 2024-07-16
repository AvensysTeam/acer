<h1>@lang('DASHBOARD')</h1>
<div class="card-deck">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('ACER STATISTICS')</h4>
            <p class="card-text">(login status)</p>
            <ul>
                @if(in_array('login_history', $role) && isset($role))
                <li><a href="{{ route('admin.history') }}" >
                    <i class="fas fa-fw fa-history"></i>
                    @lang('History')
                </a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('CUSTOMER STATISTICS')</h4>
            <p class="card-text">customer t.o.</p>
            <ul>
                @if(in_array('price_list_validity', $role) && isset($role))
                <li><a href="#" >@lang('price list validity')</a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('QUALITY')</h4>
            <p class="card-text">process notification status</p>
            <ul>
                @if(in_array('component_production', $role) && isset($role))
                <li><a href="#" >@lang('component production')</a></li>
                @endif
                @if(in_array('programming', $role) && isset($role))
                <li><a href="#" >@lang('programming')</a></li>
                @endif
                @if(in_array('end_line_testing', $role) && isset($role))
                <li><a href="#" >@lang('end line testing')</a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('MAINTENANCE')</h4>
            <p class="card-text">status</p>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('WARRANTY STATUS')</h4>
            <p class="card-text">how many units are in the warranty period</p>
            @if(in_array('open_warranties', $role) && isset($role))
            <a href="#" >@lang('open warranties')</a>
            @endif             
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('SERVICE STATUS')</h4>
            <p class="card-text">activities who is doing what</p>
            @if(in_array('service_procedures', $role) && isset($role))
            <a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/service/warranty_procedure/" >@lang('Service procedures')</a>
            @endif
            <ul class="ml-3">
                @if(in_array('error_list', $role) && isset($role))
                <li><a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/service/error_list/" >@lang('error list')</a></li>
                @endif
                @if(in_array('how_to', $role) && isset($role))
                <li><a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/service/accessory" >@lang('how to')</a></li>
                @endif
            </ul>
        </div>
    </div>
</div>
<hr>
<h1>@lang('UTILITIES')</h1>
<div class="card-deck">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">SALE</h4>
            <ul>
                @if(in_array('news', $role) && isset($role))
                <li><a href="#" >@lang('news')</a>
                    <ul class="ml-3">
                        <li><a href="#">@lang('forum')</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" >@lang('tools')</a>
                    <ul class="ml-3">
                        <li><a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/tools/KTS_virtual/KTS_AV_2_32.zip" >@lang('virtual kts')</a></li>
                        <li><a href="#" >@lang('virtual kps')</a></li>
                        <li><a href="#" >@lang('sum')</a></li>
                        <li><a href="#" >@lang('upgrade')</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('PRODUCTION')</h4>
            <ul>
                @if(in_array('table_tracker', $role) && isset($role))
                <li><a href="#" >@lang('table tracker')</a></li>   
                @endif
                @if(in_array('production_status', $role) && isset($role))
                <li><a href="#" >@lang('production status')</a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('MAINTENANCE AND SERVICE')</h4>
            <ul>
                @if(in_array('contracts', $role) && isset($role))
                <li><a href="#" >@lang('contracts')</a></li>
                @endif
                @if(in_array('agreements', $role) && isset($role))
                <li><a href="#" >@lang('agreements')</a></li>
                @endif
                @if(in_array('configuration_app', $role) && isset($role))
                <li><a href="#" >@lang('Configuration App')</a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('QUALITY')</h4>
            <p class="card-text">@lang('warranty management')</p>            
        </div>
    </div>    
</div>