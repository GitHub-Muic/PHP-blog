<?php
    include('../../config.php');
    $connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $name=$_GET['name'];
    $content=$_GET['content'];
    $query=mysqli_query($connection,"UPDATE `post` SET content='{$content}'  WHERE theme='{$name}'");
    var_dump($query);