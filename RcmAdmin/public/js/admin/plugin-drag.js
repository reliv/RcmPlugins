RcmPluginDrag = {
    /**

     /**
     * Show the layout editor
     */
    initDrag: function () {
        RcmPluginDrag.makePluginsDraggable();
        RcmPluginDrag.makePluginsSortable();
//        RcmPluginDrag.addCMSHandles();
    },
    /**
     * Make plugins in the layout editor menu draggable
     */
    makePluginsDraggable: function () {
        $(".availablePluginsMenu .rcmPluginDrag").each(function () {
            $(this).draggable({
                cursorAt: {left: 40, top: 10},
                helper: function () {
                    return RcmPluginDrag.pluginDraggableHelper(this)
                },
                drag: function () {
                    RcmPluginDrag.pluginDraggableDrag(this);
                },
                revert: 'invalid',
                connectToSortable: '.rcmContainer',
                appendTo: 'body'
            });
        });
    },
//    /**
//     * Disable dragging on plugins
//     */
//    stopPluginsDraggable: function () {
//        $("#rcmLayoutAccordion").find(".rcmPluginDrag").each(function (v, e) {
//            $(e).draggable("destroy");
//        });
//    },
    /**
     * Callback for Draggable - Helper
     *
     * @param container
     * @return {*|jQuery|HTMLElement}
     */
    pluginDraggableHelper: function (container) {
        var pluginContainer = $(container).find(".rcmPlugin");
        var pluginData = RcmPluginDrag.getPluginContainerInfo(pluginContainer);
        if (pluginData.isSiteWide != 'Y') {
            $(pluginContainer).attr(
                'data-rcmPluginInstanceId',
                pluginData.instanceId * 10
            );
        }
        var helper = $(pluginContainer).clone(false);
//Get Ajax
        RcmPluginDrag.pluginDraggableStart(helper, pluginContainer);
        RcmPluginDrag.setHelperWidth(helper, pluginContainer);
        return $(helper);
    },
    /**
     * Callback for Draggable - Start. Preforms Ajax Request for new
     * Plugin instance to add to page.
     */
    pluginDraggableStart: function (helper, pluginContainer) {
        var pluginInstanceContainer = $(pluginContainer).find('.rcmPluginContainer');
        if ($(pluginInstanceContainer).html() != '') {
            return;
        }
        var pluginData = RcmPluginDrag.getPluginContainerInfo(pluginContainer);
        var url = '/rcm-admin-get-instance/' + pluginData.pluginName + '/' + pluginData.instanceId;
//        var url = '/fakePluginInhstanceTrash';
        $.getJSON(
            url,
            function (data) {
                RcmPluginDrag.getInstanceSuccessCallback(data, helper, pluginContainer)
            }
        );
    },
    /**
     * Set the width for helper divs when dragging new plugins. This
     * keeps plugins from spanning the entire page.
     *
     * @param helper
     * @param pluginContainer
     */
    setHelperWidth: function (helper, pluginContainer) {
        var divWidth = RcmPluginDrag.getElementWidth(pluginContainer);
        if (divWidth > 1000) {
            $(helper).width(350);
        } else {
            $(helper).width(divWidth);
        }
    },
    /**
     * Runs after a successful ajax request for a new plugin.
     *
     * @param data
     * @param helper
     * @param pluginContainer
     */
    getInstanceSuccessCallback: function (data, helper, pluginContainer) {
        if (data.js != undefined && data.js != '') {
            RcmPluginDrag.loadPluginJs(data.js);
        }
        $(helper).html(data.display);
        $(pluginContainer).find(".rcmPluginContainer").html(data.display);
        RcmPluginDrag.setHelperWidth(helper, pluginContainer);
    },
    /**
     * Load a plugins edit script.
     *
     * @param jsPath
     */
    loadPluginJs: function (jsPath) {
        var scriptAlreadyLoadedCheck = $('script[src="' + jsPath + '"]');
        if (scriptAlreadyLoadedCheck.length < 1) {
            $.getScript(jsPath);
        }
    },
    /**
     * Callback for Draggable - Drag event
     */
    pluginDraggableDrag: function (container) {
        /* This is required for adding items to an empty
         * sortable. the sortable "change" event handles
         * everything else.
         */
        var placeHolder = $('.rcmPluginSortPlaceHolder');
        /*
         * If placeholder exists and has not yet been filled with a plugin
         */
        if (placeHolder.length && !placeHolder.html().length) {
            RcmPluginDrag.pluginDragPlaceHolder($(container).find(".rcmPlugin"));
        }
    },
    /**
     * Fix for containers that have no current plugins.
     *
     * @param container
     */
    pluginDragPlaceHolder: function (container) {
        var placeHolder = $('.rcmPluginSortPlaceHolder');
//If placeholder exists and has not yet been filled with a plugin
        if (placeHolder.length && !placeHolder.html().length) {
//Copy plugin css classes
            placeHolder.attr(
                'class',
                container.attr('class')
                    + ' rcmPluginSortPlaceHolder'
            );
//Copy plugin html
            placeHolder.html(container.html());
        }
    },
    pluginResizeStartHandler: function (event, ui) {
        ui.element.attr('data-rcmPluginResized', 'Y');
        RcmPluginDrag.checkResize(ui.element);
    },
    makePluginsResizable: function () {
        $('#RcmRealPage').find('.rcmPlugin').resizable({
            grid: 10,
            start: RcmPluginDrag.pluginResizeStartHandler
        });
    },
    /**
     * Makes plugins sortable.
     */
    makePluginsSortable: function () {
        $(".rcmContainer").sortable({
            connectWith: '.rcmContainer',
            dropOnEmpty: true,
            helper: "original",
            tolerance: 'pointer',
            placeholder: "rcmPluginSortPlaceHolder",
            forcePlaceholderSize: false,
            handle: '.rcmSortableHandle',
            change: function (event, ui) {
                RcmPluginDrag.pluginSortableChange(ui);
            },
            receive: function (event, ui) {
                RcmPluginDrag.pluginSortableReceive(this, ui);
            },
            start: function (event, ui) {
                RcmPluginDrag.pluginSortableStart(ui);
            },
            stop: function (event, ui) {
                $('html').removeClass('rcmDraggingPlugins');
                var initialInstance = $(ui.item).find(".initialState");
                if (!$(initialInstance).is('.initialState')) {
                    /* Let the editor know that dragging has stopped */
//                    me.rcmPlugins.initRichEdits(
//                        ui.item, me.getPluginContainerInfo(ui.item)
//                    );
                }
            },
            cancel: '[data-textedit]'
        });
        RcmPluginDrag.makePluginsResizable();
    },
//    /**
//     * Makes plugins sortable.
//     */
//    stopPluginsSortable: function () {
//        $(".rcmContainer").each(function (v, e) {
//            $(e).sortable("destroy");
//        });
//    },
    /**
     * Plugin Sortable Change event
     *
     * @param ui
     */
    pluginSortableChange: function (ui) {
        var pluginDiv;
        var placeHolder = $('.rcmPluginSortPlaceHolder');
        if (placeHolder.length && !placeHolder.html().length) {
            if (ui.item.hasClass('rcmPluginDrag')) {
                pluginDiv = $(ui.item).find(".rcmPlugin");
            } else {
                pluginDiv = ui.item;
            }
            placeHolder.attr(
                'class',
                pluginDiv.attr('class') + ' rcmPluginSortPlaceHolder'
            );
            placeHolder.html(pluginDiv.html());
        }
    },
    pluginSortableStart: function (ui) {
        $('html').addClass('rcmDraggingPlugins');
        /* Advise the editor that we are moving it's container */
        var richEdit = $(ui.item).find('[data-richedit]');
        if (richEdit.length > 0) {
            var pluginContainer = $(richEdit).closest('.rcmPlugin');
//            me.rcmPlugins.removeRichEdits(
//                pluginContainer,
//                RcmPluginDrag.getPluginContainerInfo(pluginContainer)
//            );
//            me.editor.startDrag(richEdit);
        }
    },
    /**
     * Tells the sortable objects what to do with a new plugin.
     *
     * @param container
     * @param ui
     */
    pluginSortableReceive: function (container, ui) {
//Get the current Item
        var newItem = $(container).find(".rcmPluginDrag");
//Find the actual plugin instance
        var initialInstance = $(ui.item).find(".initialState");
//Create a new element to insert once dropped
        var newDiv = $(initialInstance).find(".rcmPlugin").clone(false);
        var pluginData = RcmPluginDrag.getPluginContainerInfo(newDiv);
        if ($(initialInstance).is('.initialState')) {
            $(newItem).replaceWith($(newDiv));
            if (pluginData.isSiteWide == 'Y') {
                $('#' + pluginData.displayName).hide();
            }
            $(newDiv).find("a").unbind('click').click(function (e) {
//e.preventDefault();
            });
//            me.rcmPlugins.initPluginEditMode(newDiv);
            RcmPluginDrag.addPluginToolbar(newDiv);
        }
//Make sure the new plugin is sizable
        RcmPluginDrag.makePluginsResizable();
    },
//    /**
//     * Delete Plugin on clink bind
//     *
//     * @param container
//     */
//    deletePlugin: function (container) {
//        var pluginData = RcmPluginDrag.getPluginContainerInfo(container);
//        if (pluginData.isSiteWide == 'Y') {
//            $('#' + pluginData.displayName).show();
//        }
//        me.rcmPlugins.removeRichEdits(container, pluginData);
//        me.rcmPlugins.removeTextEdits(container, pluginData);
//        me.rcmPlugins.removeCalledPlugin(container);
//        $(container).remove();
//    },

    getElementWidth: function (container) {
        var elementToUse = container;
        var loopCounter = 0;
        while ($(elementToUse).width() == 0 && loopCounter < 10) {
            elementToUse = $(container).parent();
            loopCounter++;
        }
        return $(elementToUse).width();
    },

    getPluginContainerInfo: function (container) {
        var pluginContainer = container;
        if (!pluginContainer.hasClass('rcmPlugin')) {
            pluginContainer = container.closest('.rcmPlugin');
        }
        var pluginData = {
            pluginName: $(pluginContainer).attr('data-rcmPluginName'),
            isSiteWide: $(pluginContainer).attr('data-rcmSiteWidePlugin'),
            instanceId: $(pluginContainer).attr('data-rcmPluginInstanceId'),
            displayName: $(pluginContainer).attr('data-rcmPluginDisplayName')
        };
        if (pluginData.displayName != undefined) {
            pluginData.displayName = pluginData.displayName.replace(/\s/g, '-');
        }
        pluginData.editClass = pluginData.pluginName + 'Edit';
        return pluginData;
    },

    addPluginToolbar: function (pluginContainer) {
        $(pluginContainer).prepend("<span class='rcmSortableHandle rcmLayoutEditHelper' title='Move Plugin' />");
//$(pluginContainer).prepend("<span class='rcmDeletePluginMenuItem rcmLayoutEditHelper' title='Delete Plugin' />");
//$(pluginContainer).prepend("<span class='rcmSiteWidePluginMenuItem rcmLayoutEditHelper' title='Make Site-Wide' />");
        var pullDownMenu = '<span class="rcmContainerMenu rcmLayoutEditHelper" title="Container Menu"><ul><li><a href="#"></a><ul><li><a href="#" class="rcmSiteWidePluginMenuItem">Mark as site-wide</a> </li><li><a href="#" class="rcmDeletePluginMenuItem">Delete Plugin</a> </li></ul></li></ul></span>'
        $(pluginContainer).prepend(pullDownMenu);
        $(pluginContainer).hover(
            function () {
                $(this).find(".rcmLayoutEditHelper").each(function () {
                    $(this).show();
                });
            },
            function () {
                $(this).find(".rcmLayoutEditHelper").each(function () {
                    $(this).hide();
                })
            }
        );
        $(pluginContainer).find(".rcmDeletePluginMenuItem").click(function (e) {
            me.layoutEditor.deleteConfirm(this);
            e.preventDefault();
        });
        $(pluginContainer).find(".rcmSiteWidePluginMenuItem").click(function (e) {
            me.layoutEditor.makeSiteWide($(this).parents(".rcmPlugin"));
            e.preventDefault();
        });
//        RcmPluginDrag.checkResize(pluginContainer);
    }
};