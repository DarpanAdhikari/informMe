<?php include "admin_panel.php";
echo '<link rel="stylesheet" href="additional/css/posttable.css">
<script src="additional/script/posttable.js" defer></script>';
?>


<div class="tables">
  <input type="search" id="seachOnTable" placeholder="Seach Here..." oninput="searchTables(value)">
  <?php
  function encryptId($id, $key)
  {
    $encryptedId = urlencode(base64_encode($id ^ $key));
    return $encryptedId;
  }

  $id = $_SESSION['acc_id'];
  $checkAdmin =  mysqli_query($con, "SELECT position FROM account where accId = '$id'");
  if ($checkAdmin) {
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

        <?php if (strtoupper('admin') === strtoupper($value)) { ?>
          <?php if (isset($_GET['acct'])) { ?>
            <div class="table-responsive accounts">
              <table>
                <thead>
                  <tr>
                    <th>S.N.</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>User Bio</th>
                    <th>Image</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $sno = 1;
                  $query = "SELECT * FROM account ORDER BY accId DESC";
                  if ($query) {
                    $result = mysqli_query($con, $query);
                    foreach ($result as $row) {
                      $img = $row['image'];
                      $key = 15227;
                      $iv = '123456789abcdefg';
                      $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);

                  ?>
                      <tr>
                        <td><?= $sno; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td><?= $row['position']; ?></td>
                        <td class="userbio"><?= $row['bio']; ?></td>
                        <td><img src="images/profile_img/<?= $image; ?>" alt="<?= $row['name']; ?>'s image" width="50px" height="50px" title="<?= $image; ?>"></td>
                        <td class="acc action">
                          <i class="fas  fa-edit" title="update"></i>
                          <?php if ($row['userState'] !== "0") { ?>
                            <a href="update_item?acc_up=<?= $row['accId']; ?>&state=0" onclick="return confirm('do you Ban him/her.')">
                              <i class="fas fa-ban" title="ban user"></i>
                            </a>
                          <?php } else { ?>
                            <a href="update_item?acc_up=<?= $row['accId']; ?>&state='1'" onclick="return confirm('do you unban him/her.')">
                              <i class="far fa-flag" title="unban user"></i>
                            </a>
                          <?php } ?>
                        </td>
                        <div class="AcceditForm">
                          <form action="update_item" method="POST" enctype="multipart/form-data">
                            <input type="text" name="id" value="<?= $row['accId']; ?>" hidden>
                            <div class="input-box">
                              <label for="">Position</label>
                              <input type="text" name="position" placeholder="User Post or position">
                            </div>
                            <button type="submit" name="positionSet">Submit</button>
                          </form>
                        </div>
                      </tr>
                  <?php $sno++;
                    }
                  } ?>

                </tbody>
              </table>
            </div>
        <?php }
        } ?>
        <!-- navigation data------------------- -->
        <?php if (isset($_GET['navigation'])) { ?>

          <div class="table-responsive navigation">
            <table>
              <thead>
                <tr>
                  <th>S.N.</th>
                  <th>Navigation</th>
                  <th>About Us</th>
                  <th><i class="fab fa-facebook"></i></th>
                  <th><i class="fab fa-youtube"></i></th>
                  <th><i class="fab fa-twitter"></i></th>
                  <th><i class="fab fa-pinterest"></i></th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sno = 1;
                $query = "SELECT * FROM navbar";
                if ($query) {
                  $result = mysqli_query($con, $query);
                  foreach ($result as $row) {
                ?>
                    <tr>
                      <td><?= $sno; ?></td>
                      <td><?= $row['navigation']; ?></td>
                      <td><?= $row['about_us']; ?></td>
                      <td><?= $row['facebook']; ?></td>
                      <td><?= $row['youtube']; ?></td>
                      <td><?= $row['twitter']; ?></td>
                      <td><?= $row['pinterest']; ?></td>
                      <td class="action">
                        <a href="nav-edit">
                          <i class="fas  fa-edit" title="edit"></i>
                        </a>
                        <?php if ($row['nav_status'] !== "0") { ?>
                          <a href="update_item?navdel=<?= $row['row']; ?>&state=0">
                            <i class="fas fa-ban"></i>
                          </a>
                        <?php } else { ?>
                          <a href="update_item?navdel=<?= $row['row']; ?>&state=1">
                            <i class="fas fa-check"></i>
                          </a>
                        <?php } ?>
                      </td>
                    </tr>
                <?php $sno++;
                  }
                } ?>
              </tbody>
            </table>
          </div>
        <?php } ?>
        <?php
        if (isset($_GET['cht'])) { ?>
          <!-- chats data view -->
          <div class="table-responsive conversation">
            <form action="delete_item.php" method="POST" id="selectItem" enctype="multipart/form-data">
            <table>
              <thead>
                <tr>
                  <th><input type="checkbox" id="selectAll"> Select</th>
                  <th>S.N.</th>
                  <th>Name</th>
                  <th>Message</th>
                  <th>Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $sno = 1;
                $query = "SELECT * FROM chat LEFT JOIN  `account` ON chat.fk_accId = account.accId ORDER BY chtId DESC";
                $result = mysqli_query($con, $query);
                if (mysqli_num_rows($result)>0) {
                  foreach ($result as $row) {
                ?>
                    <tr>
                      <td><input type="checkbox" name="chatToDelete[]" value="<?php
                                                               $id = $row['chtId'];;
                                                               $key = 94325928321;
                                                               $encryptedId = encryptId($id, $key);
                                                               echo $encryptedId; ?>"></td>
                      <td><?= $sno; ?></td>

                      <?php if ($row['name'] !== "" && $row['name'] !== null) { ?>
                        <td><?= $row['name']; ?></td>
                      <?php } else { ?>
                        <td><?= $row['ch_name']; ?></td>
                      <?php } ?>
                      <td><?= $row['message']; ?></td>
                      <td><?= $row['date']; ?></td>
                    </tr>
                <?php $sno++;
                  }
                }else{
                  echo "<tr><td colspan='3' style='color:red; text-align:center;'>This table is empty!</td></tr>";
                }  ?>
              </tbody>
            </table>
            <input type="submit" name="deleteChat" value="Delete" id="deleteChat">
            </form>
          </div>
        <?php } ?>
