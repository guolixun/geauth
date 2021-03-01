@extends('geauth::layouts.app1')

@section('link')
    <link rel="stylesheet" href="/geauth/plugins/ztree/zTreeStyle/zTreeStyle.css">
@endsection

@section('style')
    <style>
        ul.ztree li {
            white-space: normal !important;
        }

        ul.ztree li span.button.switch {
            margin-right: 5px;
        }

        ul.ztree ul ul li {
            display: inline-block;
            white-space: normal;
        }

        ul.ztree > li {
            padding: 15px 25px 15px 15px;
        }

        ul.ztree > li > ul {
            margin-top: 12px;
            border-top: 1px solid rgba(0, 0, 0, .1);
        }

        ul.ztree > li > ul > li {
            padding: 5px;
        }

        ul.ztree > li > a > span {
            font-weight: 700;
            font-size: 15px;
        }

        ul.ztree .level2 .button.level2 {
            background: 0 0;
        }
    </style>
@endsection

@section('content')
    <div class="layui-form" lay-filter="auth-form" id="auth-form" style="padding: 20px 30px 0 0;">
        <ul id="zTree" class="ztree notselect"></ul>
        <div class="hr-line-dashed"></div>
        <div class="layui-form-item text-center" style="float:right;">
            <input type="hidden" name="rid" value="{{ $rid }}" id="rid"/>
            <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="auth();" type='button'>保存数据</button>
            <button class="layui-btn layui-btn-danger layui-btn-sm" type='button' onclick="cancel();">取消编辑</button>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="/geauth/plugins/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/geauth/plugins/ztree/ztree.all.min.js"></script>
    <script>
        var zTreeObj;
        var setting = {
            check: {enable: true},
            view: {showIcon: false},
        };
        $(document).ready(function(){
            $.ajax({
                type: 'post',
                url: "{{ url('admin/role/read') }}",
                data: {'rid': $('#rid').val()},
                success: function(res) {
                    zTreeObj = $.fn.zTree.init($("#zTree"), setting, res);
                }
            })
        });
        //授权
        function auth() {
            //获取选中节点的ID集合
            var nodes = [], checkedData = zTreeObj.getCheckedNodes(true);
            for (var i in checkedData) if (checkedData[i].id) nodes.push(checkedData[i].id);
            $.ajax({
                type: 'post',
                data: {nodes: nodes, rid: $('#rid').val()},
                url: "{{url('admin/role/auth')}}",
                success: function(res) {
                    if (res) {
                        layer.msg('操作成功', {time: 2000});
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    }
                }
            })
        }
        //取消
        function cancel() {
            var index = parent.layer.getFrameIndex(window.name);
            parent.layer.close(index);
        }
    </script>
@endsection
