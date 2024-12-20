@extends('layouts.admin')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.16/dist/sweetalert2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<style>
    .chart-tab-active {
        display: block !important;
    }
    #pricetable tbody td {
        vertical-align: middle !important;
    }
    .price-image {
        width: 150px;
        margin: auto;
    }
    .units-table th {
        padding: 5px;
    }
    .units-table tbody td {
        padding: 5px;
    }
    .units-delivery-time-table th {
        padding: 5px;
    }
    .units-delivery-time-table tbody td {
        padding: 5px;
    }
    .nextbtn1{
        float:right;
        padding: 0px;
        margin-top: 15px;
        margin-right: 5%;
    }
    .nextbtn2{
        float:right;
        padding: 0px;
        margin-top: 15px;
        margin-right: 5%;
    }
    .box1{
        padding:0px;
        padding-right:5px;
    }
    .box2{
        padding:0px;
        padding-right:5px;
        padding-left:5px;
    }
    .box3{
        padding:0px;
        padding-left:5px;
    }
    .p_details{
        position: relative;
        border: 3px solid orange;
        max-height: 80vh;
        overflow: scroll;
        width: 100%;
    }
    .utable{
        width: 100%;
        position: relative;
        /* left: 20px; */
    }
    body.no-scroll {
        overflow: hidden;
    }
    .v-hidden {
        visibility: hidden;
    }
    .unit-thumbnail {
        height: 50px;
    }
    .highlighted-unit td {
        background: #89bdff;
    }
    .highlighted-unit td.exclude-highlight {
        background: none; /* Exclude rowspan cells */
    }
</style>
<?php
    $units_list = json_decode($units);
?>
@if(Session::has('flash'))
  {{Session::get('flash')}}
@endif



<div class="w-full my-3">
    @if ($units_list && count($units_list) > 0)
    <div class="action-btn-group text-right">
        <a class="btn button-boxed @if($option == 'readonly') v-hidden @endif " onclick="addNewUnitInProject()" title="@lang('add new unit')">
            <span> <img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/plus-circle-icon-original.svg')}}" width="35px" height="35px"></span>
        </a>
    </div>
    @endif
     
    <table class="display compact units-table datatable-t1 utable" style="visibility: hidden;">
      @if ($units_list)
         <thead>
            <tr>
                <th class="text-center">@lang('Unit Name')</th>
                <th class="text-center">@lang('Thumbnail')</th>
                <th class="text-center">@lang('Technical PDF')</th>
                <th class="text-center">@lang('Commercial PDF')</th>
                <th class="text-center">@lang('Delivery Time')</th>
                <th class="text-center" width="110px">@lang('Action')</th>
            </tr>
        </thead>
        @endif
        <tbody>
            @if ($units_list)
            @foreach ($units_list as $key => $row)
            <tr class="unit-row text-center" data-name="{{$row->name}}" data-id="{{$row->id}}">
                <td>{{$row->name}}</td>
                <td>
                    @if($row->thumbnail)
                        <img class="unit-thumbnail" src="{{$row->thumbnail}}" alt="{{$row->name}}">
                    @endif
                </td>
                <td>
                    <a class="btn button-boxed p-0" href="/uploads/project/{{$row->pdf}}" target="_blank" title="@lang('Show Technical PDF')">
                        <span> <img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/preview-eye.png')}}" width="35px" height="35px"></span>
                    </a>
                </td>
                <?php if($key == 0) { ?>
                    <td rowspan="{{count($units_list)}}">
                        <a class="btn button-boxed btn-backward" onclick="projectPDFPreview()"  title="@lang('Show Commercial PDF')">
                            <span> <img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/preview-eye.png')}}" width="35px" height="35px"></span>
                        </a>
                    </td>
                <?php } ?>
                <td class="align-items-center flex justify-between">
                    <?php
                    if ($row->delivery_time != 'undefined') {
                        $deliverytime = explode('_', $row->delivery_time);
                    
                        if ($deliverytime[1] == 1) {
                            echo $deliverytime[0] . ' ' .  __('Day(s)');
                        } else {
                            echo $deliverytime[0] . ' ' . __('Week(s)');
                        }?>
                        <a class="btn button-boxed btn-forward" onclick="addDeliveryTime()"  title="@lang('Edit Delivery Time')">
                            <span> <img class="new mb-2" src="{{asset('/assets/icons/pencil-line-icon-original.svg')}}" width="25px" height="25px"></span>
                        </a>
                    <?php } else { ?>

                         <a class="btn button-boxed btn-forward @if($option == 'readonly') v-hidden @endif" onclick="addDeliveryTime()"  title="@lang('Add Delivery Time')">
                            <span> <img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/plus-circle-icon-original.svg')}}" width="35px" height="35px"></span>
                        </a>
                    <?php 
                    }
                    ?>
                </td>
                <td center>
                    @if($option != 'readonly')
                    <!-- <a class="btn button-boxed p-0" onclick="editOrViewUnit(`{{$row->name}}`, 'view')">
                        <span> <img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/preview-eye.png')}}" width="35px" height="35px"></span>
                    </a> -->                    
                    <a class="btn button-boxed p-0" onclick="editOrViewUnit(`{{$row->id}}`, 'edit')"  title="@lang('Edit Unit')">
                        <span> <img class="new mb-2" src="{{asset('/assets/icons/pencil-line-icon-original.svg')}}" width="35px" height="35px"></span>
                    </a>
                    <a class="btn button-boxed p-0" onclick="onDeleteUnit(`{{$row->name}}`)"  title="@lang('Delete Unit')">
                        <span> <img class="new mb-2" src="{{asset('/assets/icons/trash-icon-original.svg')}}" width="35px" height="35px"></span>
                    </a>
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
        </tbody>
    </table>
