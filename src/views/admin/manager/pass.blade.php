@extends('geauth::layouts.app1')

@section('content')
    <div class="layui-form" lay-filter="user-form" id="user-form" style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">用户名称</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="name" value="{{ $user->name }}" disabled/>
                <span class="layui-icon help-block">登录账号不能重复，账号创建后不能再次修改</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">新的密码</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" lay-verify="required|pass" name="password"/>
                <span class="layui-icon help-block">密码必须6到12位，且不能出现空格<span style=""></span></span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" lay-verify="required|pass|confirmPass" name="repass"/>
                <span class="layui-icon help-block">密码必须6到12位，且不能出现空格<span style=""></span></span>
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <input type="hidden" name="id" value="{{ $user->id }}">
            <button class="layui-btn" lay-submit lay-filter="user_submit" id="user_submit">提交</button>
        </div>
    </div>
@endsection

@section('script')
<script>
    var $ = layui.$,
        form = layui.form;
    form.verify({
        pass: [
            /^[\S]{6,12}$/
            ,'密码必须6到12位，且不能出现空格'
        ],
        confirmPass:function(value){
            if($('input[name=password]').val() !== value)
                return '两次密码输入不一致！';
        }
    })
</script>
@endsection
