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

    if($_SERVER['REQUEST_METHOD']==='POST'){
        writeblog();
    }
    function writeblog(){
        global $row;
        global $error_message;
        if(empty($_POST['theme'])){
            $error_message="文章标题不得为空";
            return;
        }
        if(empty($_POST['class'])){
            $error_message="文章类别不得为空";
            return;
        }
        if(empty($_POST['text'])){
            $error_message="文章内容不得为空";
            return;
        }
        $hot=1;
        if(empty($_POST['hot'])){
            $hot=0;
        }

        
        $theme=$_POST['theme'];
        $class=$_POST['class'];
        $text=$_POST['text'];
        $time=date("Y-m-d h:i:s");
        $connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $name=$row['name'];
        var_dump($name);
        $query=mysqli_query($connection,"INSERT INTO post(theme,time,`user`,class,content,hot) VALUES('{$theme}','{$time}','{$name}','{$class}','{$text}',{$hot})");
        
        var_dump($query);

    }

    
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>写博客</title>
    <link rel="stylesheet" href="../static/assets/venders/bootstrap-4.0.0/dist/css/bootstrap.css">
    <script src="../static/assets/venders/jquery3.0/jquery-3.0.0.min.js"></script>
    <script src="/static/assets/venders/ckeditor/ckeditor.js"></script>
</head>

<body>
    <div class="nav ">
        <div class="avatar">
            <a class="nav-link active" href="#"><img src="<?php echo $row['avatar']?>" alt="" width="50px" height="50px"
                    class="img-circle"></a>
        </div>
        <ul class="nav flex-justify-content-center my-3">
            <li class="nav-item mx-0 ">
                <a class="nav-link active" href="/admin/index.php">
                    <?php echo $row['name']?></a>
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
    <div class="container">
        <form action="<?php echo $_SERVER['PHP_SELF']?>" class="my-5 row" method="post">
            <div class="form-group col-6">
                <label for="article" class="mx-2 ">标题</label>
                <input type="text" name="theme" id="article" class="form-control" placeholder="请输入文章标题" autocomplete="off">
            </div>
            <div class="form-group col-6">
                <label for="class" class="mx-2 ">类别</label>
                <input type="text" name="class" id="class" class="form-control" placeholder="请输入文章类别" autocomplete="off">
            </div>
            <div class="form-group col-12">
                <textarea name="text" id="editor" cols="30" rows="10" class="form-control my-3" placeholder="在此编辑内容"></textarea>
            </div>
            <div class="form-group mx-3">

                <input type="checkbox" value="ok" name="hot" id="hot">
                <label for="hot">是否设置在主页</label>
            </div>
            <div class="form-group"><button class="btn btn-default mx-3">提交</button></div>

        </form>
        <?php if(isset($error_message)):?>
        <div class="alert-danger form-control col-md-5 justify-content-center  my-0">
            <?php echo  $error_message;?>
        </div>
        <?php endif?>
        <script>
             CKEDITOR.replace( 'editor' );
        </script>

    </div>
</body>

</html>