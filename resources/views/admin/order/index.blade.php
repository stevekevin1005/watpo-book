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
						<form name="searchOrders" action="order" method="get" class="form-horizontal with-pagination">
			                <input type="hidden" name="page" value="0">
			                <input type="hidden" name="totalPages">
			                <input type="hidden" name="limit" value="10">
			                <div class="row row-m">
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">顧客名稱</label>
										<div class="col-md-8">
											<input type="text" name="serialNumber" value="" class="form-control">
										</div>
									</div>
								</div>
								<!-- /col-md-4-->
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">選擇方案</label>
										<div class="col-md-8">
											<select name="shippingMethod" class="form-control">
												<option value="0" selected="true" disabled="disabled">選擇方案</option>
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
											<input type="text" name="userName" class="form-control">
										</div>
									</div>
								<!-- /form-group-->
								</div>
								<!-- /col-md-4-->
								<div class="col-md-4">
									<div class="form-group">
										<label class="col-md-4 control-label">店家</label>
										<div class="col-md-8">
											<select name="status" value="0" class="form-control">
												<option value="0">選擇店家</option>
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
			                       			<select name="status" value="0" class="form-control">
												<option value="0">選擇師傅</option>
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
			                        		<input type="date" placeholder="2015-10-30" name="createdStart" class="form-control">
			                      		</div>
			                   		</div>
			                    <!-- /form-group-->
			                  	</div>
			                  <!-- /col-md-4-->
				                <div class="col-md-4">
				                    <div class="form-group">
										<label class="col-md-4 control-label text-center">至</label>
				                    	<div class="col-md-8">
				                        	<input type="date" placeholder="2015-10-30" name="createdEnd" class="form-control">
				                        	<input type="hidden" name="page" value="0">
				                    	</div>
				                    </div>
				                    <!-- /form-group-->
				                </div>
			                  <!-- /col-md-4-->
			                  <!-- /col-md-8-->
			                	<div class="col-md-12 text-right"><button class="btn btn-danger">匯出</button><a class="btn btn-primary">查詢</a></div>
			                  <!-- /col-md-12-->
			                </div>
			                <!-- /row-->
			            </form>
					</div>
				</div>
			</div>

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
@stop