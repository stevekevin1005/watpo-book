@extends('admin.layout')
@section('head')
<link rel='stylesheet' href='/assets/plugins/fullcalendar/fullcalendar.css' />
@stop
@section('content')
<div class="content-page">
    <!-- Start content -->
    <div class="content">
        <div class="container">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <h4 class="page-title">休假管理</h4>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                    	<form action="/admin/leave">
	                		<div class="col-sm-3">
	                			<select class="form-control" id="choose_shop" name="shop_id" required>
	                				<option disabled selected value>選擇店家</option>
	                				@foreach ($shops as $key => $shop)
	                				<option value="{{ $shop->id }}" {{(isset($shop_id) && $shop_id == $shop->id) ? "selected": ""}}>{{ $shop->name }}</option>
	                				@endforeach
	                			</select>
	                    	</div>
	                        <div class="col-sm-3">
	                            <select class="form-control" id="choose_service_provider" name="service_provider_id" required>
	                				<option disabled selected value>選擇師傅</option>
	                				@foreach ($shops as $shop)
	                				@foreach ($shop->serviceProviders as $serviceProvider)
	                				<option value="{{ $serviceProvider->id }}" data-id="{{ $serviceProvider->shop_id }}" 
	       {{(isset($service_provider_id) && $service_provider_id == $serviceProvider->id) ? "selected": ''}} 
{{(isset($shop_id) && $shop_id == $serviceProvider->shop_id) ? "": 'style=display:none'}} 
	                					>{{ $serviceProvider->name }}</option>
	                				@endforeach
	                				@endforeach
	                			</select>
	                        </div>
	                        <div class="col-sm-6">
	                            <button class="btn btn-primary waves-effect waves-light">確定</button>
	                        </div>
	                    </form>
                    </div>
                </div>
                <!-- end: page -->
            </div>
            @if(isset($service_provider_id))
            <div class="panel">
                <div class="panel-body">
                    {{-- <form action="/admin/leave/add" method="post"> --}}
                    	{{-- {{ csrf_field() }} --}}
                    	<input type="hidden" value="{{ $shop_id }}" name="shop_id">
                    	<input type="hidden" value="{{ $service_provider_id }}" name="service_provider_id">
  						<div class="form-group row">
    						<label class="col-sm-1 col-form-label">開始時間</label>
    						<div class="col-sm-4">
                                <div class='input-group date datetimepicker'>
                                    <input type='datetime-local' name="start_time" id="start_time" class="form-control" required/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
    						</div>
    						<label class="col-sm-1 col-form-label">結束時間</label>
    						<div class="col-sm-4">
      							<div class='input-group date datetimepicker'>
                                    <input type='datetime-local' name="end_time" id="end_time" class="form-control" required/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
    						</div>
    						<div class="col-sm-2">
      							<button type="submit" class="btn btn-primary" id="add_leave">確定</button>
    						</div>
  						</div>
					{{-- </form> --}}
					@if ($errors->has('fail'))
                    <div style="color:red">{{ $errors->first('fail') }}</div>
                    @endif
                </div>
                <!-- end: page -->
            </div> 
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                		<div id='calendar'></div>
                    </div>
                </div>
            </div> 
            @endif
            <!-- end Panel -->
        </div>
        <!-- end container -->
    </div>
    <!-- end content -->
    <!-- FOOTER -->
    <footer class="footer text-right">
        2017 © stevia-network.
    </footer>
    <!-- End FOOTER -->
</div>
@stop
@section('script')
<script src='/assets/plugins/fullcalendar/fullcalendar.js'></script>
<script type="text/javascript">
	$(document).ready(function() {
		if(isiPhone()){
           $("#choose_service_provider > option").attr('disabled', true);
        }
        $("#choose_shop").on('change', function(){
            var shop_id = $(this).val();
            $("#choose_service_provider > option").hide();
            $("#choose_service_provider").prop('selectedIndex',0);     
            $("#choose_service_provider > option[data-id="+shop_id+"]").show();
            if(isiPhone()){
               $("#choose_service_provider > option").attr('disabled', true);
               $("#choose_service_provider > option[data-id="+shop_id+"]").attr('disabled', false);
            }
        });
	});
</script>
@if(isset($service_provider_id))
<script type="text/javascript">
	$(document).ready(function() {
        // $('.datetimepicker').datetimepicker({
        //     format: "YYYY-MM-DD HH:mm"
        // });
	    $('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listMonth'
			},
			navLinks: true, // can click day/week names to navigate views
			businessHours: true, // display business hours
			editable: true,
            events: '/api/{{$service_provider_id}}/leave/list',
			eventClick: function(calEvent, jsEvent, view) {
		        swal({
				  title: '刪除此筆休假?',
				  text: "此動作無法恢復",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  cancelButtonText: '取消',
				  confirmButtonText: '確定'
				}).then((result) => {
				  if (result.value) {
				    $.ajax({
                        url: '/api/leave/delete',
                        type: 'post',
                        data: {
                            id: calEvent.id,
                        },
                        success: function(data){
                            $('#calendar').fullCalendar('refetchEvents');
                        },
                        error: function(e){
                            swal(
                              '系統發生錯誤',
                              '新增失敗 請洽系統管理商!',
                              'error'
                            )
                        }
                    });
				  }
				});
		    }
		});
        
        $('#add_leave').on('click', function(event) {
            event.preventDefault();
            var start_time = $("#start_time").val();
            var end_time = $("#end_time").val();
            if(start_time =='' || end_time ==''){
                swal(
                    '格式錯誤',
                    '請輸入時間',
                    'error'
                )
            }
            if(start_time >= end_time){
                swal(
                    '格式錯誤',
                    '結束時間早於開始時間',
                    'error'
                )
            }
            else{
                $.ajax({
                    url: '/api/leave/add',
                    type: 'post',
                    data: {
                        service_provider_id: {{ $service_provider_id }},
                        start_time: start_time,
                        end_time: end_time
                    },
                    success: function(data){
                        swal(
                          '新增成功',
                          '成功',
                          'success'
                        );
                        $('#calendar').fullCalendar('refetchEvents');
                    },
                    error: function(e){
                        swal(
                          '系統發生錯誤',
                          e.responseText,
                          'error'
                        )
                    }
                });
            }
        });
	});
</script>
@endif
@stop