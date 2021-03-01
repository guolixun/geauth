@extends('geauth::layouts.app1')

@section('content')
    <div class="layui-form" lay-filter="role-form" id="role-form" style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">角色名称</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="name" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色状态</label>
            <div class="layui-input-block">
                <input type="checkbox" checked="" name="status" lay-skin="switch" value="1" lay-filter="switchTest" lay-text="启用|禁用">
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <button class="layui-btn" lay-submit lay-filter="role_submit" id="role_submit">提交</button>
        </div>
    </div>
@endsection


