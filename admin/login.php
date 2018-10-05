<?php
    include('../config.php');
    session_start(); 
    if($_SERVER['REQUEST_METHOD']==='POST'){
        loginCheck();
        
        
    }
    //判断是否为Post请求,若是则进行下列操作
    function loginCheck(){
         
        global $error_message;
        if(empty($_POST['username'])){
            $error_message="用户名不能为空!";
            return;
        }
        if(empty($_POST['password'])){
            $error_message="密码不能为空!";
            return;
        }
        //赋值
        $username=$_POST['username'];
        $password=$_POST['password'];
        //数据库的连接与查询
        $connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
        $query=mysqli_query($connection,"SELECT email FROM `users` WHERE email='{$username}'");
        $row=mysqli_fetch_row($query);
        if(!$row){
            $error_message="用户未被注册!";
            return ;
        }
        //一个用户名对应的一个密码,所以可以直接用数据库逻辑判断
        $query=mysqli_query($connection,"SELECT password FROM `users` WHERE email='{$username}'");
        $row=mysqli_fetch_row($query);
        if($row[0]!=$password){
            $error_message="密码不正确!";
            return ;
        }
  
        $_SESSION['session_state']=$username;
        header('Location:./index.php');
    }
    if($_SERVER['REQUEST_METHOD']==='GET'){
        
        unset($_SESSION['session_state']);
    }
    
    


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>登录</title>
    <link rel="stylesheet" href="/static/assets/venders/bootstrap-4.0.0/dist/css/bootstrap.css">
    <script src='/static/assets/venders/jquery3.0/jquery-3.0.0.min.js'></script>
</head>


<body>
    <div class="container my-5">



        <div class="row  justify-content-center my-3   ">
            <form class="col-md-4" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
                <div class="col-md-5 justify-content-center my-3 align-self-end ">
                    <img id="avatar" src="../static/assets/img/default.png" class="img-thumbnail img-res">
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1" class="col-form-label">用户名</label>
                    <input type="email" class="form-control " id="exampleInputEmail1" aria-describedby="emailHelp"
                        placeholder=请输入用户名 name="username" autocomplete="off">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1" class="col-form-label">密码</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="请输入密码" name="password">
                </div>
                <!-- <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="saveinfo" value="ok">
                    <label class="form-check-label" for="exampleCheck1">记住密码</label>
                </div> -->
                <button type="submit" class="btn btn-primary my-2">登录</button>
                <?php if(isset($error_message)):?>
                <div class="alert-danger form-control col-md-5 justify-content-center ">
                    <?php echo  $error_message;?>
                </div>
                <?php endif?>
            </form>

        </div>
        
    </div>
    <script>
        $(function () {  
            $('#exampleInputEmail1').on('blur',function(){
                var email=$(this).val();
                var x=/^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.com$/
                
                if(email&& x.test(email))
                
                {
                    //ajax这个返回的rel为string类型
                    $.get('/admin/api/avatar.php',{email:email},function (rel) {
                        console.log(rel.trim())
                        if(rel.trim().substr(0,1)=='.'||rel.trim().substr(0,1)=='..')
                        $('#avatar').attr('src',rel);
                     })
                }
               
            })
        })
    
    </script>
</body>

</html>