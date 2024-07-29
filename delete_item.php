<?php
session_start();
function decryptId($encryptedId, $key)
{
  $decryptedId = base64_decode(urldecode($encryptedId)) ^ $key;
  return $decryptedId;
}

$con = mysqli_connect("localhost", "root", "", "informme");

// chat deletion management
if (isset($_GET['ch_id'])) {
  $id = $_GET['ch_id'];
  $key = 94325928321;
  $id = decryptId($id, $key);
  $delete = mysqli_query($con, "DELETE FROM chat where chtId = $id");
  if ($delete) {
    mysqli_close($con);
    header('location:chat');
    exit;
  } else {
    mysqli_close($con);
    header('location:chat');
    exit;
  }
}
// by selecting
if (isset($_POST['deleteChat']) && isset($_POST['chatToDelete'])) {
  $selectedEncryptedIds = $_POST['chatToDelete'];
  $key = 94325928321;
  if (count($selectedEncryptedIds) > 0) {
    $selectedIds = array_map(function ($encryptedId) use ($key) {
      return decryptId($encryptedId, $key);
    }, $selectedEncryptedIds);
    $idList = implode(",", $selectedIds);
    $query = mysqli_query($con, "DELETE FROM chat WHERE chtId IN ($idList)");
    if ($query) {
      header('location:messages');
      exit;
    } else {
      $_SESSION['error'] = "Failed to delete data: " . $mysqli->error;
      header('location:messages');
      exit;
    }
  }
}

// account deletion management
if (isset($_POST['user_del'])) {
  $id = $_SESSION['acc_id'];
  $password = $_POST['password'];
  // delete image also when deleting account
  $query =  mysqli_query($con, "SELECT * FROM account where accId = '$id'");
  $result = mysqli_num_rows($query);
  $row = mysqli_fetch_assoc($query);
  if (password_verify($password, $row['password'])) {
    $img = $row['image'];
    $key = 15227;
    $iv = '123456789abcdefg';
    $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
    unlink('images/profile_img/' . $image);

    $delete_ch = mysqli_query($con, "DELETE FROM chat where  fk_accId = $id");
    $delete = mysqli_query($con, "DELETE FROM comment where fk_user = $id");
    $delete = mysqli_query($con, "DELETE FROM account where accId = $id");
    if ($delete && $delete_ch) {
      mysqli_close($con);
      header('location:logout');
      exit;
    }
  } else {
    $_SESSION['error'] = "Wrong password. Try again later";
    header('location:Home');
    exit;
  }
}

// delete post
if (isset($_GET['deletepost'])) {
  $key = 987654321;
  $id = $_GET['deletepost'];
  $id = decryptId($id, $key);
  $delete = mysqli_query($con, "DELETE FROM comment WHERE fk_postId = '$id'");
  $select = mysqli_query($con, "SELECT featureImg FROM post WHERE pId = '$id'");
  if (mysqli_num_rows($select) > 0) {
    $row = mysqli_fetch_assoc($select);
    $img = $row['featureImg'];
    $key = 1527781;
    $iv = '123456789abcdefg';
    $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
    if ($image !== "") {
      unlink('images/postimage/' . $image);
    }
  }
  $delete =  mysqli_query($con, "DELETE FROM `post` WHERE pId = '$id'");
  if ($delete) {
    $_SESSION['success'] = "That post is deleted";
    if (isset($_SERVER['HTTP_REFERER'])) {
      $previousPage = $_SERVER['HTTP_REFERER'];
      header("Location: $previousPage");
      exit;
    } else {
      header("Location: Home");
      exit;
    }
    exit;
  } else {
    $_SESSION['error'] = "can not delete try again";
    if (isset($_SERVER['HTTP_REFERER'])) {
      $previousPage = $_SERVER['HTTP_REFERER'];
      header("Location: $previousPage");
      exit;
    } else {
      header("Location: Home");
      exit;
    }
    exit;
  }
}

// delete comments
if (isset($_GET['deleteComment'])) {
  $key = 98765439;
  $id = $_GET['deleteComment'];
  $id = decryptId($id, $key);
  $delete = mysqli_query($con, "DELETE FROM comment WHERE cmId = '$id'");
  if ($delete) {
    if (isset($_SERVER['HTTP_REFERER'])) {
      $previousPage = $_SERVER['HTTP_REFERER'];
      header("Location: $previousPage");
      exit;
    } else {
      header("Location: Home");
      exit;
    }
  } else {
    if (isset($_SERVER['HTTP_REFERER'])) {
      $previousPage = $_SERVER['HTTP_REFERER'];
      header("Location: $previousPage");
      exit;
    } else {
      header("Location: Home");
      exit;
    }
  }
}
// by selecting
if (isset($_POST['deleteComment']) && isset($_POST['commentToDelete'])) {
  $selectedEncryptedIds = $_POST['commentToDelete'];
  $key = 98765439;
  if (count($selectedEncryptedIds) > 0) {
    $selectedIds = array_map(function ($encryptedId) use ($key) {
      return decryptId($encryptedId, $key);
    }, $selectedEncryptedIds);
    $idList = implode(",", $selectedIds);
    $query = mysqli_query($con, "DELETE FROM comment WHERE cmId IN ($idList)");
    if ($query) {
      header('location:comments');
      exit;
    } else {
      $_SESSION['error'] = "Failed to delete data: " . $mysqli->error;
      header('location:comments');
      exit;
    }
  }
}

// delete images of gallary
if (isset($_GET['deleteImage'])) {
  $key = 9876543;
  $id = $_GET['deleteImage'];
  $id = decryptId($id, $key);
  $select = mysqli_query($con, "SELECT imageUrl FROM postImg WHERE imageId = '$id'");
  if (mysqli_num_rows($select) > 0) {
    $row = mysqli_fetch_assoc($select);
    $img = $row['imageUrl'];
    $key = 1527781991;
    $iv = '123456789abcdefg';
    $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
    unlink('images/imagePost/' . $image);
    $delete = mysqli_query($con, "DELETE FROM postImg WHERE imageId = '$id'");
  }
  if ($delete) {
    header('location:uplimg');
    exit;
  } else {
    $_SESSION['error'] = "Unable to delete that image";
    header('location:uplimg');
    exit;
  }
}
mysqli_close($con);
