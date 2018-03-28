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
				  		<h4 class="page-title">帳號管理</h4>
					</div>
				</div>
      		</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<h4 class="text-dark  header-title m-t-0">密碼更改</h4>
						<form action="/admin/account/update_password" method="post" class="form-inline">
							{{ csrf_field() }}
							<div class="form-group">
								<label>新密碼&nbsp;&nbsp;&nbsp;</label>
								<input type="password" class="form-control" name="password1" maxlength="20" minlength="4" required>
							</div>
							<div class="form-group">
								<label for="exampleInputEmail2">請再次輸入新密碼&nbsp;&nbsp;&nbsp;</label>
								<input type="password" class="form-control" name="password2" maxlength="20" minlength="4"required>
							</div>
						  	<button type="submit" class="btn btn-info">更改</button>
						  	@if ($errors->has('fail'))
                <div style="color:red">{{ $errors->first('fail') }}</div>
                @endif
						</form>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<h4 class="text-dark  header-title m-t-0">員工帳號列表</h4>

						<div class="row">
							<table class="table table-striped">
								<thead>
									<th>帳號</th>
									<th>密碼</th>
									<th></th>
								</thead>
								<tbody>
									<tr>
										<td><input type="text" class="form-control" id="new_account"></td>
										<td><input type="password" class="form-control" id="new_password"></td>
										<td><button class="btn btn-primary" id="add_account">新增帳號</button></td>
									</tr>

									@foreach($counter_accounts as $account)
									<tr>
										<td><input type="text" class="form-control" value="{{$account->account}}" readonly></td>
										<td><input type="text" class="form-control" id="new_password_{{$account->id}}" maxlength="20" minlength="4"></td>
										<td><button class="btn btn-info reset_password" data-id="{{ $account->id }}">重置密碼</button><button class="btn btn-danger delete_account" data-id="{{ $account->id }}">刪除帳號</button></td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-12">
					<div class="card-box">
						<h4 class="text-dark  header-title m-t-0">師傅帳號列表</h4>
						<select class="form-control" id="choose_shop" name="shop_id" required>
            				<option disabled selected value>選擇店家</option>
            				@foreach ($shops as $key => $shop)
            				<option value="{{ $shop->id }}" {{(isset($shop_id) && $shop_id == $shop->id) ? "selected": ""}}>{{ $shop->name }}</option>
            				@endforeach
            			</select>
						<div class="row">
							<table class="table table-striped">
								<thead>
									<th>帳號</th>
									<th>密碼</th>
									<th>師傅</th>
								</thead>
								<tbody>
									<tr>
										<td><input type="text" class="form-control" id="new_worker_account"></td>
										<td><input type="password" class="form-control" id="new_worker_password"></td>
										<td>
											<select class="form-control" id="choose_service_provider" name="service_provider_id" required>
				                				<option disabled selected value>選擇師傅</option>
				                				@foreach ($shops as $shop)
				                				@foreach ($shop->serviceProviders as $serviceProvider)
				                				<?php
				                					if(isset($option[$serviceProvider->shop_id])){
				                						$option[$serviceProvider->shop_id] .= "<option value=$serviceProvider->id > $serviceProvider->name</option>";
				                					}
				                					else{
				                						$option[$serviceProvider->shop_id] = "<option value=$serviceProvider->id > $serviceProvider->name</option>";
				                					}
				                				?>
				                				
				                				@endforeach
				                				@endforeach
				                			</select>
				                		</td>
										<td><button class="btn btn-primary" id="add_work_account">新增帳號</button></td>
									</tr>

									@foreach($worker_accounts as $account)
									<tr class="">
										<td><input type="text" class="form-control" value="{{$account->account}}" readonly></td>
										<td><input type="text" class="form-control" id="new_password_{{$account->id}}" maxlength="20" minlength="4"></td>
										<td>{{ $account->service_provider->name }}({{ $account->service_provider->shop->name}})</td>
										<td><button class="btn btn-info reset_password" data-id="{{ $account->id }}">重置密碼</button><button class="btn btn-danger delete_account" data-id="{{ $account->id }}">刪除帳號</button></td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						
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
<script type="text/javascript">
	
	$(document).ready(function() {
        $("#choose_shop").on('change', function(){
            var shop_id = $(this).val();
            var option1 = "<?php echo $option[1];?>";
            var option2 = "<?php echo $option[2];?>";
            if(shop_id == 1){
            	$("#choose_service_provider").html(option1);
            }
            else{
            	$("#choose_service_provider").html(option2);
            }
           	
        });
	});
