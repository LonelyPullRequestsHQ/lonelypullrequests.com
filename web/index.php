<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

$devMode = (isset($_SERVER['USER']) && $_SERVER['USER'] == 'vagrant');

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
if ($devMode) {
    Debug::enable();
}

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel($devMode ? 'dev' : 'prod', $devMode);
$kernel->loadClassCache();

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
