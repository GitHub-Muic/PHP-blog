<?php
    include('../../config.php');
    $connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $email=$_GET['email'];
    $query=mysqli_query($connection,"SELECT avatar FROM `users` WHERE email='{$email}'");
    if(!$query){
        exit("失败");
    }
    $row=mysqli_fetch_row($query);
    echo $row[0];

?>
