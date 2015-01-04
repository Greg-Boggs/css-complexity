---
layout: default
---
<?php
  include ("lib/functions.php");
  $total = 0;
  $content = '';
  $matches = '';

  $url = strip_tags($_GET['url']);
  $email = strip_tags($_GET['email']);
  $url = wash_url($url);

  if (isset($url) && $url) {
    $content = get_page($url);
    if (empty($content) ) {
      header('Location: /?error=content');
    }
  }

  $tests = array(
    'h1',
    'h2',
    'h3',
    'h4',
    'h5',
    'h6',
    'margin',
    'padding',
    'margin',
    'padding: 0',
    'margin: 0',
    'font',
    'font-size',
    'font-family',
    '!important',
    'color',
    'hex',
    '#fff',
    'background',
  );

  foreach ($tests as $test) {
    $matches[$test] = $count = substr_count($content, $test);
  }
  $total += $count;

  $size = get_size(shell_exec("/usr/bin/uncss $url"));
?>

<div class="wrap">
  <a href="/">New Scan</a> | <a href="/scan.php?url=<?php echo $target; ?>&email=<?php echo $email; ?>">Rescan</a>
  <fieldset class="results">
    <legend>Scan Results for <?php echo $url; ?></legend>
    <ul>
      <?php foreach ($matches as $key => $match) { ?>
        <li><?php echo "$key: $match"; ?></li>
      <?php
        }
      ?>
    </ul>
    <ul>
      <li>Total rules: <?php echo $total; ?></li>
    </ul>
    <ul>
      <li>Size: <?php echo $size; ?></li>
    </ul>
  </fieldset>
</div>
