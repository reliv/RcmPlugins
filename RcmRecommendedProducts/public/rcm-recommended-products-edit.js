/**
 * RcmRecommendedProducts
 *
 * JS for editing RcmRecommendedProducts
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Inna Davis <idavis@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
var RcmRecommendedProductsEdit = function (instanceId, container, pluginHandler) {


    var me = this;

    var instanceConfig;
    var products = {};

    /**
     * Called by content management system to make this plugin user-editable
     */
    me.initEdit = function () {

            $.getJSON('/api/admin/shoppingcart/products', function(data){
                $.each(data, function(i, e)
                {
                    products[e.productId]= e.name;



                });
                pluginHandler.getInstanceConfig(function(instanceConfigFromServer){
                    instanceConfig = instanceConfigFromServer;
                    //Add right click menu
                    $.contextMenu({
                        selector: rcm.getPluginContainerSelector(instanceId),
                        //Here are the right click menu options
                        items: {
                            edit: {
                                name: 'Edit Properties',
                                icon: 'edit',
                                callback: function () {
                                    me.showEditDialog();
                                }
                            }

                        }
                    });
                });
            });

            //Double clicking will show properties dialog
            container.dblclick(me.showEditDialog);
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    me.getSaveData = function () {
        return instanceConfig;
    };

    /**
     * Displays a dialog box to edit href and image src
     */
    me.showEditDialog = function () {
        var productInput = $.dialogIn('select', 'Product', products, instanceConfig.productId);

        var form = $('<form></form>')
            .addClass('simple')
            .append(productInput)
            .dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                    me.refreshRecommendedProductsList(productInput.val());
                    instanceConfig.productId = productInput.val();

                        $(this).dialog('close');
                    }
                }
            }
        );
    };

    me.refreshRecommendedProductsList = function (productId) {
        $(container).find(".RcmRecommendedProducts").find(".rcmRecommendedProductsList");
        $.get('/rcm-recommended-list-refresh/' + instanceId + '/' + productId, function (data) {
            $(".rcmRecommendedProductsList").replaceWith(data);

        })
    }

};