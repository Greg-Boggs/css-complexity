<?php

include('common.php');

// Make the URL valid if http is missing, and check to make sure the result is a URL before requesting it
function wash_url($url) {

    $url = strip_tags($_GET['url']);
    $url = trim($url, '!"#$%&\'()*+,-./@:;<=>[\\]^_`{|}~');
    $url = check_plain($url);

    //check to see if the address has http add it if not.
    $url = (substr(ltrim($url), 0, 4) != 'http' ? 'http://' : '') . $url;

    if ( is_url($url) ) {
        return $url;
    }
}

function is_url($url) {

    return preg_match(URL_MATCH, $url);
}

function check_plain($text) {

    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

// Grab the URL
// http://www.php-mysql-tutorial.com/wikis/php-tutorial/reading-a-remote-file-using-php.aspx
function get_page($url) {

    if ( function_exists('curl_init') ) {

        $curl_connection = curl_init();
        curl_setopt($curl_connection, CURLOPT_URL, $url);
        curl_setopt($curl_connection, CURLOPT_HEADER, false);
        curl_setopt($curl_connection, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl_connection, CURLOPT_USERAGENT, AGENT);
        $content = curl_exec($curl_connection);
        curl_close($curl_connection);

        return $content;
    } else {
        die ('Woah, you need to install CURL on this server.<br />');
    }
}

function get_css($url, &$i = 0) {
    $css = '';
    $doc = new DOMDocument();

    libxml_use_internal_errors(true);
    $doc->loadHTML(get_page($url));
    $links = $doc->getElementsByTagName('link');
    foreach ($links as $link) {
        if (strtolower($link->getAttribute('rel')) == "stylesheet") {
            $i++;
            $file_name = $link->getAttribute('href');

            $file_name = clean_name($url, $file_name);
            $css .= get_page($file_name);
        }
    }
    $page = (get_page($url));
    preg_match_all('/url\(\"([^)]+)\"\)/', $page, $matches);
    foreach($matches[1] as $css_file) {
        $i++;
        $file_name = clean_name($url, $css_file);
        $css .= get_page($file_name);
    }

    return $css;
}

function clean_name($url, $file_name) {

    // Remove any questions
    $file_name = explode("?", $file_name, 2);

    // Add base URL if the path is relative
    $pos = strpos($file_name[0], 'http');
    if ($pos === false) {
        $parse = parse_url($url);
        if (!empty($parse['path'])) {
            $path_parts = pathinfo($parse['path']);
            if (!empty($path_parts['dirname'])) {
                $file_name[0] = $parse['host'] . '/' . $path_parts['dirname'] . '/' . $file_name[0];
            }
        } else {
            $file_name[0] = $parse['host'] . '/' . $file_name[0];
        }
    }

    return $file_name[0];
}
function post_page($url, $user, $pass) {
    $post_items = array();

    // Data to be posted.
    $post_data['log'] = $user;
    $post_data['pwd'] = $pass;

    //traverse array and prepare data for posting (key1=value1)
    foreach ( $post_data as $key => $value) {
        $post_items[] = $key . '=' . $value;
    }

    //create the final string to be posted using implode()
    $post_string = implode ('&', $post_items);

    //create cURL connection
    $curl_connection = curl_init($url);

    //set options
    curl_setopt($curl_connection, CURLOPT_HEADER, false);
    curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
    curl_setopt($curl_connection, CURLOPT_USERAGENT, AGENT);
    curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);

    //set data to be posted
    curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);

    //perform our request
    $result = curl_exec($curl_connection);

    //close the connection
    curl_close($curl_connection);

    return $result;
}

/* Trick get the size of a file */
function get_size($var) {
    $start_memory = memory_get_usage();
    $var = unserialize(serialize($var));

    return memory_get_usage() - $start_memory - PHP_INT_SIZE * 8;
}
