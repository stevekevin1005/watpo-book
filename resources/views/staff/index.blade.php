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
            <div class="card-box" style="position:fixed; z-index:1000; height:80px; width:100%;">
                <div class="row">
                    <div class="col-md-3">
                        <select id="choose_shop" class="form-control">
                            <option disabled selected value>選擇店家</option>
                            @foreach ($shops as $key => $shop)
                            <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><input type="datetime-local" id="choose_time"></div>
                    <div class="col-md-4"><button class="btn btn-primary" id="show_status">確認狀態</button></div>
                </div>
            </div>
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container">
                        
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
                                    <div id="submit_row" class="row" style="margin-top:10px">
                                        <div class="col-md-12 text-right">
                                            <button class="btn btn-primary" id="add_order">新增下一筆</button>
                                            <button class="btn btn-danger" id="delete_order">刪除最後一筆</button>
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
                    房間:
                </div>
                <div class="col-md-3">
                    <select name="order[@{{:order_index}}][room_id]" class="form-control">
                        @{{for room_status}}
                        <option value="@{{>id}}">@{{>info}}</option>
                        @{{/for}}
                    </select>
                </div>
                <div class="col-md-1" style="text-align:left;">
                    師傅:
                </div>
                <div class="col-md-6">
                    <select name="order[@{{:order_index}}][service_provider_list][]" class="selectpicker" multiple="" data-max-options="4" data-width="100%" tabindex="-98">
                        <option value="0">不指定</option>
                        <option value="0">不指定</option>
                        <option value="0">不指定</option>
                        @{{for service_provider_status}}
                        <option value="@{{>id}}">@{{>info}}</option>
                        @{{/for}}
                    </select>
                </div>
            </div>
            <div class="row order@{{:order_index}}" style="margin-top:10px">
                <div class="col-md-1" style="text-align:left;">
                    服務:
                </div>
                <div class="col-md-3">
                    <select name="order[@{{:order_index}}][service_id]" id="select_service" class="form-control">
                        <option value="1">泰式古法指壓 (1小時)</option>
                    </select>
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
            $('.selectpicker').selectpicker({
                size: 4
            });
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
                    $(html).insertBefore("#submit_row");
                    $('.selectpicker').selectpicker({
                        size: 4
                    });
                    i++;
                }
                else{
                    alert("請先選擇店家及時間");
                }
            });

            $('#delete_order').on('click', function(){
                if(i > 0){
                    $(".order"+(i-1)).remove();
                    i--;
                }
            });

            $("#choose_time").on('click', function(){
                var today = new Date();
                today.setTime(today.getTime()+1000*60*60*8);
                document.getElementById("start_time").value  = today.toISOString().substr(0, 16);
            });

            $("#choose_time,#choose_shop").on('change', function(){
                var time = $("#choose_time").val();
                var shop = $("#choose_shop").val();

                if(time !== "" && shop !== undefined){
                    $.ajax({
                        url: '/api/staff/check_status',
                        type: 'get',
                        dataType: 'json',
                        data: {
                            time: time,
                            shop_id: shop
                        },
                        success: function(data){
                            status_data = data;
                        }
                    });
                }
            });

            $("#show_status").on('click', function(){
                show_status();
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