<?php
phpinfo();
 require('../vendor/autoload.php');
 $swagger = \Swagger\scan('./');
 header('Content-Type: application/json');
 header('Access-Control-Allow-Origin: *');
 echo $swagger;

