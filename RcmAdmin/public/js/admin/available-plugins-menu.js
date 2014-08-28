var RcmAvailablePluginsMenu = {

    menu: null,

    build: function () {

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

                    $.each(plugins, function (name, pluginInfo) {
                        newInstanceId--;
                        var plugin = $('<div class="rcmPluginDrag panel-inner"></div>');
                        plugin.appendTo(collapseBody);
                        plugin.data('pluginName', name);

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
                        outerContainer.addClass(name);
                        outerContainer.attr('data-rcmPluginInstanceId', newInstanceId);
                        outerContainer.attr('data-rcmPluginName', name);
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
    }
};


