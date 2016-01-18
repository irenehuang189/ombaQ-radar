<?php
header("content-type:application/json");

require "vendor/cosenary/instagram/src/Instagram.php";
require "radar.php";

use MetzWeb\Instagram\Instagram;

ini_set("max_execution_time", 123456);
$instagram = new Instagram("af797da93a514a9381d6862490944f45");

$hashtag = $_REQUEST["hashtag"];
$limit = 10;
$result = $instagram->getTagMedia($hashtag, $limit)->data;

echo json_encode($result);
exit();
?>