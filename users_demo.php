<html>
<head>
    <title>Users demo</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
include('functions.php');
//delete user demo
if(isset($_GET['del'])){
    del_user_demo($_GET['del']);
}
$data=get_users_demo();
?>
<h2>Danh sách thành viên demo quảng cáo</h2>
<table border="1">
    <tr class="table-header">
        <td>ID</td>
        <td>Họ & tên</td>
        <td>Phone</td>
        <td>email</td>
        <td>Code</td>
        <td>Verify</td>
        <td>keywords</td>
        <td>Info</td>
        <td>X</td>
    </tr>
<?php
//list users ad
foreach($data as $row):
?>
<tr>
    <td><?php echo $row['id']?></td>
    <td><?php echo $row['fullname']?></td>
    <td><?php echo $row['phone']?></td>
    <td><?php echo $row['email']?></td>
    <td><?php echo $row['activation_code']?></td>
    <td><img src="images/<?php echo $row['verify']?'check.png':'uncheck.png';?>"/></td>
    <td><?php 
        $ui='';
        foreach(explode(',',$row['keywords']) as $key){
            $ui.='<a target="_blank" href="http://www.google.com/search?as_q='.trim($key).'">'.trim($key).'</a>, ';
        }
        echo rtrim($ui,', ');
    ?>
    </td>
    <td><?php echo $row['ref_pc']?></td>
    <td><a href="?del=<?php echo $row['id']?>" onclick="if(confirm('Are you sure?')) return true;else return false;">Delete</a></td>
</tr>
<?php
    endforeach;
?>
</table>
</body>
</html>