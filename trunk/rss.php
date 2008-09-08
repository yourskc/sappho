<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "global.php";

header("Content-Type: application/xml; charset=ISO-8859-1");

?>
<?xml version="1.0" encoding="iso-8859-1" ?>
<rss version="2.0"> 
    <channel> 
        <title><?php echo $sappho_title; ?> -- recently added</title>
        <link><?php echo $sappho_path; ?>/</link>
        <description>view the most recently added photos.</description>
        <language>English</language>

<?php

$sql = "SELECT image_id,            ".
       "       filename,            ".
       "       title,               ".
       "       caption,             ".
       "       thumb_width,         ".
       "       thumb_height         ".
       "FROM photo_image            ".
       "ORDER BY date_imported DESC ".
       "LIMIT 20                    ";
if (!$result = mysql_query($sql)) print_error();
while ($image = mysql_fetch_array($result)) {

?>
        <item>
            <title><?php echo $image["title"]; ?></title>
            <link><?php echo $sappho_path."/photo/".$image["image_id"]; ?>/</link>
            <description><?php echo(htmlentities("<img src=\"http://$s3_bucket.s3.amazonaws.com/$s3_path/c/{$image["filename"]}.jpg\" alt=\"{$image["title"]}\" width=\"{$image["thumb_width"]}\" height=\"{$image["thumb_height"]}\" />")); ?> <?php echo $image["caption"]; ?></description>
        </item>
<?php

};

?>
    </channel>
</rss>
