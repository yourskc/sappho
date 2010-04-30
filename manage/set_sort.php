<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
define('SAPPHO_HTTPS', TRUE);
require_once "../global.php";



if (!empty($_POST["set_id"])) {

    foreach ($_POST as $key => $value) {

        if (preg_match('@^sortable_([0-9]+)$@i', $key, $match)) {

            $sql = "UPDATE photo_image SET sort='".clean($value)."' WHERE image_id='".clean($match[1])."'";
            if (!$result = mysql_query($sql)) print_error();

        };

    };

    die("sort order updated successfully.");

};



if (empty($_GET["set_id"])) {

    die("you must specify a set to sort!");

};



$set_id = clean($_GET["set_id"]);
$sql = "SELECT title            ".
       "FROM photo_set          ".
       "WHERE set_id='$set_id'  ";
if (!$result = mysql_query($sql)) print_error();
list($set_title) = mysql_fetch_array($result);

header('Content-Type: text/html; charset=utf-8');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?> &mdash; manage &mdash; sort a set</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
        <script type="text/javascript" src="mootools-1.2-core-yc.js"></script>
        <script type="text/javascript" src="mootools-1.2-more-yc.js"></script>
        <script type="text/javascript">
            window.addEvent('domready', function(){
    
                var set_sort = new Sortables('#set_sort');

                var req = new Request({
                                url: 'set_sort.php',
                                onSuccess: function(txt){ $('result').set('text', txt); },
                                onFailure: function(){ $('result').set('text', 'the request failed.'); }
                });


                $('sort_form').addEvent('submit', function(e) {
                    e.stop();
                    $('result').set('text', 'requesting...');
                    req.send('set_id=<?php echo $set_id; ?>&' + set_sort.serialize(function(element, index){ return element.getProperty('id') + '=' + index; }).join('&'));
                });

            });
        </script>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/"><?php echo $sappho_title; ?></a> &raquo; <a href="<?php echo $sappho_path; ?>/manage/">manage</a></h1>
            <h2><a href="sets.php">sets</a> &raquo; sorting <i><?php echo $set_title; ?></i></h2>
            <div id="sort">
                <ol id="set_sort">
<?php

$sql = "SELECT image_id,        ".
       "       filename,        ".
       "       title,           ".
       "       thumb_width,     ".
       "       thumb_height     ".
       "FROM photo_image        ".
       "WHERE set_id='$set_id'  ".
       "ORDER BY sort           ";
if (!$result = mysql_query($sql)) print_error();

while ($image = mysql_fetch_array($result)) {

    $x = $image["thumb_width"];
    $y = $image["thumb_height"];
    $x_pad = ($x < $thumbnail_size) ? ($thumbnail_size-$x)/2 : 0;
    $y_pad = ($y < $thumbnail_size) ? ($thumbnail_size-$y)/2 : 0;

    echo "                    <li id=\"sortable_{$image["image_id"]}\">";
    echo "<img src=\"http://$s3_host/{$s3_path}a/{$image["filename"]}.jpg\" alt=\"{$image["title"]}\" style=\"margin: {$y_pad}px {$x_pad}px;\"/>";
    echo "</li>\n";

};

?>
                </ol>
                <div id="result">&nbsp;</div>
                <form id="sort_form"><input type="submit" id="sort_submit" /></form>
            </div>
        </div>
    </body>
</html>
