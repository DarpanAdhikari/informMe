<?php 
session_start();
$con = mysqli_connect("localhost", "root", "", "informme");
$id = $_SESSION['acc_id'];
$query = mysqli_query($con,"UPDATE account SET onlineStat = '0' WHERE accId = '$id'");
      unset($_SESSION['acc_id']);
      session_destroy();
      header('location:Home');
      exit;
mysqli_close($con);
?>