function RcmPageForm() {

    var me = this;

    me.pageTempateSelect = '#pageTemplate select';
    me.pageLayoutDiv = '#pageLayout';

    me.init = function() {
        me.addOnChange();
        me.showHidePageLayout();
    };

    me.addOnChange = function() {
        jQuery(me.pageTempateSelect).change(me.showHidePageLayout)
    };

    me.showHidePageLayout = function() {
        var currentValue = jQuery(me.pageTempateSelect).val();

        if (currentValue == 'blank') {
            jQuery(me.pageLayoutDiv).show();
        } else {
            jQuery(me.pageLayoutDiv).hide();
        }
    };
}

jQuery(function(){
    var pageForm = new RcmPageForm;
    pageForm.init();
});