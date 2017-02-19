<?php
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'autoload.php';

use Symlink\SymlinkInterface as Link;
use Symlink\Symlink;

$params = [
    'target'        => trim($_GET['target']),
    'destination'   => trim($_GET['destination']),
    'action'        => trim($_GET['action']),
];

$symlink = new Symlink($params);

if ($params['action'] === Link::ACTION_LINK) {
    echo $symlink->link();
} else if ($params['action'] === Link::ACTION_VALIDATE) {
    echo $symlink->validate();
} else {
    throw new \Exception('Unknown Action');
}
