@extends('layouts.admin')
@section('content')



<!-- creo un messaggio di conferma per l'avvenuta eliminazione della sezione -->
@if(session('deleted'))
<div class="alert alert-success" role="alert">
    {{session('deleted')}}
</div>
@endif

<h1 title="Read Only">@lang('DASHBOARD')</h1>
<h5>@lang('overviews and notifications')</h5>
<hr>
<div class="card-deck">
    @can('acer_statistics')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('ACER STATISTICS')</h4>
            <p class="card-text">@lang('login status')</p>
            <hr />
            <ul id="utilties_Acer_Statistics_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'acer_statistics']) }}"style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan
    @can('customer_statistics')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('CUSTOMER STATISTICS')</h4>
            <p class="card-text">@lang('customer t.o.')</p>
            <hr />
            <ul id="utilties_Customer_Statistics_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'customer_statistics']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan
    @can('dashboard_quality')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('QUALITY')</h4>
            <p class="card-text">@lang('process notification status')</p>
            <hr />
            <ul id="utilties_Dashboard_Quality_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'dashboard_quality']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan
    @can('dashboard_maintenance')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('MAINTENANCE')</h4>
            <p class="card-text">@lang('status')</p>
            <hr />
            <ul id="utilties_Dashboard_Maintenance_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'dashboard_maintenance']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan
    @can('warranty')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('WARRANTY STATUS')</h4>
            <p class="card-text">@lang('how many units are in the warranty period')</p>
            <hr />   
            <ul id="utilties_Warranty_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'warranty']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('SERVICE STATUS')</h4>
            <p class="card-text">@lang('activities who is doing what')</p>
            <hr />
            <ul id="utilties_Service_tree">
            </ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'service']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('INFORMATICS')</h4>
            <p class="card-text">@lang('informatic tools')</p>
            <hr />
            <ul id="utilties_Informatics_tree">
            </ul>
        </div>
        @can('utilities_access')
        <a href="{{ route('admin.utilities', ['modelname' => 'informatics']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    
</div>
<hr>
<h1 title="Read and Write">@lang('UTILITIES')</h1>
<h5>@lang('operational tools')</h5>
<br>
<div class="card-deck">
    @can('sale')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('SALE')</h4>
            <hr />
            <ul id="utilties_Sale_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'sale']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan
    @can('production')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('PRODUCTION')</h4>
            <hr />
            <ul id="utilties_Production_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'production']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan
    @can('maintenance')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('MAINTENANCE AND SERVICE')</h4>
            <hr />
            <ul id="utilties_Maintenance_tree"></ul>
        </div>
        <a href="{{ route('admin.utilities', ['modelname' => 'maintenance']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
    </div>
    @endcan
    @can('quality')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('QUALITY')</h4>
            <p class="card-text">@lang('warranty management')</p>
            <hr />
            <ul id="utilties_Quality_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'quality']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan
    @can('mechanicals')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('MECHANICALS')</h4>
            <hr />
            <ul id="utilties_Mechanicals_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'mechanicals']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan
    @can('electronics')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title text-danger font-weight-bold">@lang('ELECTRONICS')</h4>
            <!-- <a href="https://acer.avensys-srl.com/planteuml" target="blank"><p class="card-text">@lang('PlanteUML')</p></a> -->
            <hr />
            <ul id="utilties_Electronics_tree"></ul>
        </div>
        @can('utilities_access')
            <a href="{{ route('admin.utilities', ['modelname' => 'electronics']) }}" style="display: flex; justify-content: flex-end;">
                <img class="mb-2" src="{{ asset('assets/icons/pencil-line-icon-original.svg') }}" width="25px" height="25px" style="margin-right: 10px;" />
        </a>
        @endcan
    </div>
    @endcan

    
</div>
@endsection

@section('scripts')
<script>
    const defaultData = {
    acerStatistics: [
        { id: 0, title: "service_logbook", is_folder: true, parent_folder_id: null },
        { id: 1, title: "{{ auth()->user()->name }}", is_folder: true, parent_folder_id: 0 },
        { id: 2, title: "Qrcode", is_folder: false, parent_folder_id: 1, link: "{{route('admin.qrcode', ['email' => Auth::user()->email])}}" },
    ],
    informaticsStatistics: [
        { id: 0, title: "Acer back up manager", is_folder: true, parent_folder_id: null },
        { id: 1, title: "Backup features", is_folder: false, parent_folder_id: 0, link: "{{ route('admin.exportdata') }}" },
     
    ],
    
};

