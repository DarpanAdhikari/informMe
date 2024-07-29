<?php session_start();
if (isset($_SESSION["logedIn"]) && $_SESSION["logedIn"] = true) {
  header('location:Home');
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <link rel="icon" href="additional/logo.png">
  <link rel="stylesheet" href="additional/css/account.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
  <script src="additional/script/account.js" defer></script>
  <?php
  $con = mysqli_connect("localhost", "root", "", "informme");
  $get = mysqli_query($con, "SELECT page_name FROM navbar where row = '1'");
  if ($get && mysqli_num_rows($get)>0) {
    $PageName = mysqli_fetch_assoc($get);
  ?>
    <title><?= $PageName['page_name']; ?></title>
  <?php } ?>

</head>

<body>

  <div class="house-container">
    <div class="house">
      <span class="drp sun-b">&#9728;</span>
      <div class="main">
        <div class="window_right"></div>
        <div class="window_left"></div>
        <div class="line adjst"></div>
        <div class="roof"></div>
        <div class="front">
          <div class="line"></div>
          <div class="door">
            <div class="dotHole"></div>
            <i class="fas fa-key"></i>
          </div>
        </div>
        <div class="side">
          <div class="line"></div>
          <div class="windows"></div>
        </div>
      </div>
    </div>
    <div class="road">
      <img src="additional/addition/giphy.gif" alt="" width="200px" draggable="false">
    </div>
  </div>

  <!-- form container started----------------- -->
  <div class="form-container">
    <?php if (isset($_SESSION['acc_error'])) { ?>
      <div class="alert_info active" style="background: #c43d3d;">
        <span><i class="fa-solid fa-triangle-exclamation"></i>ALERT!</span><?php echo $_SESSION['acc_error']; ?>
      </div>

    <?php }
    unset($_SESSION['acc_error']); ?>
    <?php if (isset($_SESSION['acc_success'])) { ?>
      <div class="alert_info active" style="background: #159e45">
        <span><i class="fa-solid fa-thumbs-up"></i> Success!</span><?php echo $_SESSION['acc_success']; ?>
      </div>
    <?php }
    unset($_SESSION['acc_success']); ?>
    <form action="insert" method="POST" enctype="multipart/form-data" class="signUp" id="submitForm">
      <h2><i class="fas fa-user-plus"></i> Register</h2>
      <div class="inputBox">
        <input type="text" name="username" placeholder="First-name Last-name" required>
        <i class="fas fa-user"></i>
        <label for="username">Username</label>
      </div>
      <div class="inputBox">
        <input type="email" name="email" placeholder="example@gmail.com" required>
        <i class="fas fa-envelope"></i>
        <label for="email">Email</label>
      </div>
      <div class="inputBox">
        <textarea type="text" name="user_bio" placeholder="introduce yourself.." required></textarea>
        <i class="fas fa-address-card"></i>
        <label for="address">Your Bio.</label>
      </div>
      <div class="inputBox">
        <input type="file" id="choosePP" name="profilepic" accept="image/*" placeholder="image must be .png,.jpg,.gif" hidden>
        <input type="button" id="chooseFile">
        <i class="fas fa-image"></i>
        <label for="profileimg">Profile Image</label>
      </div>
      <div class="inputBox">
        <input type="password" autocomplete="on" required>
        <i class="fas fa-lock"></i>
        <label for="password">Passowrd</label>
      </div>
      <div class="inputBox">
        <input type="password" name="password" minlength="5" maxlength="15" autocomplete="on" required>
        <i class="fas fa-lock"></i>
        <label for="cpassword">Confirm Password</label>
        <i class="fas fa-eye-slash" id="showHide"></i>
      </div>
      <div class="inputBox">
        <button type="submit" name="accountSubmit">submit</button>
      </div>
      <p>Already a memeber ?<a href="" class="login">Log In</a></p>
    </form>
    <form action="insert" enctype="multipart/form-data" method="POST" class="logIn">
      <h2><i class="fas fa-key"></i> Log In</h2>
      <div class="inputBox">
        <input type="email" name="email" required>
        <i class="fas fa-envelope"></i>
        <label for="email">Email</label>
      </div>
      <div class="inputBox">
        <input type="password" autocomplete="on" name="password" required>
        <i class="fas fa-lock"></i>
        <label for="password">Password</label>
        <i class="fas fa-eye-slash" id="showHide"></i>
      </div>
      <div class="inputBox">
        <button type="submit" name="checkacc">submit</button>
      </div>
      <p>Wanna create an account? <a href="" class="signup">Register</a></p>
    </form>
  </div>
</body>

</html>