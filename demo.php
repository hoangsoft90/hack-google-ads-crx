<?php
    session_start();
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Công cụ quảng cáo miễn phí lên google - google adword</title>
    <meta name="Description" content="Công cụ đăng quảng cáo miễn phí lên google tìm kiếm, giúp từ khoá của bạn lên top google adword không cạnh tranh với bất kỳ ai. Bằng việc sử dụng hệ thống quảng này sẽ giảm được chi phí lớn về quảng cáo mà vẫn đạt hiểu quả như mong muốn. Liên hệ: 0944.049.910" />
    <meta name="Keywords" content="quảng cáo,quang cao,google adword,miễn phí,mien phi,SEO từ khoá,SEO tu khoa,quảng cáo miễn phí,quang cao mien phi,quảng cáo vnexpress,quang cao vnexpress,công cụ SEO,cong cu SEO,công cụ marketing,cong cu marketing,SEO tool,marketing strategy" />
    <meta name="Distribution" content="Global" />
    <meta name="Author" content="Mr.Hoang - 0944.049.910" />
    <meta name="Robots" content="index,follow" />
    <style>
    .footer{
        border-top:1.5px solid gray;
    }
    .aroundbox{
        border:1px solid red;
        background:rgb(255, 252, 192);
        padding:4px;
    }
    .box{margin-bottom:5px;}
    .box .title{
        font-weight:bold;
    }
    .message{
        background:pink;border:1px solid red;padding:10px;
    }
    .message ul li{list-style-type:none ;}
    </style>
    <!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
$.src='//cdn.zopim.com/?139jF14UcVKQFUhDgfGI8nx4qvWZUDyK';z.t=+new Date;$.
type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
</script>
<!--End of Zopim Live Chat Script-->
</head>
<body>
<div class="wraper">
    <div class="header"><h2>Demo quảng cáo trên google</h2>
    <p>Chi tiết: <a target="_blank" href="https://docs.google.com/document/d/1a4O9DtT1CvK8u-WyJrOodgTeQaVnSwvdFEzezyJC5BY/edit">http://goo.gl/1zLunq</a></p>
    </div>
    <div class="main">
<?php
include('functions.php');
//send_mail('quachhoang_2005@yahoo.com','sdfdsf','sdfdsg');
/*logout*/
if(isset($_REQUEST['logout'])){
    logout_demo();
}
/*login demo user*/
if(isset($_POST['login'])){
    $_POST['phone']=mysql_real_escape_string($_POST['phone']);  //valid input
    //get ref info
    $ref='IP:'.$_SERVER['REMOTE_ADDR'].', from: '.$_SERVER['HTTP_REFERER'].', browser: '.$_SERVER['HTTP_USER_AGENT'];
    
    $demo=login_demo2($_POST['phone'],$_POST['pass']);
    if($demo){
        if(!$demo['verify']){
            $logs[]='Bạn chưa kích hoạt email.';
            $logs[]='Vui lòng soạn tin nhắn: VAS DEMOQC gửi 8085';
        }
        else {
            $_SESSION['vcn_extension']['demo']=$demo;
        }
    }
    else{
        $logs[]='Đã khởi tạo tài khoản demo.';
        $logs[]='Vui lòng soạn tin nhắn: VAS DEMOQC gửi 8085';
    }
}

?>
<div style="display:table;width: 100%;">
    <div style="display:table-cell;vertical-align:top;">
    <?php show_logs();//show logs?>
