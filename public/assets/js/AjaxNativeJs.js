function AjaxNativeJs() {
    if (window.XMLHttpRequest || window.ActiveXObject) {
        this.xhttp =  this.getXmlHttpRequest();
        if (this.xhttp === false) {
            throw new Error('Can not instantiate ajax object.');
        }
    }
}

AjaxNativeJs.prototype = {
    getXmlHttpRequest: function () {
        return new XMLHttpRequest()                 ||
            new ActiveXObject('Msxml2.XMLHTTP')     ||
            new ActiveXObject('Microsoft.XMLHTTP')  ||
            false;
    },
    post: function (options, callback, async) {
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
            options['url'] + '?' + this.parseParams(options['params']),
            async
        );
        this.xhttp.setRequestHeader('Content-Type', 'application/json');
        this.xhttp.send();
    },
    parseParams: function (params) {
        var Type = {
            getType: function () {
                switch (Object.prototype.toString.call(params)) {
                    case '[object Array]':
                        return 'Array';
                    case '[object Object]':
                        return 'Object';
                    default:
                        return false;
                }
            },
            isArray: function () {
                return this.getType() === 'Array';
            },
            isObject: function () {
                return this.getType() === 'Object';
            }
        };

        if (Type.isArray()) {
            return params.join("&");
        } else if (Type.isObject()) {
            var p = [];
            Object.keys(params).forEach(function (key) {
                p.push(key + '=' + params[key]);
            });
            return p.join("&");
        } else {
            throw new Error('Can not parse params.');
        }
    }
};
