<?php include_once "header.php";
echo '<link rel="stylesheet" href="additional/css/chatwith.css">';
echo '<script src="additional/script/chatwith.js" defer></script>'; 

function encryptId($id, $key)
  {
    $encryptedId = urlencode(base64_encode($id ^ $key));
    return $encryptedId;
  }
?>
<div class="chat">
  <div class="chat-container">
    <!-- -----------alert info start----- -->


    <!-- --------------alert info end-------------- -->
    <?php

    $query = mysqli_query($con, "SELECT chtId,date,ch_name,name,position,message FROM `chat` LEFT JOIN  `account` ON chat.fk_accId = account.accId");

    foreach ($query as $row) {
    ?>
      <div class="message" title="<?= $row['date']; ?>">
        <div class="msg">
          <h4><?= $row['ch_name']; ?></h4>
          <h4 title="<?= $row['position']; ?>"><?= $row['name']; ?></h4>
          <div class="chatNo"></div>
          <p><?= $row['message']; ?></p>
        </div>
        <div class="actionChat">
          <span>...</span>
          <div class="action-container">
            <!-- deleting process----------------------- -->
            <?php if (isset($_SESSION['logedIn']) && $_SESSION['logedIn'] === true) {
            ?>
              <a href="deletec<?php
                              $id = $row['chtId'];;
                              $key = 94325928321;
                              $encryptedId = encryptId($id, $key);
                              echo $encryptedId; ?>">
                <i class="fas fa-trash" title="delete"></i>
              </a>
            <?php } ?>
            <i class="fas fa-reply" title="reply"></i>
          </div>
        </div>
      </div>
    <?php }
    mysqli_close($con); ?>
  </div>

  <div class="input-container">
    <form action="insert" enctype="multipart/form-data" method="POST">
      <div class="input-box">
        <?php if (isset($_SESSION['logedIn']) && $_SESSION['logedIn'] === true) { ?>
        <?php } else { ?>
          <input type="text" name="name" minlength="5" placeholder="Enter Your Name" autocomplete="off" required>
        <?php } ?>
        <textarea name="message" minlength="10" placeholder="Type Your Message" required></textarea>
        <button type="submit" name="messageSmt" class="drp navigation-arrow">&#10148;</button>
      </div>
    </form>
  </div>
</div>