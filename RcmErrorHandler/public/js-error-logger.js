window.onerror = function (message, file, line) {
    // Supports IE7+, Firefox, Chrome, Opera, Safari
    if (window.XMLHttpRequest) {
        var http = new XMLHttpRequest();
        http.open('POST', '/api/rcm-error-handler/js-error', true);
        http.setRequestHeader('Content-Type', 'application/json');
        http.send(JSON.stringify({
            message: message,
            file: file,
            line: line
        }));
    }
};