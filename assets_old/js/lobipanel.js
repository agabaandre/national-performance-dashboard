Math.randomString = function (n) { for (var text = "", possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", i = 0; n > i; i++)text += possible.charAt(Math.floor(Math.random() * possible.length)); return text }, String.prototype.getCss = function () { for (var css = {}, style = this.valueOf().split(";"), i = 0; i < style.length; i++)if (style[i] = $.trim(style[i]), style[i]) { var s = style[i].split(":"); css[$.trim(s[0])] = $.trim(s[1]) } return css }, String.prototype.trim = function () { return this.replace(/^\s+|\s+$/g, "") }, String.prototype.toCamel = function () { return this.replace(/(\-[a-z])/g, function ($1) { return $1.toUpperCase().replace("-", "") }) }, String.prototype.toDash = function () { return this.replace(/([A-Z])/g, function ($1) { return "-" + $1.toLowerCase() }) }, String.prototype.toUnderscore = function () { return this.replace(/([A-Z])/g, function ($1) { return "_" + $1.toLowerCase() }) }, Number.prototype.isBetween = function (num1, num2, including) { if (including) { if (this.valueOf() <= num2 && this.valueOf() >= num1) return !0 } else if (this.valueOf() < num2 && this.valueOf() > num1) return !0; return !1 }, $.fn.insertAt = function (i, selector) { var object = selector; if ("string" == typeof selector && (object = $(selector)), i = Math.min(object.children().length, i), 0 == i) return object.prepend(this), this; var oldIndex = this.data("index"); return this.attr("data-index", i), object.find(">*:nth-child(" + i + ")").after(this), object.children().each(function (index, el) { var $el = $(el); i > oldIndex && index > oldIndex && i >= index ? $el.attr("data-index", parseInt($el.data("data-index"), 10) - 1) : oldIndex >= i && index > i && oldIndex >= index && $el.attr("data-index", parseInt($el.attr("data-index"), 10) + 1) }), this }, $.fn.disableSelection = function () { return this.attr("unselectable", "on").css("user-select", "none").on("selectstart", !1) }, $.fn.enableSelection = function () { return this.removeAttr("unselectable").css("user-select", "initial").off("selectstart") }, $(function () { var LobiPanel = function ($el, options) { this.$el = null, this.$options = {}, this.hasRandomId = !1, this.storage = null; var $heading, $body, innerId, storagePrefix = "lobipanel_", me = this, _processInput = function (options) { options || (options = {}); var opts = _getOptionsFromAttributes(); options = $.extend({}, $.fn.lobiPanel.DEFAULTS, me.storage, options, opts); for (var objects = ["unpin", "reload", "expand", "minimize", "close", "editTitle"], i = 0; i < objects.length; i++) { var prop = objects[i]; "object" == typeof options[prop] && (options[prop] = $.extend({}, $.fn.lobiPanel.DEFAULTS[prop], options[prop], opts[prop])) } return options }, _init = function () { me.$el.addClass("lobipanel"), $heading.append(_generateControls()); var parent = me.$el.parent(); _appendInnerIdToParent(parent, innerId), _enableSorting(), _adjustForScreenSize(), _onToggleIconsBtnClick(), _enableResponsiveness(), _setBodyHeight(), me.$options.autoload && me.load(); var maxWidth = "calc(100% - " + $heading.find(".dropdown-menu").children().length * $heading.find(".dropdown-menu li").first().outerWidth() + "px)"; $heading.find(".panel-title").css("max-width", maxWidth), _triggerEvent("init") }, _generateControls = function () { var dropdown = _generateDropdown(), menu = dropdown.find(".dropdown-menu"); return me.$options.editTitle !== !1 && menu.append(_generateEditTitle()), me.$options.unpin !== !1 && menu.append(_generateUnpin()), me.$options.reload !== !1 && menu.append(_generateReload()), me.$options.minimize !== !1 && menu.append(_generateMinimize()), me.$options.expand !== !1 && menu.append(_generateExpand()), me.$options.close !== !1 && menu.append(_generateClose()), menu.find(">li>a").on("click", function (ev) { ev.preventDefault(), ev.stopPropagation() }), dropdown }, _generateDropdown = function () { return $('<div class="dropdown"></div>').append('<ul class="dropdown-menu dropdown-menu-right"></ul>').append('<div class="dropdown-toggle" data-toggle="dropdown"><span class="' + LobiPanel.PRIVATE_OPTIONS.iconClass + " " + me.$options.toggleIcon + '"></div>') }, _generateEditTitle = function () { var options = me.$options.editTitle, control = $('<a data-func="editTitle"></a>'); return control.append('<i class="' + LobiPanel.PRIVATE_OPTIONS.iconClass + " " + options.icon + '"></i>'), options.tooltip && "string" == typeof options.tooltip && (control.append('<span class="control-title">' + options.tooltip + "</span>"), control.attr("data-tooltip", options.tooltip)), _onEditTitleClick(control), $("<li></li>").append(control) }, _onEditTitleClick = function (control) { control.on("mousedown", function (ev) { ev.stopPropagation() }), control.on("click", function (ev) { ev.stopPropagation(), $heading.find('[data-func="editTitle"]').tooltip("hide"), me.isTitleEditing() ? me.finishTitleEditing() : me.startTitleEditing() }) }, _generateUnpin = function () { var options = me.$options.unpin, control = $('<a data-func="unpin"></a>'); return control.append('<i class="' + LobiPanel.PRIVATE_OPTIONS.iconClass + " " + options.icon + '"></i>'), options.tooltip && "string" == typeof options.tooltip && (control.append('<span class="control-title">' + options.tooltip + "</span>"), control.attr("data-tooltip", options.tooltip)), _onUnpinClick(control), $("<li></li>").append(control) }, _onUnpinClick = function (control) { control.on("mousedown", function (ev) { ev.stopPropagation() }), control.on("click", function () { me.togglePin() }) }, _generateReload = function () { var options = me.$options.reload, control = $('<a data-func="reload"></a>'); return control.append('<i class="' + LobiPanel.PRIVATE_OPTIONS.iconClass + " " + options.icon + '"></i>'), options.tooltip && "string" == typeof options.tooltip && (control.append('<span class="control-title">' + options.tooltip + "</span>"), control.attr("data-tooltip", options.tooltip)), _onReloadClick(control), $("<li></li>").append(control) }, _onReloadClick = function (control) { control.on("mousedown", function (ev) { ev.stopPropagation() }), control.on("click", function () { me.load({ callback: function () { control.tooltip("hide") } }) }) }, _generateMinimize = function () { var options = me.$options.minimize, control = $('<a data-func="minimize"></a>'); return control.append('<i class="' + LobiPanel.PRIVATE_OPTIONS.iconClass + " " + options.icon + '"></i>'), options.tooltip && "string" == typeof options.tooltip && (control.append('<span class="control-title">' + options.tooltip + "</span>"), control.attr("data-tooltip", options.tooltip)), _onMinimizeClick(control), $("<li></li>").append(control) }, _onMinimizeClick = function (control) { control.on("mousedown", function (ev) { ev.stopPropagation() }), control.on("click", function (ev) { ev.stopPropagation(), me.toggleMinimize() }) }, _generateExpand = function () { var options = me.$options.expand, control = $('<a data-func="expand"></a>'); return control.append('<i class="' + LobiPanel.PRIVATE_OPTIONS.iconClass + " " + options.icon + '"></i>'), options.tooltip && "string" == typeof options.tooltip && (control.append('<span class="control-title">' + options.tooltip + "</span>"), control.attr("data-tooltip", options.tooltip)), _onExpandClick(control), $("<li></li>").append(control) }, _onExpandClick = function (control) { control.on("mousedown", function (ev) { ev.stopPropagation() }), control.on("click", function (ev) { ev.stopPropagation(), me.toggleSize() }) }, _generateClose = function () { var options = me.$options.close, control = $('<a data-func="close"></a>'); return control.append('<i class="' + LobiPanel.PRIVATE_OPTIONS.iconClass + " " + options.icon + '"></i>'), options.tooltip && "string" == typeof options.tooltip && (control.append('<span class="control-title">' + options.tooltip + "</span>"), control.attr("data-tooltip", options.tooltip)), _onCloseClick(control), $("<li></li>").append(control) }, _onCloseClick = function (control) { control.on("mousedown", function (ev) { ev.stopPropagation() }), control.on("click", function (ev) { ev.stopPropagation(), control.tooltip("hide"), me.close() }) }, _getMaxZIndex = function () { var style, max, cur, panels = $(".lobipanel.panel-unpin:not(.panel-minimized.panel-expanded)"); if (0 === panels.length) return { id: "", "z-index": LobiPanel.PRIVATE_OPTIONS.initialZIndex }; style = $(panels[0]).attr("style"); var id = $(panels[0]).data("inner-id"); max = style ? style.getCss()["z-index"] : LobiPanel.PRIVATE_OPTIONS.initialZIndex; for (var i = 1; i < panels.length; i++)style = $(panels[i]).attr("style"), cur = style ? style.getCss()["z-index"] : 0, cur > max && (id = $(panels[i]).data("inner-id"), max = cur); return { id: id, "z-index": parseInt(max, 10) } }, _onPanelClick = function () { me.$el.on("mousedown.lobiPanel", function () { return me.isPinned() || me.isMinimized() || me.isOnFullScreen() ? !1 : void me.bringToFront() }) }, _offPanelClick = function () { me.$el.off("mousedown.lobiPanel") }, _changeClassOfControl = function (el) { el = $(el); var opts = me.$options[el.attr("data-func")]; opts.icon && el.find("." + LobiPanel.PRIVATE_OPTIONS.iconClass).toggleClass(opts.icon).toggleClass(opts.icon2) }, _getFooterForMinimizedPanels = function () { var minimizedCtr = $("." + LobiPanel.PRIVATE_OPTIONS.toolbarClass); return 0 === minimizedCtr.length && (minimizedCtr = $('<div class="' + LobiPanel.PRIVATE_OPTIONS.toolbarClass + '"></div>'), $("body").append(minimizedCtr)), minimizedCtr }, _expandOnHeaderClick = function () { $heading.on("click.lobiPanel", function () { me.maximize(), me.bringToFront() }) }, _removeExpandOnHeaderClick = function () { $heading.off("click.lobiPanel") }, _getAvailableWidth = function (calcWidth) { return me.$options.maxWidth && (calcWidth = Math.min(calcWidth, me.$options.maxWidth)), me.$options.minWidth && (calcWidth = Math.max(calcWidth, me.$options.minWidth)), calcWidth }, _getAvailableHeight = function (calcHeight) { return me.$options.maxHeight && (calcHeight = Math.min(calcHeight, me.$options.maxHeight)), me.$options.minHeight && (calcHeight = Math.max(calcHeight, me.$options.minHeight)), calcHeight }, _calculateBodyHeight = function (h) { return h - $heading.outerHeight() - me.$el.find(".panel-footer").outerHeight() }, _calculateBodyWidth = function (w) { return w - 2 }, _appendInnerIdToParent = function (parent, innerId) { if (void 0 === parent.attr(LobiPanel.PRIVATE_OPTIONS.parentAttr)) parent.attr(LobiPanel.PRIVATE_OPTIONS.parentAttr, innerId); else { if (parent.attr(LobiPanel.PRIVATE_OPTIONS.parentAttr).indexOf(innerId) > -1) return; var innerIds = parent.attr(LobiPanel.PRIVATE_OPTIONS.parentAttr); parent.attr(LobiPanel.PRIVATE_OPTIONS.parentAttr, innerIds + " " + innerId) } me.$el.attr("data-index", me.$el.index()) }, _insertInParent = function () { var parent = $("[" + LobiPanel.PRIVATE_OPTIONS.parentAttr + "~=" + innerId + "]"); me.$el.insertAt(me.$el.attr("data-index"), parent) }, _generateWindow8Spinner = function () { var template = ['<div class="spinner spinner-windows8">', '<div class="wBall">', '<div class="wInnerBall">', "</div>", "</div>", '<div class="wBall">', '<div class="wInnerBall">', "</div>", "</div>", '<div class="wBall">', '<div class="wInnerBall">', "</div>", "</div>", '<div class="wBall">', '<div class="wInnerBall">', "</div>", "</div>", '<div class="wBall">', '<div class="wInnerBall">', "</div>", "</div>", "</div>"].join(""); return $('<div class="spinner-wrapper">' + template + "</div>") }, _enableSorting = function () { var parent = me.$el.parent(); parent.hasClass("ui-sortable") && parent.sortable("destroy"), me.$options.sortable ? (me.$el.addClass("lobipanel-sortable"), parent.addClass("lobipanel-parent-sortable")) : me.$el.removeClass("lobipanel-sortable"), parent.sortable({ connectWith: ".lobipanel-parent-sortable", items: ".lobipanel-sortable", handle: ".panel-heading", cursor: "move", placeholder: "lobipanel-placeholder", forcePlaceholderSize: !0, opacity: .7, revert: 300, update: function (event, ui) { var innerId = ui.item.data("inner-id"); _removeInnerIdFromParent(innerId), _appendInnerIdToParent(ui.item.parent(), innerId), _updateDataIndices(ui.item), _triggerEvent("dragged") } }) }, _disableSorting = function () { var parent = me.$el.parent(); parent.hasClass("ui-sortable") && parent.sortable("destroy") }, _updateDataIndices = function (panel) { var items = panel.parent().find("> *"); items.each(function (index, el) { $(el).attr("data-index", index) }) }, _removeInnerIdFromParent = function (innerId) { var parent = $("[" + LobiPanel.PRIVATE_OPTIONS.parentAttr + "~=" + innerId + "]"), innerIds = parent.attr(LobiPanel.PRIVATE_OPTIONS.parentAttr).replace(innerId, "").trim().replace(/\s{2,}/g, " "); parent.attr(LobiPanel.PRIVATE_OPTIONS.parentAttr, innerIds) }, _onToggleIconsBtnClick = function () { $heading.find(".toggle-controls").on("click.lobiPanel", function () { me.$el.toggleClass("controls-expanded") }) }, _adjustForScreenSize = function () { me.disableTooltips(), $(window).width() > 768 && me.$options.tooltips && me.enableTooltips(), me.isOnFullScreen() && $body.css({ width: _calculateBodyWidth(me.$el.width()), height: _calculateBodyHeight(me.$el.height()) }) }, _enableResponsiveness = function () { $(window).on("resize.lobiPanel", function () { _adjustForScreenSize() }) }, _setBodyHeight = function () { "auto" !== me.$options.bodyHeight && $body.css({ height: me.$options.bodyHeight, overflow: "auto" }) }, _getOptionsFromAttributes = function () { var $el = me.$el, options = {}; for (var key in $.fn.lobiPanel.DEFAULTS) { var k = key.toDash(), val = $el.data(k); void 0 !== val && (options[key] = "object" != typeof $.fn.lobiPanel.DEFAULTS[key] ? val : eval("(" + val + ")")) } return options }, _saveState = function (state) { !me.hasRandomId && me.$options.stateful && (me.storage.state = state, _saveLocalStorage(me.storage)) }, _saveLocalStorage = function (storage) { localStorage.setItem(storagePrefix + innerId, JSON.stringify(storage)) }, _applyState = function (state) { switch (state) { case "unpinned": me.unpin(); break; case "minimized": me.unpin(), me.minimize(); break; case "collapsed": me.minimize(); break; case "fullscreen": me.toFullScreen() } }, _applyIndex = function (index) { null !== index && me.$el.insertAt(index, me.$el.parent()) }, _triggerEvent = function (eventType) { var args = Array.prototype.slice.call(arguments, 1); args.unshift(me), me.$el.trigger(eventType + ".lobiPanel", args) }; this.isPanelInit = function () { return me.$el.hasClass("lobipanel") && me.$el.data("inner-id") }, this.isPinned = function () { return !me.$el.hasClass("panel-unpin") }, this.pin = function () { return _triggerEvent("beforePin"), $heading.find('[data-func="unpin"]').tooltip("hide"), me.disableResize(), me.disableDrag(), _enableSorting(), _offPanelClick(), me.$el.removeClass("panel-unpin").attr("old-style", me.$el.attr("style")).removeAttr("style").css("position", "relative"), $body.css({ width: "", height: "" }), _setBodyHeight(), _insertInParent(), _saveState("pinned"), _triggerEvent("onPin"), me }, this.unpin = function () { if (_triggerEvent("beforeUnpin"), me.$el.hasClass("panel-collapsed")) return me; if (_disableSorting(), $heading.find('[data-func="unpin"]').tooltip("hide"), me.$el.attr("old-style")) me.$el.attr("style", me.$el.attr("old-style")); else { var width = me.$el.width(), height = me.$el.height(), left = Math.max(0, ($(window).width() - me.$el.outerWidth()) / 2), top = Math.max(0, ($(window).height() - me.$el.outerHeight()) / 2); me.$el.css({ left: left, top: top, width: width, height: height }) } var res = _getMaxZIndex(); me.$el.css("z-index", res["z-index"] + 1), _onPanelClick(), me.$el.addClass("panel-unpin"), $("body").append(me.$el); var panelWidth = _getAvailableWidth(me.$el.width()), panelHeight = _getAvailableHeight(me.$el.height()); me.$el.css({ position: "fixed", width: panelWidth, height: panelHeight }); var bHeight = _calculateBodyHeight(panelHeight), bWidth = _calculateBodyWidth(panelWidth); return $body.css({ width: bWidth, height: bHeight }), me.$options.draggable && me.enableDrag(), "none" !== me.$options.resize && me.enableResize(), _saveState("unpinned"), _triggerEvent("onUnpin"), me }, this.togglePin = function () { return this.isPinned() ? this.unpin() : this.pin(), me }, this.isMinimized = function () { return me.$el.hasClass("panel-minimized") || me.$el.hasClass("panel-collapsed") }, this.minimize = function () { if (_triggerEvent("beforeMinimize"), me.isMinimized()) return me; if (me.isPinned()) $body.slideUp(), me.$el.find(".panel-footer").slideUp(), me.$el.addClass("panel-collapsed"), _saveState("collapsed"), _changeClassOfControl($heading.find('[data-func="minimize"]')); else { me.disableTooltips(), $heading.find('[data-func="minimize"]').tooltip("hide"); var left, top, footer = _getFooterForMinimizedPanels(), children = footer.find(">*"); if (top = footer.offset().top, 0 === children.length) left = footer.offset().left; else { var ch = $(children[children.length - 1]); left = ch.offset().left + ch.width() } me.$el.hasClass("panel-expanded") || me.$el.attr("old-style", me.$el.attr("style")), me.$el.animate({ left: left, top: top, width: 200, height: footer.height() }, 100, function () { me.$el.hasClass("panel-expanded") && (me.$el.removeClass("panel-expanded"), me.$el.find(".panel-heading [data-func=expand] ." + LobiPanel.PRIVATE_OPTIONS.iconClass).removeClass(me.$options.expand.icon2).addClass(me.$options.expand.icon)), me.$el.addClass("panel-minimized"), me.$el.removeAttr("style"), me.disableDrag(), me.disableResize(), _expandOnHeaderClick(), footer.append(me.$el), $("body").addClass("lobipanel-minimized"); var maxWidth = "calc(100% - " + $heading.find(".dropdown-menu li>a:visible").length * $heading.find(".dropdown-menu li>a:visible").first().outerWidth() + "px)"; $heading.find(".panel-title").css("max-width", maxWidth), _saveState("minimized"), _triggerEvent("onMinimize") }) } return me }, this.maximize = function () { if (_triggerEvent("beforeMaximize"), !me.isMinimized()) return me; if (me.isPinned()) $body.slideDown(), me.$el.find(".panel-footer").slideDown(), me.$el.removeClass("panel-collapsed"), _saveState("pinned"), _changeClassOfControl($heading.find('[data-func="minimize"]')); else { me.enableTooltips(); var css = me.$el.attr("old-style").getCss(); me.$el.css({ position: css.position || "fixed", "z-index": css["z-index"], left: me.$el.offset().left, top: me.$el.offset().top, width: me.$el.width(), height: me.$el.height() }), $("body").append(me.$el), delete css.position, delete css["z-index"], me.$el.animate(css, 100, function () { me.$el.css("position", ""), me.$el.removeClass("panel-minimized"), me.$el.removeAttr("old-style"), me.$options.draggable && me.enableDrag(), me.enableResize(), _removeExpandOnHeaderClick(); var footer = _getFooterForMinimizedPanels(); 0 === footer.children().length && footer.remove(), $("body").removeClass("lobipanel-minimized").addClass("lobipanel-minimized"); var maxWidth = "calc(100% - " + $heading.find(".dropdown-menu li").length * $heading.find(".dropdown-menu li").first().outerWidth() + "px)"; $heading.find(".panel-title").css("max-width", maxWidth), _saveState("unpinned"), _triggerEvent("onMaximize") }) } return me }, this.toggleMinimize = function () { return me.isMinimized() ? me.maximize() : me.minimize(), me }, this.isOnFullScreen = function () { return me.$el.hasClass("panel-expanded") }, this.toFullScreen = function () { if (_triggerEvent("beforeFullScreen"), me.$el.hasClass("panel-collapsed")) return me; _changeClassOfControl($heading.find('[data-func="expand"]')), $heading.find('[data-func="expand"]').tooltip("hide"); var res = _getMaxZIndex(); if (me.isPinned() || me.isMinimized()) { me.enableTooltips(), me.$el.css({ position: "fixed", "z-index": res["z-index"] + 1, left: me.$el.offset().left, top: me.$el.offset().top - $(window).scrollTop(), width: me.$el.width(), height: me.$el.height() }), $("body").append(me.$el); var footer = _getFooterForMinimizedPanels(); 0 === footer.children().length && footer.remove() } else $body.css({ width: "", height: "" }), _setBodyHeight(); me.isMinimized() ? (me.$el.removeClass("panel-minimized"), _removeExpandOnHeaderClick()) : (me.$el.attr("old-style", me.$el.attr("style")), me.disableResize()); var toolbar = $("." + LobiPanel.PRIVATE_OPTIONS.toolbarClass), toolbarHeight = toolbar.outerHeight() || 0; return me.$el.animate({ width: $(window).width(), height: $(window).height() - toolbarHeight, left: 0, top: 0 }, me.$options.expandAnimation, function () { me.$el.css({ width: "", height: "", right: 0, bottom: toolbarHeight }), me.$el.addClass("panel-expanded"), $("body").css("overflow", "hidden"), $body.css({ width: _calculateBodyWidth(me.$el.width()), height: _calculateBodyHeight(me.$el.height()) }), me.disableDrag(), me.isPinned() && _disableSorting(), _saveState("fullscreen"), _triggerEvent("onFullScreen") }), me }, this.toSmallSize = function () { _triggerEvent("beforeSmallSize"), _changeClassOfControl($heading.find('[data-func="expand"]')), $heading.find('[data-func="expand"]').tooltip("hide"); var css = me.$el.attr("old-style").getCss(); return me.$el.animate({ left: css.left, top: css.top, width: css.width, height: css.height, right: css.right, bottom: css.bottom }, me.$options.collapseAnimation, function () { me.$el.removeAttr("old-style"), me.$el.hasClass("panel-unpin") ? (me.$options.draggable && me.enableDrag(), me.enableResize()) : (me.$el.removeAttr("style"), _insertInParent(), _enableSorting()), me.$el.removeClass("panel-expanded"), $("body").css("overflow", "auto"); var bWidth = "", bHeight = ""; me.isPinned() ? "auto" !== me.$options.bodyHeight && (bHeight = me.$options.bodyHeight, _saveState("pinned")) : (bWidth = _calculateBodyWidth(me.getWidth()), bHeight = _calculateBodyHeight(me.getHeight()), _saveState("unpinned")), $body.css({ width: bWidth, height: bHeight }), _triggerEvent("onSmallSize") }), me }, this.toggleSize = function () { return me.isOnFullScreen() ? me.toSmallSize() : me.toFullScreen(), me }, this.close = function () { return _triggerEvent("beforeClose"), me.$el.hide(100, function () { me.isOnFullScreen() && $("body").css("overflow", "auto"), me.$el.remove(); var footer = _getFooterForMinimizedPanels(); 0 === footer.children().length && footer.remove(), _triggerEvent("onClose") }), me }, this.setPosition = function (left, top) { return me.isPinned() ? me : (me.$el.animate({ left: left, top: top }, 100), me) }, this.setWidth = function (w) { if (me.isPinned()) return me; var bWidth = _calculateBodyWidth(w); return me.$el.animate({ width: w }, 100), $body.animate({ width: bWidth }, 100), me }, this.setHeight = function (h) { if (me.isPinned()) return me; var bHeight = _calculateBodyHeight(h); return me.$el.animate({ height: h }, 100), $body.animate({ height: bHeight }, 100), me }, this.setSize = function (w, h) { if (me.isPinned()) return me; var bHeight = _calculateBodyHeight(h), bWidth = _calculateBodyWidth(w); return me.$el.animate({ height: h, width: w }, 100), $body.animate({ height: bHeight, width: bWidth }, 100), me }, this.getPosition = function () { var offset = me.$el.offset(); return { x: offset.left, y: offset.top } }, this.getWidth = function () { return me.$el.width() }, this.getHeight = function () { return me.$el.height() }, this.bringToFront = function () { _triggerEvent("beforeToFront"); var res = _getMaxZIndex(); return res.id === me.$el.data("inner-id") ? me : (me.$el.css("z-index", res["z-index"] + 1), _triggerEvent("onToFront"), me) }, this.enableDrag = function () { return me.$el.draggable({ handle: ".panel-heading" }), me }, this.disableDrag = function () { return me.$el.hasClass("ui-draggable") && me.$el.draggable("destroy"), me }, this.enableResize = function () { var handles = !1; return "vertical" === me.$options.resize ? handles = "n, s" : "horizontal" === me.$options.resize ? handles = "e, w" : "both" === me.$options.resize && (handles = "all"), handles ? (me.$el.resizable({ minWidth: me.$options.minWidth, maxWidth: me.$options.maxWidth, minHeight: me.$options.minHeight, maxHeight: me.$options.maxHeight, handles: handles, start: function () { me.$el.disableSelection(), _triggerEvent("resizeStart") }, stop: function () { me.$el.enableSelection(), _triggerEvent("resizeStop") }, resize: function () { var bHeight = _calculateBodyHeight(me.$el.height()), bWidth = _calculateBodyWidth(me.$el.width()); $body.css({ width: bWidth, height: bHeight }), _triggerEvent("onResize") } }), me) : void 0 }, this.disableResize = function () { return me.$el.hasClass("ui-resizable") && me.$el.resizable("destroy"), me }, this.startLoading = function () { var spinner = _generateWindow8Spinner(); me.$el.append(spinner); var sp = spinner.find(".spinner"); return sp.css("margin-top", 50), me }, this.stopLoading = function () { return me.$el.find(".spinner-wrapper").remove(), me }, this.setLoadUrl = function (url) { return me.$options.loadUrl = url, me }, this.load = function (params) { params = params || {}, "string" == typeof params && (params = { url: params }); var url = params.url || me.$options.loadUrl, data = params.data || {}, callback = params.callback || null; return url ? (_triggerEvent("beforeLoad"), me.startLoading(), $body.load(url, data, function (result, status, xhr) { callback && "function" == typeof callback && callback(result, status, xhr), me.stopLoading(), _triggerEvent("loaded", result, status, xhr) }), me) : me }, this.destroy = function () { return me.disableDrag(), me.disableResize(), me.$options.sortable = !1, _enableSorting(), _removeInnerIdFromParent(innerId), me.$el.removeClass("lobipanel").removeAttr("data-inner-id").removeAttr("data-index").removeData("lobiPanel"), $heading.find(".dropdown").remove(), me.$el }, this.startTitleEditing = function () { var title = $heading.find(".panel-title").text().trim(), input = $('<input value="' + title + '"/>'); return input.on("keydown", function (ev) { 13 === ev.which ? me.finishTitleEditing() : 27 === ev.which && me.cancelTitleEditing() }), $heading.find(".panel-title").data("old-title", title).html("").append(input), input[0].focus(), input[0].select(), _changeClassOfControl($heading.find('[data-func="editTitle"]')), me }, this.isTitleEditing = function () { return $heading.find(".panel-title input").length > 0 }, this.cancelTitleEditing = function () { var title = $heading.find(".panel-title"); return title.html(title.data("old-title")).find("input").remove(), _changeClassOfControl($heading.find('[data-func="editTitle"]')), me }, this.finishTitleEditing = function () { var input = $heading.find("input"); return $heading.find(".panel-title").html(input.val()), input.remove(), _changeClassOfControl($heading.find('[data-func="editTitle"]')), me }, this.enableTooltips = function () { if ($(window).width() < 768) return me; var controls = $heading.find(".dropdown-menu>li>a"); return controls.each(function (index, el) { var $el = $(el); $el.attr("data-toggle", "tooltip").attr("data-title", $el.data("tooltip")).attr("data-placement", "bottom") }), controls.each(function (ind, el) { $(el).tooltip({ container: "body", template: '<div class="tooltip lobipanel-tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>' }) }), me }, this.disableTooltips = function () { return $heading.find(".dropdown-menu>li>a").tooltip("destroy"), me }, this.$el = $el, me.$el.data("inner-id") || (me.hasRandomId = !0, me.$el.attr("data-inner-id", Math.randomString(10))), innerId = me.$el.data("inner-id"), me.hasRandomId || (me.storage = localStorage.getItem(storagePrefix + innerId), me.storage = JSON.parse(me.storage) || {}), this.$options = _processInput(options), $heading = this.$el.find(">.panel-heading"), $body = this.$el.find(">.panel-body"), _init(), _applyState(me.$options.state), _applyIndex(me.$options.initialIndex) }; $.fn.lobiPanel = function (option) { var args = arguments, ret = null; return this.each(function () { var $this = $(this), data = $this.data("lobiPanel"), options = "object" == typeof option && option; data || $this.data("lobiPanel", data = new LobiPanel($this, options)), "string" == typeof option && (args = Array.prototype.slice.call(args, 1), ret = data[option].apply(data, args)) }), ret }, LobiPanel.PRIVATE_OPTIONS = { parentAttr: "data-lobipanel-child-inner-id", toolbarClass: "lobipanel-minimized-toolbar", initialZIndex: 1e4, iconClass: "panel-control-icon" }, $.fn.lobiPanel.DEFAULTS = { draggable: !0, sortable: !1, connectWith: ".ui-sortable", resize: "both", minWidth: 200, minHeight: 100, maxWidth: 1200, maxHeight: 700, loadUrl: "", autoload: !0, bodyHeight: "auto", tooltips: !0, toggleIcon: "glyphicon glyphicon-cog", expandAnimation: 100, collapseAnimation: 100, state: "pinned", initialIndex: null, stateful: !1, unpin: { icon: "glyphicon glyphicon-move", tooltip: "Unpin" }, reload: { icon: "glyphicon glyphicon-refresh", tooltip: "Reload" }, minimize: { icon: "glyphicon glyphicon-minus", icon2: "glyphicon glyphicon-plus", tooltip: "Minimize" }, expand: { icon: "glyphicon glyphicon-resize-full", icon2: "glyphicon glyphicon-resize-small", tooltip: "Fullscreen" }, close: { icon: "glyphicon glyphicon-remove", tooltip: "Close" }, editTitle: { icon: "glyphicon glyphicon-pencil", icon2: "glyphicon glyphicon-floppy-disk", tooltip: "Edit title" } }, $(".lobipanel").lobiPanel() });