</script>
<script type="text/javascript">
	$("#add_account").on('click', function(){
		var account = $("#new_account").val();
		var password = $("#new_password").val();
		if(account.length <= 2 || account.length > 20){
			swal(
			  '資料格式錯誤',
			  '帳號最少二個字元最多二十個字元!',
			  'error'
			);
			return;
		}
		if(password.length < 4 || password.length > 20){
			swal(
			  '資料格式錯誤',
			  '密碼最少四個字元最多二十個字元!',
			  'error'
			);
			return;
		}
		$.ajax({
			url: '/api/account/add',
			type: 'post',
			dataType: 'json',
			data: {
				account: account,
				password: password
			},
			success: function(){
				swal(
					'新增結果',
					'成功!',
					'success'
				).then((result) => {
					location.reload();
				});
			},
			error: function(e){
				swal(
				  '資料格式錯誤',
				  e.responseJSON,
				  'error'
				);
			}
		});
	});

	$("#add_work_account").on('click', function(){
		var account = $("#new_worker_account").val();
		var password = $("#new_worker_password").val();
		var worker_id = $("#choose_service_provider").val();
		if(account.length <= 2 || account.length > 20){
			swal(
			  '資料格式錯誤',
			  '帳號最少二個字元最多二十個字元!',
			  'error'
			);
			return;
		}
		if(password.length < 4 || password.length > 20){
			swal(
			  '資料格式錯誤',
			  '密碼最少四個字元最多二十個字元!',
			  'error'
			);
			return;
		}
		if(!worker_id || worker_id == ''){
			swal(
			  '資料格式錯誤',
			  '沒有選擇師傅',
			  'error'
			);
			return;
		}
		$.ajax({
			url: '/api/worker_account/add',
			type: 'post',
			dataType: 'json',
			data: {
				account: account,
				password: password,
				worker_id: worker_id
			},
			success: function(){
				swal(
					'新增結果',
					'成功!',
					'success'
				).then((result) => {
					location.reload();
				});
			},
			error: function(e){
				swal(
				  '資料格式錯誤',
				  e.responseJSON,
				  'error'
				);
			}
		});
	});

	$(".delete_account").on('click', function(){
		var id = $(this).data('id');
		swal({
		  title: '刪除此帳號?',
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
				    url: '/api/account/delete',
				    type: 'post',
				    data: {
				        id: id,
				    },
				    success: function(data){
				        location.reload();
				    },
				    error: function(e){
				        swal(
				          '系統發生錯誤',
				          e.responseJSON,
				          'error'
				        )
				    }
				});
			}
		});
	});

	$(".reset_password").on('click', function(){
		var id = $(this).data('id');
		var password = $("#new_password_"+ id).val();
		if(password.length < 4 || password.length > 20){
			swal(
			  '資料格式錯誤',
			  '密碼最少四個字元最多二十個字元!',
			  'error'
			);
			return;
		}
	
		$.ajax({
		    url: '/api/account/reset_password',
		    type: 'post',
		    data: {
		        id: id,
		        password: password
		    },
		    success: function(data){
		        swal(
					'更改結果',
					'成功!',
					'success'
				).then((result) => {
					location.reload();
				});
		    },
		    error: function(e){
		        swal(
		          '系統發生錯誤',
		          e.responseJSON,
		          'error'
		        )
		    }
		});
});
</script>
@stop