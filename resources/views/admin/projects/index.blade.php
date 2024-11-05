@extends('layouts.admin')
@section('content')
<style>
    .action_btn{
        display:flex;
        align-items:center;
        justify-content:space-around;
        height:38px;
    }
    .dataTables_paginate{
        display:none;
    }
    .next_previous{
        display: flex;
        justify-content: end;
        align-items: center;
        margin-top: 8px;
        margin-right: 8px;
    }
    .customPreviousBtn{
       rotate: -90deg;
    }
</style>
<div class="main-card projects">
    <div class="body">        
        <div class="row">
            <div class="raw col-sm-12">
             <div class="rowcontant">   
            <div class="col-md-6">
                <label for="keyword_project" class="text-xs">@lang('Find a project')</label>
                <div class="form-group">
                    <div class="input-group">
                        <input type="search" id="keyword_project" name="keyword_project" class="form-control" placeholder="@lang('Project name, reference, description...')">
                        <div class="input-group-append">
                            <button class="btn btn-info" type="button" onclick="searchProject()">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <label class="text-xs">@lang('Statuts filter')</label>
                <div class="form-group check">
                    <label class="label-chb"><input type="checkbox" name="p_status" value="in progress"/>@lang('In progress')</label>
                    <label class="label-chb"><input type="checkbox" name="p_status" value="won"/>@lang('Won')</label>
                    <label class="label-chb"><input type="checkbox" name="p_status" value="lost"/>@lang('Lost')</label>
                </div>
                
               
            </div>
            </div> 
            <div class="add-new d-flex"> 
                    <a href="#" class="multi-delete-projects d-none">
                        <img class="new" src="{{asset('assets/icons/trash-icon-original.svg')}}" width="30px" height="30px"/>
                    </a>
                    <a href="{{route('admin.projects.profile')}}" class="">
                        <img class="new" src="{{asset('assets/icons/plus-circle-icon-original.svg')}}" width="30px" height="30px"/>
                    </a>
            </div>
            </div>
        </div>
        
        <div class="w-full table-responsive">
            <table class="display compact project-table datatable-project">
                <thead>
                    <tr>
                        <th>@lang('Statuts')</th>
                        <th>@lang('Customer')</th>
                        <th>@lang('Contact')</th>
                        <th>@lang('Project Name')</th>
                        <th>@lang('Reference')</th>
                        <th>@lang('Description')</th>
                        <th>@lang('Modification Date')</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(isset($project_list) && is_array($project_list)) {
                        $arr_status = [
                            ['text' => "@lang('In progress')", 'class' => 'primary'],
                            ['text' => "@lang('Won')", 'class' => 'success'],
                            ['text' => "@lang('Lost')", 'class' => 'danger'],
                        ];
                        foreach($project_list as $p) {
                            ?>
                            <tr id="mytr_{{$p->id}}" class="getdata" data-id="{{$p->id}}" data-company="{{$p->company}}" data-contact="{{$p->contact}}">
                                <td class="nowrap" data-value="{{$arr_status[$p->status]['text']}}">
                                    <select class="form-control" onchange="onProjectStatusChange({{$p->id}}, this)">
                                        <option value="0" {{$p->status == 0 ? "selected" : ""}}>@lang('In progress')</option>
                                        <option value="1" {{$p->status == 1 ? "selected" : ""}}>@lang('Won')</option>
                                        <option value="2" {{$p->status == 2 ? "selected" : ""}}>@lang('Lost')</option>
                                    </select>                                    
                                </td>
                                <td class="nowrap">{{$p->customer}}</td>
                                <td class="nowrap">Mr. {{$p->contact_name}}</td>
                                <td class="nowrap">{{$p->project_name}}</td>
                                <td class="nowrap">{{$p->reference}}</td>
                                <td class="nowrap">{{$p->description}}</td>
                                <td class="nowrap">{{date("m/d/Y", strtotime($p->updated_at))}}</td>
                                <td class="action_btn align-items-center ">
                                    <a class="" onclick="goToDetail('{{$p->id}}','{{$p->company}}','{{$p->contact}}')">
                                        <img class="new" src="{{asset('assets/icons/pencil-line-icon-original.svg')}}" width="25px" height="25px"/>
                                    </a>
                                    <a class="" onclick="del('{{$p->id}}')">
                                        <img class="new" src="{{asset('assets/icons/trash-icon-original.svg')}}" width="25px" height="25px"/>
                                    </a>
                                    <input type="checkbox" name="deletable_projects[]" class="deletable-projects" value="{{$p->id}}" />
                                </td>
                            </tr>
                            <?php
                        }
                    }                    
                    ?>
                </tbody>
            </table>
            <div class="next_previous">
              <button id="customPreviousBtn" style="rotate: -90deg;"><img class="new mb-2" src="{{asset('assets/icons/arrow-circle-up-icon-original.svg')}}" width="35px" height="35px"/></button>
              <button id="customNextBtn" style="margin-top:8px;"><img class="new mb-2" src="{{asset('assets/icons/nextArrow.png')}}" width="35px" height="35px"/></button>
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
    @parent
    <script>
        var dTable;
        $(function () {
            $.extend(true, $.fn.dataTable.defaults, {
                dom:'tp',
                orderCellsTop: true,
                order: [[ 1, 'desc' ]],
                //paging: false,
                pageLength: 10,
                // responsive: true,
                select: {
                    style: 'single', // or 'multi',
                    selector: 'td:not(:first-child)'
                },
                rowCallback: function(row, data) {
                    $(row).on('dblclick', function() {
                        var selectedRow = dTable.row('.selected');
                        console.log(selectedRow);
                        if (selectedRow.any()) {  // check if any row is currently selected
                            selectedRow.deselect();  // deselect the selected row
                        }
                        $(row).toggleClass('selected');

                        const pid = $(row).data('id');
                        const cid = $(row).data('company');
                        const uid = $(row).data('contact');
                        // alert(pid);
                        // alert(cid);
                        // alert(uid);
                        location.href = '{{route('admin.projects.detail')}}/' + pid + '/' + cid + '/' + uid + '?o=readonly';
                        
                    });
                }
            });
            $('#customPreviousBtn').on('click', function () {
        dTable.page('previous').draw('page');
    });

    // Custom Next button functionality
    $('#customNextBtn').on('click', function () {
        dTable.page('next').draw('page');
    });

            // Add custom search function
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {                    
                    var searchValue1 = $('#keyword_project').val().toLowerCase();
                    
                    let selected_status = [];
                    $('input[name=p_status]:checked').each(function(){selected_status.push($(this).val())});
                    if(selected_status.length > 0)  {
                        var row_status = $(dTable.row(dataIndex).node()).find('td:first-child').attr('data-value');
                        row_status = row_status.slice(7, -2);
                        if(selected_status.filter(item => item.toLowerCase() === row_status.toLowerCase()).length < 1)
                            return false;
                    }                       
                    
                    // Perform search by other columns with other value
                    if (data.slice(1).join(' ').toLowerCase().indexOf(searchValue1) === -1) {
                        return false;
                    }

                    return true;
                }
            );
            dTable = $('.datatable-project').DataTable();
        });

        function modify() {
            var selected_tr = dTable.row('.selected').node();
            console.log(selected_tr);
            if(selected_tr === null){
                return false;
            }
            var pid = $(selected_tr).data('id');
            var cid = $(selected_tr).data('company');
            var uid = $(selected_tr).data('contact');

            // console.log(pid, cid, uid);
            location.href = '{{route('admin.projects.profile')}}/' + pid + '/' + cid + '/' + uid;
        }

        // function modify2(pid, cid, uid) {
        //     /*var selected_tr = dTable.row('.selected').node();
        //     console.log(selected_tr);
        //     if(selected_tr === null){
        //         return false;
        //     }
        //     var pid = $(selected_tr).data('id');
        //     var cid = $(selected_tr).data('company');
        //     var uid = $(selected_tr).data('contact');*/

        //     // console.log(pid, cid, uid);
        //     if(confirm("@lang('Do you want to modify?')")) {
        //     location.href = '{{route('admin.projects.profile')}}/' + pid + '/' + cid + '/' + uid;
        //     }
        // }
        function modify2(pid, cid, uid) {
            Swal.fire({
               title: "@lang('Do you confirm to modify ?')",
               icon: 'question',
               showCancelButton: true,
               confirmButtonColor: '#3085d6',
               cancelButtonColor: '#d33',
               confirmButtonText: '@lang("Yes")',
               cancelButtonText: '@lang("No")'
           }).then((result) => {
               if (result.isConfirmed) {
                   location.href = '{{ route("admin.projects.profile") }}/' + pid + '/' + cid + '/' + uid;
               }
           });
      }

        $('.deletable-projects').on('change', function() {
            // Check if at least one checkbox is checked
            if ($('.deletable-projects:checked').length > 0) {
                // Emit action if at least one is checked
                console.log('At least one checkbox is checked.');
                // Add your custom action here
                $('.multi-delete-projects').removeClass("d-none");
            } else {
                console.log('No checkboxes are checked.');
                $('.multi-delete-projects').addClass("d-none");
            }
        });

        $('.multi-delete-projects').click(function(){
            var pids = [];
            $('.deletable-projects:checked').map((index, ele) => {
                pids.push($(ele).val());
            });
            Swal.fire({
                title: "Do you confirm to remove ?",
                
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '@lang("Yes")'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: 'POST',
                        url: '{{route("admin.projects.multi-delete.project")}}',
                        headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                        data: {ids: pids}
                    }).done(function (res) {
                        if(res.result && res.result > 0) {
                            for( var pid of pids) {
                                dTable.row('#mytr_'+pid).remove().draw(false);
                            }
                            $('.multi-delete-projects').addClass("d-none");
                            Swal.fire(
                                '@lang("Deleted!")',
                                '@lang("Your projectx have been deleted.")',
                                'success'
                            );
                        }
                    });
                }
            });

        })
        
        function del(pid) {
        Swal.fire({
        title: "Do you confirm to remove ?",
        
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '@lang("Yes")'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                method: 'POST',
                url: '{{route("admin.projects.delete.project")}}',
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: {id: pid}
            }).done(function (res) {
                if(res.result == true) {
                    dTable.row('#mytr_'+pid).remove().draw(false);
                    Swal.fire(
                        '@lang("Deleted!")',
                        '@lang("Your project has been deleted.")',
                        'success'
                    );
                }
            });
        }
    });
}

        function duplicate(id) {
            // var selected_tr = dTable.row('.selected').node();
            var selected_tr = dTable.row('.selected').node();
            // console.log(selected_tr);
            if(selected_tr === undefined)
                return false;
            var pid = id;
            // var pid = $('.duplicate').data('id');

            $.ajax({
                method: 'POST',
                url: '{{route('admin.projects.duplicate.project')}}',
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: {id: pid}
            }).done(function (res) {
                if(res.result > 0) {
                    // dTable.row('.selected').remove().draw(false);
                    // var cloned_tr = $(selected_tr).clone();
                    // cloned_tr.attr('data-id', res.result);
                    // cloned_tr.removeClass('selected');
                    // // dTable.row.add(cloned_tr);
                    // dTable.row.add(cloned_tr).draw();
                    // dTable.draw(false);
                    window.location.reload(true);
                }   
            });
        }

        function duplicate2(pid) {
            var id = $('#myid_'+pid).addClass('selected');
            var selected_tr = dTable.row('.selected').node();
            
            alert(id);
        
            console.log(selected_tr);
           
            $.ajax({
                method: 'POST',
                url: '{{route('admin.projects.duplicate.project')}}',
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: {id: pid}
            }).done(function (res) {
                 console.log(res);
                if(res.result > 0) {
                     //dTable.row('.selected').remove();
                    var cloned_tr = $(selected_tr).clone();
                    cloned_tr.attr('data-id', res.result);
                    cloned_tr.removeClass('selected');
                    dTable.row.add(cloned_tr);
                    dTable.draw(false);
                    window.location.reload(true);
                }   
            });
        }

    // $('.getdata').on('click',function(){
    //       $.ajax({
    //          url:'{{route('admin.projects')}}',
    //          type:'GET',
    //          data:'json',
    //          success:function(data){
    //             console.log(data.result);
    //          }
    //       });
    // });

        function searchProject() {
            dTable.draw(false);
        }

        function onProjectStatusChange(pid, obj) {
            $.ajax({
                method: 'POST',
                url: '{{route('admin.projects.status.change')}}',
                headers: {'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')},
                data: {id: pid, status: obj.value}
            }).done(function(res) {
                window.location.reload();
                $(obj).closest('td').attr('data-value', $(obj).find('option:selected').text());
            });
        }

        $('#keyword_project').on('keyup', function(e) {
            if(e.keyCode === 13) {
                dTable.draw(false);
            }
        });

        $('input[name=p_status]').on('change', function() {
            dTable.draw(false);
        });

        function goToDetail(pid,company_id,contact_id) {
            location.href = `{{route('admin.projects.detail')}}/${pid}/${company_id}/${contact_id}`;
           
        //     Swal.fire({
        //        title: "@lang('Do you confirm to modify ?')",
        //        icon: 'question',
        //        showCancelButton: true,
        //        confirmButtonColor: '#3085d6',
        //        cancelButtonColor: '#d33',
        //        confirmButtonText: '@lang("Yes")',
        //        cancelButtonText: '@lang("No")'
        //    }).then((result) => {
        //        if (result.isConfirmed) {
        //         location.href = `{{route('admin.projects.detail')}}/${pid}/${company_id}/${contact_id}`;
        //        }
        //    });
           
        }
    </script>
@endsection