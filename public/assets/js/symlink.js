var Symlink = {};

Symlink.Symlink = function () {
    this.ajax = new AjaxNativeJs();
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
        message.innerHTML = '';
        message.innerHTML = result['message'];
        message.style.color = colours[result['found']];
        success &= result['found'];
    });
    return success;
};

Symlink.Symlink.prototype.validate = function (id) {
    var options = {
        'type': 'GET',
        'url': this.url,
        'params': [
            "target=" + this.form().getTarget().value,
            "destination=" + this.form().getDestination().value,
            'clean=' + this.form().isClean().checked,
            "action=" + id
        ].join("&")
    };
    return this.ajax.send(options, this._validate);
};

Symlink.Symlink.prototype.form = function () {
    return {
        _getElement: function (id) {
            return document.getElementById(id);
        },
        getTarget: function () {
            return this._getElement('target');
        },
        getDestination: function () {
            return this._getElement('destination');
        },
        isClean: function () {
            return this._getElement('clean');
        }
    };
};

Symlink.Symlink.prototype.link = function (id) {
    var options = {
        'type': 'GET',
        'url': this.url,
        'params': [
            "target=" + this.form().getTarget().value,
            "destination=" + this.form().getDestination().value,
            "clean=" + this.form().isClean().checked,
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

        var resultDev = document.getElementById('result');
        var resultMessage = document.getElementById('result-message');
        resultDev.style.display = 'block';
        resultMessage.innerHTML = results['message'];


    });
};