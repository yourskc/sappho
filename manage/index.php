<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "../global.php";

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?> &mdash; manage</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/"><?php echo $sappho_title; ?></a> &raquo; manage</h1>
            <h2>things to do or be changed</h2>
            <ul>
                <li><a href="collections.php">collections</a></li>
                <li><a href="sets.php">sets</a></li>
                <li><a href="images.php">images</a></li>
            </ul>
        </div>
    </body>
</html>
