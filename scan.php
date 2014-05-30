---
layout: default
---
<?php
  include ("lib/functions.php");
  $target = strip_tags($_GET['url']);
  $email = strip_tags($_GET['email']);
  $url = wash_url($target);

  if (isset($url) && $url) {
    $content = get_page($url);
    if (empty($content) ) {
      header('Location: /?error=content');
    }
  }
  $matches = array(
    'font-size' => substr_count($content, 'font-size'),
    'color' => substr_count($content, 'color'),
  );

?>
<div class="wrap">
  <a href="/">New Scan</a> | <a href="/scan.php?url=<?php echo $target; ?>&email=<?php echo $email; ?>">Rescan</a>
  <fieldset class="results">
    <legend>Scan Results for <?php echo $url; ?></legend>
    <ol>
      <?php foreach ($matches as $key => $match) { ?>
        <li><?php echo "$key: $match"; ?></li>
      <?php
        }
      ?>
    </ol>
  </fieldset>
</div>
