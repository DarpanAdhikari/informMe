<?php
session_start();
if (isset($_SESSION["logedIn"])) {
  if (isset($_SESSION['login_time'])) {
    $timeElapsed = time() - $_SESSION['login_time'];
    if ($timeElapsed >= (30 * 24 * 60 * 60)) {
      header('Location: logout');
      exit;
    }
  }
}
function convertString($name)
{
  $sentence = $name;
  $encodedSentence = str_replace(" ", "-", $sentence);
  return $encodedSentence;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#fb4b77">
  <meta name="robots" content="index, follow">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel='icon' href="additional/addition/logo512x512.png">
  <link rel="apple-touch-icon" href="additional/addition/logo192x192.png">
  <link rel="manifest" href="manifest.json">
  <link rel="stylesheet" href="additional/css/header.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
  <script src="additional/script/header.js" defer></script>
  <script src="additional/script/darkmode.js" defer></script>
  <script src="additional/script/search.js" defer></script>
  <?php
  $con = mysqli_connect("localhost", "root", "", "informme");
  $get = mysqli_query($con, "SELECT page_name FROM navbar where row = '1'");
  $PageName = "";
  if($get){
  if (mysqli_num_rows($get) > 0) {
    $PageName = mysqli_fetch_assoc($get);
  ?>
    <title><?php if ($PageName !== "") echo $PageName['page_name']; ?></title>
  <?php } }?>
</head>

<body>
  <header>
    <nav class="navbar">
      <ul class="nav-links">
        <div class="toggleBar">
          <span class="drp bar">&#9776;</span>
        </div>
        <li class="logo">
          <img src="additional/addition/logo192x192.png" width="50px" height="50px" alt="<?php if ($PageName !== "") echo $PageName['page_name']; ?> logo">
          <?php if ($PageName !== "") echo $PageName['page_name']; ?>
        </li>
        <div class="setting">

          <?php if (isset($_SESSION["logedIn"]) && $_SESSION["logedIn"] = true) {
            $id = $_SESSION['acc_id'];
            $query =  mysqli_query($con, "SELECT * FROM account where accId = '$id'");
            if ($query && mysqli_num_rows($query) === 1) {
              $row = mysqli_fetch_assoc($query);
              if ($row['userState'] !== '0') {
                $img = $row['image'];
                $key = 15227;
                $iv = '123456789abcdefg';
                $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
          ?>
                <div class="intro" id="profile">
                  <h4><?= $row['name']; ?>
                    <p style="font-size: 10px"><?= $row['position']; ?></p>
                  </h4>
                  <?php if (($row['image'] !== "") && ($row['image'] !== null)) { ?>
                    <picture>
                      <img src="<?= 'images/profile_img/', $image; ?>" alt="">
                    </picture>
                  <?php } else { ?>
                    <picture>
                      <img src="additional/addition/default.png" alt="">
                    </picture>
                  <?php } ?>
                </div>
          <?php }
            }
          } else {
            echo '<i class="fas fa-gear"></i>';
          } ?>
        </div>
      </ul>
      <ul class="nav-links">
        <li style="--i:1;"><a href="Home" class="active">Home</a></li>
        <?php

        $get = mysqli_query($con, "SELECT * FROM navbar");
        $sno = 2;
        $navArray = array();
        if($get){
        foreach ($get as $row) {
          $metaDescription = $row['about_us'];
          $navArray[] = $row['navigation'];
        ?>
          <?php
          if ($row['navigation'] !== "") {
            if ($row['nav_status'] !== '0') {
          ?>
              <li style="--i:<?= $sno; ?>;"><a href="<?= $row['navigation']; ?>">
                  <?= $row['navigation']; ?>
              <?php }
          }
              ?></a></li>
            <?php }} ?>
            <li style="--i:2;"><a href="Gallary"> Gallary</a></li>
            <li style="--i:3;">
            <a class="search-data">
              <i class="fas fa-magnifying-glass searchIcon" title="search"></i>
            </a>
              <div class="searchField">
                <div class="field">
                  <input type="search" placeholder="search here.." id="searchInput">
                  <button><i class="fas fa-magnifying-glass"></i></button>
                </div>
                <div class="searchList">
                </div>
              </div>
            </li>
      </ul>
      <div class="setting-menu">
        <div class="profile">
          <div class="user-info">
            <?php if (isset($_SESSION["logedIn"]) && $_SESSION["logedIn"] = true) { ?>
              <h3 onclick="openProfile()" title="ctrl+u"><i class="fas fa-user"></i> Profile</h3>

              <a href="update-profile">
                <h3><i class="fas fa-edit"></i> Edit profile</h3>
              </a>
              <a href="admin-panel" title="Ctrl+D">
                <h3><i class="fas fa-laptop"></i> Dashboard</h3>
              </a>
              <a href="logout">
                <h3><i class="fas fa-right-from-bracket"></i> Log Out <?php
                                                                      $remainingDays = ceil((30 * 24 * 60 * 60 - $timeElapsed) / (24 * 60 * 60));
                                                                      echo "'" . $remainingDays . "d'";
                                                                      ?></h3>
              </a>
            <?php } else { ?>
              <a href="account" onclick="signUp()">
                <h3><i class="fas fa-user-plus"></i>Sign Up</h3>
              </a>
              <a href="account">
                <h3><i class="fas fa-key"></i> Log In</h3>
              </a>
            <?php } ?>
            <a href="chat" title="ctrl+i">
              <h3><i class="fas fa-message"></i> Inbox</h3>
            </a>

            <a href="chat" class="help">
              <h3><i class="fa-regular fa-circle-question"></i> Help</h3>
            </a>
            <h3 class="theme">
              <div>
                <i class="fa-solid fa-circle-half-stroke"></i>Theme
              </div>
              <div class="tIcons">
                <h3><i class="fas fa-sun"></i>
                  <div>Dark Mode</div>
                </h3>
                <h3><i class="fas fa-computer"></i> Automatic</h3>
              </div>
            </h3>
          </div>
        </div>
      </div>

      <?php if (isset($_SESSION["logedIn"]) && $_SESSION["logedIn"] = true) {
        $id = $_SESSION['acc_id'];
        $query =  mysqli_query($con, "SELECT * FROM account where accId = '$id'");

        $result = mysqli_num_rows($query);
        if ($result == 1) {
          $row = mysqli_fetch_assoc($query);
          if ($row['userState'] !== '0') {
            $img = $row['image'];
            $key = 15227;
            $iv = '123456789abcdefg';
            $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
      ?>
            <div class="profile-container">
              <span class="drp close">&times;</span>
              <div class="profile-pic">
                <?php if (($row['image'] !== "") && ($row['image'] !== null)) { ?>
                  <img src="<?= 'images/profile_img/', $image; ?>" alt="">
                <?php } else { ?>
                  <img src="additional/addition/default.png" alt="">
                <?php } ?>
              </div>

              <div class="user-details">
                <h5>Name
                  <i class="fas fa-circle-user"></i>
                  <p><?= $row['name']; ?></p>
                </h5>
                <h5>Email
                  <i class="fas fa-envelope"></i>
                  <p><?= $row['email']; ?></p>
                </h5>
                <h5>Your Bio.
                  <i class="fas fa-address-card"></i>
                  <p><?= $row['bio']; ?></p>
                </h5>
                <h5>Position
                  <i class="fas fa-user-tag"></i>
                  <p><?= $row['position']; ?></p>
                </h5>
                <div class="user-social-id">
                  <h5>Social Media</h5>
                  <p><a href="<?= $row['facebook']; ?>" target="_blank">
                      <i class="fab fa-facebook"></i>
                    </a>
                    <a href="<?= $row['instagram']; ?>" target="_blank">
                      <i class="fab fa-instagram"></i>
                    </a>
                    <a href="<?= $row['twitter']; ?>" target="_blank">
                      <i class="fab fa-twitter"></i>
                    </a>
                    <a href="<?= $row['youtube']; ?>" target="_blank">
                      <i class="fab fa-youtube"></i>
                    </a>
                  </p>
                </div>

              </div>

              <button id='delete-account'>Delete Account</button>
            </div>

      <?php } else {
            session_destroy();
          }
        }
      } ?>
      </div>
    </nav>
    </div>

    <?php if (isset($_SESSION['error'])) { ?>
      <div class="alert_info active" style="background: #c43d3d;">
        <span><i class="fa-solid fa-triangle-exclamation"></i>ALERT!</span><?php echo $_SESSION['error']; ?>
      </div>
    <?php }
    unset($_SESSION['error']); ?>

    <?php if (isset($_SESSION['success'])) { ?>
      <div class="alert_info active" style="background: #159e45">
        <span><i class="fa-solid fa-thumbs-up"></i> Success!</span><?php echo $_SESSION['success']; ?>
      </div>
    <?php }
    unset($_SESSION['success']); ?>

    <!-- dialogue box for many actions -->
    <div class="postVisitSgt">
      <div class="dialogue">
        <h2>Account Delete</h2>
        <p>Are you sure you want to delete your account ?</p>
        <hr>
        <form action="deleteu" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <label for="pass">Enter your Password</label>
            <input type="password" name="password" placeholder="your password.." autocomplete="on" />
            <input type="submit" value="Delete" name="user_del">
          </div>
        </form>
      </div>
    </div>
  </header>
</body>

</html>