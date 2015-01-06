---
layout: default
---
<?php
  include ("lib/functions.php");
  $url = '';
  $total = 0;
  $total_size = 0;
  $unused_size = 0;
  $i = 0;
  $content = '';
  $matches = array();
  $doc = new DOMDocument();
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

  if (isset($_GET['url']) && !empty($_GET['url'])) {
    $url = strip_tags($_GET['url']);

    // TODO: Improve test for valid domain.
    $url = wash_url($url);

    // Load the remote file into a local document.Then extract all the CSS files
    // TODO: add support for CSS Import and inline styles
    $doc->loadHTML(get_page($url));
    $css_files = $doc->getElementsByTagName('link');
    foreach ($css_files as $css_file) {
      $i++;
      if (strtolower($css_file->getAttribute('rel')) == "stylesheet") {

        // Remove any questions
        $file_name = explode("?", $css_file->getAttribute('href'), 2);

        // Add base URL if the path is relative
        $pos = strpos($file_name[0], $url);
        if ($pos === false) {
          $file_name[0] = $url . '/' . $file_name[0];
        }

        // Create a single stylesheet to make scoring faster to code.
        // This approach my break on huge sites. Maybe I should ajax 1 file at a time incrementally testing them.
        $content .= get_page($file_name[0]);
      }
    }
    if (empty($content)) {
      header('Location: /?error=content');
    }

    // Some stack overflow code to tally the results.
    foreach ($tests as $test) {
      $matches[$test] = $count = substr_count($content, $test);
      $total += $count;
    }

    // Calculate unused CSS
    $total_size = get_size($content);
    $used_size = get_size(shell_exec("/usr/bin/uncss $url"));
    $unused_size = $total_size - $used_size;
  }
?>

<div class="wrap">
  <a href="/">New Scan</a> | <a href="/scan.php?url=<?php echo $url; ?>&email=<?php echo $email; ?>">Rescan</a>
  <fieldset class="results">
    <legend>Found <?php echo $i; ?> files on <?php echo $url; ?></legend>
    <ul>
      <?php foreach ($matches as $key => $match) { ?>
        <li><?php echo "$key: $match"; ?></li>
      <?php
        }
      ?>
    </ul>
    <ul>
      <li>Total CSS: <?php echo $total_size; ?> Bytes</li>
      <li>Unused CSS: <?php echo $unused_size; ?> Bytes</li>
    </ul>
    <ul>
      <li>Total rules: <?php echo $total; ?></li>
    </ul>
  </fieldset>
</div>
