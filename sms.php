<?php
session_start();
include('functions.php');

$code=$_REQUEST['code'];    //sub
$subcode=$_REQUEST['subCode'];  //subcode
$sender=$_REQUEST['mobile'];
$servNum=$_REQUEST['serviceNumber'];    //service number
$mess=$_REQUEST['info'];    //message

//generate code
$code=RandomString(10);
if(count(select_sql('select * from crx_demo_users where phone="'.$sender.'"'))){    //update
    mysql_update('crx_demo_users',array('verify'=>'1','activation_code'=>$code),'phone="'.$sender.'"');
}
else{   //add new demo
    //add user to crx_demo_users table
    $id=mysql_insert('crx_demo_users',array('phone'=>$sender,'verify'=>'1','activation_code'=>$code));
    //add advertising data to crx_ads
    mysql_insert('crx_ads',array('user'=>$id));
}


        
//0 là sms dạng text
$resp="0|Your Code:".$code.' Login URL: http://app-ads.hoangweb.com/ksn-crx/admin.php';
echo $resp;
?>