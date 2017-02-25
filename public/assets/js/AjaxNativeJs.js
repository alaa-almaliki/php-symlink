function AjaxNativeJs() {
    if (typeof XMLHttpRequest !== 'undefined') {
        this.xhttp =  new XMLHttpRequest();
    }
}

AjaxNativeJs.prototype = {
    send: function (options, callback, async) {
        if (typeof async === 'undefined') {
            async = true;
        }

        this.xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                if (typeof callback === 'undefined') {
                    return this;
                }
                return callback(this);
            }
        };

        this.xhttp.open(
            options['type'],
            options['url'] + '?' + options['params'],
            async
        );
        this.xhttp.setRequestHeader('Content-Type', 'application/json');
        this.xhttp.send();
    }
};
