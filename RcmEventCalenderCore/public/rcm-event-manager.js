/**
 *
 * @param {Integer} [defaultCategoryId]
 * @param {Function} [eventsChangedCallback] called each time events change
 * @constructor
 */
var RcmEventManager = function (defaultCategoryId) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmEventManager}
     */
    var me = this;

    var eventsUrl = '/rcm-event-calender/events';

    var categoriesUrl = '/rcm-event-calender/categories';

    var categories = [];

    var currentCategoryId;

    var eventList;

    var categorySelectContainer;

    var warning = '<p style="color:gray">Note: This window ' +
        'is part of the event manager. Changes to events happen in real time ' +
        'and cannot be rolled back.</p>'

    me.eventsChanged = function(){
        if($('#eventManagerList')){
            me.renderEventList();
        }

        $('body').trigger('rcmEventManagerEventsChanged');
    };

    this.showManager = function(){
        var form = $('<form id="eventManager"></form>').addClass('simple');
        categorySelectContainer = $('<div></div>');
        form.append(categorySelectContainer);
        eventList = $('<div></div>');
        form.append(eventList);
        form.dialog({
            title:'Event Manager',
            modal:true,
            width:620,
            buttons:{
                Cancel:function () {
                    $(this).dialog("close");
                },
                Ok:function () {
                    $(this).dialog("close");
                }
            }
        });

        me.renderCategorySelect();

        me.renderEventList();
    };

    this.renderCategorySelect = function(){
        categorySelectContainer.addSelect('categoryId', 'Event Category', categories, event.categoryId);
        categorySelectContainer.find('select').change(function(){
            currentCategoryId = $(this).val();
            me.renderEventList();
        });
    }

    this.renderEventList = function(){
        eventList.html('Loading...');
        me.getEvents(
            currentCategoryId,
            function(events){
                eventList.empty();
                $.each(events,function(){
                    eventList.append(
                        '<div' +
                            ' style="cursor:pointer;"' +
                            ' class="rcmEventManagerListEvent" ' +
                            ' data-eventId="' + this.eventId + '">'
                            + this.title +
                            '</div>');
                });

                $('.rcmEventManagerListEvent').dblclick(function(){
                    me.editEvent(
                        $(this).attr('data-eventId')
                    );
                });

                rcmEdit.pluginContextMenu(
                    {
                        selector:'.rcmEventManagerListEvent',
                        //Here are the right click menu options
                        items:{
                            addEvent:{
                                name:'Add New Event',
                                icon:'edit',
                                callback:me.addEvent
                            },
                            deleteEvent:{
                                name:'Delete this Event',
                                icon:'delete',
                                callback:function(){
                                    me.deleteEvent(
                                        $(this).attr('data-eventId')
                                    );
                                }
                            },
                            editEvent:{
                                name:'Edit this Event',
                                icon:'edit',
                                callback:function(){
                                    me.editEvent(
                                        $(this).attr('data-eventId')
                                    );
                                }
                            }
                        }
                    }
                );

            }
        );
    };

    this.addCategory = function(){
        me.showCategoryPropertiesDialog(
            'New Category',
            {name:''},
            function(category, okButton){
                $.ajax({
                    url: categoriesUrl,
                    type: 'POST',
                    data: category,
                    success: function() {
                        me.eventsChanged();
                        okButton.dialog('close');
                    },
                    error:me.handleAjaxError
                });
            }
        );
    };

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
                    success: function() {
                        me.eventsChanged();
                        okButton.dialog('close');
                    },
                    error:me.handleAjaxError
                });
            }
        );
    };

    this.getToday = function(){
        var today = new Date();
        return (today.getMonth()+1) + '/'
            + today.getDate() + '/' + today.getFullYear();
    };

    this.handleAjaxError = function(){
        $().confirm(
            'There was a problem. Please make sure you entered valid values ' +
                'and try again.'
        )
    };

    /**
     *
     * @param {Integer} eventId
     */
    this.deleteEvent = function(eventId){
        me.getEvent(
            eventId,
            function(event){
                var message = 'Delete this event?<br><br>' + event.title +
                    '<br><br>' + warning
                $().confirm(
                    message,
                    function(){
                        $.ajax({
                            url: eventsUrl + '/' + eventId,
                            type: 'DELETE',
                            success: function() {
                                me.eventsChanged();
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
                                me.eventsChanged();
                                okButton.dialog('close');
                            },
                            error:me.handleAjaxError
                        });
                    }
                );
            }
        );
    };

    this.getEvent = function(eventId, callback){
        $.getJSON(
            eventsUrl + '/' + eventId,
                function(response) {
                callback(response)
            }
        ).error(me.handleAjaxError);
    };

    this.getEvents = function(categoryId, callback){
        $.getJSON(
            eventsUrl + '?categoryId=' + categoryId,
            function(response) {
                callback(response)
            }
        ).error(me.handleAjaxError);
    };

    /**
     *
     * @param {String} formTitle
     * @param {Object} [event]
     * @param {Function} okCallback
     */
    this.showEventPropertiesDialog = function(formTitle, event, okCallback){
        var form = $('<form></form>').addClass('simple');
        form.addSelect('categoryId', 'Event Category', categories, event.categoryId);
        form.addInput('title', 'Title', event.title);
        form.addRichEdit('text', 'Text', event.text);
        form.addDate('startDate', 'Start Date', event.startDate);
        form.addDate('endDate', 'End Date', event.endDate);
        form.addInput('mapAddress', 'Map Address', event.mapAddress);
        form.append(warning);
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
    };

    /**
     *
     * @param {String} formTitle
     * @param {Object} [category]
     * @param {Function} okCallback
     */
    this.showCategoryPropertiesDialog = function(formTitle, category, okCallback){
        var form = $('<form></form>').addClass('simple');
        form.addInput('name', 'Name', category.name);
        form.append(warning);
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
                    category.name= form.find('[name=name]').val();

                    okCallback(category,$(this));
                }
            }
        });
    };

    this.requestCategories = function(){
        $.getJSON(
            '/rcm-event-calender/categories',
            function(result) {
                categories=[];
                $.each(result, function(){
                    categories[this.categoryId]=this.name;
                });

                me.setCurrentCategoryToDefault();
            }
        );
    };

    this.setCurrentCategoryToDefault = function(){
        if(typeof(defaultCategoryId)!='undefined'){
            currentCategoryId = defaultCategoryId;
        }else{
            for (var firstCategoryId in categories) {
                currentCategoryId = firstCategoryId;
                break;
            }
        }
    };

    this.requestCategories();

    this.getCategories = function(){
        return categories;
    };
};