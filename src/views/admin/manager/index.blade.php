@extends('geauth::layouts.app1')

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                {{--     表头       --}}
                <script type="text/html" id="toolbarUser">
                    <div class="layui-btn-container">
                        <button class="layui-btn layui-btn-xs" lay-event="add"><i class="layui-icon layui-icon-add-1"></i>添加用户</button>
                    </div>
                </script>
                <table id="users" lay-filter="users"></table>
                {{--      行内操作      --}}
                <script type="text/html" id="options">
                    <div class="layui-row">
                        <p class="layui-btn layui-btn-normal layui-btn-xs layui-col-md12" lay-event="pass"><i class="layui-icon layui-icon-auz">重置密码</i></p><br/>
                        @{{# if(d.id == 1){  }}
                        <span class="layui-btn layui-btn-xs layui-btn-disabled layui-col-md6" lay-event="edit"><i class="layui-icon layui-icon-edit">编辑</i></span>
                        <span class="layui-btn layui-btn-danger layui-btn-xs layui-btn-disabled layui-col-space2 layui-col-md5" lay-event="del"><i class="layui-icon layui-icon-delete">删除</i></span>
                        @{{# } else {  }}
                        <span class="layui-btn layui-btn-xs layui-col-md6" lay-event="edit"><i class="layui-icon layui-icon-edit">编辑</i></span>
                        <span class="layui-btn layui-btn-danger layui-btn-xs layui-col-space2 layui-col-md5" lay-event="del"><i class="layui-icon layui-icon-delete">删除</i></span>
                        @{{# }  }}
                    </div>

                </script>
            </div>
        </div>
    </div>
@endsection


@section('script')
<script>
    var $ = layui.$,
        table = layui.table,
        form = layui.form;

    table.render({
        elem: '#users'
        ,url: "{{url('admin/manager')}}"
        ,method: 'post'
        ,id: 'users'
        ,page: true
        ,limit: 15
        ,cols: [[
            {field: 'id', title: 'ID', sort: true, fixed: 'left'}
            ,{field: 'name', title: '用户名称'}
            ,{field: 'email', title: '用户邮箱'}
            ,{field: 'mobile', title: '用户手机'}
            ,{field: 'avatar', title: '用户头像', templet: function(d) {
                    return '<img style="width:50px;height:50px;height:auto;border-radius:50%;" src="/storage/'+ d.avatar +'" />';
                }}
            ,{title: '操作', align: 'center', templet: '#options'}
        ]]
        ,toolbar: '#toolbarUser'
        ,defaultToolbar: []
        ,done: function(res, curr, count) {
            //console.log(res);
        }
    });
    //监听表头
    table.on('toolbar(users)', function(obj) {
        switch(obj.event) {
            case 'add':
                layer.open({
                    type: 2
                    ,title: '添加管理员'
                    ,content: "{{url('admin/manager/create')}}"
                    ,maxmin: true
                    ,area: ['80%', '85%']
                    ,btn: ['确定', '取消']
                    ,traditional: true
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'user_submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);

                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            var role_arr = [];
                            layero.find('iframe').contents().find('input[type=checkbox]:checked').each(function() {
                                role_arr.push($(this).val());
                            });
                            field.role_id = role_arr.join(',');
                            //提交 Ajax 成功后，静态更新表格中的数据
                            $.ajax({
                                type:'post',
                                url: "{{url('admin/manager/create')}}",
                                data: field,
                                success: function(res) {
                                    if (res.code) {
                                        layer.msg('操作成功');
                                        layer.close(index);
                                        table.reload('users');
                                    } else {
                                        layer.msg('操作异常');
                                    }
                                }
                            });
                        });
                        submit.trigger('click');
                    }
                });
                break;
        }
    });
    //监听行内事件
    table.on('tool(users)', function (obj) {
        switch (obj.event) {
            case 'edit':
                url = "{{url('admin/manager/edit/id')}}";
                url = url.replace('id', obj.data.id);
                layer.open({
                    type: 2
                    ,title: '编辑用户'
                    ,content: url
                    ,maxmin: true
                    ,area: ['80%', '85%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'user_submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field; //获取提交的字段
                            //提交 Ajax 成功后，静态更新表格中的数据
                            var role_arr = [];
                            layero.find('iframe').contents().find('input[type=checkbox]:checked').each(function() {
                                role_arr.push($(this).val());
                            });
                            field.role_id = role_arr.join(',');
                            $.ajax({
                                type:'post',
                                url: url,
                                data: {_method:"PUT", data:field},
                                success: function(res) {
                                    if (res.code) {
                                        layer.msg('操作成功');
                                        table.reload('users'); //数据刷新
                                        layer.close(index); //关闭弹层
                                    } else {
                                        layer.msg('操作异常');
                                    }
                                }
                            });
                        });
                        submit.trigger('click');
                    }
                });
                break;
            case 'pass':
                url = "{{url('admin/manager/pass/uid')}}";
                url = url.replace('uid', obj.data.id);
                layer.open({
                    type: 2
                    ,title: '重置密码'
                    ,content: url
                    ,maxmin: true
                    ,area: ['50%', '50%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'user_submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            $.ajax({
                                type:'post',
                                url: "{{ url('admin/manager/pass') }}",
                                data: field,
                                success: function(res) {
                                    if (res) {
                                        layer.msg('操作成功');
                                        table.reload('users'); //数据刷新
                                        layer.close(index); //关闭弹层
                                    } else {
                                        layer.msg('操作异常');
                                    }
                                }
                            });
                        });
                        submit.trigger('click');
                    }
                });
                break;
            case 'del':
                layer.alert('确认删除吗？',{title:'警告'}, function() {
                    $.ajax({
                        type: 'post',
                        url: "{{url('admin/manager')}}",
                        data: {_method:"DELETE", id: obj.data.id},
                        success: function(res) {
                            if (res.code == 1) {
                                layer.msg(res.msg);
                                obj.del();
                            } else {
                                layer.msg(res.msg);
                            }
                        }
                    })
                })
                break;
        }
    })
    //监听搜索
    form.on('submit(LAY-user-front-search)', function(data){
        var field = data.field;
        //执行重载
        table.reload('LAY-user-manage', {
            where: field
        });
    });
</script>
@endsection

