<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "../global.php";

if (empty($_REQUEST["set_id"])) {

    die("you must apecify a set to sort!");

};

if (!empty($_POST["sort"])) {

    print_r($_POST["sort"]);

    die();

};

$set_id = clean($_GET["set_id"]);
$sql = "SELECT set_id,          ".
       "       collection_id,   ".
       "       search_path,     ".
       "       title,           ".
       "       body             ".
       "FROM photo_set          ".
       "WHERE set_id='$set_id'  ";
if (!$result = mysql_query($sql)) print_error();
$set = mysql_fetch_array($result);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?> &mdash; manage &mdash; sort a set</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
        <script type="text/javascript" src="mootools.js"></script>
        <script type="text/javascript">
            window.addEvent('domready', function(){
    
                var set_sort = new Sortables('#set_sort');

                var req = new Request({
                                url: 'set_sort.php',
                            
                                onSuccess: function(txt){ $('result').set('text', txt); },
                                onFailure: function(){ $('result').set('text', 'The request failed.'); }
                });

                $('sort_submit').addEvent('click', function(){
//		      req.send('set_id=<?php echo $set_id; ?>&sort='+set_sort.serialize());
		      alert('set_id=<?php echo $set_id; ?>&sort='+set_sort.serialize());
                });
                
            });
        </script>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/manage/"><?php echo $sappho_title; ?> management</a></h1>
            <h2>sets</h2>
            <h3>sorting <i><?php echo $set["title"]; ?></i></h3>
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
       "ORDER BY set_order      ";
if (!$result = mysql_query($sql)) print_error();
while ($image = mysql_fetch_array($result)) {

    $x = $image["thumb_width"];
    $y = $image["thumb_height"];
    $x_pad = ($x < $thumbnail_size) ? ($thumbnail_size-$x)/2 : 0;
    $y_pad = ($y < $thumbnail_size) ? ($thumbnail_size-$y)/2 : 0;

    echo "                    <li><div class=\"set_sortable\">";
    echo "<img src=\"http://$s3_bucket.s3.amazonaws.com/$s3_path/c/{$image["filename"]}.jpg\" alt=\"{$image["title"]}\" style=\"margin: {$y_pad}px {$x_pad}px;\"/>";
    echo "</div></li>\n";

};

?>
                </ol>

                <br /><a href="#" id="sort_submit">Update sorting order</a>
                <div id="result"></div>

            </div>
        </div>
    </body>
</html>

