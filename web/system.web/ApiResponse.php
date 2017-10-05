<?php

function successRespone($data, $message = "")
{
    $res=array();
    $res["success"]=true;
    $res["message"]=$message;
    if ($data && $data!="") {
        $mcrypt = new MCrypt();
        $res["data"]=$mcrypt->encrypt($data);
    } else {
        $res["data"]=null;
    }
    return (object)$res;
}

function errorRespone($errorMessage)
{
    $res=array();
    $res["success"]=false;
    $res["message"]=$errorMessage;
    $res["data"]=null;
    return (object)$res;
}
