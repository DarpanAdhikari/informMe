<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "informme");
// account insert----------------------
if (isset($_POST["accountSubmit"])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $userbio = $_POST['user_bio'];

  // image encrypt-----
  $img = $_FILES['profilepic']['name'];
  if($img !==""){
    $uploaded_ext = pathinfo($img, PATHINFO_EXTENSION);
    $temp_name = uniqid() . '.' . $uploaded_ext;
    $iv = '123456789abcdefg';
    $key = 15227;
    $image = openssl_encrypt($temp_name, 'AES-256-CBC', $key, 0,$iv);
  }

  $pass = $_POST['password'];
  $password = password_hash($pass, PASSWORD_DEFAULT);

  $checkuser = mysqli_query($con,"SELECT * FROM account where email = '$email'");
if(mysqli_num_rows($checkuser)>0){
    $_SESSION['acc_error'] = "This email already has an account.";
    header('location:account');
    exit;
  } else{
    $userbio = $con->real_escape_string($userbio);
   $insert = "INSERT INTO `account`( `name`, `email`, `password`, `image`, `bio`) VALUES ('$username','$email','$password','$image','$userbio')";
    $query = mysqli_query($con,$insert);

    if($query){ 
       $tempname = $_FILES['profilepic']["tmp_name"];  
       $img_path ='images/profile_img/'.$temp_name; 
        move_uploaded_file($tempname, $img_path);
        $_SESSION['acc_success'] = "You successfully created your account please login now.";
        $_SESSION['opnLogin'] = true;
        mysqli_close($con);
        header('location:account');
        exit;
    }else{
       $_SESSION['acc_error'] = "Something went wrong";
       mysqli_close($con);
       header('location:account');
       exit;
    }
}
}
// checking login iformation-------------
if(isset($_POST["checkacc"])){
  $email = $_POST['email'];
  $password = $_POST['password'];
  $query =  mysqli_query($con,"SELECT * FROM account where email = '$email'");
  $result = mysqli_num_rows($query);
  if($result == 1){
    $row = mysqli_fetch_assoc($query);
    if($row['userState'] !== '0'){
      if(password_verify($password,$row['password'])){
        $_SESSION['acc_id']= $row['accId'];
        $_SESSION["logedIn"] = true;
        if($_SESSION['login_time']){
          unset($_SESSION['login_time']);
        }
        $_SESSION['login_time'] = time();
        mysqli_close($con);
        header("location:Home");
        exit;
      }else{
        $_SESSION["acc_error"] = "Email or password error";
        mysqli_close($con);
        header("location:account");
        exit;
      }
    }else{
      $_SESSION['error'] = "Your account is blocked. Try to contact with our team.";
      mysqli_close($con);
      header('location:Home');
      exit;
    }
  }else{
    $_SESSION['acc_error'] = "Please create your account before logIn.";
    header('location:account');
    exit;
  }
}
// chat inserting------------------
if(isset($_POST['messageSmt'])){
  $message = $_POST['message'];
  $message = $con->real_escape_string($message);
  $date = date("j, n, Y");

  if(isset($_SESSION['logedIn']) && $_SESSION['logedIn'] === true){
    $accId = $_SESSION['acc_id'];
    $insert = "INSERT INTO chat(`message`, `fk_accId`, `date`) VALUES ('$message','$accId','$date')";
  }else{
    $name = $_POST['name'];
    $insert = "INSERT INTO chat(`ch_name`, `message`, `date`) VALUES ('$name','$message','$date')";
  }
  $query = mysqli_query($con,$insert);
  if($query){
    mysqli_close($con);
    header("location:chat");
    exit;
  }else{
    mysqli_close($con);
    header("location:chat");
    exit;
  }
}
if(isset($_POST['navSbt'])){
  $page_name = $_POST['page_name'];
  $firstNav = $_POST['first'];
  $secondNav = $_POST['second'];
  $thirdNav = $_POST['third'];
  $fourthNav = $_POST['fourth'];
  if($fourthNav===""){
    $fourthNav = "not-available";
  }
  $fifthNav = $_POST['fifth'];
  if($fifthNav===""){
    $fifthNav = "not-available";
  }
  $aboutUs = $_POST['about_us'];
  $aboutUs = $con->real_escape_string($aboutUs);
  $facebook = $_POST['facebook'];
  $youtube = $_POST['youtube'];
  $twitter = $_POST['twitter'];
  $pinterest = $_POST['pinterest'];
 if($firstNav !=="" && $secondNav !=="" && $thirdNav !==""){
  $insert = mysqli_query($con,"INSERT INTO navbar (`page_name`,`row`,`navigation`,`about_us`,`visitors`,`facebook`,`youtube`,`twitter`,`pinterest`,`nav_status`)VALUES('$page_name','1','$firstNav','$aboutUs','0','$facebook','$youtube','$twitter','$pinterest','$navStatus')");
  $insert = mysqli_query($con,"INSERT INTO navbar (`row`,`navigation`)VALUES('2','$secondNav'),('3','$thirdNav'),('4','$fourthNav'),('5','$fifthNav')");
 }else{
  $_SESSION['error']="You fill minimum first 3 field";
  header('location:nav-edit');
  exit;
 }
  if($insert){
    $rewriteRules = [
      '# Custom Rewrite Rules',
      'RewriteRule ^'.$firstNav.'$ blog.php?pageId=1 [L]',
      'RewriteRule ^'.$secondNav.'$ blog.php?pageId=2 [L]',
      'RewriteRule ^'.$thirdNav.'$ blog.php?pageId=3 [L]',
      'RewriteRule ^'.$fourthNav.'$ blog.php?pageId=4 [L]',
      'RewriteRule ^'.$fifthNav.'$ blog.php?pageId=5 [L]',
      '# default url redirect',
      'RewriteCond %{REQUEST_FILENAME} !-f',
      'RewriteCond %{REQUEST_FILENAME} !-d',
      'RewriteRule ^(.*)$ readpost.php [L]'
  ];
  
  $htaccessFile = '.htaccess';
  $currentContent = file_get_contents($htaccessFile);
  $existingRules = implode(PHP_EOL, $rewriteRules);
  if (strpos($currentContent, $existingRules) === false) {
      $newContent = $currentContent . PHP_EOL . $existingRules;
      file_put_contents($htaccessFile, $newContent);
  } else {
      $newContent = preg_replace(
          '/# Custom Rewrite Rules.*# default url redirect/s',
          $existingRules,
          $currentContent
      );
      file_put_contents($htaccessFile, $newContent);
  }
  
  
  $_SESSION['success'] = "Something new changes on your main page";
  header('location:navigations');
  exit;
  }else{
    $_SESSION['error']="We got some error try again";
    header('location:navigations');
    exit;
  }
}

