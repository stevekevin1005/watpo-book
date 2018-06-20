<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="管理系統,泰和殿">
        <meta name="author" content="Coderthemes">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="/assets/images/favicon_1.ico">

        <title>泰和殿 - 管理介面</title>

        <link href="/assets/plugins/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css">
        <link href="/assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
        <link href="/assets/plugins/jquery-circliful/css/jquery.circliful.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/core.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/components.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/pages.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/menu.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/responsive.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
        <style type="text/css">
            .swal2-cancel{
                margin-right: 30px;
            }
            .content-page{
                min-height: 1000px;
            }
            body {
              position: static !important;
            }
        </style>
        @yield('head')
        <script src="/assets/js/modernizr.min.js"></script>

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
        
            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <div class="text-center">
                        <a href="/admin/dashboard" class="logo"><span>泰和殿管理後台</span> </a>
                    </div>
                </div>

                <!-- Navbar -->
                <div class="navbar navbar-default" role="navigation">
                    <div class="container">
                        <div class="">
                            <div class="pull-left">
                                <button class="button-menu-mobile open-left waves-effect">
                                    <i class="md md-menu"></i>
                                </button>
                                <span class="clearfix"></span>
                            </div>
                        </div>
                        <!--/.nav-collapse -->
                    </div>
                </div>
            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="sidebar-inner slimscrollleft">

                    <div id="sidebar-menu">
                        <ul>
                            <li class="text-muted menu-title">一般功能選單</li>
                            <li>
                                <a href="/admin/dashboard" class="waves-effect waves-primary "><i
                                        class="md md-dashboard"></i><span> 總覽 </span></a>
                            </li>
                            <li>
                                <a href="#" class="waves-effect waves-primary"><i class="md md-event-note"></i>
                                    <span> 預約排程 </span> 
                                </a>
                                <ul class="list-unstyled">
                                     @if(is_null(session('account_shop_id')) || session('account_shop_id') == 1)
                                    <li><a href="/admin/calendar/1">民生店</a></li>
                                    @endif
                                    @if(is_null(session('account_shop_id')) || session('account_shop_id') == 2)
                                    <li><a href="/admin/calendar/2">光復店</a></li>
                                    @endif
                                </ul>
                            </li>
                             @if(session('account_level') != 3)
                            <li>
                                <a href="/admin/order" class="waves-effect waves-primary"><i class="md md-view-list"></i><span> 訂單列表 </span></a>
                            </li>
                            @endif
                            <li>
                                <a href="/admin/logout" class="waves-effect waves-primary"><i class="md  md-reply"></i><span> 登出 </span></a>
                            </li>
                            @if(session('account_level') == 1)
                            <li class="text-muted menu-title">管理功能選單</li>
                            <li>
                                <a href="/admin/report" class="waves-effect waves-primary"><i class="md md-receipt"></i><span> 顧客調查表 </span></a>
                            </li>

                            <li>
                                <a href="/admin/blacklist/list" class="waves-effect waves-primary"><i class="md md-error"></i><span> 黑名單 </span></a>
                            </li>
                            <li>
                                <a href="/admin/leave" class="waves-effect waves-primary"><i class="md md-face-unlock"></i><span> 休假管理 </span></a>
                            </li>
                            <li>
                                <a href="/admin/shift" class="waves-effect waves-primary"><i class="md md-face-unlock"></i><span> 排班設定 </span></a>
                            </li>
                            <li>
                                <a href="#" class="waves-effect waves-primary"><i class="md md-people"></i><span> 員工管理 </span></a>
                                <ul class="list-unstyled">
                                    <li><a href="/admin/serviceprovider/list">師傅管理</a></li>
                                    <li><a href="/admin/account">帳號管理</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="/admin/log" class="waves-effect waves-primary"><i class="md md-content-paste"></i><span> 操作記錄 </span></a>
                            </li> 
                            @endif 
                        </ul>
                        <div class="clearfix"></div>
                    </div>


                    <div class="clearfix"></div>
                </div>

                
            </div>
            <!-- Left Sidebar End --> 
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            @yield('content')
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
            

        </div>
        <!-- END wrapper -->


    
        <script>
            var resizefunc = [];
        </script>

        <!-- Plugins  -->
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/bootstrap.min.js"></script>
        <script src="/assets/js/detect.js"></script>
        <script src="/assets/js/fastclick.js"></script>
        <script src="/assets/js/jquery.slimscroll.js"></script>
        <script src="/assets/js/jquery.blockUI.js"></script>
        <script src="/assets/js/waves.js"></script>
        <script src="/assets/js/wow.min.js"></script>
        <script src="/assets/js/jquery.nicescroll.js"></script>
        <script src="/assets/js/jquery.scrollTo.min.js"></script>
        <script src="/assets/plugins/switchery/switchery.min.js"></script>
        <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
        <!-- Moment  -->
        <script src="/assets/plugins/moment/moment.js"></script>
        
        <!-- Counter Up  -->
        <script src="/assets/plugins/waypoints/lib/jquery.waypoints.js"></script>
        <script src="/assets/plugins/counterup/jquery.counterup.min.js"></script>
        
        <!-- Sweet Alert  -->
        <script src="/assets/plugins/sweetalert/dist/sweetalert.min.js"></script>
        
        <!-- flot Chart -->
        <script src="/assets/plugins/flot-chart/jquery.flot.js"></script>
        <script src="/assets/plugins/flot-chart/jquery.flot.time.js"></script>
        <script src="/assets/plugins/flot-chart/jquery.flot.tooltip.min.js"></script>
        <script src="/assets/plugins/flot-chart/jquery.flot.resize.js"></script>
        <script src="/assets/plugins/flot-chart/jquery.flot.pie.js"></script>
        <script src="/assets/plugins/flot-chart/jquery.flot.selection.js"></script>
        <script src="/assets/plugins/flot-chart/jquery.flot.stack.js"></script>
        <script src="/assets/plugins/flot-chart/jquery.flot.crosshair.js"></script>

        <!-- circliful Chart -->
        <script src="/assets/plugins/jquery-circliful/js/jquery.circliful.min.js"></script>
        <script src="/assets/plugins/jquery-sparkline/jquery.sparkline.min.js"></script>

        <!-- skycons -->
        <script src="/assets/plugins/skyicons/skycons.min.js" type="text/javascript"></script>

        <!-- Todos app  -->
        <script src="/assets/pages/jquery.todo.js"></script>
        
        <!-- Chat App  -->
        <script src="/assets/pages/jquery.chat.js"></script>
        
        <!-- Page js  -->
        <script src="/assets/pages/jquery.dashboard.js"></script>

        <!-- Custom main Js -->
        <script src="/assets/js/jquery.core.js"></script>
        <script src="/assets/js/jquery.app.js"></script>
        <!-- Js render -->
        <script src="/assets/js/jsrender.min.js"></script>
        <!-- Js bootstrap-datetimepicker -->
        <script type="text/javascript" src="/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('.counter').counterUp({
                    delay: 100,
                    time: 1200
                });
                $('.circliful-chart').circliful();
            });

            /* BEGIN SVG WEATHER ICON */
            if (typeof Skycons !== 'undefined'){
            var icons = new Skycons(
                {"color": "#228bdf"},
                {"resizeClear": true}
                ),
                    list  = [
                        "clear-day", "clear-night", "partly-cloudy-day",
                        "partly-cloudy-night", "cloudy", "rain", "sleet", "snow", "wind",
                        "fog"
                    ],
                    i;

                for(i = list.length; i--; )
                icons.set(list[i], list[i]);
                icons.play();
            };
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function isiPhone(){
                return (
                    (navigator.platform.indexOf("iPhone") != -1) ||
                    (navigator.platform.indexOf("iPod") != -1)
                );
            }
        </script>
        @yield('script')
    </body>
</html>