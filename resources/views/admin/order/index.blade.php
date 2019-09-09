@extends('admin.layout')
@section('head')
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
				  		<h4 class="page-title">訂單列表</h4>
					</div>
				</div>
      		</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<form action="/admin/order" method="get" class="form-horizontal with-pagination">
			                <div class="row row-m">
			                	<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">訂單編號</label>
										<div class="col-md-8">
											<input type="text" name="id" value="{{ $request->id }}" class="form-control">
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">顧客名稱</label>
										<div class="col-md-8">
											<input type="text" name="name" value="{{ $request->name }}" class="form-control">
										</div>
									</div>
								</div>
								<!-- /col-md-4-->
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">選擇方案</label>
										<div class="col-md-8">
											<select name="service" class="form-control">
												<option selected="true" value="">選擇方案</option>
												@foreach($service_list as $service)
												<option value="{{ $service->id }}" <?php if($request->service == $service->id){?> selected <?php }?>>{{ $service->title }}</option>
												@endforeach
											</select>
										</div>
									</div>
								<!-- /form-group-->
								</div>
			                </div>
			                <!-- /row-->
			                <div class="row row-m">
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">手機號碼</label>
										<div class="col-md-8">
											<input type="text" name="phone" class="form-control">
										</div>
									</div>
								<!-- /form-group-->
								</div>
								<!-- /col-md-4-->
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">店家</label>
										<div class="col-md-8">
											<select name="shop" class="form-control">
												<option selected="true" value="">選擇店家</option>
												@foreach($shop_list as $shop)
												<option value="{{ $shop->id }}" <?php if($request->shop == $shop->id){?> selected <?php }?>>{{ $shop->name }}</option>
												@endforeach
											</select>
										</div>
									</div>
								<!-- /form-group-->
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">包廂</label>
										<div class="col-md-8">
											<select name="room" class="form-control">
												<option selected="true" value="">選擇包廂</option>
												@foreach($room_list as $room)
												<option value="{{ $room->id }}" <?php if($request->room == $room->id){?> selected <?php }?>>{{ $room->name."(".$room->shop->name.")" }}</option>
												@endforeach
											</select>
										</div>
									</div>
								<!-- /form-group-->
								</div>
			                  <!-- /col-md-4-->
			                  <!-- /col-md-4-->
			                </div>
			                <!-- /row-->
			                <div class="row row-m">
								<div class="col-md-4">
			                    	<div class="form-group">
										<label class="col-md-4 control-label">師傅</label>
										<div class="col-md-8">
			                       			<select name="service_provider" value="0" class="form-control">
												<option selected="true" value="">選擇師傅</option>
												@foreach($service_provider_list as $service_provider)
												<option value="{{ $service_provider['id'] }}"<?php if($request->service_provider == $service_provider['id']){?> selected <?php }?>>{{ $service_provider['name'] }}</option>
												@endforeach
											</select>
			                    		</div>
			                    	</div>
			                    	<!-- /form-group-->
			                  	</div>
			                  <!-- /col-md-4-->
			                  	<div class="col-md-4">
			                    	<div class="form-group">
			                      		<label class="col-md-4 control-label">訂單日期</label>
			                      		<div class="col-md-8">
			                        		<input type="date" name="start_time" class="form-control" value="{{ $request->start_time }}">
			                      		</div>
			                   		</div>
			                    <!-- /form-group-->
			                  	</div>
			                  <!-- /col-md-4-->
				                <div class="col-md-4">
				                    <div class="form-group">
										<label class="col-md-4 control-label text-center">至</label>
				                    	<div class="col-md-8">
				                        	<input type="date" name="end_time" class="form-control" value="{{ $request->end_time }}">
				                    	</div>
				                    </div>
				                    <!-- /form-group-->
				                </div>
			                  <!-- /col-md-4-->
			                  <!-- /col-md-8-->
			                	<div class="col-md-12 text-right"><a href="/admin/order/export?name={{$request->name}}&service={{$request->service}}&phone={{$request->phone}}&shop={{$request->shop}}&service_provider={{$request->service_provider}}&start_time={{$request->start_time}}&end_time={{$request->end_time}}&room={{$request->room}}" class="btn btn-danger" target="_blank">匯出</a><input class="btn btn-primary" type="submit" value="查詢"></div>
			                  <!-- /col-md-12-->
			                </div>
			                <!-- /row-->
			            </form>
					</div>
				</div>
			</div>

		</div>
        <!-- end container -->
        <div class="container">
        	<div class="card-box">
		        <table class="table table-striped">
					<thead>
						<th>編號</th>
						<th>店家</th>
						<th>姓名</th>
						<th>手機號碼</th>
						<th>師傅</th>
						<th>房間</th>
						<th>方案</th>
						<th>預約人</th>
						<th>開始</th>
						<th>結束</th>
						<th>狀態</th>
						@if(session('account_level') == 1)
						<th></th>
						@endif
					</thead>
					<tbody>
						@foreach($order_list as $order)
						<tr>
							<?php
								$service_provider_list= "";
								foreach ($order->serviceProviders as $key => $serviceProvider) {
									if($serviceProvider->shop_id == $order->shop_id){
										$service_provider_list = $service_provider_list." ".$serviceProvider->name;
									}
									else{
										$service_provider_list = $service_provider_list." ".$serviceProvider->name."(調)";
									}
									
								}

								$status = "";
								switch ($order->status) {
									case 1:
										$status = "<button class='btn btn-info'>客戶預定</button>";
										break;
									case 2:
										$status = "<button class='btn btn-primary'>櫃檯預定</button>";
										break;
									case 3:
										$status = "<button class='btn btn-light'>客戶取消</button>";
										break;
									case 4:
										$status = "<button class='btn btn-warning'>櫃檯取消</button>";
										break;
									case 5:
										$status = "<button class='btn btn-success'>訂單成立</button>";
										break;
									case 6:
										$status = "<button class='btn btn-danger'>逾期取消</button>";
										break;
									default:
										$status = "<button class='btn btn-info'>客戶預定</button>";
										break;
								}
							?>
							<td>{{ $order->id }}</td>
							<td>{{ $order->shop->name }}</td>
							<td>{{ $order->name }}</td>
							<td>{{ $order->phone }}</td>
							<td>{{ $service_provider_list }}</td>
							<td>{{ $order->room->name }}</td>
							<td>{{ $order->service->title }}</td>
							@if($order->account != null)
							<td>{{ $order->account->account }}</td>
							@else
							<td></td>
							@endif
							<td>{{ $order->start_time }}</td>
							<td>{{ $order->end_time }}</td>
							<td>{!! $status !!}</td>
							@if(session('account_level') == 1)
							<th><button data-id="{{$order->id}}" class="btn btn-primary operate">操作</button></th>
							@endif
						</tr>
						@endforeach
					</tbody>
				</table>
				{!! $order_list->appends(['name' => $request->name, 'service' => $request->service, 'phone' => $request->phone, 'shop' => $request->shop, 'service_provider' => $request->service_provider, 'start_time' => $request->start_time, 'end_time' => $request->end_time, 'room' => $request->room])->links() !!}
			</div>
		</div>
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
@if(session('account_level') == 1)
<script id="check_form_template" type="x-jsrender">
    <div class="container" style="height:200x;">
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-4">
                <button type="button" class="btn btn-warning order_cancel" data-id="@{{:id}}" style="font-size:20px;">取消訂單</button>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-success order_confirm" data-id="@{{:id}}" style="font-size:20px;">確認訂單</button>
            </div>
        </div>
    </div>
