<?php
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'Bootstrap.php';
BootStrap::register();

use Symlink\Validator;

$validator = new Validator();

$data = [
    'target'        => trim($_GET['target']),
    'destination'   => trim($_GET['destination']),
];

echo $validator->validate($data);