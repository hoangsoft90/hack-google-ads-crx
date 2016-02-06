<?php
global $conn;
global $logs;
$logs=array();

include('config.php');
connectDb();
//connect to mysql
function connectDb(){
    global $conn;
    $conn=mysql_connect(DB_HOST,DB_USER,DB_PASS) or die("can't connect to mysql=>".mysql_error());
    mysql_selectdb(DB_NAME,$conn);
    mysql_query("SET NAMES utf8",$conn);    //set utf8
}
/**
* get mysql table data
* 
* @param mixed $sql
* @return array
*/
function select_sql($sql){
    global $conn,$logs;
    $data=array();
    $result=mysql_query($sql,$conn);
    if($result && mysql_num_rows($result)){
        while ($row = mysql_fetch_assoc($result)) {
            $data[]= $row;
        }
        //return $data;
    }
    if(!$result) echo mysql_error();
    else mysql_free_result($result); //free data
    return $data;
}
/**
* insert sql
* 
* @param mixed $table
* @param mixed $toAdd
*/
function mysql_insert($table, $toAdd){
    global $conn;
   $fields = implode(array_keys($toAdd), ',');
    $values = "'".implode(array_values($toAdd), "','")."'"; # better

   $q = 'INSERT INTO '.$table.' ('.$fields.') VALUES ('.$values.')';
   
   $res = mysql_query($q,$conn)OR die(mysql_error());

   return mysql_insert_id();
   
   //-- Example of usage
   //$tToAdd = array('id'=>3, 'name'=>'Yo', 'salary' => 5000);
   //insertIntoDB('myTable', $tToAdd)
}
/**
* update sql
* 
* @param mixed $table
* @param mixed $update
* @param mixed $where
*/
function mysql_update($table, $update, $where){
    global $conn;
    $fields = array_keys($update);
    $values = array_values($update);
     $i=0;
     $query="UPDATE ".$table." SET ";
     while(isset($fields[$i])){
       if($i<0){$query.=", ";}
       $query.=$fields[$i]." = '".$values[$i]."',";
       $i++;
     }
     $query=rtrim($query,',');
     $query.=" WHERE ".$where." LIMIT 1;";
     return mysql_query($query,$conn) or die(mysql_error());
     //return true;
     //Example
     // mysql_update('myTable', $anarray, "type = 'main'")
}
/**
* build attributes
* 
* @param mixed $attr
*/
function build_attr($attrs=array()){
    $p='';
    foreach($attrs as $k=>$v){
        $p.=$k.'="'.$v.'" ';
    }
    return trim($p);
}
/**
* list avaiables hosts
* 
* @param mixed $atts
* @param mixed $selected
*/
function list_avaibles_hosts_combox($atts=array(),$selected=0){
    $ui='<select '.build_attr($atts).'>';
    $ui.='<option value="">--------Select--------</option>';
    $rs=select_sql("select distinct host from crx_ads");
    if(is_array($rs))
    foreach($rs as $row){
        $focus=($row['host']==$selected)? 'selected="selected"':'';     //focus item
        $ui.='<option '.$focus.' value="'.$row['host'].'">'.$row['host'].'</option>';
    }
    $ui.='</select>';
    return $ui;
}
/**
* show logs data
* 
* @param mixed $logs
*/
function show_logs(){
    global $logs;
    if(is_array($logs) && count($logs)){
    echo '<ul class="logs message">';
    foreach($logs as $l){
        echo '<li>'.$l.'</li>';
    }
    echo '</ul>';
    }
}
/**
* filter unicode str
* 
* @param string $str
* @return new string
*/
function vn_str_filter ($str){

   $unicode = array(

       'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',

       'd'=>'đ',

       'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',

       'i'=>'í|ì|ỉ|ĩ|ị',

       'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',

       'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',

       'y'=>'ý|ỳ|ỷ|ỹ|ỵ',

       'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',

       'D'=>'Đ',

       'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',

       'I'=>'Í|Ì|Ỉ|Ĩ|Ị',

       'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',

       'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',

       'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',

   );

  foreach($unicode as $nonUnicode=>$uni){

       $str = preg_replace("/($uni)/i", $nonUnicode, $str);

  }
   return $str;
}
/**
* limit string
* 
* @param mixed $str
* @param mixed $len
*/
function limit_str($str,$len=100){
    if(strlen($str)>=$len) $str=mb_substr(($str),0,$len-3,'UTF-8').'...';
    return htmlspecialchars($str);  //make textplain
}
/**
* check match condition 2 keywords
* 
* @param mixed $key: main keywords
* @param mixed $check match keywords
*/
function match_keywords($key,$check){
    $key=vn_str_filter(strtolower($key));
    $keys=explode(',',$key);
    
    $check=vn_str_filter(strtolower($check));
    $checks=preg_split('#[\s]+#',$check);
    
    foreach($keys as $key_){
        $current_pos=-1;   //current position substring found
        $count_order=0;     //reset
        foreach($checks as $t){
            $pos=array_search($t,preg_split('#[\s]+#',$key_));//print_r(preg_split('#[\s]+#',$key_));
            if($pos!==false && $pos>$current_pos ) $count_order++;       //count substring that found in main keyword with ASC condition
            $current_pos=$pos;  //update last pos
        }
        //echo '['.$count_order.']';
        //condition result
        if($count_order/count($checks)>=2/3) return true;
    }
    return false;
}
/**
* check exists keywords
* 
* @param mixed $keywords
* @param mixed $user: exclude user
* @return mixed
*/
function check_exists_keywords($keywords,$user=0,$search_site='google'){
    global $conn;
    $sql='select * from crx_ads where host="'.mysql_real_escape_string($search_site).'"';
    if($user) $sql.=' and user!="'.$user.'"';
    
    $data=select_sql($sql);
    $matches=array();
    $unmatches=array();
    $keywords_split=explode(',',$keywords);
    foreach($data as $row){
        //echo 'keyword:'.$row['keywords'].',cond:'.match_keywords($row['keywords'],$query).'<br/>';
        foreach($keywords_split as $query ){
            if(isset($result[$query])) continue;
            //echo $row['keywords'].'<>'.$query.'<br/>';
            if($row['keywords'] && match_keywords($row['keywords'],$query)){
                $matches[$query]=1;
            }
        }
    }
    $matches=array_keys($matches);
    foreach($keywords_split as $k){
        if(!in_array($k,$matches)) $unmatches[]=$k;
    }
    return array('matches'=>$matches,'unmatches'=>$unmatches);
}
/**
* get ad content
* 
* @param mixed $query
* @return mixed
*/
function get_ads($postid,$search_site='',$query=''){
    //specify site for ad
    if($search_site!=''){
        $sql='select * from crx_ads where host="'.mysql_real_escape_string($search_site).'"';
        $data=select_sql($sql);
        foreach($data as $row){
            //echo 'keyword:'.$row['keywords'].',cond:'.match_keywords($row['keywords'],$query).'<br/>';
            if($row['keywords'] && match_keywords($row['keywords'],$query)){//echo '=>'.$row['ad_content'];
                return $row['ad_content'];
            }
        }
    }
    else {  //get specific ad
        $data=select_sql('select * from crx_ads where id="'.mysql_real_escape_string($postid).'"');
        if(count($data)==1) return ($data[0]['ad_content']);
    }
}
/**
* list common fields for host
* 
* @param mixed $host
*/
function show_commonData4host($host){
    
    //ads methods
    $data=select_sql('select pattern,get_ad_method,place_ad_method from crx_ads where (get_ad_method!="" OR place_ad_method!="") AND host="'.mysql_real_escape_string($host).'"');
    $result=array(
        'get_methods'=>array(),
        'place_methods'=>array(),
        'patterns'=>array()
        );
    foreach($data as $r){
        if($r['get_ad_method']) $result['get_methods'][$r['get_ad_method']]=1;
        if($r['place_ad_method']) $result['place_methods'][$r['place_ad_method']]=1;
        if($r['pattern']) $result['patterns'][$r['pattern']]=1;
    }
    $result['get_methods']=array_flip($result['get_methods']);
    $result['place_methods']=array_flip($result['place_methods']);
    $result['patterns']=array_flip($result['patterns']);
    //var_dump($result);
    return (object)$result;
}
/**
* logout admin
* 
*/
function logout_admin(){
    if(isset($_SESSION['vcn_extension'])) unset($_SESSION['vcn_extension']);
}
//logout demo
function logout_demo(){
    if(isset($_SESSION['vcn_extension'])) unset($_SESSION['vcn_extension']);
}
/**
* login demo
*/
function login_demo($email){
    global $conn;
    $sql='select * from crx_demo_users where email="'.mysql_real_escape_string($email).'"';
    
    $rows=select_sql($sql,$conn);
    if(count($rows)){
        return $rows[0];
    }
}
function login_demo1($email,$code){
    global $conn;
    $sql='select * from crx_demo_users where email="'.mysql_real_escape_string($email).'"';
    $sql.=' and activation_code="'.$code.'"';
    
    $rows=select_sql($sql,$conn);
    if(count($rows)){
        return $rows[0];
    }
}
/**
* login with phone & pass
* 
* @param mixed $phone
* @param mixed $pass
* @return mixed
*/
function login_demo2($phone,$pass){
    $sql='select * from crx_demo_users where phone="'.mysql_real_escape_string($phone).'" and activation_code="'.$pass.'"';
    $rows=select_sql($sql);
    if(count($rows)){
        return $rows[0];
    }
}
/**
* verify account
* 
* @param mixed $email
* @param mixed $code
*/
function verify_account($email,$code,$data=array()){
    global $conn;
    $rs=mysql_query('select * from crx_demo_users where email="'.mysql_real_escape_string($email).'" and activation_code="'.mysql_real_escape_string($code).'"',$conn);
    if(mysql_num_rows($rs)){
        $update=array('verify'=>'1');
        if(is_array($data) && count($data)){
            $update=array_merge($update,$data);
        } 
        mysql_update('crx_demo_users',$update,'email="'.mysql_real_escape_string($email).'"');
        return true;
    }
    return false;
}
/**
* get current demo advertising
* 
* @param mixed $user
*/
function get_demo_user_ad($user){
    //global $conn;
    $rows=select_sql('select * from crx_ads where user="'.mysql_real_escape_string($user).'"');
    if(count($rows)) return $rows[0];
}
/**
* get users ad data
* 
*/
function get_users_demo(){
    $sql='select crx_demo_users.*,crx_ads.keywords from crx_demo_users inner join crx_ads on crx_demo_users.id=crx_ads.user';
    $data=select_sql($sql);
    return $data;
}
/**
* delete user demo
* 
* @param mixed $user
*/
function del_user_demo($user){
    global $conn;
    if($conn){
        mysql_query('delete from crx_demo_users where id="'.mysql_real_escape_string($user).'"',$conn);
        mysql_query('delete from crx_ads where user="'.$user.'"',$conn);
    } 
}
/**
* send email
* 
* @param mixed $to
* @param mixed $subject
* @param mixed $body
*/
function send_mail($email,$subject,$body)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        require 'libs/PHPMailer-master/PHPMailerAutoload.php';
        $mail = new PHPMailer();

        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->SMTPDebug = 2;
        //$mail->Debugoutput = 'html';
        
        $mail->Host = 'smtpcorp.com';  // Specify main and backup server
        $mail->Port=2525;
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'huyhoang08';                            // SMTP username
        $mail->Password = '837939';                           // SMTP password
        $mail->SMTPSecure = 'tls';//'tls';                            // Enable encryption, 'ssl' also accepted
        $mail->Priority = 1;
        //set from
        //$mail->setFrom('nhokngoc90@gmail.com','MrHoang');
        $mail->From = "example@mail.com"; 
        $mail->FromName = "MrHoang"; 
        $mail->addReplyTo('example@mail.com', 'MrHoang');

        $mail->addAddress($email);               // Name is optional
        
        //$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail -> CharSet = "UTF-8"; 
        $mail->Subject = $subject;
        $mail->Body    = $body;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            echo 'Lỗi server mail, chúng tôi chưa gửi được mã code cho bạn vào email, liên hệ: 01663.930.250';
            $mail->SmtpClose();
        }
        else{
            $mail->SmtpClose();
             return true;
        }
    }
    else return 'invalid_email';
}
function send_mail1($email,$subject,$body){
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,'http://api.postmarkapp.com/email');
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    $headers=array(
        'Accept: application/json',
        'Content-Type: application/json',
        'X-Postmark-Server-Token: f6bbec2e-4cbe-46ef-b41f-9cc44b88829e'
    );
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
    curl_setopt($ch,CURLOPT_POST,1);
    $post=array(
        'From'=>'hoangbaby@facebook.com',
        'To'=>$email,
        'Subject'=>'Postmark test',
        'HtmlBody'=>'<html><body><strong>Hello</strong> dear Postmark user.</body></html>'
    );
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($post));
    echo curl_exec($ch);
    echo curl_error($ch);
    return true;
}
/**
* get current page url
* 
*/
function get_current_url($skip_uri=true) {

    $pageURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } 
    else 
    {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $skip_uri? $pageURL: strtok($pageURL,'?');
}
//echo strtok($_SERVER["REQUEST_URI"],'?');
/**
* javascript obfuscator
* 
* @param mixed $js_code
*/
function js_obfuscator_with_php($js_code){
    /*ob_start();
    echo $js_code;
    $generatedoutput = ob_get_contents();
    ob_end_clean();*/
    $generatedoutput=$js_code;
    $generatedoutput = str_replace("\\\r\n", "\\n", $generatedoutput);
    $generatedoutput = str_replace("\\\n", "\\n", $generatedoutput);
    $generatedoutput = str_replace("\\\r", "\\n", $generatedoutput);
    $generatedoutput = str_replace("}\r\n", "};\r\n", $generatedoutput);
    $generatedoutput = str_replace("}\n", "};\n", $generatedoutput);
    $generatedoutput = str_replace("}\r", "};\r", $generatedoutput);
    require('libs/packer.php-1.1/class.JavaScriptPacker.php');
    $myPacker = new JavaScriptPacker($generatedoutput, 62, true, false);
    $packed = $myPacker->pack();
    return ($packed);
}
/**
* curl load content
* @param string $Url: url string
*/
function curl($url){
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    if(!curl_error($ch)) return curl_exec($ch);
}
/**
* random string
* @return return a random string
*/
function RandomString($length) {
    $key='';
    //$keys = array_merge(range(0,9), range('a', 'z'));
    $keys = array_merge(range('A','Z'), range('a', 'z'));

    for($i=0; $i < $length; $i++) {

        $key .= $keys[array_rand($keys)];

    }

    return $key;

}
/**
* show error
* 
* @param mixed $code
* @param mixed $content
* @param mixed $title
*/
function show404($content,$title='404 Not Found'){
    header('HTTP/1.0 404 Not Found');
    echo "<h1>{$title}</h1>";
    echo $content;
    exit();    
}
/**
* get request
* 
* @param mixed $url
* @param mixed $headers
* @return mixed
*/
function get_request($url, $headers=array()){
    // parse the given URL
    $url = parse_url($url);
 
    if ($url['scheme'] != 'http') { 
        die('Error: Only HTTP request are supported !');
    }
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
 
    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 30);
 
    if ($fp){
        // send the request headers:
        fputs($fp, "GET $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
 
        //if ($referer != '')
          //  fputs($fp, "Referer: $referer\r\n");
        if(count($headers)){
            foreach($headers as $header){
                fputs($fp, $header."\r\n");
            }
        }
        fputs($fp, "Connection: close\r\n\r\n");
 
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
    }
    else { 
        return array(
            'status' => 'err', 
            'error' => "$errstr ($errno)"
        );
    }
    // close the socket connection:
    fclose($fp);
 
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
 
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
 
    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}
