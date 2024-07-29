<?php
session_start();
function decryptId($encryptedId, $key)
{
    $decryptedId = base64_decode(urldecode($encryptedId)) ^ $key;
    return $decryptedId;
}
function decryptImg($encryptImg,$key){
  $iv = '123456789abcdefg';
  $image = openssl_decrypt($encryptImg, 'AES-256-CBC', $key, 0, $iv);
  return $image;
}

$con = mysqli_connect("localhost", "root", "", "informme");

if(isset($_GET['acc_up'])){
  $id = $_GET['acc_up'];
  $state = $_GET['state'];
  $query = mysqli_query($con,"UPDATE account SET userState = $state where accId=$id");
  if($query){
     if($state =='0'){
      $_SESSION['success'] = "You banned one successfully";
     }else{
      $_SESSION['success'] = "You that user unbanned successfully";
     }
     mysqli_close($con);
    header("location:users");
    exit;
  }else{
    $_SESSION['success'] = "wait something is wrong";
    mysqli_close($con);
    header("location:users");
    exit;
  }
}

// profile details update
if(isset($_POST['upDprofile'])){
  $accId = $_SESSION['acc_id'];
  $name = $_POST['name'];
  $name = $con->real_escape_string($name);
  $email = $_POST['email'];
  $confirmPass = $_POST['confirmpass'];
  $oldPass = $_POST['old_pass'];
  $newPass = $_POST['password'];
  $old_img = $_POST['old_img'];
  $new_img = $_FILES['new_img']['name'];
  $userbio = $_POST['bio'];
  $userbio = $con->real_escape_string($userbio);
  $facebook = $_POST['facebook'];
  $instagram = $_POST['instagram'];
  $youtube = $_POST['youtube'];
  $twitter = $_POST['twitter'];

  $query =  mysqli_query($con,"SELECT * FROM account where accId = '$accId'");
  $result = mysqli_num_rows($query);
  $row = mysqli_fetch_assoc($query);

  if(password_verify($oldPass,$row['password'])){
    if($new_img !=="")
    {
      $uploaded_ext = pathinfo($new_img, PATHINFO_EXTENSION);
      $temp_name = uniqid() . '.' . $uploaded_ext;
      $iv = '123456789abcdefg';
      $key = 15227;
      $image = openssl_encrypt($temp_name, 'AES-256-CBC', $key, 0,$iv);
      unlink('images/profile_img/'.$old_img);

      $tempname= $_FILES['new_img']['tmp_name'];
      $img_path ='images/profile_img/'.$temp_name;
      move_uploaded_file($tempname, $img_path);
    }else{
      $image = $row['image'];
    }
  
    if($newPass===""){
      $newPass = $row['password'];
    }else{
      $newPass = password_hash($newPass, PASSWORD_DEFAULT);
    }
    if($facebook===""){
      $facebook = $row['facebook'];
    }
    if($instagram===""){
      $instagram = $row['instagram'];
    }
    if($youtube===""){
      $youtube = $row['$youtube'];
    }
    if($twitter===""){
      $twitter = $row['twitter'];
    }
   
    $update = mysqli_query($con,"UPDATE account SET name = '$name',email = '$email', password = '$newPass', bio = '$userbio', image='$image',facebook='$facebook',instagram='$instagram',youtube='$youtube',twitter='$twitter' where accId = '$accId'");
    if($update){
      mysqli_close($con);
      header('location:Home');
      exit;
    }else{
      $_SESSION['error']="Profile update has been canceled for some reason try again later";
      mysqli_close($con);
      header('location:update-profile');
      exit;
    }
  }else{
    $_SESSION['error']="Wrong Password";
    mysqli_close($con);
    header('location:update-profile');
    exit;
  }
}

