var RcmAvailablePluginsMenu = {

    menu: null,

    build: function () {

        var page = RcmAdminService.getPage(
            function(page) {
                RcmAvailablePluginsMenu.onRegisterObjects(page.plugins);
            }
        );

        page.events.on('registerObjects', RcmAvailablePluginsMenu.onRegisterObjects);

        if (!RcmAvailablePluginsMenu.menu) {

            $(function () {
                RcmAvailablePluginsMenu.menu = $('<div class="availablePluginsMenu panel panel-default"></div>');
                var menu = RcmAvailablePluginsMenu.menu;
                $('body').prepend(menu);
                menu.css('top', $('.rcmAdminPanelWrapper').height());
                var header = $('<h1 class="panel-heading">Available Plugins</h1>')
                menu.append(header);

                var accordion = $('<div class="panel-group" id="availablePluginsGroup">');
                menu.append(accordion);
                menu.draggable({ cancel: '.panel-group' });
                var categoryIndex = 0;
                var newInstanceId = 0;
                $.each(window.rcmAvailablePlugins, function (category, plugins) {
                    var collapseId = 'availablePluginsCollapse' + categoryIndex;

                    var group = $('<div class="panel panel-default"></div>');
                    group.appendTo(accordion);

                    var link = $('<a class="panel-link"></a>');
                    link.appendTo(group);
                    link.attr('data-parent', '#availablePluginsGroup');
                    link.attr('data-toggle', 'collapse');
                    link.attr('href', '#' + collapseId);

                    var heading = $('<div class="panel-heading"></div>');
                    heading.appendTo(link);

                    var title = $('<h4 class="panel-title"></h4>');
                    title.appendTo(heading);
                    title.html(category);

                    var collapse = $('<div class="panel-collapse collapse"></div>');
                    collapse.appendTo(group);
                    collapse.attr('id', collapseId);

                    var collapseBody = $('<div class="panel-body"></div>');
                    collapse.append(collapseBody);

                    $.each(plugins, function (displayNameStr, pluginInfo) {
                        newInstanceId--;
                        var instanceId = newInstanceId;
                        //console.log(pluginInfo.siteWide);
                        if (pluginInfo.siteWide) {
                            instanceId = pluginInfo.instanceId;
                        }
                        //console.log(pluginInfo);
                        var plugin = $('<div class="rcmPluginDrag panel-inner"></div>');
                        plugin.appendTo(collapseBody);
                        plugin.data('pluginName', pluginInfo.name);

                        var icon = $('<img>');
                        icon.attr('src', pluginInfo.icon);
                        icon.appendTo(plugin);
                        var displayName = $('<span></span>');
                        displayName.appendTo(plugin);
                        displayName.html(pluginInfo.displayName);

                        var initialState = $('<div class="initialState"></div>');
                        initialState.css('display', 'none');
                        initialState.appendTo(plugin);

                        var outerContainer = $('<div class="rcmPlugin">');
                        outerContainer.addClass(pluginInfo.name);
                        outerContainer.attr('data-rcmPluginInstanceId', instanceId);
                        outerContainer.attr('data-rcmPluginName', pluginInfo.name);
                        outerContainer.appendTo(initialState);

                        var innerContainer = $('<div class="rcmPluginContainer">');
                        innerContainer.appendTo(outerContainer);

                    });
                    categoryIndex++;
                });
            });
        } else {

            RcmAvailablePluginsMenu.menu.remove();
            RcmAvailablePluginsMenu.menu = null;
        }
    },

    onRegisterObjects: function(plugins) {

        // @todo - remove any site-wides from the plugins menu that are already in the list
        // @todo - NOTE: each plugin has a method getType() which will return: sitewide, page or layout
        console.log('onRegisterObjects', plugins)
    }
};


