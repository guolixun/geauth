$win = $(window),
    $body = $('body'),container = $('#LAY_app'),
    tabs = $('#LAY_app_tabsheader>li')
    element = layui.element,
    TABS_HEADER = '#LAY_app_tabsheader>li',
    TABS_BODY = 'layadmin-tabsbody-item',
    APP_BODY = '#LAY_app_body',
    FILTER_TAB_TBAS = 'layadmin-layout-tabs',
    APP_SPREAD_SM = 'layadmin-side-spread-sm',
    TABS_BODY = 'layadmin-tabsbody-item',
    APP_FLEXIBLE = 'LAY_app_flexible',
    ICON_SPREAD = 'layui-icon-spread-left',
    ICON_SHRINK = 'layui-icon-shrink-right',
    SIDE_SHRINK = 'layadmin-side-shrink',
    SIDE_MENU = 'LAY-system-side-menu',
    SHOW = 'layui-show', HIDE = 'layui-hide',
    THIS = 'layui-this';

var admin = {
    v: 'ge_sns_1.0'
    //屏幕类型
    ,screen: function(){
        var width = $win.width()
        if(width >= 1200){
            return 3; //大屏幕
        } else if(width >= 992){
            return 2; //中屏幕
        } else if(width >= 768){
            return 1; //小屏幕
        } else {
            return 0; //超小屏幕
        }
    }
    //记录最近一次点击的页面标签数据
    ,tabsPage: {}
    //获取页面标签主体元素
    ,tabsBody: function(index){
        return $(APP_BODY).find('.'+ TABS_BODY).eq(index || 0);
    }
    //切换页面标签主体
    ,tabsBodyChange: function(index, options){
        options = options || {};

        admin.tabsBody(index).addClass(SHOW).siblings().removeClass(SHOW);
        events.rollPage('auto', index);

        //执行 {setter.MOD_NAME}.tabsPage 下的事件
        layui.event.call(this, 'admin', 'tabsPage({*})', {
            url: options.url
            ,text: options.text
        });
    }
    //侧边伸缩
    ,sideFlexible: function(status){
        var app = container
            ,iconElem =  $('#'+ APP_FLEXIBLE)
            ,screen = admin.screen();

        //设置状态，PC：默认展开、移动：默认收缩
        if(status === 'spread'){
            //切换到展开状态的 icon，箭头：←
            iconElem.removeClass(ICON_SPREAD).addClass(ICON_SHRINK);

            //移动：从左到右位移；PC：清除多余选择器恢复默认
            if(screen < 2){
                app.addClass(APP_SPREAD_SM);
            } else {
                app.removeClass(APP_SPREAD_SM);
            }

            app.removeClass(SIDE_SHRINK)
        } else {
            //切换到搜索状态的 icon，箭头：→
            iconElem.removeClass(ICON_SHRINK).addClass(ICON_SPREAD);
            //移动：清除多余选择器恢复默认；PC：从右往左收缩
            if(screen < 2){
                app.removeClass(SIDE_SHRINK);
            } else {
                app.addClass(SIDE_SHRINK);
            }
            app.removeClass(APP_SPREAD_SM)
        }

        layui.event.call(this, 'admin', 'side({*})', {
            status: status
        });
    }
    //全屏
    ,fullScreen: function(){
        var ele = document.documentElement
            ,reqFullScreen = ele.requestFullScreen || ele.webkitRequestFullScreen
            || ele.mozRequestFullScreen || ele.msRequestFullscreen;
        if(typeof reqFullScreen !== 'undefined' && reqFullScreen) {
            reqFullScreen.call(ele);
        };
    }
    //退出全屏
    ,exitScreen: function(){
        var ele = document.documentElement
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
            document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
        }
    }
    //关闭当前 pageTabs
    ,closeThisTabs: function(){
        if(!admin.tabsPage.index) return;
        $(TABS_HEADER).eq(admin.tabsPage.index).find('.layui-tab-close').trigger('click');
    }

    //事件监听
    ,on: function(events, callback){
        return layui.onevent.call(this, 'admin', events, callback);
    }
    ,openTabsPage: function(url, text){
        //遍历页签选项卡
        var matchTo
            ,tabs = $('#LAY_app_tabsheader>li')
            ,path = url.replace(/(^http(s*):)|(\?[\s\S]*$)/g, '');

        tabs.each(function(index){
            var li = $(this)
                ,layid = li.attr('lay-id');

            if(layid === url){
                matchTo = true;
                admin.tabsPage.index = index;
            }
        });

        text = text || '新标签页';

        if(true){
            //如果未在选项卡中匹配到，则追加选项卡
            if(!matchTo){
                $(APP_BODY).append([
                    '<div class="layadmin-tabsbody-item layui-show">'
                    ,'<iframe src="'+ url +'" frameborder="0" class="layadmin-iframe"></iframe>'
                    ,'</div>'
                ].join(''));
                admin.tabsPage.index = tabs.length;
                element.tabAdd(FILTER_TAB_TBAS, {
                    title: '<span>'+ text +'</span>'
                    ,id: url
                    ,attr: path
                });
            }
        } else {
            var iframe = admin.tabsBody(admin.tabsPage.index).find('.layadmin-iframe');
            iframe[0].contentWindow.location.href = url;
        }

        //定位当前tabs
        element.tabChange(FILTER_TAB_TBAS, url);
        admin.tabsBodyChange(admin.tabsPage.index, {
            url: url
            ,text: text
        });
    }

}

