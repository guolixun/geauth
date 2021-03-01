@extends('geauth::layouts.app1')

@section('style')
    <style>
        .layui-table-tool {padding:0;}
        .layui-table-tool-temp {padding-right:0;}
        .layui-note {padding: 10px; margin:5px 0px;}
    </style>
@endsection

@section('content')
    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-card-body">
                <script type="text/html" id="toolbarPermission">
                    <div class="layui-btn-container">
                        <button class="layui-btn layui-btn-xs" lay-event="add"><i class="layui-icon layui-icon-add-1"></i>添加菜单</button>
                        <button class="layui-btn layui-btn-xs layui-btn-warm" lay-event="import">导入新菜单</button>
                    </div>
                    <div class="layui-bg-orange layui-note">请在开发人员指导下进行以上操作！</div>
                </script>
                <table id="permission" lay-filter="permission"></table>
                {{--      行内操作      --}}
                <script type="text/html" id="options">
                    <span class="layui-btn layui-btn-xs" lay-event="sub"><i class="layui-icon layui-icon-add-1">新增</i></span>
                    <span class="layui-btn layui-btn-normal layui-btn-xs" lay-event="edit"><i class="layui-icon layui-icon-edit">编辑</i></span>
                    <span class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del"><i class="layui-icon layui-icon-delete">删除</i></span>
                </script>
            </div>
        </div>
    </div>
@endsection


@section('script')
<script>
    var $ = layui.$;
        table = layui.table;

    table.render({
        elem: '#permission'
        ,id: 'permission'
        ,url: "{{url('admin/permission')}}"
        ,method: 'post'
        ,toolbar: '#toolbarPermission'
        ,defaultToolbar: []
        ,cols: [[
            {field: 'id', title: 'ID', width:'5%', sort: true, fixed: 'left'}
            ,{field: 'order', align: 'center', title: '菜单排序(<span style="color:red;font-size:10px;">点击编辑</span> )', width:'12%', edit:'text'}
            ,{field: 'icon', title: '菜单Icon', width:'5%', align: 'center', sort: true, templet: function(d) {
                    return '<i class="'+ d.icon +'"></i>'
                }}
            ,{title: '菜单名称', width:'30%', templet: function (d) {
                    return '<span>'+ d.spl +'</span>' + d.name;
                }}
            ,{field: 'url_', title: '菜单url', width:'18%', sort: true}
            ,{field: 'updated_at', title: '更新时间', width:'13%', sort: true}
            ,{title: '操作', align:'center', width:'17%', fixed: 'right', templet: '#options'}
        ]]
        ,done: function(res, curr, count) {
            // console.log(res);
            $('.layui-table').css("width","100%");
            $("th[data-field='bar']").css("border-right",'none');
        }
    });
    //监听表头事件
    table.on('toolbar(permission)', function(obj) {
        var checkStatus = table.checkStatus(obj.config.id);
        switch(obj.event) {
            case 'add':
                layer.open({
                    type: 2
                    ,title: '添加菜单'
                    ,content: "{{url('admin/permission/create')}}"
                    ,maxmin: true
                    ,area: ['60%', '80%']
                    ,btn: ['确定', '取消']
                    ,yes: function(index, layero){
                        var iframeWindow = window['layui-layer-iframe'+ index]
                            ,submitID = 'permission_submit'
                            ,submit = layero.find('iframe').contents().find('#'+ submitID);

                        //监听提交
                        iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                            var field = data.field;
                            $.ajax({
                                type:'post',
                                url: "{{url('admin/permission/create')}}",
                                data: field,
                                success: function(res) {
                                    if (res) {
                                        layer.msg('操作成功');
                                        table.reload('permission');
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
            case 'import':
                $.ajax({
                    type: 'post',
                    url: "{{url('admin/permission/import')}}",
                    data: {_method:"PUT"},
                    success: function(res) {
                        console.log(res);
                        /*if (res.code == 1) {
                            layer.msg(res.msg);
                            obj.del();
                        } else {
                            layer.msg(res.msg);
                        }*/
                    }
                })
                break;
        }
    });
    //监听行内单元格编辑
    table.on('edit(permission)', function(obj) {
        upData = '{"id":"'+ obj.data.id +'","'+ obj.field +'":"'+ obj.value +'"}';
        upDataObj = JSON.parse(upData);
        $.ajax({
            type: 'post',
            url: "{{url('admin/permission/update')}}",
            data: {_method:"PUT", data: upDataObj},
            success: function(res) {
                layer.msg('设置成功');
            }
        })
    })
    //监听行内事件
    table.on('tool(permission)', function(obj) {
            switch (obj.event) {
                case 'sub':
                    url_sub = "{{url('admin/permission/create/id')}}";
                    url_sub = url_sub.replace('id', obj.data.id);
                    layer.open({
                        type: 2
                        ,title: '添加子集菜单'
                        ,content: url_sub
                        ,maxmin: true
                        ,area: ['60%', '80%']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index]
                                ,submitID = 'permission_submit'
                                ,submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field;
                                $.ajax({
                                    type:'post',
                                    url: url_sub,
                                    data: field,
                                    success: function(res) {
                                        if (res) {
                                            layer.msg('操作成功');
                                            table.reload('permission');
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
                case 'edit':
                    url = "{{url('admin/permission/edit', ['id' => 'editID'])}}";
                    url = url.replace('editID', obj.data.id);
                    layer.open({
                        type: 2
                        ,title: '编辑菜单'
                        ,content: url
                        ,maxmin: true
                        ,area: ['60%', '80%']
                        ,btn: ['确定', '取消']
                        ,yes: function(index, layero){
                            var iframeWindow = window['layui-layer-iframe'+ index]
                                ,submitID = 'permission_submit'
                                ,submit = layero.find('iframe').contents().find('#'+ submitID);

                            //监听提交
                            iframeWindow.layui.form.on('submit('+ submitID +')', function(data){
                                var field = data.field;
                                upData = '{"id":"'+ obj.data.id +'","pid":"'+ field.pid +'","name":"'+ field.name +'","url":"'+ field.url +'","icon":"'+ field.icon +'"}';
                                upDataObj = JSON.parse(upData);
                                $.ajax({
                                    type: 'post',
                                    url: url,
                                    data: {_method:"PUT", data: upDataObj},
                                    success: function(res) {
                                        layer.msg('设置成功');
                                        table.reload('permission');
                                        layer.close(index);
                                    }
                                })
                            });
                            submit.trigger('click');
                        }
                    });
                    break;
                case 'del':
                    layer.alert('确认删除吗？',{title:'警告'}, function() {
                        $.ajax({
                            type: 'post',
                            url: "{{url('admin/permission')}}",
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
</script>
@endsection


