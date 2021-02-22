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
                            <h4 class="page-title">會員列表</h4>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <form action="/admin/report" method="get" class="form-horizontal with-pagination">
                                <div class="row row-m">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">姓名</label>
                                            <div class="col-md-8">
                                                <input type="text" name="name" value="{{ $request->name }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">電話</label>
                                            <div class="col-md-8">
                                                <input type="text" name="phone" value="{{ $request->phone }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">生日</label>
                                            <div class="col-md-8">
                                                <input type="date" name="birthdate" class="form-control" value="{{ $request->birthdate }}">
                                            </div>
                                        </div>
                                        <!-- /form-group-->
                                    </div>
                                </div>
                                <!-- /row-->
                                <div class="row row-m">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">身分證字號</label>
                                            <div class="col-md-8">
                                                <input type="text" name="id_card" value="{{ $request->id_card }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">會員狀態</label>
                                            <div class="col-md-8">
                                                <select name="status" class="form-control">
                                                    <option selected="true" value="">選擇狀態</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /row-->
                                <div class="row row-m">
                                    <div class="col-md-12 text-right">
                                        <a href="/admin/member/export?
									name={{$request->name}}&
									phone={{$request->phone}}&
									birthdate={{$request->birthdate}}&
									id_card={{$request->id_card}}&
									status={{$request->status}}" class="btn btn-danger" target="_blank">匯出</a>
                                        <input class="btn btn-primary" type="submit" value="查詢">
                                    </div>
                                </div>
                                <!-- /row-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end container -->
            <div class="container">
                <div class="card-box">
                    <table class="table table-striped">
                        <thead>
                        <th>姓名</th>
                        <th>電話</th>
                        <th>生日</th>
                        <th>身分證字號</th>
                        <th>累積點數</th>
                        <th>會員狀態</th>
                        <th>註冊時間</th>
                        <th></th>
                        </thead>
                        <tbody>
                        @foreach($member_list as $member)
                            <tr>
                                <td></td>
                                <td>{{$member->phone}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{$member->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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