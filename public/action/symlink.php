<?php
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'autoload.php';

use Symlink\SymlinkInterface as Link;

$params = [
    'target'        => trim($_GET['target']),
    'destination'   => trim($_GET['destination']),
    'action'        => trim($_GET['action']),
];

if ($params['action'] === Link::ACTION_LINK) {
    echo (new \Symlink\Symlink($params))->link();
} else if ($params['action'] === Link::ACTION_VALIDATE) {
    echo (new \Symlink\Symlink($params))->validate($params);
}
