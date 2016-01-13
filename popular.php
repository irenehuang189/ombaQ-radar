<?php

require 'vendor/cosenary/instagram/src/Instagram.php';

use MetzWeb\Instagram\Instagram;

$instagram = new Instagram('af797da93a514a9381d6862490944f45');

$tag = 'photographyislifee';
// Set number of photos to show
$limit = 10;
$result = $instagram->getTagMedia($tag, $limit);

// Count post and like number for every contributor
$contributors = array();
foreach($result->data as $media) {
    $user_id = $media->user->id;
    $username = $media->user->username;
    $post_num = 1;
    $post_like_num = $media->likes->count;
    // Search username in contibutor list
    $i = 0;
    $isContributorFound = false;
    while ($i<sizeof($contributors) && !$isContributorFound) {
        if ($contributors[$i][0] === $user_id) {
            // Contributor sudah ada di tabel
            $isContributorFound = true;
            $post_num += $contributors[$i][2];
            $post_like_num += $contributors[$i][3];
        } else {
            $i++;
        }
    }

    $contributor = array($user_id, $username, $post_num, $post_like_num);
    if ($isContributorFound) {
        $contributors[$i] = $contributor;
    } else {
        array_push($contributors, $contributor);
    }
}

// Contributors
echo "<br>Contributors (" . sizeof($contributors) . ")<br>";
echo "Username Posts Likes<br>";
foreach($contributors as $contributor) {
    echo $contributor[0] . "--" . $contributor[1] . "--" . $contributor[2] . "--" . $contributor[3] . "<br>";
}

echo "<br><br>Top Contributors<br>";
// Search contributor with most posts and likes
$max_posts_idx = 0;
$max_posts = 0;
$max_likes_idx = 0;
$max_likes = 0;
for ($i=0; $i<sizeof($contributors); $i++) {
    $posts = $contributors[$i][2];
    if ($posts > $max_posts) {
        $max_posts_idx = $i;
        $max_posts = $posts;
    } else if ($posts == $max_posts) {
        $user_id = $contributors[$i][0];
        $user_followers = $instagram->getUser($user_id)->data->counts->followed_by;
        $max_posts_user_id = $contributors[$max_posts_idx][0];
        $max_posts_user_followers = $instagram->getUser($max_posts_user_id)->data->counts->followed_by;
        if ($user_followers > $max_posts_user_followers) {
            // Pengguna memiliki follower lebih banyak
            $max_posts_idx = $i;
            $max_posts = $posts;
        }
    }
    $likes = $contributors[$i][3];
    if ($likes > $max_likes) {
        $max_likes_idx = $i;
        $max_likes = $likes;
    } else if ($likes == $max_likes) {
        $user_id = $contributors[$i][0];
        $user_followers = $instagram->getUser($user_id)->data->counts->followed_by;
        $max_likes_user_id = $contributors[$max_likes_idx][0];
        $max_likes_user_followers = $instagram->getUser($max_likes_user_id)->data->counts->followed_by;
        if ($user_followers > $max_likes_user_followers) {
            // Pengguna memiliki follower lebih banyak
            $max_likes_idx = $i;
            $max_likes = $likes;
        }
    }
}
echo "Most posts: " . $max_posts . " posts by " . $contributors[$max_posts_idx][1] . "<br>";
echo "Most likes: " . $max_likes . " likes by " . $contributors[$max_likes_idx][1] . "<br><br><br>";

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
    echo date('M j, Y', $media->created_time) . "<br>";
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
<div class="container">
    <header class="clearfix">
        <img src="assets/instagram.png" alt="Instagram logo">

        <h1>Instagram <span>popular photos</span></h1>
    </header>
    <div class="main">
        <ul class="grid">
            <?php
			function my_sort($a, $b)
		    {
				if ($a->likes->count < $b->likes->count) {
					return 1;
				} else if ($a->likes->count > $b->likes->count) {
					return -1;
				} else {
					return 0;
				}
		    }
		 
	       usort($result->data, 'my_sort');
		   $tagcount = array();
            foreach ($result->data as $media) {
                $content = '<li>';
                // output media
                if ($media->type === 'video') {
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
                $comment = (!empty($media->caption->text)) ? $media->caption->text : '';
				$like = $media->likes->count;
				$tags = $media ->tags;

				
				foreach($tags as $temptag) {
					if(!array_key_exists($temptag, $tagcount) && $temptag != $tag) 
						$tagcount[$temptag] = 1;
					else {
						if($temptag != $tag)
							$tagcount[$temptag]++;
					}
				}
            
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
\
		<?php 
		$word_array = array();
		
		foreach($tagcount as $x => $x_value) {
			$obj = new stdClass();
			$obj->text = $x;
			$obj->weight = $x_value;
			$word_array[] = $obj; 
		}
		?>

		
		<div id="wordcloud" style="width: 550px; height: 350px;margin: 0 auto"></div>
        <!-- GitHub project -->
        <footer>
            <p>created by <a href="https://github.com/cosenary/Instagram-PHP-API">cosenary's Instagram class</a>,
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
        $('li').hover(
            function () {
                var $image = $(this).find('.media');
                var height = $image.height();
                $image.stop().animate({marginTop: -(height - 82)}, 1000);
            }, function () {
                var $image = $(this).find('.media');
                var height = $image.height();
                $image.stop().animate({marginTop: '0px'}, 1000);
            }
        );
    });
</script>
 <script type="text/javascript">
      /*!
       * Create an array of word objects, each representing a word in the cloud
       */
      var word_array = eval(<?php echo json_encode($word_array);?>);
      $(function() {
        // When DOM is ready, select the container element and call the jQCloud method, passing the array of words as the first argument.
        $("#wordcloud").jQCloud(word_array);
      });
	   </script>


</body>
</html>
