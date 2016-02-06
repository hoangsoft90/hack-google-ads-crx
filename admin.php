<?php
session_start();
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Chrome extension advertising</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
<body>
<div id="wrapper">
    <div id="header"><h2>Chrome extension advertising manager</h2></div>
    <div id="body">
<?php
include('functions.php');
//logout admin
if(isset($_REQUEST['logout'])){
    logout_admin();
}

//submit login form
if(isset($_POST['login'])){
    if($_POST['pass']=='837939'){
        $_SESSION['vcn_extension']['user']='admin';
    }
}
//switch interface
if(!isset($_SESSION['vcn_extension']) || !isset($_SESSION['vcn_extension']['user'])){
?>
<center>
<h2>Login</h2>
<form method="post" action="<?php echo get_current_url(false)?>">
    <label>Pass</label><input type="text" name="pass"/>
    <input type="submit" name="login" value="Login"/>
</form></center>
<?php
}else{
    ?>
    <div class="topbar"><a href="?logout">Logout</a></div>
    <?php
$logs=array();
//post form
if(isset($_POST['submit'])){
    /*remove some GET params ->don't need because form worked without uri
    //if(isset($_GET['edit'])) unset($_GET['edit']);
    if(isset($_GET['del'])) unset($_GET['del']);
    */
    //#params
    $keywords=$_POST['keywords'];
    //get host
    $host=$_POST['host'];
    if($_POST['new_host']) $host=$_POST['new_host'];
    
    $pattern=$_POST['pattern']; //pattern
    $get_ad_method=$_POST['get_ad_method']; //get ad method
    $place_ad_method=$_POST['place_ad_method'];     //place ad method
    
    //ad content
    $ads=$_POST['content'];
    
    
    //update ad
    if($_POST['edit']){
        //$sql='update crx_ads set keywords="'.mysql_real_escape_string($keywords).'",host="'.mysql_real_escape_string($host).'", pattern="'.mysql_real_escape_string($pattern).'",get_ad_method="'.mysql_real_escape_string($get_ad_method).'",place_ad_method="'.mysql_real_escape_string($place_ad_method).'", ad_content="'.mysql_real_escape_string($ads).'" where id="'.mysql_real_escape_string($_POST['edit']).'"';
        //$logs[]=htmlspecialchars($sql);
        //$rs=mysql_query($sql,$conn);
        $rs=mysql_update("crx_ads",array(
            "keywords"=>$keywords,"host"=>$host,"pattern"=>$pattern,"get_ad_method"=>$get_ad_method,
            "place_ad_method"=>$place_ad_method,"ad_content"=>$ads
        ),'id="'.mysql_real_escape_string($_POST['edit']).'"');
        if($rs) $logs[]='updated advertising with ID='.$_POST['edit'];
        else $logs[]=mysql_error();
    }
    //add new ad
    else{
        //$sql='insert into crx_ads set keywords="'.mysql_real_escape_string($keywords).'",host="'.mysql_real_escape_string($host).'", pattern="'.mysql_real_escape_string($pattern).'",get_ad_method="'.mysql_real_escape_string($get_ad_method).'",place_ad_method="'.mysql_real_escape_string($place_ad_method).'", ad_content="'.mysql_real_escape_string($ads).'"';
        //$rs=mysql_query($sql,$conn);
        $rs=mysql_insert("crx_ads",array(
            "keywords"=>$keywords,"host"=>$host,"pattern"=>$pattern,"get_ad_method"=>$get_ad_method,
            "place_ad_method"=>$place_ad_method,"ad_content"=>$ads
        ));
        
        if($rs) $logs[]='Add new advertising successful.';else $logs[]= mysql_error();
    }
}
//delete ad
if(isset($_GET['del']) ){
    $rs=mysql_query('delete from crx_ads where id="'.mysql_real_escape_string($_GET['del']).'"');
    if($rs) $logs[]='delete ad with ID='.$_GET['del'];else $logs[]=mysql_error();
}
//get ad
if(isset($_GET['edit']) && is_numeric($_GET['edit'])){
    $ad_data=select_sql('select * from crx_ads where id="'.mysql_real_escape_string($_GET['edit']).'"');
    //echo '<textarea>';print_r($ad_data);echo '</textarea>';
    if(count($ad_data)==1) $ad_data=$ad_data[0];
}
//show logs
show_logs($logs);
?>

<!-- form -->
<form method="post" accept-charset="UTF-8" action="<?php echo get_current_url(false)?>">
<input type="hidden" name="edit" value="<?php echo (isset($ad_data) && isset($ad_data['id']))? $ad_data['id']:''?>"/>
<table class="table-css">
    <tr>
        <td></td>
        <td><label><input type="radio" name="opt" <?php echo !isset($ad_data)?'checked="checked"':''?> value="add"/> Add new</label>
            <label><input type="radio" name="opt" <?php echo isset($ad_data)? 'checked="checked"':''?> value="update"/> Update</label>
        </td>
    </tr>
    <tr>
        <td>Host</td>
        <td><?php echo list_avaibles_hosts_combox(array('name'=>'host'),(isset($ad_data) && isset($ad_data['host']))? $ad_data['host']:'');?>
            <input type="text" name="new_host" value=""/>
            (select one of them.)
        </td>
    </tr>
    <tr bgcolor="#dadada">
        <td>Pattern</td>
        <td><input type="text" name="pattern" value="<?php echo (isset($ad_data) && isset($ad_data['pattern']))? $ad_data['pattern']:''?>"/>
        <?php
        if(isset($ad_data) && isset($ad_data['host']) && $ad_data['host']){
            $hostdata=show_commonData4host($ad_data['host']);
            echo '<i>Suggest Pattern</i>: <code>'.join(',',$hostdata->patterns).'</code>';
        }
?>
        </td>
    </tr>
    <tr bgcolor="#dadada">
        <td>Code</td>
        <td>
            <div style="display: inline-block;"><label>Get Ad</label><br/><textarea name="get_ad_method"><?php echo (isset($ad_data) && isset($ad_data['get_ad_method']))? $ad_data['get_ad_method']:''?></textarea></div>
            <div style="display: inline-block;"><label>Place Ad</label><br/><textarea name="place_ad_method"><?php echo (isset($ad_data) && isset($ad_data['place_ad_method']))? $ad_data['place_ad_method']:''?></textarea></div>
            <?php 
        if(isset($ad_data) && isset($ad_data['host']) && $ad_data['host']){
            
            echo '<br/><i>Suggest</i>:<br/> <strong>Get ad Method</strong>: <code>'.join(',',$hostdata->get_methods).'</code>, <strong>Place ad method</strong>: <code>'.join(',',$hostdata->place_methods).'</code>';
        } 
        
        ?>
        <b>Note:</b>
        <ul>
            <li>pattern & code must be distingyst, if detect same pattern prev, will be ignore that rule.</li>
        </ul>
        </td>
    </tr>
    <tr>
        <td>Keywords</td>
        <td><textarea name="keywords" cols="40" rows="5"><?php echo (isset($ad_data) && isset($ad_data['keywords']))? $ad_data['keywords']:''?></textarea><br/>(each keyword separated by comma.)</td>
    </tr>
    <tr>
        <td>Ad Content</td>
        <td>
        <!-- no ckeditor -->
        <textarea name="content" cols="80" rows="14"><?php echo (isset($ad_data) && isset($ad_data['ad_content']))? ($ad_data['ad_content']):''?></textarea>
        <div>
            <ul>
                <li><code>{{q}}</code>: search keywords.</li>
            </ul>
        </div>
        </td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" name="submit" value="<?php echo isset($ad_data)? 'Update':'Add'?>"/></td>
    </tr>
</table>
</form>
<hr/>
<!-- list ads -->
<h2>List Advertisings</h2>
<?php
$data=select_sql('select * from crx_ads');
?>
<table width="100%" border="1" class="table-css-1">
    <tr style="background: #808080;color:#fff">
        <td>ID</td>
        <td>Host</td>
        <td>Pattern</td>
        <td>Keywords</td>
        <td>Get Ad</td>
        <td>Place Ad</td>
        <td>Content</td>
        <td>E</td>
        <td>D</td>
    </tr>
<?php
foreach($data as $row){
?>
    <tr>
        <td><?php echo $row['id']?></td>
        <td><code><?php echo $row['host']?></code></td>
        <td style="color:#FF00FF"><code><?php echo $row['pattern'] ?></code></td>
        <td><?php echo limit_str($row['keywords'])?></td>
        <td><code><?php echo $row['get_ad_method']?></code></td>
        <td><code><?php echo $row['place_ad_method']?></code></td>
        <td><?php echo limit_str($row['ad_content'],300)?></td>
        <td><a href="?edit=<?php echo $row['id']?>">Edit</a></td>
        <td><a href="?del=<?php echo $row['id']?>" onclick="if(!confirm('Are your sure?'))return false;">Delete</a></td>
    </tr>
<?php
}
?>
</table>
    </div>
    <?php
}
?>
    <div id="footer">Copyright @vaycanhan.com - quachhoang_2005@yahoo.com</div>
    
</div>
</body>
</html>