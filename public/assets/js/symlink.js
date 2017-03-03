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
            'log_enabled=' + this.form().logEnabled().checked,
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
        },
        logEnabled: function () {
            return this._getElement('log_enabled');
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
            'log_enabled=' + this.form().logEnabled().checked,
            "action=" + id
        ].join("&")
    };

    this.ajax.send(options, function (response) {
        var results = JSON.parse(response.responseText);
        var resultDev = document.getElementById('result');
        resultDev.innerHTML = '';
        resultDev.style.display = 'block';

        results.forEach(function (result) {
            var color = result['status']? '#00FF00': '#FF0000';
            var p = document.createElement("p");
            p.innerHTML = result['message'];
            p.style.color = color;
            resultDev.appendChild(p);
        });
    });
};