<?php if( (!isset($_SESSION['vcn_extension']) || !isset($_SESSION['vcn_extension']['demo']))){?>
<!-- login form -->
<h2>Login/registry to Dashboard</h2>
<div style="">
<p><strong>Đăng ký tài khoản:</strong></p>
<ul>
    <li>Vì lý do host firewall, nêu chúng tôi không dùng phương pháp gửi mã code vào mail được, thay vào đó bạn phải soạn tin để kích hoạt tài khoản (phí: 500đ/sms).</li>
    <li>Để đăng ký soạn tin: VAS DEMOQC gửi: 8085 (phí: 500đ)</li>
</ul>
<p><strong>Đăng nhập</strong></p>
</div>
<form method="post" action="demo.php">
<!-- <label for="email">Email</label><input type="text" name="email" value=""/> -->
<TABLE>
    <tr>
        <td><label for="phone">Phone</label></td>
        <td><input type="text" name="phone"/></td>
    </tr>
    <tr>
        <td><label for="pass">Code</label></td>
        <td><input type="text" name="pass"/></td>
    </tr>
    <tr>
        <td></td>
        <td><input name="login" type="submit" value="Login"/></td>
    </tr>
</TABLE>
</form>
<?php }
else{
    /*show current demo ad*/
    $user_demo=get_demo_user_ad($_SESSION['vcn_extension']['demo']['id']);
    
    if(!isset($_SESSION['vcn_extension']['step'])) $_SESSION['vcn_extension']['step']=1; //step
//form submit
if(isset($_POST['submit'])){
    /*filter keywords*/
    if(isset($_POST['keywords'])){
        $_SESSION['vcn_extension']['demo']['data']['data_keywords']=$data_keywords=check_exists_keywords($_POST['keywords'],$_SESSION['vcn_extension']['demo']['id']);
        $_SESSION['vcn_extension']['ad_data']['keywords']=array();   //save keywords
        //check condition
        if(count($data_keywords['unmatches'])){
            $_SESSION['vcn_extension']['step']=2;   //next step
        }
        else $logs[]='Từ khoá này đã trùng với người trước. Chọn từ khoá khác ?';
    }
    /*advertising config*/
    if(isset($_POST['title'])){
        $keywords=join(',',$_SESSION['vcn_extension']['demo']['data']['data_keywords']['unmatches']);
        //ads content
        $ads=item_search_result(array(
            'title'=>$_POST['title'],
            'thumbnail'=>$_POST['thumbnail'],
            'url'=>$_POST['url'],
            'short_desc'=>$_POST['short_desc']
            ));
        mysql_update("crx_ads",array(
            "keywords"=>$keywords,"host"=>'google',"pattern"=>'/google.com.+/g',"get_ad_method"=>'get_google_ad',"place_ad_method"=>'place_google_ad',"ad_content"=>$ads
            ),
            'user="'.mysql_real_escape_string($_SESSION['vcn_extension']['demo']['id']).'"'
        );
        $_SESSION['vcn_extension']['step']=3;   //final step
        
    }
}
    ?>

<a href="?logout" rel='nofollow'>Logout</a> (Để sửa lại hoặc thêm mới quảng cáo.)<br/>
<!-- display -->
<img  src="http://kvliveblog.files.wordpress.com/2013/02/make-google-default-search-engine-icon.png" width="200"/>
<!-- current ad -->
<?php if($user_demo){?>
<div class="aroundbox">
Quảng cáo của bạn, hiện tại với từ khoá:
<a target="_blank" href="http://www.google.com/search?as_q=<?php echo $user_demo['keywords']?>"><strong><?php echo $user_demo['keywords'];?></strong></a>
</div>
<h2>Sửa quảng cáo</h2>
<?php }else echo '<h2>Thêm quảng cáo mới</h2>'?>
<?php
    //show logs
    show_logs();
?>
<!-- form -->
<form method="post" name="frm_keywords" action="demo.php">
<table>
<?php
    if($_SESSION['vcn_extension']['step']==1):
?>
    <tr>
        <td valign="top">Từ khoá</td>
        <td><textarea name="keywords" rows="3" cols="40"></textarea>
        <p>Mỗi từ khoá cách nhau dấu phẩy (,) từ khoá nào chưa có sẽ được dùng để hiển thị quảng cáo của bạn.</p>
        </td>
    </tr>
<?php
    endif;
    if($_SESSION['vcn_extension']['step']==2):
?>
    <tr>
        <td colspan="2">
        <strong>Bạn có thể dùng được những từ khoá sau để thử nghiệm:</strong>
        <ul>
        <?php
    if(isset($_SESSION['vcn_extension']['demo']['data']['data_keywords']))
    foreach($_SESSION['vcn_extension']['demo']['data']['data_keywords']['unmatches'] as $keyword){
        echo '<li>'.$keyword.'</li>';
    }
?>
    </ul><br/>
    <img src="images/search-result.jpg"/>
        </td>
    </tr>
    <tr>
        <td>Thumbnail URL</td>
        <td><input name="thumbnail"/></td>
    </tr>
    <tr>
        <td>Tiêu đề</td>
        <td><input name="title" size="50"/></td>
    </tr>
    <tr>
        <td>URL</td>
        <td><input name="url" size=""/></td>
    </tr>
    <tr>
        <td>Mô tả</td>
        <td><textarea name="short_desc" rows="5" cols="50"></textarea></td>
    </tr>
<?php
    endif;
    if($_SESSION['vcn_extension']['step']== 2 || $_SESSION['vcn_extension']['step']==1):
?>
    <tr>
        <td></td>
        <td><input type="submit" name="submit" value="<?php echo ($_SESSION['vcn_extension']['step']!=2)? 'Next >':'Save'?>"/><input type="reset" Value="Làm lại"/>
        </td>
    </tr>
<?php
    endif;
    if($_SESSION['vcn_extension']['step']==3):
?>    
<div>
    Chúc mừng ! bạn đã cài đặt quảng cáo thành công ! <br/> Để hoàn tất vui lòng làm theo các bước:<br/>
    <b>Bước 1:</b>
    <p>Để hiển thị được quảng cáo bạn cần tải bộ cài đặt + tài liệu hướng dẫn, tại địa chỉ:<br/>
    <a target="_blank" href="http://www.fshare.vn/file/6V98IH1SK9/">http://www.fshare.vn/file/6V98IH1SK9/</a><br/>
    Sau khi download xong, chạy ứng dụng: chọn folder lưu (Save to Folder) rồi nhấn nút Download, chờ đợi vài phút để cài đặt.
    </p>
    <b>Bước 2:</b>
    <p>Ngay sau khi cài xong sẽ tự động mở trình duyệt chrome, bạn truy cập vào: <a href="http://www.google.com">Google</a> và nhập từ khoá của mình để xem kết quả.
    </p>
    <i>Chúc bạn thành công !</i>
</div>
<?php
    endif;
?>
</table>
</form>
<?php
}
?>
    </div>
    <div style="display: table-cell;vertical-align:top;">
    <div class="box"><a href="http://hoangweb.com"><img src="http://chovaytiennhanh.files.wordpress.com/2013/02/thiet-ke-web-gia-re.png" alt="Nhận thiết kế web giá rẻ"/></a></div>
    <div class="box"><div class="title">Hỗ trợ</div>
    <a href = 'ymsgr:sendim?quachhoang_2005'><img src='http://opi.yahoo.com/online?u=quachhoang_2005&m=g&t=2' border=0></a>
    </div>
    </div>
</div>
<!-- footer -->
<div class="footer">
Liên hệ: quachhoang_2005@yahoo.com - 0944.049.910 - 01663.930.250<br/>
<!-- Histats.com  START  (standard)-->
<script type="text/javascript">document.write(unescape("%3Cscript src=%27http://s10.histats.com/js15_gif.js%27 type=%27text/javascript%27%3E%3C/script%3E"));</script>
<a href="http://www.histats.com" target="_blank" title="free site statistics" ><script  type="text/javascript" >
try {Histats.startgif(1,2554477,4,10042,"");
Histats.track_hits();} catch(err){};
</script></a>
<noscript><a href="http://www.histats.com" alt="free site statistics" target="_blank" ><div id="histatsC"><img border="0" src="http://s4is.histats.com/stats/i/2554477.gif?2554477&103"></div></a>
</noscript>
<!-- Histats.com  END  -->
</div>
</body>
</html>