// update position of user
if(isset($_POST['positionSet'])){
  $id = $_POST['id'];
  $position = $_POST['position'];
  $update = mysqli_query($con,"UPDATE account SET position = '$position' where accId = '$id'");
  if($update){
    mysqli_close($con);
    header('location:users');
    exit;
  }else{
    $_SESSION['error'] = "something went wrong";
    mysqli_close($con);
    header('location:users');
    exit;
  }
}

// update navigation details------------
if(isset($_POST['navSbt']))
{
  function fetchRowById($id) {
    $conn = mysqli_connect("localhost", "root", "", "informme");

    $escapedId = mysqli_real_escape_string($conn, $id);
    $result = mysqli_query($conn,"SELECT * FROM navbar WHERE row = '$escapedId'");
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        return $row;
    } else {
        return null;
    }
}
 $row = fetchRowById(1);
  $oldFirstnav = $row['navigation'];
  $row = fetchRowById(2);
  $oldsecondnav = $row['navigation'];
  $row = fetchRowById(3);
  $oldThirdnav = $row['navigation'];
  $row = fetchRowById(4);
  $oldFourthnav = $row['navigation'];
  $row = fetchRowById(5);
  $oldFifthnav = $row['navigation'];
  $firstNav = $_POST['first'];
  if($firstNav===""){
    $firstNav = $oldFirstnav;
  }
  $secondNav = $_POST['second'];
  if($secondNav===""){
    $secondNav = $oldsecondnav;
  }
  $thirdNav = $_POST['third'];
  if($thirdNav ===""){
    $thirdNav = $oldThirdnav;
  }
  $fourthNav = $_POST['fourth'];
  if($fourthNav ===""){
    $fourthNav = $oldFourthnav;
  }
  $fifthNav = $_POST['fifth'];
  if($fifthNav ===""){
    $fifthNav = $oldFifthnav;
  }

  // retrive links 
  $get = mysqli_query($con,"SELECT * FROM navbar Where row = '1'");
    $content = mysqli_fetch_assoc($get);
  $page_name = $_POST['page_name'];
  if($page_name === ""){
    $page_name = $content['page_name'];
  }
  $aboutUs = $_POST['about_us'];
  if($aboutUs===""){
    $aboutUs = $content['about_us'];
  }
  $aboutUs = $con->real_escape_string($aboutUs);

  $facebook = $_POST['facebook']; 
 if($facebook === ""){
    $facebook = $content['facebook'];
  }
  $youtube = $_POST['youtube'];
  if($youtube === ""){
    $youtube = $content['youtube'];
  }
  $twitter = $_POST['twitter'];
  if($twitter === ""){
    $twitter = $content['twitter'];
  }
  $pinterest = $_POST['pinterest'];
  if($pinterest === ""){
    $pinterest = $content['$pinterest'];
  }

  // other procedure start
  
  $sql = "UPDATE navbar SET page_name='$page_name', navigation = '$firstNav',about_us = '$aboutUs',facebook = '$facebook',youtube = '$youtube',twitter = '$twitter',pinterest='$pinterest' WHERE row='1';
  UPDATE navbar SET navigation = '$secondNav' WHERE row='2';
  UPDATE navbar SET navigation = '$thirdNav' WHERE row='3';
  UPDATE navbar SET navigation = '$fourthNav' WHERE row='4';
  UPDATE navbar SET navigation = '$fifthNav' WHERE row='5';";
  $update = mysqli_multi_query($con,$sql);
   
    if($update){
      $replacements = array(
        'RewriteRule ^'.$oldFirstnav.'$ blog.php?pageId=1 [L]'=>'RewriteRule ^'.$firstNav.'$ blog.php?pageId=1 [L]',
        'RewriteRule ^'.$oldsecondnav.'$ blog.php?pageId=2 [L]'=>'RewriteRule ^'.$secondNav.'$ blog.php?pageId=2 [L]',
        'RewriteRule ^'.$oldThirdnav.'$ blog.php?pageId=3 [L]'=>'RewriteRule ^'.$thirdNav.'$ blog.php?pageId=3 [L]',
        'RewriteRule ^'.$oldFourthnav.'$ blog.php?pageId=4 [L]'=>'RewriteRule ^'.$fourthNav.'$ blog.php?pageId=4 [L]',
        'RewriteRule ^'.$oldFifthnav.'$ blog.php?pageId=5 [L]'=>'RewriteRule ^'.$fifthNav.'$ blog.php?pageId=5 [L]'
      );
      $file = '.htaccess';
      $currentContent = file_get_contents($file);
      $newContent = strtr($currentContent, $replacements);
      if (is_writable($file)) {
        file_put_contents($file, $newContent);
    }
    header('location:navigations');
    exit;
    }else{
      $_SESSION['error']="Error found try again";
      header('location:nav-edit');
      exit;
    }
}

