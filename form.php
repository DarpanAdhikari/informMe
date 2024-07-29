<?php include "admin_panel.php";
echo '<link rel="stylesheet" href="additional/css/form.css"/>
<script src="additional/script/form.js" defer></script>';
$con = mysqli_connect("localhost", "root", "", "informme");

function decryptId($encryptedId, $key)
{
  $decryptedId = base64_decode(urldecode($encryptedId)) ^ $key;
  return $decryptedId;
}
?>

<?php if (isset($_SESSION['logedIn']) && $_SESSION['logedIn'] === true) { ?>
  <div class="container" id="profileUpdate">
    <?php if (isset($_GET['acup'])) { ?>
      <!-- profile data update form----------- -->
      <form action="update_item" enctype="multipart/form-data" method="POST" style="margin-top: 20px;">
        <picture class="form-group">
          <?php if (($row['image'] !== "") && ($row['image'] !== null)) { ?>
            <img src="images/profile_img/<?= $image; ?>" alt="user image" id="prevImage">
          <?php } else {
            echo '<img src="additional/default.png" alt="user image" id="prevImage">';
          } ?>
          <button title="image must be .png,.jpg,.gif"><i class="fas fa-image"></i> Change Image</button>
          <input type="file" name="new_img" id="newImg" accept="image/*" hidden>
          <input type="text" name="old_img" value="<?= $image; ?>" placeholder="image must be .png,.jpg,.gif" hidden>
        </picture>
        <div class="form-group">
          <label for="name"><i class="fas fa-user"></i> Name:</label>
          <input type="text" name="name" placeholder="Enter your name" value="<?= $row['name']; ?>" required>
        </div>
        <div class="form-group">
          <label for="email"><i class="fas fa-envelope"></i> Email:</label>
          <input type="email" name="email" placeholder="Enter your email" value="<?= $row['email']; ?>" required>
        </div>
        <div class="form-group">
          <input type="text" name="confirmpass" value=" <?= $row['password']; ?>" hidden>
          <label for="password"><i class="fas fa-lock"></i> Old Password:</label>
          <input type="password" name="old_pass" placeholder="Enter your old password" required>
          <label for="password"><i class="fas fa-lock"></i> New Password:</label>
          <input type="text" name="password" placeholder="Enter your new password">
        </div>
        <div class="form-group">
          <label for="bio"><i class="fas fa-address-card"></i> Your Bio:</label>
          <textarea name="bio" placeholder="Enter a short bio" required><?= $row['bio']; ?></textarea>
        </div>

        <div class="form-group">
          <label for="facebook"><i class="fab fa-facebook"></i> facebook:</label>
          <input type="url" name="facebook" placeholder="Enter or paste your facebook url" value="<?= $row['facebook']; ?>">
        </div>
        <div class="form-group">
          <label for="instagram"><i class="fab fa-instagram"></i> Instagram:</label>
          <input type="url" name="instagram" placeholder="Enter or paste your instagram url" value="<?= $row['instagram']; ?>">
        </div>
        <div class="form-group">
          <label for="youtube"><i class="fab fa-youtube"></i> YouTube:</label>
          <input type="url" name="youtube" placeholder="Enter or paste your youtube url" value="<?= $row['youtube']; ?>">
        </div>
        <div class="form-group">
          <label for="twitter"><i class="fab fa-twitter"></i> Twitter:</label>
          <input type="url" name="twitter" placeholder="Enter or paste your twitter url" value="<?= $row['twitter']; ?>">
        </div>
        <div class="form-group submission">
          <input type="submit" name="upDprofile" value="Save Changes" style="background: #137615;"></input>
        </div>
      </form>
  </div>
<?php }
  } ?>

