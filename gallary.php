<?php include_once "header.php"; ?>
<link rel="stylesheet" type="text/css" href="additional/css/gallary.css">

<div class="gallery">
  <?php
  $con = mysqli_connect("localhost", "root", "", "informme");
  $query = mysqli_query($con, "SELECT featureImg,slug,title FROM post WHERE postStat = '1' ORDER BY pId DESC");
  if ($query) {
    foreach ($query as $row) {
      $img = $row['featureImg'];
      $key = 1527781;
      $iv = '123456789abcdefg';
      $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
  ?>
      <a href="<?php echo convertString($row['slug']); ?>" title="<?= $row['title']; ?>">
        <img src="images/postimage/<?= $image; ?>" alt="<?= $row['title']; ?>">
      </a>
  <?php }
  } ?>
  <?php
  $selectImg = mysqli_query($con, 'SELECT * FROM postImg ORDER BY imageId DESC');
  if ($selectImg && mysqli_num_rows($selectImg) > 0) {
    $sno = 1;
    foreach ($selectImg as $row) {
      $img = $row['imageUrl'];
      $key = 1527781991;
      $iv = '123456789abcdefg';
      $image = openssl_decrypt($img, 'AES-256-CBC', $key, 0, $iv);
  ?>
     <a href="<?= $row['url'];?>">
        <img src="images/imagePost/<?= $image; ?>" alt="<?= $row['url']; ?>">
      </a>
  <?php }
  } ?>
</div>
<?php include "footer.php";
mysqli_close($con);
?>


<script>
  const navItems = document.querySelectorAll(".navbar .nav-links:nth-child(2) li a");
  navItems.forEach(navitem => {
    navitem.classList.remove("active");
    navItems[navItems.length-1].classList.add("active");
  });
  document.title += " // Gallary";
</script>