@extends('admin.adminlayouts.adminlayout')
@section('head')
	{{HTML::style("assets/global/plugins/select2/select2.css")}}
	{{HTML::style("assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css")}}
@stop
@section('mainarea')
    <div class="content-section">
        <div class="container-fluid">
            <div id="load">
                @include('admin.common.error')
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="portlet box">
                        <div class="portlet-title">
                            <div class="title-left">
                                <div class="icon"><i class="fa fa-briefcase fa-fw"></i></div>
                                <span>{{trans('core.department')}}</span>
                                <div class="tools"></div>
                            </div>
                            <div class="btn-portlet-right">
                                <a data-toggle="modal" href="#static">
                                    <span class="icon"><i class="fa fa-plus fa-fw"></i></span>
                                    <span>{{trans('core.btnAddDepartment')}}</span>
                                </a>
                            </div>
                        </div> {{-- end of .portlet-title --}}
                        <div class="portlet-body">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>{{trans('core.department')}} {{trans('core.name')}}</th>
                                        <th>{{trans('core.designation')}}</th>
                                        <th style="width: 110px;">{{trans('core.action')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($departments)>0)
                                        @foreach ($departments as $department)
                                            <tr id="row{{ $department->id }}">
                                                <td>{{ $department->id }}</td>
                                                <td>{{ $department->deptName }}</td>
                                                <td>
                                                    <ol>
                                                        @foreach($department->Designations as $desig)
                                                            <li>{{ $desig->designation }}</li>
                                                        @endforeach
                                                    </ol>
                                                </td>
                                                <td class=" " style="width: 110px;">
                                                    <div class="btn-actions">
                                                        @if(strtolower($department->deptName) == 'employee')
                                                            <span>Default</span>
                                                        @else
                                                            <a class="btn btn-1"  data-toggle="modal" href="#edit_static" onclick="showEdit({{$department->id}},'{{ $department->deptName }}')"><i class="fa fa-edit fa-fw"></i></a>
                                                            <a class="btn btn-1" href="javascript:;" onclick="del({{$department->id}},'{{ $department->deptName }}')"><i class="fa fa-trash fa-fw"></i></a>
                                                        @endif()
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div> {{-- end of .portlet-body --}}
                    </div> {{-- end of .portlet --}}
                </div> {{-- end of .col-md-12 --}}
            </div> {{-- end of .row --}}
        </div>
    </div> {{-- end of .content-section --}}

    {{-- MODALS SECTION --}}
    <div id="static" class="modal fade add-department" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span class="icon"><i class="fa fa-plus fa-fw"></i></span>
                        <span>{{ trans( 'core.new' ) }} {{ trans( 'core.department' ) }}</span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times fa-fw" aria-hidden="true"></i></button>
                </div> {{-- end of .modal-header --}}
                <div class="modal-body">
                    <div class="portlet-body form">
                        {{ Form::open( array( 'route'=>"admin.departments.store", 'class' => 'custom-form','method'=>'POST' ) ) }}
                            <div class="form-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="deptName" class="text-success">{{ trans( 'core.department' ) }}</label>
                                            <input class="form-control form-control-inline " name="deptName" type="text" value="" placeholder="{{trans('core.department')}}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <label for="designation" class="text-success">{{ trans( 'core.designation' ) }}</label>
                                            <input class="form-control form-control-inline" name="designation[0]" type="text" value="" placeholder="{{trans('core.designation')}} #1"/>
                                            <div id="insertBefore"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" id="plusButton" class="btn btn-1 form-control-inline">
                                                <span class="icon"><i class="fa fa-plus fa-fw"></i></span>
                                                <span>Add</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div> {{-- end of .form-body --}}
                            <div class="btn-panel">
                                <button type="submit" data-loading-text="Submitting..." class="demo-loading-btn btn btn-1">{{trans('core.btnSubmit')}}</button>
                            </div>
                        {{ Form::close() }}
                    </div> {{-- end of .portlet-body --}}
                </div> {{-- end of .modal-body --}}
            </div> {{-- end of .modal-content --}}
        </div> {{-- end of .modal-dialog --}}
    </div> {{-- end of .add-department --}}

    <div id="edit_static" class="modal fade edit-department" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <span class="icon"><i class="fa fa-edit fa-fw"></i></span>
                        <span>{{ trans( 'core.editDepartment' ) }}</span>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times fa-fw" aria-hidden="true"></i></button>
                </div> {{-- end of .modal-header --}}
                <div class="modal-body">
                    <div class="portlet-body form">
                        {{ Form::open( [ 'method' => 'PATCH', 'url' => '', 'class' => 'custom-form' , 'id'=>'edit_form' ] ) }}
                            <div class="form-body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="deptName" class="text-success">{{trans('core.department')}}</label>
                                            <input class="form-control form-control-inline " name="deptName" id="edit_deptName" type="text" value="" placeholder="{{trans('core.department')}}" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-9">
                                            <div id="deptresponse"></div>
                                            <div id="insertBefore_edit"></div>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" id="plus_edit_Button" class="btn btn-1 form-control-inline">
                                                <span class="icon"><i class="fa fa-plus fa-fw"></i></span>
                                                <span>{{trans('core.more')}}</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div> {{-- end of .form-body --}}
                            <div class="note note-warning">
                                {{ trans( 'core.note' ) }} {{ Lang::get( 'messages.deleteNoteDesignation' ) }}  
                            </div> {{-- end of .note-warning --}}
                            <div class="btn-panel">
                                <button type="submit" data-loading-text="{{trans('core.btnUpdating')}}" class="demo-loading-btn btn btn-1">{{trans('core.btnUpdate')}}</button>
                            </div>
                        {{ Form::close() }}
                    </div> {{-- end of .portlet-body --}}
                </div> {{-- end of .modal-body --}}
            </div> {{-- end of .modal-content --}}
        </div> {{-- end of .modal-dialog --}}
    </div> {{-- end of .edit-department --}}
    
    @include('admin.common.delete')
@stop

@section('footerjs')

<script>

             var $insertBefore = $('#insertBefore');
                    var $i = 0;
                    $('#plusButton').click(function(){
                         $i = $i+1;
                        $( '<input class="form-control form-control-inline"  name="designation['+$i+']" type="text"  placeholder="{{trans('core.designation')}} #'+($i+1)+'"/>' ).insertBefore($insertBefore);

                    });
//-----EDIT Modal

        var $insertBefore_edit = $('#insertBefore_edit');
             var $j = 0;
            $('#plus_edit_Button').click(function(){
              $j = $j+1;
              $( '<input class="form-control form-control-inline"  name="designation['+$j+']" type="text"  placeholder="{{trans('core.designation')}} #'+($j+1)+'"/>' ).insertBefore( $insertBefore_edit );


            });

		function del(id,dept)
		{

			$('#deleteModal').appendTo("body").modal('show');
			$('#info').html('{{Lang::get('messages.deleteConfirm')}} <strong>'+dept+'</strong> ?<br>' +
			 '<br><div class="note note-warning">' +
			  '{{Lang::get('messages.deleteNoteDepartment')}}'+
              '</div>');
			$("#delete").click(function(){
                $.ajax({
                    type: "POST",
                    url : "{{ url('api/delete/' . $slug) }}/" + id,
                    dataType: 'json',
                    })
                    .done(function(response){
                     if(response.success == "deleted"){
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                        $('#deleteModal').modal('hide');
                        $('#row'+id).fadeOut(500);
                        showToastrMessage(' {{Lang::get('messages.successDelete')}} ', '{{Lang::get('messages.success')}}', 'success'); 
                        setTimeout(function() {
                            window.location.replace('{{ route('admin.'.$slug.'.index') }}')
                        }, 1000);           
                    }
                        });
                    })

    			}

			function showEdit(id,deptName)
			{

                    $('div[id^="edit_field"]').remove();
                    var url = "{{ route('admin.departments.update',':id') }}";
                    url = url.replace(':id',id);
                    $('#edit_form').attr('action',url );

					var get_url = "{{ route('admin.departments.edit',':id') }}";
					get_url = get_url.replace(':id',id);

			        $("#edit_deptName").val(deptName);
			        $("#deptresponse").html('<div class="text-center">{{HTML::image('assets/admin/layout/img/loading-spinner-blue.gif')}}</div>');

                    $.ajax({

                            type: "GET",
                            url : get_url,

                            data: {"id":id}

                            }).done(function(response)
                              {
                                        $("#deptresponse").html(response);
                                        $j = $('input#designation').length-1;
                             });

			}
</script>
@stop
	