</div>
<!-- project unit edit part -->
<div class="main-card project-detail p_details  @if( $pid != '0') d-none @endif">    
    <div class="body">
        <div class="row">
            <div class="col-md-6" >
                <div class="form-group row" id="unitform" style="display: none;">
                    <label for="unit_name" class="col-md-3 col-form-label">@lang('Unit Name')</label>
                    <div class="col-md-8" style="display:flex;">
                        <input type="text" class="form-control" id="unit_name" name="uname" placeholder="Unit Name" value="">
                    </div>
                </div>
            </div>
            

            <div class="col-md-6" style=" text-align: end;">

                <a class="btn button-boxed preview-unit-pdf-btn d-none step-action-btn" onclick="preview2PDF()" step="0">
                    <span> <img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/preview-eye.png')}}" width="35px" height="35px"></span>
                </a>
                <a class="btn button-boxed add-more-unit-btn d-none step-action-btn" onclick="addMoreUnit()" step="0">
                    <span> <img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/plus-circle-icon-original.svg')}}" width="35px" height="35px"></span>
                </a> 
                <a class="btn button-boxed prev-step-btn d-none step-action-btn" step="0">
                    <span> <img class="new mb-2 " src="{{asset('/assets/icons/set_creazilla/caret-circle-left-thin.svg')}}" width="35px" height="35px"></span>
                </a>
                <a class="btn button-boxed next-step-btn d-none step-action-btn" step="0">
                    <span> <img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/caret-circle-right-icon-original.svg')}}" width="35px" height="35px"></span>
                </a>
            </div>
            
        </div>

        <div class="w-full">
            <div class="tabs tabs-primary">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">               
                        <a class="nav-link  active"  id="tab_unit_home" href="#project_tab" data-title1="Project reference" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('PROJECT REFERENCE')</a>
                    </li>
                    <li class="nav-item" role="presentation">               
                        <a class="nav-link" id="tab_unit_selection" data-title1="Unit selection" href="#unit_tab" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('UNIT SELECTION')</a>
                    </li>
                    <li class="nav-item" role="presentation">               
                        <a class="nav-link uname @if($option == '') disabled @else onclick='onViewUnit()' @endif" id="tab_results_table" data-title1="Unit features" href="#unit_feature_tab" data-bs-toggle="tab" aria-selected="true"  role="tab">@lang('UNIT FEATURES')</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="project_tab" class="tab-pane active pt-3" role="tabpanel">
                        <div class="border border-dark rounded px-5 py-1 row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="project_name" class="text-xs">@lang('Project Name')</label>
                                    <input type="text" id="project_name" name="project_name" class="form-control" value="{{$project->name ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="project_desc" class="text-xs">@lang('Project Description')</label>
                                    <input type="text" id="project_desc" name="project_desc" class="form-control" value="{{$project->description ?? ''}}">
                                </div>                                
                                <input type="hidden" id="project_reference" name="project_reference" class="form-control" value="{{$project->reference ?? ''}}">
                                
                                @if($option == 'readonly')
                                <div class="form-group">
                                    <label for="project_reference" class="text-xs">@lang('Project Reference')</label>
                                    <input type="text" id="project_reference" class="form-control" value="{{$project->reference ?? ''}}" readonly>
                                 </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="create_date" class="text-xs">@lang('Creation Date')</label>
                                    <?php
                                        $c_date = null;
                                        if(isset($project->created_at))
                                        {
                                            $date = strtotime($project->created_at);
                                            $c_date = date('d.m.Y', $date);
                                        } else {
                                            $c_date = date('d.m.Y'); 
                                        }
                                    ?>
                                    <input type="text" id="create_date" name="create_date" class="form-control" value="{{$c_date}}" readonly>
                                </div>
                                <div class="form-group">
                                    <?php
                                        $m_date = null;
                                        if(isset($project->updated_at))
                                        {
                                            $date = strtotime($project->updated_at);
                                            $m_date = date('d.m.Y', $date);
                                        } else {
                                            $m_date = date('d.m.Y'); 
                                        }
                                    ?>
                                    <label for="modify_date" class="text-xs">@lang('Last Modified Date')</label>
                                    <input type="text" id="modify_date" name="modify_date" class="form-control" value="{{$m_date}}" readonly>
                                </div>
                               
                            </div>
                            
                        </div>                        
                    </div>
                    <div id="unit_tab" class="tab-pane pt-3 px-3" role="tabpanel">
                        

                        <div class="row" style="justify-content: space-around;">
                            <div class="col-lg-12 col-xl-5 box1">
                                <div class="box border border-dark rounded px-3 mt-3">
                                    <div class="box-header">
                                        @lang('AIRFLOW data')
                                        <a href="#" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </div>
                                    <div class="box-body pb-4">
                                        <div class="text-center">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label for="title" class="text-xs">@lang('Application type')</label>
                                                    <div class="form-group">
                                                        <select class="form-control" id="p_layout">
                                                            <option value="C" selected>@lang('Centralized')</option>
                                                            <option value="D">@lang('Decentralized')</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <label for="title" class="text-xs">@lang('Installation type')</label>
                                                    <div class="form-group">
                                                        <label class="label-chb"><input type="radio" name="indoor" value="I" checked/> @lang('Indoor')</label>
                                                        <label class="label-chb"><input type="radio" name="indoor" value="O"/> @lang('Outdoor')</label>
                                                        <i class="fa fa-warning text-danger"></i>
                                                    </div>
                                                </div>            
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div>
                                                        <label class="text-xs">@lang('Recovery technology')</label>
                                                        <i class="fa fa-warning text-danger"></i>
                                                    </div>
                                                    <div class="flex">
                                                        <a href="#" class="image-item">
                                                            <div class="image-item-header" style="background-image: url('{{ asset('img/m/ST_LT.png') }}');background-position: center;background-size: 70%;"></div>
                                                            <div class="image-item-footer py-2">
                                                                <label class="mb-0"><input type="radio" name="ex" value="CF|LT" checked/> @lang('Standard plate')</label>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="image-item">
                                                            <div class="image-item-header" style="background-image: url('{{ asset('img/m/ST_EN.png') }}');background-position: center;background-size: 70%;"></div>                                                        
                                                            <div class="image-item-footer py-2">
                                                                <label class="mb-0"><input type="radio" name="ex" value="CF|EN"/> @lang('Entalphic plate')</label>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="image-item">
                                                            <div class="image-item-header" style="background-image: url('{{ asset('img/m/RT_LT.png') }}');background-position: center;background-size: 65%;"></div>                                                        
                                                            <div class="image-item-footer py-2">
                                                                <label class="mb-0"><input type="radio" name="ex" value="RT|LT"/> @lang('Standard rotary')</label>
                                                            </div>
                                                        </a>
                                                        <a href="#" class="image-item">
                                                            <div class="image-item-header" style="background-image: url('{{ asset('img/m/RT_EN.png') }}');background-position: center;background-size: 65%;"></div>                                                        
                                                            <div class="image-item-footer py-2">
                                                                <label class="mb-0"><input type="radio" name="ex" value="RT|EN"/> @lang('Entalphic rotary')</label>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <div class="row">
                                                    <div class="col-lg-12 col-xl-6">
                                                        <h6>@lang("Supply")</h6>
                                                        <div class="row">
                                                            <div class="col-md-6 text-left">
                                                                <label for="p_airflow" class="text-xs">@lang('Airflow rate')(m3/h)</label>
                                                                <div class="form-group">
                                                                    <input type="text" id="p_airflow" name="p_airflow" class="form-control" value="200" style="width: 69px;">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 text-left">
                                                                <label for="p_pressure" class="text-xs">@lang('Airflow pressure')(Pa)</label>
                                                                <div class="form-group">
                                                                    <input type="text" id="p_pressure" name="p_pressure" class="form-control" value="50" style="width: 69px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-xl-6">
                                                        <h6>@lang("Return")</h6>
                                                        <div class="row">
                                                            <div class="col-md-6 text-left">
                                                                <label for="p_r_airflow" class="text-xs">@lang('Airflow rate')(m3/h)</label>
                                                                <div class="form-group">
                                                                    <input type="text" id="p_r_airflow" name="p_r_airflow" class="form-control" value="200" style="width: 69px;">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 text-left">
                                                                <label for="p_r_pressure" class="text-xs">@lang('Airflow pressure')(Pa)</label>
                                                                <div class="form-group">
                                                                    <input type="text" id="p_r_pressure" name="p_r_pressure" class="form-control" value="50" style="width: 69px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                 
                                    </div>
                                    
                                </div>
                                <div class="box border border-dark rounded px-3 mt-3">
                                    <div class="box-header">
                                        @lang('PERFORMANCE CONSTRAINT')
                                        <a href="#" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </div>
                                    <div class="box-body pb-4">
                                        <div class="text-center">
                                            <div class="mt-3">
                                                <div class="row">
                                                    <div class="col-md-6 text-left">
                                                        <label for="p_sfp" class="text-xs">@lang('SFP') (J/m3)</label>
                                                        <div class="form-group">
                                                            <input type="text" id="p_sfp" name="p_sfp" class="form-control" value="1000" style="width: 69px;">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 text-left">
                                                        <label for="m_rfl" class="text-xs">@lang('Max Regulation Fan Level')([%])</label>
                                                        <div class="form-group">
                                                            <input type="text" id="m_rfl" name="m_rfl" class="form-control" value="85" style="width: 69px;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>                                 
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-lg-12 col-xl-3 box2">
                                <div class="box border border-dark rounded px-3 mt-3">
                                    <div class="box-header">
                                        @lang('Climatic data')
                                        <a href="#" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </div>
                                    <div class="box-body pb-4 position-relative">
                                        <div>
                                            <h5>@lang("Winter Conditions")</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="p_w_Tfin" class="text-xs">@lang("Fresh Air temperature")(°C)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_w_Tfin" name="p_w_Tfin" class="form-control climatic-inner-data" min="-20" max="40"  value="-10">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="p_w_Trin" class="text-xs">@lang("Return Air temperature")(°C)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_w_Trin" name="p_w_Trin" class="form-control climatic-inner-data" min="-20" max="40"  value="20">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="p_w_Hfin" class="text-xs">@lang("Fresh Air humidity")(%)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_w_Hfin" name="p_w_Hfin" class="form-control climatic-inner-data" min="5" max="98"  value="80">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="p_w_Hrin" class="text-xs">@lang("Return Air humidity")(%)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_w_Hrin" name="p_w_Hrin" class="form-control climatic-inner-data" min="5" max="98"  value="60">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <h5>@lang("Summer Conditions")</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="p_s_Tfin" class="text-xs">@lang("Fresh Air temperature")(°C)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_s_Tfin" name="p_s_Tfin" class="form-control climatic-inner-data" min="-20" max="40"  value="-10">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="p_s_Trin" class="text-xs">@lang("Return Air temperature")(°C)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_s_Trin" name="p_s_Trin" class="form-control climatic-inner-data" min="-20" max="40"  value="20">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label for="p_s_Hfin" class="text-xs">@lang("Fresh Air humidity")(%)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_s_Hfin" name="p_s_Hfin" class="form-control climatic-inner-data" min="5" max="98"  value="80">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="p_s_Hrin" class="text-xs">@lang("Return Air humidity")(%)</label>
                                                    <div class="form-group">
                                                        <input type="number" id="p_s_Hrin" name="p_s_Hrin" class="form-control climatic-inner-data" min="5" max="98"  value="60">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <input type="checkbox" name="climatic_standard" id="climatic_standard">                                            
                                            <label for="climatic_standard">@lang('Standard')</label>
                                            <p>(@lang('These parameters will remain for all projects.'))</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class=" col-lg-12 col-xl-3 box3">
                                <div class="box border border-dark rounded px-3 mt-3">
                                    <div class="box-header">
                                        @lang('Accessories')
                                        <a href="#" class="btn btn-sm btn-secondary">
                                            <i class="fa fa-minus"></i>
                                        </a>
                                    </div>
                                    <div class="box-body pb-4 position-relative">
                                        <div class="mt-3">
                                            <p><label><input type="checkbox" checked> @lang('Without coil')</label></p>
                                            <p><label><input type="checkbox"> @lang('With defrosting electrical coil')</label></p>
                                            <h6>@lang("Temperature") (@lang("Winter"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_w_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_w_i_Temperature" name="h_w_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_w_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_w_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_w_o_Temperature" name="h_w_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_w_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                            <p><label><input type="checkbox"> @lang('With heating coil')</label></p>
                                            <h6>@lang("Temperature") (@lang("Summer"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_s_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_s_i_Temperature" name="h_s_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_s_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_s_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_s_o_Temperature" name="h_s_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_s_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                            <p><label><input type="checkbox"> @lang('With cooling coil')</label></p>
                                            <h6>@lang("Temperature") (@lang("Winter"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_s_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_s_i_Temperature" name="h_s_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_s_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="h_s_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="h_s_o_Temperature" name="h_s_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="h_s_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                            <p><label><input type="checkbox"> @lang('With reversible coil')</label></p>
                                            <h6>@lang("Temperature") (@lang("Winter"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="r_w_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="r_w_i_Temperature" name="r_w_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="r_w_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="r_w_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="r_w_o_Temperature" name="r_w_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="r_w_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                            <h6>@lang("Temperature") (@lang("Summer"))(°C)</h6>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="r_s_i_Temperature">@lang("In")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="r_s_i_Temperature" name="r_s_i_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="r_s_i_Temperature">(°C)</label>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-xl-6">
                                                    <label for="r_s_o_Temperature">@lang("Out")</label>
                                                    <div class="form-group inline-block">
                                                        <input type="number" id="r_s_o_Temperature" name="r_s_o_Temperature" class="form-control" min="-20" max="40"  value="0">
                                                    </div>
                                                    <label for="r_s_o_Temperature">(°C)</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="mt-3 d-flex justify-content-end">
                            <a class="btn btn-outline-secondary button-right btn-display-models" onclick="display_compatible_models(null)">@lang('Display compatible models')</a>
                        </div> -->
                    </div>
                    <div id="unit_feature_tab" class="tab-pane" role="tabpanel">
                        <div class="w-full mt-3 models-tbl-container">
                            <table class="display compact project-table datatable-t1 unitfeaturs">
                                <thead>
                                    <tr>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                        <div class="w-full mt-3">
                            <div class="tabs tabs-primary graph-tabs hidden">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" href="#tech_info" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('TECHNICAL INFORMATION')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#pressure_curve" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('PRESSURE CURVE')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#power_consumer" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">@lang('POWER CONSUMPTION CURVE')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#psfp_curve" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">@lang('PSFP CURVE')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#efficency_curve" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">@lang('EFFICIENCY CURVE')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#noise_level" data-bs-toggle="tab" aria-selected="false" tabindex="-1" role="tab">@lang('NOISE LEVEL')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#accessories_tab" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('ACCESSORIES')</a>
                                    </li>
                                    <li class="nav-item" role="presentation">               
                                        <a class="nav-link" href="#price_tab" data-bs-toggle="tab" aria-selected="true" role="tab">@lang('PRICE')</a>
                                    </li>
                                </ul>
                                <div class="tab-content chart-tab-content">
                                    <div id="tech_info" class="tab-pane active p-3" role="tabpanel">
                                    </div>
                                    <div id="pressure_curve" class="tab-pane pt-3" role="tabpanel">
                                        <div class="w-fill px-3 graph-container">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <img id="render" src="">
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="mx-auto line-exp">
                                                        <div><span class="stroke-line line-color-blue"></span> @lang('Max curve')</div>
                                                        <div><span class="dashed-line line-color-blue"></span> @lang('Operating curve')</div>
                                                        <div><span class="stroke-dot line-color-blue"></span> @lang('Working point')</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-lg-6 col-xl-4">
                                                    <canvas height="200" id="pressure_graph"></canvas>
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div id="power_consumer" class="tab-pane pt-3" role="tabpanel">
                                        <div class="w-fill px-3 graph-container">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="mx-auto line-exp">
                                                        <div><span class="stroke-line line-color-blue"></span> @lang('Max curve')</div>
                                                        <div><span class="dashed-line line-color-blue"></span> @lang('Operating curve')</div>
                                                        <div><span class="stroke-dot line-color-blue"></span> @lang('Working point')</div>                                                                                                        
                                                    </div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-6 col-lg-6 col-xl-4">
                                                    <canvas height="200" id="power_graph"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="psfp_curve" class="tab-pane pt-3" role="tabpanel">
									    <div class="w-fill px-3 graph-container">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="mx-auto line-exp">
                                                        <div><span class="stroke-line line-color-blue"></span> @lang('Max curve')</div>
                                                        <div><span class="dashed-line line-color-blue"></span> @lang('Operating curve')</div>
                                                        <div><span class="stroke-dot line-color-blue"></span> @lang('Working point')</div>                     
                                                    </div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-6 col-lg-6 col-xl-4">
                                                    <canvas height="200" id="psfp_graph"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="efficency_curve" class="tab-pane pt-3" role="tabpanel">
                                        <div class="w-fill px-3 graph-container">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="mx-auto line-exp">
                                                        <div><span class="stroke-line line-color-blue"></span> @lang('Max curve')</div>
                                                        <div><span class="stroke-dot line-color-blue"></span> @lang('Working point')</div>                                                       
                                                    </div>
                                                </div>
                                                <div class="col-md-1"></div>
                                                <div class="col-md-6 col-lg-6 col-xl-4">
                                                    <canvas height="200" id="efficiency_graph"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="noise_level" class="tab-pane pt-3" role="tabpanel">
                                    </div>
                                    <div id="accessories_tab" class="tab-pane p-3" role="tabpanel">
                                        <div class="w-full" id="accessoriestable">
                                        </div>
                                    </div>
                                    <div id="price_tab" class="tab-pane p-3" role="tabpanel">
                                        <div class="w-full" id="pricetable">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<!-- Modal -->
<div class="modal fade" 
    id="staticBackdrop" 
    data-backdrop="static" 
    data-keyboard="false" 
    tabindex="-1" 
    aria-labelledby="staticBackdropLabel" 
    aria-hidden="true"
    >
    <div class="modal-dialog">
        <div class="modal-content modal-content-add-unit">
            <div class="modal-body">
                @lang("Would you add more Unit?")
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-primary btn-multiple">Yes</button>
                <button type="button" class="btn btn-info btn-multiple">No</button>
            </div>
        </div>
        <div class="modal-content modal-content-save-delivery-time" style="display: none;">
            <div class="modal-body">
                <div class="w-full">
                    <table class="display compact units-delivery-time-table datatable-t1" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>@lang('Unit Name')</th>
                                <th>@lang('Delivery Time')</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-primary save-delivery-time-btn" onclick="onSaveDeliveryTime()">Save</button>
                <button type="button" class="btn btn-info btn-cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>


<div class="modal" id="pdf_on_iframe_model">
    <div class="modal-dialog modal-xl " role="document">
      <div class="modal-content">
        <div class="modal-body" style="height: calc(85vh);">
            <iframe id="pdf_on_iframe" src="" height="100%" width="100%"></iframe>
        </div>
        <div class="modal-footer">

            <button type="button" class="preview-unit-pdf" id="btn-back" data-toggle="tooltip" data-placement="top" title="Back"   onclick="preview_pdf_model('back');"><img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/caret-circle-left-thin.svg')}}" width="35px" height="35px"></button>
            <button type="button" class="preview-unit-pdf" id="btn-addmore" data-toggle="tooltip" data-placement="top" title="Add More"  onclick="preview_pdf_model('addmore');" ><img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/plus-circle-icon-original.svg')}}" width="35px" height="35px"></button>
            <button type="button" class="preview-unit-pdf" id="btn-complete" data-toggle="tooltip" data-placement="top" title="Complete"       onclick="preview_pdf_model('complete');"><img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/caret-circle-right-icon-original.svg')}}" width="35px" height="35px"></button>

            <button type="button" class="preview-project-pdf upload-project-pdf" id="btn-addmore" data-toggle="tooltip" data-placement="top" title="Upload PDF"  onclick="preview_pdf_model('upload');" ><img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/upload-simple-icon-original.svg')}}" width="35px" height="35px"></button>
            <button type="button" class="preview-project-pdf" id="btn-complete" data-toggle="tooltip" data-placement="top" title="close"       onclick="preview_pdf_model('close');"><img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/caret-circle-right-icon-original.svg')}}" width="35px" height="35px"></button>

          <!-- <button type="button" class="" id="btn-continue" data-toggle="tooltip" data-placement="top" title="Continue"   onclick="preview_pdf_model('continue');"><img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/check-circle-icon-original.svg')}}" width="35px" height="35px"></button>
          <button type="button" class="" id="btn-download"  data-toggle="tooltip" data-placement="top" title="Download"   onclick="preview_pdf_model('download');"><img class="new mb-2" src="{{asset('/assets/icons/download-simple-icon-original.svg')}}" width="35px" height="35px"></button>
          <button type="button" class="" id="btn-sendemail" data-toggle="tooltip" data-placement="top" title="Send Mail"  onclick="preview_pdf_model('sendemail');" ><img class="new mb-2" src="{{asset('/assets/icons/set_creazilla/share-network-icon-original.svg')}}" width="35px" height="35px"></button>
          <button type="button" class="" id="btn-edit"     data-toggle="tooltip" data-placement="top" title="Edit"       onclick="preview_pdf_model('edit');"><img class="new mb-2" src="{{asset('/assets/icons/pencil-line-icon-original.svg')}}" width="35px" height="35px"></button> -->
        </div>
      </div>
    </div>
  </div>


<input type="hidden" id="read_only" value="{{$option}}">
<input type="hidden" id="project_count" value="{{ $project_count }}">
<input type="hidden" id="project_id" name="project_id" value="{{ $pid }}">
<input type="hidden" id="continue_flag" name="continue_flag" value="0">
@foreach($contact_email as $mail)
  <input type="hidden" id="contact_email" value="{{ $mail->email }}">
@endforeach

<!-- <input type="hidden" id="modelid" value=""> -->

<!-- Modal -->
@endsection
@section('scripts')
    @parent

    <script>
        let table = null;
        var airflow = null;
        var pressure = null;
        var w_Trin = null;
        var w_Hrin = null;
        var w_Tfin = null;
        var w_Hfin = null;        
        var model_id = null;
        var selected_model = null;
        var dTable = null;
        var logoImgDataURL = null;
        var renderImgData = null;
        var savedDoc = null;
        var savedPreviewDoc = null;
        var powerconsumption = null;
        var regulation = null;
        var unitsel = null;
        var psfp = null;
        var multiple_selection = false;
        var saveLoading = false;
        var saveLoadingId = null;
        var doc = null;
        var pagenumber = 1;
        var totalpage = 3;
        var isNew = true;
        var isView = false;
        var isEdit = false;
        var selected_unit = 0;
        var pdf_units = [];
        var isLoadImage = false;
        var loadImageId = null;
        var selected_price_id = null;
        var delivery_times = [];
        var selected_model_full_name = null;

        <?php
            $user_multiplier = auth()->user()->multiplier;
            if ($user_multiplier) {
                $user_multiplier = floatval(explode('_', $user_multiplier)[1]);
            } else {
                $user_multiplier = 1;
            }
        ?>
        <?php
            if($pid > 0){
                echo 'var units = JSON.parse(`'. $units .'`);';
            } else {
                echo 'var units = [];';
            }
        ?>
        var user_multiplier = parseFloat({{$user_multiplier}});
        var price_validity = "{{auth()->user()->delivery_time ?? ''}}"
        var unit_name = '';
        var edit_unit_name = '';
        var edit_unit_index = 0;


        $(document).ready(function() {

            $('.next-step-btn').removeClass('d-none');

            /** initialize project reference */
            const project_refer = $('#project_reference').val();                
            if (project_refer  == null || project_refer  == undefined ||  project_refer  == ''){
                var count = $('#project_count').val();
                var lastGeneratedNumber = count;
                lastGeneratedNumber++;
                var serialNumber = lastGeneratedNumber.toString().padStart(6, '0');
                $('#project_reference').val(serialNumber);
            }
            /** end project reference initializing */

            var read1 = $('#read_only').val();   
                if(read1 === 'readonly'){
                    $('.heading').hide();
                }else{
                    $('.heading').html('/'+' '+'project reference');
                }

            $('.units-table').css('visibility', 'visible');
            if (units.length > 0) {
                $('.btn-first-unit-next').hide();
            } else {
                $('.btn-first-unit-next').show();
            }

            // Create a new Image object
            var img = new Image();

            // Set the image source to your URL            
            img.src = "<?=isset($settings) ? asset('/uploads/' . $settings->image) : asset('img/logo_dark.png') ?>";
            // Wait for the image to load
            img.onload = function() {                
                // Create a canvas element
                var canvas = document.createElement('canvas');
                
                // Set the canvas dimensions to the image dimensions
                canvas.width = img.width;
                canvas.height = img.height;

                // Draw the image onto the canvas
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);

                // Get the data URL from the canvas
                logoImgData = {
                    dataURL: canvas.toDataURL(),
                    width: img.width,
                    height: img.height
                };

                // Use the data URL as needed

                canvas.remove();
            };

            //==========================================for unit selection=====================================================

            $('.compatiblewithuname').hide();
            $('.compatiblewithoutuname').hide();

            var unitName = $('#unit_name').val().trim();
            if (unitName == "") {
                $('.compatiblewithuname').hide();
                $('.compatiblewithoutuname').show();
            } 
            else {
                $('.compatiblewithoutuname').hide();
                $('.compatiblewithuname').show();
            }
        });

        $(document).on('click', '.next-step-btn', function(){
            var current_step = $(this).attr('step');
            if (current_step == 0 ) {
                document.querySelector('.nav-link[id="tab_unit_selection"]').click(); //unit tab click
            } else if (current_step == 1) {
                document.querySelector('.nav-link[id="tab_results_table"]').click();
            } else if (current_step == 3) { // after getting complete data
                completeProcess();
            }

        })

        $(document).on('click', '.prev-step-btn', function(){
            var current_step = $(this).attr('step');
            console.log(current_step);
            if (current_step == 1 ) {
                document.querySelector('.nav-link[id="tab_unit_home"]').click(); //project tab click
            } else if (current_step == 2) {
                document.querySelector('.nav-link[id="tab_unit_selection"]').click();
            }
        })

        $(document).on('click', '#tab_unit_home', function(){
            $('.step-action-btn').attr('step', 0);
            $('.prev-step-btn').addClass('d-none');
            $('#unitform').hide();
            $('#tab_results_table').addClass("disabled");

            $('.add-more-unit-btn').addClass('d-none');
            $('.preview-unit-pdf-btn').addClass('d-none');
        })
        
        $(document).on('click', '#tab_unit_selection', function(){
            $('.step-action-btn').attr('step', 1);
            $('.prev-step-btn').removeClass('d-none');
            
            $('.add-more-unit-btn').addClass('d-none');
            $('.preview-unit-pdf-btn').addClass('d-none');

            onNewUnit();
        })

        $(document).on('click', '#tab_results_table', function(){
            $('.step-action-btn').attr('step', 2);
            display_compatible_models(null);
        })
        

        function addMoreUnit() {
            $('input[name=continue_flag]').val(1);
            // onSaveNewUnit(1);
            storeProject();
        }

        function completeProcess() {
            $('input[name=continue_flag]').val(0);
            // onSaveNewUnit(0);
            storeProject();
        }
        
       

       

        function initBox() {
            $('.box .box-header .btn').on('click', function() {
                $(this).closest('.box').find('.box-body').slideToggle();
                $(this).find('i').toggleClass('fa-minus').toggleClass('fa-plus');
            });
        }
        
        $('a.image-item').on('click', function(e) {
            e.preventDefault();
            $(this).find('input')[0].checked = true;
        });

        function showSwalLoading(title = "@lang('Please wait...')", text = "@lang('Please wait...')") {
            Swal.fire({
                title: title,
                // text: text,                
                allowOutsideClick: false,
                showConfirmButton:false,
                showCancelButton:false,
                allowEscapeKey: false,
                willOpen: () => {
                    Swal.showLoading()
                }
            });   
        }
 
        function display_compatible_models(callback, _showLoading = true) {

           

            $('.heading').html('');
            $('.heading').html('/'+' '+'unit features');
            var compatiblewithoutuname = $('.compatiblewithoutuname').data('value');
            // alert(compatiblewithoutuname);
            // return;
           if(compatiblewithoutuname == 1){
              unit_name = $('#unit_name').val().trim();
              if (unit_name === ''){
                  document.querySelector('.nav-link[href="#unit_tab"]').click();
                  alert("@lang('Please type Unit Name')");
                  $('#unit_name').focus();
                  return;
              }
           }
            let n = units.length;
            if (!isView) {
                for (let i = 0; i < n; i++) {
                    if (!isNew && i == edit_unit_index) {
                        continue;
                    }
                    if (units[i].name == unit_name) {
                        alert(`Unit ${unit_name} already exists.`);
                        $('#unit_name').focus();
                        return;
                    }
                }
            }
            var params = {
                layout:$('#p_layout').val(),
                indoor:$('input[name=indoor]:checked').val(),
                ex1:$('input[name=ex]:checked').val().split('|')[1],
                ex2:$('input[name=ex]:checked').val().split('|')[0]
            };

            airflow = $.trim($('#p_airflow').val());
            if(airflow === '' || isNaN(parseFloat(airflow)) ||  parseFloat(airflow) <= 0) {
                alert("@lang('Airflow must be greater than 0')");
                $('#p_airflow').focus();
                return false;
            }
            params.airflow = parseFloat(airflow);

            pressure = $.trim($('#p_pressure').val());
            if(pressure === '' || isNaN(parseFloat(pressure)) ||  parseFloat(pressure) <= 0) {
                alert("@lang('Pressure must be greater than 0')");
                $('#p_pressure').focus();
                return false;
            }
            params.pressure = parseFloat(pressure);

            /** new fields; constrain and regular fan level */

            p_sfp = $.trim($('#p_sfp').val());
            if(p_sfp === '' || isNaN(parseFloat(p_sfp)) ||  parseFloat(p_sfp) <= 0) {
                alert("@lang('Performance Contrain must be greater than 0')");
                $('#p_sfp').focus();
                return false;
            }
            params.p_sfp = parseFloat(p_sfp);

            m_rfl = $.trim($('#m_rfl').val());
            if(m_rfl === '' || isNaN(parseFloat(m_rfl)) ||  parseFloat(m_rfl) <= 0) {
                alert("@lang('Max regulation fan level must be greater than 0')");
                $('#m_rfl').focus();
                return false;
            }
            params.m_rfl = parseFloat(m_rfl);

            /** */

            w_Trin = $.trim($('#p_w_Trin').val());
            console.log(w_Trin);
            if(w_Trin === '' || isNaN(parseFloat(w_Trin)) ||  parseFloat(w_Trin) < -20 ||  parseFloat(w_Trin) > 40) {
                alert("@lang('Exhaust Air temperature(°C) must be greater than or equal to -20 and less than or equal to 40')");
                $('#p_w_Trin').focus();
                return false;
            }
            params.Trin = parseFloat(w_Trin);

            w_Hrin = $.trim($('#p_w_Hrin').val());
            if(w_Hrin === '' || isNaN(parseFloat(w_Hrin)) ||  parseFloat(w_Hrin) < 5 || parseFloat(w_Hrin) > 98 ) {
                alert("@lang('Exhaust Air humidity(%) must be greater than or equal to 5 and less than or equal to 98')");
                $('#p_w_Hrin').focus();
                return false;
            }
            params.Hrin = parseFloat(w_Hrin);

            w_Tfin = $.trim($('#p_w_Tfin').val());
            if(w_Tfin === '' || isNaN(parseFloat(w_Tfin)) ||  parseFloat(w_Tfin) < -20 || parseFloat(w_Tfin) > 40) {
                alert("@lang('Fresh Air temperature(°C) must be greater than or equal to -20 and less than or equal to 40')");
                $('#p_w_Tfin').focus();
                return false;
            }
            params.Tfin = parseFloat(w_Tfin);

            w_Hfin = $.trim($('#p_w_Hfin').val());
            if(w_Hfin === '' || isNaN(parseFloat(w_Hfin)) ||  parseFloat(w_Hfin) < 5 ||  parseFloat(w_Hfin) > 98) {
                alert("@lang('Fresh Air humidity(%) must be greater than or equal to 5 and less than or equal to 98')");
                $('#p_w_Hfin').focus();
                return false;
            }
            params.Hfin = parseFloat(w_Hfin);

            // Todo: send and receive data from API
            if (_showLoading)
                showSwalLoading(); 

            $.ajax({
                method: 'GET',
                url: `{{route('admin.projects.get.models')}}`,
                data: params
            }).done(function (res) { 
                var result = res.result;
                
                $('#tab_results_table').removeClass("disabled");
                $('#tab_results_table').closest('ul.nav-tabs').find('.nav-link.active').removeClass('active');
                $('#tab_results_table').addClass('active');
                $('#tab_results_table').closest('.tabs').find('#project_tab').removeClass('active'); 
                $('#tab_results_table').closest('.tabs').find('#unit_tab').removeClass('active'); 
                $('#tab_results_table').closest('.tabs').find('#unit_feature_tab').addClass('active');
                if (_showLoading)
                    swal.close();
                if (result != null && result != 'Empty')
                    initTable(result, callback);
            });
        }
       
        function generateRandomNumber() {
            // Generate a 5-digit random number
            var randomNumber = Math.floor(10000 + Math.random() * 90000);
            // Get the current year and take the last two digits
            var currentYearLastTwoDigits = new Date().getFullYear().toString().slice(-2);
            // Combine the random number and current year digits
            return parseInt(randomNumber.toString() + currentYearLastTwoDigits);
        }

        function initTable(data, callback) {
            $('.models-tbl-container').empty();
            var $dt = $(`<table class="display compact project-table datatable-t1">\
                    <thead>\
                        <tr>\
                            <th>@lang("Model")</th>\
                            <th>@lang("Airflow")<br/>[m³/h]</th>\
                            <th>@lang("Pressure")<br/>[Pa]</th>\
                            <th>@lang("Power")<br/>[W]</th>\
                            <th>@lang("Efficiency")<br/>[%]</th>\
                            <th>@lang("Noise Power")<br/>[dB(A)]</th>\
                        </tr>\
                    </thead>\
                    <tbody>\                                  
                    </tbody>\
                </table>`);
            $('.models-tbl-container').html($dt);
            for(var i=0;i<Object.keys(data).length;i++) {
                var model = Object.keys(data)[i];
                var $row = $(`<tr class="uname" id="row_${i}" onclick="select_row(${i});" data-model="${data[model]["id"]}"></tr>`);
                $row.append(`<td data-id="${data[model]["id"]}" data-reg="${data[model]["Reg"]}">${model}</td>`);
                $row.append('<td>' + data[model]["Airflow"] + '</td>');
                $row.append('<td>' + data[model]["Pressure"] + '</td>');
                $row.append('<td>' + data[model]["Power"] + '</td>');
                $row.append('<td>' + data[model]["Efficiency"] + '</td>');
                $row.append('<td>' + data[model]["Lw"] + '</td>');
                $dt.find('tbody').append($row);

                // if(callback !== undefined && callback !== null) {
                //     callback(data[model]["id"], model, data[model]["Reg"]);
                // }
                // document.querySelector('.nav-link[href="#tab3"]').click();
                // $('.tabs.graph-tabs').addClass("hidden");
            }

            dTable = $('.models-tbl-container').find('table').DataTable({
                dom:'t',
                scrollY: '200px',
                paging: false,
                responsive: true,
                select: ('{{$option}}' === 'readonly') ? false :{
                    style: 'single' // or 'multi'
                },
                rowCallback: function(row, data) {
                    
                    if('{{$option}}' !== 'readonly') {
                        $(row).off();
                        $(row).on('click', function() {
                            var model = $(this).find('td:first-child').text();
                            selected_model = model;
                            var id = $(this).find('td:first-child').data('id');
                            var reg = $(this).find('td:first-child').data('reg');
                            // var reg = $(this).find('td:last-child').text();
                            /** call complete data when the row is clicked */
                            loadFromModel(id, reg, model);
                            // initPriceTable(id);
                        });
                    }                    
                }
            });
            var pid = parseInt('{{$pid}}');
            // if the pid > 0 and also if the unit is selected this should be called.
            if (pid > 0 && !isNew) {
                // var defaultRowIndex = 0;
                dTable.rows().every(function (rowIdx, tableLoop, rowLoop) {
                    let rowNode = this.node(); // Get the <tr> element
                    if ($(rowNode).data('model') === model_id) { // Check the data-model attribute
                        console.log('Found row with data-model=', model_id, rowNode);
                        this.row(rowIdx).select();
                        return false; // Stop iteration if found
                    }
                });


                // dTable.row(defaultRowIndex).select();
                var id_default = $('.selected td:first-child').data('id');
                var selectedRows = dTable.rows({ selected: true }).data();
                for(var i=0; i<selectedRows.length;i++){
                    var modelid_default = selectedRows[i][0];
                    var reg_default = $('.selected td:first-child').data('reg');
                    loadFromModel(id_default, reg_default, modelid_default);
                }
            }
            
        }

        function select_row(id) {
            // Get the total number of rows in the table
            var total_rows = $("#DataTables_Table_0 tr").length;
            
            console.log('row selected', id, total_rows)
            // // Iterate through each row
            // for (var i = 0; i < total_rows; i++) {
            //     // Check if the current row does not have the specified id
            //     if (i !== id) {
            //         // Hide the row
            //         $('#DataTables_Table_0 tbody tr#row_' + i).hide();
            //     }
            // }
        }
   
        $(document).on('click', '.accessories', function(){
            // initPriceTable(model_id)
        })

        function renderPriceTable(res, price_id=0, callback=null) {
            $('#pricetable').html("<table class='table display compact datatable-t1'>\
                            <thead>\
                                <tr>\
                                    <th></th>\
                                    <th>@lang('IMAGE')</th>\
                                    <th>@lang('ITEMCODE')</th>\
                                    <th>@lang('PRIMARY DESCRIPTION')</th>\
                                    <th>@lang('SECONDARY DESCRIPTION')</th>\
                                    <th>@lang('PRICE')</th>\
                                </tr>\
                            </thead>\
                            <tbody>\
                            </tbody>\
                        </table>");
                    if (res.length == 0){
                        $('#pricetable tbody').empty().append('<tr><td colspan="6"><p class="text-center">@lang("NO Data")</p></td></tr>');
                    } else {
                        var dt = $('#pricetable tbody');
                        dt.empty();
                        var i = 0;
                        for (row of res) {
                            var temprow = $('<tr></tr>');
                            if (row.description2 == null){
                                row.description2 = '';
                            }
                            if (row.id == price_id) {
                                temprow.append(`<td class="text-center"><input class="form-radio price-value" type="radio" name="price" value="${row.id}" checked></td>`);
                            } else {
                                temprow.append(`<td class="text-center"><input class="form-radio price-value" type="radio" name="price" value="${row.id}"></td>`);
                            }
                            let pos = row.image.indexOf('img');
                            if (pos != -1) {
                                temprow.append('<td>' + row.image + '</td>');
                            } else {
                                temprow.append('<td><img class="m-auto" src="' + APP_URL + '/uploads/price/'+ row.image + '" width="50" height="50"></td>');
                            }
                            temprow.append('<td>' + row.itemcode + '</td>');
                            temprow.append('<td>' + row.description + '</td>');
                            temprow.append('<td>' + row.description2 + '</td>');
                            let temp_price = row.price * row.multiplier * user_multiplier;
                            temprow.append('<td>' + temp_price.toFixed(2)  + ' €</td>');
                            dt.append(temprow);
                        }
                        @if($project != null)
                        if ($(`input[name="price"][value="${price_id}"]`).length != 0){
                            $(`input[name="price"][value="${price_id}"]`)[0].setAttribute("checked", true);
                        }
                        else {
                            $('input[name="price"]')[0].setAttribute("checked", true);
                        }
                        @else
                            $('input[name="price"]')[0].setAttribute("checked", true);
                        @endif
                    }
                    if(callback !== undefined && callback !== null) {
                        callback();
                    }
        }

        function initPriceTable(id, price_id = 0, callback) {
            if (id == null){
                id = 0;
            }
            $.ajax({
                type: 'GET',
                url: "{{route('admin.projects.get.modelprice')}}",
                data: {
                    id: id,
                },
                success: function(res) {
                    res = JSON.parse(res);
                    renderPriceTable(res)
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }

        function loadFromModel(id, reg, model, _showLoading = true) {

            model_id = id;
            if (_showLoading)
                showSwalLoading();
            $.ajax({
                method: 'GET',
                url: '{{route("admin.projects.get.completedata")}}',
                data: {
                    id: id,
                    reg: reg,
                    airflow: airflow,
                    pressure: pressure,
                    Trin: w_Trin,
                    Hrin: w_Hrin,
                    Tfin: w_Tfin,
                    Hfin: w_Hfin
                }
            }).done(function (res) {
                $('.next-step-btn').attr('step', 3);
                $('.add-more-unit-btn').removeClass('d-none');
                $('.preview-unit-pdf-btn').removeClass('d-none');
                if(res.result) {
                    var result = res.result.completedata;
                    powerconsumption = result[model].Power;
                    regulation = result[model].Reg;
                    unitsel = result[model].Unit_SEL;
                    psfp = result[model].PSFP;

                    selected_model_full_name = model;

                    if(isEdit) {
                        $('input#unit_name').val(model);
                    }

                    drawGraph(result[model]);

                    showaccessories(res.result.accessories)

                    renderPriceTable(res.result.prices)
                    
                    $('.dataTable tr.selected').removeClass('selected');
                    if(!$('.dataTable tr td[data-id=' + model_id + ']').closest('tr').hasClass('selected'))
                        $('.dataTable tr td[data-id=' + model_id + ']').closest('tr').addClass('selected');
                    if('{{$option}}' === 'readonly') {
                        $('a.btn-display-models').hide();
                    }
                    dTable.draw(false);
                    if (!isView) {
                        isView = true;
                        $('.btn-unit-save').show();
                    }
                    if (_showLoading)
                        swal.close();
                    
                    $(".feature-tab-enable").removeClass('d-none');
                    $('.feature-tab-disable').addClass('d-none');
                } else {
                    if (_showLoading)
                        swal.close();
                    Swal.fire({
                        title: 'There is no complete data',
                        icon: "error",
                        text: "It seems there are some errors in the server-side.",                
                        allowOutsideClick: true,
                        backdrop:true,
                        showConfirmButton:false,
                        showCancelButton:false,
                        allowEscapeKey: false,
                    })
                }
            });
        }

        function showaccessories(result) {
                $('#accessoriestable').html("<table class='table display compact datatable-t1'>\
                    <thead>\
                        <tr>\
                            <th></th>\
                            <th>@lang('ABR')</th>\
                            <th>@lang('Description')</th>\
                            <th>@lang('Calculable')</th>\
                        </tr>\
                    </thead>\
                    <tbody>\
                    </tbody>\
                </table>");
            if (result.data.length == 0){
                $('#accessoriestable tbody').empty().append('<tr><td colspan="4"><p class="text-center">@lang("NO Data")</p></td></tr>');
            } else {
                var dt = $('#accessoriestable tbody');
                dt.empty();
                var accessories = result.data;
                if (accessories) {
                    var i = 0;
                    for (row of accessories) {
                        var temprow = $('<tr></tr>');                           
                        temprow.append(`<td class="text-center">
                            <input class="form-checkbox accessories control-regulation" type="checkbox" name="control_regulation" value="${row.id}"></td>`);
                        temprow.append('<td class="text-uppercase">' + row.abbr + '</td>');
                        temprow.append('<td>' + row.description + '</td>');
                        temprow.append('<td>' + row.calculable + '</td>');
                        // if(row.multiple) {
                        //     var dropdown = `<select name="accessories_${row.abbreviation}">`;
                        //     for (option of row.options) {
                        //         dropdown += `<option value="${option}">${option}</option>`;
                        //     }
                        //     dropdown += `</select>`;
                        //     temprow.append(`<td>${dropdown}</td>`);
                        // } else {
                        //     temprow.append('<td></td>');
                        // }

                        dt.append(temprow);
                    }

                }
            }
        }

        

        function mergeArr(x_arr, y_arr) {
            if(Object.keys(x_arr).length != Object.keys(y_arr).length)
                return null;
            var res = [];
            for(var i = 0; i < Object.keys(x_arr).length; i++) {
                var key = Object.keys(x_arr)[i];
                res.push({x: x_arr[key], y: y_arr[key]});
            }
            return res;
        }

        function drawGraph(data) {
            $('.tabs.graph-tabs').removeClass("hidden");
            var pressure_graph_data = [mergeArr(data.Max_Airflows, data.Max_Pressures), mergeArr(data.Regulate_Airflows, data.Regulate_Pressures),mergeArr([data.Airflow], [data.Pressure])];
			var power_graph_data = [mergeArr(data.Max_Airflows, data.Max_Powers),mergeArr(data.Regulate_Airflows, data.Regulate_Powers), mergeArr([data.Airflow], [data.Power])];
            var psfp_graph_data = [mergeArr(data.Max_PSFP_af, data.Max_PSFP),mergeArr(data.Regulate_PSFP_af, data.Regulate_PSFP), mergeArr([data.Airflow], [data.Unit_SEL])];
            var efficiency_graph_data = [mergeArr(data.Max_Airflows, data.ThermodynamicData.Efficiencies),  mergeArr([data.Airflow], [data.ThermodynamicData.efficiency])];
            
            // Show Technical Informations
            showTechInfo(data.ThermodynamicData);

            document.getElementById('render').src = APP_URL + '/uploads/price/' + data.IND_VarHor_Ceiling_Img;
            renderImgData = null;
            var render_img = new Image();

            // Set the image source to your URL            
            // Wait for the image to load
            render_img.onload = function() {                
                // Create a canvas element
                var canvas1 = document.createElement('canvas');
                
                // Set the canvas dimensions to the image dimensions
                canvas1.width = render_img.width;
                canvas1.height = render_img.height;
                // Draw the image onto the canvas
                var ctx1 = canvas1.getContext('2d');
                ctx1.drawImage(render_img, 0, 0);

                // Get the data URL from the canvas
                renderImgData = {
                    dataURL: canvas1.toDataURL(),
                    width: render_img.width,
                    height: render_img.height,
                };
                canvas1.remove();
                isLoadImage = true;
            };
            render_img.src = document.getElementById('render').src;
            
            initGraph('pressure_graph', pressure_graph_data, "@lang('Airflow rate')[m³/h]", "@lang('External Static Pressure') [Pa] - EN 13141-7");
            initGraph('power_graph', power_graph_data, "@lang('Airflow rate') [m³/h]", "@lang('Power Supply') [W] - EN 13141-7");
			initGraph('psfp_graph', psfp_graph_data, "@lang('Airflow rate') [m³/h]", "@lang('Global') PSFP [Ws/m³] - EN 13779");
            initGraph('efficiency_graph', efficiency_graph_data, "@lang('Airflow rate') [m³/h]", "@lang('Efficiency') [%] - EN 13141-7");

            // Draw Histogram Noise Level - GRAPH
            $('#noise_level').empty();
            $('#noise_level').append('<div class="row"><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise1"></canvas></div><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise2"></canvas></div></div>');
            $('#noise_level').append('<div class="row"><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise3"></canvas></div><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise4"></canvas></div></div>');
            $('#noise_level').append('<div class="row"><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise5"></canvas></div><div class="col-md-6 col-lg-6 col-xl-4 p-3"><canvas height="200" id="g_noise6"></canvas></div></div>');

            initNoiseGraph('g_noise1', data.Soundtable.Breakout,  "@lang('Breakout noise level')", "@lang('Sound Level') [dB(A)] EN ISO 3744", "rgba(215, 38, 61, 0.8)");
            initNoiseGraph('g_noise2', data.Soundtable.Return, "@lang('Return in-duct noise level')", "@lang('Sound Level') [dB(A)]", "rgba(244, 96, 54, 0.8)");
            initNoiseGraph('g_noise3', data.Soundtable.Fresh, "@lang('Fresh in-duct noise level')", "@lang('Sound Level') [dB(A)] EN ISO 5136", "rgba(46,41,78,0.8)");
            initNoiseGraph('g_noise4', data.Soundtable.Supply, "@lang('Supply in-duct noise level')", "@lang('Sound Level') [dB(A)] EN ISO 5136", "rgba(27,153,139,0.8)");
            initNoiseGraph('g_noise5', data.Soundtable.Exhaust, "@lang('Exhaust in-duct noise level')", "@lang('Sound Level') [dB(A)] EN ISO 5136", "rgba(87,162,252,0.8)");

        }        

        function initGraph(obj_id, data, xLabel, yLabel) {
            var _colors = ['dodgerblue', 'dodgerblue', 'dodgerblue'];
            var datasets = data.map((xy_arr, index) => {
			
			if(index === data.length-1)
			{
			        return {
                    data: xy_arr,
                    pointBackgroundColor: _colors[index],
                    fill: false,
                    showLine: false,
                    pointRadius: 4,
                }
			}
			else
			{
                return {
                    data: xy_arr,
                    borderColor: _colors[index],
                    fill: false,
                    borderWidth: 2,
                    lineTension: 0,
					pointHoverRadius: 2,
					pointBorderWidth: 2,
                    pointRadius: 0,
                    borderDash: [index * 5]
                }
			}
            });
            var ctx = document.getElementById(obj_id).getContext('2d');
            var n = datasets.length;
            for (let i = 0; i < n; i++){
                if ( i != n-1){
                    datasets[i].borderWidth = 5;
                }
                else{
                    datasets[i].pointRadius = 7;
                }
            }
            // datasets[1].borderWidth = 5;
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: datasets,
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                color: 'black' // sets x-axis grid line color to black
                            },
                            type: 'linear', // make the scale linear
                            position: 'bottom',
                            scaleLabel: {
                                display: true,
                                labelString: xLabel,
                                fontSize: 15,
                                fontStyle: 'bold',
                                fontColor: 'black',
                            },
                            ticks: {
                                fontColor: 'black',
                                fontSize: 15 // Set font size for y axis here
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: 'black' // sets x-axis grid line color to black
                            },
                            type: 'linear', // make the scale linear
                            scaleLabel: {
                                display: true,
                                labelString: yLabel,
                                fontSize: 15,
                                fontStyle: 'bold',
                                fontColor: 'black',
                            },
                            ticks: {
                                fontColor: 'black',
                                fontSize: 16 // Set font size for y axis here
                            }
                        }]
                    }
                }
            });
        }       

        function initNoiseGraph(obj_id, data, xLabel, yLabel, color) {
            var ctx = document.getElementById(obj_id).getContext('2d');   
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            var myChart = new Chart(ctx, {
                type: 'bar',                
                data: {
                    labels: Object.keys(data),
                    datasets: [{
                        data: Object.values(data),
                        backgroundColor: Object.keys(data).map((k) => {
                            return k.indexOf("Hz") >= 0 ? gradient : color;
                        }),
                        borderColor: color,
                        borderWidth: Object.keys(data).map((k) => {
                            return k.indexOf("Hz") >= 0 ? 3 : 0;
                        }),
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    }, 
                    scales: {
                        xAxes: [{
                            gridLines: {
                                color: 'black' // sets x-axis grid line color to black
                            },
                            position: 'bottom',                            
                            scaleLabel: {
                                display: true,
                                labelString: xLabel,
                                fontSize: 15,
                                fontStyle: 'bold',
                                fontColor: 'black'
                            },
                            ticks: {
                                autoSkip: false,
                                maxRotation: 90,
                                minRotation: 0,
                                fontSize: 16,
                                fontStyle: 'bold',
                                fontColor: 'black'
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: 'black' // sets x-axis grid line color to black
                            },
                            ticks: {
                                beginAtZero: true,
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0,
                                fontSize: 16,
                                fontStyle: 'bold',
                                fontColor: 'black'
                            },
                            scaleLabel: {
                                display: true,
                                labelString: yLabel,
                                fontSize: 15,
                                fontStyle: 'bold',
                                fontColor: 'black',
                            }
                        }]
                    }
                },
            });
        }

        function showTechInfo(data) {
            var makeFormControl = (label, value) => {
                var id = "TI-" + $('#tech_info input.form-control').length;
                return `<div class="form-group row">
                    <label for="${id}" class="col-sm-6 col-form-label">${label}</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="${id}" value="${value}" readonly>
                    </div>
                </div>`;
            }            

            $('#tech_info').empty();
            $('#tech_info').append('<div class="row"><div class="col-md-6 col-xl-3"></div><div class="col-md-6 col-xl-3"></div></div>');
            $('#tech_info').find('.col-md-6:first-child').append(makeFormControl("@lang('Supply Temperature') [°C]", data.Supply_outlet_temp));
            $('#tech_info').find('.col-md-6:first-child').append(makeFormControl("@lang('Supply Humidity') [%]", data.Supply_outlet_rh));
            $('#tech_info').find('.col-md-6:first-child').append(makeFormControl("@lang('Exhaust Temperature') [°C]", data.Exhaust_outlet_temp));
            $('#tech_info').find('.col-md-6:first-child').append(makeFormControl("@lang('Exahust Humidity') [%]", data.Exhaust_outlet_rh));
            $('#tech_info').find('.col-md-6:first-child').append(makeFormControl("@lang('Water produced') [l/h]", data.water_produced));
            $('#tech_info').find('.col-md-6:first-child').append(makeFormControl("@lang('Return Temperature') [°C]", data.Return_inlet_temp));
            $('#tech_info').find('.col-md-6:first-child').append(makeFormControl("@lang('Return Humidity') [%]", data.Return_inlet_rh));
            $('#tech_info').find('.col-md-6:last-child').append(makeFormControl("@lang('Fresh Temperature') [°C]", data.Fresh_inlet_temp));
            $('#tech_info').find('.col-md-6:last-child').append(makeFormControl("@lang('Fresh Humidity') [%]", data.Fresh_inlet_rh));
            $('#tech_info').find('.col-md-6:last-child').append(makeFormControl("@lang('Efficiency') [%]", data.efficiency));
            $('#tech_info').find('.col-md-6:last-child').append(makeFormControl("@lang('Heat Recovery') [W]", data.heat_recovery));
            $('#tech_info').find('.col-md-6:last-child').append(makeFormControl("@lang('Sensible Heat') [W]", data.sensible_heat));
            $('#tech_info').find('.col-md-6:last-child').append(makeFormControl("@lang('Latent Heat') [W]", data.latent_heat));
        }

        function showMultipleModal() {
            $('#staticBackdrop').modal('show');
        }

        async function projectPDFPreview() {

            showSwalLoading();          
                
            var doc1 = await generatePDFforProject();
            

            var filename = 'REPORT_' + (new Date()).getTime() + '.pdf';         

            swal.close();

            let pdfB64String = doc1.output('dataurlstring', filename);

            $("#pdf_on_iframe").attr("src",pdfB64String);
            $('.preview-unit-pdf').hide();
            $('.upload-project-pdf').data('blob', doc1.output('blob'));
            $('.upload-project-pdf').data('filename', filename);
            $('#pdf_on_iframe_model').modal('show');

        }

        async function preview2PDF() {


            $("#btn-continue").removeClass('d-none');
            $("#btn-edit").removeClass('d-none');

            $("#btn-download").addClass('d-none');
            $("#btn-sendemail").addClass('d-none');
           
            showSwalLoading();          
                
            var doc1 = await generatePDFforUNIT();

            var filename = 'PREVIEW_REPORT_' + (new Date()).getTime() + '.pdf';
            savedPreviewDoc = {
                'filename': filename,
                'doc': doc1
            };

            $('.chart-tab-content .tab-pane.chart-tab-active').removeClass('chart-tab-active');
            swal.close();

            let pdfB64String = doc1.output('dataurlstring', savedPreviewDoc.filename);

            $("#pdf_on_iframe").attr("src",pdfB64String);
            $('.preview-project-pdf').hide();
            $('#pdf_on_iframe_model').modal('show');
            

        }

        function delay(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        }


        async function generatePDFforUNIT() {

            if (!renderImgData) return;

            /** this action is required because canvas elements load all graph within a short time */
            $('.chart-tab-content .tab-pane').addClass('chart-tab-active');

            await delay(200);

            const project_name = $('#project_name').val().trim();
            const project_desc = $('#project_desc').val().trim();
            const project_refer = $('#project_reference').val().trim();
            const creation_date = $('#create_date').val().trim();
            const modify_date = $('#modify_date').val().trim();


            if (project_name === ''){
                document.querySelector('.nav-link[href="#project_tab"]').click();
                alert("@lang('Please type Project Name')");
                $('#project_name').focus();
                return;
            }

            var selectedModel = $(dTable.row({selected: true}).data());
            var valueZero = selectedModel[0];
            
            var doc1 = new jsPDF('p', 'pt', [595, 842], true); // A4 Size
            var x = 20;
            var y = 20;

            doc1.addImage(logoImgData.dataURL, 'PNG', 30, y, logoImgData.width * 30 / logoImgData.height, 30, '', 'FAST');
            y += 30;

            doc1.setFontSize(7);
            doc1.setFontStyle('normal');
            y += 10;

            doc1.rect(20, y, 595 - 40, 25);
            y += 10;
            doc1.text("@lang('Project') : " + project_name  + ' - ' + project_desc, 30, y);
            doc1.text("@lang('Project reference') : " + project_refer, 595 / 3 + 10, y);
            doc1.text("@lang('Creation date') : " + creation_date, 595 * 2 / 3 + 10, y);
            y += 10;
            doc1.text("@lang('Last revistion') : " + modify_date, 30, y);
            doc1.text("@lang('SSW version') : {{$version ?? ''}}", 595 / 3 + 10, y);
            y += 10;

            var temp_y = y;

            doc1.rect(20, y, 595 - 40 - 350, 150);
            y += 10;
            doc1.setFontSize(10);
            doc1.setFontStyle('bold');
            doc1.text("@lang('SELECTED UNIT'): " + valueZero, 20 + (595 - 40 - 350) / 2, y, {align: 'center'});
            y += 5;
            doc1.line(20,  y, 595 - 20 - 350, y);
            doc1.setFontSize(7);
            doc1.setFontStyle('normal');
            var temp_price = $('input[name="price"]:checked').parents('tr').children('td');
            if (temp_price.length != 0){
                y += 10;
                doc1.text("@lang('Itemcode'):    " + temp_price[2].innerHTML, 30, y);
                y += 10;
                doc1.text("@lang('Description'): " + temp_price[3].innerHTML + (temp_price[4].innerHTML != '' ? ( ' - ' + temp_price[4].innerHTML) : ''), 30, y);
            }
            y += 10;
            doc1.addImage(renderImgData.dataURL, 'PNG', 20 + (595 - 40 - 350 - renderImgData.width / renderImgData.height * 100) / 2, y,  renderImgData.width / renderImgData.height * 100, 100, '', 'FAST');
            y += 110;

            
            doc1.rect(20, y, 595 - 40 - 350, 60);
            y += 10;
            doc1.setFontSize(10);
            doc1.setFontStyle('bold');
            doc1.text("@lang('WORKING POINT')", 20 + (595 - 40 - 350) / 2, y, {align: 'center'});
            y += 3;
            doc1.line(20, y, 595 - 20 - 350, y);
            // set font size to 10

            y += 10;
            doc1.setFontSize(8);
            doc1.setFontStyle('bold');
            doc1.text("@lang('Airflow data')", 30, y);
            y += 10;
            doc1.setFontSize(7);
            doc1.setFontStyle('normal');
            doc1.text("@lang('Airflow rate') : " + airflow +' [m³/h]', 30, y);
            doc1.text("@lang('Airflow pressure') : " + pressure +  ' [Pa]', 130, y);
            y += 10;
            doc1.setFontSize(7);
            doc1.setFontStyle('normal');
            doc1.text("@lang('Power consumption') : " + powerconsumption +' [W]', 30, y);
            doc1.text("@lang('Regulation') : " + regulation +  ' [%]', 130, y);
            y += 10;
            doc1.setFontSize(7);
            doc1.setFontStyle('normal');
            doc1.text("@lang('Unit SEL') : " + unitsel +' [J/m3]', 30, y);
            doc1.text("@lang('PSFP') : " + psfp +  ' [J/m3]', 130, y);


            doc1.rect(595 - 20 - 340, temp_y, 340, 180);
            temp_y += 10;
            doc1.setFontSize(10);
            doc1.setFontStyle('bold');
            doc1.text("@lang('THERMAL PERFORMANCE')", 595 - 20 - 340 / 2, temp_y, {align: 'center'});
            temp_y += 5;
            doc1.line(595 - 20 - 340, temp_y, 595 - 20, temp_y);

            // set font size to 10

            var drawGridText = (pdf, arrText, y) => {
                var x0 = 245, x1 = 345, x2 = 365, x3 = 405, x4 = 505, x5 = 525;
                pdf.text(arrText[0], x0, y);
                pdf.text(arrText[1], x1, y);
                pdf.text(arrText[2], x2, y);
                pdf.text(arrText[3], x3, y);
                pdf.text(arrText[4], x4, y);
                pdf.text(arrText[5], x5, y);
            };

            temp_y += 10;
            doc1.setFontSize(7);
            doc1.setFontStyle('bold');
            doc1.text("@lang('WINTER OPERATION')", 595 - 20 - 340 + 10, temp_y);
            doc1.text(`@lang('imbalance ratio') : 90 %`, 595 - 20 - 340 + 100, temp_y);

            doc1.setFontStyle('normal');
            temp_y += 10;
            drawGridText(doc1, ["@lang('Supply Temperature')", $('#TI-0').val(), '[°C]', "@lang('Fresh Temperature')", $('#TI-7').val(), '[°C]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Supply Humidity')", $('#TI-1').val(), '[%]', "@lang('Fresh Humidity')", $('#TI-8').val(), '[%]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Exhaust Temperature')", $('#TI-2').val(), '[°C]', "@lang('Efficiency')", $('#TI-9').val(), '[%]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Exahust Humidity')", $('#TI-3').val(), '[%]', "@lang('Heat Recovery')", $('#TI-10').val(), '[W]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Water produced')", $('#TI-4').val(), '[l/h]', "@lang('Sensible Heat')", $('#TI-11').val(), '[W]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Return Temperature')", $('#TI-5').val(), '[°C]', "@lang('Latent Heat')", $('#TI-12').val(), '[W]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Return Humidity')", $('#TI-6').val(), '[%]', '', '', ''], temp_y);

            temp_y += 10;
            doc1.setFontSize(7);
            doc1.setFontStyle('bold');
            doc1.text("@lang('SUMMER OPERATION')", 595 - 20 - 340 + 10, temp_y);
            doc1.text(`@lang('imbalance ratio') : 70 %`, 595 - 20 - 340 + 100, temp_y);

            doc1.setFontStyle('normal');
            temp_y += 10;
            drawGridText(doc1, ["@lang('Supply Temperature')", '', '[°C]', "@lang('Fresh Temperature')", '', '[°C]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Supply Humidity')", '', '[%]', "@lang('Fresh Humidity')", '', '[%]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Exhaust Temperature')", '', '[°C]', "@lang('Efficiency')", '', '[%]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Exahust Humidity')", '', '[%]', "@lang('Heat Recovery')", '', '[W]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Water produced')", '', '[l/h]', "@lang('Sensible Heat')", '', '[W]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Return Temperature')", '', '[°C]', "@lang('Latent Heat')", '', '[W]'], temp_y);
            temp_y += 10;
            drawGridText(doc1, ["@lang('Return Humidity')", '', '[%]', '', '', ''], temp_y);

            temp_y = y;

            y += 10;
            var _y = y;
            y += 20;
            doc1.line(20, y, 595 - 20, y);
            doc1.setFontSize(10);
            doc1.setFontStyle('bold');
            doc1.text("@lang('PERFORMANCE CURVES')", 30, y - 7);

            y += 10;
            var drawGraphOnPDF = (pdf, id, x, y) => {                    
                var canvas = document.getElementById(id);
                canvas.getContext('2d');
                var image = canvas.toDataURL('image/png', 1.0);
                var cw = 150;
                var cy = cw * canvas.height / canvas.width;
                pdf.addImage(image, 'PNG', x, y, cw, cy, '', 'FAST');
                return cy;
            }
            h1 = drawGraphOnPDF(doc1, 'pressure_graph', 105, y);
            h2 = drawGraphOnPDF(doc1, 'power_graph', 340, y);
            y = Math.max(h1, h2) + y + 10;

            h1 = drawGraphOnPDF(doc1, 'efficiency_graph', 105, y);
            h2 = drawGraphOnPDF(doc1, 'psfp_graph', 340, y);
            y = Math.max(h1, h2) + y + 10;
            
            doc1.rect(20, _y, 595 - 40, y - _y);

            y += 25;

            tempy = y - 15;

            doc1.line(20, y, 595 - 20, y);

            // set font size to 10
            doc1.setFontSize(10);
            doc1.setFontStyle('bold');
            doc1.text("@lang('ACOUSTIC CHARACTERISTICS')", 30, y - 3);
            y += 10;
            var h1 = drawGraphOnPDF(doc1, 'g_noise1', 20 + 105 / 4, y);
            var h2 = drawGraphOnPDF(doc1, 'g_noise2', 20 + 105 / 2 + 150, y);
            var h3 = drawGraphOnPDF(doc1, 'g_noise3', 20 + 105 / 4 * 3 + 150 * 2, y);
            var nextY = Math.max(h1, h2, h3) + y + 10;

            h1 = drawGraphOnPDF(doc1, 'g_noise4', 20 + 105 / 4, nextY);
            h2 = drawGraphOnPDF(doc1, 'g_noise5', 20 + 105 / 2 + 150, nextY);
            h3 = drawGraphOnPDF(doc1, 'g_noise6', 20 + 105 / 4 * 3 + 150 * 2, nextY);
            nextY = Math.max(h1, h2, h3) + nextY + 10;
            doc1.rect(20, tempy, 595 - 40, nextY - tempy);
            
            /** this action is required because canvas elements load all graph within a short time */
            $('.chart-tab-content .tab-pane.chart-tab-active').removeClass('chart-tab-active');

            return doc1;
        }

        function generatePDFforProject() {

            const project_name = $('#project_name').val().trim();
            const project_desc = $('#project_desc').val().trim();
            const project_refer = $('#project_reference').val();
            const creation_date = $('#create_date').val().trim();
            const modify_date = $('#modify_date').val().trim();


            var doc = new jsPDF('p', 'pt', [595, 842], true);

            y = 0;
            x = 20;
            doc.setDrawColor(0,0,0);

            y += 20;
            doc.rect(20, y, 595 - 40, 105);
            doc.line(595 / 2,  y, 595 / 2, y + 105);

            y += 10;
            doc.addImage(logoImgData.dataURL, 'PNG', 30, y, logoImgData.width * 30 / logoImgData.height, 30, '', 'FAST');
            y += 30;

            y += 10;
            // set font size to 10
            doc.setFontSize(10);
            doc.setFontStyle('bold');
            doc.text('{{$user->company_name ?? ""}}', 30, y);
            doc.text('{{$company->name}}', 595 / 2 + 10, y);

            y += 10;
            doc.setFontSize(7);
            doc.setFontStyle('normal');
            doc.text('{{$user->company_address ?? ""}}', 30, y);
            doc.text('{{$company->address}}', 595 / 2 + 10, y); //120
            doc.text("@lang('Contact')", 180, y);

            y += 10;
            doc.text('{{$user->company_post_code ?? ""}}, {{$user->company_city ?? ""}}', 30, y);
            doc.text('{{$settings->conname ?? ""}}', 180, y);

            y += 10;
            doc.text("{{$user->company_state ?? ''}}, {{$user->company_country ?? ''}}", 30, y);
            doc.text("@lang('Tel. No.') : {{$user->company_tel ?? ''}}", 180, y);
            doc.text("@lang('Tel. No.') : {{$company->phone}} ", 595 / 2 + 10, y);

            y += 10;
            doc.text("@lang('VAT No.') {{$user->company_vat ?? ''}}", 30, y);
            doc.text("@lang('Mobile') : {{$user->company_mobile ?? ''}}", 180, y);
            doc.text("@lang('VAT') : {{$company->VAT}}" , 595 / 2 + 10, y);

            y += 10;
            doc.text("{{$user->company_web_address ?? ''}}", 30, y);
            doc.text('{{$user->email ?? ""}}', 180, y);

            y += 10;
            doc.rect(20, y, 595 - 40, 25);
            doc.line(595 / 2, y, 595 / 2, y + 25);

            y += 10;
            doc.text("@lang('Recipient') : {{$contact->firstname}}  {{$contact->secondname}}", 30, y);
            doc.text("@lang('Mobile') : {{$contact->mobile}}" , 595 / 2 + 10, y);
            y += 10;
            doc.text("@lang('Tel. No.') : {{$contact->phone}}", 30, y);
            doc.text("@lang('Mail') : {{$contact->email}}", 595 / 2 + 10, y);

            y += 10;

            doc.rect(20, y, 595 - 40, 25);
            y += 10;
            doc.text("@lang('Project') : " + project_name  + (project_desc?' - ':'') + project_desc, 30, y);
            doc.text("@lang('Project reference') : " + project_refer, 595 / 3 + 10, y);
            doc.text("@lang('Creation date') : " + creation_date, 595 * 2 / 3 + 10, y);
            y += 10;
            doc.text("@lang('Last revistion') : " + modify_date, 30, y);
            doc.text("@lang('SSW version') : {{$version ?? ''}}", 595 / 3 + 10, y);

            y += 10;
            var startCharCode = 65;
            var i = 0;
            var totalprice = 0;
            console.log('on pdf generate', units);
            for (const unit of units) {
                y += 10;
                var char = String.fromCharCode(startCharCode + i);
                i++;
                doc.text(`${char}) ${unit.name}`, 30, y);
                y += 10;
                doc.text(`${unit.p_itemcode} - ${unit.p_desc}`, 50, y);
                doc.text(`€`, 250, y);
                let temp = unit.price.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                doc.text(`${temp}`, 300, y, { align: "right" });
                totalprice += unit.price;
                doc.text(`@lang('Delivery Time'):`, 320, y);
                if('undefined' != unit.delivery_time) {
                    temp = unit.delivery_time.split('_');
                    if (temp[1] == 1) {
                        doc.text(`${temp[0]} @lang('Days')`, 400, y, { align: "right" });
                    } else {
                        doc.text(`${temp[0]} @lang('Weeks')`, 400, y, { align: "right" });
                    }
                }
            }

            doc.setFontStyle('bold');
            y += 30;
            doc.text(`@lang('Total')`, 50, y);
            doc.text(`€`, 250, y);
            let temp = totalprice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            doc.text(`${temp}`, 300, y, { align: "right" });

            y += 30;
            // doc.text("@lang('Delivery Terms'):  {{$delivery_address->address ?? ''}},  {{$delivery_condition->cond ?? ''}}", 595 / 2, y, { align: "center" });

            y += 20;
            if (price_validity != '') {
                temp = price_validity.split('_');
                temp[1] = temp[1] == 1 ? 'Days' : 'Weeks';
                doc.text(`@lang('Price Validity'):  ${temp[0]} ${temp[1]}`, 595 / 2, y, { align: "center" });
            } else {
                doc.text(`@lang('Price Validity'):`, 595 / 2, y, { align: "center" });
            }

            var currentPageNumber = doc.internal.getNumberOfPages();
            doc.movePage(currentPageNumber, 1);

            var totalPages = doc.internal.getNumberOfPages();

            // Define the header and footer template function
            var headerFooterTemplate = function(pageNumber, pageCount) {
                // Footer
                doc.setFontSize(10);
                doc.text(pageNumber + " / " + pageCount, doc.internal.pageSize.width / 2 - 10, doc.internal.pageSize.height - 10, { align: "right" });
            };

            // Apply the header and footer template function to each page
            for (var i = 1; i <= totalPages; i++) {
                doc.setPage(i);
                var pageInfo = doc.internal.getCurrentPageInfo();
                
                // Add the footer text to each page
                headerFooterTemplate(pageInfo.pageNumber, totalPages);
            }

            return doc;
        }

        function Email_sending() {
            showSwalLoading();
            $('.chart-tab-content .tab-pane').addClass('chart-tab-active');
            setTimeout(() => {
                const project_name = $('#project_name').val().trim();
                const project_desc = $('#project_desc').val().trim();
                const project_refer = $('#project_reference').val().trim();
                const creation_date = $('#create_date').val().trim();
                const modify_date = $('#modify_date').val().trim();

                if (project_name === ''){
                    document.querySelector('.nav-link[href="#project_tab"]').click();
                    alert("@lang('Please type Project Name')");
                    $('#project_name').focus();
                    return;
                }
                // if (project_refer === ''){
                //     document.querySelector('.nav-link[href="#project_tab"]').click();
                //     alert("@lang('Please type Project Reference')");
                //     $('#project_reference').focus();
                //     return;
                // }

                var doc1 = generatePDFforUNIT()

                var filename = 'PREVIEW_REPORT_' + (new Date()).getTime() + '.pdf';
                savedPreviewDoc = {
                    'filename': filename,
                    'doc': doc1
                };
                var pdfBlob = new Blob([savedPreviewDoc.doc.output('blob')], { type: 'application/pdf' });
               
                //formData.append('pdf', savedPreviewDoc);
                var contact_email = $('#contact_email').val();
                var route12 = "{{route('admin.getpdf')}}";
                var formData = new FormData();
                formData.append('pdf', pdfBlob, filename);
                formData.append('filename', filename);
                formData.append('contact_email',contact_email);
                $.ajax({
                        type: 'POST',
                        url: route12,
                        data: formData,
                        headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                        processData: false,
                        contentType: false,
                        success: function(data) {
                            swal.close();
                            console.log('done');
                        },
                        error: function(xhr, status, error) {
                            console.log('not done');
                        }
                    });
                // savedPreviewDoc.doc.save(savedPreviewDoc.filename);
                // $('.chart-tab-content .tab-pane.chart-tab-active').removeClass('chart-tab-active');
                // swal.close();
            }, 100);
        }

        function download_pdf(){
            var selectedModel = $(dTable.row({selected: true}).data());
            var valueZero = selectedModel[0];
           
            showSwalLoading();
            $('.chart-tab-content .tab-pane').addClass('chart-tab-active');
            setTimeout(() => {
                
                var doc1 = generatePDFforUNIT();

                var filename = 'PREVIEW_REPORT_' + (new Date()).getTime() + '.pdf';
                savedPreviewDoc = {
                    'filename': filename,
                    'doc': doc1
                };

                savedPreviewDoc.doc.save(savedPreviewDoc.filename);
                $('.chart-tab-content .tab-pane.chart-tab-active').removeClass('chart-tab-active');
                swal.close();
            }, 100);

        }        

        function export2PDF(final = false) {
            console.log('export, pdf');
            $('.chart-tab-content .tab-pane').addClass('chart-tab-active');

                const project_name = $('#project_name').val().trim();
                const project_desc = $('#project_desc').val().trim();
                const project_refer = $('#project_reference').val();
                const creation_date = $('#create_date').val().trim();
                const modify_date = $('#modify_date').val().trim();         
                

                if (!final) {
                    doc = generatePDFforUNIT();
                    saveLoading = false;
                    $('#unitform').hide();
                    $('#tab_unit_selection').addClass('disabled');
                    $('#tab_results_table').addClass('disabled');
                    document.querySelector('.nav-link[href="#project_tab"]').click();
                    document.querySelector('#unit_tab').scrollTop = 0;
                    $('.btn-preview').hide();
                    $('.btn-unit-save').hide();
                } else {
                     //doc.addPage(595, 842);
                    storeProject()
                }

                $('.chart-tab-content .tab-pane.chart-tab-active').removeClass('chart-tab-active');
            // }, final ? 100 : 2000);
        }

        function getCenteredTextPos(pdf, x, w, text) {
            var textWidth = pdf.getStringUnitWidth(text) * pdf.internal.getFontSize();
            var textX = x + (w - textWidth) / 2;            
            return textX;
        }

        initBox();

        function pdfReport() {
            // alert(savedDoc);
            if(savedDoc) {
                savedDoc.doc.save(savedDoc.filename);
            }
        }

        $(document).on('click', '.btn-cancel', (e) => {
            $('#staticBackdrop').modal('hide');
            $('#staticBackdrop .modal-content-add-unit').show();
            $('#staticBackdrop .modal-content-save-delivery-time').hide();
        });
        $(document).on('click', '.btn-multiple', (e) => {
            if ($(e.target).hasClass('btn-primary')){
                onNewUnit();
                $('#staticBackdrop').modal('hide');
            } else {
                $('#staticBackdrop .modal-content-add-unit').hide();
                $('#staticBackdrop .modal-content-save-delivery-time').show();
                $('.units-delivery-time-table tbody').html('');
                for (row of pdf_units) {
                    $('.units-delivery-time-table tbody').append(`
                        <tr data-name="${row.name}">
                            <td>${row.name}</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="number" class="form-control delivery-time" value="1" min="1" max="320" maxlength="3">
                                    </div>
                                    <div class="col-md-9">
                                        <select class="form-control delivery-time-type">
                                            <option value="0">Please select Time Type</option>
                                            <option value="1">Days</option>
                                            <option value="2">Weeks</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `);
                }
            }
        });
        $(document).on('dblclick', '.unit-row td:first-child', (e) => {
            let temp_unit_name = e.target.innerText;
            onViewUnit(temp_unit_name);
        });
        
        function onNextDeliveryTime() {
            var temp_units_array = $('.units-delivery-time-table tbody tr');
            for (row of temp_units_array) {
                let temp_unit_name = $(row).attr('data-name');
                let temp_delivery_time = parseInt($(row).find('td:last-child .delivery-time').val());
                if (temp_delivery_time <= 0 || temp_delivery_time == '') {
                    $(row).find('td:last-child .delivery-time').focus();
                    alert("@lang('Please type Time')");
                    return;
                }
                let temp_delivery_time_type = $(row).find('td:last-child .delivery-time-type').val();
                if (temp_delivery_time_type == 0) {
                    $(row).find('td:last-child .delivery-time-type').focus();
                    alert("@lang('Please select Time Type')");
                    return;
                }
                pdf_units.map(row => {
                    if (row.name == temp_unit_name) {
                        row.delivery_time = `${temp_delivery_time}_${temp_delivery_time_type}`;
                    }
                });
            }
            $('#staticBackdrop').modal('hide');
            //$('.unitfeatures tbody tr').addClass('selected');
            // display_compatible_models(null);
            // onSaveOffer();
        }

        function onNewUnit() {
            $('.btn-first-unit-next').hide();
            $('#unitform').show();

            if ($('#project_reference').val()  == null || $('#project_reference').val()  == undefined ||  $('#project_reference').val()  == ''){
                var count = $('#project_count').val()
                var lastGeneratedNumber = count;
                lastGeneratedNumber++;
                var serialNumber = lastGeneratedNumber.toString().padStart(6, '0');
                $('#project_reference').val(serialNumber);
            }

            if ($('#project_name').val() == "" || $('#project_name').val() == null){
                var project_name = generateRandomNumber();
                $('#project_name').val(project_name);
            }

            isNew = true;
            isView = false;
            $('#tab_unit_selection').removeClass('disabled');
            // $('#tab_results_table').addClass('disabled');
            $('#tab_results_table').removeClass('disabled');
            document.querySelector('.nav-link[href="#unit_tab"]').click();
            document.querySelector('#unit_tab').scrollTop = 0;
            var standard_climatic_data = localStorage.getItem('standard_climatic_data');
            if(standard_climatic_data) {
                var jsonValues = JSON.parse(standard_climatic_data);
                console.log(jsonValues);
                var input_name_values = ['p_s_Hfin','p_s_Hrin','p_s_Tfin','p_s_Trin','p_w_Hfin','p_w_Hrin','p_w_Tfin','p_w_Trin'];
                input_name_values.forEach(name => {
                    $('input[name=' + name + ']').val(jsonValues[name]);
                })
                $('input.climatic-inner-data').attr({'disabled': true});
                $('input[name=climatic_standard]').prop("checked", true);
            } else {
                $('#p_w_Trin').val(20);
                $('#p_w_Hrin').val(60);
                $('#p_w_Tfin').val(-10);
                $('#p_w_Hfin').val(80);
            }
            $('.btn-unit-save').hide();
            $('#unit_name').focus();
            $('#p_layout').val('C');
            $(`input[name=indoor][value="I"]`).prop('checked', true);
            $(`input[name=ex][value="CF|LT"]`).prop('checked', true);
            $('#p_airflow').val(200);
            $('#p_pressure').val(50);


            // tab1_active
            // if ($('#unit_tab').hasClass('active')) {
            //     // $('html, body').animate({ scrollTop: $('#unit_tab').offset().top }, 'slow');
            //     $('main').css('overflow', 'hidden');
            //     // $(".w-full.my-3").remove();
            // }else{
            //     $('main').css('overflow', '');
            // }
        }

        // function onViewUnit(unit_name){
            
        // }

        function editOrViewUnit(unit_id, flag='edit') {
            if(flag == 'view') { // hide all store buttons
                $('.btn-unit-save').hide();
                isView = true;
            } else {
                isView = false;
            }

            $('.p_details').removeClass('d-none');
            var readonly = $('#read_only').val();
            isEdit = true;
            selected_unit = unit_id;

            $(`tr.unit-row`).removeClass('highlighted-unit');
            $(`tr.unit-row td[rowspan]`).addClass('exclude-highlight');
            let targetRow = $(`tr.unit-row[data-id="${unit_id}"]`);

            if (targetRow.length > 0) {
                targetRow.addClass('highlighted-unit'); // Example: Add a CSS class
            }

            if(readonly !== 'readonly'){
                $('#tab_unit_selection').removeClass('disabled');
                $('#tab_results_table').addClass('disabled');
                $('#unitform').show();
                document.querySelector('#unit_tab').scrollTop = 0;
                document.querySelector('.nav-link[href="#unit_tab"]').click();
                isNew = false;
                var temp_unit = null;
                units.map((row, index) => {
                    if (row.id == unit_id) {
                        temp_unit = row;
                        edit_unit_index = index;
                    }
                });
            // edit_unit_name = temp_unit.name;
                $('#unit_name').attr('disabled', false);
                $('#unit_name').val(temp_unit.name);
                $('#p_layout').val(temp_unit.layout);
                $(`input[name=indoor][value="${temp_unit.indoor}"]`).prop('checked', true);
                $(`input[name=ex][value="${temp_unit.ex2}|${temp_unit.ex1}"]`).prop('checked', true);
                $('#p_airflow').val(temp_unit.airflow);
                $('#p_pressure').val(temp_unit.pressure);

                $('#p_r_airflow').val(temp_unit.p_r_airflow);
                $('#p_r_pressure').val(temp_unit.p_r_pressure);

                $('#p_w_Trin').val(temp_unit.Trin);
                $('#p_w_Hrin').val(temp_unit.Hrin);
                $('#p_w_Tfin').val(temp_unit.Tfin);
                $('#p_w_Hfin').val(temp_unit.Hfin);

                $('#p_s_Trin').val(temp_unit.s_Trin);
                $('#p_s_Hrin').val(temp_unit.s_Hrin);
                $('#p_s_Tfin').val(temp_unit.s_Tfin);
                $('#p_s_Hfin').val(temp_unit.s_Hfin);
                
                $('#p_sfp').val(temp_unit.p_sfp);
                $('#m_rfl').val(temp_unit.m_rfl);

                model_id = temp_unit.modelId;

                if(flag == 'view') {
                    display_compatible_models(null);
                }

            } else {
                alert('Cant access edit')
            }
        }

        function onSaveUnit() {
            unit_name = $('#unit_name').val().trim();
            if (unit_name === ''){
                alert("@lang('Please type Unit Name')");
                $('#unit_name').focus();
                return;
            }
            if (!isNew) {
                units.map((row, index) => {
                    if (row.name == edit_unit_name) {
                        row.name = unit_name;
                        let temp_price = 0;
                        if ($('input[name="price"]:checked').length != 0) {
                            temp_price = parseFloat($('input[name="price"]:checked').parents('tr').find('td:last-child').html().slice(0, -2))
                        }
                        row.layout = $('#p_layout').val();
                        row.indoor = $('input[name=indoor]:checked').val();
                        row.ex1 = $('input[name=ex]:checked').val().split('|')[1];
                        row.ex2 = $('input[name=ex]:checked').val().split('|')[0];
                        row.airflow = airflow;
                        row.pressure = pressure;
                        row.Tfin = w_Tfin;
                        row.Trin = w_Trin;
                        row.Hfin = w_Hfin;
                        row.Hrin = w_Hrin;
                        row.modelId = model_id;
                        row.priceId = $('input[name="price"]:checked').length != 0 ? parseInt($('input[name="price"]:checked').val()) : 0;
                        row.price = temp_price;
                    }
                });
                if (edit_unit_name != unit_name) {
                    let temp_unit = $(`.unit-row[data-name="${edit_unit_name}"]`);
                    $(temp_unit).find('td')[0].innerText = unit_name;
                    $(temp_unit).find('td')[1].innerHTML = '\
                        <button type="button" class="btn btn-primary" onclick="onViewUnit(`' + unit_name +'`)">View</button>\
                        <button type="button" class="btn btn-success" onclick="onEditUnit(`' + unit_name +'`)">Edit</button>\
                        <button type="button" class="btn btn-danger" onclick="onDeleteUnit(`' + unit_name +'`)">Delete</button>\
                        <button type="button" class="btn btn-info" onclick="onAddOffer(`' + unit_name +'`)">Add Offer</button>\
                    ';
                    $(temp_unit).attr('data-name', unit_name);
                }
            } else {
                if (units.length == 0) {
                    $(`.units-table tbody`).html('\
                        <tr class="unit-row" data-name="'+ unit_name + '">\
                            <td>\
                            ' + unit_name +'\
                            </td>\
                            <td align="center">\
                                <button type="button" class="btn btn-primary" onclick="onViewUnit(`' + unit_name +'`)">View</button>\
                                <button type="button" class="btn btn-success" onclick="onEditUnit(`' + unit_name +'`)">Edit</button>\
                                <button type="button" class="btn btn-danger" onclick="onDeleteUnit(`' + unit_name +'`)">Delete</button>\
                                <button type="button" class="btn btn-info" onclick="onAddOffer(`' + unit_name +'`)">Add Offer</button>\
                            </td>\
                        </tr>\
                    ');
                    $(`.units-table tfoot`).html(`
                        <tr>
                            <td colspan="2" align="center">
                                <a class="btn btn-success btn-block button-boxed" onclick="onNewUnit()">
                                    <i class="fa fa-plus"></i>
                                    <small>@lang('Add Unit')</small>
                                </a>
                            </td>
                        </tr>
                    `);
                } else {
                    $(`.units-table tbody`).append('\
                        <tr data-name="'+ unit_name + '">\
                            <td>\
                            ' + unit_name +'\
                            </td>\
                            <td align="center">\
                                <button type="button" class="btn btn-primary" onclick="onViewUnit(`' + unit_name +'`)">View</button>\
                                <button type="button" class="btn btn-success" onclick="onEditUnit(`' + unit_name +'`)">Edit</button>\
                                <button type="button" class="btn btn-danger" onclick="onDeleteUnit(`' + unit_name +'`)">Delete</button>\
                                <button type="button" class="btn btn-info" onclick="onAddOffer(`' + unit_name +'`)">Add Offer</button>\
                            </td>\
                        </tr>\
                    ');
                }
                let temp_price = 0;
                if ($('input[name="price"]:checked').length != 0) {
                    temp_price = parseFloat($('input[name="price"]:checked').parents('tr').find('td:last-child').html().slice(0, -2))
                }
                units.push({
                    name: $('#unit_name').val(),
                    layout: $('#p_layout').val(),
                    indoor: $('input[name=indoor]:checked').val(),
                    ex1: $('input[name=ex]:checked').val().split('|')[1],
                    ex2: $('input[name=ex]:checked').val().split('|')[0],
                    airflow: airflow,
                    pressure: pressure,
                    Tfin: w_Tfin,
                    Trin: w_Trin,
                    Hfin: w_Hfin,
                    Hrin: w_Hrin,
                    modelId: model_id,
                    priceId: $('input[name="price"]:checked').length != 0 ? parseInt($('input[name="price"]:checked').val()) : 0,
                    price: temp_price,
                });
            }
            
            if (units.length == pdf_units.length) {
                $('.btn-offer-save').show();
            } else {
                $('.btn-offer-save').hide();
            }
            $('#unitform').hide();
            $('#tab_unit_selection').addClass('disabled');
            $('#tab_results_table').addClass('disabled');
            document.querySelector('.nav-link[href="#project_tab"]').click();
            $('.btn-unit-save').hide();
            $('.btn-preview').hide();
        }

        function onDeleteUnit(unit_name){
            var readonly = $('#read_only').val();
            if(readonly !== 'readonly'){
            units.map((row, index) => {
                if (row.name == unit_name) {
                    units.splice(index, 1);
                    $(`.units-table tbody tr[data-name="${unit_name}"]`).remove();
                }
            });
            if (units.length == pdf_units.length && pdf_units.length > 0) {
                $('.btn-offer-save').show();
            } else {
                $('.btn-offer-save').hide();
            }
            if (units.length == 0) {
               // $('.btn-first-unit-next').show();
                //$('.units-table tfoot').html('');
            }
            $('#unitform').hide();
            $('#tab_unit_selection').addClass('disabled');
            $('#tab_results_table').addClass('disabled');
            document.querySelector('.nav-link[href="#project_tab"]').click();
            document.querySelector('#unit_tab').scrollTop = 0;
            $('.btn-preview').hide();
            $('.btn-unit-save').hide();
            }else{
                alert('Cant access delete');
            }
        }

        function onSaveOffer() {
             export2PDF(true);
            $('.btn-offer-save').hide();
        }
        

       


        $('.compatiblewithoutuname').on('dblclick',function(){
            $('.compatiblewithuname').show();
            $('.compatiblewithoutuname').hide();
        })
        
        var read = $('#read_only').val(); 
       
        // if(read == 'readonly'){
    
        //    $('.nav-link').click(function(){
        //       var title = $(this).data('title1');

        //      $('.heading').html("");
        //    })
        // }else{
        //     $('.nav-link').click(function(){
        //       var title = $(this).data('title1');
        //     //   alert(title);
        //      $('.heading').html('/'+' '+title);
        //    })
        
        // }
        
        $('.nav-item a').click(function(){
            var title = $(this).data('title1');
            if(title == "" || title == undefined ){
                
            }else{
                $('.heading').html('/'+' '+title);
                
            }
            
        })
       
        /**======================================= ***************** ==================================== */

        /** if the unit selection tab is clicked, unitname and preview button should be disappeared*/
        $('#tab_unit_selection').on('click', function(){
            $('.btn-preview').hide();
            $('.tabs.graph-tabs').addClass("hidden");
        })

        function preview_pdf_model(action){

            switch (action) {
                case 'back':
                    document.querySelector('.nav-link[id="tab_unit_selection"]').click();
                    break;
                case 'addmore':
                    addMoreUnit();
                    break;
                case 'complete':
                    completeProcess();
                    break;
                case 'upload':
                    uploadPDF();
                    break;


                default:
                    break;
            }

            close_model();
          
        }

        function onSaveNewUnit() {
            $('#staticBackdrop').modal('show');
            $('#staticBackdrop .modal-content-add-unit').hide();
            $('#staticBackdrop .modal-content-save-delivery-time').show();
            $('.units-delivery-time-table tbody').html('');

            units.map(unit => {
                var delivery_time_type = 0;
                var delivery_time =1;
                if(unit.delivery_time) {
                    console.log(unit.delivery_time);
                    var d = unit.delivery_time.split('_');
                    if (d.length > 1) {
                        delivery_time_type = d[1];
                        delivery_time = d[0];
                    }
                }
                $('.units-delivery-time-table tbody').append(`
                    <tr data-name="${unit.name}" data-id="${unit.id}" class="units-delivery-time-table-tr">
                        <td>${unit.name}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="number" class="form-control delivery-time" value="${delivery_time}" min="1" max="320" maxlength="3">
                                </div>
                                <div class="col-md-9">
                                    <select class="form-control delivery-time-type">
                                        <option value="0" selected disabled>Please select Time Type</option>
                                        <option value="1" ${delivery_time_type == 1?'selected': ''}>Days</option>
                                        <option value="2" ${delivery_time_type == 2?'selected': ''}>Weeks</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr>
                `)
            })

            return;
        }

        function onSaveDeliveryTime() {

            console.log($('.units-delivery-time-table-tr'));

            var temp_units_array = $('.units-delivery-time-table tbody tr');
            delivery_times = [];
            for (row of temp_units_array) {
                let temp_unit_name = $(row).attr('data-name');
                let temp_unit_id = $(row).data('id');
                let temp_delivery_time = parseInt($(row).find('td:last-child .delivery-time').val());
                if (temp_delivery_time <= 0 || temp_delivery_time == '') {
                    $(row).find('td:last-child .delivery-time').focus();
                    alert("@lang('Please type Time')");
                    return;
                }
                let temp_delivery_time_type = $(row).find('td:last-child .delivery-time-type').val();
                if (temp_delivery_time_type == 0 || !temp_delivery_time_type) {
                    $(row).find('td:last-child .delivery-time-type').focus();
                    alert("@lang('Please select Time Type')");
                    return;
                }
                delivery_times.push({
                    "id" : temp_unit_id,
                    "delivery_time" : `${temp_delivery_time}_${temp_delivery_time_type}`
                });
                pdf_units.map(row => {
                    if (row.name == temp_unit_name) {
                        row.delivery_time = `${temp_delivery_time}_${temp_delivery_time_type}`;
                    }
                });
            }

            $('#staticBackdrop').modal('hide');

            // storeProject();
            storeDeliveryTime();
        }

        function storeDeliveryTime() {
            
            showSwalLoading();

            var formData = new FormData();
            formData.append('unit_delivery_times',JSON.stringify(delivery_times));
            
            $.ajax({
                type: 'POST',
                url: `{{route('admin.projects.store.deliverytime')}}`,
                data: formData,
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                processData: false,
                contentType: false,
                success: function(data) {
                    swal.close();
                    if(data == 'success') {
                        location.reload();
                    } else {                        
                        alert('An error occurred while updating data., Please try again later');
                    }
                },
                error: function(xhr, status, error) {
                    swal.close();
                    console.log('An error occurred while updating data.');
                }
            });
        }


        async function storeProject() {
            
            showSwalLoading();

            var continue_flag = $('input[name=continue_flag]').val();

            console.log('continue flag', continue_flag);

            var formData = new FormData();

            formData.append('id', $('#project_id').val());
            // formData.append('uid', '{{$uid}}');
            formData.append('company', '{{$company->id}}');
            formData.append('contact', '{{$contact->id}}');

            var _name = $('#project_name').val();
            if(_name == '') {
                _name = 'P_' + $('#project_reference').val();
            }
            formData.append('name', _name);

            formData.append('description', $('#project_desc').val());
            formData.append('reference', $('#project_reference').val());

            // if(continue_flag == 0) { // if final step, project pdf should be generated
            //     var doc = generatePDFforProject();
            //     var filename = 'REPORT_' + (new Date()).getTime() + '.pdf';
            //     savedDoc = {
            //         'filename': filename,
            //         'doc': doc
            //     };
            //     formData.append('pdf', doc.output('blob'), filename);
            // }

            /** generate unit pdf  */

            formData.append('unit_id',selected_unit);

            var doc_unit = await generatePDFforUNIT();            
            var filename_unit = 'UNIT_' + (new Date()).getTime() + '.pdf';
            formData.append('unit_pdf', doc_unit.output('blob'), filename_unit);

            // formData.append('units', JSON.stringify(pdf_units));
            var unit_name = $('#unit_name').val();
            if (unit_name == '' && selected_model_full_name) {
                unit_name = selected_model_full_name;
            }
            formData.append('unit_name',unit_name);

            formData.append('layout',$('#p_layout').val());
            formData.append('indoor',$("input[name='indoor']:checked").val());
            formData.append('ex1',$('input[name=ex]:checked').val().split('|')[1]);
            formData.append('ex2',$('input[name=ex]:checked').val().split('|')[0]);
            formData.append('airflow',$('#p_airflow').val());
            formData.append('pressure',$('#p_pressure').val());

            formData.append('p_r_airflow',$('#p_r_airflow').val());
            formData.append('p_r_pressure',$('#p_r_pressure').val());

            formData.append('Tfin',$('#p_w_Tfin').val());
            formData.append('Trin',$('#p_w_Trin').val());
            formData.append('Hfin',$('#p_w_Hfin').val());
            formData.append('Hrin',$('#p_w_Hrin').val());
            
            formData.append('s_Tfin',$('#p_s_Tfin').val());
            formData.append('s_Trin',$('#p_s_Trin').val());
            formData.append('s_Hfin',$('#p_s_Hfin').val());
            formData.append('s_Hrin',$('#p_s_Hrin').val());

            formData.append('p_sfp',$('#p_sfp').val());
            formData.append('m_rfl',$('#m_rfl').val());

            formData.append('modelId',model_id);
            formData.append('unit_delivery_time',delivery_times[0]);

            formData.append('thumbnail', document.getElementById('render').src);
            formData.append('standard_climatic',$('input[name=climatic_standard]').prop('checked')?1:0);

            $(document).find('input.price-value').map((index, ele) => {
                if($(ele).prop('checked')) {
                    formData.append('priceId',$(ele).val());
                }
            })

            var accessories = [];

            $(document).find('input.accessories').map((index, ele) => {
                if($(ele).prop('checked')) {
                    accessories.push($(ele).val());
                }
            })

            formData.append('accessories',accessories.join(','));

            
            $.ajax({
                type: 'POST',
                url: `{{route('admin.projects.store.project')}}`,
                data: formData,
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                processData: false,
                contentType: false,
                success: function(data) {
                    swal.close();
                    $('input[name=project_id]').val(data.result.project_id);
                    Swal.fire({
                        title: "@lang('Saved...')",
                        imageUrl: $('img#render').attr('src'),
                        imageHeight: 300,
                        imageAlt: "A tall image",
                        allowOutsideClick: false,
                        showConfirmButton:true,
                        showCancelButton:false,
                        allowEscapeKey: false,
                        confirmButtonText: "Continue"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if (continue_flag == 0) {
                                var reload = `{{route('admin.projects')}}`;
                                var pid = parseInt('{{$pid}}');
                                if (pid > 0) {
                                    reload = `{{route('admin.projects.detail', ['pid' => $pid, 'cid' => $cid, 'uid' => $uid])}}`;
                                }
                                location.href = reload;
                            } else {
                                selected_unit = 0;
                                document.querySelector('.nav-link[id="tab_unit_selection"]').click();
                            }
                        }
                    });
                    console.log('File uploaded successfully.');
                },
                error: function(xhr, status, error) {
                    swal.close();
                    console.log('An error occurred while uploading the file.');
                }
            });
        }

        function addNewUnitInProject() {
            $('.p_details').removeClass('d-none');
            onNewUnit();
        }

        function addDeliveryTime() {
            onSaveNewUnit();
        }

        $('input[name=climatic_standard]').on('change', function(){
            if($(this).prop('checked')){
                $('input.climatic-inner-data').attr({'disabled': true});

                const inputs = document.querySelectorAll(".climatic-inner-data");

                const jsonValues = {};

                inputs.forEach(input => {
                    const name = input.name;
                    const value = input.value;
                    jsonValues[name] = value;
                });

                localStorage.setItem('standard_climatic_data', JSON.stringify(jsonValues));

            } else {
                Swal.fire({
                        text:'@lang("This will remove saved standard data, are you sure to remove that?")',
                        icon: 'warning',
                        allowOutsideClick: false,
                        showConfirmButton:true,
                        showCancelButton:true,
                        allowEscapeKey: false,
                        confirmButtonText: "Yes"
                    }).then((result) => {
                        if (result.isConfirmed) {                           
                            $('input.climatic-inner-data').attr({'disabled': false});
                            localStorage.removeItem('standard_climatic_data');
                        } else {
                            $('input[name=climatic_standard]').prop("checked", true);
                        }
                    });
            }
        })

        function close_model(){
            $("#pdf_on_iframe_model").modal('hide');
        }

        function uploadPDF() {

            showSwalLoading();

            var formData = new FormData();
            var blob = $('.upload-project-pdf').data('blob');
            var filename = $('.upload-project-pdf').data('filename');
            formData.append('pdf', blob, filename);
            
            formData.append('id', $('#project_id').val());


            $.ajax({
                type: 'POST',
                url: `{{route('admin.projects.upload.project-pdf')}}`,
                data: formData,
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                processData: false,
                contentType: false,
                success: function(data) {
                    swal.close();
                    // $('input[name=project_id]').val(data.result.project_id);
                    Swal.fire({
                        title: "@lang('Uploaded...')",
                        icon: 'success',
                        allowOutsideClick: false,
                        showConfirmButton:true,
                        showCancelButton:false,
                        allowEscapeKey: false,
                        confirmButtonText: "Continue"
                    }).then((result) => {
                    //     if (result.isConfirmed) {
                    //         if (continue_flag == 0) {
                    //             location.href = `{{route('admin.projects')}}`;
                    //         } else {
                    //             document.querySelector('.nav-link[id="tab_unit_selection"]').click();
                    //         }
                    //     }
                    });
                    // console.log('File uploaded successfully.');
                    
                   

                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: "@lang('Something Wrong, Please try again later!')",
                        icon: 'error',
                        allowOutsideClick: false,
                        showConfirmButton:true,
                        showCancelButton:false,
                        allowEscapeKey: false,
                        confirmButtonText: "Continue"
                    })
                    swal.close();
                    console.log('An error occurred while uploading the file.');
                }
            });
        }
  
    </script>
@endsection