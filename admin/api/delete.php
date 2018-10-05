<?php
    include('../../config.php');
    $name=$_GET['name'];
    $connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    $query=mysqli_query($connection,"DELETE FROM `post` WHERE theme='{$name}'");