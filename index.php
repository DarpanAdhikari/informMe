<?php
include_once "header.php";
echo '<link rel="stylesheet" href="additional/css/index.css">';
echo ' <script src="additional/script/index.js" defer></script>';
echo '<script src="additional/script/update.js" defer></script>';
$con = mysqli_connect("localhost", "root", "", "informme");
$sql = "UPDATE navbar SET visitors = visitors+1";
$con->query($sql);
?>

<div class="container">
  <section class="topSection">
    <?php
    $select = mysqli_query($con, "SELECT * FROM post LEFT JOIN account ON post.fk_author = account.accId WHERE display != '0' AND postStat = '1' ORDER BY pId");
    if($select){
    if (mysqli_num_rows($select)>0) { ?>
      <div class="carousel-container">
        <div class="carousel">
          <?php
          foreach ($select as $row) {
            $img = $row['featureImg'];
            $key = 1527781;
            $iv = '123456789abcdefg';
            $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
          ?>
            <?php
            if ($row['display'] === "1" || $row['display'] === "2") {
            ?>
              <a href="<?php
                        if ($row['slug'] !== "") {
                          echo convertString($row['slug']);
                        } else {
                          echo convertString($row['title']);
                        }
                        ?>">
                <div class="card">
                  <div class="box">
                    <img loading="lazy" loading="lazy" src="images/postimage/<?= $image; ?>" alt="<?= $row['title']; ?>">
                    <div class="description">
                      <h1><?= $row['title']; ?></h1>
                      <p id="slideContent"><?= $row['metaDescription']; ?></p>
                    </div>
                  </div>
                  <div class="about-content">
                    <span class="UploadDate"><?= $row['postDate']; ?></span>
                    <span class="fas fa-eye">
                      <span class="specialNumber"><?= $row['viewer']; ?></span>
                    </span>
                    <span class="fas fa-heart">
                      <span class="specialNumber"><?= $row['likes']; ?></span>
                    </span>
                    <span class="fas fa-comment">
                      <?php
                      $pId = $row['pId'];
                      $query = mysqli_query($con, "SELECT COUNT(cmId) AS cmCount FROM comment WHERE fk_postId = '$pId'");
                      if (mysqli_num_rows($query) > 0) {
                        $comment = mysqli_fetch_assoc($query);
                      ?>
                        <span class="specialNumber"><?= $comment['cmCount']; ?></span>
                      <?php } else {
                        echo "<span>0</span>";
                      } }?>
                    </span>
                    <span class="author">By <?= $row['name']; ?></span>
                  </div>
                </div>
              </a>
          <?php }
          } }?>
        </div>
      </div>
      <div class="next-container">
        <?php $sno = 0;
        if ($select && mysqli_num_rows($select)>0) {
        foreach ($select as $row) {
            $img = $row['featureImg'];
            $key = 1527781;
            $iv = '123456789abcdefg';
            $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
        ?>
            <?php
            if ($row['display'] === "1" || $row['display'] === "3") {
              if($sno < 5){
            ?>
              <a href="<?php
                        if ($row['slug'] !== "") {
                          echo convertString($row['slug']);
                        } else {
                          $url = convertString($row['title']);
                          echo $url;
                        }
                        ?>">
                <div class="card">
                  <div class="box">
                    <img loading="lazy" loading="lazy" src="images/postimage/<?= $image; ?>" alt="<?= $row['title']; ?>">
                    <div class="description">
                      <h1><?= $row['title']; ?></h1>
                      <p><?= $row['metaDescription']; ?></p>
                    </div>
                  </div>
                  <div class="about-content">
                    <span class="UploadDate"><?= $row['postDate']; ?></span>
                    <span class="fas fa-eye">
                      <span class="specialNumber"><?= $row['viewer']; ?></span>
                    </span>
                    <span class="fas fa-heart">
                      <span class="specialNumber"><?= $row['likes']; ?></span>
                    </span>
                    <span class="fas fa-comment">
                      <?php
                      $pId = $row['pId'];
                      $query = mysqli_query($con, "SELECT COUNT(cmId) AS cmCount FROM comment WHERE fk_postId = '$pId'");
                      if (mysqli_num_rows($query) > 0) {
                        $comment = mysqli_fetch_assoc($query);
                      ?>
                        <span class="specialNumber"><?= $comment['cmCount']; ?></span>
                      <?php } else {
                        echo "<span>0</span>";
                      } ?>
                    </span>
                    <span class="author">By <?= $row['name']; ?></span>
                  </div>
                </div>
              </a>
        <?php }}$sno++;} ?>
      </div>
      <div class="text-writer">
        <span>Update</span>
        <p></p>
      </div>
    <?php } ?>

  </section>
  <section class="update-container">
    <div class="posts">
      <?php
      for ($i = 1; $i <= 5; $i++) {
        $updatePost = mysqli_query($con, "SELECT * FROM post LEFT JOIN account ON post.fk_author = account.accId WHERE postStat = '1' AND fk_category = '$i' ORDER BY pId DESC LIMIT 1");

        if ($updatePost && mysqli_num_rows($updatePost) > 0) {
          $row = mysqli_fetch_assoc($updatePost);
          $img = $row['featureImg'];
          $key = 1527781;
          $iv = '123456789abcdefg';
          $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
      ?>

          <a href="<?php
                    if (!empty($row['slug'])) {
                      echo convertString($row['slug']);
                    } else {
                      echo convertString($row['title']);
                    }
                    ?>">
            <div class="card">
              <div class="box">
                <img loading="lazy" src="images/postimage/<?= $image; ?>" alt="<?= $row['title']; ?>">
                <div class="description">
                  <h1><?= $row['title']; ?></h1>
                  <p>"<?= $row['metaDescription']; ?>"</p>
                </div>
              </div>
              <div class="about-content">
                <span class="UploadDate"><?= $row['postDate']; ?></span>
                <span class="fas fa-eye">
                  <span class="specialNumber"><?= $row['viewer']; ?></span>
                </span>
                <span class="fas fa-heart">
                  <span class="specialNumber"><?= $row['likes']; ?></span>
                </span>
                <span class="fas fa-comment">
                  <?php
                  $pId = $row['pId'];
                  $query = mysqli_query($con, "SELECT COUNT(cmId) AS cmCount FROM comment WHERE fk_postId = '$pId'");
                  if (mysqli_num_rows($query) > 0) {
                    $comment = mysqli_fetch_assoc($query);
                  ?>
                    <span class="specialNumber"><?= $comment['cmCount']; ?></span>
                  <?php } else {
                    echo "<span>0</span>";
                  } ?>
                </span>
                <span class="author">By <?= $row['name']; ?></span>
              </div>
            </div>
            <div class="view-all-btn"><i class="fas fa-book-open"></i> Read more <i class="fas fa-arrow-right"></i></div>
          </a>

      <?php }
      } ?>

    </div>

    <!-- posts has been ended -->
    <div class="make-easy">
      <div class="popular-post">
        <div class="title">Most Viewed post</div>
        <div class="contents">
          <?php
          $query = mysqli_query($con, "SELECT * FROM post LEFT JOIN account ON post.fk_author = account.accId WHERE postStat = '1' ORDER BY likes DESC LIMIT 5");
          if ($query) {
            foreach ($query as $row) {
              $img = $row['featureImg'];
              $key = 1527781;
              $iv = '123456789abcdefg';
              $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
          ?>
              <a href="<?php
                        if ($row['slug'] !== "") {
                          echo convertString($row['slug']);
                        } else {
                          $url = convertString($row['title']);
                          echo $url;
                        }
                        ?>">
                <div class="content">
                  <img loading="lazy" src="images/postimage/<?= $image; ?>" alt="<?= $row['title']; ?>">
                  <div>
                    <h2><?= $row['title']; ?></h2>
                    <p class="desc"><?= $row['metaDescription']; ?></p>
                  </div>
                  <div class="about-content">
                    <span class="UploadDate"><?= $row['postDate']; ?></span>
                    <span class="fas fa-eye">
                      <span class="specialNumber"><?= $row['viewer']; ?></span>
                    </span>
                    <span class="fas fa-heart">
                      <span class="specialNumber"><?= $row['likes']; ?></span>
                    </span>
                    <span class="fas fa-comment">
                      <?php
                      $pId = $row['pId'];
                      $query = mysqli_query($con, "SELECT COUNT(cmId) AS cmCount FROM comment WHERE fk_postId = '$pId'");
                      if (mysqli_num_rows($query) > 0) {
                        $comment = mysqli_fetch_assoc($query);
                      ?>
                        <span class="specialNumber"><?= $comment['cmCount']; ?></span>
                      <?php } else {
                        echo "<span>0</span>";
                      } ?>
                    </span>
                    <span class="author">By <?= $row['name']; ?></span>
                  </div>
                </div>
              </a>
          <?php
            }
            mysqli_free_result($query);
          } ?>
        </div>
      </div>
      <div class="category-container">
        <div class="items">
          <div class="title">Catogories</div>
          <?php
          $get = mysqli_query($con, "SELECT * FROM `navbar` WHERE nav_status != '0'");
          if ($get) {
            foreach ($get as $row) {
          ?>
              <a href="<?= $row['navigation']; ?>">
                <div class="bar">
                  <h3><?= $row['navigation']; ?> Post</h3>
                  <?php
                  $navi = $row['row'];
                  $countSet = mysqli_query($con, "SELECT COUNT(pId) AS count FROM post WHERE fk_category = '$navi'");
                  if ($countSet) {
                    $count = mysqli_fetch_assoc($countSet);
                    echo "<span>" . $count['count'] . "</span>";
                  }
                  ?>

                </div>
              </a>
          <?php }
          } ?>
          <a href="Gallary">
            <div class="bar">
              <h3>Gallary</h3>
              <span class="fas fa-images"></span>
            </div>
          </a>
        </div>
      </div>
      <div class="users">
        <div class="title">Team</div>
        <div class="items team" id="team">
        </div>
      </div>
    </div>
  </section>
