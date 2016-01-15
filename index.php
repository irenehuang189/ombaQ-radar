<?php

require "vendor/cosenary/instagram/src/Instagram.php";
require "radar.php";

use MetzWeb\Instagram\Instagram;

ini_set("max_execution_time", 123456);
$instagram = new Instagram("af797da93a514a9381d6862490944f45");

$tag = "photographyislifee";
// Set number of photos to show
$limit = 10;
$result = $instagram->getTagMedia($tag, $limit);

// Users
$users = get_users_post_like($result->data);
echo "Users (" . sizeof($users) . ")<br>";
echo "Username Posts Likes<br>";
foreach($users as $user) {
  echo $user["user_id"] . "--" . $user["username"] . "--" . $user["post_num"] . "--" . $user["like_num"] . "<br>";
}

echo "<br><br>Top Users<br>";
// Search user with most posts and likes
$top_users = get_top_users($instagram, $users);
$max_post_idx = $top_users["max_post_idx"];
$max_like_idx = $top_users["max_like_idx"];

$max_post = $users[$max_post_idx]["post_num"];
$max_like = $users[$max_like_idx]["like_num"];
$max_post_username = $users[$max_post_idx]["username"];
$max_like_username = $users[$max_like_idx]["username"];
echo "Most posts: " . $max_post . " posts by " . $max_post_username . "<br>";
echo "Most likes: " . $max_like . " likes by " . $max_like_username . "<br><br><br>";

// Activities Volume
$image_type_count = 0;
$video_type_count = 0;
foreach($result->data as $media) {
  if ($media->type === "image") {
    $image_type_count++;
  } else {
    $video_type_count++;
  }
}
echo "Activities Volume<br>" . "Images: " . $image_type_count . "<br>";
echo "Videos: " . $video_type_count ."<br>  ";

// Graph
foreach($result->data as $media) {
  echo date("M j, Y", $media->created_time) . "<br>";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Instagram - popular photos</title>
  <link href="https://vjs.zencdn.net/4.2/video-js.css" rel="stylesheet">
  <link href="assets/style.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/jqcloud/1.0.4/jqcloud.css" rel="stylesheet">
   
</head>
<body>
<div id="wordcloud" style="width: 550px; height: 350px;margin: 0 auto"></div>

<div class="container">
  <header class="clearfix">
    <img src="assets/instagram.png" alt="Instagram logo">

    <h1>Instagram <span>popular photos</span></h1>
  </header>
  <div class="main">
    <ul class="grid">
      <?php
      $most_posts = get_most_posts($result->data, 3);

      $tag_count = get_tag_count($result->data, $tag);
      $word_cloud = convert_tag_count_to_word_array($tag_count);

      foreach ($result->data as $media) {
        $content = "<li>";
        // output media
        if ($media->type === "video") {
          // video
          $poster = $media->images->low_resolution->url;
          $source = $media->videos->standard_resolution->url;
          $content .= "<video class=\"media video-js vjs-default-skin\" width=\"250\" height=\"250\" poster=\"{$poster}\"
                       data-setup='{\"controls\":true, \"preload\": \"auto\"}'>
                         <source src=\"{$source}\" type=\"video/mp4\" />
                       </video>";
        } else {
          // image
          $image = $media->images->low_resolution->url;
          $content .= "<img class=\"media\" src=\"{$image}\"/>";
        }
        // create meta section
        
        $avatar = $media->user->profile_picture;
        $username = $media->user->username;
        $comment = (!empty($media->caption->text)) ? $media->caption->text : "";
        $like = $media->likes->count;
        $tags = $media ->tags;
      
        $content .= "<div class=\"content\">
                      <div class=\"avatar\" style=\"background-image: url({$avatar})\"></div>
                      <div class=\"\">{$like}</div>
                        <p>{$username}</p>
                        <div class=\"comment\">{$comment}</div>
                    </div>";
        // output media

        echo $content . '</li>';
      }
      ?>
    </ul>
    
    <!-- GitHub project -->
    <footer>
      <p>created by <a href="https://github.com/cosenary/Instagram-PHP-API">cosenary"s Instagram class</a>,
        available on GitHub</p>
    </footer>
  </div>
</div>
<!-- javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://vjs.zencdn.net/4.2/video.js"></script>
<script src="https://vjs.zencdn.net/4.2/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqcloud/1.0.4/jqcloud-1.0.4.min.js"></script>
<script>
  $(document).ready(function () {
    // rollover effect
    $("li").hover(
      function () {
        var $image = $(this).find(".media");
        var height = $image.height();
        $image.stop().animate({marginTop: -(height - 82)}, 1000);
      }, function () {
        var $image = $(this).find(".media");
        var height = $image.height();
        $image.stop().animate({marginTop: "0px"}, 1000);
      }
    );
  });
</script>
 <script type="text/javascript">
    /*!
     * Create an array of word objects, each representing a word in the cloud
     */
    var word_cloud = eval(<?php echo json_encode($word_cloud);?>);
    $(function() {
    // When DOM is ready, select the container element and call the jQCloud method, passing the array of words as the first argument.
    $("#wordcloud").jQCloud(word_cloud);
    });
     </script>


</body>
</html>
