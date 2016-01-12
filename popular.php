<?php

require 'vendor/cosenary/instagram/src/Instagram.php';

use MetzWeb\Instagram\Instagram;

$instagram = new Instagram('af797da93a514a9381d6862490944f45');

$tag = 'holiday';
// Set number of photos to show
$limit = 15;

// Set height and width for photos
$size = '100';
$result = $instagram->getTagMedia($tag, $limit);


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