/**
* post request URL
* 
* @param mixed $url
* @param string $data
* @param mixed $headers
* @return mixed
*/
function post_request($url, $data, $headers=array()) {
 
    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);
 
    // parse the given URL
    $url = parse_url($url);
 
    if ($url['scheme'] != 'http') { 
        die('Error: Only HTTP request are supported !');
    }
 
    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];
 
    // open a socket connection on port 80 - timeout: 30 sec
    $fp = fsockopen($host, 80, $errno, $errstr, 30);
 
    if ($fp){
 
        // send the request headers:
        fputs($fp, "POST $path HTTP/1.1\r\n");
        fputs($fp, "Host: $host\r\n");
 
        //if ($referer != '')
          //  fputs($fp, "Referer: $referer\r\n");
        if(count($headers)){
            foreach($headers as $header){
                fputs($fp, $header."\r\n");
            }
        }
        fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($fp, "Content-length: ". strlen($data) ."\r\n");
        fputs($fp, "Connection: close\r\n\r\n");
        fputs($fp, $data);
 
        $result = ''; 
        while(!feof($fp)) {
            // receive the results of the request
            $result .= fgets($fp, 128);
        }
    }
    else { 
        return array(
            'status' => 'err', 
            'error' => "$errstr ($errno)"
        );
    }
 
    // close the socket connection:
    fclose($fp);
 
    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);
 
    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';
 
    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
}
//include template
include('templates.php');
?>