</script>
<script type="text/javascript">
$(function(){
	$('.operate').on('click', function(){
        var id = $(this).data('id');
        var myTemplate = $.templates("#check_form_template"); 
        var html = myTemplate.render({
            id: id
        });
        swal({
            title: '#'+id+' 預約單操作',
            html: html,
            width: "50%",
            allowOutsideClick: false,
            showCancelButton: false,
            focusConfirm: false,
            cancelButtonText:'取消',
            showConfirmButton: false,
            showCloseButton: true,
        });
    });

    $('body').on('click', '.order_cancel', function(){
        var order_id = $(this).data('id');
        $.ajax({
            url: '/api/order/cancel',
            type: 'post',
            dataType: 'json',
            data: {
                order_id: order_id
            },
            success: function(data){
                swal.close();
                window.location.reload();
            },
            error: function(e){
                alert('訂單取消失敗 請洽系統商!');
            }
        });  
    });

    $('body').on('click', '.order_confirm', function(){
        var order_id = $(this).data('id');
        $.ajax({
            url: '/api/order/confirm',
            type: 'post',
            dataType: 'json',
            data: {
                order_id: order_id
            },
            success: function(data){
                swal.close();
                window.location.reload();
            },
            error: function(e){
                alert('訂單確認失敗 請洽系統商!');
            }
        });  
    });
});
</script>
@endif
@stop