<?php
$con = mysqli_connect("localhost", "root", "", "informme");
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
  }
function convertString($name)
{
  $sentence = $name;
  $encodedSentence = str_replace(" ", "-", $sentence);
  return $encodedSentence;
}

?>


<!-- data for search -->
<?php
if (isset($_POST['getContent']) || isset($_POST['search'])) {
    if(isset($_POST['getContent'])){
        $data = $_POST['getContent'];
    }else{
        $data = $_POST['search'];
    }
    $stmt = mysqli_prepare($con, "SELECT * FROM post 
    LEFT JOIN `account` ON post.fk_author = account.accId 
    WHERE (title LIKE ? OR keywords LIKE ? OR metaDescription LIKE ? OR content LIKE ?) AND postStat = '1' ORDER BY pId DESC");

    $searchTerm = '%' . $data . '%';
    mysqli_stmt_bind_param($stmt, "ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    mysqli_stmt_execute($stmt);
    $resultSet = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($resultSet) > 0) {
        while ($row = mysqli_fetch_array($resultSet)) {
            $img = $row['featureImg'];
            $key = "1527781";
            $iv = '123456789abcdefg'; 
            $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
            if ($row['slug'] !== "") {
                $url = convertString($row['slug']);
            } else {
                $url = convertString($row['title']);
            }
            if(isset($_POST['getContent'])){
            ?>
            <a href="<?php echo $url; ?>" title="<?= $row['title']; ?>">
                <div class="card">
                    <div class="box">
                        <picture>
                            <img loading="lazy" src="images/postimage/<?= $image; ?>" alt="">
                        </picture>
                        <div class="description">
                            <h1><?= $row['title']; ?></h1>
                            <p><?= $row['metaDescription']; ?></p>
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
            <?php }else{
                echo '
                <a href="'.$url.'" class="fas fa-magnifying-glass"><p>'.$row['title'].'</p></a>
                ';
            }
        }
    }
}
?>


<?php
if (isset($_POST['checkStatus'])) {
  $query = "SELECT account.*, COUNT(post.pId) AS postCount 
            FROM account 
            LEFT JOIN post ON account.accId = post.fk_author 
            WHERE account.userState != '0' AND postStat != '0'
            GROUP BY account.accId
            ORDER BY postCount DESC";
  $result = mysqli_query($con, $query);

  if ($result) {
    while ($row = mysqli_fetch_array($result)) {
      $img = $row['image'];
      $key = 15227;
      $iv = '123456789abcdefg';
      $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
?>
      <a href="<?= "user=" . base64_encode($row['email']); ?>">
        <div class="bar">
          <?php if (($row['image'] !== "") && ($row['image'] !== null)) { ?>
            <div class="intro">
              <picture <?php if ($row['onlineStat'] !== "0") {
                          echo 'class="online"';
                        } ?>>
                <img loading="lazy" src="<?= 'images/profile_img/', $image; ?>" alt="profile pic" width="50px" height="50px">
              </picture>
            </div>
          <?php } else { ?>
            <div class="intro">
              <picture <?php if ($row['onlineStat'] !== "0") {
                          echo 'class="online"';
                        } ?>>
                <img loading="lazy" src="additional/addition/default.png" alt="profile pic" width="50px" height="50px">
              </picture>
            </div>
          <?php } ?>
          <p style="font-size: 13px; font-weight:700;"><?= $row['name']; ?></p>
          <span ><?= $row['position']; ?></span>
          <p>POST: <?= $row['postCount']; ?></p>
        </div>
      </a>
<?php
    }
  }
}
?>

<?php if(isset($_POST['download'])){
 $tables = array();
 $result = mysqli_query($con, "SHOW TABLES");
 while ($row = mysqli_fetch_row($result)) {
     $tables[] = $row[0];
 }
 $data = array();
 foreach ($tables as $table) {
     $query = "SELECT * FROM $table";
     $result = mysqli_query($con, $query);
 
     $tableData = "";
     while ($row = mysqli_fetch_assoc($result)) {
         $tableData .= implode(",", $row) . "\n";
     }
 
     $data[$table] = $tableData;
 }
 $zip = new ZipArchive();
 $zipFilename = "database_tables.zip";
 if ($zip->open($zipFilename, ZipArchive::CREATE) === true) {
     foreach ($data as $table => $tableData) {
         $file = $table . ".csv";
         $zip->addFromString($file, $tableData);
     }
     $zip->close();
     header("Content-Type: application/octet-stream");
     header("Content-Disposition: attachment; filename=\"$zipFilename\"");
     readfile($zipFilename);
     unlink($zipFilename);
 }
 mysqli_close($con);
}?>
