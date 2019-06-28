/**
 * http://git.oschina.net/hbbcs/bootStrap-addTabs
 * Created by joe on 2015-12-19.
 * @param {type} options {
 * content string||html 直接指定内容
 * close bool 是否可以关闭
 * monitor 监视的区域
 * }
 *
 * @returns
 */
$.fn.addtabs = function (options) {
    var obj = $(this);
    options = $.extend({
        content: '', //直接指定所有页面TABS内容
        close: true, //是否可以关闭
        monitor: 'body', //监视的区域
        nav: '.nav-addtabs',
        tab: '.tab-addtabs',
        iframeUse: true, //使用iframe还是ajax
        iframeHeight: $(window).height() - 50, //固定TAB中IFRAME高度,根据需要自己修改
        callback: function () {
            //关闭后回调函数
        }
    }, options || {});
    var navobj = $(options.nav);
    var tabobj = $(options.tab);
    if (history.pushState) {
        //浏览器前进后退事件
        $(window).on("popstate", function (e) {
            var state = e.originalEvent.state;
            if (state) {
                $("a[addtabs=" + state.id + "]", options.monitor).data("pushstate", true).trigger("click");
            }
        });
    }
    $(options.monitor).on('click', '[addtabs]', function (e) {
        if ($(this).attr('url').indexOf("javascript") !== 0) {
            if ($(this).is("a")) {
                e.preventDefault();
            }
            var id = $(this).attr('addtabs');
            var title = $(this).attr('title') ? $(this).attr('title') : $.trim($(this).text());
            var url = $(this).attr('url');
            var content = options.content ? options.content : $(this).attr('content');
            var ajax = $(this).attr('ajax') ? true : false;
            var state = ({
                url: url, title: title, id: id, content: content, ajax: ajax
            });

            document.title = title;
            if (history.pushState && !$(this).data("pushstate")) {
                var pushurl = url.indexOf("ref=addtabs") == -1 ? (url + (url.indexOf("?") > -1 ? "&" : "?") + "ref=addtabs") : url;
                window.history.pushState(state, title, pushurl);
            }
            $(this).data("pushstate", null);
            _add({
                id: id,
                title: $(this).attr('title') ? $(this).attr('title') : $(this).html(),
                content: content,
                url: url,
                ajax: ajax
            });
        }
    });

    navobj.on('click', '.close-tab', function (e) {
        id = $(this).prev("a").attr("aria-controls");
        _close(id);
        return false;
    });
    navobj.on('dblclick', 'li[role=presentation]', function (e) {
        $(this).find(".close-tab").trigger("click");
    });
    navobj.on('click', 'li[role=presentation]', function (e) {
        $("a[addtabs=" + $("a", this).attr("node-id") + "]").trigger("click");
    });

    $(window).resize(function () {
        $("#nav").width($("#header > .navbar").width() - $(".sidebar-toggle").outerWidth() - $(".navbar-custom-menu").outerWidth() - 20);
        _drop();
    });

    _add = function (opts) {
        var id, tabid, conid, url;
        id = opts.id;
        tabid = 'tab_' + opts.id;
        conid = 'con_' + opts.id;
        url = opts.url;
        url += (opts.url.indexOf("?") > -1 ? "&addtabs=1" : "?addtabs=1");
        navobj.find("[role='presentation']").removeClass('active');
        tabobj.find("[role='tabpanel']").removeClass('active');
        //如果TAB不存在，创建一个新的TAB
        if ($("#" + tabid).size() == 0) {
            //创建新TAB的title
            title = $('<li role="presentation" id="' + tabid + '"><a href="#' + conid + '" node-id="' + opts.id + '" aria-controls="' + id + '" role="tab" data-toggle="tab">' + opts.title + '</a></li>');
            //是否允许关闭
            if (options.close && $("li", navobj).size() > 0) {
                title.append(' <i class="close-tab fa fa-remove"></i>');
            }
            //创建新TAB的内容
            content = $('<div role="tabpanel" class="tab-pane" id="' + conid + '"></div>');
            //是否指定TAB内容
            if (opts.content) {
                content.append(opts.content);
            } else if (options.iframeUse && !opts.ajax) {//没有内容，使用IFRAME打开链接
                var height = options.iframeHeight;
                content.append('<iframe src="' + url + '" width="100%" height="' + height + '" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe></div>');
            } else {
                $.get(url, function (data) {
                    content.append(data);
                });
            }
            //加入TABS
            if ($('.tabdrop li').size() > 0) {
                $('.tabdrop ul').append(title);
            } else {
                navobj.append(title);
            }
            tabobj.append(content);
        }

        //激活TAB
        $("#" + tabid).addClass('active');
        $("#" + conid).addClass("active");
        _drop();
    };

    _close = function (id) {
        var tabid = 'tab_'+id;
        var conid = 'con_'+id;
        //如果关闭的是当前激活的TAB，激活他的前一个TAB
        if (obj.find("li.active").attr('id') == tabid) {
            if ($("#" + tabid).prev().not(".tabdrop").size() > 0) {
                $("#" + tabid).prev().not(".tabdrop").find("a").trigger("click");
            } else if ($("#" + tabid).next().size() > 0) {
                $("#" + tabid).next().trigger("click");
            }
        }
        //关闭TAB
        $("#" + tabid).remove();
        $("#" + conid).remove();
        _drop();
        options.callback();
    };

    _drop = function () {
        //创建下拉标签
        var dropdown = $('<li class="dropdown pull-right hide tabdrop"><a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;">' +
                '<i class="glyphicon glyphicon-align-justify"></i>' +
                ' <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>');
        //检测是否已增加
        if (!$('.tabdrop').html()) {
            dropdown.prependTo(navobj);
        } else {
            dropdown = navobj.find('.tabdrop');
        }
        //检测是否有下拉样式
        if (navobj.parent().is('.tabs-below')) {
            dropdown.addClass('dropup');
        }
        var collection = 0;

        var maxwidth = navobj.width() - 60;

        var liwidth = 0;
        //检查超过一行的标签页
        var litabs = navobj.append(dropdown.find('li')).find('>li').not('.tabdrop');
        var lisize = litabs.size();
        litabs.each(function (i, j) {
            liwidth += $(this).width();
            if (collection == 0 && i == lisize - 1 && liwidth <= navobj.width()) {
                return true;
            }
            if (liwidth > maxwidth) {
                dropdown.find('ul').append($(this));
                collection++;
            }
        });
        //如果有超出的，显示下拉标签
        if (collection > 0) {
            dropdown.removeClass('hide');
            if (dropdown.find('.active').length == 1) {
                dropdown.addClass('active');
            } else {
                dropdown.removeClass('active');
            }
        } else {
            dropdown.addClass('hide');
        }
    };
};
