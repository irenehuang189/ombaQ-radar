<?php
header("content-type:application/json");

require "vendor/cosenary/instagram/src/Instagram.php";
require "radar.php";

use MetzWeb\Instagram\Instagram;

ini_set("max_execution_time", 123456);

$instagram = new Instagram("af797da93a514a9381d6862490944f45");
$hashtag = $_REQUEST["hashtag"];
$limit = 10;
$posts = $instagram->getTagMedia($hashtag, $limit)->data;

$tag_count = get_tag_count($posts, $hashtag);
$word_cloud = convert_tag_count_to_word_array($tag_count);

$top_posts_limit =  5;
$top_posts = get_most_posts($posts, $top_posts_limit);

$users = get_users_post_like($posts);
$top_users = get_top_users($instagram, $users);

$result = array("word_cloud" => $word_cloud, 
                "posts" => $posts, 
                "top_posts" => $top_posts, 
                "users" => $users, 
                "top_users" => $top_users);
echo json_encode($result);
exit();
?>