// remove navigation from window
if(isset($_GET['navdel'])){
  $row = $_GET['navdel'];
  $state = $_GET['state'];
  $update = mysqli_query($con,"UPDATE navbar SET nav_status = '$state' where row='$row'");
  if($update){
    if($state==="0"){
      $_SESSION['success'] = "navigation hide successfully";
    }else{
      $_SESSION['success'] = "navigation displayed successfully";
    }
    header('location:navigations');
    exit;
  }else{
    $_SESSION['error'] = "I'm unable to hide this navigation from window";
    header('location:navigations');
    exit;
  }
}

if(isset($_POST['postUpd'])||isset($_POST['updraft'])){
  $key = 9876543923;
  $eId = $_POST['postNo'];
  $id = decryptId($eId, $key);

  $key = 1527781;
  $old_featureImg = $_POST['old_img'];
  $old_image = decryptImg($old_featureImg,$key);
  $new_featureImg = $_FILES['feature_img']['name'];
  if($new_featureImg !==""){
    $uploaded_ext = pathinfo($new_featureImg, PATHINFO_EXTENSION);
    $temp_name = uniqid() . '.' . $uploaded_ext;
    $iv = '123456789abcdefg';
    $key = 1527781;
    $image = openssl_encrypt($temp_name, 'AES-256-CBC', $key, 0,$iv);
    unlink('images/postimage/'.$old_image);

    $tempname= $_FILES['feature_img']['tmp_name'];
    $img_path ='images/postimage/'.$temp_name;
    move_uploaded_file($tempname, $img_path);
  }else{
    $image = $old_featureImg;
  }

  if(isset($_POST['updraft'])){
    $postStat = "0";
  }else{
    $postStat = "1";
  }
  $display = $_POST['display'];
  $title = $_POST['title'];
  $title = $con->real_escape_string($title);
  $slug = $_POST['slug'];
  $slug = $con->real_escape_string($slug);
  $meta_description = $_POST['meta_description'];
  $meta_description = $con->real_escape_string($meta_description);
  $keywords = $_POST['keywords'];
  $keywords = $con->real_escape_string($keywords);
  $category = $_POST['category'];
  $display = $_POST['display'];
  $tags = $_POST['tags'];
  $tags = $con->real_escape_string($tags);
  $content = $_POST['content'];
  $content = $con->real_escape_string($content);
  $author = $_SESSION['acc_id'];

  $update = mysqli_query($con,"UPDATE post SET title = '$title', slug = '$slug',metaDescription ='$meta_description',keywords = '$keywords',fk_category = '$category',tags = '$tags',content = '$content',featureImg = '$image',postStat = '$postStat',display = '$display' WHERE pId = '$id'");

  if($update){ 
     $_SESSION['success'] = "You successively updated -".$title;
     if(isset($_POST['updraft'])){
      header('location:draft');
    }else{
      header('location:posts');
    }
     exit;
 }else{
  $_SESSION['error'] = "Please try again something went wrong";
  header('location:update-post'.$eId.'');
  exit;
 }
}
mysqli_close($con);
?>
