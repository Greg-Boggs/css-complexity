---
layout: default
---
<?php
  include ("lib/functions.php");
  $url = '';
  $total = 0;
  $total_size = 0;
  $unused_size = 0;
  $count = 0;
  $i = 0;
  $content = '';
  $matches = array();
  $tests = array(
    'h1',
    'h2',
    'h3',
    'h4',
    'h5',
    'h6',
    'padding: 0',
    'margin: 0',
    'padding-top: 0',
    'padding-left: 0',
    'padding-bottom: 0',
    'padding-right: 0',
    'margin-top: 0',
    'margin-left: 0',
    'margin-bottom: 0',
    'margin-right: 0',
    'margin',
    'padding',
    'font',
    'font-size',
    'font-family',
    'font-weight',
    '!important',
    'color',
    'hex',
    '#fff',
    '#ffffff',
    'background',
    );

  if (isset($_GET['url']) && !empty($_GET['url'])) {

    // TODO: Improve test for valid domain.
    $url = wash_url($url);
    if (empty($url)) {
      header('Location: /?error=url');
      die();
    }

    // Load the remote file into a local document.Then extract all the CSS files
    // TODO: add support for CSS Import and inline styles
    $content = get_css($url, $i);
    }
    if (empty($content)) {
      header('Location: /?error=content');
      die();
    }

    // Some stack overflow code to tally the results.
    foreach ($tests as $test) {
      $matches[$test] = $count = substr_count($content, $test);
      $total += $count;
    }

    // Calculate unused CSS
    $total_size = get_size($content);
    //$used_size = get_size(shell_exec('/usr/bin/uncss' . escapeshellarg(string $url)));
    //$unused_size = $total_size - $used_size;

    // Convert to KB
    $unused_size = number_format($unused_size / 1024, 2);
    $total_size = number_format($total_size / 1024, 2);
?>

<div class="wrap">
  <a href="/">New Scan</a> | <a href="/scan.php?url=<?php echo $url; ?>">Rescan</a>
  <fieldset class="results">
    <legend>Found <?php echo $i; ?> CSS files on <?php echo $url; ?></legend>
    <table class="score">
      <tr>
        <th>Score</th>
        <th>Files</th>
        <th>Size</th>
      </tr>
      <tr>
        <td><?php echo $total; ?></td>
        <td><?php echo $i; ?></td>
        <td><?php echo $total_size; ?> KB</td>
      </tr>
    </table>
    <table class="body">
      <tr class="title">
        <th>Selector</th>
        <th>Count</th>
      </tr>
      <?php foreach ($matches as $key => $match) { ?>
        <tr>
          <td class="key"><?php echo $key; ?></td>
          <td class="match"><?php echo $match; ?></td>
        </tr>
      <?php
        }
      ?>
    </table>
  </fieldset>
</div>
