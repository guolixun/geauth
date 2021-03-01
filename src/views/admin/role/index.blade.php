@extends('geauth::layouts.app1')

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <script type="text/html" id="toolbarRole">
                    <div class="layui-btn-container">
                        <button class="layui-btn layui-btn-xs" lay-event="add"><i class="layui-icon layui-icon-add-1"></i>添加角色</button>
                    </div>
                </script>
                <table id="roles" lay-filter="roles"></table>
                {{--     行内开关       --}}
                @verbatim
                    <script type="text/html" id="switch_role" lay-filter="ckState">
                        <input type="checkbox" lay-filter="ckState" lay-skin="switch" name="status" lay-text="正常|禁用" value="{{d.id}}" {{ d.status == 1 ? 'checked' : '' }}>
                    </script>
                @endverbatim
                {{--      行内操作      --}}
                <script type="text/html" id="options">
                    <span class="layui-btn layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit">编辑</i></span>
                    <span class="layui-btn layui-btn-normal layui-btn-xs" lay-event="auth"><i class="layui-icon layui-icon-auz">授权</i></span>
                    <span class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete">删除</i></span>
                </script>

            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
    var $ = layui.$,
        form = layui.form,
        table = layui.table;

    table.render({
        elem: '#roles'
        ,id: 'roles'
        ,url: "{{url('admin/role')}}"
        ,method: 'post'
        ,toolbar: '#toolbarRole'
        ,defaultToolbar: []
        ,page: true
        ,limit: 15
        ,cols: [[ //表头
            {field: 'id', title: 'ID', sort: true, fixed: 'left'}
            ,{field: 'name', title: '角色名称', sort: true}
            ,{title: '角色状态', align: 'center', templet: '#switch_role'}
            ,{field: 'updated_at', title: '更新时间', sort: true}
            ,{title: '操作', align:'center',fixed: 'right', templet: '#options'}
        ]]
        ,done: function(res, curr, count) {
            //console.log(res);
        }
    });
    //监听表头事件
    table.on('toolbar(roles)', function(obj) {
        switch(obj.event) {
            case 'add':
                layer.open({
                    type: 2
                    ,title: '添加角色'
                    ,content: "{{url('admin/role/create')}}"
                    ,maxmin: true
                    ,area: ['500px', '450px']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'role_submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);

                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            $.ajax({
                                type:'post',
                                url: "{{url('admin/role/create')}}",
                                data: field,
                                success: function(res) {
                                    if (res) {
                                        layer.msg('操作成功');
                                        layer.close(index); //关闭弹层
                                        table.reload('roles'); //数据刷新
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
            case 'edit':
                alert('edit');
                break;
            case 'del':
                alert('del');
                break;
        }
    });
    //监听行内开关
    form.on('switch(ckState)', function(obj){
        layer.tips('确认执行操作吗?',obj.othis,{btn:['确定', '取消'],time:0,tips:3,yes: function() {
                var checked = obj.elem.checked ? 1 : 0;
                url = "{{url('admin/role/edit/id')}}";
                url = url.replace('id', obj.elem.value);
                $.ajax({url: url, type: 'post', data: {id:obj.elem.value, data:{'status': checked}}, success(res) {
                    layer.closeAll();
                    res ? layer.msg('设置成功') : layer.msg('操作异常');
                }})
            },btn2: function() {
                //取消操作则重新渲染switch
                obj.elem.checked = !obj.elem.checked;
                form.render('checkbox');
            }});
    });
    //监听行内事件
    table.on('tool(roles)', function (obj) {
        switch (obj.event) {
            case 'edit':
                url = "{{url('admin/role/edit/id')}}";
                url = url.replace('id', obj.data.id);
                layer.open({
                    type: 2
                    ,title: '编辑角色'
                    ,content: url
                    ,maxmin: true
                    ,area: ['500px', '450px']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'role_submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            if(data.field.status == undefined) data.field.status = 0;
                            $.ajax({
                                type:'post',
                                url: url,
                                data: {_method:"PUT", data: data.field},
                                success: function(res) {
                                    if (res) {
                                        layer.msg('操作成功');
                                        table.reload('roles');
                                        layer.close(index);
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
            case 'auth':
                url = "{{url('admin/role/read/rid')}}";
                url = url.replace('rid', obj.data.id);
                console.log(url);
                layer.open({
                    type: 2
                    ,title: '<span style="color:red;font-size: 14px;">' + obj.data.name +  '</span>:授权'
                    ,content: url
                    ,maxmin: true
                    ,area: ['98%', '95%']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'auth_submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);
                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            console.log(1111);
                            var field = data.field; //获取提交的字段
                            var nodes = [], checkedData = zTreeObj.getCheckedNodes(true);
                            for (var i in checkedData) if (checkedData[i].id) nodes.push(checkedData[i].id);
                            console.log(nodes);
                            //提交 Ajax 成功后，静态更新表格中的数据
                            {{--$.ajax({--}}
                            {{--    type:'post',--}}
                            {{--    url: "{{ url('admin/role/update/') }}",--}}
                            {{--    data: {_method:"PUT", data:field},--}}
                            {{--    success: function(res) {--}}
                            {{--        if (res) {--}}
                            {{--            layer.msg('操作成功');--}}
                            {{--            table.reload('roles'); //数据刷新--}}
                            {{--            layer.close(index); //关闭弹层--}}
                            {{--        } else {--}}
                            {{--            layer.msg('操作异常');--}}
                            {{--        }--}}
                            {{--    }--}}
                            {{--});--}}
                        });
                        submit.trigger('click');
                    }
                });
                break;
            case 'del':
                layer.alert('确认删除吗？',{title:'警告'}, function() {
                    $.ajax({
                        type: 'post',
                        url: "{{url('admin/role')}}",
                        data: {_method:"DELETE", id: obj.data.id},
                        success: function(res) {
                            if (res.code == 1) {
                                layer.msg(res.msg);obj.del();
                            } else {
                                layer.msg(res.msg);
                            }
                        }
                    })
                })
                break;
        }
    })
</script>
@endsection

