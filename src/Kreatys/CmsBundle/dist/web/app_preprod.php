<?php

use Symfony\Component\HttpFoundation\Request;
// ***** POUR DEBUG
//use Symfony\Component\Debug\Debug;
// ***** FIN POUR DEBUG

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
// ***** POUR DEBUG
//Debug::enable();
// ***** FIN POUR DEBUG

require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('preprod', false);
//$kernel = new AppKernel('preprod', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

