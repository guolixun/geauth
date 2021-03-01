@extends('geauth::layouts.app1')

@section('content')
    <div class="layui-form" lay-filter="role-form" id="role-form" style="padding: 20px 30px 0 0;">
        <div class="layui-form-item">
            <label class="layui-form-label">角色名称</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="name" value="{{$role->name}}" />
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色状态</label>
            <div class="layui-input-block">
                @if($role->status == 1)
                    <input type="checkbox" checked="" name="status" lay-skin="switch" value="1" lay-filter="switchTest" lay-text="启用|禁用">
                @else
                    <input type="checkbox" name="status" lay-skin="switch" value="1" lay-filter="switchTest" lay-text="启用|禁用">
                @endif
            </div>
        </div>
        <div class="layui-form-item layui-hide">
            <input type="hidden" name="id" value="{{ $role->id }}">
            <button class="layui-btn" lay-submit lay-filter="role_submit" id="role_submit">提交</button>
        </div>
    </div>
@endsection

@section('script')
<script>
    var form = layui.form;
</script>
@endsection