<!-- new post form  -->
<?php if (isset($_GET['newPost']) || isset($_GET['upPost']) || isset($_GET['upDraft'])) { ?>
  <div class="container" id="postMaker">
    <?php if (isset($_GET['upPost']) || isset($_GET['upDraft']) && !isset($_GET['newPost'])) {
      if (isset($_GET['upPost'])) {
        $id = $_GET['upPost'];
      } else {
        $id = $_GET['upDraft'];
      }
      $key = 9876543923;
      $id = decryptId($id, $key);

      $get = mysqli_query($con,  "SELECT * FROM post 
                           LEFT JOIN  `account` ON post.fk_author = account.accId 
                           LEFT JOIN  `navbar` ON post.fk_category = navbar.row WHERE post.pId = '$id'");
      $row = mysqli_fetch_assoc($get);
    ?>
      <form action="update_item" method="POST" enctype="multipart/form-data">
        <h2>Update</h2>
        <div class="form-group">
          <label for="title">Title:</label>
          <input type="text" name="title" value="<?= $row['title']; ?>" required>
        </div>

        <div class="form-group">
          <label for="slug">Slug:</label>
          <input type="text" name="slug" value="<?= $row['slug']; ?>" required>
        </div>

        <div class="form-group">
          <label for="meta_description">Meta Description:</label>
          <textarea name="meta_description" rows="3" required><?= $row['metaDescription']; ?></textarea>
        </div>

        <div class="form-group">
          <label for="keywords">Keywords:</label>
          <input type="text" name="keywords" value="<?= $row['keywords']; ?>" required>
        </div>

        <div class="form-group">
          <label for="category">Category:</label>
          <select name="category" required>
            <option>Select Category</option>
            <?php
            $category = mysqli_query($con, "SELECT navigation, row FROM `navbar` WHERE nav_status != '0'");
            if ($category) {
              foreach ($category as $nav) {
                $selected = "";
                if ($nav['row'] == $row['fk_category']) {
                  $selected = "selected";
                }
            ?>
                <option value="<?= $nav['row'] ?>" <?= $selected; ?>><?= $nav['navigation']; ?></option>
            <?php
              }
            }
            ?>

            <option value="0" <?= ($row['fk_category'] == '0') ? 'selected' : '' ?>>Extra Dose</option>
          </select>
        </div>

        <div class="form-group">
          <label for="post state">Display:</label>
          <select id="display" name="display" required>
            <option value="0" <?= ($row['display'] == '0') ? 'selected' : '' ?>>Normal</option>
            <option value="1" <?= ($row['display'] == '1') ? 'selected' : '' ?>>Both</option>
            <option value="2" <?= ($row['display'] == '2') ? 'selected' : '' ?>>Slide</option>
            <option value="3" <?= ($row['display'] == '3') ? 'selected' : '' ?>>Side</option>
          </select>
        </div>

        <div class="form-group">
          <label for="tags">Tags:</label>
          <input type="text" name="tags" value="<?= $row['tags']; ?>">
        </div>
        <div class="form-group">
          <input type="text" name="old_img" id="old_img" value="<?= $row['featureImg']; ?>" hidden>
          <input type="file" name="feature_img" id="featureImg" accept="image/*" hidden>
          <img src="" id="featureImagePreview" alt="" hidden>
          <button id="featureImgBtn">
            <i class="fas fa-image"></i> Feature Image</button>
        </div>
        <div class="form-group">
          <label for="content">Content:</label>
          <textarea id="textarea" name="content" rows="10"><?= $row['content']; ?></textarea>
        </div>
        <?php if (isset($_GET['upPost'])) { ?>
          <input type="text" value="<?= $_GET['upPost']; ?>" name="postNo" hidden>
        <?php } else { ?>
          <input type="text" value="<?= $_GET['upDraft']; ?>" name="postNo" hidden>
        <?php } ?>
        <div class="form-group submission" style="display: flex;">
          <?php if (isset($_GET['upPost'])) { ?>
            <input type="submit" value="Save Changes" name="postUpd" style="background: #137615;" onclick="checkFile(event)">
            <input type="submit" value="Make Draft" name="updraft" style="background: #987e1f;">
          <?php } else { ?>
            <input type="submit" value="Publish" name="postUpd" style="background: #137615;" onclick="checkFile(event)">
            <input type="submit" value="Save Changes" name="updraft" style="background: #987e1f;">
          <?php } ?>
        </div>
      </form>
    <?php } else { ?>
      <form action="insert" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
        <div class="form-group">
          <label for="title">Title:</label>
          <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
          <label for="slug">Slug:</label>
          <input type="text" id="slug" name="slug" required>
        </div>

        <div class="form-group">
          <label for="meta_description">Meta Description:</label>
          <textarea id="metadescription" name="meta_description" rows="3" required></textarea>
        </div>

        <div class="form-group">
          <label for="keywords">Keywords:</label>
          <input type="text" id="keywords" name="keywords" required>
        </div>

        <div class="form-group">
          <label for="category">Category:</label>
          <select id="category" name="category" required>
            <option value="">Select Category</option>
            <?php
            $get = mysqli_query($con, "SELECT * FROM `navbar` WHERE nav_status != '0'");
            if ($get) {
              foreach ($get as $row) {
            ?>
                <option value="<?= $row['row'] ?>"><?= $row['navigation']; ?></option>
            <?php }
            }
            ?>
            <option value="0">Extra Dose</option>
          </select>
        </div>

        <div class="form-group">
          <label for="display">Display:</label>
          <select id="display" name="display" required>
            <option value="">Select How to display</option>
            <option value="0">Normal</option>
            <option value="1">Both</option>
            <option value="2">Slide</option>
            <option value="3">Side</option>
          </select>
        </div>

        <div class="form-group">
          <label for="tags">Tags:</label>
          <input type="text" id="tags" name="tags">
        </div>
        <div class="form-group">
          <input type="file" name="feature_img" id="featureImg" accept="image/*" hidden>
          <button id="featureImgBtn">
            <i class="fas fa-image"></i> Feature Image</button>
        </div>
        <div class="form-group">
          <label for="content">Content:</label>
          <textarea id="textarea" name="content" rows="10"></textarea>
        </div>

        <div class="form-group submission" style="display: flex; gap:10px;">
          <input type="submit" value="Publish" name="postSbt" style="background: #137615;" onclick="checkFile(event)">
          <input type="submit" value="Draft" name="postDrf" style="background: #987e1f;">
        </div>
      </form>
    <?php } ?>

    <!-- postSbt--------------------------- -->
    <div class="additional-input">
      <section class="images-holder">
        <div class="images-container">
          <div class="image-drop">
            <div class="drop-here">
              <p>Drop your image</p>
            </div>
            <form id="uploadForm" enctype="multipart/form-data" hidden>
              <input type="file" id="imageInsert" accept="image/*" name="images[]" multiple>
            </form>
            <button>Choose Image</button>
          </div>
          <div class="uploaded-images">
            <p style="color:#0e2dc8;">*If image is not coppied please refresh this page.</p>
            <div class="images">
              <?php $selectImg = mysqli_query($con, 'SELECT * FROM postImg ORDER BY imageId DESC');
              if (mysqli_num_rows($selectImg) > 0) {
                foreach ($selectImg as $row) {
                  $img = $row['imageUrl'];
                  $key = 1527781991;
                  $iv = '123456789abcdefg';
                  $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
              ?>
                  <img src="images/imagePost/<?= $image; ?>" alt="postImage">
              <?php }
              } ?>
            </div>
            <div class="buttons">
              <button id="uploadFor">Upload</button>
              <button id="hideIt">Close</button>
            </div>
          </div>
        </div>
      </section>
      <h2>Make Image Link</h2>
      <form action="" class="imagesForm">
        <div class="form-group">
          <button id="linkImgBtn">
            <i class="fas fa-images"></i> Select Images</button>
        </div>
      </form>
      <hr>
      <div class="imgLinks">
        <div class="select">
        </div>
        <p style="color: #ccc;">*Click On image to copy link of image</p>
        <hr>
        <div class="feature-image">
          <h3>Feature image</h3>
          <picture>
            <img src="">
          </picture>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

<!-- web page edit or change -->
<?php if (isset($_GET['editNav'])) {
  $id = $_SESSION['acc_id'];
  $checkAdmin =  mysqli_query($con, "SELECT * FROM account where accId = '$id'");

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
      <div class="container" id="navigation">
        <?php
        $check = mysqli_query($con, "SELECT * FROM navbar");
        $pageName = mysqli_fetch_assoc($check);
        if (mysqli_num_rows($check) >= 5) {
        ?>

          <form method="POST" action="update_item" enctype="multipart/form-data">
            <h2>Edit on Page</h2>
            <div class="form-group">
              <label for="page name">Page Name</label>
              <input type="text" name="page_name" placeholder="Page name" value="<?= $pageName['page_name']; ?>" />
            </div>
            <div class="form-group">
              <label for="first">Second navigation</label>
              <input type="text" name="first" placeholder="First item" value="<?= $pageName['navigation']; ?>" />
            </div>
            <?php
            $thirdNav = mysqli_fetch_assoc($check);
            ?>
            <div class="form-group">
              <label for="third_nav">Third navigation</label>
              <input type="text" name="second" placeholder="second item" value="<?= $thirdNav['navigation']; ?>" />
            </div>
            <?php
            $fourthNav = mysqli_fetch_assoc($check);
            ?>
            <div class="form-group">
              <label for="fourth">Fourth navigation</label>
              <input type="text" name="third" placeholder="third item" value="<?= $fourthNav['navigation']; ?>" />
            </div>
            <?php
            $fifthNav = mysqli_fetch_assoc($check);
            ?>
            <div class="form-group">
              <label for="fifth">Fifth navigation</label>
              <input type="text" name="fourth" placeholder="fourth item" value="<?= $fifthNav['navigation']; ?>" />
            </div>
            <?php
            $sixthNav = mysqli_fetch_assoc($check);
            ?>
            <div class="form-group">
              <label for="sixth">Sixth navigation</label>
              <input type="text" name="fifth" placeholder="fifth item" value="<?= $sixthNav['navigation']; ?>" />
            </div>

            <div class="form-group">
              <label for="About Us">About Us</label>
              <textarea name="about_us" rows="10" minlength="50" id="textarea" ><?= $pageName['about_us']; ?></textarea>
            </div>

            <div class="form-group">
              <h1 for="third_nav">Page Social Link</h1>
              <label for="facebook"><i class="fab fa-facebook"></i> Facebook</label>
              <input type="url" name="facebook" placeholder="https://example.com" value="<?= $pageName['facebook']; ?>">
              <label for="Youtube"><i class="fab fa-youtube"></i> Youtube</label>
              <input type="url" name="youtube" placeholder="https://example.com" value="<?= $pageName['youtube']; ?>">
              <label for="twitter"><i class="fab fa-twitter"></i> Twitter</label>
              <input type="url" name="twitter" placeholder="https://example.com" value="<?= $pageName['twitter']; ?>">

              <label for="pinterest"><i class="fab fa-pinterest"></i> Pinterest</label>
              <input type="url" name="pinterest" placeholder="https://example.com" value="<?= $pageName['pinterest']; ?>">
            </div>

            <div class="form-group submission">
              <input type="submit" name="navSbt" value="Save Changes" class="submit" onclick="return confirm('Are you sure there is all good nothing mistake? Check again before pressing OK.')" style="background: #137615;">
            </div>
          </form>
        <?php } else { ?>
          <form method="POST" action="insert" enctype="multipart/form-data">
            <h2>Create data for Page</h2>
            <div class="form-group">
              <label for="page name">Page Name</label>
              <input type="text" name="page_name" placeholder="Page name" />
            </div>
            <div class="form-group">
              <label for="first">Second navigation</label>
              <input type="text" name="first" placeholder="First item" />
            </div>
            <div class="form-group">
              <label for="third_nav">Third navigation</label>
              <input type="text" name="second" placeholder="second item" />
            </div>
            <div class="form-group">
              <label for="fourth">Fourth navigation</label>
              <input type="text" name="third" placeholder="third item" />
            </div>
            <div class="form-group">
              <label for="fifth">Fifth navigation</label>
              <input type="text" name="fourth" placeholder="fourth item" />
            </div>

            <div class="form-group">
              <label for="sixth">Sixth navigation</label>
              <input type="text" name="fifth" placeholder="fifth item" />
            </div>
            <div class="form-group">
              <label for="About Us">About Us</label>
              <textarea name="about_us" minlength="50" id="textarea"></textarea>
            </div>

            <div class="form-group">
              <h1 for="third_nav">Page Social Link</h1>
              <label for="facebook"><i class="fab fa-facebook"></i> Facebook</label>
              <input type="url" name="facebook" placeholder="https://example.com">
              <label for="Youtube"><i class="fab fa-youtube"></i> Youtube</label>
              <input type="url" name="youtube" placeholder="https://example.com">
              <label for="twitter"><i class="fab fa-twitter"></i> Twitter</label>
              <input type="url" name="twitter" placeholder="https://example.com">

              <label for="pinterest"><i class="fab fa-pinterest"></i> pinterest</label>
              <input type="url" name="pinterest" placeholder="https://example.com">
            </div>
            <div class="form-group submission">
              <input type="submit" name="navSbt" value="save" class="submit" onclick="return confirm('Are you sure there is all good nothing mistake ?*check again before pressing ok')" style="background: #137615;">
            </div>
          </form>
          <div class="additional-input">
            <p>*Note there should be minimum first three field filled</p>
          </div>
        <?php }?>
      </div>
<?php }
  }?>
<script>
  tinymce.init({
    selector: '#textarea',
    plugins: [
      'a11ychecker', 'advlist', 'advcode', 'advtable', 'autolink', 'checklist', 'export',
      'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks',
      'powerpaste', 'fullscreen', 'formatpainter', 'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | formatpainter casechange blocks | bold italic backcolor | ' +
      'alignleft aligncenter alignright alignjustify | ' +
      'bullist numlist checklist outdent indent | removeformat | a11ycheck code table help'
  });
</script>
<?php mysqli_close($con);?>