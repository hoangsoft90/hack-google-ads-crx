<?php
include('functions.php');
include('libs/php-ga-1.1.1/src/autoload.php');

$pid=isset($_GET['postid'])? $_GET['postid']:0;

if(isset($_GET['site']) && isset($_GET['q']) && $_GET['site']=='google'){
    //choose ads
    $data=get_ads($pid,$_GET['site'],$_GET['q']);        //dont need postid
    $data=str_replace('{{q}}',$_GET['q'],$data);
    
    echo($data); //print ads content
}
elseif(isset($_GET['site']) && isset($_GET['q']) && $_GET['site']=='bing'){
    
}
elseif(isset($_GET['site']) && isset($_GET['q']) && $_GET['site']=='yahoo'){
    
}
elseif(isset($_GET['postid'])){
  //random ad
  $data=get_ads($pid);
  if(isset($_GET['q'])) $data=str_replace('{{q}}',$_GET['q'],$data);
  echo($data);
}
/*tracking ga*/
/*
use UnitedPrototype\GoogleAnalytics;

// Initilize GA Tracker
$tracker = new GoogleAnalytics\Tracker('UA-46107816-1', 'http://app-ads.hoangweb.com');

// Assemble Visitor information
// (could also get unserialized from database)
$visitor = new GoogleAnalytics\Visitor();
$visitor->setIpAddress($_SERVER['REMOTE_ADDR']);
$visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);
//$visitor->setScreenResolution('1024x768');

// Assemble Session information
// (could also get unserialized from PHP session)
$session = new GoogleAnalytics\Session();

// Assemble Page information
$page = new GoogleAnalytics\Page(get_current_url());
$page->setTitle('Get Afvertising from '.get_current_url());

// Track page view
$tracker->trackPageview($page, $session, $visitor);
*/
?>