@extends('layout')
@section('head')
	<link rel="stylesheet" href="/assets/plugins/magnific-popup/dist/magnific-popup.css" />
 	<link rel="stylesheet" href="/assets/plugins/jquery-datatables-editable/datatables.css" />
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
                <th></th>
            </tr>
        </thead>
        <tbody>
            @{{for serviceProviders}}
            <tr class="gradeX">
                <td>@{{:name}}</td>
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
            text: "此舉會刪除該名員工所有相關記錄!",
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
</script>
@stop