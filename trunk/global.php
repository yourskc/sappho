<?php
/******************************************************************************
 *                                   sappho                                   *
 ******************************************************************************
 * Copyright (c) 2008 Jonathan Lucas Reddinger <lucas@wingedleopard.net>      *
 *                                                                            *
 * Permission to use, copy, modify, and distribute this software for any      *
 * purpose with or without fee is hereby granted, provided that the above     *
 * copyright notice and this permission notice appear in all copies.          *
 *                                                                            *
 * THE SOFTWARE IS PROVIDED "AS IS" AND THE AUTHOR DISCLAIMS ALL WARRANTIES   *
 * WITH REGARD TO THIS SOFTWARE INCLUDING ALL IMPLIED WARRANTIES OF           *
 * MERCHANTABILITY AND FITNESS. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR    *
 * ANY SPECIAL, DIRECT, INDIRECT, OR CONSEQUENTIAL DAMAGES OR ANY DAMAGES     *
 * WHATSOEVER RESULTING FROM LOSS OF USE, DATA OR PROFITS, WHETHER IN AN      *
 * ACTION OF CONTRACT, NEGLIGENCE OR OTHER TORTIOUS ACTION, ARISING OUT OF    *
 * OR IN CONNECTION WITH THE USE OR PERFORMANCE OF THIS SOFTWARE.             *
 ******************************************************************************/


/*******************************
 * GET CONFIGURATION VARIABLES *
 *******************************/
require_once "config.php";


/********************************************************
 * REMOVE MAGIC QUOTES                                  *
 *                                                      *
 * php.net/manual/en/security.magicquotes.disabling.php *
 ********************************************************/
if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value) {
        $value = is_array($value) ?
            array_map('stripslashes_deep', $value) :
            stripslashes($value);
        return $value;
    };
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
};


/***************
 * PRINT ERROR *
 ***************/
function print_error() {
    if (mysql_error()) {
        echo "<strong>mysql dbms error ".mysql_errno()."</strong>: ".
            mysql_error()."\n";
    } else {
        echo "<strong>error</strong>: could not connect to mysql dbms.\n";
    };
    die();
};


/***************
 * CLEAN INPUT *
 ***************/
function clean($text) {
    $text = mysql_real_escape_string($text);
    return $text;
};


/*****************
 * FORMAT OUTPUT *
 *****************/
function output($text) {
    global $entry_find, $entry_replace;
    $text = htmlentities($text, ENT_QUOTES, "ISO-8859-1", FALSE);
    $text = str_replace($entry_find, $entry_replace, $text);
    $text = nl2br($text);
    return $text;
};


/**************************************
 * LIST FILES FROM S3 BUCKET AND PATH *
 **************************************/
function file_listing() {
    require_once "S3.php";
    global $aws_access, $aws_secret;
    global $s3_bucket, $s3_path;
    $listing = array();
    $s3 = new S3($aws_access, $aws_secret);
    foreach ($s3->getBucket($s3_bucket, $s3_path."/") as $file) {
        if (preg_match('@^.*/([ab])/([0-9]+)\.jpg$@i', $file['name'], $match)) {
            $listing[$match[2]][$match[1]] = array('size' => $file['size'], 'time' => $file['time']);
        };
    };
    return $listing;
};


/***********************************************
 * LIST NEW FILES THAT ARE IN S3 BUT NOT IN DB *
 ***********************************************/
function new_files() {
    $db_list = array();
    $sql = "SELECT filename FROM photo_image";
    if (!$result = mysql_query($sql)) print_error();
    while (list($filename) = mysql_fetch_array($result)) {
        $db_list[] = $filename;
    };
    $new_list = array();
    foreach(file_listing() as $id => $ver) {
        if (!in_array($id, $db_list)) {
            if (!empty($ver['a']) && !empty($ver['b'])) {
                $new_list[$id] = $ver;
            };
        };
    };
    return $new_list;
};


/*************************
 * CONNECT TO MYSQL DBMS *
 *************************/
if (!$dbmscnx = @mysql_pconnect($dbms_host, $dbms_user, $dbms_pass)) print_error();
if (!mysql_select_db($dbms_db)) print_error();

?>
