<?php
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'Bootstrap.php';
BootStrap::register();

$symlink = new \Symlink\Symlink(
    [
        'target'        => trim($_GET['target']),
        'destination'   => trim($_GET['destination']),
    ]
);

echo $symlink->link();