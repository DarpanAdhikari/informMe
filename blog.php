<?php
$con = mysqli_connect("localhost", "root", "", "informme");

$heading = "";
$user = "";
if ((!isset($_GET['pageId'])) && (!isset($_GET['userId']))) {
  header('location:Home');
  exit;
} else {
  if ((isset($_GET['pageId']))) {
    $pid = $_GET['pageId'];
    $sql = "UPDATE navbar SET pageVisit = pageVisit+1 WHERE row = '$pid'";
    $con->query($sql);
    $get = mysqli_query($con, "SELECT * FROM `navbar` WHERE row = '$pid'");
    if ($get) {
      $row = mysqli_fetch_assoc($get);
      if ($row['nav_status'] === "1") {
        $nav = $row['navigation'];
        $heading = $nav;
      }
    }
  } else {
    $email = base64_decode($_GET['userId']);
    $get = mysqli_query($con, "SELECT * FROM `account` WHERE email = '$email'");
    if ($get) {
      $row = mysqli_fetch_assoc($get);
      $img = $row['image'];
      $key = 15227;
      $iv = '123456789abcdefg';
      $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
      $nav = $row['name'];
      $user = $row['name'];
      $bio = $row['bio'];
      $keywords = $row['position'];
      if($image !== ""){
        $pageImage = 'images/profile_img/'.$image;
      }else{
        $pageImage = 'additional/addition/default.png';
      }
      $acid =  $row['accId'];
    }
  }
}
?>
<?php include_once "header.php"; 
echo '
<link rel="stylesheet" href="additional/css/blog.css">
<script src="additional/script/scroll.js" defer></script>
<link rel="stylesheet" type="text/css" href="additional/css/share.css">
<script src="additional/script/share.js" defer></script>
<script src="additional/script/update.js" defer></script>
';?>

