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
                startDate:me.getToday(),
                endDate:me.getToday(),
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

    this.getToday = function(){
        var today = new Date();
        return today.getFullYear() + '-' + today.getMonth()+1 + '-'
            + today.getDate();
    }

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
        form.addRichEdit('text', 'Text', event.text);
        form.addInput('startDate', 'Start Date (YYYY-MM-DD)', event.startDate);
        form.addInput('endDate', 'End Date (YYYY-MM-DD)', event.endDate);
        form.addInput('mapAddress', 'Map Address', event.mapAddress);

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
                    event.startDate = form.find('[name=startDate]').val();
                    event.endDate = form.find('[name=endDate]').val();
                    event.mapAddress= form.find('[name=mapAddress]').val();

                    var editorId = form.find('.text').attr('id');
                    event.text = CKEDITOR.instances[editorId].getData();

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