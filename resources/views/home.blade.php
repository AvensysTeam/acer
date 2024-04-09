@extends('layouts.admin')
@section('content')
<h1 title="Read Only">@lang('DASHBOARD')</h1>
<h5>@lang('overviews and notifications')</h5>
<hr>
<div class="card-deck">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('ACER STATISTICS')</h4>
            <p class="card-text">(login status)</p>
            <ul>
                <li><a href="{{ route('admin.history') }}" >
                    <i class="fas fa-fw fa-history"></i>
                    @lang('Login history')
                </a></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('CUSTOMER STATISTICS')</h4>
            <p class="card-text">customer t.o.</p>
            <ul>
                <li><a href="#" >@lang('price list validity')</a></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('QUALITY')</h4>
            <p class="card-text">process notification status</p>
            <ul>
                <li><a href="#" >@lang('component production')</a></li>
                <li><a href="#" >@lang('programming')</a></li>
                <li><a href="#" >@lang('end line testing')</a></li>
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
            <a href="#" >@lang('open warranties')</a>                
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('SERVICE STATUS')</h4>
            <p class="card-text">activities who is doing what</p>
            <a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/service/warranty_procedure/" >@lang('Service procedures')</a>
            <ul class="ml-3">
                <li><a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/service/error_list/" >@lang('error list')</a></li>
                <li><a href="https://www.avensys-srl.com//ftproot/DOCUMENTS/service/accessory/" >@lang('how to')</a></li>
            </ul>
        </div>
    </div>
</div>
<hr>
<h1 title="Read and Write">@lang('UTILITIES')</h1>
<h5>@lang('operational tools')</h5>
<br>
<div class="card-deck">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">SALE</h4>
            <ul id="utilties_sale_tree"></ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('PRODUCTION')</h4>
            <ul>
                <li><a href="#" >@lang('table tracker')</a></li>                
                <li><a href="#" >@lang('production status')</a></li>
            </ul>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">@lang('MAINTENANCE AND SERVICE')</h4>
            <ul>
                <li><a href="#">@lang('contracts')</a></li>                
                <li><a href="#">@lang('agreements')</a></li>
                <li><a href="#">@lang('Configuration App')</a></li>
                <li><a href="#">@lang('Service App')</a></li>
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

@endsection

@section('scripts')
<script>
    function buildTree(items = [], mainUl) {
        for (const item of items) {
            if (item.parent_folder_id === null) {
                const li = createListItem(item);
                mainUl.append(li);
            } else if (mainUl.find("li").find(`a#${item.parent_folder_id}`).length) {
                const chaildUl = mainUl.find("li").find(`a#${item.parent_folder_id}`);
                const li = createListItem(item);
                chaildUl.siblings("ul").append(li);
            }
        }
    }

    function createListItem(item) {
        const li = $("<li>");
        const link = $("<a>");
        link.text(item.title).attr("id", item.id);
        if (item.is_folder) {
            const arrow = $("<span>").addClass("arrow").text("•");
            li.append(arrow, link);
            const sublist = $("<ul class='ml-4'>").addClass("open");
            li.append(sublist);
        } else {
            const arrow = $("<span>").addClass("arrow").text("◦");
            link.attr("href", item.link);
            li.append(arrow, link);
        }
        return li;
    }


$(document).ready(function() {
    const $utilitiesSale = <?php echo json_encode($utilitiesSale); ?>;
    const directoryTree = $("#utilties_sale_tree"); // Target the element for the tree
    buildTree($utilitiesSale, directoryTree);
    
    removeEmptyTag(directoryTree)
})


function removeEmptyTag(element) {
    if(element.find('ul.ml-4.open')?.length){
        element.find('ul.ml-4.open').map((i, e) => {
            if ($(e).is(':empty')){
                if($(e).closest('li').parent('ul.ml-4.open')?.length){
                    let ele = $(e).closest('li').parent('ul.ml-4.open').closest('li')
                    
                    if(ele?.length) $(e).closest('li').remove(); removeEmptyTag(ele)
                } else {
                    $(e).closest('li').remove()
                }
            }
        })
    }
}

</script>
@endsection