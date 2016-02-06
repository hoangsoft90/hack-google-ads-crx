<html>
<head>
    <title>Verify Account</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
include('functions.php');
/*check exists*/
if(!isset($_GET['email']) || !login_demo($_GET['email'])){
    $msg='Không tìm thấy trang này, liên hệ: 01663.930.250<br/>';
    $msg.='Nhấn vào đây để tạo tài khoản demo.<a href="demo.php">Create Account</a>';
    show404($msg);
}


//verify
if(isset($_POST['submit'])){
    $update=array(
        'phone'=>$_POST['phone'],
        'fullname'=>$_POST['fullname']
    );
    $r=verify_account($_POST['email'],$_POST['activation_code'],$update);
    if($r) $logs[]='Tài khoản của bạn đã được xác thực thành công ! Nhấn vào đây để đăng nhập: <a href="demo.php">Đăng nhập</a>';
    else $logs[]='Sai code, tài khoản của bạn chưa được xác thực. Nhấn <a href="demo.php">vào đây</a> để gửi lại code.';
    
}
//show logs
show_logs();
?>
<!-- activation form -->
<h2>Activation Code</h2>
<form method="post">
<input type="hidden" name="email" value="<?php echo isset($_GET['email'])? $_GET['email']:'';?>"/>
<table>
    <tr>
        <td><label for="fullname">Họ & tên</label></td>
        <td><input type="text" name="fullname"/></td>
    </tr>
    <tr>
        <td><label for="phone">Phone</label></td>
        <td><input type="text" name="phone"/> (Điền Mobile Phone của bạn để chúng tôi hỗ trợ.)</td>
    </tr>
    <tr>
        <td><label for="activation_code">Code</label></td>
        <td><input type="text" name="activation_code"/></td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" name="submit" value="Verify"/></td>
    </tr>
</table>
</form>
</body>
</html>