<div class="container">
  <section class="main-post">
    <h1 class="sectionTitle main"><?= $nav; ?> Post</h1>
    <div class="main-container">
      <?php
      if (isset($_GET['pageId'])) {
        $article = mysqli_query($con,  "SELECT * FROM post 
                       LEFT JOIN  `account` ON post.fk_author = account.accId  WHERE post.fk_category = '$pid' AND postStat = '1' ORDER BY pId DESC");
      } else {
        $article = mysqli_query($con,  "SELECT * FROM post 
                         LEFT JOIN  `account` ON post.fk_author = account.accId  WHERE post.fk_author = '$acid' AND postStat = '1' ORDER BY pId DESC");
      }
      if ($article) {
        foreach ($article as $row) {
          $img = $row['featureImg'];
          $key = 1527781;
          $iv = '123456789abcdefg';
          $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
      ?>
          <a href=" <?php
                    if ($row['slug'] !== "") {
                      echo convertString($row['slug']);
                    } else {
                      $url = convertString($row['title']);
                      echo $url;
                    }
                    ?>">
            <div class="card">
              <div class="box">
                <picture>
                  <img loading="lazy" src="images/postimage/<?= $image; ?>" alt="">
                </picture>
                <div class="description">
                  <h1><?= $row['title']; ?></h1>
                  <p>"<?= $row['metaDescription']; ?>"</p>
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
            </div>
          </a>

      <?php }
      } ?>

    </div>
    <i class="fa-solid fa-arrow-down-wide-short" title="scroll down"></i>
  </section>
  <section class="suggestion-post">
    <h1 class="sectionTitle">Recomended</h1>
    <?php
    if ($heading !== "") {
      $select = mysqli_query($con, "SELECT * FROM post LEFT JOIN account ON post.fk_author = account.accId
      WHERE post.fk_category != '$pid' AND (title LIKE '%$nav%' OR keywords LIKE '%$nav%' OR tags LIKE '%$nav%' OR content LIKE '%$nav%') AND postStat = '1' ORDER BY pId DESC LIMIT 3");
      if ($select) {
        foreach ($select as $recom) {
          $img = $recom['featureImg'];
          $key = 1527781;
          $iv = '123456789abcdefg';
          $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
    ?>
          <a href="<?php
                    if ($recom['slug'] !== "") {
                      echo convertString($recom['slug']);
                    } else {
                      $url = convertString($recom['title']);
                      echo $url;
                    }
                    ?>">
            <div class="card">
              <div class="box">
                <picture>
                  <img loading="lazy" src="images/postimage/<?= $image; ?>" alt="">
                </picture>
                <div class="description">
                  <h1><?= $recom['title']; ?></h1>
                  <p><?= $recom['metaDescription']; ?></p>
                </div>
                <div class="about-content">
                  <span class="UploadDate"><?= $recom['postDate']; ?></span>
                  <span class="fas fa-eye">
                    <p class="specialNumber"><?= $recom['viewer']; ?></p>
                  </span>
                  <span class="fas fa-heart">
                    <span class="specialNumber"><?= $recom['likes']; ?></span>
                  </span>
                  <span class="fas fa-comment">
                    <?php
                    $pId = $recom['pId'];
                    $query = mysqli_query($con, "SELECT COUNT(cmId) AS cmCount FROM comment WHERE fk_postId = '$pId'");
                    if (mysqli_num_rows($query) > 0) {
                      $comment = mysqli_fetch_assoc($query);
                    ?>
                      <span class="specialNumber"><?= $comment['cmCount']; ?></span>
                    <?php } else {
                      echo "<span>0</span>";
                    } ?>
                  </span>
                  <span class="author">By <?= $recom['name']; ?></span>
                </div>
              </div>
            </div>
          </a>
    <?php }
      }
    } ?>

  </section>
  <section class="category-container">
    <h1 class="sectionTitle">Category</h1>
    <div class="items">
      <?php
      if (isset($_GET['pageId'])) {
        $id = $_GET['pageId'];
        $get = mysqli_query($con, "SELECT navigation,row FROM `navbar` WHERE row != '$id' AND nav_status != '0'");
      } else {
        $get = mysqli_query($con, "SELECT * FROM `navbar` WHERE nav_status != '0'");
      }
      if ($get) {
        foreach ($get as $row) {
      ?>
          <a href="<?= $row['navigation']; ?>">
            <div class="bar">
              <h3><?= $row['navigation']; ?> Post</h3>
              <?php
              $navi = $row['row'];
              $countSet = mysqli_query($con, "SELECT COUNT(pId) AS count FROM post WHERE fk_category = '$navi' AND postStat != '0'");
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
    <h1 class="sectionTitle">Team</h1>
    <div class="items team" id="team">
    </div>
  </section>
</div>
<section class="Scroll-items">
  <div class="card-container">
    <?php
    if ($heading === "") {
      $select = mysqli_query($con, "SELECT * FROM post LEFT JOIN account ON post.fk_author = account.accId
      WHERE post.fk_author != '$acid' AND postStat = '1'");
    }else{
      $select = mysqli_query($con, "SELECT * FROM post LEFT JOIN account ON post.fk_author = account.accId
                                  WHERE post.fk_category != '$pid' AND postStat = '1'");
    }
      
      if ($select) {
        foreach ($select as $all) {
          $img = $all['featureImg'];
          $key = 1527781;
          $iv = '123456789abcdefg';
          $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
    ?>
          <a href="<?php
                    if ($all['slug'] !== "") {
                      echo convertString($all['slug']);
                    } else {
                      $url = convertString($all['title']);
                      echo $url;
                    }
                    ?>">
            <div class="card">
              <div class="box">
                <picture>
                  <img loading="lazy" src="images/postimage/<?= $image; ?>" alt="">
                </picture>
                <div class="description">
                  <h1><?= $all['title']; ?></h1>
                  <p><?= $all['metaDescription']; ?></p>
                </div>
              </div>
              <div class="about-content">
                <span class="UploadDate"><?= $all['postDate']; ?></span>
                <span class="fas fa-eye">
                  <span class="specialNumber"><?= $all['viewer']; ?></span>
                </span>
                <span class="fas fa-heart">
                  <span class="specialNumber"><?= $all['likes']; ?></span>
                </span>
                <span class="fas fa-comment">
                  <?php
                  $pId = $all['pId'];
                  $query = mysqli_query($con, "SELECT COUNT(cmId) AS cmCount FROM comment WHERE fk_postId = '$pId'");
                  if (mysqli_num_rows($query) > 0) {
                    $comment = mysqli_fetch_assoc($query);
                  ?>
                    <span class="specialNumber"><?= $comment['cmCount']; ?></span>
                  <?php } else {
                    echo "<span>0</span>";
                  } ?>
                </span>
                <span class="author">By <?= $all['name']; ?></span>
              </div>
            </div>
          </a>
    <?php }
      }?>
  </div>
</section>
<?php
if (isset($_GET['userId'])) {
  echo '<div class="share-field">
	<div class="share-icon">
		<i class="fas fa-share-alt" title="Share"></i>
	</div>
	<div class="share-data">
		<div class="social-share ">
			<header>
				<span>Share Options</span>
				<span class="close">&times;</span>
			</header>
			<div class="main-field">
				<p>Share this link via..</p>
				<ul class="icons">
					<a target="_blank" class="facebook"><i class="fab fa-facebook"></i></a>
					<a target="_blank" class="messenger"><i class="fab fa-facebook-messenger"></i></a>
					<a target="_blank" class="whatsapp"><i class="fa-brands fa-whatsapp"></i></a>
					<a target="_blank" class="twitter"><i class="fa-brands fa-twitter"></i></a>
					<a target="_blank" class="pinterest"><i class="fa-brands fa-pinterest"></i></a>
					<a target="_blank" class="telegram"><i class="fab fa-telegram-plane"></i></a>
					<a target="_blank" class="reddit"><i class="fab fa-reddit"></i></a>
					<a target="_blank" class="envelope"><span class="">&#9993;</span></a>
				</ul>
				<p>Or copy link</p>
				<div class="copy-field">
					<i class="fa fa-link"></i>
					<input type="text" id="copy-link">
					<button id="copy-btn">COPY</button>
				</div>
			</div>
		</div>
	</div>
</div>';
}
?>
<?php
if (isset($_GET['pageId'])) {
  echo '
  <script>
 const navItems = document.querySelectorAll(".navbar .nav-links:nth-child(2) li a");
let heading = document.querySelector(".sectionTitle.main").textContent;
let mainHeading = heading.split(" ");
const firstWordWithoutSpaces = mainHeading[0].replace(/\s+/g, "");

navItems.forEach(navitem => {
  navitem.classList.remove("active");
  let navigation = navitem.textContent;
  const navigationWithoutSpaces = navigation.replace(/\s+/g, "");
  if (navigationWithoutSpaces.toUpperCase() === firstWordWithoutSpaces.toUpperCase()) {
    navitem.classList.add("active");
  }
});
document.title += " // " + mainHeading[0];
  </script>
  ';
  
} else {
  echo '<script>
  let heading = document.querySelector(".sectionTitle.main").textContent,
    mainHeading = heading.split(" ");
    document.querySelector(".sectionTitle.main").textContent = mainHeading[0]+"`s post";
    document.title += " // "+mainHeading[0]+"`s post";
  const navItems = document.querySelectorAll(".navbar .nav-links:nth-child(2) li a");
    navItems.forEach(navitem => {
      navitem.classList.remove("active");
    });
    </script>';
}
include "footer.php";
mysqli_close($con); ?>

<?php if($user !== ""){?>
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
    var ogTitle = createMetaTag('og:title', '<?= $user; ?>');
    var ogDescription = createMetaTag('og:description', '<?= $bio; ?>');
    var ogImage = createMetaTag('og:image', 'images/profile_img/<?= $pageImage; ?>');
    var author = createMetaTag('author', '<?= $user; ?>');
    var description = createMetaTag('description', '<?= $bio; ?>');
    var keywords = createMetaTag('keywords', '<?= $user; ?>, <?= $keywords;?>, new post');

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
<?php }else{?>
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
<?php }?>