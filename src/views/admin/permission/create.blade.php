@extends('geauth::layouts.app1')

@section('content')
    <div class="layui-card-body">
        <div class="layui-form" lay-filter="permission-form" id="permission-form">
            <div class="layui-form-item">
                <label class="layui-form-label help-require">上级菜单</label>
                <div class="layui-input-block">
                    <select name="pid" lay-verify="" lay-search>
                        @foreach($permissions as $permission)
                            @if($pid)
                                @if($permission['id'] == $pid)
                                    <option selected value={{ $permission['id'] }}>{{ $permission['spl'] }}{{ $permission['name'] }}</option>
                                @else
                                    <option value={{ $permission['id'] }}>{{ $permission['spl'] }}{{ $permission['name'] }}</option>
                                @endif
                            @else
                                <option value={{ $permission['id'] }}>{{ $permission['spl'] }}{{ $permission['name'] }}</option>
                            @endif
                        @endforeach
                    </select>
                    <p class="layui-icon help-block">必选，请选择上级菜单或顶级菜单（目前最多支持三级菜单）</p>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label help-require">菜单名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" placeholder="请输入菜单名称" autocomplete="off" class="layui-input">
                    <p class="layui-icon help-block">必填，请填写菜单名称（如：系统管理），建议字符不要太长，一般4-6个汉字</p>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label help-require">菜单链接</label>
                <div class="layui-input-block">
                    <input type="text" name="url" lay-verify="title" autocomplete="off" value="#" placeholder="请输入菜单链接" class="layui-input">
                    <p class="layui-icon help-block">必填，请填写系统节点（如：admin/user/index），节点加入权限管理时菜单才会自动隐藏，非规则内的不会隐藏；
                        正常情况下，在输入的时候会有自动提示。如果是上级菜单时，请填写"#"符号，不要填写地址或节点地址</p>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">菜单Icon</label>
                <div class="layui-input-block">
                    <div class="layui-input-inline">
                        <input placeholder="请输入或选择图标" name="icon" value='' class="layui-input">
                    </div>
                    <span style="padding:0 12px;min-width:45px" class='layui-btn layui-btn-primary'>
                    <i style="font-size:1.2em;margin:0" class='' id="icon-wrap"></i>
                </span>
                    <button onclick="selectIcon()" type='button' class='layui-btn layui-btn-primary'>选择图标</button>
                    <p class="layui-icon help-block">可选，设置菜单选项前置图标，目前只支持 Font Awesome 5.2.0 字体图标</p>
                </div>
            </div>
            <div class="layui-form-item layui-hide">
                <button class="layui-btn" lay-submit lay-filter="permission_submit" id="permission_submit">提交</button>
            </div>
        </>
    </div>
@endsection

@section('script')
    <script src="/geauth/plugins/jquery/jquery.min.js" type="text/javascript"></script>
    <script>
        function selectIcon() {
            layer.open({
                type: 2
                ,title: false
                ,closeBtn: 0
                ,content: "{{url('admin/permission/icon')}}"
                ,area: ['90%', '80%']
            });
        }
    </script>
@endsection
