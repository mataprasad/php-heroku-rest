<?php
require_once 'web-config.php';
require_once 'biz/common.php';
require('../vendor/autoload.php');
date_default_timezone_set("Asia/Kolkata");
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

/**
 * @SWG\Info(title="Lions Protocol.", version="1.0.45",
 * description="Lions Attendance API for Mobile Clients.")
 */
$app = new Silex\Application();

$app->before(function (Request $request) use ($app) {
    $headers=getallheaders();
    $allow_access=true;
    //$headers = $request->headers->all();
    if (!($request->getPathInfo()=="/api/register" || $request->getPathInfo()=="/")) {
        if (!isset($headers["Authorization"])) {
            $allow_access=false;
        } else {
            $auth = $headers["Authorization"][0];
            if ($auth==null || $auth=="") {
                $allow_access=false;
            }
        }
        if (!$allow_access) {
            return $app->json("UnAuthorized", 401);
        }
    }
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $final_data=is_array($data) ? $data : array();
        if (isset($headers["Authorization"])) {
            $final_data["authorization"]=decodePayload(str_replace("Basic ", "", $headers["Authorization"][0]));
        }
        $request->request->replace($final_data);
    }
});

/**
 * @SWG\Get(
 *     path="/",
 *     @SWG\Response(response="200", description="Greeting Service.")
 * )
 */

$app->get('/', function (Request $request) use ($app) {
    /*
    $token="56642fe5fc6bb45823ac30cda66861bedb9aeea35ca368a380f58da80b3472b1705eaf0c1e197e63c64837a61620d4fd5d4209c691b350b91bc4aa3b56d1f746183a57e90c07e192ef87a7e2fd5ce588f392f9d331cb1e135eedb586cde07923f9abbe4afda6a275e32d88160a955790de9df26b471d3ddd8f5d241284274016f950993b7e1d27374e3ff26b6fc8529784976e6e591fe7d0e2016b8a914bedac0142ee937f707f518500655879fc7f807e78de99009d3b6b7002a3670e7e6124703de12aff63196d38f8116de8d2ddf463feb0cbb9336da17d94429e259e513cdd98eae43361c36153f966bdb8122d53";
    return $app->json(decodePayload($token),200);
    return $app->json(successRespone(json_encode((object)$token), "Device registered successfully."), 200);
    */
    return $app->json("I am up and running. Current Time at Server : ".date(DATE_ATOM), 200);
});

//-------------- POST METHODS --------------
/**
 * @SWG\Post(
 *     path="/api/register",
 *     @SWG\Response(response="200", description="Device registration service.")
 * )
 */
$app->post('/api/register', function (Request $request) use ($app) {
    
    try{
     $model = decodePayload ($request->request->get('pay_load'));
        
     $emp=getEmployeeByEmployeeId($model->employeeId);
     
     if ($emp!=null && $emp->EmployeeID==$model->employeeId) {
         $serverKey=GET_GUID();
         $success = updateEmployeeDeviceRegistration($serverKey,$model->mobileNumber, $model->email, $model->pin, $model->imeiNumber, $model->employeeId, $emp->ID);
         if ($success) {
             $token=array();
             $token["name"]=$emp->FirstName." ".$emp->LastName;
             $token["employeeId"]=$model->employeeId;
             $token["email"]=$model->email;
             $token["mobile"]=$model->mobileNumber;
             $token["imeiNumber"]=$model->imeiNumber;
             $token["serverKey"]=$serverKey;
             $token["pin"]=$model->pin;
             $token["expiry"]=date(DATE_ATOM,strtotime('+30 days', time()));
             return $app->json(successRespone(json_encode((object)$token), "Device registered successfully."), 200);
         }
     } else {
         return $app->json(errorRespone("Employee id '".$model->employeeId."' is not available in the system."), 400);
     }
    }
    catch(Exception $ex){

    }
    return $app->json(errorRespone("Internal Server Error.Please try after some time"), 500);
});

/**
 * @SWG\Post(
 *     path="/api/login",
 *     @SWG\Response(response="200", description="Login service.")
 * )
 */
$app->post('/api/login', function (Request $request) use ($app) {
    return $app->json((object)array("Ok"), 200);
});

/**
 * @SWG\Post(
 *     path="/api/puch-in",
 *     @SWG\Response(response="200", description="Attendance punch service.")
 * )
 */
$app->post('/api/puch-in', function (Request $request) use ($app) {
    return $app->json((object)array("Ok"), 200);
});

/**
 * @SWG\Post(
 *     description="Attendance history download service[Sends history as attachment to given email.]",
 *     path="/api/download",
 *     @SWG\Response(response="200", description="")
 * )
 */
$app->post('/api/download', function (Request $request) use ($app) {
    return $app->json((object)array("Ok"), 200);
});


//-------------- PUT METHODS --------------
/**
 * @SWG\Put(
 *     description="Service to update the new pin for app login.",
 *     path="/api/update-pin",
 *     @SWG\Response(response="200", description="")
 * )
 */
$app->put('/api/update-pin/{emp_id}', function (Request $request, $emp_id) use ($app) {
    return $app->json((object)array("Ok"), 200);
});

//-------------- GET METHODS --------------
/**
 * @SWG\Get(
 *     schemes={"https"},
 *     description="Service to get the new pin for app login.",
 *     path="/api/get-pin/{emp_id}",
 *     @SWG\Response(response="200", description="Pin successfully retrieved."),
 *     @SWG\Response(response="401", description="No access token present in the authorization header."),
 *     @SWG\Parameter(name= "emp_id",in= "path",description= "employee id",required= true,type= "string",maxLength= 256),
 *     @SWG\SecurityScheme(
 *         securityDefinition="Bearer",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header"
 *     ),
 * )
 */
$app->get('/api/get-pin/{emp_id}', function (Request $request, $emp_id) use ($app) {
    return $app->json((object)array("Ok"), 200);
});

$app->get('/api/load-locations/{for_dt}/{emp_id}', function (Request $request, $for_dt, $emp_id) use ($app) {
    try{
        //$model = decodePayload ($request->request->get('pay_load'));
        $locations=getTodayLocationsForDate($emp_id,$for_dt);
        if ($locations!=null && sizeof($locations)>0) {
            return $app->json(successRespone(json_encode($locations),"Success."), 200);
        } else {
            return $app->json(errorRespone("No records found."), 400);
        }
       }
       catch(Exception $ex){
   
       }
       return $app->json(errorRespone("Internal Server Error.Please try after some time"), 500);
});

$app->get('/api/history/{from}/{to}/{emp_id}', function (Request $request, $from, $to, $emp_id) use ($app) {
 


    return $app->json(successRespone('{"pay_load":"Hello World!"}'), 200);
});

$app->after(function (Request $request, Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

$app->run();
