<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "../global.php";


if (!empty($_GET["delete"])) {

    $set_id = clean($_GET["delete"]);
    $sql = "DELETE FROM photo_set   ".
           "WHERE set_id='$set_id'  ";
    if (!$result = mysql_query($sql)) print_error();

    header("Location: sets.php");

};


if (!empty($_POST["edit"])) {

    $set_id  = clean($_POST["edit"]);
    $coll_id = clean($_POST["coll_id"]);
    $title   = clean($_POST["title"]);
    $body    = clean($_POST["body"]);
    $sql = "UPDATE photo_set                ".
           "SET collection_id='$coll_id',   ".
           "    title='$title',             ".
           "    body='$body'                ".
           "WHERE set_id='$set_id'          ";
    if (!$result = mysql_query($sql)) print_error();

    header("Location: sets.php");

};


if (isset($_POST["insert"])) {

    $coll_id = clean($_POST["coll_id"]);
    $title   = clean($_POST["title"]);
    $body    = clean($_POST["body"]);
    $sql = "INSERT INTO photo_set           ".
           "SET collection_id='$coll_id',   ".
           "    title='$title',             ".
           "    body='$body'                ";
    if (!$result = mysql_query($sql)) print_error();

    header("Location: sets.php");

};


if (!empty($_GET["edit"])) {

    $set_id = clean($_GET["edit"]);
    $sql = "SELECT set_id, collection_id, title, body   ".
           "FROM photo_set                              ".
           "WHERE set_id='$set_id'                      ";
    if (!$result = mysql_query($sql)) print_error();
    $set = mysql_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?> &mdash; manage &mdash; sets</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/manage/"><?php echo $sappho_title; ?> management</a></h1>
            <h2>sets</h2>
            <h3>editing <i><?php echo $set["title"]; ?></i></h3>
            <form action="sets.php" method="post">
                <input type="text" name="title" value="<?php echo $set["title"]; ?>" /><br />
                <textarea name="body"><?php echo $set["body"]; ?></textarea><br />
                <select name="coll_id">
<?php
    $sql = "SELECT collection_id, title FROM photo_collection ORDER BY title ASC";
    if (!$result = mysql_query($sql)) print_error();
    while (list($coll_id, $col_title) = mysql_fetch_row($result)) {
        if ($coll_id == $set["collection_id"]) { $sel = " selected"; }
        else { unset($sel); };
        echo "    <option value=\"$coll_id\"$sel>$col_title</option>\n";
    };
?>
                </select><br />
                <input type="hidden" name="edit" value="<?php echo $set["set_id"]; ?>" />
                <input type="submit" />
            </form>
        </div>
    </body>
</html>
<?php

    die();

};


if (isset($_GET["insert"])) {

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?> &mdash; manage &mdash; sets</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/manage/"><?php echo $sappho_title; ?> management</a></h1>
            <h2>sets</h2>
            <h3>inserting a new row</h3>
            <form action="sets.php" method="post">
                <input type="text" name="title" /><br />
                <textarea name="body"></textarea><br />
                <select name="coll_id">
<?php
    $sql = "SELECT collection_id, title FROM photo_collection ORDER BY title ASC";
    if (!$result = mysql_query($sql)) print_error();
    while (list($coll_id, $col_title) = mysql_fetch_row($result)) {
        if ($coll_id == $set["collection_id"]) { $sel = " selected"; }
        else { unset($sel); };
        echo "    <option value=\"$coll_id\"$sel>$col_title</option>\n";
    };
?>
                </select><br />
                <input type="hidden" name="insert" />
                <input type="submit" />
            </form>
        </div>
    </body>
</html>
<?php

    die();

};


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?> &mdash; manage &mdash; sets</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/manage/"><?php echo $sappho_title; ?> management</a></h1>
            <h2>sets</h2>
            <div id="insert"><a href="sets.php?insert">insert new row</a></div>
            <div id="edit">
                <table>
                    <th>collection</th>
                    <th>title</th>
                    <th>body</th>
                    <th>created</th>
                    <th>updated</th>
                    <th>edit</th>
                    <th>del</th>
<?php

$sql = "SELECT photo_collection.title   AS col_title,   ".
       "       photo_set.set_id,                        ".
       "       photo_set.title,                         ".
       "       photo_set.body,                          ".
       "       photo_set.date_created,                  ".
       "       photo_set.date_updated                   ".
       "FROM photo_set                                  ".
       "LEFT JOIN photo_collection                      ".
       "    USING ( collection_id )                     ".
       "ORDER BY collection_id,                         ".
       "         set_id                                 ";
if (!$result = mysql_query($sql)) print_error();
while ($set = mysql_fetch_array($result)) {
    echo "                    <tr>\n";
    echo "                        <td>{$set["col_title"]}</td>\n";
    echo "                        <td>{$set["title"]}</td>\n";
    echo "                        <td>{$set["body"]}</td>\n";
    echo "                        <td>{$set["date_created"]}</td>\n";
    echo "                        <td>{$set["date_updated"]}</td>\n";
    echo "                        <td><a href=\"sets.php?edit={$set["set_id"]}\">edit</a></td>\n";
    echo "                        <td><a href=\"sets.php?delete={$set["set_id"]}\">del</a></td>\n";
    echo "                    </tr>\n";
};

?>
                </table>
            </div>
        </div>
    </body>
</html>
