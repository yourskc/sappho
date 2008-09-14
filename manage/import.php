<?php

/*********************************
 * GET GLOBAL VARS AND FUNCTIONS *
 *********************************/
require_once "../global.php";



if (!empty($_POST)) {

    $id_listing = array();

    foreach ($_POST as $key => $value) {

        list($type, $id) = explode("-", $key);
        if ($type == "set" && !empty($value)) $id_listing[] = $id;

    };

    foreach ($id_listing as $id) {

        $url = "http://$s3_bucket.s3.amazonaws.com/$s3_path/c/$id.jpg";
        $tmp_file = "/tmp/sappho-$id.jpg";

        if (!$file_str = file_get_contents($url)) {
            die("could not get file from s3.");
        };

        if (!file_put_contents($tmp_file, $file_str)) {
            die("could not put file into $tmp_file.");
        };

        if (!$exif = exif_read_data($tmp_file, 0, TRUE)) {
            die("could not read exif data from image.");
        };

        if (!unlink($tmp_file)) {
            die("could not unlink $tmp_file.");
        };

        $filename               = clean($id);
        $title                  = clean($_POST["title-$id"]);
        $caption                = clean($_POST["caption-$id"]);
        $set                    = clean($_POST["set-$id"]);
        $exif_cameramodel       = clean($exif["IFD0"]["Model"]);
        $exif_exposuretime      = clean($exif["EXIF"]["ExposureTime"]);
        $exif_fnumber           = clean($exif["EXIF"]["FNumber"]);
        $exif_isospeedratings   = clean($exif["EXIF"]["ISOSpeedRatings"]);
        $exif_flash             = clean($exif["EXIF"]["Flash"] & 1 ? "1" : "0");
        $exif_focallength       = clean($exif["EXIF"]["FocalLength"]);
        $exif_datetimeoriginal  = clean($exif["EXIF"]["DateTimeOriginal"]);
        $thumb_width            = clean($exif["COMPUTED"]["Width"]);
        $thumb_height           = clean($exif["COMPUTED"]["Height"]);

        $sql = "INSERT INTO `photo_image`                               ".
               "SET `filename`              ='$filename',               ".
               "    `set_id`                ='$set',                    ".
               "    `title`                 ='$title',                  ".
               "    `caption`               ='$caption',                ".
               "    `date_imported`         = UNIX_TIMESTAMP(),         ".
               "    `exif_cameramodel`      ='$exif_cameramodel',       ".
               "    `exif_exposuretime`     ='$exif_exposuretime',      ".
               "    `exif_fnumber`          ='$exif_fnumber',           ".
               "    `exif_isospeedratings`  ='$exif_isospeedratings',   ".
               "    `exif_focallength`      ='$exif_focallength',       ".
               "    `exif_flash`            ='$exif_flash',             ".
               "    `exif_datetimeoriginal` ='$exif_datetimeoriginal',  ".
               "    `thumb_width`           ='$thumb_width',            ".
               "    `thumb_height`          ='$thumb_height'            ";
        if (!$result = mysql_query($sql)) print_error();

        $sql = "UPDATE photo_set                    ".
               "SET images=images+1,                ".
               "    date_updated=UNIX_TIMESTAMP()   ".
               "WHERE set_id='$set'                 ";
        if (!$result = mysql_query($sql)) print_error();

    };

    header("Location: index.php?import_success");

};

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
    <head>
    <title><?php echo $sappho_title; ?> &mdash; manage &mdash; import images</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <style type="text/css">
            @import "<?php echo $sappho_path; ?>/style.css";
        </style>
    </head>
    <body>
        <div id="container">
            <h1><a href="<?php echo $sappho_path; ?>/manage/"><?php echo $sappho_title; ?> management</a></h1>
            <h2>import images from aws s3</h2>
<?php

$freshies = new_files();

if (count($freshies) === 0) {

    echo "            <h3>there are no new photos in s3 to import.</h3>\n";

} else if (count($freshies) > 0) {

?>
            <div id="import">
                <form action="import.php" method="post">
                    <table>
<?php

    $set_options = "<option value=\"\">---- choose a set ----</option>";
    $sql = "SELECT set_id, title FROM photo_set";
    if (!$result = mysql_query($sql)) print_error();
    while (list($set_id, $set_title) = mysql_fetch_array($result)) {
        $set_options .= "<option value=\"$set_id\">$set_title</option>";
    };

    foreach ($freshies as $id => $sizes) {

        echo "                        <tr>\n";
        echo "                            <td><img src=\"http://$s3_bucket.s3.amazonaws.com/$s3_path/c/$id.jpg\" alt=\"$id\" /></td>\n";
        echo "                            <td>\n";
        echo "                                <input type=\"text\" name=\"title-$id\" /><br />\n";
        echo "                                <textarea name=\"caption-$id\"></textarea><br />\n";
        echo "                                <select name=\"set-$id\">$set_options</select>\n";
        echo "                            </td>\n";
        echo "                        </tr>\n";

    };

?>
                        <tr>
                            <th colspan="2">
                                <input type="submit" value="import" />
                            </th>
                        </tr>
                    </table>
                </form>
            </div>
<?php

} else {

    echo "            <h3>an error was encountered.</h3>\n";

};

?>
        </div>
    </body>
</html>
