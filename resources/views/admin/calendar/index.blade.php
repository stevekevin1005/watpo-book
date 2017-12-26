@extends('admin.layout')
@section('head')
<link rel='stylesheet' href='/assets/plugins/fullcalendar/fullcalendar.css' />
<link href='/assets/plugins/fullcalendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<link href='/assets/plugins/fullcalendar/scheduler.min.css' rel='stylesheet' />
<link rel="stylesheet" href="/assets/css/bootstrap-select.min.css">
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
                        <a href="#" style="color:royalblue;">●</a> - 客戶預定
                        <a href="#" style="color:khaki;">●</a> - 櫃檯預定
                        <a href="#" style="color:indianred;">●</a> - 客戶取消
                        <a href="#" style="color:lime;">●</a> - 訂單成立
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
{{-- <script src="/assets/plugins/i18n/defaults-*.min.js"></script> --}}
<script src='/assets/plugins/fullcalendar/fullcalendar.js'></script>
<script src='/assets/plugins/fullcalendar/scheduler.min.js'></script>
<script id="order_form_template" type="x-jsrender">
    <form id="order_form" class="container" style="height:300px;">
        <div class="row" style="margin-top:10px">
            <div class="col-md-1" style="text-align:left;">
                姓名:
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="現場客">
            </div>
            <div class="col-md-1" style="text-align:left;">
                電話:
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" id="exampleInputEmail3" placeholder="現場客">
            </div>
        </div>
        <div class="row" style="margin-top:10px">
            <div class="col-md-1" style="text-align:left;">
                房間:
            </div>
            <div class="col-md-3">
                <select name="" class="form-control">
                    @foreach($rooms as $room)
                    <option value="{{ $room['id'] }}">{{ $room['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1" style="text-align:left;">
                師傅:
            </div>
            <div class="col-md-6">
                <select name="" class="selectpicker" multiple data-max-options="4" data-width="100%">
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
                <select name="" class="form-control" required>
                    @foreach($service_list as $service)
                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top:10px">
            <div class="col-md-2"  style="text-align:left;">
                開始時間:
            </div>
            <div class="col-md-3">
                <input type="datetime-local" class="form-control" required>
            </div>
            <div class="col-md-2"  style="text-align:left;">
                結束時間:
            </div>
            <div class="col-md-3">
                <input type="datetime-local" class="form-control" required>
            </div>
        </div>
        <div class="row" style="margin-top:50px;">
            <div class="col-md-12">
                <input type="submit" class="btn btn-info" style="font-size:17px; font-weight: 500;font-weight: 500;margin: 15px 5px 0;padding: 10px 32px;border: 0;border-radius: 3px;">
            </div>
        </div>
    </form>
</script>
<script type="text/javascript">
    $(function() { // document ready
        $( "#new_order" ).on( "click", function() {
            var myTemplate = $.templates("#order_form_template");
                var html = myTemplate.render();
            swal({
                title: '新建預約單',
                html: html,
                width: "90%",
                allowOutsideClick: false,
                showCancelButton: true,
                focusConfirm: false,
                cancelButtonText:'取消',
                showConfirmButton: false,
            }).then((result) => {

            });
            $('.selectpicker').selectpicker({
                size: 4
            });
        });
        $(".open-left").trigger('click');
        $(".open-left").trigger('touchstart');


        function render_calender(){
            $.ajax({
                url: '/api/calendar/{{ $shop_id }}',
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    console.log(data);
                    var option = {
                        header: {
                            left: 'today prev,next',
                            center: 'title',
                            right: 'timelineDay,timelineThreeDays,agendaWeek,month,listWeek'
                        },
                        resourceLabelText: '師傅',
                        views: {
                            timelineThreeDays: {
                                type: 'timeline',
                                duration: { days: 3 }
                            }
                        },
                        defaultView: 'timelineDay',
                        editable: false, 
                        aspectRatio: 1.8,
                        eventClick: function(calEvent, jsEvent, view) {

                        },
                        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source'
                    };
 
                    option.now = data.today;
                    option.resources = data.service_providers;
                    option.events = data.orders;
                    console.log(option);
                    $('#calendar').fullCalendar(option);
                },
                error: function(e){

                }
            });
        }
        render_calender();
        // $('#calendar').fullCalendar({
        //     now: '2017-11-07',
        //     editable: true, // enable draggable events
        //     aspectRatio: 1.8,
        //     scrollTime: '03:00', // undo default 6am scrollTime
            
        //     
            
            
        //     resources: [
        //         { id: '1', title: '1號' },
        //         { id: '2', title: '2號', eventColor: 'green' },
        //         { id: '3', title: '3號', eventColor: 'orange' },
        //         { id: '4', title: '4號' },
        //         { id: '5', title: '5號', eventColor: 'red' },
        //         { id: '6', title: '6號' },
        //         { id: '7', title: '7號' },
        //         { id: '8', title: '8號' },
        //         { id: '9', title: '9號' },
        //         { id: '10', title: '10號', eventColor: 'green' },
        //         { id: '11', title: '11號', eventColor: 'orange' },
        //         { id: '12', title: '4號' },
        //         { id: '13', title: '5號', eventColor: 'red' },
        //         { id: '14', title: '6號' },
        //         { id: '15', title: '7號' },
        //         { id: '16', title: '8號' },
        //         { id: '17', title: '1號' },
        //         { id: '18', title: '2號', eventColor: 'green' },
        //         { id: '19', title: '3號', eventColor: 'orange' },
        //         { id: '20', title: '4號' },
        //         { id: '21', title: '5號', eventColor: 'red' },
        //         { id: '22', title: '6號' },
        //         { id: '23', title: '7號' },
        //         { id: '24', title: '8號' },
        //     ],
        //     events: [
        //         { id: '1', resourceId: '1', start: '2017-11-07T02:00:00', end: '2017-11-07T03:00:00', title: '陳先生', detail: '123'},
        //         { id: '2', resourceId: '2', start: '2017-11-07T05:00:00', end: '2017-11-07T22:00:00', title: 'event 2' },
        //         { id: '3', resourceId: '3', start: '2017-11-06', end: '2017-11-08', title: 'event 3' },
        //         { id: '4', resourceId: '4', start: '2017-11-07T03:00:00', end: '2017-11-07T08:00:00', title: 'event 4' },
        //         { id: '5', resourceId: '5', start: '2017-11-07T00:30:00', end: '2017-11-07T02:30:00', title: 'event 5' }
        //     ],
            
        // });
    });
</script>
@stop