<?php
header('content-type:application/javascript');
include('functions.php');
ob_start();
?>
/**
* load cover content
* @param string site: choose cover for the site
*/
function load_cover(site){
    var list_domains={
        'khosachnoi.net':{add_cover:lock_cover1,remove_cover:remove_cover1},
        'http://localhost:81/test/ksn-crx':{add_cover:lock_cover1,remove_cover:remove_cover1}
    };
    if(list_domains && site ){
        if(list_domains[site]) return list_domains[site];else return null;
    }
    return list_domains;
}
/**
* add overlay cover on div
*/
function add_cover_content(target){
    $(target).addClass('blur-text');
    /*init cover*/
    var url='http://www.mediafire.com/download/2kag88vh321w6ub/KhoSachNoi.exe';
    var cover='<div id="ksn-overlay-content-cover">';
    cover+='<div class="cover" style="border: 1px solid red;"></div>';
    cover+='<div class="front"><button onclick=\'window.open("'+url+'","_blank");\'>Vui lòng cài plugin để xem</button><br/><div>Chú ý: Sau khi cài đặt thành công, vui lòng khởi động lại trình duyệt chrome.</div></div>';
    cover+='</div>';
    
    $(cover).css({
        position: "absolute",
        width: "100%",
        height: "100%",
        left: 0,
        top: 0,
        zIndex: 1000
    }).appendTo($(target).css("position", "relative"));
}
/**
* add cover for site khosachnoi.net from webpage
*/
function lock_cover1(){
    /*send to unlock
    chrome.runtime.sendMessage('cffmjpapjbopipehkegcljpdlahamlnc', {type:'method',param: window.location},function(res){
        
    });*/
    add_cover_content('.entry-content');
}
function remove_cover1(){
    /*check_exists_extension('cffmjpapjbopipehkegcljpdlahamlnc',function(rs){
        if(rs==true){
            
        }
        else{
            alert('detect chrome extension is not installed on your browser.');
        }
    });*/
    $('#ksn-overlay-content-cover').remove();
    $('.entry-content').addClass('remove-blur-text');
}

/**
* get protect method site
*/
function get_add_methods_cover_site(url){
    var list_domains=load_cover();
    for(var domain in list_domains){
        if(url.indexOf(domain)!==-1){
            /*add cover*/
            if(typeof list_domains[domain].add_cover=='function'){
                return list_domains[domain].add_cover;
            }
            break;
        }
    }
}
/**
* get remove cover method for the site.
*/
function get_remove_method_cover_site(url){
    var list_domains=load_cover();
    for(var domain in list_domains){
        if(url.indexOf(domain)!==-1){
            /*add cover*/
            if(typeof list_domains[domain].remove_cover=='function'){
                return list_domains[domain].remove_cover;
            }
            break;
        }
    }
}
/**
* detect whether your extension is installed or not install on browser
* @param string crxId: extension ID
* @param callback cb: callback method
*/
function check_exists_extension(crxId,cb){
    function Ext_Detect_NotInstalled(ExtName,ExtID) {
       console.log(ExtName + ' Not Installed');
       if(typeof cb=='function') cb(false);
       /*bring user to that extension chrome store*/
       var message='Page needs ' + ExtName + ' Extension -- to intall the LocalLinks extension click <a href="https://chrome.google.com/webstore/detail/locallinks/' + ExtID +'">here</a>';
    }

    function Ext_Detect_Installed(ExtName,ExtID) {
        console.log(ExtName + ' Installed');
        if(typeof cb=='function') cb(true);
    }

    var Ext_Detect = function(ExtName,ExtID) {
        var s = document.createElement('script');
        s.onload = function(){Ext_Detect_Installed(ExtName,ExtID);};
        s.onerror = function(){Ext_Detect_NotInstalled(ExtName,ExtID);};
        s.src = 'chrome-extension://' + ExtID + '/manifest.json';
        document.body.appendChild(s);
    }
    /*start checking*/
    var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
    if (is_chrome==true)
    {
      return Ext_Detect('ext_name',crxId);
    }
}
/*check current chrome browser*/
function is_chrome_browser(){
    return (typeof(chrome) !== 'undefined');
}
<?php
$generatedoutput = ob_get_contents();
ob_end_clean();
    
echo js_obfuscator_with_php($generatedoutput);
?>