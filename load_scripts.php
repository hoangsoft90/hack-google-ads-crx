<?php
header("content-type:application/javascript");
/*
-load ad content.
*/
include('functions.php');
ob_start();
//load plugin
/*echo curl('http://localhost:81/test/ksn-crx/blur-content-crx/load-scripts-cover.php');*/
?>

/**
* behavior for google site
*/
function get_google_ad(id){
    /*var q='Ket qua tim kiem cho: "'+$('#gbqfq').val()+'"';*/
    chrome.runtime.sendMessage({text:'load_site_ad',site:'google',q:$('#gbqfq').val(),pid:id});
}
/**
* behavior for bing site
*/
function get_bing_ad(id){
    chrome.runtime.sendMessage({text:'load_site_ad',site:'bing',q:'basfsdf',pid:id});
}
/**
* get yahoo ad
*/
function get_yahoo_ad(id){
    chrome.runtime.sendMessage({text:'load_site_ad',site:'yahoo',q:'abc',pid:id});
}
/**
* get default ad
*/
function get_default_ad(id) {
    chrome.runtime.sendMessage({text:'load_default_ad',pid:id});
}
/**
* place google ad
*/
var timer1=0,times=0;

function place_google_ad(data){
    /*place ad*/
    console.log('call place_google_ad from keyword.');
    /*place ad*/
    var place_ad=function(){
        if(timer1) clearTimeout(timer1);
        timer1=setInterval(function(){
            if( $('#rso').length) {
                console.log('place ad');
                if($('#vcn-ksn-from-crx').length ){
                    $('#vcn-ksn-from-crx').html(data);
                } 
                else {
                    var li=$('<li class="g" id="vcn-ksn-from-crx"/>').append(data);
                    $('#rso').prepend(li);
                }
                clearInterval(timer1);
                times=0;
                oneCall=0;
            }
            if(times++>100){
                times=0;    /*reset times to 0*/
                clearInterval(timer1);
            }
        },100);
    };
    place_ad();
    var oneCall=0;
    /*for ajax search*/
    if(jQuery('#main').length && 
        (!jQuery('#main').data('events') || !jQuery('#main').data('events').DOMSubtreeModified)
        ){
        
        var timer2,count=0;
        $('#main').bind("DOMSubtreeModified",function(){
            if(oneCall++<10){
                console.log('DOMSubtreeModified');
                /*if(timer2) clearTimeout(timer2);*/
                timer2=setTimeout(function(){
                    if($('#vcn-ksn-from-crx').length==0){
                        place_ad();
                    }
                },100);
            }
        });
    }
    if(!jQuery('#gbqfq').data('events') || !jQuery('#gbqfq').data('events').keypress){
        var timer3;
        $('#gbqfq').bind('keypress',function(){
            if(timer3) clearTimeout(timer3);
            timer3=setTimeout(function(){
                /*if(!$('#vcn-ksn-from-crx').length){*/
                    console.log('from keypress');
                    port.postMessage({text: "load_ad",site:'google',q:jQuery('#gbqfq').val()});
                /*}*/
            },500);
            
        });
        port.onMessage.addListener(function(msg) {
          data=(msg.data);
          place_ad();
        });
    }
}
/**
* place default ad
*/
function place_default_ad(data){
    $('<div id="vcn_ad_extension"/>').append(data).appendTo($(document.body));
}
/**
* place default ad for vnexpress
*/
function place_ad1_vnexpress(data){
    if($('#vcn_ad_extension').length) return;
    var after=['#header','#wrapper_header'];
    for(var i=0;i< after.length;i++){
        if($(after[i]).length){
            $('<div id="vcn_ad_extension"/>').append(data).insertAfter($(after[i]));
            return;
        }
    }
}
function place_ad2_vnexpress(data){
    if($('#vcn_ad_extension').length) return;
    console.log('place vnexpress ad');
    var myad=$('<div id="vcn_ad_extension" class="box-item" style="width:100%; float:left; margin:0px 0px 10px;"><div class="listitem_title fl"><div id="listitem_title1"><a href="#">Quảng Cáo</a></div></div></div>');
    myad.append(data);
    myad.insertAfter($("#content .content-left .box-item").eq(0));
}
/**
* get ad content base on site
* inherit: request.tab
*/
function decide_ad_content_base_site(){
    <?php
    $data=select_sql('select * from crx_ads order by pattern DESC');
    $code='';   //code
    $check_exclude_pattern=array();
    
    foreach($data as $r){
        if($r['pattern'] && !isset($check_exclude_pattern[$r['pattern']])){
            if(!trim($r['get_ad_method'])) $r['get_ad_method']=RandomString(5);
            
            $code.=sprintf('if(%s.test(request.tab.url)){ if(typeof %s=="function") %s(%s);}else ',$r['pattern'],$r['get_ad_method'],$r['get_ad_method'],$r['id']);
        } 
        $check_exclude_pattern[$r['pattern']]=1;    //mark pattern make sure distinct pattern
    }
    //else do default
    $code.='if(typeof get_default_ad=="function") get_default_ad();';
    echo $code;
    ?>
}
/**
* load complete ad content
*/
function complete_ad_content(){
    <?php
    $data=select_sql('select * from crx_ads order by pattern DESC');
    $code='';   //code
    $check_exclude_pattern=array();
    
    foreach($data as $r){
        if($r['pattern'] && !isset($check_exclude_pattern[$r['pattern']])){
            if(!trim($r['place_ad_method'])) $r['place_ad_method']=RandomString(5);
            $code.=sprintf('if(%s.test(request.tab.url)){ if(typeof %s=="function") %s(request.data);}else ',$r['pattern'],$r['place_ad_method'],$r['place_ad_method']);
        } 
        $check_exclude_pattern[$r['pattern']]=1;    //mark pattern
    }
    //else do default
    $code.='if(typeof place_default_ad=="function") place_default_ad(request.data);';
    echo $code;
    ?>
}
/*track google analyst*/
function track_ganalyst(){
    /*track by google analyst*/
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-46107816-1']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = 'https://ssl.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
}
<?php
$generatedoutput = ob_get_contents();
ob_end_clean();
    //echo $generatedoutput;
    echo js_obfuscator_with_php($generatedoutput);
?>