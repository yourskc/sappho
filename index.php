<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "global.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
        <link rel="alternate" type="application/rss+xml" title="rss" href="<?php echo $sappho_path; ?>/rss/" />
    </head>
    <body>
        <div id="container">
            <h1><?php echo $sappho_title; ?></h1>
<?php

// i can't figure out a better way to do this with joins. D:
$sql = "SELECT photo_collection.collection_id,  ".
       "       photo_collection.search_path,    ".
       "       photo_collection.title,          ".
       "       COUNT(*) AS num_sets             ".
       "FROM photo_collection                   ".
       "LEFT JOIN photo_set ON photo_set.collection_id=photo_collection.collection_id ".
       "GROUP BY photo_collection.collection_id ";
if (!$result_a = mysql_query($sql)) print_error();
while ($coll = mysql_fetch_array($result_a)) {

    echo "            <h2>{$coll[title]}</h2>\n";

    $sql = "SELECT photo_set.set_id,            ".
           "       photo_set.search_path,       ".
           "       photo_set.title,             ".
           "       photo_set.body,              ".
           "       COUNT(*) AS num_photos       ".
           "FROM photo_set                      ".
           "LEFT JOIN photo_image ON photo_image.set_id=photo_set.set_id ".
           "WHERE photo_set.collection_id='{$coll['collection_id']}' ".
           "GROUP BY photo_set.set_id           ".
           "LIMIT $num_sets_on_index            ";
    if (!$result_b = mysql_query($sql)) print_error();
    while ($set = mysql_fetch_array($result_b)) {

        echo "            <h3><a href=\"set/{$set['search_path']}/\">{$set['title']}</a></h3>\n";
        echo "            <h4>{$set['body']}</h4>\n";

    };

    if ($coll['num_sets'] > $num_sets_on_index) {
        echo "            <p class=\"small\"><a href=\"collection/{$coll['search_path']}/\">view all {$coll['num_sets']}</a></p>\n";
    };

};

?>
            <div id="index_info">see <a href="recent/">recently added photos</a>. this is a <a href="http://code.google.com/p/sappho/">sappho</a> photo album.</div>
        </div>
    </body>
</html>
