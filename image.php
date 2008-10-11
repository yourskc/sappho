<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "global.php";

header('Content-Type: text/html; charset=iso-8859-1');

$image_id = clean($_GET['id']);

$sql = "SELECT filename,                    ".
       "       title,                       ".
       "       caption,                     ".
       "       sort,                        ".
       "       set_id,                      ".
       "       exif_cameramodel,            ".
       "       exif_exposuretime,           ".
       "       exif_fnumber,                ".
       "       exif_isospeedratings,        ".
       "       exif_focallength,            ".
       "       exif_flash,                  ".
       "       exif_datetimeoriginal        ".
       "FROM photo_image                    ".
       "WHERE image_id='$image_id'          ";
if (!$result = mysql_query($sql)) print_error();

if (mysql_num_rows($result) !== 1) {

    die("could not find the specified image.");

};

$image = mysql_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?php echo $sappho_title." &mdash; ".output($image["title"]); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path."\">".$sappho_title; ?></a></h1>
            <h2><?php echo output($image["title"]); ?></h2>
<?php

echo "            <img src=\"http://$s3_bucket.s3.amazonaws.com/$s3_path/b/{$image["filename"]}.jpg\" alt=\"".output($image["title"])."\" id=\"photo\" />\n";

echo "            <div id=\"photo_info\">\n";
echo "                <div id=\"title\">photo information</div>\n";
echo "                ".output($image["exif_cameramodel"])."<br />\n";
echo "                shutter: ".output($image["exif_exposuretime"])." s<br />\n";
echo "                <i>f</i>: ".output($image["exif_fnumber"])."<br />\n";
echo "                iso: ".output($image["exif_isospeedratings"])."<br />\n";
echo "                focal: ".output($image["exif_focallength"])." mm<br />\n";
echo "                ".($image["exif_flash"]?"flash fired":"no flash")."<br />\n";
echo "                ".output($image["exif_datetimeoriginal"])."<br />\n";
echo "                <a href=\"http://$s3_bucket.s3.amazonaws.com/$s3_path/a/{$image["filename"]}.jpg\">high resolution version</a>\n";
echo "            </div>\n";

if (!empty($image["caption"])) {

    echo "            <div id=\"caption\">".output($image["caption"])."<br /><br /></div>\n";

};


$sql = "SELECT image_id                     ".
       "FROM photo_image                    ".
       "WHERE set_id='{$image["set_id"]}'   ".
       "   && sort > {$image["sort"]}       ".
       "ORDER BY sort                       ".
       "LIMIT 1                             ";
if (!$result = mysql_query($sql)) print_error();

if (mysql_num_rows($result) === 1) {
    
    $next_img = mysql_fetch_array($result);
    echo "            <div id=\"link_next\"><a href=\"$sappho_path/image/{$next_img["image_id"]}/\">view next in set</a></div>\n";

};

$sql = "SELECT search_path                  ".
       "FROM photo_set                      ".
       "WHERE set_id='{$image["set_id"]}'   ";
if (!$result = mysql_query($sql)) print_error();

if (mysql_num_rows($result) === 1) {

    $set = mysql_fetch_array($result);
    echo "            <div id=\"link_set\"><a href=\"$sappho_path/set/{$set["search_path"]}/\">return to set</a></div>\n";

};

?>
        </div>
    </body>
</html>
