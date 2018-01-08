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
												<option value="{{ $service->id }}" <?php if($request->service == $service->id){?> selected <?}?>>{{ $service->title }}</option>
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
												<option value="{{ $shop->id }}" <?php if($request->shop == $shop->id){?> selected <?}?>>{{ $shop->name }}</option>
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
												<option value="{{ $service_provider['id'] }}"<?php if($request->service_provider == $service_provider['id']){?> selected <?}?>>{{ $service_provider['name'] }}</option>
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
			                	<div class="col-md-12 text-right"><a href="/admin/order/export?name={{$request->name}}&service={{$request->service}}&phone={{$request->phone}}&shop={{$request->shop}}&service_provider={{$request->service_provider}}&start_time={{$request->start_time}}&end_time={{$request->end_time}}" class="btn btn-danger" target="_blank">匯出</a><input class="btn btn-primary" type="submit" value="查詢"></div>
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
						<th>訂單編號</th>
						<th>店家</th>
						<th>顧客姓名</th>
						<th>手機號碼</th>
						<th>師傅</th>
						<th>方案</th>
						<th>狀態</th>
						<th>訂單日期</th>
					</thead>
					<tbody>
						@foreach($order_list as $order)
						<tr>
							<?php
								$service_provider_list= "";
								foreach ($order->serviceProviders as $key => $serviceProvider) {
									$service_provider_list = $service_provider_list." ".$serviceProvider->name;
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
										$status = "<button class='btn btn-danger'>客戶取消</button>";
										break;
									case 4:
										$status = "<button class='btn btn-warning'>櫃檯取消</button>";
										break;
									case 5:
										$status = "<button class='btn btn-success'>訂單成立</button>";
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
							<td>{{ $order->service->title }}</td>
							<td>{!! $status !!}</td>
							<td>{{ $order->created_at }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				{!! $order_list->appends(['name' => $request->name, 'service' => $request->service, 'phone' => $request->phone, 'shop' => $request->shop, 'service_provider' => $request->service_provider, 'start_time' => $request->start_time, 'end_time' => $request->end_time, ])->links() !!}
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
@stop