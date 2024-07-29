<?php include "admin_panel.php";
echo '<link rel="stylesheet" href="additional/css/admin_home.css">';
$con = mysqli_connect("localhost", "root", "", "informme");
function convertString($name)
{
  $sentence = $name;
  $encodedSentence = str_replace(" ", "-", $sentence);
  return $encodedSentence;
}
?>
<div class="container">
  <div class="highlights">
    <a href="posts">
      <div class="post info" title="post">
        <h1>Post</h1>
        <i class="fas fa-file-zipper"></i>
        <?php
            $user = mysqli_query($con, "SELECT COUNT(*) AS count FROM post WHERE postStat = '1'");
            if ($user) {
              $user = mysqli_fetch_assoc($user);
              echo "<h1>" . $user['count'] . "</h1>";
            } else {
              echo "<h1>0</h1>";
            } ?>
      </div>
    </a>
    <a href="draft">
      <div class="draft info" title="draft">
        <h1>Draft</h1>
        <i class="fas fa-file"></i>
        <?php
            $user = mysqli_query($con, "SELECT COUNT(*) AS count FROM post WHERE postStat = '0'");
            if ($user) {
              $user = mysqli_fetch_assoc($user);
              echo "<h1>" . $user['count'] . "</h1>";
            } else {
              echo "<h1>0</h1>";
            } ?>
      </div>
    </a>

    <div class="user info" title="users">
      <h1>Users</h1>
      <i class="fas fa-user"></i>
      <?php

      $user = mysqli_query($con, "SELECT COUNT(*) AS count FROM account");
      if ($user) {
        $user = mysqli_fetch_assoc($user);
        echo "<h1>" . $user['count'] . "</h1>";
      } else {
        echo "<h1>0</h1>";
      } ?>
    </div>

    <?php
    $get = mysqli_query($con, "SELECT * FROM `navbar` WHERE nav_status != '0'");
    if ($get) {
      foreach ($get as $row) {
    ?>
        <div class="total-view info" title="views">
          <a href="<?= $row['navigation'] ?>">
          <h1><?= $row['navigation'] ?><sup style="font-size:10px;"><tt>Visitors</tt></sup></h1>
          <i class="fas fa-eye"></i>
          <h1><?= $row['pageVisit'] ?></h1>
          </a>
        </div>
    <?php }
    }
    ?>

  </div>
  <div class="details">
    <?php

    $result =  mysqli_query($con, "SELECT * FROM account");
    if ($result) {
      foreach ($result as $row) {
        $img = $row['image'];
        $key = 15227;
        $iv = '123456789abcdefg';
        $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);

    ?>
        <div class="user detail">
          <span class="drp close">&times;</span>
          <div class="user-details">
            <div class="about-author">
              <?php if (($row['image'] !== "") && ($row['image'] !== null)) { ?>
                <img src="<?= 'images/profile_img/', $image; ?>" alt="">
              <?php } else { ?>
                <img src="additional/addition/default.png" alt="">
              <?php } ?>
              <div class="about">
                <h3>Author Name</h3>
                <p><?= $row['name']; ?></p>
              </div>
              <div class="about">
                <h3>Author Post</h3>
                <p><?= $row['position']; ?></p>
              </div>
              <div class="about">
                <h3>Bio.</h3>
                <p><?= $row['bio']; ?></p>
              </div>
              <div class="about">
                <button id="dspPost">Article Post 50</button>
              </div>
              <div class="user-social-id">
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
          </div>
        </div>
        <div class="post">
          <?php
          $dataId = $row['accId'];
          $data =  mysqli_query($con, "SELECT * FROM post where fk_author = $dataId");
          foreach ($data as $get) {
            $img = $get['featureImg'];
            $key = 1527781;
            $iv = '123456789abcdefg';
            $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
          ?>
            <a href="<?php
                      if ($get['slug'] !== "") {
                        echo convertString($get['slug']);
                      } else {
                        echo convertString($get['title']);
                      }
                      ?>">
              <div class="content">
                <img src="images/postimage/<?= $image; ?>" alt="">
                <h3><?= $get['title']; ?></h3>
                <span class="fas fa-eye"><span><?= $get['viewer']; ?></span></span>
                <span class="fas fa-thumbs-up"><span><?= $get['likes']; ?></span></span>
                <span class="fas fa-comment">
                  <?php
                  $pId = $get['pId'];
                  $query = mysqli_query($con, "SELECT COUNT(cmId) AS cmCount FROM comment WHERE fk_postId = '$pId'");
                  if (mysqli_num_rows($query) > 0) {
                    $comment = mysqli_fetch_assoc($query);
                  ?>
                    <span class="specialNumber"><?= $comment['cmCount']; ?></span>
                  <?php } else {
                    echo "<span>0</span>";
                  } ?>
                </span>
              </div>
              <hr>
            </a>
          <?php } ?>
        </div>
    <?php }
    }
    mysqli_close($con); ?>
  </div>
</div>
<script>
  const sidebarItems = document.querySelectorAll(".sidebar li");

  sidebarItems.forEach(item => {
    item.classList.remove("active");
  });

  const IdButton = document.querySelectorAll("#dspPost"),
    postList = document.querySelectorAll(".details .post"),
    userCloseBtn = document.querySelectorAll(".user.detail .drp.close");
  let useInfo = document.querySelector('.user.info'),
    teamDetails = document.querySelectorAll(".user.detail");
  IdButton.forEach((openList, index) => {
    openList.addEventListener("click", () => {
      postList[index].classList.toggle("active");
    });
  });

  userCloseBtn.forEach((close, index) => {
    close.addEventListener("click", () => {
      teamDetails[index].remove('active');
    });
  })

  useInfo.onclick = () => {
    teamDetails.forEach(openDet => {
      openDet.classList.add("active");
    });
  }
</script>