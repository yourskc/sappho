<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "global.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
<?php

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
$image = mysql_fetch_array($result);

?>
        <title><?php echo $sappho_title." &mdash; ".$image["title"]; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path."\">".$sappho_title; ?></a></h1>
            <h2><?php echo $image["title"]; ?></h2>
<?php

echo "            <img src=\"http://$s3_bucket.s3.amazonaws.com/$s3_path/b/{$image["filename"]}.jpg\" alt=\"{$image["title"]}\" id=\"photo\" />\n";

echo "            <div id=\"exif\">\n";
echo "                <div id=\"title\">photo information</div>\n";
echo "                {$image["exif_cameramodel"]}<br />\n";
echo "                shutter: {$image["exif_exposuretime"]} s<br />\n";
echo "                <i>f</i>: {$image["exif_fnumber"]}<br />\n";
echo "                iso: {$image["exif_isospeedratings"]}<br />\n";
echo "                focal: {$image["exif_focallength"]} mm<br />\n";
echo "                ".($image["exif_flash"]?"flash fired":"no flash")."<br />\n";
echo "                {$image["exif_datetimeoriginal"]}\n";
echo "            </div>\n";

echo "            <div id=\"caption\">".nl2br($image["caption"])."<br /><br /></div>\n";


$sql = "SELECT image_id                     ".
       "FROM photo_image                    ".
       "WHERE set_id='{$image["set_id"]}'   ".
       "   && sort > {$image["sort"]}       ".
       "ORDER BY sort                       ".
       "LIMIT 1                             ";
if (!$result = mysql_query($sql)) print_error();

if (mysql_num_rows($result) === 1) {
    
    $next_img = mysql_fetch_array($result);
    echo "            <div id=\"link_next\"><a href=\"$sappho_path/photo/{$next_img["image_id"]}/\">view next in set</a></div>\n";

};

echo "            <div id=\"link_full\"><a href=\"http://$s3_bucket.s3.amazonaws.com/$s3_path/a/{$image["filename"]}.jpg\">view in high resolution</a></div>\n";

?>
        </div>
    </body>
</html>
