@extends('geauth::layouts.app1')

@section('content')
    <div id="LAY_app">
        <div class="layui-layout layui-layout-admin">
            <div class="layui-header">
                <!-- 头部区域 -->
                <ul class="layui-nav layui-layout-left">
                    <li class="layui-nav-item layadmin-flexible" lay-unselect>
                        <a href="javascript:;" layadmin-event="flexible" title="侧边伸缩">
                            <i class="layui-icon layui-icon-shrink-right" id="LAY_app_flexible"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="http://www.layui.com/admin/" target="_blank" title="前台">
                            <i class="layui-icon layui-icon-website"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;" layadmin-event="refresh" title="刷新">
                            <i class="layui-icon layui-icon-refresh-3"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <input type="text" placeholder="搜索..." autocomplete="off" class="layui-input layui-input-search" layadmin-event="serach" lay-action="template/search.html?keywords=">
                    </li>
                </ul>
                <ul class="layui-nav layui-layout-right" lay-filter="layadmin-layout-right">
                    <li class="layui-nav-item" lay-unselect>
                        <a lay-href="app/message/index.html" layadmin-event="message" lay-text="消息中心">
                            <i class="layui-icon layui-icon-notice"></i>
                            <!-- 如果有新消息，则显示小圆点 -->
                            <span class="layui-badge-dot"></span>
                        </a>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="fullscreen">
                            <i class="layui-icon layui-icon-screen-full"></i>
                        </a>
                    </li>
                    <li class="layui-nav-item" lay-unselect>
                        <a href="javascript:;">
                            <cite>{{ session('users.name') }}</cite>
                            <span class="layui-nav-more"></span>
                        </a>
                        <dl class="layui-nav-child">
                            <dd><a lay-href="{{ url('admin/manager/edit', [session('users.id')]) }}">基本资料</a></dd>
                            <dd><a lay-href="{{ url('admin/manager/pass', [session('users.id')]) }}">修改密码</a></dd>
                            <hr>
                            <dd id="logout" style="text-align: center;"><a onclick="logout()">退出</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item layui-hide-xs" lay-unselect>
                        <a href="javascript:;" layadmin-event="about"><i class="layui-icon layui-icon-more-vertical"></i></a>
                    </li>
                    <li class="layui-nav-item layui-show-xs-inline-block layui-hide-sm" lay-unselect>
                        <a href="javascript:;" layadmin-event="more"><i class="layui-icon layui-icon-more-vertical"></i></a>
                    </li>
                </ul>
            </div>

            <!-- 侧边菜单 -->
            <div class="layui-side layui-side-menu">
                <div class="layui-side-scroll">
                    <div class="layui-logo" lay-href="home/console.html">
                        <span>GE-AUTH</span>
                    </div>

                    <ul class="layui-nav layui-nav-tree" lay-shrink="all" id="LAY-system-side-menu">
                        <li data-name="home" class="layui-nav-item layui-nav-itemed">
                            <a href="javascript:;" lay-tips="主页" lay-direction="2">
                                <i class="layui-icon layui-icon-console"></i>
                                <cite>控制台</cite>
                            </a>
                            <dl class="layui-nav-child">
                                <dd data-name="console" class="layui-this" style="padding-left: 10%;">
                                    <a lay-href="home/console.html"><i class="layui-icon layui-icon-find-fill"></i>主页一</a>
                                </dd>
                                <dd data-name="console" style="padding-left: 10%;">
                                    <a lay-href="{{url('admin/show1')}}"><i class="layui-icon layui-icon-app"></i>主页二</a>
                                </dd>
                            </dl>
                        </li>
                        @foreach($menus as $menu)
                            <li data-name="app" class="layui-nav-item">
                                @if($menu['url'] == '#' || substr($menu['url'], -1) == '#')
                                <a href="javascript:;" lay-tips="{{ $menu['name'] }}">
                                @else
                                <a lay-href="/{{ $menu['url'] }}" lay-tips="{{ $menu['name'] }}">
                                @endif
                                    <i class="{{ $menu['icon'] }}"></i>
                                    <cite>{{ $menu['name'] }}</cite>
                                </a>
                                @if (isset($menu['children']))
                                    <dl class="layui-nav-child">
                                        @foreach( $menu['children'] as $children)
                                            <dd data-name="content" class="nav-padding-left">
                                                @if($children['url'] == '#')
                                                    <a href="javascript:;"><i class="{{ $children['icon'] }}"></i>{{ $children['name'] }}</a>
                                                @else
                                                    <a lay-href="/{{ $children['url'] }}"><i class="{{ $children['icon'] }}"></i>{{ $children['name'] }}</a>
                                                @endif

                                                @if (isset($children['children']))
                                                    <dl class="layui-nav-child">
                                                        @foreach($children['children'] as $sub)
                                                            <dd data-name="list"><a lay-href="/{{ $sub['url'] }}">{{ $sub['name'] }}</a></dd>
                                                        @endforeach
                                                    </dl>
                                                @endif
                                            </dd>
                                        @endforeach
                                    </dl>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- 页面标签 -->
            <div class="layadmin-pagetabs" id="LAY_app_tabs">
                <div class="layui-icon layadmin-tabs-control layui-icon-prev" layadmin-event="leftPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"></div>
                <div class="layui-icon layadmin-tabs-control layui-icon-down">
                    <ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav">
                        <li class="layui-nav-item" lay-unselect>
                            <a href="javascript:;"></a>
                            <dl class="layui-nav-child layui-anim-fadein">
                                <dd layadmin-event="closeThisTabs"><a href="javascript:;">关闭当前标签页</a></dd>
                                <dd layadmin-event="closeOtherTabs"><a href="javascript:;">关闭其它标签页</a></dd>
                                <dd layadmin-event="closeAllTabs"><a href="javascript:;">关闭全部标签页</a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
                <div class="layui-tab" lay-unauto lay-allowClose="true" lay-filter="layadmin-layout-tabs">
                    <ul class="layui-tab-title" id="LAY_app_tabsheader">
                        <li lay-id="home/console.html" lay-attr="home/console.html" class="layui-this"><i class="layui-icon layui-icon-home"></i></li>
                    </ul>
                </div>
            </div>
            <!-- 主体内容 -->
            <div class="layui-body" id="LAY_app_body">
                <div class="layadmin-tabsbody-item layui-show">
                    <iframe src="{{ url('admin/console') }}" frameborder="0" class="layadmin-iframe"></iframe>
                </div>
            </div>
            <!-- 辅助元素，一般用于移动设备下遮罩 -->
            <div class="layadmin-body-shade" layadmin-event="shade"></div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var $ = layui.jquery;
        //退出
        $('#logout').click(function() {
            $.getJSON("{{url('admin/logout')}}", function(res) {
                location.href = "/admin";
            })
        })
    </script>
    <script src="/geauth/layui-v257/js/admin.js"></script>
@endsection



