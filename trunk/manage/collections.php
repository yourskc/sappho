<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "../global.php";


if (!empty($_GET["delete"])) {

    $coll_id = clean($_GET["delete"]);
    $sql = "DELETE FROM photo_collection    ".
           "WHERE collection_id='$coll_id'  ";
    if (!$result = mysql_query($sql)) print_error();

    header("Location: collections.php");

};


if (!empty($_POST["edit"])) {

    $coll_id = clean($_POST["edit"]);
    $title   = clean($_POST["title"]);
    $body    = clean($_POST["body"]);
    $sql = "UPDATE photo_collection         ".
           "SET title='$title',             ".
           "    body='$body'                ".
           "WHERE collection_id='$coll_id'  ";
    if (!$result = mysql_query($sql)) print_error();

    header("Location: collections.php");

};


if (isset($_POST["insert"])) {

    $title   = clean($_POST["title"]);
    $body    = clean($_POST["body"]);
    $sql = "INSERT INTO photo_collection    ".
           "SET title='$title',             ".
           "    body='$body'                ";
    if (!$result = mysql_query($sql)) print_error();

    header("Location: collections.php");

};


if (!empty($_GET["edit"])) {

    $coll_id = clean($_GET["edit"]);
    $sql = "SELECT collection_id, title, body   ".
           "FROM photo_collection               ".
           "WHERE collection_id='$coll_id'      ";
    if (!$result = mysql_query($sql)) print_error();
    $col = mysql_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?> &mdash; manage &mdash; collections</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/manage/"><?php echo $sappho_title; ?> management</a></h1>
            <h2>collections</h2>
            <h3>editing <i><?php echo $col["title"]; ?></i></h3>
            <div id="edit">
                <form action="collections.php" method="post">
                    <input type="text" name="title" value="<?php echo $col["title"]; ?>" /><br />
                    <textarea name="body" rows="8"><?php echo $col["body"]; ?></textarea><br />
                    <input type="hidden" name="edit" value="<?php echo $col["collection_id"]; ?>" />
                    <input type="submit" />
                </form>
            </div>
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
    <title><?php echo $sappho_title; ?> &mdash; manage &mdash; collections</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/manage/"><?php echo $sappho_title; ?> management</a></h1>
            <h2>collections</h2>
            <h3>inserting a new row</h3>
            <div id="edit">
                <form action="collections.php" method="post">
                    <input type="text" name="title" /><br />
                    <textarea name="body" rows="8"></textarea><br />
                    <input type="hidden" name="insert" />
                    <input type="submit" />
                </form>
            </div>
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
    <title><?php echo $sappho_title; ?> &mdash; manage &mdash; collections</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/manage/"><?php echo $sappho_title; ?> management</a></h1>
            <h2>collections</h2>
            <div id="insert"><a href="collections.php?insert">insert new row</a></div>
            <div id="list">
                <table>
                    <tr>
                        <th>title</th>
                        <th>body</th>
                        <th>edit</th>
                        <th>del</th>
                    </tr>
<?php

$sql = "SELECT collection_id, title, body   ".
       "FROM photo_collection               ".
       "ORDER BY collection_id              ";
if (!$result = mysql_query($sql)) print_error();
while ($col = mysql_fetch_array($result)) {
    echo "                    <tr>\n";
    echo "                        <td>{$col["title"]}</td>\n";
    echo "                        <td>{$col["body"]}</td>\n";
    echo "                        <td><a href=\"collections.php?edit={$col["collection_id"]}\">edit</a></td>\n";
    echo "                        <td><a href=\"collections.php?delete={$col["collection_id"]}\">del</a></td>\n";
    echo "                    </tr>\n";
};

?>
                </table>
            </div>
        </div>
    </body>
</html>
