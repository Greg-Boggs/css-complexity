<?php

include('common.php');

// Make the URL valid if http is missing, and check to make sure the result is a URL before requesting it
function wash_url($url) {

    //check to see if the address has http:// add it if not.
    $url = (substr(ltrim($url), 0, 7) != 'http://' ? 'http://' : '') . $url;

    if ( is_url($url) ) {
        return $url;
    }
}

function is_url($url) {

    return preg_match(URL_MATCH, $url);
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

function post_page($url, $user, $pass) {

    //create array of data to be posted
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
