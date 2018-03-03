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
                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-success">
                        <h3 class="text-white counter">{{ $shop['order_day'] }}</h3>
                        <p class="text-white">今日預約</p>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-warning">
                        <h3 class="text-white counter">{{ $shop['order_week'] }}</h3>
                        <p class="text-white">一週預約</p>
                    </div>
                </div>
                @if(session('account_level') != 3)
                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-pink">
                        <h3 class="text-white">$ <span class="counter">{{ $shop['revenue_day'] }}</span></h3>
                        <p class="text-white">今日營業額</p>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="widget-simple text-center card-box bg-purple">
                        <h3 class="text-white">$ <span class="counter">{{ $shop['revenue_week'] }}</span></h3>
                        <p class="text-white">一週營業額</p>
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