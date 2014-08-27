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
            '<div id="alertModal" class="modal fade">' +
                '<div class="modal-dialog">' +
                '<div class="modal-content">' +
                '<div class="modal-header">' +
                '<button type="button" class="close">' +
                '<span class="sr-only">Close</span>' +
                '</button>' +
                '<h1 class="modal-title">' + title + '</h1></div>' +
                ' <div class="modal-body"><p>' + text + '</p></div> ' +
                '<div class="modal-footer">' +
                ' <button id="closeAlert" type="button" class="btn btn-primary ok">Ok</button> ' +
                '</div>  </div><!-- /.modal-content --> ' +
                ' </div><!-- /.modal-dialog -->' +
                '</div><!-- /.modal -->');
        modal.modal('show');
        modal.find('.ok').click(function () {
            $('.modal-backdrop').remove();
            $('#alertModal').remove();
            modal.modal('hide');
            if (typeof(okCallBack) == 'function') {
                okCallBack();
            }
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

        if (!title) {
            title = 'Confirm';
        }

        var message = title;
        if(text){
            message = '<div class="modal-body"><p>' + text + '</p></div>';
        }

        var config = {
            message: message,
            title: '<h1 class="modal-title">' + title + '</h1>',
            buttons: {
                cancel: {
                    label: "Cancel",
                    className: "btn-default",
                    callback: function () {
                    }
                },
                ok: {
                    label: "Ok",
                    className: "btn-primary",
                    callback: function () {
                    }
                }
            }

        };

        if (typeof cancelCallBack === 'function') {
            config.buttons.cancel.callback = cancelCallBack;
        }

        if (typeof okCallBack == 'function') {
            config.buttons.ok.callback = okCallBack;
        }

        bootbox.dialog(config);
    };
})(jQuery);