<!doctype html>
<!--[if lt IE 7 ]><html itemscope itemtype="http://schema.org/Product" id="ie6" class="ie ie-old" lang="en-US" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if IE 7 ]>   <html itemscope itemtype="http://schema.org/Product" id="ie7" class="ie ie-old" lang="en-US" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if IE 8 ]>   <html itemscope itemtype="http://schema.org/Product" id="ie8" class="ie ie-old" lang="en-US" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if IE 9 ]>   <html itemscope itemtype="http://schema.org/Product" id="ie9" class="ie" lang="en-US" prefix="og: http://ogp.me/ns#"><![endif]-->
<!--[if gt IE 9]><!--><html itemscope itemtype="http://schema.org/Product" lang="en-US" prefix="og: http://ogp.me/ns#"><!--<![endif]-->
<head>
    <title>File Sync</title>
    <link rel="stylesheet" type="text/css" href="public/assets/css/styles.css" />
</head>
<body>
<script type="text/javascript" src="public/assets/js/symlink.js"></script>
<script type="text/javascript">
    'use strict';
    Symlink.App  = {
        init: function () {
            this.symlink = new Symlink.Symlink();
        },
        validate: function (id) {
            this.symlink.validate(id);
            return this;
        },
        link: function (id) {
            this.symlink.link(id);
        }
    };
    Symlink.App.init();
</script>
    <div class="container">
        <div class="title">
            <h1>File Symlink</h1>
            <hr />
        </div>
        <div class="form">
            <form onsubmit="event.preventDefault();">
                <fieldset>
                    <legend>Php File Symlink</legend>
                    <div class="field">
                        <label for="target" class="field-label">Target:</label>
                        <div class="field-target">
                            <input type="text" name="target" class="text-input target" id="target" /><span><small>Add absolute path to the target folder</small></span>
                        </div>
                        <div class="message">
                            <p id="target-message"></p>
                        </div>
                    </div>
                    <div class="field">
                        <label for="destination" class="field-label">Destination:</label>
                        <div class="field-destination">
                            <input type="text" name="destination" class="text-input destination" id="destination" /><span><small>Add absolute path to destination folder</small></span>
                        </div>
                        <div class="message">
                            <p id="destination-message"></p>
                        </div>
                    </div>
                    <div class="field field-submit">
                        <button class="submit" id="validate" onclick="Symlink.App.validate(this.id);">Validate</button>
                        <button class="submit" id="symlink" onclick="Symlink.App.link(this.id);">Link</button>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="result" id="result">
            <p id="result-message"></p>
        </div>
    </div>
<div class="footer">
    <p>&copy; <b><small>Alaa Al-Maliki</small></b> <br /><span class="email">alaa.almaliki@gmail.com</span></p>
</div>
</body>
</html>