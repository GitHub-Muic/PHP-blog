<?php
include('../../config.php');
$connection=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
$name=$_GET['name'];
$query=mysqli_query($connection,"SELECT content FROM `post` WHERE theme='{$name}'");
if(!$query){
    exit("失败");
}

$row=mysqli_fetch_row($query);
echo $row[0];

?>