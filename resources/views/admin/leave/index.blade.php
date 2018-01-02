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
                        <h4 class="page-title">出勤管理</h4>
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
	                				@foreach ($shop->serviceProviders()->get() as $serviceProvider)
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
                    <form action="/admin/leave/add" method="post">
                    	{{ csrf_field() }}
                    	<input type="hidden" value="{{ $shop_id }}" name="shop_id">
                    	<input type="hidden" value="{{ $service_provider_id }}" name="service_provider_id">
  						<div class="form-group row">
    						<label class="col-sm-1 col-form-label">開始時間</label>
    						<div class="col-sm-4">
                                <div class='input-group date datetimepicker'>
                                    <input type='text' name="start_time" class="form-control" required/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
    						</div>
    						<label class="col-sm-1 col-form-label">結束時間</label>
    						<div class="col-sm-4">
      							<div class='input-group date datetimepicker'>
                                    <input type='text' name="end_time" class="form-control" required/>
                                    <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                </div>
    						</div>
    						<div class="col-sm-2">
      							<button type="submit" class="btn btn-primary">確定</button>
    						</div>
  						</div>
					</form>
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
		$("#choose_shop").on('change', function(){
			var shop_id = $(this).val();
			$("#choose_service_provider > option").hide();
			$("#choose_service_provider").not(this).prop('selectedIndex',0);     
			$("#choose_service_provider > option[data-id="+shop_id+"]").show();
		})
	});
</script>
@if(isset($service_provider_id))
<script type="text/javascript">
	$(document).ready(function() {
        $('.datetimepicker').datetimepicker({
            format: "YYYY-MM-DD HH:mm"
        });
	    $('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,listMonth'
			},
			navLinks: true, // can click day/week names to navigate views
			businessHours: true, // display business hours
			editable: true,
			events: [
				@foreach ($leaves as $leave) 
				{
					title: '休假',
					id: '{{ $leave->id }}',
					start: '{{ $leave->start_time }}',
					end: '{{ $leave->end_time }}',
					constraint: '休假', // defined below
					color: 'red'
				},
				@endforeach
			],
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
                        location.reload();
                    },
                    error: function(e){
                        swal(
                          '系統發生錯誤',
                          '新增失敗 請洽系統管理商!',
                          'error'
                        )
                    }
                })
				  }
				})
		    }
		});

	});
</script>
@endif
@stop