//事件
var events = admin.events = {
    //伸缩
    flexible: function(othis){
        var iconElem = othis.find('#'+ APP_FLEXIBLE)
            ,isSpread = iconElem.hasClass(ICON_SPREAD);
        admin.sideFlexible(isSpread ? 'spread' : null);
    }

    //刷新
    ,refresh: function(){
        var ELEM_IFRAME = '.layadmin-iframe'
            ,length = $('.'+ TABS_BODY).length;

        if(admin.tabsPage.index >= length){
            admin.tabsPage.index = length - 1;
        }

        var iframe = admin.tabsBody(admin.tabsPage.index).find(ELEM_IFRAME);
        iframe[0].contentWindow.location.reload(true);
    }

    //输入框搜索
    ,serach: function(othis){
        othis.off('keypress').on('keypress',function(e){
            if(!this.value.replace(/\s/g, '')) return;
            //回车跳转
            if(e.keyCode === 13){
                var href = othis.attr('lay-action')
                    ,text = othis.attr('lay-text') || '搜索';

                href = href + this.value;
                text = text + ' <span style="color: #FF5722;">'+ admin.escape(this.value) +'</span>';

                //打开标签页
                layui.index.openTabsPage(href, text);

                //如果搜索关键词已经打开，则刷新页面即可
                events.serach.keys || (events.serach.keys = {});
                events.serach.keys[admin.tabsPage.index] = this.value;
                if(this.value === events.serach.keys[admin.tabsPage.index]){
                    events.refresh(othis);
                }

                //清空输入框
                this.value = '';
            }
        });
    }

    //点击消息
    ,message: function(othis){
        othis.find('.layui-badge-dot').remove();
    }

    //全屏
    ,fullscreen: function(othis){
        var SCREEN_FULL = 'layui-icon-screen-full'
            ,SCREEN_REST = 'layui-icon-screen-restore'
            ,iconElem = othis.children("i");

        if(iconElem.hasClass(SCREEN_FULL)){
            admin.fullScreen();
            iconElem.addClass(SCREEN_REST).removeClass(SCREEN_FULL);
        } else {
            admin.exitScreen();
            iconElem.addClass(SCREEN_FULL).removeClass(SCREEN_REST);
        }
    }

    //关闭当前标签页
    ,closeThisTabs: function(){
        var topAdmin = parent === self ? admin : parent.layui.admin;
        topAdmin.closeThisTabs();
    }

    //关闭其它标签页
    ,closeOtherTabs: function(type){
        var TABS_REMOVE = 'LAY-system-pagetabs-remove';
        if(type === 'all'){
            $(TABS_HEADER+ ':gt(0)').remove();
            $(APP_BODY).find('.'+ TABS_BODY+ ':gt(0)').remove();

            $(TABS_HEADER).eq(0).trigger('click');
        } else {
            $(TABS_HEADER).each(function(index, item){
                if(index && index != admin.tabsPage.index){
                    $(item).addClass(TABS_REMOVE);
                    admin.tabsBody(index).addClass(TABS_REMOVE);
                }
            });
            $('.'+ TABS_REMOVE).remove();
        }
    }

    //关闭全部标签页
    ,closeAllTabs: function(){
        events.closeOtherTabs('all');
        //location.hash = '';
    }
    //左右滚动页面标签
    ,rollPage: function(type, index){
        var tabsHeader = $('#LAY_app_tabsheader')
            ,liItem = tabsHeader.children('li')
            ,scrollWidth = tabsHeader.prop('scrollWidth')
            ,outerWidth = tabsHeader.outerWidth()
            ,tabsLeft = parseFloat(tabsHeader.css('left'));

        //右左往右
        if(type === 'left'){
            if(!tabsLeft && tabsLeft <=0) return;

            //当前的left减去可视宽度，用于与上一轮的页标比较
            var  prefLeft = -tabsLeft - outerWidth;

            liItem.each(function(index, item){
                var li = $(item)
                    ,left = li.position().left;

                if(left >= prefLeft){
                    tabsHeader.css('left', -left);
                    return false;
                }
            });
        } else if(type === 'auto'){ //自动滚动
            (function(){
                var thisLi = liItem.eq(index), thisLeft;

                if(!thisLi[0]) return;
                thisLeft = thisLi.position().left;

                //当目标标签在可视区域左侧时
                if(thisLeft < -tabsLeft){
                    return tabsHeader.css('left', -thisLeft);
                }

                //当目标标签在可视区域右侧时
                if(thisLeft + thisLi.outerWidth() >= outerWidth - tabsLeft){
                    var subLeft = thisLeft + thisLi.outerWidth() - (outerWidth - tabsLeft);
                    liItem.each(function(i, item){
                        var li = $(item)
                            ,left = li.position().left;

                        //从当前可视区域的最左第二个节点遍历，如果减去最左节点的差 > 目标在右侧不可见的宽度，则将该节点放置可视区域最左
                        if(left + tabsLeft > 0){
                            if(left - tabsLeft > subLeft){
                                tabsHeader.css('left', -left);
                                return false;
                            }
                        }
                    });
                }
            }());
        } else {
            //默认向左滚动
            liItem.each(function(i, item){
                var li = $(item)
                    ,left = li.position().left;

                if(left + li.outerWidth() >= outerWidth - tabsLeft){
                    tabsHeader.css('left', -left);
                    return false;
                }
            });
        }
    }

    //遮罩
    ,shade: function(){
        admin.sideFlexible();
    }
};

