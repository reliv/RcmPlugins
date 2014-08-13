(function ($) {
    /**
     * Pops up an alert dialog using Boostrap
     *
     * @param {String} text what to say to user
     * @param {Function} [okCallBack] optional callback for ok button
     * @param {String} [title] optional the title bar text
     */
    $.fn.alert = function (text, okCallBack, title) {
        if (typeof(title) == 'undefined') {
            title = 'Alert';
        }
        var modal = $(
            '<div class="modal fade">' +
                '<div class="modal-dialog">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal">' +
                '<span aria-hidden="true">&times;</span>' +
                '<span class="sr-only">Close</span>' +
                '</button>' +
                '<h1 class="modal-title">' + title + '</h1></div>' +
                ' <div class="modal-body"><p>' + text + '</p></div> ' +
                '<div class="modal-footer">' +
                ' <button type="button" class="btn btn-primary ok">Ok</button> ' +
                '</div>  </div><!-- /.modal-content --> ' +
                ' </div><!-- /.modal-dialog -->' +
                '</div><!-- /.modal -->');
        modal.modal('show');
        modal.find('.ok').click(function () {
            $('.modal-backdrop').remove();
            modal.modal('hide');
            okCallBack();
        });
    };
    /**
     * Pops up a confirm dialog using Boostrap
     *
     * @param {String} text what we are asking the user to confirm
     * @param {Function} [okCallBack] optional callback for ok button click
     * @param {Function} [cancelCallBack] optional callback for cancel button click
     * @param {String} [title] optional the title bar text
     */
    $.fn.confirm = function (text, okCallBack, cancelCallBack, title) {
        if (typeof(title) == 'undefined') {
            title = 'Confirm';
        }
        var modal = $(
            '<div class="modal fade">' +
                '<div class="modal-dialog">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<button type="button" class="close" data-dismiss="modal">' +
                '<span aria-hidden="true">&times;</span>' +
                '<span class="sr-only">Close</span>' +
                '</button>' +
                '<h1 class="modal-title">' + title + '</h1></div>' +
                ' <div class="modal-body"><p>' + text + '</p></div> ' +
                '<div class="modal-footer">' +
                ' <button type="button" class="btn btn-default cancel">Cancel</button> ' +
                ' <button type="button" class="btn btn-primary ok">Ok</button> ' +
                '</div>  </div><!-- /.modal-content --> ' +
                ' </div><!-- /.modal-dialog -->' +
                '</div><!-- /.modal -->');
        modal.modal('show');
        modal.find('.cancel').click(function () {
            $('.modal-backdrop').remove();
            modal.modal('hide');
            cancelCallBack();
        });
        modal.find('.ok').click(function () {
            $('.modal-backdrop').remove();
            modal.modal('hide');
            okCallBack();
        });
    };
})(jQuery);