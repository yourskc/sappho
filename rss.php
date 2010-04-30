<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "global.php";

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

header("Content-Type: application/xml; charset=utf-8");

echo <<<EOF
<?xml version="1.0" encoding="utf-8" ?>
<rss version="2.0"> 
    <channel> 
        <title>{$rss_title}</title>
        <link>{$sappho_path}</link>
        <description>{$rss_description}</description>
        <language>{$rss_lang}</language>

EOF;

while ($image = mysql_fetch_array($result)) {

    $title = outputxml($image['title']);
    $url = outputxml($sappho_path.'/image/'.$image['image_id'].'/');
    $caption = empty($image['caption']) ? '' : "\n                <br /><br />".output($image['caption']);
    $description = <<<EOF
                <img src="http://{$s3_host}/{$s3_path}a/{$image['filename']}.jpg"
                     alt="{$image['title']}"
                     width="{$image['thumb_width']}"
                     height="{$image['thumb_height']}" />{$caption}
EOF;
    $description = outputxml($description);

    echo <<<EOF
        <item>
            <title>{$title}</title>
            <link>{$url}</link>
            <guid>{$url}</guid>
            <description>
{$description}
            </description>
        </item>

EOF;

};

?>
    </channel>
</rss>
