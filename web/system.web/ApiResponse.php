<?php

function successRespone($data)
{
    $res=array();
    $res["success"]=true;
    $res["errorMessage"]=null;
    $mcrypt = new MCrypt();
    $res["data"]=$mcrypt->encrypt($data);
    return (object)$res;
}

function errorRespone($errorMessage)
{
    $res=array();
    $res["success"]=false;
    $res["errorMessage"]=$errorMessage;
    $res["data"]=null;
    return (object)$res;
}
