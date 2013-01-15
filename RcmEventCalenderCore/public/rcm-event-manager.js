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
                $.ajax({
                    url: eventsUrl,
                    type: 'POST',
                    data: event,
                    success: function(response) {
                        eventsChangedCallback();
                        okButton.dialog('close');
                    },
                    error:me.handleAjaxError
                });
            }
        );
    };

    this.handleAjaxError = function(){
        $().confirm(
            'There was a problem. Please Try again.'
        )
    }

    /**
     *
     * @param {Integer} eventId
     */
    this.deleteEvent = function(eventId){
        me.getEvent(
            eventId,
            function(event){
                $().confirm(
                    'Delete this event?<br><br>' + event.title,
                    function(){
                        $.ajax({
                            url: eventsUrl + '/' + eventId,
                            type: 'DELETE',
                            success: function(response) {
                                eventsChangedCallback();
                            },
                            error:me.handleAjaxError
                        });
                    }
                )
            }
        )
    };

    /**
     *
     * @param {Integer} eventId
     */
    this.editEvent = function(eventId){
        me.getEvent(
            eventId,
            function(event){
                me.showEventPropertiesDialog(
                    'Edit Event',
                    event,
                    function(event, okButton){
                        $.ajax({
                            url: eventsUrl + '/' + eventId,
                            type: 'PUT',
                            data: event,
                            success: function(response) {
                                eventsChangedCallback();
                                okButton.dialog('close');
                            },
                            error:me.handleAjaxError
                        });
                    }
                );
            }
        );
    }

    this.getEvent = function(eventId, callback){
        $.getJSON(
            eventsUrl + '/' + eventId,
                function(response) {
                callback(response)
            }
        ).error(me.handleAjaxError);
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
        form.addInput('mapAddress', 'Map Address', event.mapAddress);

        form.append('<h1>TODO: DATES</h1>')

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
                    event.mapAddress= form.find('[name=mapAddress]').val();
                    event.startDate = '2013-6-1';
                    event.endDate = '2013-6-1';

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