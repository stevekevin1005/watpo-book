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
                        <h4 class="page-title">排班設定</h4>
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
	                				<option value="{{ $shop->id }}">{{ $shop->name }}</option>
	                				@endforeach
	                			</select>
	                    	</div>
	                        <div class="col-sm-3">
                                <input id="month" type="month" class="form-control">
	                        </div>
	                    </form>
                    </div>
                </div>
                <!-- end: page -->
            </div>
            <div class="panel">
                <div class="panel-body" id="shiftTemplateContainer">
					@if ($errors->has('fail'))
                    <div style="color:red">{{ $errors->first('fail') }}</div>
                    @endif
                    @if (session('status'))
                    <div style="color:green">{{ session('status') }}</div>
                    @endif
                </div>
                <!-- end: page -->
            </div> 
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
<script id="shiftTemplate" type="x-jsrender">
    <form action="/admin/shift/update" method="post">
        {{ csrf_field() }}
        <input type="hidden" name="month" value="@{{:month}}" class="form-control">
        <table class="table table-striped">
            <thead>
                <th>師傅</th>
                <th>上班時間</th>
                <th>下班時間</th>
            </thead>
            <tbody>
                @{{for serviceProviders}}
                <tr class="gradeX">
                    <td>@{{:name}}<input type="hidden" name="shifts[@{{:#index}}][id]" value="@{{:id}}" class="form-control"></td>
                    <td><input type="time" name="shifts[@{{:#index}}][start_time]" class="form-control" @{{if start_time}} value="@{{:start_time}}" @{{/if}} required></td>
                    <td><input type="time" name="shifts[@{{:#index}}][end_time]" class="form-control" @{{if end_time}} value="@{{:end_time}}" @{{/if}} required></td>
                </tr>
                @{{/for}}
            </tbody>
        </table>
        <input type="submit" class="btn btn-primary" value="確認">
    </form>
</script>
<script type="text/javascript">
	$(document).ready(function() {
        $("#month, #choose_shop").on('change', function(){
            var shop_id = $("#choose_shop").val();
            var month = $("#month").val();
            if(shop_id != null && month != ''){
                $.ajax({
                    url: '/api/shift/list',
                    data: {
                        shop_id: shop_id,
                        month: month
                    },
                    success: function(data){
                        var myTemplate = $.templates("#shiftTemplate");
                        var html = myTemplate.render(data);
                        $("#shiftTemplateContainer").html(html);
                    },
                    error: function(e){
                        swal(
                          '系統發生錯誤',
                          '請洽系統管理商!',
                          'error'
                        )
                    }
                });
            }
        });
	});
</script>
@stop