function AdminUI() {

}

AdminUI.isEmpty = function (obj) {
    if (typeof obj == "undefined" || obj == null || obj == "") {
        return true;
    } else {
        return false;
    }
};

AdminUI.jQueryPath = 'https://cdn.bootcdn.net/ajax/libs/jquery/3.5.0/jquery.min.js';

AdminUI.resourceType = {
    js: 1,
    css: 2
};


AdminUI.welcomePage = './welcome.html';

AdminUI.uiResource = [
    {url: AdminUI.jQueryPath , type:AdminUI.resourceType.js},
    {url: "/Css/common.css", type: AdminUI.resourceType.css}
];

AdminUI.loadResource = function (file, type, callback) {
    let element;
    let fn = callback || function () {
    };
    if (type == AdminUI.resourceType.css) {
        element = document.createElement('link');
        element.setAttribute("rel", "stylesheet");
        element.setAttribute("type", "text/css");
        element.setAttribute("href", file);
    } else if (type == AdminUI.resourceType.js) {
        element = document.createElement('script');
        element.type = 'text/javascript';
        element.src = file;
    }
    if (type != AdminUI.resourceType.css) {
        if (element.readyState) {
            //IE
            element.onreadystatechange = function () {
                if (element.readyState == 'loaded' || element.readyState == 'complete') {
                    element.onreadystatechange = null;
                    fn();
                }
            };
        } else {
            //其他浏览器
            element.onload = function () {
                fn();
            };
        }
    }
    document.getElementsByTagName('head')[0].appendChild(element);
};

AdminUI.init = function (callback) {
    let fn = callback || function () {
    };
    let loadCount = 0;
    for (var i in this.uiResource) {
        if (this.uiResource[i].type == this.resourceType.js) {
            loadCount++;
            this.loadResource(this.uiResource[i].url, this.uiResource[i].type, function () {
                loadCount--;
                if (loadCount == 0) {
                    fn();
                }
            });
        } else {
            this.loadResource(this.uiResource[i].url, this.uiResource[i].type);
        }
    }
    return this;
};

AdminUI.UI = function () {

};
AdminUI.UI.showStatus = function (item, item2) {
    item.addClass('d-inlink').removeClass('d-none');
    item2.addClass('d-none').removeClass('d-inlink');
};

