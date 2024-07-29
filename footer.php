<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <link rel="stylesheet" href="additional/css/footer.css">
  <script src="additional/script/footer.js" defer></script>
</head>

<body>
  <footer>
    <div class="row">
      <div class="col">
        <h3>About Us</h3>
        <?php
        $get = mysqli_query($con, "SELECT * FROM navbar");
        if ($get && mysqli_num_rows($get)>1) {
          $content = mysqli_fetch_assoc($get);
          if($content['about_us']!==""){
            $metaDescritpion = $content['about_us'];
        ?>
          <p id="aboutPage"><?= $content['about_us']; ?></p>
        <?php }} ?>
      </div>
      <div class="col">
        <h3>Quick Links</h3>
        <ul>
          <li><a href="Home">Home</a></li>

          <?php if ($get && mysqli_num_rows($get)>1) {
            foreach ($get as $row) { ?>
              <?php
              if ($row['navigation'] !== "") {
                if($row['nav_status'] !=='0'){
              ?>
                <li><a href="<?= $row['navigation']; ?>"> <?= $row['navigation']; ?>
                  <?php }} else {
                  echo "<li><a href=''>navigation";
                }
                  ?></a></li>
            <?php }
          } ?>
            <li><a href="Gallary">Gallary</a></li>
        </ul>
      </div>
      <div class="col">
        <h3>Connect With Us</h3>
        <div class="WebLinks">
        <div class="visitors-data">
          <?php
          $get = mysqli_query($con, "SELECT * FROM navbar");
          if ($get && mysqli_num_rows($get)>1) {
            $row = mysqli_fetch_assoc($get);
            if($row['visitors'] !== ""){
            echo "<span class='specialNumber'>" . $row['visitors'] . " </span> HIT";}else {
              echo "<span>0</span>HIT";
            }
          ?>
        </div>
        <div class="social-icon">
          <a href="<?=$row['facebook']; ?>"><i class="fab fa-facebook"></i></a>
          <a href="<?=$row['youtube'] ?>"><i class="fab fa-youtube"></i></a>
          <a href="<?=$row['twitter'] ?>"><i class="fab fa-twitter"></i></a>
          <a href="<?=$row['pinterest'] ?>"><i class="fab fa-pinterest"></i></a>
        </div>
        <?php } ?>
        <div class="download-app">
          <button id="install-button"><i class="fab fa-app-store-ios"></i></button>
        </div>
        </div>
      </div>
    </div>
    <hr>
    <div class="bottom-footer">
      <span>copyright <span class="drp copyright">&#169;</span> <a href="Home"><img src="additional/addition/logo512x512.png" width="20px" height="20px"><?php if($PageName !== "") echo $PageName['page_name'];?> </a>|| all right reserved</span>
    </div>
  </footer>
  <script>
document.addEventListener('DOMContentLoaded', () => {
  const installButton = document.getElementById('install-button');
  let deferredPrompt;

  if ('BeforeInstallPromptEvent' in window) {
    window.addEventListener('beforeinstallprompt', (event) => {
      event.preventDefault();
      deferredPrompt = event;
      showInstallButton();
    });
  }

  function showInstallButton() {
    // Check if the PWA is not installed (not in standalone mode)
    if (!window.matchMedia('(display-mode: standalone)').matches && !window.navigator.standalone) {
      installButton.style.display = 'block'; // Show the install button
    } else {
      installButton.style.display = 'none'; // Hide the install button
    }

    installButton.addEventListener('click', () => {
      if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then((choiceResult) => {
          if (choiceResult.outcome === 'accepted') {
            console.log('User accepted the install prompt.');
            deferredPrompt = null; // Reset the deferredPrompt
          }
        });
      }
    });
  }
});

  </script>
</body>

</html>