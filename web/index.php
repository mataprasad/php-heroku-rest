<?php
require_once 'web-config.php';
require_once 'biz/common.php';
require('../vendor/autoload.php');
date_default_timezone_set("Asia/Kolkata");
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app->before(function (Request $request) use ($app) {
    $allow_access=true;
    $headers = $request->headers->all();
    if (!($request->getPathInfo()=="/api/register" || $request->getPathInfo()=="/")) {
        if (!isset($headers["authorization"])) {
            $allow_access=false;
        } else {
            $auth = $headers["authorization"][0];
            if ($auth==null || $auth=="") {
                $allow_access=false;
            }
        }
        if (!$allow_access) {
            return $app->json("UnAuthorized", 400);
        }
    }
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->get('/', function (Request $request) use ($app) {
    //dbTest();
    return $app->json("I am up and running. Current Time at Server : ".date(DATE_ATOM), 200);
});

//-------------- POST METHODS --------------
$app->post('/api/register', function (Request $request) use ($app) {
    
    $pay_load = $request->request->get('pay_load');
    $headers = $request->headers->all();

    return $app->json($pay_load." ".($headers["authorization"][0]), 200);
});

$app->post('/api/login', function (Request $request) use ($app) {
    return $app->json("Ok", 200);
});

$app->post('/api/puch-in', function (Request $request) use ($app) {
    return $app->json("Ok", 200);
});

$app->post('/api/download', function (Request $request) use ($app) {
    return $app->json($from." ".$to." Ok ".$emp_id." ".$email, 200);
});


//-------------- PUT METHODS --------------

$app->put('/api/update-pin', function (Request $request) use ($app) {
    return $app->json("Ok", 200);
});

//-------------- GET METHODS --------------

$app->get('/api/get-pin/{emp_id}', function (Request $request, $emp_id) use ($app) {
    return $app->json("Ok".$emp_id, 200);
});

$app->get('/api/load-locations/{for_dt}/{emp_id}', function (Request $request, $for_dt, $emp_id) use ($app) {
    return $app->json($for_dt."Ok".$emp_id, 200);
});

$app->get('/api/history/{from}/{to}/{emp_id}', function (Request $request, $from, $to, $emp_id) use ($app) {
    return $app->json($from." ".$to." Ok ".$emp_id, 200);
});

$app->run();
