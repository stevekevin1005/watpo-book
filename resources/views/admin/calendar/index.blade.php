@extends('admin.layout')
@section('head')
<link rel="stylesheet" href="/assets/css/bootstrap-select.min.css">
<link rel="stylesheet" href="/bower_components/jquery-timepicker-wvega/jquery.timepicker.css">
<style type="text/css">
    .fc-event{
        cursor: pointer;
    }
    td{
        font-size: 22px;
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
                        <h4 class="page-title">{{ $shop->name }} - 預約管理
                            &nbsp;&nbsp;&nbsp;
                            <input type="date" class="form-control-inline" value="{{ $today }}" id="date">
                            @if(session('account_level') != 3)
                            &nbsp;&nbsp;&nbsp;
                            <button class="btn btn-primary" id="new_order">新建預約單</button> 
                            &nbsp;&nbsp;&nbsp;
                            <button class="btn btn-warning" id="leave_status">師傅出勤</button>
                            @endif
                        </h4>
                        <a href="#" style="color:#3ddcf7;">●</a> - 客戶預訂
                        <a href="#" style="color:#1d7dca;">●</a> - 櫃檯修改
                        <a href="#" style="color:gray;">●</a> - 客戶取消
                        <a href="#" style="color:#ffaa00;">●</a> - 櫃檯取消
                        <a href="#" style="color:#5cb85c;">●</a> - 訂單成立
                        <a href="#" style="color:red;">●</a> - 逾時取消
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <table class="table table-striped" id="order_list">
                        <thead>
                            <th>訂單編號</th>
                            <th>預約時間</th>
                            @if(session('account_level') != 3)
                            <th>顧客姓名</th>
                            <th>手機號碼</th>
                            {{-- <th>人數</th> --}}
                            <th>師傅</th>
                            <th>房間</th>
                            <th>預約人</th>
                            @endif
                            <th>方案</th>             
                        </thead>
                        <tbody>
                            @foreach($order_list as $order)
                            <tr id="order_list" style="background-color: {{ $order->color }};cursor: pointer;color: white; {{$order->same_phone}}"
                                data-id="{{$order->id}}"
                                data-name="{{$order->name}}"
                                data-phone="{{$order->phone}}"
                                data-person="{{$order->person}}"
                                data-service_id="{{$order->service_id}}"
                                data-start_time="{{$order->start_time}}"
                                data-room_id="{{$order->room_id}}"
                                data-end_time="{{$order->end_time}}"
                                data-status = "{{ $order->status }}"
                                data-provider= "{{$order->provider}}"
                            >
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->time }}</td>
                                @if(session('account_level') != 3)
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->phone }}</td>
                                {{-- <td>{{ $order->person }}</td> --}}
                                <td>{{ $order->provider }}</td>
                                <td>{{ $order->room }}</td>
                                <td>{{ $order->account }}</td>
                                @endif
                                <td>{{ $order->service }}</td>    
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
{{-- <script src="/bower_components/jquery-timepicker-wvega/jquery.timepicker.js"></script> --}}
<script id="order_form_template" type="x-jsrender">
    <form class="container" style="height:500px;" method="post" action="@{{:url}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="row" style="margin-top:10px">
            <div class="col-md-1" style="text-align:left;">
                姓名:
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="name" placeholder="現場客" @{{if name}} value="@{{:name}}" @{{/if}}>
            </div>
            <div class="col-md-1" style="text-align:left;">
                電話:
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" name="phone" placeholder="現場客" @{{if phone}} value="@{{:phone}}" @{{/if}}>
            </div>
            @{{if provider}}
            <div class="col-md-2" style="text-align:left;">
                原安排師傅:
            </div>
            <div class="col-md-2" style="text-align:left;">
                 @{{:provider}} 
            </div>
            @{{/if}}
        </div>
        <div class="row" style="margin-top:10px">
            <div class="col-md-1" style="text-align:left;">
                房間:
            </div>
            <div class="col-md-3">
                <select name="room_id" class="form-control">
                    @foreach($rooms as $room)
                    <option value="{{ $room['id'] }}" {{if room_id && room_id == <?php echo $room['id']?>}} selected="selected" @{{/if}}>{{ $room['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1" style="text-align:left;">
                師傅:
            </div>
            <div class="col-md-6">
                <select name="service_provider_list[]" class="selectpicker1" multiple data-max-options="4" data-width="100%">
                    <option value="0">不指定</option>
                    <option value="0">不指定</option>
                    <option value="0">不指定</option>
                    @foreach($service_providers_1 as $service_provider)
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
                <select name="service_id" id="select_service" class="form-control">
                    @foreach($service_list as $service)
                    <option value="{{ $service->id }}" {{if service_id && service_id == <?php echo $service->id?>}} selected="selected" @{{/if}}>{{ $service->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 col-md-offset-1">
                <select name="service_provider_list[]" class="selectpicker2" multiple data-max-options="4" data-width="100%">
                    @foreach($service_providers_2 as $service_provider)
                    <option value="{{ $service_provider['id'] }}">{{ $service_provider['name'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top:10px">
            <div class="col-md-1"  style="text-align:left;">
                開始:
            </div>
            <div class="col-md-3">
                <input type='datetime-local' id="start_time" name="start_time" class="form-control" @{{if start_time}} value="@{{:start_time}}" @{{/if}} />
            </div>
            <div class="col-md-1"  style="text-align:left;">
                結束:
            </div>
            <div class="col-md-3">
                <input type='datetime-local' id="end_time" name="end_time" class="form-control" @{{if end_time}} value="@{{:end_time}}" @{{/if}} />
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
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-4">
                <button type="button" class="btn btn-warning order_cancel" data-id="@{{:id}}" style="font-size:20px;">取消訂單</button>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-primary order_update" 
                    data-id="@{{:id}}" 
                    data-name="@{{:name}}" 
                    data-phone="@{{:phone}}" 
                    data-person="@{{:person}}" 
                    data-start_time='@{{:start_time}}'
                    data-end_time='@{{:end_time}}'
                    data-room_id='@{{:room_id}}'
                    data-service_id='@{{:service_id}}'
                    data-provider='@{{:provider}}'
                    style="font-size:20px;">更改訂單</button>
            </div>
            @{{if status != 6}}
            <div class="col-md-4">
                <button type="button" class="btn btn-success order_confirm" data-id="@{{:id}}" style="font-size:20px;">確認訂單</button>
            </div>
            @{{/if}}
        </div>
    </div>
</script>
<script id="order_list_template" type="x-jsrender">
    <thead>
        <th>訂單編號</th>
        <th>預約時間</th>
        @if(session('account_level') != 3)
        <th>顧客姓名</th>
        <th>手機號碼</th>
        <th>師傅</th>
        <th>房間</th>
        <th>預約人</th>
         @endif
        <th>方案</th>             
    </thead>
    <tbody>
        @{{for order_list}}
        <tr id="order_list" style="background-color: @{{:color}};cursor: pointer;color: white; @{{:same_phone}}"
            data-id="@{{:id}}"
            data-name="@{{:name}}"
            data-phone="@{{:phone}}"
            data-person="@{{:person}}"
            data-service_id="@{{:service_id}}"
            data-start_time="@{{:start_time}}"
            data-room_id="@{{:room_id}}"
            data-end_time="@{{:end_time}}"
            data-status = "@{{:status}}"
            data-provider= "@{{:provider}}"
        >
            <td>@{{:id}}</td>
            <td>@{{:time}}</td>
             @if(session('account_level') != 3)
            <td>@{{:name}}</td>
            <td>@{{:phone}}</td>
            <td>@{{:provider}}</td>
            <td>@{{:room}}</td>
            <td>@{{:account}}</td>
             @endif
            <td>@{{:service}}</td>    
        </tr>
        @{{/for}}
    </tbody>
</script>
<script id="leave_template" type="x-jsrender">
    <table class="table table-striped" >
        <thead>
            <tr>
                <th>師傅</th>
                <th>開始時間</th>
                <th>結束時間</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @{{for serviceProviders}}
            <tr>
                <td>@{{:name}}</td>

                <td><div class='input-group date datetimepicker'>
                        <input type='time' id="@{{:id}}_start" class="form-control"/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </td>
                <td><div class='input-group date datetimepicker'>
                    <input type='time' id="@{{:id}}_end" class="form-control"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>
                </td>
                <td><button class="btn btn-primary leave_add" data-id=@{{:id}}>新增</button></td>
            </tr>
            @{{if leave_list}}
            @{{for leave_list}}
            <tr>
                <td>休</td>
                <td>@{{:leave_start_time}}</td>
                <td>@{{:leave_end_time}}</td>
                <td><button class="btn btn-danger leave_cancel" data-id=@{{:leave_id}} >刪除</button></td>
            </tr>
             @{{/for}}
            @{{/if}}
            @{{/for}}
        </tbody>
    </table>
</script>
<script type="text/javascript">
    $(function() { // document ready
        
        @if ($errors->has('fail'))
        swal(
          '失敗',
          "{{ $errors->first('fail') }}",
          'error'
        )
        @endif
        @if (old('message'))
        swal(
          '成功',
          "{{ old('message') }}",
          'success'
        )
        @endif
        $( "#new_order" ).on("click", function() {
            var myTemplate = $.templates("#order_form_template");
            var html = myTemplate.render({
                url: '/admin/calendar/{{$shop_id}}/add_order',
                start_time: '{{ date("Y-m-d\TH:i") }}',
                end_time: '{{ date("Y-m-d\TH:i") }}'
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


            $('.selectpicker1').selectpicker({
                size: 4
            });
            $('.selectpicker2').selectpicker({
                size: 4
            });
            var today = new Date();
            today.setTime(today.getTime()+1000*60*60*8);
            document.getElementById("start_time").value  = today.toISOString().substr(0, 16);
            today.setMinutes(today.getMinutes() + 120);
            document.getElementById("end_time").value  = today.toISOString().substr(0, 16);
        });

        $("#leave_status").on("click", function(){

            var date =  $("#date").val();
            $.ajax({
                url: '/api/serviceprovider/leave',
                type: 'get',
                dataType: 'json',
                data: {
                    date: date,
                    shop_id: {{ $shop->id }}
                },
                success: function(data){
                    var myTemplate = $.templates("#leave_template");
                    var html = myTemplate.render(data);
                    swal({
                        title: date + ' 師傅出勤狀況',
                        html: html,
                        width: "70%",
                        allowOutsideClick: false,
                        showCancelButton: false,
                        focusConfirm: false,
                        cancelButtonText:'取消',
                        showConfirmButton: false,
                        showCloseButton: true,
                    });
           
                },
                error: function(e){
                    alert('師傅出勤獲取失敗 請洽系統管理商!');
                }
            });
        });

        $("body").on('click', '.leave_cancel', function(){
            var id = $(this).data('id');
            swal({
                title: '刪除此筆休假?',
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
                        url: '/api/leave/delete',
                        type: 'post',
                        data: {
                            id: id,
                        },
                        success: function(data){
                            swal.close();
                        },
                        error: function(e){
                            swal(
                            '系統發生錯誤',
                            '新增失敗 請洽系統管理商!',
                            'error'
                            )
                        }
                    });
                }
            });
        });

        $("body").on('click', '.leave_add', function(){
            var service_provider_id = $(this).data('id');
            var date =  $("#date").val();
            if($('#'+service_provider_id+'_start').val() != "" && $('#'+service_provider_id+'_end').val() !=  ""){
                var start_time = new Date(date+"T"+$('#'+service_provider_id+'_start').val()+"+08:00");
                var end_time = new Date(date+"T"+$('#'+service_provider_id+'_end').val()+"+08:00");
            
                if(start_time > end_time){
                    end_time.setDate(end_time.getDate()+1);
                }
                
                $.ajax({
                    url: '/api/leave/add',
                    type: 'post',
                    data: {
                        service_provider_id: service_provider_id,
                        start_time: start_time,
                        end_time: end_time
                    },
                    success: function(data){
                        swal.close();
                        swal(
                        '新增休假',
                        '成功',
                        'success'
                        )
                    },
                    error: function(e){
                        swal(
                        '系統發生錯誤',
                        e.responseText,
                        'error'
                        )
                    }
                });
            }
            else{
                swal(
                    '請輸入時間',
                    '尚未輸入時間',
                    'error'
                )
            }
        });

        $(".open-left").trigger('click');
        $(".open-left").trigger('touchstart');
        @if(session('account_level') != 3)
        $('#order_list').on('click', 'tbody tr',function(){
            var id = $(this).data('id');
            var name = $(this).data('name');
            var phone = $(this).data('phone');
            var person = $(this).data('person');
            var service = $(this).data('service');
            var room = $(this).data('room');
            var service_providers = $(this).data('service_providers');
            var start_time = $(this).data('start_time');
            var end_time = $(this).data('end_time');
            var service_id = $(this).data('service_id');
            var room_id = $(this).data('room_id');
            var status = $(this).data('status');
            var provider = $(this).data('provider');
            var myTemplate = $.templates("#check_form_template"); 
            var html = myTemplate.render({
                id: id,
                name: name,
                phone: phone,
                person: person,
                service_providers: service_providers,
                end_time: end_time,
                start_time: start_time,
                service_id: service_id,
                room_id: room_id,
                status: status,
                provider: provider
            });
            swal({
                title: '#'+id+' 預約單確認',
                html: html,
                width: "50%",
                allowOutsideClick: false,
                showCancelButton: false,
                focusConfirm: false,
                cancelButtonText:'取消',
                showConfirmButton: false,
                showCloseButton: true,
            });
        });

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
                    render_order_list();
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
                    render_order_list();
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
            var start_time = $(this).data('start_time');
            var end_time = $(this).data('end_time');
            var service_id = $(this).data('service_id');
            var person = $(this).data('person');
            var provider = $(this).data('provider');

            var myTemplate = $.templates("#order_form_template");
            var html = myTemplate.render({
                url: '/admin/calendar/order/'+order_id+'/update',
                name: name,
                phone: phone,
                room_id: room_id,
                start_time: start_time,
                end_time: end_time,
                service_id: service_id,
                person: person,
                provider: provider
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

            $('.selectpicker1').selectpicker({
                size: 4
            });
            $('.selectpicker2').selectpicker({
                size: 4
            });
        });

        $('body').on('change', '#select_service, #start_time', function(){
            var service_id = $("#select_service").val();
            var start_time = new Date($("#start_time").val());
            // start_time = new Date(start_time - start_time.getTimezoneOffset() * 60000);
            if(service_id == 4 || service_id == 3){
                start_time.setMinutes(start_time.getMinutes() + 60);
            }
            else{
                start_time.setMinutes(start_time.getMinutes() + 120);
            }
           document.getElementById("end_time").value  = start_time.toISOString().substr(0, 16);
        });
        @endif
        $("#date").on('change', function(e){
            render_order_list();
        });

        function render_order_list(){
            var date =  $("#date").val();
            $.ajax({
                url: '/api/order/schedule',
                type: 'get',
                data: {
                    date: date,
                    shop_id: {{ $shop->id }}
                },
                success: function(data){
                    var myTemplate = $.templates("#order_list_template"); 
                    var html = myTemplate.render(data);
                    $("#order_list").html(html);
                },
                error: function(){

                }
            });
        }
        var oTimerId;
        var t;
        function Timeout(){
            render_order_list();
            t = setTimeout(Timeout, 1*1000*10);
        }
        function ReCalculate(){
            clearTimeout(oTimerId);
            clearTimeout(t);
            oTimerId = setTimeout(Timeout, 1*1000*10);
        }
        document.onmousedown = ReCalculate;
        document.onmousemove = ReCalculate;
        ReCalculate();
    });
</script>
@stop