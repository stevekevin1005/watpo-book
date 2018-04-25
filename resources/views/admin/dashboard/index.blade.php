@extends('admin.layout')
@section('head')
@stop
@section('content')
<div class="content-page">
    <!-- Start content -->
	<div class="content">
		<div class="container">
			@foreach($shop_list as $shop)
        
            @if(is_null(session('account_shop_id')) || session('account_shop_id') == $shop['id'])
			<div class="row">
				<h1>{{ $shop['name'] }}</h1>
			</div>
		    <div class="row">
                @if(!is_null(session('account_shop_id')))
                <div class="col-sm-6 col-lg-3">
                    <a class="text-white" href="/admin/calendar/{{session('account_shop_id')}}">
                        <div class="widget-simple text-center card-box bg-primary">
                            <h3 class="text-white">預約列表</h3>
                            <p class="text-white">&nbsp;</p>
                        </div>
                    </a>
                </div>
                @endif
                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-success">
                        <h3 class="text-white"><span class="counter">{{ $shop['order_day'] }}</span></h3>
                        <p class="text-white">今日預約</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-warning">
                        <h3 class="text-white"><span class="counter">{{ $shop['order_month'] }}</span></h3>
                        <p class="text-white">月預約</p>
                    </div>
                </div>
                @if(session('account_level') == 1)
                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-pink">
                        <h3 class="text-white">$ <span class="counter">{{ $shop['revenue_day'] }}</span></h3>
                        <p class="text-white">今日營業額</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-purple">
                        <h3 class="text-white">$ <span class="counter">{{ $shop['revenue_month'] }}</span></h3>
                        <p class="text-white">月營業額</p>
                    </div>
                </div>
                @endif
                @if(session('account_level') == 2)
                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-pink">
                        <h3 class="text-white"><span class="counter">{{ $shop['cancel_day'] }}</span></h3>
                        <p class="text-white">今日逾期</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-purple">
                        <h3 class="text-white"><span class="counter">{{ $shop['cancel_month'] }}</span></h3>
                        <p class="text-white">月逾期</p>
                    </div>
                </div>
                @endif
            </div>
            @endif
            @endforeach
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
@stop