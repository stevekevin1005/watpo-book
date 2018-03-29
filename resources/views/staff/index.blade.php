<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="管理系統,泰和殿">
        <meta name="author" content="Coderthemes">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="/assets/images/favicon_1.ico">

        <title>泰和殿 - 櫃檯介面</title>

        <link href="/assets/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">

        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/pages.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/assets/css/bootstrap-select.min.css">
        <style type="text/css">
            .swal2-cancel{
                margin-right: 30px;
            }
            .content-page{
                min-height: 1000px;
            }
        </style>


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        
    </head>


    <body class="fixed-left">
        
        <!-- Begin page -->
        <div id="wrapper">
        
            <!-- ========== Left Sidebar Start ========== -->
            <!-- Left Sidebar End --> 
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
            <form action="/staff/order" method="post" id="orderForm">
                <div class="card-box" style="position:fixed; z-index:1000; height:80px; width:100%;">
                    <div class="row">
                        <div class="col-xs-3">
                            <select id="choose_shop" class="form-control" name="shop_id" required>
                                <option disabled selected value>選擇店家</option>
                                @foreach ($shops as $key => $shop)
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-4"><input class="form-control" type="datetime-local" id="choose_time" name="time" required></div>
                        <div class="col-xs-1">限制時間：</div>
                        <div class="col-xs-1"><input class="form-control" type="checkbox" id="limit_time" value="true" checked></div>
                        <div class="col-xs-3"><div class="btn btn-primary" id="show_status">確認狀態</div></div>
                    </div>
                </div>
                <div class="content-page">
                <!-- Start content -->
                    <div class="content">
                        <div class="container">
                        @csrf
                        <!-- Page-Title -->
                            <div class="row" style="margin-top: 80px;">
                                <div class="col-lg-12">
                                    <div class="card-box">
                                        <div class="row">
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
                                        <hr/>
                                        <div class="detail">
                                        </div>
                                        <div id="submit_row" class="row" style="margin-top:10px">
                                            <div class="col-md-12 text-right">
                                                <div class="btn btn-primary" id="add_order">新增下一筆</div>
                                                <input class="btn btn-success" type="submit" value="送出">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- end content -->
                <!-- FOOTER -->
                <!-- End FOOTER -->
                </div>
             </form>

        </div>
        <!-- END wrapper -->


        <!-- Plugins  -->
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
        <!-- Moment  -->
        <script src="/assets/plugins/moment/moment.js"></script>
        
        <!-- Sweet Alert  -->
        <script src="/assets/plugins/sweetalert/dist/sweetalert.min.js"></script>

        <!-- Js render -->
        <script src="/assets/js/jsrender.min.js"></script>
        <script src="/assets/plugins/bootstrap-select.min.js"></script>
        <script id="order_form_template" type="x-jsrender">
            <div class="row order@{{:order_index}}" style="margin-top:10px">
                <div class="col-md-1" style="text-align:left;">
                    服務:
                </div>
                <div class="col-md-3">
                    <select name="order[@{{:order_index}}][service_id]" id="select_service" class="form-control selectpicker" title="選擇服務" data-hide-disabled="true" required>
                        @foreach($services as $service)
                            <option value="{{$service->id}}">{{$service->title}}</option>
                        @endforeach
                        
                    </select>
                </div>
                <div class="col-md-1" style="text-align:left;">
                    師傅:
                </div>
                <div class="col-md-6">
                    <select name="order[@{{:order_index}}][service_provider_list][]" class="selectpicker selectWorker" multiple="" data-max-options="3" data-width="100%" tabindex="-98" title="選擇師傅" data-hide-disabled="true" required>
                        <option value="0">不指定</option>
                        <option value="0">不指定</option>
                        <option value="0">不指定</option>
                        @{{for service_provider_status}}
                        @{{if selected == false}}
                        <option value="@{{>id}}" data-index="@{{:index}}">@{{>info}}</option>
                        @{{else}}
                        <option value="@{{>id}}" data-index="@{{:index}}" disabled>@{{>info}}</option>
                        @{{/if}}
                        @{{/for}}
                    </select>
                </div>
                
            </div>
            <div class="row order@{{:order_index}}" style="margin-top:10px">
                <div class="col-md-1" style="text-align:left;">
                    房間:
                </div>
                <div class="col-md-3">
                    <select name="order[@{{:order_index}}][room_id]" class="form-control selectpicker selectRoom" title="選擇房間" data-hide-disabled="true" required>
                        @{{for room_status}}
                        @{{if selected == false}}
                        <option value="@{{>id}}" data-index="@{{:index}}">@{{>info}}</option>
                        @{{else}}
                        <option value="@{{>id}}" data-index="@{{:index}}" disabled>@{{>info}}</option>
                        @{{/if}}
                        @{{/for}}
                    </select>
                </div>
                <div class="col-md-1 col-md-offset-7">
                    <div class="btn btn-danger delete_order" data-order_index="@{{:order_index}}">刪除</div>
                </div>
            </div>
            <hr class="order@{{:order_index}}" />
        </script>
        <script id="status" type="x-jsrender">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="5" style="text-align:center;">師傅狀態</th>
                    </tr>
                </thead>
                <tbody>
                    @{{for service_provider_status}}
                    @{{if index % 5 == 0}}
                    <tr>
                    @{{/if}}
                        <td>@{{>info}}</td>
                    @{{if index % 5 == 4}}
                    </tr>
                    @{{/if}}
                    @{{/for}}
                </tbody>
            </table>
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="5" style="text-align:center;">房間狀態</th>
                    </tr>
                </thead>
                <tbody>
                    @{{for room_status}}
                    @{{if index % 5 == 0}}
                    <tr>
                    @{{/if}}
                        <td>@{{>info}}</td>
                    @{{if index % 5 == 4}}
                    </tr>
                    @{{/if}}
                    @{{/for}}
                </tbody>
            </table>
        </script>
        <script type="text/javascript">
        $(function() {
            var i = 0;

            var status_data = {
                service_provider_status: [],
                room_status: []
            };
            $('#add_order').on('click', function(){
                var myTemplate = $.templates("#order_form_template");

                var time = $("#choose_time").val();
                var shop = $("#choose_shop").val();

                if(time !== "" && shop !== undefined){
                    var html = myTemplate.render({
                        order_index: i,
                        service_provider_status: status_data.service_provider_status,
                        room_status: status_data.room_status
                    });

                    $('.detail').append(html);
                    $('.selectpicker').selectpicker({
                        size: 7
                    });
                    i++;
                }
                else{
                    alert("請先選擇店家及時間");
                }
            });

            $('body').on('click', '.delete_order',function(){
                var order_index = $(this).data('order_index');
                $(".order"+order_index).remove();
            });

            function limitSelect(){
                $('option').removeProp('disabled');
                for(var i in status_data.service_provider_status){
                    status_data.service_provider_status[i].selected = false;
                }
                for(var i in status_data.room_status){
                    status_data.room_status[i].selected = false;
                }
                $('.selectWorker').each(function(){
                    if($(this).context.localName == 'select'){
                        var worker_list = $(this).selectpicker('val');
                        for(var i in status_data.service_provider_status){
                            for(var k in worker_list){
                                if(worker_list[k] != 0 && status_data.service_provider_status[i].id == worker_list[k]){
                                    status_data.service_provider_status[i].selected = true;
                                }
                            }
                        }
                        $('.selectWorker').not(this).each(function(){
                            if($(this).context.localName == 'select'){
                                $(this).children().each(function(index, el) {
                                    for(var i in worker_list){
                                        if(worker_list[i] != 0 && $(this).val() == worker_list[i]){
                                            $(this).attr('disabled','disabled')
                                        }
                                    }
                                });
                            }
                        });
                    }
                });
                $('.selectRoom').each(function(){
                    if($(this).context.localName == 'select'){
                        var room_id = $(this).selectpicker('val');
                        console.log(room_id);
                        for(var i in status_data.room_status){
                            if(status_data.room_status[i].id == room_id){
                                status_data.room_status[i].selected = true;
                            }
                        }
                        $('.selectRoom').not(this).each(function(){
                            if($(this).context.localName == 'select'){
                                $(this).children().each(function(index, el) {
                                    if($(this).val() == room_id){
                                        $(this).attr('disabled','disabled')
                                    } 
                                });
                            }
                        });
                    }
                });
                $('.selectWorker').selectpicker('refresh');
                $('.selectRoom').selectpicker('refresh');
                console.log(status_data);
            }
            $('body').on('changed.bs.select', '.selectWorker, .selectRoom', function(e, clickedIndex, newValue, oldValue){
                if($(this).context.localName == 'select'){
                    limitSelect();
                }
            });

            $("#choose_time").on('click', function(){
                var today = new Date();
                today.setTime(today.getTime()+1000*60*60*8);
                document.getElementById("choose_time").value  = today.toISOString().substr(0, 16);
                var shop = $("#choose_shop").val();
                if(shop !== undefined && shop !== null){
                    $.ajax({
                        url: '/api/staff/check_status',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            time: today.toISOString().substr(0, 16),
                            shop_id: shop
                        },
                        success: function(data){
                            status_data = data;
                            $('.detail').html('');
                        }
                    });
                }
            });

            $("#choose_time,#choose_shop,#limit_time").on('change', function(){
                var time = $("#choose_time").val();
                var shop = $("#choose_shop").val();
                var limit = document.getElementById("limit_time").checked;
                
                if(time !== "" && shop !== undefined){
                    $.ajax({
                        url: '/api/staff/check_status',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            time: time,
                            shop_id: shop,
                            limit_time: limit
                        },
                        success: function(data){
                            status_data = data;
                            $('.detail').html('');
                        }
                    });
                }
            });

            $("#show_status").on('click', function(){
                show_status();
            });

            $('#orderForm').submit(function() {
                var count = 0;
                $('.selectWorker').each(function(){
                    if($(this).context.localName == 'select'){
                        var worker_list = $(this).selectpicker('val');
                        count += worker_list.length;
                    }
                });
                if(count == 0){
                    alert("沒有選擇師傅");
                    return false;
                }
                else if(count > status_data.service_provider_status.length){
                    alert("可選擇師傅超過上限");
                    return false;
                }
                return true;
            });

            function show_status(){
                var myTemplate = $.templates("#status"); 
                var html = myTemplate.render(status_data);
                swal({
                    title: '師傅&房間 狀態',
                    html: html,
                    width: "70%",
                    allowOutsideClick: false,
                    showCancelButton: false,
                    focusConfirm: false,
                    cancelButtonText:'取消',
                    showConfirmButton: false,
                    showCloseButton: true,
                });
            }
        });
            
        </script>
    </body>
</html>