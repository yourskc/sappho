<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "global.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
        <title><?php echo $sappho_title; ?> &mdash; about</title>
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

?>
                </ul>
                <h2>about</h2>
                <p>this is a photo album coded in php, organized in mysql, and driven by aws s3.</p>
            </div>
        </div>
    </body>
</html>