AdminUI.UI.windowWidth = function (window_Width, _leftMenu) {
    var _aIcon = $('.account-icon');
    var _aName = $('.account-name');
    if (window_Width < 768) {
        _leftMenu.hide();
        AdminUI.UI.showStatus(_aIcon, _aName);
    } else {
        AdminUI.UI.showStatus(_aName, _aIcon);
        _leftMenu.show();
    }
};
//点击左侧导航栏公共事件
AdminUI.UI.leftAsideEvent = function (_this, aHref, dClass, aTitle) {
    var _iframeBox = $('.iframe-box');
    var _iframeNavBox = $('.iframe-nav-box');
    $('.iframe-box iframe').hide();
    $('.left-aside .b-green').removeClass('b-green');
    _this.addClass('b-green');
    $('.iframe-nav-box .b-f6').removeClass('b-f6');
    if ($(window).width() < 768) $('#left-menu').slideUp();
    if (_iframeNavBox.find('.' + dClass).length < 1) {
        var html = '<div class="tabnav-box border-left-0 ' + dClass + ' b-f6" data-href="' + aHref + '" data-class="' + dClass + '" ><span>' + aTitle + '</span>' +
            '<span class="nav-close"></span></div>';
        _iframeNavBox.append(html);
    } else {
        _iframeNavBox.find('.' + dClass).addClass('b-f6').siblings().removeClass('b-f6');
    }
    var len2 = _iframeBox.find('.' + dClass).length;
    if (len2 < 1) {
        var html2 = '<iframe class="' + dClass + '" src="' + aHref + '"></iframe>';
        _iframeBox.append(html2);
    } else {
        _iframeBox.find('.' + dClass).show();
    }
};
//关闭右侧导航栏公共事件
AdminUI.UI.iframeNavCloseEvent = function (_this, dClass, dClass2) {
    var leftMenuSelect = $('#left-menu .' + dClass2);
    $('.iframe-nav-box>div').removeClass('b-f6');
    $('#left-menu .b-green').removeClass('b-green');
    $('#left-menu .rotate180').removeClass('rotate180');
    $('#left-menu>dl>dd').hide();
    if (dClass2 != undefined && dClass2 != null && dClass2 != "") {
        $('.iframe-nav-box .' + dClass2).addClass('b-f6');
        leftMenuSelect.parent().show();
        leftMenuSelect.addClass('b-green');
        leftMenuSelect.parents('dl').find('dt>i').addClass('rotate180');
        $('.iframe-box .' + dClass2).show();
    } else {
        $('.welcome-home').addClass('b-f6');
        $('#welcomePage').show();
    }
    _this.remove();
    $('.iframe-box .' + dClass).remove();
};
AdminUI.buildFrame = function (jsonFilePath) {
    //欢迎页
    document.getElementById('welcomePage').setAttribute('src', this.welcomePage);
    //请求菜单内容
    $.ajax({
        url: jsonFilePath,
        dataType: 'json',
        success: function (msg) {
            console.log(msg);
            menuData = msg.result.menu;
            menuHtml = '';
            var serialNum = 0;
            for (var i in menuData) {
                sonHtml = menuData[i]['list'];
                num = Number(i) + 1;
                menuHtml += '<dl id="menu-item' + num + '"><dt  data-class="c-nav-' + num + '" class="c-nav-' + num + '">';
                menuHtml += '<a data-href="' + menuData[i]['href'] + '">' + menuData[i]['name'] + '</a>';
                if (sonHtml.length > 1) menuHtml += '<i></i>';
                menuHtml += '</dt><dd>';
                for (var j in sonHtml) {
                    serialNum++;
                    menuHtml += '<div class="id-nav-' + serialNum + '"><a class="o-eli" data-href="' + sonHtml[j]['href'] + '" data-class="id-nav-' + serialNum + '">' + sonHtml[j]['title'] + '</a></div>';
                }
                menuHtml += '</dd></dl>';
            }
            document.getElementById("left-menu").innerHTML = menuHtml;
            document.getElementById("admin-grade").innerHTML = msg.result.account;
        }
    });
    //浏览器宽度小于767px,导航按钮
    var _body = $('body');
    var _aWindow = $(window);
    var window_Width = _aWindow.width();//获取浏览器窗口宽度
    var _topNavAccount = $('.top-nav-account');
    var _closeMenu = $('#close-menu');
    var _leftMenu = $('#left-menu');
    var _welcomeHome = $('.welcome-home');
    AdminUI.UI.windowWidth(window_Width, _leftMenu);
    _body.on('click', '.account-icon', function () {
        _leftMenu.slideToggle();
    });
    _aWindow.resize(function () {
        window_Width = _aWindow.width();
        AdminUI.UI.windowWidth(window_Width, _leftMenu);
    });
    // fadeIn fadeOut
    _topNavAccount.hover(function () {
        if (window_Width >= 768)
            $(this).find('.dropdown-menu').show();
    }, function () {
        if (window_Width >= 768)
            $(this).find('.dropdown-menu').hide();
    });
    // 关闭菜单
    _closeMenu.hover(function () {
        $(this).find('.close-menu').show();
    }, function () {
        $(this).find('.close-menu').hide();
    });
    //左侧菜单栏旋转特效
    _body.on('click', '.left-aside>dl>dt', function () {
        var _this = $(this);
        var aHref = _this.find('a').attr('data-href');
        var dClass = _this.attr('data-class');
        var aTitle = _this.find('a').text();
        if (aHref.length > 0) AdminUI.UI.leftAsideEvent(_this, aHref, dClass, aTitle);
        _this.parent().find('dd').slideToggle();
        _this.parent().siblings().find('dd').slideUp();
        _this.parent().siblings().find('i').removeClass('rotate180');
        _this.find('i').toggleClass('rotate180');

    });
    //左侧菜单栏点击事件
    _body.on('click', '.left-aside>dl>dd>div', function () {
        var _this = $(this);
        var aTitle = _this.children().text();
        var dClass = _this.children().attr('data-class');
        var aHref = _this.children().attr('data-href');
        AdminUI.UI.leftAsideEvent(_this, aHref, dClass, aTitle);
    });
    //右侧头部导航
    _body.on('click', '.iframe-nav-box>div', function () {
        var _this = $(this);
        var dClass = _this.attr('data-class');
        var leftMenuSelect = $('#left-menu .' + dClass);
        if ($(this).index() > 0) {
            $('.iframe-nav-box .' + dClass).addClass('b-f6').siblings().removeClass('b-f6');
            $('.iframe-box .' + dClass).show().siblings().hide();
        } else {
            $('.iframe-nav-box>div:nth-of-type(1)').addClass('b-f6').siblings().removeClass('b-f6');
            $('.iframe-box>iframe:nth-of-type(1)').show().siblings().hide();
        }
        $('#left-menu .b-green').removeClass('b-green');
        leftMenuSelect.addClass('b-green');
        $('#left-menu>dl>dd').hide();
        leftMenuSelect.parents('dd').show();
        $('#left-menu .rotate180').removeClass('rotate180');
        leftMenuSelect.parents('dl').find('dt>i').addClass('rotate180');
    });
    //右侧子导航双击关闭
    _body.on('dblclick', '.iframe-nav-box>div:not(div:nth-of-type(1))', function () {
        var _this = $(this);
        var dClass = _this.attr('data-class');
        var dClass2 = _this.prev().attr('data-class');
        AdminUI.UI.iframeNavCloseEvent(_this, dClass, dClass2);
    });
    //右侧子导航关闭图标
    _body.on('click', '.nav-close', function (event) {
        event.stopPropagation();
        var _this = $(this).parent();
        var dClass = _this.attr('data-class');
        var dClass2 = _this.prev().attr('data-class');
        AdminUI.UI.iframeNavCloseEvent(_this, dClass, dClass2);
    });
    //关闭当前iframe
    _body.on('click', '#close-now', function () {
        var dClass = $('.iframe-nav-box .b-f6').attr('data-class');
        var _ifNavBoxSelect = $('.iframe-nav-box .' + dClass);
        var dClass2 = _ifNavBoxSelect.prev().attr('data-class');
        var _leftMenuSelect = $('#left-menu .' + dClass2);
        $('#left-menu .b-green').removeClass('b-green');
        $('#left-menu .rotate180').removeClass('rotate180');
        $('.iframe-box .' + dClass).remove();
        if (dClass2 != undefined && dClass2 != null && dClass2 != "") {
            $('.iframe-nav-box .b-f6').remove();
            $('.iframe-nav-box .' + dClass2).addClass('b-f6');
            _leftMenuSelect.parent().show();
            _leftMenuSelect.addClass('b-green');
            _leftMenuSelect.parents('dl').find('dt>i').addClass('rotate180');
            $('.iframe-box .' + dClass2).show();
        } else {
            _ifNavBoxSelect.remove();
            $('.iframe-nav-box>div').removeClass('b-f6');
            $('#left-menu>dl>dd').hide();
            _welcomeHome.addClass('b-f6');
            $('.iframe-box iframe').hide();
            $('.iframe-box iframe:nth-of-type(1)').show();
        }
    });
    //关闭所有iframe
    _body.on('click', '#close-all', function () {
        $('#left-menu>dl>dd').hide();
        $('.iframe-nav-box>div:not(div:nth-of-type(1))').remove();
        $('.iframe-box iframe:not(iframe:nth-of-type(1))').remove();
        _welcomeHome.addClass('b-f6');
        $('.iframe-box iframe:nth-of-type(1)').show();
        $('#left-menu i').removeClass('rotate180');
    });
    // 向左移动
    _body.on('click', '#left-move', function () {
        var _iframeNavBox = $('.iframe-nav-box');
        var nowLeft = parseInt(_iframeNavBox.css('left'));
        var afterLeft = nowLeft + 123;
        if (nowLeft < 82) {
            _iframeNavBox.css("left", afterLeft + 'px');
        }
    });
    //向右移动
    _body.on('click', '#right-move', function () {
        var _iframeNavBox = $('.iframe-nav-box');
        var allwidth = $('.nav-right-content-top').width() - 164;
        var nowLeft = parseInt(_iframeNavBox.css('left'));
        var allnavwidth = _iframeNavBox.width();
        var removablewidth = allnavwidth - allwidth;
        var afterLeft = nowLeft - 123;
        if (removablewidth + nowLeft > 0) {
            _iframeNavBox.css("left", afterLeft + 'px');
        }
    });
    return this;
};
