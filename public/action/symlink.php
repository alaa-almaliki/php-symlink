<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'params.php';
require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'autoload.php';

echo (new \Symlink\Symlink($params))->link();