</div>
<?php include "footer.php";
?>
  <script>
  function setSocialMediaMetadata() {
  var head = document.head;
  var metaTags = head.getElementsByTagName('meta');
  var existingMetaTags = [];

  for (var i = 0; i < metaTags.length; i++) {
      var property = metaTags[i].getAttribute('property');
      var name = metaTags[i].getAttribute('name');

      if ((property && property.indexOf('og:') === 0) || (name && name.toLowerCase() === 'author')) {
          existingMetaTags.push(metaTags[i]);
      }
  }

  function createMetaTag(propertyOrName, content) {
      var metaTag = document.createElement('meta');
      if (propertyOrName.indexOf('og:') === 0) {
          metaTag.setAttribute('property', propertyOrName);
      } else {
          metaTag.setAttribute('name', propertyOrName);
      }
      metaTag.setAttribute('content', content);
      return metaTag;
  }
  let  navigations = document.querySelectorAll('.navbar .nav-links:nth-child(2) a');
     const textContentArray = [];
       navigations.forEach(element => {
            textContentArray.push(element.textContent);
        });

  var author = createMetaTag(`author`, document.title);
  var description = createMetaTag(`description`,`<?= $metaDescritpion?>`);
  var ogTitle = createMetaTag(`og:title`, document.title);
  var ogDescription = createMetaTag(`og:description`, `<?= $metaDescritpion?>`);
  var ogImage = createMetaTag(`og:image`, `additional/addition/homePage.png`);
  var keywords = createMetaTag(`keywords`, textContentArray+`, new post`);

  existingMetaTags.forEach(function (existingTag) {
      head.removeChild(existingTag);
  });
  head.appendChild(ogTitle);
  head.appendChild(ogDescription);
  head.appendChild(ogImage);
  head.appendChild(author);
  head.appendChild(description);
  head.appendChild(keywords);
}
setSocialMediaMetadata();

  </script>