// insert post into database
if(isset($_POST['postSbt'])||isset($_POST['postDrf'])){

  if(isset($_POST['postDrf'])){
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
  $tags = $_POST['tags'];
  $tags = $con->real_escape_string($tags);
  $featureImg = $_FILES['feature_img']['name'];
  if($featureImg !==""){
    $uploaded_ext = pathinfo($featureImg, PATHINFO_EXTENSION);
    $temp_name = uniqid() . '.' . $uploaded_ext;
    $iv = '123456789abcdefg';
    $key = 1527781;
    $image = openssl_encrypt($temp_name, 'AES-256-CBC', $key, 0,$iv);
  }
  $content = $_POST['content'];
  $content = $con->real_escape_string($content);
  $author = $_SESSION['acc_id'];
  $uplodDate = date("j, n, Y");

  $insert = mysqli_query($con,"INSERT INTO post (`title`,`slug`,`metaDescription`,`keywords`,`fk_category`,`tags`,`content`,`featureImg`,`postStat`,`postDate`,`fk_author`,`display`) VALUES ('$title','$slug','$meta_description','$keywords','$category','$tags','$content','$image','$postStat','$uplodDate','$author','$display')");

  if($insert){ 
    $tempname = $_FILES['feature_img']["tmp_name"];  
    $img_path ='images/postimage/'.$temp_name; 
     move_uploaded_file($tempname, $img_path);
     $_SESSION['success'] = "New post is arrived right now";

      if(isset($_POST['postDrf'])){
      header('location:draft');
    }else{
      header('location:posts');
    }
     exit;
 }else{
  $_SESSION['error'] = "post can not submited".$mysqli->error;
  if (isset($_SERVER['HTTP_REFERER'])) {
    $previousPage = $_SERVER['HTTP_REFERER'];
    header("Location: $previousPage");
    exit;
  } else {
    header("Location: posts");
    exit;
  }
  exit;
 }
}

mysqli_close($con);
?>