</div>
<?php }
    }
   ?>

<!-- display post------------------------ -->

<?php if (isset($_GET['post']) || isset($_GET['draft']) || isset($_GET['pimages']) || isset($_GET['comments'])) { ?>
  <?php if (isset($_GET['post']) || isset($_GET['draft'])) { ?>
    <a href="new-post" class="goto">
      <button><i class="fas fa-plus"></i> Add Post</button>
    </a>
    <div class="table-responsive post">
      <table>
        <thead>
          <tr>
            <th>S.N.</th>
            <th>Author Name</th>
            <th>Heading</th>
            <th hidden>Content</th>
            <th>Feature Image</th>
            <th>Category</th>
            <th>Post State</th>
            <th>Display</th>
            <th>Viewer</th>
            <th>Likes</th>
            <th>Comments</th>
            <th>Post Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $id = $_SESSION['acc_id'];
          $checkAdmin =  mysqli_query($con, "SELECT position FROM account where accId = '$id'");
          $displayManagement = false;
          if (mysqli_num_rows($checkAdmin)>0) {
            $check = mysqli_fetch_assoc($checkAdmin);
            $splitData = explode(" ", $check['position']);
            foreach ($splitData as $value) {
              if ((strtoupper('admin') === strtoupper($value)) || (strtoupper('editor') === strtoupper($value))) {
                if (isset($_GET['draft'])) {
                  $get = mysqli_query($con, "SELECT * FROM post 
                                    LEFT JOIN  `account` ON post.fk_author = account.accId 
                                    LEFT JOIN  `navbar` ON post.fk_category = navbar.row WHERE post.postStat = '0' ORDER BY pId DESC");
                  break;
                } else {
                  $get = mysqli_query($con, "SELECT * FROM post 
                                    LEFT JOIN  `account` ON post.fk_author = account.accId 
                                    LEFT JOIN  `navbar` ON post.fk_category = navbar.row WHERE post.postStat != '0' ORDER BY pId DESC");
                  break;
                }
              } else {
                if (isset($_GET['draft'])) {
                  $get = mysqli_query($con, "SELECT * FROM post 
              LEFT JOIN  `account` ON post.fk_author = account.accId 
             LEFT JOIN  `navbar` ON post.fk_category = navbar.row WHERE post.fk_author = '$id' AND post.postStat= '0' ORDER BY pId DESC");
                } else {
                  $get = mysqli_query($con, "SELECT * FROM post 
              LEFT JOIN  `account` ON post.fk_author = account.accId 
             LEFT JOIN  `navbar` ON post.fk_category = navbar.row WHERE post.fk_author = '$id' AND post.postStat= '1' ORDER BY pId DESC");
                }
              }
            }

            if (mysqli_num_rows($get)>0) {
              $sno = 1;
              foreach ($get as $row) {
                $img = $row['featureImg'];
                $key = 1527781;
                $iv = '123456789abcdefg';
                $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
          ?>
                <tr>
                  <td><?= $sno; ?></td>
                  <td><?= $row['name']; ?></td>
                  <td><?= $row['title']; ?></td>
                  <td hidden><?= $row['content']; ?></td>
                  <td><img src="images/postimage/<?= $image; ?>" alt="" width="100px" height="50px" title="<?= $image; ?>"></td>
                  <td><?= $row['navigation']; ?></td>
                  <td>
                    <?php if ($row['postStat'] === "0") {
                      echo "Draft";
                    } else {
                      echo "Post";
                    } ?>
                  </td>
                  <td>
                    <?php if ($row['display'] === "0") {
                      echo "None";
                    } else {
                      if ($row['display'] === "1") {
                        echo "Both";
                      } else {
                        if ($row['display'] === "2") {
                          echo "Slide";
                        } else {
                          echo "Side";
                        }
                      }
                    }
                    ?>
                  </td>
                  <td><?= $row['viewer']; ?></td>
                  <td><?= $row['likes']; ?></td>
                  <td>
                    <?php
                    $pId = $row['pId'];
                    $query = mysqli_query($con, "SELECT COUNT(cmId) AS cmCount FROM comment WHERE fk_postId = '$pId'");
                    if (mysqli_num_rows($query) > 0) {
                      $comment = mysqli_fetch_assoc($query);
                    ?>
                      <span><?= $comment['cmCount']; ?></span>
                    <?php } else {
                      echo "<span>0</span>";
                    } ?>
                  </td>
                  <td><?= $row['postDate']; ?></td>
                  <td class="action">
                    <?php  if (isset($_GET['draft'])) {?>
                    <a href="update-draft<?php $id = $row['pId'];
                                        $key = 9876543923;
                                        $encryptedId = encryptId($id, $key);
                                        echo $encryptedId; ?>">
                      <i class="fas  fa-edit" title="edit"></i>
                    </a>
                    <?php }else{?>
                      <a href="update-post<?php $id = $row['pId'];
                                        $key = 9876543923;
                                        $encryptedId = encryptId($id, $key);
                                        echo $encryptedId; ?>">
                      <i class="fas  fa-edit" title="edit"></i>
                    </a>
                    <?php }?>
                    <i class="fas  fa-eye" title="preview and check its responsivity"></i>
                    <a href="deletePost<?php
                                        $id = $row['pId'];
                                        $key = 987654321;
                                        $encryptedId = encryptId($id, $key);
                                        echo $encryptedId;
                                        ?>" onclick="return confirm('Are you sure you want to delete this post')">
                      <i class="fas  fa-trash" title="delete"></i>
                    </a>
                  </td>
                </tr>
            <?php $sno++;
              }
            }else{
              echo "<tr><td colspan='3' style='color:red; text-align:center;'>This table is empty!</td></tr>";
            } ?>
        </tbody>
      </table>
    </div>
    <?php ?>
  <?php } ?>
<?php } ?>

<?php if (isset($_GET['comments'])) { ?>
  <!-- comments---------------------------------------------------------------- -->
  <div class="table-responsive comments">
    <form action="delete_item.php" method="POST" id="selectItem" enctype="multipart/form-data">
    <table>
      <thead>
        <tr>
          <th><input type="checkbox" id="selectAll"> SELECT</th>
          <th>S.N.</th>
          <th>Name</th>
          <th>Post</th>
          <th>Comment</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach ($splitData as $value) {
          if ((strtoupper('admin') === strtoupper($value)) || (strtoupper('editor') === strtoupper($value))) {
            $displayManagement = true;
            break;
          }
        }
        if ($displayManagement) {
            $comments = mysqli_query($con, "SELECT * FROM comment
                                           LEFT JOIN account ON comment.fk_user = account.accId
                                           LEFT JOIN post ON comment.fk_postId = post.pId ORDER BY pId DESC");
            $sno = 1;
            if(mysqli_num_rows($comments)>0){
            foreach ($comments as $row) {
           ?>
              <tr>
                <td><input type="checkbox" name="commentToDelete[]" value="<?php
                                                               $id = $row['cmId'];
                                                               $key = 98765439;
                                                               $encryptedId = encryptId($id, $key);
                                                               echo $encryptedId; ?>"></td>
                <td><?= $sno; ?></td>
                <?php if ($row['commentor'] !== "") { ?>
                  <td><?= $row['commentor']; ?></td>
                <?php } else {
                  echo "<td>" . $row['name'] . "</td>";
                } ?>
                <td><?= $row['title']; ?></td>
                <td><?= $row['comment']; ?></td>
              </tr>
           <?php $sno++;
            }
           }else{
            echo "<tr><td colspan='3' style='color:red; text-align:center;'>This table is empty!</td></tr>";
          } 
        } ?>
      </tbody>
    </table>
    <input type="submit" name="deleteComment" id="deleteChat" value="Delete">
    </form>
  </div>
<?php } ?>

<!-- images =========================================================================== -->
<?php if (isset($_GET['pimages'])) { ?>
  <div class="table-responsive uploadedImg">
    <table>
      <thead>
        <tr>
          <th>S.No.</th>
          <th>Redirect</th>
          <th>Images</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $selectImg = mysqli_query($con, 'SELECT * FROM postImg ORDER BY imageId DESC');
        if (mysqli_num_rows($selectImg) > 0) {
          $sno = 1;
          foreach ($selectImg as $row) {
            $img = $row['imageUrl'];
            $key = 1527781991;
            $iv = '123456789abcdefg';
            $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
        ?>
            <tr>
              <td><?= $sno; ?></td>
              <td id="url"><?= $row['url']; ?>
                <form action="change" method="post" enctype="multipart/form-data">
                  <input type="text" name="updateUrl" value="<?php
                                                              $key = 9820;
                                                              echo encryptId($row['imageId'], $key); ?>" hidden>
                  <input type="url" id="redirect" name="urlData" placeholder="https://example.com *" required>
                  <button name="urlSbmt">Finished</button>
                </form>
              </td>
              <td><img src="images/imagePost/<?= $image; ?>" width="100px" height="60px"></td>
              <td class="action">
                <i class="fas fa-edit"></i>
                <a href="deleteImage<?php
                                    $id = $row['imageId'];
                                    $key = 9876543;
                                    $encryptedId = encryptId($id, $key);
                                    echo $encryptedId;
                                    ?>"><i class="fas fa-trash"></i></a>
              </td>
            </tr>
        <?php $sno++;
          }
        }else{
          echo "<tr><td colspan='3' style='color:red; text-align:center;'>This table is empty!</td></tr>";
        }  ?>
      </tbody>
    </table>
  </div>
<?php } ?>
<!-- .article-preview article preview -->
<div class="article-preview">
  <article>
    <h2></h2>
    <img src="" alt="">
    <p></p>
  </article>
</div>
<?php
  if (isset($_GET["draft"])) {
    echo '<script>
    const sidebarItems = document.querySelectorAll(".sidebar li");
    sidebarItems.forEach(item => {
      item.classList.remove("active");
      sidebarItems[2].classList.add("active");
    });
    </script>';
  } else {
    echo '<script>
  const sidebarItems = document.querySelectorAll(".sidebar li");
  sidebarItems.forEach(item => {
    item.classList.remove("active");
    sidebarItems[1].classList.add("active");
  });
  </script>';
  }
?>
<?php  } ?>
<?php mysqli_close($con); ?>