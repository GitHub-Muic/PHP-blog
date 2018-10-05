<?php
include('../config.php');
session_start();
if(empty($_SESSION['session_state']))
{
    header('Location:/admin/login.php');
}
    $connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $query=mysqli_query($connection,"SELECT * FROM `users` WHERE email='{$_SESSION['session_state']}'");
    $row=mysqli_fetch_assoc($query);
    $id=$row['id'];
    if($_SERVER['REQUEST_METHOD']==='POST'){
        setting($id);     
    }

function setting($x){
    
    global $error_message;
    if(empty($_POST['username'])){
        $error_message="用户名不能为空!";
        return;
    }
    if(empty($_POST['password'])){
        $error_message="密码不能为空!";
        return;
    }
    if(empty($_POST['confirm'])){
        $error_message="确认密码不能为空!";
        return;
    }
    if($_POST['confirm']!=$_POST['password']){
        $error_message="请输入两次一样的密码!";
        return;
    }
    if(empty($_FILES['avatar'])){
        $error_message="图片未成功上传";
        return;
    }
    $avatar=$_FILES['avatar'];
    if($avatar['error']!=UPLOAD_ERR_OK){
        $error_message="图片上传错误!";
        return;
    }
    if(strpos($avatar['type'],'image')!==0){
        $error_message="不是图片!";
        return;
    }
    if($avatar['size']>1*1024*1024)
    {
        $error_message="图片太大";
        return;
    }
    $username=$_POST['username'];
    $password=$_POST['password'];
    $target='../static/assets/img/'.$username.'.'.pathinfo($avatar['name'],PATHINFO_EXTENSION);
    if(!move_uploaded_file($avatar['tmp_name'],$target)){
        $error_message="保存图片失败";
        return;
    }
    $img='..'.substr($target,2);    
    $connect=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $query=mysqli_query($connect,"SELECT name FROM `users` WHERE name='{$username}' ");
    $row=mysqli_fetch_row($query);
    if($row){
        $error_message="用户已被注册!";
        return ;
    }
    session_start();
    $query=mysqli_query($connect,"UPDATE users SET name='{$username}' WHERE id='{$x}'");
    $query=mysqli_query($connect,"UPDATE users SET password='{$password}' WHERE id='{$x}'");
    $query=mysqli_query($connect,"UPDATE users SET avatar='{$img}' WHERE id='{$x}'");
    $query=mysqli_query($connect,"UPDATE post SET user='{$username}' WHERE user='{}'");
    // $query=mysqli_query($connect,"INSERT INTO  user_table(user_name,user_password,user_age,user_sex,user_email,user_tel,user_img) VALUE('{$username}','{$password}',$age,$sex,'{$email}',$telephone,'{$img}')"); 
    header("Location:index.php");  

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>首页</title>
    <link rel="stylesheet" href="../static/assets/venders/bootstrap-4.0.0/dist/css/bootstrap.css">
</head>

<body>
    <div class="nav ">
        <div class="avatar">
            <a class="nav-link active" href="#"><img src="<?php echo $row['avatar']?>" alt="" width="50px" height="50px"
                    class="img-circle"></a>
        </div>
        <ul class="nav flex-justify-content-center my-3">
            <li class="nav-item mx-0 ">
                <a class="nav-link active" href="/admin/index.php"><?php echo $row['name']?></a>
                <img src="" alt="">
            </li>
            <li class="nav-item mx-0 ">
                <a class="nav-link active" href="/admin/myblog.php">我的文章</a>
                <img src="" alt="">
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/writeblog.php">写博客</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/setting.php">编辑资料</a>
            </li>
        </ul>
    </div>
    <h3 class='mx-5'>编辑资料</h3>
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" class='' method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="form-group ">
                <lable>请输入想要更改的用户名</lable>
                <input type="text" class="form-control my-3" name='username'>
                <lable>请输入新密码</lable>
                <input type="password" class="form-control my-3" name="password">
                <lable>请确认新密码</lable>
                <input type="password" class="form-control my-3" name="confirm">
                <input type='file' id='avatar' class="my-2" name="avatar"> 
                <br>
                <button class='btn btn-primary my-2'>提交</button>
                <?php if(isset($error_message)):?>
                <div class="alert-danger form-control col-md-5 justify-content-center ">
                    <?php echo  $error_message;?>
                </div>
                <?php endif?>
            </div>
        </form>
    </div>
    
</body>

</html>