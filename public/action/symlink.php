<?php
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'autoload.php';

use Symlink\SymlinkInterface as Link;
use Symlink\Validator;
use Symlink\Symlink;

$logEnabled = trim($_GET['log_enabled']) === 'true' ? true : false;
define('LOG_ENABLED', $logEnabled);

$params = [
    'target'        => trim($_GET['target']),
    'destination'   => trim($_GET['destination']),
    'clean'         => trim($_GET['clean']),
    'action'        => trim($_GET['action']),
];

$symlink = new Symlink(new Validator($params));

if ($params['action'] === Link::ACTION_LINK) {
    echo $symlink->link();
} else if ($params['action'] === Link::ACTION_VALIDATE) {
    echo $symlink->validate();
} else {
    throw new \Exception('Unknown Action');
}
