<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "global.php";

header('Content-Type: text/html; charset=iso-8859-1');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?php echo $sappho_title; ?> &mdash; recently added</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
        <link rel="alternate" type="application/rss+xml" title="rss" href="<?php echo $sappho_path; ?>/rss/" />
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
    echo "<li><a href=\"$sappho_path/collection/{$coll['search_path']}/\">".output($coll['title'])."</a></li>\n";

};

?>
                </ul>
            </div>
            <h2>recently added (<a href="<?php echo $sappho_path; ?>/rss/">rss</a>)</h2>
<?php

$sql = "SELECT image_id,            ".
       "       filename,            ".
       "       title,               ".
       "       thumb_width,         ".
       "       thumb_height         ".
       "FROM photo_image            ".
       "ORDER BY date_imported DESC ".
       "LIMIT $num_recent_photos    ";
if (!$result = mysql_query($sql)) print_error();
while ($image = mysql_fetch_array($result)) {

    $x = $image["thumb_width"];
    $y = $image["thumb_height"];
    $x_pad = ($x < $thumbnail_size) ? ($thumbnail_size-$x)/2 : 0;
    $y_pad = ($y < $thumbnail_size) ? ($thumbnail_size-$y)/2 : 0;

    echo "            <div class=\"set_thumbnail\" title=\"".output($image["title"])."\">";
    echo "<a href=\"$sappho_path/image/{$image["image_id"]}/\">";
    echo "<img src=\"http://$s3_bucket.s3.amazonaws.com/$s3_path/c/{$image["filename"]}.jpg\" alt=\"".output($image["title"])."\" style=\"margin: {$y_pad}px {$x_pad}px;\"/>";
    echo "</a></div>\n";

};

?>
        </div>
    </body>
</html>
