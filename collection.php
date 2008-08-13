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
$coll_id = clean($_GET['id']);
$sql = "SELECT title FROM photo_collection WHERE collection_id='$coll_id'";
if (!$result = mysql_query($sql)) print_error();
list($coll_title) = mysql_fetch_row($result);
?>
        <title><?php echo $sappho_title." &mdash; ".$coll_title; ?></title>
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

$sql = "SELECT photo_collection.collection_id,  ".
       "       photo_collection.title           ".
       "FROM photo_collection                   ";
if (!$result = mysql_query($sql)) print_error();
while ($coll = mysql_fetch_array($result)) {

    echo "                    ";

    if ($coll['collection_id'] == $coll_id) {
        echo "<li class=\"bordered\">{$coll['title']}</li>\n";
    } else {
        echo "<li><a href=\"$sappho_path/collection/{$coll['collection_id']}/\">{$coll['title']}</a></li>\n";
    };

};
        
echo "                </ul>\n";
echo "            </div>\n";

$sql = "SELECT photo_set.set_id,            ".
       "       photo_set.title,             ".
       "       photo_set.body,              ".
       "       COUNT(*) AS num_photos       ".
       "FROM photo_set                      ".
       "LEFT JOIN photo_image ON photo_image.set_id=photo_set.set_id ".
       "WHERE photo_set.collection_id='{$coll_id}' ".
       "GROUP BY photo_set.set_id           ";
if (!$result_b = mysql_query($sql)) print_error();
while ($set = mysql_fetch_array($result_b)) {

    echo "            <h3><a href=\"$sappho_path/set/{$set['set_id']}/\">{$set[title]}</a></h3>\n";
    echo "            <h4>{$set[body]}</h4>\n";

};

?>
        </div>
    </body>
</html>
