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
                        <form action="/admin/blacklist/add" method="post">
                            {{ csrf_field() }}
                            <div class="col-sm-3">
                                <input type="text" class="form-control" maxlength="20" name="name" placeholder="姓名" required>
                            </div>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" maxlength="20" name="phone" placeholder="電話" required>
                            </div>
                            <div class="col-sm-6">
                                <div class="m-b-30">
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">Add <i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            @if ($errors->has('fail'))
                            <a href="#" style="color:red;">{{ $errors->first('fail') }}</a>
                            @endif
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
@stop