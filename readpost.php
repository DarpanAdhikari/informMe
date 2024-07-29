<?php
$con = mysqli_connect("localhost", "root", "", "informme");

if (isset($_SERVER['REQUEST_URI'])) {
    $url = $_SERVER['REQUEST_URI'];
    $url = trim($url, '/');
    $characters = array('.', '>', '=', '-', '*', '/', '+', '%20');
    $fUrl = str_replace($characters, " ", $url);

    if (strpos($fUrl, "websites magazine") !== false) {
    $fUrl = str_replace("websites magazine", "", $fUrl);
    }
    if (substr($fUrl, 0, 1) === ' ') {
        $fUrl = substr($fUrl, 1);
    }
}
function encryptId($id, $key)
{
    $encryptedId = urlencode(base64_encode($id ^ $key));
    return $encryptedId;
}
include_once "header.php";
echo '
<link rel="stylesheet" type="text/css" href="additional/css/readpost.css">
<script src="additional/script/readpost.js" defer></script>
<script src="additional/script/read.js" defer></script>
<link rel="stylesheet" type="text/css" href="additional/css/share.css">
<script src="additional/script/share.js" defer></script>
';
?> 


<main>
    <article>
        <div id="mainArticle">

            <?php
            $heading = "";
            $contId = "";
            $stmt = mysqli_prepare($con, "SELECT * FROM post 
                                LEFT JOIN `account` ON post.fk_author = account.accId 
                                WHERE (title = ? OR REPLACE(slug, '-', ' ') = ?
                                OR keywords LIKE ? OR metaDescription LIKE ? OR content LIKE ?) AND postStat = '1' ORDER BY pId DESC");
            $searchTerm = '%' . $fUrl . '%';
            mysqli_stmt_bind_param($stmt, "sssss", $fUrl, $fUrl, $searchTerm, $searchTerm, $searchTerm);
            mysqli_stmt_execute($stmt);
            $article = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($article) > 0) {
                $row = mysqli_fetch_assoc($article);
                $contId = $row['pId'];
                mysqli_query($con, "UPDATE post SET viewer = viewer+1 WHERE pId = '$contId'");
                $img = $row['featureImg'];
                $key = 1527781;
                $iv = '123456789abcdefg';
                $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);

                $heading = $row['title'];
                $author = $row['name'];
                $pageImage = $image;
                $keywords = $row['keywords'];
                $tags = $row['tags'];
                $metaDescription = $row['metaDescription'];
            ?>

                <div class="heading">
                    <h2 class="titleOfArticle"><?= $row['title']; ?></h2>
                    <p>"<?= $row['metaDescription']; ?>"</p>
                </div>
                <div class="react-love">
                    <span class="fas fa-heart"></span>
                </div>
                <picture class="article">
                    <img loading="lazy" src="images/postimage/<?= $image; ?>" alt="<?= $row['title']; ?>">
                </picture>
                <p><?= $row['content']; ?></p>
        </div>

        <div class="comment-section">
            <h3>Leave your comment here:</h3>
            <form action="">
                <input type="text" name="post" id="post" value="<?php $key = 987654393;
                                                                echo  encryptId($contId, $key); ?>" hidden>
                <?php if (!isset($_SESSION['logedIn'])) { ?>
                    <div class="input-box">
                        <label><i class="fas fa-user"></i>Name</label>
                        <input type="text" name="reader" id="reader" placeholder="Your name please*" minlength="3" required>
                    </div>
                <?php } ?>
                <div class="input-box">
                    <label><i class="fas fa-comment"></i>Comment</label>
                    <textarea type="text" name="comment" id="comment" placeholder="Your comment....*" required></textarea>
                </div>
                <div class="input-box">
                    <button type="submit"><i class="fas fa-comment"></i><span class="drp navigation-arrow">&#10148;</span></button>
                </div>
            </form>
        </div>
        <div class="reaction">
            <hr>
            <h3>Visitors Reaction</h3>
            <div class="reactIcon">
                <span class="far fa-heart" title="Like" data-like="<?php $key = 987654393;
                                                                    echo  encryptId($contId, $key); ?>">
                    <div class="badge"><?= $row['likes']; ?></div>
                </span>
                <span class="fas fa-comments" title="comments">
                    <div class="badge"></div>
                </span>
                <?php if ($row['email'] !== "" && $row['email'] !== null) { ?>
                    <div class="author-details">
                        <span class="fas fa-user-tag"></span>
                        <div class="about-author">
                                <?php
                                $img = $row['image'];
                                $key = 15227;
                                $iv = '123456789abcdefg';
                                $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
                                ?>
                                <?php if (($row['image'] !== "")) { ?>
                                    <img loading="lazy" src="<?= 'images/profile_img/', $image; ?>" alt="<?= $row['title']; ?>">
                                <?php } else { ?>
                                    <img loading="lazy" src="additional/addition/default.png" alt="Profile picture">
                                <?php } ?>
                            <div class="about">
                                <h3><?= $row['name']; ?></h3>
                                <p><?= $row['position']; ?></p>
                            </div>
                            <div class="about">
                                <h3>Bio.</h3>
                                <p><?= $row['bio']; ?></p>
                            </div>

                            <div class="user-social-id">
                                <p>
                                    <a href="<?= $row['facebook']; ?>" target="_blank">
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
                <?php } ?>
            </div>
        </div>
        <div class="comment-view">
            <div class="comments">
                <?php
                $comments = mysqli_query($con, "SELECT * FROM comment LEFT JOIN `account` ON comment.fk_user = account.accId WHERE fk_postId = '$contId'");
                if ($comments) {
                    foreach ($comments as $row) {
                        $image = ($row['image'] !== "") ? openssl_decrypt($row['image'], 'AES-256-CBC', 15227, 0, '123456789abcdefg') : "additional/addition/default.png";
                ?>
                        <div class="comment-item">
                            <?php if (($row['image'] !== "") && $row['commentor'] === "") { ?>
                                <?php if (($row['image'] !== "")) { ?>
                                    
                                    <img loading="lazy" src="<?= 'images/profile_img/' . $image; ?>" alt="<?= $row['name']; ?>">
                                    
                                <?php } else { ?>
                                    <img loading="lazy" src="additional/addition/default.png" alt="Profile picture">
                                <?php } ?>
                                <div>
                                    <h4><?= $row['name']; ?></h4>
                                <?php } else { ?>
                                    <img loading="lazy" src="additional/addition/default.png" alt="Profile picture">
                                    <div>
                                        <h4><?= $row['commentor']; ?></h4>
                                    <?php } ?>
                                    <p><?= $row['comment']; ?></p>
                                    </div>
                                    <div>
                                        <span class="UploadDate"><?= $row['cmtDate']; ?></span>
                                        <div class="option" title="more option">
                                            <?php if(isset($_SESSION['acc_id'])){?>
                                                ...<a href="<?php $id = $row['cmId'];
                                                        $key = 98765439;
                                                        $encryptedId = encryptId($id, $key);
                                                        echo 'delete-Comment' . $encryptedId; ?>">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                        <?php  }
                } ?>
                        </div>
            </div>

        <?php } else {
                echo "
 <article>
  <div class='not-found'>
   <h2> 404 Data Not found </h2>
   <a href='Home'><h3> Go To Home page </h3></a>
   <p> *You can search your curiosity on searchfield please try once.</p>
  </div>
 ";
            } ?>
    </article>
    <div class="otherSources">
        <div class="searchField">
            <input type="search" placeholder="Search here.." id="searchPost">
            <button type="submit"><i class="fa-solid fa-magnifying-glass" style="font-size: 20px;"></i></button>
        </div>
        <div class="dataReleted" id="dataRelated">
            <?php
            $splitData = explode(" ", $fUrl);
            $count = 0;

            if (count($splitData) > 2) {

                foreach ($splitData as $value) {
                    if ($count < 7) {
                        $stmt = mysqli_prepare($con, "SELECT * FROM post 
                                               LEFT JOIN `account` ON post.fk_author = account.accId 
                                            WHERE (title LIKE ? OR keywords LIKE ? OR metaDescription LIKE ? OR content LIKE ? OR tags LIKE ?) AND postStat = '1' AND pId != '$contId'");
                        $searchTerm = '%' . $value . '%';
                        mysqli_stmt_bind_param($stmt, "sssss", $value, $value, $value, $value, $value);
                        mysqli_stmt_execute($stmt);
                        $article = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($article) > 0) {
                            foreach ($article as $row) {
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
                                    <div class="card">
                                        <div class="box">
                                            <picture>
                                                <img loading="lazy" src="images/postimage/<?= $image; ?>" alt="<?= $row['title']; ?>">
                                            </picture>
                                            <div class="description">
                                                <h1 style="font-size: 22px; color:#000;"><?= $row['title']; ?></h1>
                                                <p id="type"><?= $row['metaDescription']; ?></p>
                                            </div>
                                            <div class="about-content">
                                                <span class="UploadDate"><?= $row['postDate']; ?></span>
                                                <span class="fas fa-eye">
                                                    <span class="specialNumber"><?= $row['viewer'] ?></span>
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
                        <?php
                                $count++;
                            }
                        }
                        $count++;
                    }
                }
            } else {
                $stmt = mysqli_prepare($con, "SELECT * FROM post 
                                            LEFT JOIN `account` ON post.fk_author = account.accId 
                                            WHERE (title LIKE ? OR keywords LIKE ? OR metaDescription LIKE ? OR content LIKE ?) AND postStat = '1' AND pId != '$contId'  ORDER BY pId DESC LIMIT 7");

                $searchTerm = '%' . $fUrl . '%';
                mysqli_stmt_bind_param($stmt, "ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
                mysqli_stmt_execute($stmt);
                $resultSet = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($resultSet) > 0) {
                    foreach ($resultSet as $row) {
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
                            <div class="card">
                                <div class="box">
                                    <picture>
                                        <img loading="lazy" src="images/postimage/<?= $image; ?>" alt="<?= $row['title']; ?>">
                                    </picture>
                                    <div class="description">
                                        <h1 style="font-size: 22px; color:#000;"><?= $row['title']; ?></h1>
                                        <p id="type"><?= $row['metaDescription']; ?></p>
                                    </div>
                                    <div class="about-content">
                                        <span class="UploadDate"><?= $row['postDate']; ?></span>
                                        <span class="fas fa-eye">
                                            <span class="specialNumber"><?= $row['viewer'] ?></span>
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
            <?php
                    }
                }
            }
            ?>

        </div>
    </div>
</main>
<!-- share into social media -->
<div class="share-field">
    <div class="share-icon">
        <i class="fas fa-share-alt" title="Share"></i>
    </div>
    <div class="share-data">
        <div class="social-share ">
            <header>
                <span>Share Option's</span>
                <span class="close">&times;</span>
            </header>
            <div class="main-field">
                <p>Share this link via..</p>
                <ul class="icons">
                    <a title='Facebook' class="facebook"><i class="fab fa-facebook"></i></a>
                    <a title="Messanger" class="messenger"><i class="fab fa-facebook-messenger"></i></a>
                    <a title="WhatsApp" class="whatsapp"><i class="fa-brands fa-whatsapp"></i></a>
                    <a title="Twitter" class="twitter"><i class="fa-brands fa-twitter"></i></a>
                    <a title="Pinterest" class="pinterest"><i class="fa-brands fa-pinterest"></i></a>
                    <a title="Telegram" class="telegram"><i class="fab fa-telegram-plane"></i></a>
                    <a title="Reddit" class="reddit"><i class="fab fa-reddit"></i></a>
                    <a titlle="E-Mail" class="envelope"><span class="">&#9993;</span></a>
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
</div>
<?php mysqli_close($con); ?>
<?php if ($heading !== "") { ?>
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
    var ogTitle = createMetaTag(`og:title`, `<?= $heading; ?>`);
    var ogDescription = createMetaTag(`og:description`, `<?= $author;?>, <?= $metaDescription; ?>`);
    var ogImage = createMetaTag(`og:image`, `images/postimage/<?= $pageImage; ?>`);
    var keywords = createMetaTag(`keywords`, `<?= $keywords; ?>`);
    var tags = createMetaTag(`tags`, `<?= $tags; ?>`);
    var author = createMetaTag(`author`, `<?= $author;?>`); 
    var description = createMetaTag(`description`, `<?= $metaDescription; ?>`);
    existingMetaTags.forEach(function (existingTag) {
        head.removeChild(existingTag);
    });
    head.appendChild(ogTitle);
    head.appendChild(ogDescription);
    head.appendChild(ogImage);
    head.appendChild(keywords);
    head.appendChild(tags);
    head.appendChild(description);
    head.appendChild(author);
}
setSocialMediaMetadata();
const introPicture = document.querySelector('#profile picture');
if(introPicture){
    function checkOnline(){
    if (navigator.onLine) {
        introPicture.classList.add('online');
      } else {
        introPicture.classList.remove('online');
      }
}
setInterval(checkOnline,2000);
}
    </script>
<?php } ?>