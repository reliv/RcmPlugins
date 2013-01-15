/**
 *
 * @param {Integer} defaultCategoryId
 * @param {Function} eventsChangedCallback
 * @constructor
 */
var RcmEventManager = function (defaultCategoryId, eventsChangedCallback) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmEventManager}
     */
    var me = this;

    var eventsUrl = '/rcm-event-calender/events'

    var categories = [];
    /**
     *
     * @param {Function} [okCallback]
     */
    this.addEvent = function(){
        me.showEventPropertiesDialog(
            'New Event',
            {
                categoryId:defaultCategoryId,
                title:'',
                text:'',
                startDate:'',
                endDate:'',
                mapAddress:''
            },
            function(event, okButton){

                console.log(event);

                $.post(
                    eventsUrl,
                    event,
                    function() {
                        eventsChangedCallback();
                        okButton.dialog('close');
                     }
                ).error(me.handleAjaxError)
            }
        );
    };

    this.handleAjaxError = function(){
        $().confirm(
            'There was a problem communicating with the server.'
                + ' Please Try again.'
        )
    }

    /**
     *
     * @param {Integer} eventId
     * @param {Function} [okCallback]
     */
    this.deleteEvent = function(eventId){
        $().confirm(
            'Delete this event?',
            function(){
                $.ajax({
                    url: eventsUrl + '/' + eventId,
                    type: 'DELETE',
                    success: function(response) {
                        eventsChangedCallback();
                    }
                });
            }
        )
    };

    /**
     *
     * @param {Integer} eventId
     * @param {Function} [okCallback]
     */
    this.editEvent = function(eventId){

    }

    /**
     *
     * @param {Function} okCallback
     * @param {String} formTitle
     * @param {Object} [event]
     */
    this.showEventPropertiesDialog = function(formTitle, event, okCallback){
        var form = $('<form></form>').addClass('simple');
        form.addSelect('categoryId', 'Event Category', categories, event.categoryId);
        form.addInput('title', 'Title', event.title);
        form.addInput('text', 'Text', event.text);

        form.dialog({
            title:formTitle,
            modal:true,
            width:620,
            buttons:{
                Cancel:function () {
                    $(this).dialog("close");
                },
                Ok:function () {

                    //Get user-entered data from form
                    event.categoryId= form.find('[name=categoryId]').val();
                    event.title= form.find('[name=title]').val();
                    event.text= form.find('[name=text]').val();
                    event.startDate = '2013-6-1';
                    event.endDate = '2013-6-1';
                    event.mapAddress = '123 main st. st charles mo';

                    okCallback(event,$(this));
                }
            }
        });
    }

    this.requestCategories = function(){
        $.getJSON(
            '/rcm-event-calender/categories',
            function(result) {
                categories=[];
                $.each(result, function(){
                    categories[this.categoryId]=this.name;
                });
            }
        );
    }

    this.requestCategories();

    this.getCategories = function(){
        return categories;
    }
};