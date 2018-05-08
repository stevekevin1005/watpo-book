@extends('admin.layout')
@section('head')
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
                        <h4 class="page-title">黑名單管理</h4>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="row">
                            <div class="col-sm-3">
                                <input type="text" class="form-control" maxlength="20" name="name" placeholder="姓名" id="name" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" maxlength="20" name="phone" placeholder="電話" id="phone" required>
                            </div>
                            <div class="col-sm-4">
                                <input type="text" class="form-control" maxlength="20" name="description" placeholder="描述" id="description" required>
                            </div>
                            <div class="col-sm-2">
                                <div class="m-b-30">
                                    <button id="add_blacklist" class="btn btn-primary">Add <i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 col-sm-offset-6">
                                <div class="btn btn-primary description">酒客</div>
                                <div class="btn btn-primary description">性騷擾</div>
                                <div class="btn btn-primary description">不付錢</div>
                                <div class="btn btn-primary description">咆哮</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end: page -->
            </div>
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <form action="/admin/blacklist/list" method="get">
                            <div class="row">
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" maxlength="20" name="name" placeholder="姓名" value="{{ $request->name }}">
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" maxlength="20" name="phone" placeholder="電話" value="{{ $request->phone }}">
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" class="form-control" maxlength="20" name="description" placeholder="描述" value="{{ $request->description }}">
                                </div>
                                <div class="col-sm-2">
                                    <div class="m-b-30">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"> <i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end: page -->
            </div>  
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>姓名</th>
                                    <th>電話</th>
                                    <th>逾期次數</th>
                                    <th>描述</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1 ?>
                            @foreach ($blackList as $user)
                                <tr>
                                    <td>{{ $i + ($blackList->currentPage() - 1) * 10 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>{{ $user->overtime }}</td>
                                    <td>{{ $user->description }}</td>
                                    <td><form action="/admin/blacklist/delete" method="post">{{ csrf_field() }}<input type="hidden" name="id" value="{{ $user->id }}"><input type="submit" class="btn btn-danger" value="刪除"></form></td>
                                </tr>
                                <?php $i++ ?>
                            @endforeach
                            </tbody>
                        </table>
                        {!! $blackList->links() !!}
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
<script type="text/javascript">
$(function(){
    $(".description").on('click', function(){
        var description = $(this).text();
        $('input[name=description]').val(description);
    });

    $("#add_blacklist").on('click', function(){
        var name = $('#name').val();
        var phone = $('#phone').val();
        var description = $("#description").val();
        if(description != ""){
            $.ajax({
                url: '/api/blacklist/add',
                type: 'post',
                dataType: 'json',
                data: {
                    name: name,
                    phone: phone,
                    description: description
                },
                success: function(res){
                    location.reload();
                },
                error: function(e){
                    swal(
                        '黑名單',
                        '新增失敗，請洽系統管理商！',
                        'error'
                    );
                }
            });
        }
        else{
            swal(
                '黑名單',
                '描述不得為空！',
                'error'
            );
        }
    });
});
</script>
@stop