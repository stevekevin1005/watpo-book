@extends('admin.layout')
@section('head')
<link rel='stylesheet' href='/assets/plugins/fullcalendar/fullcalendar.css' />
<link href='/assets/plugins/fullcalendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<link href='/assets/plugins/fullcalendar/scheduler.min.css' rel='stylesheet' />
<link rel="stylesheet" href="/assets/css/bootstrap-select.min.css">
<style type="text/css">
    .fc-event{
        cursor: pointer;
    }
</style>
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
                        <h4 class="page-title">{{ $shop->name }} - 預約管理&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-primary" id="new_order">新建預約單</button> 
                        </h4>
                        <a href="#" style="color:#3ddcf7;">●</a> - 客戶預定
                        <a href="#" style="color:#1d7dca;">●</a> - 櫃檯預定
                        <a href="#" style="color:#ffaa00;">●</a> - 櫃檯取消
                        <a href="#" style="color:#5cb85c;">●</a> - 訂單成立
                        @if ($errors->has('fail'))
                        <a href="#" style="color:red;">{{ $errors->first('fail') }}</a>
                        @endif
                        @if (old('message'))
                        <a href="#" style="color:green;">{{ old('message') }}</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div id='calendar'></div>
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
<script src="/assets/plugins/bootstrap-select.min.js"></script>
<script src='/assets/plugins/fullcalendar/fullcalendar.js'></script>
<script src='/assets/plugins/fullcalendar/scheduler.min.js'></script>
<script id="order_form_template" type="x-jsrender">
    <form class="container" style="height:500px;" method="post" action="@{{:url}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="shop_id" value="{{ $shop_id }}">
        <div class="row" style="margin-top:10px">
            <div class="col-md-1" style="text-align:left;">
                姓名:
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="name" placeholder="現場客">
            </div>
            <div class="col-md-1" style="text-align:left;">
                電話:
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="phone" placeholder="現場客">
            </div>
        </div>
        <div class="row" style="margin-top:10px">
            <div class="col-md-1" style="text-align:left;">
                房間:
            </div>
            <div class="col-md-3">
                <select name="room_id" class="form-control">
                    @foreach($rooms as $room)
                    <option value="{{ $room['id'] }}">{{ $room['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1" style="text-align:left;">
                師傅:
            </div>
            <div class="col-md-6">
                <select name="service_provider_list[]" class="selectpicker" multiple data-max-options="4" data-width="100%">
                    @foreach($service_providers as $service_provider)
                    <option value="{{ $service_provider['id'] }}">{{ $service_provider['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top:10px">
            <div class="col-md-1"  style="text-align:left;">
                服務:
            </div>
            <div class="col-md-3">
                <select name="service_id" class="form-control" required>
                    @foreach($service_list as $service)
                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top:10px">
            <div class="col-md-1"  style="text-align:left;">
                開始:
            </div>
            <div class="col-md-3">
                <div class='input-group date datetimepicker'>
                    <input type='text' name="start_time" class="form-control" required/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
            <div class="col-md-1"  style="text-align:left;">
                結束:
            </div>
            <div class="col-md-3">
                <div class='input-group date datetimepicker'>
                    <input type='text' name="end_time" class="form-control" required/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top:50px;">
            <div class="col-md-12">
                <input type="submit" class="btn btn-info" style="font-size:17px; font-weight: 500;font-weight: 500;margin: 15px 5px 0;padding: 10px 32px;border: 0;border-radius: 3px;">
            </div>
        </div>
    </form>
</script>
<script id="check_form_template" type="x-jsrender">
    <div class="container" style="height:200x;">
        <div class="row">
            <div class="col-md-12">
                <h4>姓名: @{{:name}} 電話: @{{:phone}}</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>人數: @{{:person}} 服務: @{{:service}} 房號: @{{:room}}</h4>
            </div>
        </div>
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-4">
                <button type="button" class="btn btn-danger order_cancel" data-id="@{{:order_id}}" style="font-size:20px;">取消訂單</button>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-info order_update" data-id="@{{:order_id}}" data-name="@{{:name}}" data-phone="@{{:phone}}" style="font-size:20px;">更改訂單</button>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-success order_confirm" data-id="@{{:order_id}}" style="font-size:20px;">確認訂單</button>
            </div>
        </div>
    </div>
</script>
<script type="text/javascript">
    $(function() { // document ready
        $( "#new_order" ).on( "click", function() {
            var myTemplate = $.templates("#order_form_template");
            var html = myTemplate.render({
                url: '/admin/calendar/{{$shop_id}}/add_order'
            });

            swal({
                title: '新建預約單',
                html: html,
                width: "90%",
                allowOutsideClick: false,
                showCancelButton: false,
                focusConfirm: false,
                cancelButtonText:'取消',
                showConfirmButton: false,
                showCloseButton: true,
            });

            $('.datetimepicker').datetimepicker({
                format: "YYYY-MM-DD HH:mm"
            });
            $('.selectpicker').selectpicker({
                size: 4
            });
        });
        $(".open-left").trigger('click');
        $(".open-left").trigger('touchstart');

        $('#calendar').fullCalendar({
            header: {
                left: 'today prev,next',
                center: 'title',
                right: 'timelineDay,timelineThreeDays,agendaWeek,month,listWeek'
            },
            now: "{{ $today }}",
            resources: [ 
                @foreach($shop_service_providers as $shop_service_provider)
                { id: {{$shop_service_provider['id']}}, title: "{{$shop_service_provider['title']}}"},
                @endforeach
            ],
            resourceLabelText: '師傅',
            views: {
                timelineThreeDays: {
                    type: 'timeline',
                    duration: { days: 3 }
                }
            },
            defaultView: 'timelineDay',
            aspectRatio: 1.8,
            events: '/api/calendar/{{ $shop_id }}',
            eventClick: function(calEvent, jsEvent, view) {
                var myTemplate = $.templates("#check_form_template");
                var html = myTemplate.render({
                    order_id: calEvent.order_id,
                    name: calEvent.title,
                    phone: calEvent.phone,
                    person: calEvent.person,
                    service: calEvent.service,
                    room: calEvent.room
                });

                swal({
                    title: '預約單確認',
                    html: html,
                    width: "50%",
                    allowOutsideClick: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    cancelButtonText:'取消',
                    showConfirmButton: false,
                    showCloseButton: true,
                });
            },
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source'
        });
        
        $('#calendar').fullCalendar('refetchEventSources', "/api/calendar/{{$shop_id}}" );

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
                    $('#calendar').fullCalendar('refetchEventSources', "/api/calendar/{{$shop_id}}" );
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
                    $('#calendar').fullCalendar('refetchEventSources', "/api/calendar/{{$shop_id}}" );
                },
                error: function(e){
                    alert('訂單確認失敗 請洽系統商!');
                }
            });  
        });

        $('body').on('click', '.order_update', function(){
            var order_id = $(this).data('id');
            var name = $(this).data('name');
            var phone = $(this).data('phone');
            var room_id = $(this).data('room_id');


            var myTemplate = $.templates("#order_form_template");
            var html = myTemplate.render({
                url: '/admin/calendar/{{$shop_id}}/add_order'

            });

            swal({
                title: '更改預約單',
                html: html,
                width: "90%",
                allowOutsideClick: false,
                showCancelButton: false,
                focusConfirm: false,
                cancelButtonText:'取消',
                showConfirmButton: false,
                showCloseButton: true,
            });

            $('.datetimepicker').datetimepicker({
                format: "YYYY-MM-DD HH:mm"
            });
            $('.selectpicker').selectpicker({
                size: 4
            });
        });
    });
</script>
@stop