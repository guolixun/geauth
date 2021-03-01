<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ge-auth</title>
    <link rel="stylesheet" href="/geauth/layui-v257/css/layui.css">
    <link rel="stylesheet" href="/geauth/css/admin.css">
    <link rel="stylesheet" href="/geauth/css/common.css">

    {{--  自定义样式css  --}}
    @yield('link')

    {{--  页面样式style  --}}
    @yield('style')

</head>

<body>

    {{--  主体内容  --}}
    @yield('content')

</body>

<script src="/geauth/layui-v257/layui.all.js"></script>

{{--自定义js--}}
@yield('script')

{{--layui--}}
@yield('scriptPrevTag')

;!function() {
    var $ = layui.jquery
    ,form = layui.form
    ,table = layui.table
    ,upload = layui.upload
    ,table = layui.table
    ,layer = layui.layer
    ,element = layui.element
    ,carousel = layui.carousel
    ,device = layui.device();

    /*---------表格的工具按钮列显示更多的时候也能点击触发table的事件------------*/
    // 缓存当前操作的是哪个表格的哪个tr的哪个td
    $(document).off('mousedown', '.layui-table-grid-down')
    .on('mousedown', '.layui-table-grid-down', function (event) {
        table._tableTrCurr = $(this).closest('td');
    });

    $(document).off('click', '.layui-table-tips-main [lay-event]')
    .on('click', '.layui-table-tips-main [lay-event]', function (event) {
        var elem = $(this);
        var tableTrCurr = table._tableTrCurr;
        if (!tableTrCurr) {
            return;
        }
        var layerIndex = elem.closest('.layui-table-tips').attr('times');
        // 关闭当前这个显示更多的tip
        layer.close(layerIndex);
        table._tableTrCurr.find('[lay-event="' + elem.attr('lay-event') + '"]').first().click();
    });
    /*---------表格的工具按钮列显示更多的时候也能点击触发table的事件------------*/

    @yield('layJS')

}();

@yield('scriptNextTag')

</html>
