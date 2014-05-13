/**
 * This ensures forms can only be submitted once and shows that it is loading via css
 */
$().ready(function () {
    $.each($('form.rcmDoubleSubmitProtect'), function () {
        var form = $(this);
        form.on('submit', function () {
            if (form.hasClass('processing')) {
                return false;
            }
            form.addClass('processing');
            form.submit();
            return true;
        });
    });
});