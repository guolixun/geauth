@extends('geauth::layouts.app')

@section('link')
{{--    <link rel="stylesheet" href="{{ $static }}layui-v257/style/login.css">--}}
    <link rel="stylesheet" href="/geauth/css/login.css">
@endsection

@section('content')
    <div class="layadmin-user-login layadmin-user-display-show" id="LAY-user-login" style="display: none;">
        <div class="layadmin-user-login-main">
            <div class="layadmin-user-login-box layadmin-user-login-header">
                <h2>GEauth</h2>
                <p>Geauth管理平台</p>
            </div>
            <div class="layadmin-user-login-box layadmin-user-login-body layui-form">
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-username"
                           for="LAY-user-login-username"></label>
                    <input type="text" name="username" id="username" lay-verify="required" placeholder="用户名"
                           class="layui-input">
                </div>
                <div class="layui-form-item">
                    <label class="layadmin-user-login-icon layui-icon layui-icon-password"
                           for="LAY-user-login-password"></label>
                    <input type="password" name="password" id="login-password" lay-verify="required"
                           placeholder="密码" class="layui-input">
                </div>
                <div class="layui-form-item">
                    <button class="layui-btn layui-btn-fluid" lay-submit lay-filter="login-submit">登 陆</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptPrevTag')
<script>
@endsection

@section('layJS')
    form.render();
    //回车事件监听
    $(document).keydown(function(e){
        if(e.keyCode === 13){
            $('button[lay-filter="login-submit"]').click();
            return false;
        }
    })
    //提交
    form.on('submit(login-submit)', function(obj){
        $.ajax({
            type: 'post',
            data: obj.field,
            url: "{{ url('admin') }}",
            success: function(res) {
                console.log(res);
                if(res.code == 1) {
                    layer.msg('登录成功', {offset: '15px', icon:1,time:1000}, function() {
                        location.href = "{{ url('admin/index') }}";
                    })
                } else {
                    layer.msg(res.msg);
                }
            }
        })
    });
@endsection

@section('scriptNextTag')
</script>
@endsection


