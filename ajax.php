<?php
session_start();
function decryptId($encryptedId, $key)
{
  $decryptedId = base64_decode(urldecode($encryptedId)) ^ $key;
  return $decryptedId;
}
$con = mysqli_connect("localhost", "root", "", "informme");
// CHANGE ONLINE STATUS AS USER IS ONLINE 
if (isset($_POST['change'])) {
  $id = $_SESSION['acc_id'];
  $status = $_POST['change'];
  $query = "UPDATE account SET onlineStat = '$status' WHERE accId = '$id'";
  $result = mysqli_query($con, $query);
  mysqli_close($con);
}

//  insert comment into database
if (isset($_POST['comment']) && $_POST['comment'] !== "") {
  $comment = $_POST['comment'];
  $cmtDate = date("j, n, Y");
  $key = 987654393;
  $id = $_POST['id'];
  $postId = decryptId($id, $key);

  if (isset($_POST['name'])) {
    $commentor = $_POST['name'];
    $insertCmt = mysqli_query($con, "INSERT INTO comment (`commentor`,`comment`,`cmtDate`,`fk_postId`) VALUES ('$commentor','$comment','$cmtDate','$postId')");
  } else {
    $uid = $_SESSION['acc_id'];
    $commentor = "";
    $insertCmt = mysqli_query($con, "INSERT INTO comment (`fk_user`,`commentor`,`comment`,`cmtDate`,`fk_postId`) VALUES ('$uid','$commentor','$comment','$cmtDate','$postId')");
  }


  if ($insertCmt) {
    $newComment = mysqli_query($con, "SELECT * FROM comment ORDER BY cmId DESC LIMIT 1");

    if ($newComment && mysqli_num_rows($newComment) > 0) {
      $getCmt = mysqli_fetch_assoc($newComment);
      $commentor = $getCmt['commentor'];
      $commentText = $getCmt['comment'];

      if ($commentor === "") {
        $user = mysqli_query($con, "SELECT name,image FROM account WHERE accId = '$uid'");

        if ($user && mysqli_num_rows($user) > 0) {
          $row = mysqli_fetch_assoc($user);
          $img = $row['image'];
          $key = 15227;
          $iv = '123456789abcdefg';
          $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
          $image = "images/profile_img/" . $image;

          if ($row['image'] === "") {
            $image = "additional/addition/default.png";
          }

          echo "<div class='comment-item'>
            <img loading='lazy' src='".$image."' alt='".$row['name']."'>
            <div>
            <h4>".$row['name']."</h4>
            <p>".$commentText."</p>
            </div>
            <div>
          <span class='UploadDate'>".$getCmt['cmtDate']."</span>
            <div class='option'>...
              <a href='".$id = $getCmt['cmId'];
                        $key = 98765439; 
                        $encryptedId = encryptId($id, $key);
                        echo 'delete-Comment'.$encryptedId;"'>
              <i class='fas fa-trash'></i>
              </a>
            </div>
          </div>
          </div>";
        }
      } else {
        echo "<div class='comment-item'>
          <img loading='lazy' src='additional/addition/default.png' alt='".$commentor."'>
          <div>
          <h4>".$commentor."</h4>
          <p>".$commentText."</p>
          </div>   
          <div>
          <span class='UploadDate'>".$getCmt['cmtDate']."</span>
            <div class='option'>...
              <a href='".$id = $getCmt['cmId'];
                        $key = 98765439; 
                        $encryptedId = encryptId($id, $key);
                        echo 'delete-Comment'.$encryptedId;"'>
              <i class='fas fa-trash'></i>
              </a>
            </div>
          </div>
        </div>";
      }
    }
  }
}
// increase likes
if(isset($_POST['likes'])){
  $key = 987654393;
  $pId = $_POST['likes'];
  $pId = decryptId($pId, $key);
  $update = mysqli_query($con,"UPDATE post SET likes = likes+1 WHERE pId = '$pId'");
  if($update)
  {
    echo "Im update";
  }else{
    echo "im not update";
  }
}

// if image uploaded
if(isset($_FILES['images']) && !isset($_POST['submitted'])){
  $_POST['submitted'] = true;
  $errors = [];
  $uploadedFiles = [];
  $extension = ['jpg','jpeg','png','gif','jfif'];

  $total = count($_FILES['images']['name']);

  for($i=0; $i < $total; $i++) {
      $file_name = $_FILES['images']['name'][$i];
      $file_tmp = $_FILES['images']['tmp_name'][$i];
      $file_type = $_FILES['images']['type'][$i];
      $file_size = $_FILES['images']['size'][$i];

      if($file_name !==""){
          $uploaded_ext = pathinfo($file_name, PATHINFO_EXTENSION);
          $temp_name = uniqid() . '.' . $uploaded_ext;
          $iv = '123456789abcdefg';
          $key = 1527781991;
          $image = openssl_encrypt($temp_name, 'AES-256-CBC', $key, 0,$iv);
      }

      $file_ext = strtolower($uploaded_ext);
      if (!in_array($file_ext, $extension)) {
          $errors[] = 'Extension not allowed: ' . $file_name . ' (' . $file_type . ')';
      }

      if (empty($errors)) {
          $query =  mysqli_query($con, "INSERT INTO postImg (imageUrl) VALUES ('$image')");
          if($query){ 
              $img_path ='images/imagePost/'.$temp_name; 
              move_uploaded_file($file_tmp, $img_path);
          }
      }
  }
  $selectImg = mysqli_query($con,'SELECT * FROM postImg ORDER BY imageId DESC');
  if(mysqli_num_rows($selectImg) > 0){
      foreach($selectImg as $row){
          $img = $row['imageUrl'];
          $key = 1527781991;
          $iv = '123456789abcdefg';
          $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
          ?>
          <img src="images/imagePost/<?= $image;?>" alt="postImage">
          <?php
      }
  }
}

// image uploaded updation
if(isset($_POST['urlSbmt'])){
  $id = $_POST['updateUrl'];
  $key = 9820;
  $id = decryptId($id, $key);
  $url = $_POST['urlData'];
  $update = mysqli_query($con,"UPDATE postimg SET url = '$url' WHERE imageId = '$id'");
  if($update)
  {
    header('location:posts');
    exit;
  }else{
    $_SESSION['error'] = "There is something wrong!";
    header('location:posts');
    exit;
  }
}
mysqli_close($con);
?>