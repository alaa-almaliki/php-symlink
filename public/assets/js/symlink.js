var Symlink = {};

Symlink.Symlink = function () {
    this.ajax = new Symlink.Ajax();
    this.url = '/symlink/public/action/symlink.php';
};

Symlink.Symlink.prototype._validate = function (response) {
    var results = JSON.parse(response.responseText);
    var colours = {
        true: "green",
        false: "red"
    };

    var success = true;
    results.forEach(function (result) {
        var id = result['field'] + "-message";
        var message = document.getElementById(id);
        message.innerHTML = result['message'];
        message.style.color = colours[result['found']];
        success &= result['found'];
    });
    return success;
};

Symlink.Symlink.prototype.validate = function (id) {
    var el = this.getForm().init();
    var options = {
        'type': 'GET',
        'url': this.url,
        'params': [
            "target=" + el.target.value,
            "destination=" + el.destination.value,
            "action=" + id
        ].join("&")
    };
    return this.ajax.send(options, this._validate);
};

Symlink.Symlink.prototype.getForm = function () {
    return {
        el: {},

        _getElement: function (id) {
            return document.getElementById(id);
        },
        getTarget: function () {
            return this._getElement('target');
        },
        getDestination: function () {
            return this._getElement('destination');
        },

        init: function () {
            var self = this;
            this.el = {
                target: self.getTarget(),
                destination: self.getDestination()
            };

            return this.el;
        }
    };
};

Symlink.Symlink.prototype.link = function (id) {
    var el = this.getForm().init();
    var options = {
        'type': 'GET',
        'url': this.url,
        'params': [
            "target=" + el.target.value,
            "destination=" + el.destination.value,
            "action=" + id
        ].join("&")
    };

    var self = this;
    this.ajax.send(options, function (response) {
        var results = JSON.parse(response.responseText);

        if (typeof results === 'undefined') {
            return false;
        }

        if (results.constructor === Array) {
            return self._validate(response);
        }
    });
    
};

Symlink.Ajax = function () {
    if (typeof XMLHttpRequest !== 'undefined') {
        this.xhttp =  new XMLHttpRequest();
    }

};

Symlink.Ajax.prototype.send = function (options, callback, async) {
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
};