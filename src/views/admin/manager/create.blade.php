@extends('geauth::layouts.app1')

@section('style')
    <style>
        .img-wrap {
            width: 100px;
            height: 100px;
            cursor: pointer;
            position: relative;
            display: inline-block;
            border: 1px dashed #e2e2e2;
        }
    </style>
@endsection

@section('content')
    <div class="layui-form" lay-filter="user_form" id="user_form" style="padding: 20px 30px 0 0;">
        <div class="layui-card-body padding-left-40">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-xs2 text-align layui-upload">
                    <button type="button" class="layui-btn layui-btn-xs" id="avatar">上传图片</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img img-wrap" id="demo1">
                        <p id="demoText"></p>
                    </div>
                    <input type="hidden" name="avatar" value="" id="avatar_img">
                </div>
                <div class="layui-col-xs5">
                    <label class="block relative">
                        <span class="color-green font-w7">登录账号</span>
                        <span class="color-desc margin-left-5">Username</span>
                        <input name="name" value='' placeholder="请输入登录用户账号" class="layui-input" lay-verify="required|name">
                        <span class="layui-icon help-block">登录账号不能重复，账号创建后不能再次修改</span>
                    </label>
                </div>
                <div class="layui-col-xs5">
                    <label class="block relative">
                        <span class="color-green font-w7">用户密码</span>
                        <span class="color-desc margin-left-5">Password</span>
                        <input name="password" value='' lay-verify="required|pass" placeholder="请输入用户的密码" class="layui-input">
                        <span class="layui-icon help-block">密码必须6到12位，且不能出现空格<span style=""></span></span>
                    </label>
                </div>
                <div class="layui-col-xs5">
                    <label class="relative block">
                        <span class="color-green font-w7">联系邮箱</span><span class="color-desc margin-left-5">Contact Email</span>
                        <input name="email" value='' placeholder="请输入联系电子邮箱" class="layui-input">
                        <span class="layui-icon help-block">可选，请填写常用的电子邮箱</span>
                    </label>
                </div>
                <div class="layui-col-xs5">
                    <label class="relative block">
                        <span class="color-green font-w7">联系手机</span><span class="color-desc margin-left-5">Contact Mobile</span>
                        <input type="text" maxlength="11" name="mobile" value='' placeholder="请输入用户联系手机" class="layui-input">
                        <span class="layui-icon help-block">可选，请填写常用的联系手机号</span>
                    </label>
                </div>
            </div>
            <div class="hr-line-dashed margin-top-10 margin-bottom-10"></div>
            <div class="layui-form-item">
                <span class="color-green font-w7">访问权限</span>
                <span class="color-desc margin-left-5">Authorize</span>
                <div class="layui-textarea">
                    <div class="layui-form-item" pane="">
                        @foreach($roles as $role)
                            <input type="checkbox" name="role_id[]" lay-skin="primary" value="{{ $role->id }}" title="{{ $role->name }}">
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="hr-line-dashed"></div>
        </div>
        <div class="layui-form-item layui-hide">
            <button class="layui-btn" lay-submit lay-filter="user_submit" id="user_submit">提交</button>
        </div>
    </div>
@endsection

@section('script')
<script>
    var $ = layui.$,
        form = layui.form,
        upload = layui.upload;

    var uploadInst = upload.render({
        elem: '#avatar'
        ,url: "{{ url('admin/manager/avatar') }}"
        ,before: function(obj){
            //预读本地文件示例，不支持ie8
            obj.preview(function(index, file, result){
                $('#demo1').attr('src', result); //图片链接（base64）
            });
        }
        ,done: function(res){
            console.log(res);
            //如果上传失败
            if(res.code == 0){
                return layer.msg('上传失败');
            }
            //上传成功
            $('#avatar_img').val(res.path);
        }
        ,error: function(){
            //演示失败状态，并实现重传
            var demoText = $('#demoText');
            demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
            demoText.find('.demo-reload').on('click', function(){
                uploadInst.upload();
            });
        }
    });

    //自定义验证规则
    form.verify({
        name: [
            /^.{4,}$/,
            '用户名最少4个字符'
        ],
        pass: [
            /^[\S]{6,12}$/
            ,'密码必须6到12位，且不能出现空格'
        ]
    })
</script>
@endsection


