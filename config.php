<?php

/*****************************
 * MYSQL AND AWS CREDENTIALS *
 *****************************/
$dbms_host  = "localhost";
$dbms_user  = "user";
$dbms_pass  = "pass";
$dbms_db    = "database";

$aws_access = "abcdef";
$aws_secret = "123456789abcdefg";



/*****************
 * CONFIGURATION *
 *****************/
$sappho_path = 'http://www.domain.com/path/to/sappho';
$sappho_title = 'photo album';

$num_sets_on_index = 3;
$thumbnail_size = 120;
$num_recent_photos = 12;

date_default_timezone_set('America/Denver');
$date_format = 'F jS\, Y, g\:i a';

$entry_find = array('--', '...', '  ');
$entry_replace = array('&mdash;', '&hellip;', ' &nbsp; ');

$rss_title = 'photo album: recently added';
$rss_description = 'view the most recently added photos.';
$rss_lang = 'en-us';

$s3_bucket = 'some-bucket';
$s3_path = 'path/to/s3/images';
$s3_host = $s3_bucket.'.s3.amazonaws.com';

?>