buildTree(defaultData.acerStatistics, $("#utilties_Service_tree"));
buildTree(defaultData.informaticsStatistics, $("#utilties_Informatics_tree"));
    function buildTree(items = [], mainUl) {
    for (const item of items) {
        if (item.parent_folder_id === null) {
            const li = createListItem(item);
            mainUl.append(li);
        } else {
            const parentLink = mainUl.find(`a#${item.parent_folder_id}`);
            if (parentLink.length) {
                const sublist = parentLink.siblings("ul");
                const li = createListItem(item);
                sublist.append(li);
            }
        }
    }
}
function createListItem(item) {
    const li = $("<li>");
    const link = $("<a>").attr("id", item.id);

    if (item.is_folder) {
        const folderIcon = $("<i>").addClass("fas fa-folder").addClass("text-primary");
        link.text(" " + item.title).prepend(folderIcon);

        const sublist = $("<ul class='ml-4'>").addClass("hidden"); // Initially hide the sublist

        // Toggle the sublist visibility when clicking on the folder icon
        link.on("dblclick", function(e) {
            e.preventDefault();
            sublist.toggleClass("hidden");
        });
        folderIcon.on("click", function(e) {
            e.preventDefault();
            sublist.toggleClass("hidden");
        });
        link.add(folderIcon).css("user-select", "none");
        link.css("cursor", "pointer");
        folderIcon.css("cursor", "pointer");
        li.append(link, sublist);
    } else {
        const fileIcon = $("<i>").addClass("fas fa-file").addClass("text-info");
        link.text(" " + item.title).attr("href", item.link).prepend(fileIcon);

        // Add the confirmation dialog for specific files
        

        li.append(link);
    }

    return li;
}



        
    $(document).ready(function() {
        const $utilitiesSale = <?php echo json_encode($utilties_Acer_Statistics_tree); ?>;
        const directoryTree = $("#utilties_Acer_Statistics_tree");
        buildTree($utilitiesSale, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesSale = <?php echo json_encode($utilties_Customer_Statistics_tree); ?>;
        const directoryTree = $("#utilties_Customer_Statistics_tree");
        buildTree($utilitiesSale, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesSale = <?php echo json_encode($utilties_Dashboard_Quality_tree); ?>;
        const directoryTree = $("#utilties_Dashboard_Quality_tree");
        buildTree($utilitiesSale, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesSale = <?php echo json_encode($utilties_Dashboard_Maintenance_tree); ?>;
        const directoryTree = $("#utilties_Dashboard_Maintenance_tree");
        buildTree($utilitiesSale, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesSale = <?php echo json_encode($utilties_Warranty_tree); ?>;
        const directoryTree = $("#utilties_Warranty_tree");
        buildTree($utilitiesSale, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesSale = <?php echo json_encode($utilties_Service_tree); ?>;
        const directoryTree = $("#utilties_Service_tree");
        buildTree($utilitiesSale, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesSale = <?php echo json_encode($utilties_Informatics_tree); ?>;
        const directoryTree = $("#utilties_Informatics_tree");
        buildTree($utilitiesSale, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesSale = <?php echo json_encode($utilitiesSale); ?>;
        const directoryTree = $("#utilties_Sale_tree");
        buildTree($utilitiesSale, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesProduction = <?php echo json_encode($utilitiesProduction); ?>;
        const directoryTree = $("#utilties_Production_tree");
        buildTree($utilitiesProduction, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesMaintenance = <?php echo json_encode($utilitiesMaintenance); ?>;
        const directoryTree = $("#utilties_Maintenance_tree");
        buildTree($utilitiesMaintenance, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesQuality = <?php echo json_encode($utilitiesQuality); ?>;
        const directoryTree = $("#utilties_Quality_tree");
        buildTree($utilitiesQuality, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesQuality = <?php echo json_encode($utilitiesMechanicals); ?>;
        const directoryTree = $("#utilties_Mechanicals_tree");
        buildTree($utilitiesQuality, directoryTree);
    })
    $(document).ready(function() {
        const $utilitiesQuality = <?php echo json_encode($utilitiesElectronics); ?>;
        const directoryTree = $("#utilties_Electronics_tree");
        buildTree($utilitiesQuality, directoryTree);
    })
</script>
@endsection