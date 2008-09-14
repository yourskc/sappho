<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "global.php";

if (!empty($_GET['id'])) {

    $coll_id = clean($_GET['id']);
    $sql = "SELECT title                    ".
           "FROM photo_collection           ".
           "WHERE collection_id='$coll_id'  ";
    if (!$result = mysql_query($sql)) print_error();
    list($coll_title) = mysql_fetch_row($result);
    if (mysql_num_rows($result) == 0) {
        die("i don't know which collection you are looking for.");
    };

} else if (!empty($_GET['search_path'])) {

    $search_path = clean($_GET['search_path']);
    $sql = "SELECT collection_id,           ".
           "       title                    ".
           "FROM photo_collection           ".
           "WHERE search_path='$search_path'";
    if (!$result = mysql_query($sql)) print_error();
    list($coll_id, $coll_title) = mysql_fetch_row($result);
    if (mysql_num_rows($result) == 0) {
        die("i don't know which collection you are looking for.");
    };

} else {

    die("i don't know which collection you are looking for.");

};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
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

$sql = "SELECT collection_id,  ".
       "       search_path,    ".
       "       title           ".
       "FROM photo_collection  ".
       "ORDER BY sort          ";
if (!$result = mysql_query($sql)) print_error();
while ($coll = mysql_fetch_array($result)) {

    echo "                    ";

    if ($coll['collection_id'] == $coll_id) {
        echo "<li class=\"bordered\">{$coll['title']}</li>\n";
    } else {
        echo "<li><a href=\"$sappho_path/collection/{$coll['search_path']}/\">{$coll['title']}</a></li>\n";
    };

};
        
echo "                </ul>\n";
echo "            </div>\n";

$sql = "SELECT search_path,             ".
       "       title,                   ".
       "       body                     ".
       "FROM photo_set                  ".
       "WHERE collection_id='$coll_id'  ".
       "ORDER BY date_updated DESC      ";
if (!$result_b = mysql_query($sql)) print_error();
while ($set = mysql_fetch_array($result_b)) {

    echo "            <h3><a href=\"$sappho_path/set/{$set['search_path']}/\">{$set['title']}</a></h3>\n";
    echo "            <h4>{$set['body']}</h4>\n";

};

?>
        </div>
    </body>
</html>
