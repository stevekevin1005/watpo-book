@extends('admin.layout')
@section('head')
<link rel='stylesheet' href='/assets/plugins/fullcalendar/fullcalendar.css' />
<link href='/assets/plugins/fullcalendar/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<link href='/assets/plugins/fullcalendar/scheduler.min.css' rel='stylesheet' />
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
                        <h4 class="page-title">民生店 - 預約管理</h4>
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
<script src='/assets/plugins/fullcalendar/fullcalendar.js'></script>
<script src='/assets/plugins/fullcalendar/scheduler.min.js'></script>
<script type="text/javascript">
    $(function() { // document ready
        $(".button-menu-mobile").trigger('click');
        $('#calendar').fullCalendar({
            now: '2017-11-07',
            editable: true, // enable draggable events
            aspectRatio: 1.8,
            scrollTime: '03:00', // undo default 6am scrollTime
            header: {
                left: 'today prev,next',
                center: 'title',
                right: 'timelineDay,timelineThreeDays,agendaWeek,month,listWeek'
            },
            defaultView: 'timelineDay',
            views: {
                timelineThreeDays: {
                    type: 'timeline',
                    duration: { days: 3 }
                }
            },
            resourceLabelText: '師傅',
            resources: [
                { id: '1', title: '1號' },
                { id: '2', title: '2號', eventColor: 'green' },
                { id: '3', title: '3號', eventColor: 'orange' },
                { id: '4', title: '4號' },
                { id: '5', title: '5號', eventColor: 'red' },
                { id: '6', title: '6號' },
                { id: '7', title: '7號' },
                { id: '8', title: '8號' },
                { id: '9', title: '9號' },
                { id: '10', title: '10號', eventColor: 'green' },
                { id: '11', title: '11號', eventColor: 'orange' },
                { id: '12', title: '4號' },
                { id: '13', title: '5號', eventColor: 'red' },
                { id: '14', title: '6號' },
                { id: '15', title: '7號' },
                { id: '16', title: '8號' },
                { id: '17', title: '1號' },
                { id: '18', title: '2號', eventColor: 'green' },
                { id: '19', title: '3號', eventColor: 'orange' },
                { id: '20', title: '4號' },
                { id: '21', title: '5號', eventColor: 'red' },
                { id: '22', title: '6號' },
                { id: '23', title: '7號' },
                { id: '24', title: '8號' },
            ],
            events: [
                { id: '1', resourceId: '1', start: '2017-11-07T02:00:00', end: '2017-11-07T07:00:00', title: 'event 1' },
                { id: '2', resourceId: '2', start: '2017-11-07T05:00:00', end: '2017-11-07T22:00:00', title: 'event 2' },
                { id: '6', resourceId: '2', start: '2017-11-07T05:00:00', end: '2017-11-07T22:00:00', title: 'event 2' },
                { id: '3', resourceId: '3', start: '2017-11-06', end: '2017-11-08', title: 'event 3' },
                { id: '4', resourceId: '4', start: '2017-11-07T03:00:00', end: '2017-11-07T08:00:00', title: 'event 4' },
                { id: '5', resourceId: '5', start: '2017-11-07T00:30:00', end: '2017-11-07T02:30:00', title: 'event 5' }
            ],
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source'
        });
    });
</script>
@stop