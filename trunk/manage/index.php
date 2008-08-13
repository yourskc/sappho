<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "../global.php";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?> &mdash; manage</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><?php echo $sappho_title; ?> management</h1>
<?php
if (isset($_GET["import_success"])) {
    echo "            <h2 class=\"success\">the photos were successfully imported.</h2>\n";
};
?>
            <h2>things to do or be changed</h2>
            <ul>
                <li><a href="collections.php">collections</a></li>
                <li><a href="sets.php">sets</a></li>
                <li><a href="import.php">import images</a></li>
            </ul>
        </div>
    </body>
</html>
