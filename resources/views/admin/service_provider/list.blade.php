@extends('admin.layout')
@section('head')
 	<style type="text/css">
		.spinner {
		  margin: 100px auto;
		  width: 50px;
		  height: 40px;
		  text-align: center;
		  font-size: 10px;
		}

		.spinner > div {
		  background-color: #1abc9c;
		  height: 100%;
		  width: 6px;
		  display: inline-block;
		  
		  -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
		  animation: sk-stretchdelay 1.2s infinite ease-in-out;
		}

		.spinner .rect2 {
		  -webkit-animation-delay: -1.1s;
		  animation-delay: -1.1s;
		}

		.spinner .rect3 {
		  -webkit-animation-delay: -1.0s;
		  animation-delay: -1.0s;
		}

		.spinner .rect4 {
		  -webkit-animation-delay: -0.9s;
		  animation-delay: -0.9s;
		}

		.spinner .rect5 {
		  -webkit-animation-delay: -0.8s;
		  animation-delay: -0.8s;
		}

		@-webkit-keyframes sk-stretchdelay {
		  0%, 40%, 100% { -webkit-transform: scaleY(0.4) }  
		  20% { -webkit-transform: scaleY(1.0) }
		}

		@keyframes sk-stretchdelay {
		  0%, 40%, 100% { 
		    transform: scaleY(0.4);
		    -webkit-transform: scaleY(0.4);
		  }  20% { 
		    transform: scaleY(1.0);
		    -webkit-transform: scaleY(1.0);
		  }
		}
        .material-switch > input[type="checkbox"] {
            display: none;   
        }

        .material-switch > label {
            cursor: pointer;
            height: 0px;
            position: relative; 
            width: 40px;  
        }

        .material-switch > label::before {
            background: rgb(0, 0, 0);
            box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
            border-radius: 8px;
            content: '';
            height: 16px;
            margin-top: -8px;
            position:absolute;
            opacity: 0.3;
            transition: all 0.4s ease-in-out;
            width: 40px;
        }
        .material-switch > label::after {
            background: rgb(255, 255, 255);
            border-radius: 16px;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
            content: '';
            height: 24px;
            left: -4px;
            margin-top: -8px;
            position: absolute;
            top: -4px;
            transition: all 0.3s ease-in-out;
            width: 24px;
        }
        .material-switch > input[type="checkbox"]:checked + label::before {
            background: inherit;
            opacity: 0.5;
        }
        .material-switch > input[type="checkbox"]:checked + label::after {
            background: inherit;
            left: 20px;
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
                        <h4 class="page-title">師傅管理</h4>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                		<div class="col-sm-3">
                			<select class="form-control" id="choose_shop">
                				<option disabled selected value>選擇店家</option>
                				@foreach ($shops as $key => $shop)
                				<option value="{{ $shop->id }}">{{ $shop->name }}</option>
                				@endforeach
                			</select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" maxlength="20" id="serviceProviderName">
                        </div>
                        <div class="col-sm-6">
                            <div class="m-b-30">
                                <button id="addServiceProvider" class="btn btn-primary waves-effect waves-light">Add <i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
					<div class="spinner" style="display:none;">
						<div class="rect1"></div>
						<div class="rect2"></div>
						<div class="rect3"></div>
						<div class="rect4"></div>
						<div class="rect5"></div>
					</div>
                    <div id="serviceProviderContainer">  	
                    </div>
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
<script id="serviceProviderListTemplate" type="x-jsrender">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>師傅名稱</th>
                <th>指壓</th>
                <th>油壓</th>
                <th>油壓去角質</th>
                <th>刪除</th>
            </tr>
        </thead>
        <tbody>
            @{{for serviceProviders}}
            <tr class="gradeX">
                <td>@{{:name}}</td>
                <td>
                    <div class="material-switch">
                        <input id="@{{:id}}_service1" data-id=@{{:id}} data-service=1 @{{if service_1}} checked @{{/if}} type="checkbox"/>
                        <label for="@{{:id}}_service1" class="label-success"></label>
                    </div>
                </td>
                <td>
                    <div class="material-switch">
                        <input id="@{{:id}}_service2" data-id=@{{:id}} data-service=2 @{{if service_2}} checked @{{/if}} type="checkbox"/>
                        <label for="@{{:id}}_service2" class="label-success"></label>
                    </div>
                </td>
                <td>
                    <div class="material-switch">
                        <input id="@{{:id}}_service3" data-id=@{{:id}} data-service=3 @{{if service_3}} checked @{{/if}} type="checkbox"/>
                        <label for="@{{:id}}_service3" class="label-success"></label>
                    </div>
                </td>
                <td class="actions">
                    <a href="#" class="on-default remove-row" data-id=@{{:id}} data-shop_id=@{{:shop_id}}><i class="fa fa-trash-o"></i></a>
                </td>
            </tr>
            @{{/for}}
        </tbody>
    </table>
</script>
<script type="text/javascript">
	$("#choose_shop").on('change', function(e){
		$(".spinner").show();
		var id = $(this).val();
		renderList(id);
		$('.spinner').hide();
	})

    $("#addServiceProvider").on('click', function(){
        var id = $("option:selected").val();
        if(id == ""){
            swal(
              '尚未選擇店家',
              '請選擇店家再進行新增動作',
              'error'
            )
        }
        else{
            var name = $("#serviceProviderName").val();
            if(name == ""){
                swal(
                  '尚未輸入姓名',
                  '請輸入姓名再進行新增動作',
                  'error'
                )
            }
            else{
                $.ajax({
                    url: '/api/serviceprovider/add',
                    type: 'post',
                    data: {
                        id: id,
                        name: name
                    },
                    success: function(data){
                        renderList(id);
                        $("#serviceProviderName").val('');
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
        }
    }); 

    function renderList(id){
        $.ajax({
            url: '/api/serviceprovider/list',
            data: {
                id: id
            },
            success: function(data){
                var myTemplate = $.templates("#serviceProviderListTemplate");
                var html = myTemplate.render(data);
                $("#serviceProviderContainer").html(html);
            },
            error: function(e){
                swal(
                  '系統發生錯誤',
                  '請洽系統管理商!',
                  'error'
                )
            }
        })
    }

    $("#serviceProviderContainer").on('click', ".remove-row",function(e){
        var id = $(this).data("id");
        var shop_id = $(this).data("shop_id");
        swal({
            title: '確定刪除該名員工?',
            text: "此舉無法恢復紀錄！",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消',
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger',
            buttonsStyling: false,
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/api/serviceprovider/delete',
                    type: 'post',
                    data: {
                        id: id,
                    },
                    success: function(data){
                        renderList(shop_id);
                    },
                    error: function(e){
                        swal(
                          '系統發生錯誤',
                          '新增失敗 請洽系統管理商!',
                          'error'
                        )
                    }
                })
            } 
        });
    })

    $("#serviceProviderContainer ").on('change', 'input[type=checkbox]',function(e){
        var id = $(this).data('id');
        var service = $(this).data('service');
        $.ajax({
            url: '/api/serviceprovider/service',
            type: 'post',
            data: {
                id: id,
                service: service
            },
            success: function(data){
            },
            error: function(e){
                swal(
                  '系統發生錯誤',
                  '更改失敗 請洽系統管理商!',
                  'error'
                )
            }
        });
    });
</script>
@stop