//点击事件
$body.on('click', '*[layadmin-event]', function(){
    var othis = $(this)
        ,attrEvent = othis.attr('layadmin-event');
    console.log(attrEvent);
    events[attrEvent] && events[attrEvent].call(this, othis);
});

//监听 tab 组件切换，同步 index
element.on('tab('+ FILTER_TAB_TBAS +')', function(data){
    admin.tabsPage.index = data.index;
});

//监听选项卡切换，改变菜单状态
admin.on('tabsPage(setMenustatus)', function(router){
    var pathURL = router.url, getData = function(item){
            return {
                list: item.children('.layui-nav-child')
                ,a: item.children('*[lay-href]')
            }
        }
        ,sideMenu = $('#'+ SIDE_MENU)
        ,SIDE_NAV_ITEMD = 'layui-nav-itemed'

        //捕获对应菜单
        ,matchMenu = function(list){
            list.each(function(index1, item1){
                var othis1 = $(item1)
                    ,data1 = getData(othis1)
                    ,listChildren1 = data1.list.children('dd')
                    ,matched1 = pathURL === data1.a.attr('lay-href');

                listChildren1.each(function(index2, item2){
                    var othis2 = $(item2)
                        ,data2 = getData(othis2)
                        ,listChildren2 = data2.list.children('dd')
                        ,matched2 = pathURL === data2.a.attr('lay-href');

                    listChildren2.each(function(index3, item3){
                        var othis3 = $(item3)
                            ,data3 = getData(othis3)
                            ,matched3 = pathURL === data3.a.attr('lay-href');

                        if(matched3){
                            var selected = data3.list[0] ? SIDE_NAV_ITEMD : THIS;
                            othis3.addClass(selected).siblings().removeClass(selected); //标记选择器
                            return false;
                        }

                    });

                    if(matched2){
                        var selected = data2.list[0] ? SIDE_NAV_ITEMD : THIS;
                        othis2.addClass(selected).siblings().removeClass(selected); //标记选择器
                        return false
                    }

                });

                if(matched1){
                    var selected = data1.list[0] ? SIDE_NAV_ITEMD : THIS;
                    othis1.addClass(selected).siblings().removeClass(selected); //标记选择器
                    return false;
                }

            });
        }

    //重置状态
    sideMenu.find('.'+ THIS).removeClass(THIS);

    //移动端点击菜单时自动收缩
    if(admin.screen() < 2) admin.sideFlexible();

    //开始捕获
    matchMenu(sideMenu.children('li'));
});

//监听侧边导航点击事件
element.on('nav(layadmin-system-side-menu)', function(elem){
    if(elem.siblings('.layui-nav-child')[0] && container.hasClass(SIDE_SHRINK)){
        admin.sideFlexible('spread');
        layer.close(elem.data('index'));
    };
    admin.tabsPage.type = 'nav';
});

//监听选项卡的更多操作
element.on('nav(layadmin-pagetabs-nav)', function(elem){
    var dd = elem.parent();
    dd.removeClass(THIS);
    dd.parent().removeClass(SHOW);
});

//同步路由
var setThisRouter = function(othis){
    var layid = othis.attr('lay-id')
        ,attr = othis.attr('lay-attr')
        ,index = othis.index();

    admin.tabsBodyChange(index, {
        url: attr
    });
    //location.hash = layid === setter.entry ? '/' : attr;
}
    ,TABS_HEADER = '#LAY_app_tabsheader>li';

//标签页标题点击
$body.on('click', TABS_HEADER, function(){
    var othis = $(this)
        ,index = othis.index();

    admin.tabsPage.type = 'tab';
    admin.tabsPage.index = index;

    setThisRouter(othis);
});

//监听 tabspage 删除
element.on('tabDelete('+ FILTER_TAB_TBAS +')', function(obj){
    var othis = $(TABS_HEADER+ '.layui-this');

    obj.index && admin.tabsBody(obj.index).remove();
    setThisRouter(othis);

    //移除resize事件
    admin.delResize();
});

//页面跳转
$body.on('click', '*[lay-href]', function(){
    var othis = $(this)
        ,href = othis.attr('lay-href')
        ,text = othis.attr('lay-text')
        ,router = layui.router();

    admin.tabsPage.elem = othis;
    //admin.prevRouter[router.path[0]] = router.href; //记录上一次各菜单的路由信息

    //执行跳转
    var topLayui = parent === self ? layui : top.layui;
    admin.openTabsPage(href, text || othis.text());
});

