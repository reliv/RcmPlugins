/**
 *
 * @constructor
 */
var RcmEventManager = function () {

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

    var warning = '<p style="color:gray">Note: Changes to events happen ' +
        'immediately and cannot be rolled back.</p>';

    me.showManager = function(){
        var form = $('<div id="eventManager"></div>').addClass('simple');
        categorySelectContainer =
            $('<div id="eventManagerCategory">Loading...</div>');
        eventList =
            $('<table id="eventManagerList" style="cursor:pointer;"></table>');
        form.append(categorySelectContainer);
        form.append('<br>');
        form.append(eventList);
        form.append('<br>');
        form.append(warning);
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

        me.renderCategorySelect(me.renderEventList);
    };

    /**
     *
     * @param {Function} [callback]
     */
    me.renderCategorySelect = function(callback){
        me.getCategories(function(){
            categorySelectContainer.empty();
            categorySelectContainer.addSelect(
                'categoryId',
                'Event Category',
                categories,
                currentCategoryId
            );
            categorySelectContainer.find('select').change(function(){
                me.changeSelectedCategory($(this).val());
            });
            categorySelectContainer.find('p').append(me.buildEditButtons());

            if(typeof(callback)=='function'){
                callback();
            }
        });
    };

    /**
     *
     * @param {Integer} categoryId
     */
    me.changeSelectedCategory = function(categoryId){
        currentCategoryId = categoryId;
        me.renderEventList();
    };

    me.buildEditButtons = function(){
        var buttonCont = $('<span></span>');

        var deleteCategoryButton = $('<button>Delete Category</button>');
        deleteCategoryButton.click(
            function(){
                me.deleteCategory(currentCategoryId);
            }
        );
        buttonCont.append('&nbsp;').append(deleteCategoryButton);

        var createCategoryButton = $('<button>Create Category</button>');
        createCategoryButton.click(
            function(){
                me.createCategory();
            }
        );
        buttonCont.append('&nbsp;').append(createCategoryButton);

        var createEventButton = $('<button>Create Event</button>');
        createEventButton.click(
            function(){
                me.createEvent();
            }
        );
        buttonCont.append('&nbsp;').append(createEventButton);


        return buttonCont;
    };

    me.renderEventList = function(){
        eventList.html('<tr><td>Loading...</td></tr>');
        me.getEvents(
            currentCategoryId,
            function(events){

                if(events.length){
                    eventList.html('<tr><th>Event Title</th><th>Start Date</th></tr>');
                }else{
                    eventList.html('<tr><td>No events found in this category.</td></tr>')
                }

                var tdOpen = '<td style="border:1px solid gray;padding:3px 5px">';
                $.each(events,function(){
                    var tr=$('<tr data-eventId="' + this.eventId + '"></tr>');
                    tr.append(tdOpen + this.title + '</td>');
                    tr.append(tdOpen + this.startDate + '</td>');
                    tr.disableSelection();
                    eventList.append(tr);
                });

                eventList.find('tr').dblclick(function(){
                    me.editEvent($(this).attr('data-eventId'));
                });

                rcmEdit.pluginContextMenu(
                    {
                        selector:'#eventManagerList tr',
                        //Here are the right click menu options
                        items:{
                            deleteEvent:{
                                name:'Delete Event',
                                icon:'delete',
                                callback:function(){
                                    me.deleteEvent(
                                        $(this).attr('data-eventId')
                                    );
                                }
                            },
                            editEvent:{
                                name:'Edit Event',
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

                $('body').trigger('rcmEventManagerRender');
            }
        );
    };

    me.createCategory = function(){
        me.showCategoryPropertiesDialog(
            'Create Category',
            {name:''},
            function(category, okButton){
                $.ajax({
                    url: categoriesUrl,
                    type: 'POST',
                    data: category,
                    success: function() {
                        me.renderCategorySelect();
                        okButton.dialog('close');
                    },
                    error:me.handleAjaxError
                });
            }
        );
    };

    /**
     */
    me.createEvent = function(){
        me.showEventPropertiesDialog(
            'Create Event',
            {
                categoryId:currentCategoryId,
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
                        me.renderEventList();
                        okButton.dialog('close');
                    },
                    error:me.handleAjaxError
                });
            }
        );
    };

    me.getToday = function(){
        var today = new Date();
        return (today.getMonth()+1) + '/'
            + today.getDate() + '/' + today.getFullYear();
    };

    me.handleAjaxError = function(){
        $().confirm(
            'There was a problem. Please make sure you entered valid values ' +
                'and try again.'
        )
    };

    /**
     *
     * @param {Integer} categoryId
     */
    me.deleteCategory = function(categoryId){
        me.getCategory(
            categoryId,
            function(category){
                var message = 'Delete this category <b>AND ALL EVENTS UNDER' +
                    ' IT?</b><br><br>' + category.name +
                    '<br><br>' + warning;
                $().confirm(
                    message,
                    function(){
                        $.ajax({
                            url: categoriesUrl + '/' + categoryId,
                            type: 'DELETE',
                            success: function() {
                                me.renderCategorySelect(me.renderEventList);
                            },
                            error:me.handleAjaxError
                        });
                    }
                );
            }
        )
    };

    /**
     *
     * @param {Integer} eventId
     */
    me.deleteEvent = function(eventId){
        me.getEvent(
            eventId,
            function(event){
                var message = 'Delete this event?<br><br>' + event.title +
                    '<br><br>' + warning;
                $().confirm(
                    message,
                    function(){
                        $.ajax({
                            url: eventsUrl + '/' + eventId,
                            type: 'DELETE',
                            success: function() {
                                me.renderEventList();
                            },
                            error:me.handleAjaxError
                        });
                    }
                );
            }
        )
    };

    /**
     *
     * @param {Integer} eventId
     */
    me.editEvent = function(eventId){
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
                            success: function() {
                                me.renderEventList();
                                okButton.dialog('close');
                            },
                            error:me.handleAjaxError
                        });
                    }
                );
            }
        );
    };

    me.getEvent = function(eventId, callback){
        $.getJSON(
            eventsUrl + '/' + eventId,
                function(response) {
                callback(response)
            }
        ).error(me.handleAjaxError);
    };

    me.getCategory = function(categoryId, callback){
        $.getJSON(
            categoriesUrl + '/' + categoryId,
                function(response) {
                callback(response)
            }
        ).error(me.handleAjaxError);
    };

    me.getEvents = function(categoryId, callback){
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
    me.showEventPropertiesDialog = function(formTitle, event, okCallback){

        var inputs = {
            categoryId:$().dialogIn('select', 'Event Category', categories, event.categoryId),
            title:$().dialogIn('text', 'Title', event.title),
            text:$().dialogIn('richEdit', 'text', 'Text', event.text),
            startDate:$().dialogIn('date', 'Start Date', event.startDate),
            endDate:$().dialogIn('date' , 'End Date', event.endDate),
            mapAddress:$().dialogIn('text' , 'Map Address', event.mapAddress)
        };

        var form = $('<form></form>')
            .addClass('simple')
            .appendMulti(inputs)
            .append(warning)
            .dialog({
                title:formTitle,
                modal:true,
                width:620,
                buttons:{
                    Cancel:function () {
                        $(this).dialog("close");
                    },
                    Ok:function () {

                        //Get user-entered data from form
                        event.categoryId = inputs.categoryId.val();
                        event.title = inputs.title.val();
                        event.startDate  = inputs.startDate.val();
                        event.endDate  = inputs.endDate.val();
                        event.mapAddress = inputs.mapAddress.val();
                        event.text  = inputs.text.val();

                        okCallback(event,$(this));
                    }
                }
            }
        );
    };

    /**
     *
     * @param {String} formTitle
     * @param {Object} [category]
     * @param {Function} okCallback
     */
    me.showCategoryPropertiesDialog = function(formTitle, category, okCallback){
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

    /**
     * updates category array from server
     * @param {Function} [callback]
     */
    me.getCategories = function(callback){
        $.getJSON(
            '/rcm-event-calender/categories',
            function(result) {
                categories=[];
                $.each(result, function(){
                    categories[this.categoryId]=this.name;
                });

                me.setCurrentCategoryToDefault();

                if(typeof(callback)=='function'){
                    callback(categories);
                }
            }
        );
    };

    me.setCurrentCategoryToDefault = function(){
        //Use for-loop to get first key
        for (var firstCategoryId in categories) {
            currentCategoryId = firstCategoryId;
            if(1==1){//Prevent IDE whining
                break;
            }
        }
    };
};