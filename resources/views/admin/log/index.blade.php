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
				  		<h4 class="page-title">操作記錄</h4>
					</div>
				</div>
      		</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<form name="log_form" method="get" class="form-horizontal with-pagination">
			                <!-- /row-->
			                <div class="row row-m">
								<div class="col-md-4">
			                    	<div class="form-group">
										<label class="col-md-4 control-label">帳號</label>
										<div class="col-md-8">
			                       			<select name="account_id" value="0" class="form-control">
												<option selected="true" value="">選擇帳號</option>
												@foreach($account_list as $account)
												<option value="{{$account->id}}" <?php if($request->account_id == $account->id){?> selected <?php }?>
												>{{$account->account}}</option>
												@endforeach
											</select>
			                    		</div>
			                    	</div>
			                    	<!-- /form-group-->
			                  	</div>
			                  <!-- /col-md-4-->
			                  	<div class="col-md-4">
			                    	<div class="form-group">
			                      		<label class="col-md-4 control-label">操作日期</label>
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
			               	</div>
			               	<div class="row row-m">
								<div class="col-md-8">
			                    	<div class="form-group">
										<label class="col-md-2 control-label">描述</label>
										<div class="col-md-10">
			                       			<input class="form-control" type="text" name="description"placeholder="請輸入描述" value="{{ $request->description }}">
			                    		</div>
			                    	</div>
			                  	</div>
			                  	
			               	</div>
			                <div class="row row-m">
			                	<div class="col-md-12 text-right">
			                		<button class="btn btn-danger" onclick="export_xls();">匯出</button><button class="btn btn-primary" onclick="list();">查詢</button>
			                	</div>
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
						<th>帳號</th>
						<th>描述</th>
						<th>時間</th>
					</thead>
					<tbody>
						@foreach($log_list as $log)
						<tr>
							<td>{{$log->account['account']."	".$log->account['information']}}</td>
							<td>{{$log->description}}</td>
							<td>{{$log->created_at}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				{!! $log_list->appends(['account_id' => $request->account_id, 'start_time' => $request->start_time, 'end_time' => $request->end_time])->links() !!}
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
<script language="javascript">
function export_xls() {
	document.log_form.action= "/admin/log/export" ;
	if(document.log_form.checkValidity()){
		document.log_form.submit();
	}
	else{
		document.log_form.reportValidity()
	}
}
function list() {
	document.log_form.action= "/admin/log" ;
    if(document.log_form.checkValidity()){
		document.log_form.submit();
	}
	else{
		document.log_form.reportValidity()
	}
}
</script>
@stop