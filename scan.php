---
layout: default
---
<?php
  $target = strip_tags($_GET['url']);
  $email = strip_tags($_GET['email']);
?>
<div class="wrap">
  <a href="/">New Scan</a> | <a href="/scan.php?url=<?php echo $target; ?>&email=<?php echo $email; ?>">Rescan</a>
  <fieldset class="results">
    <legend>Scan Results for <?php echo $url; ?></legend>
    <ol>
      <li><?php echo "Hello World"; ?></li>
    </ol>
  </fieldset>
</div>
