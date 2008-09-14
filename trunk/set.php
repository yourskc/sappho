<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "global.php";

if (!empty($_GET['id'])) {

    $set_id = clean($_GET['id']);
    $sql = "SELECT title,           ".
           "       body,            ".
           "       collection_id    ".
           "FROM photo_set          ".
           "WHERE set_id='$set_id'";
    if (!$result = mysql_query($sql)) print_error();
    list($set_title, $set_body, $coll_id) = mysql_fetch_row($result);
    if (mysql_num_rows($result) == 0) {
        die("i don't know which set you are looking for.");
    };

} else if (!empty($_GET['search_path'])) {

    $search_path = clean($_GET['search_path']);
    $sql = "SELECT set_id,                  ".
           "       title,                   ".
           "       body,                    ".
           "       collection_id            ".
           "FROM photo_set                  ".
           "WHERE search_path='$search_path'";
    if (!$result = mysql_query($sql)) print_error();
    list($set_id, $set_title, $set_body, $coll_id) = mysql_fetch_row($result);
    if (mysql_num_rows($result) == 0) {
        die("i don't know which set you are looking for.");
    };

} else {

    die("i don't know which set you are looking for.");

};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?php echo $sappho_title." &mdash; ".$set_title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path."\">".$sappho_title; ?></a></h1>
            <div id="collections">
                <ul>
<?php

$sql = "SELECT collection_id,  ".
       "       search_path,    ".
       "       title           ".
       "FROM photo_collection  ".
       "ORDER BY sort          ";
if (!$result = mysql_query($sql)) print_error();
while ($coll = mysql_fetch_array($result)) {

    echo "                    ";

    if ($coll['collection_id'] == $coll_id) {
        echo "<li class=\"bolded\"><a href=\"$sappho_path/collection/{$coll['search_path']}/\">{$coll['title']}</a></li>\n";
    } else {
        echo "<li><a href=\"$sappho_path/collection/{$coll['search_path']}/\">{$coll['title']}</a></li>\n";
    };

};

?>
                </ul>
            </div>
            <h2><?php echo $set_title; ?></h2>
<?php

$sql = "SELECT image_id,        ".
       "       filename,        ".
       "       title,           ".
       "       thumb_width,     ".
       "       thumb_height     ".
       "FROM photo_image        ".
       "WHERE set_id='$set_id'  ".
       "ORDER BY sort           ";
if (!$result = mysql_query($sql)) print_error();
while ($image = mysql_fetch_array($result)) {

    $x = $image["thumb_width"];
    $y = $image["thumb_height"];
    $x_pad = ($x < $thumbnail_size) ? ($thumbnail_size-$x)/2 : 0;
    $y_pad = ($y < $thumbnail_size) ? ($thumbnail_size-$y)/2 : 0;

    echo "            <div class=\"set_thumbnail\">";
    echo "<a href=\"$sappho_path/photo/{$image["image_id"]}/\">";
    echo "<img src=\"http://$s3_bucket.s3.amazonaws.com/$s3_path/c/{$image["filename"]}.jpg\" alt=\"{$image["title"]}\" style=\"margin: {$y_pad}px {$x_pad}px;\"/>";
    echo "</a></div>\n";

};

?>
            <div id="set_info"><?php echo $set_body; ?></div>
        </div>
    </body>
</html>
