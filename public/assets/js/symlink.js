var Symlink = {};

Symlink.Symlink = function () {
    this.ajax = new Symlink.Ajax();
};

Symlink.Symlink.prototype.callback = function (response) {
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

Symlink.Symlink.prototype.validate = function () {
    var el = this.getForm().init();
    var options = {
        'type': 'GET',
        'url': '/symlink/public/action/validator.php',
        'params': [
            "target=" + el.target.value,
            "destination=" + el.destination.value
        ].join("&")
    };
    return this.ajax.send(options, this.callback);
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
            var that = this;
            this.el = {
                target: that.getTarget(),
                destination: that.getDestination()
            };

            return this.el;
        }
    };
};

Symlink.Symlink.prototype.link = function () {
    var el = this.getForm().init();
    var options = {
        'type': 'GET',
        'url': '/symlink/public/action/symlink.php',
        'params': [
            "target=" + el.target.value,
            "destination=" + el.destination.value
        ].join("&")
    };

    this.ajax.send(options, function (response) {
        console.log(JSON.parse(response.responseText));
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