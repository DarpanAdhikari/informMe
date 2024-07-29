<?php session_start();
if (!isset($_SESSION["logedIn"]) && $_SESSION["logedIn"] !== true) {
  header('location:account');
}
$con = mysqli_connect("localhost", "root", "", "informme");
?>
<!DOCTYPE html>
<html>

<head>
  <title>DashBoard</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" type="text/css" href="additional/css/admin_panel.css">
  <link rel="icon" href="additional/addition/logo512x512.png">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" defer></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.6.0/tinymce.min.js"></script>
  <script src="additional/script/dashboard.js" defer></script>
  <script src="additional/script/read.js" defer></script>
</head>

<body>
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

  <div class="navbar-toggle">
    <span class="drp bar-cr">&#9781;</span>
    <h3> <img src="additional/addition/logo192x192.png" width="50px" height="50px" alt=""></h3>
  </div>

  <div class="sidebar">
    <div class="heading">
      <a href="admin-panel" style="text-decoration: none;">
        <h3>DashBoard</h3>
      </a>
      <?php
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
          <div class="profile" title="<?= $row['position']; ?>">
            <?php if (($row['image'] !== "") && ($row['image'] !== null)) { ?>
              <img src="<?= 'images/profile_img/', $image; ?>" alt="profile pic">
            <?php } else { ?>
              <img src="additional/addition/default.png" alt="profile pic">
            <?php } ?>
            <h2 style="color: #fff;"><?= $row['name']; ?></h2>
          </div>
      <?php }
      } ?>
    </div>
    <hr>
    <ul class="navbar">
      <a href="Home" title="ctrl+h">
        <li class="active"><i class="fas fa-home"></i> Go Back</li>
      </a>
      <li class="posted"><i class="fas fa-database"></i> Posted
        <ul>
          <a href="posts" class="active" title="ctrl+/">
            <li><i class="fas fa-receipt"></i> Posts</li>
          </a>
          <a href="draft">
            <li><i class="fas fa-clock"></i> Draft</li>
          </a>
          <a href="comments">
            <li><i class="fas fa-comments"></i> Comments</li>
          </a>
          <a href="uplimg">
            <li><i class="fas fa-images"></i> Images</li>
          </a>
        </ul>
      </li>
      <a href="new-post" title="ctrl+/">
        <li><i class="fas fa-plus"></i> Add (Posts / Draft)</li>
      </a>
      <?php
      $id = $_SESSION['acc_id'];
      $checkAdmin = mysqli_query($con, "SELECT * FROM account where accId = '$id'");
      $displayManagement = false;
      if (mysqli_num_rows($checkAdmin) > 0) {
        $row = mysqli_fetch_assoc($checkAdmin);
        $splitData = explode(" ", $row['position']);
        foreach ($splitData as $value) {
          if ((strtoupper('admin') === strtoupper($value)) || (strtoupper('editor') === strtoupper($value))) {
            $displayManagement = true;
            break;
          }
        }
        if ($displayManagement) {
      ?>
          <li class="management"><i class="fas fa-list-check"></i> Management
            <ul class="list">
              <a href="nav-edit">
                <li><i class="fas fa-edit"></i> <span>Edit page</span></li>
              </a>
              <a href="users">
                <li><i class="fas fa-users"></i> <span>Accounts</span></li>
              </a>
              <?php if ((strtoupper('admin') === strtoupper($value))) { ?>
                <a href="navigations">
                  <li><i class="fas fa-warning" style="color:red;"></i> <span>Navigations</span></li>
                </a>
              <?php } ?>
              <a href="messages">
                <li><i class="fas fa-message"></i> <span>Chat</span></li>
              </a>
              <a href="download" onclick="return confirm('Are you sure you want to download?')">
                <li><i class="fas fa-download"></i> Database</li>
              </a>
            </ul>
          </li>
      <?php
        }
      }
      ?>

    </ul>
  </div>
</body>

</html>