<?php
function get_most_posts($posts, $limit) {
  $sorted_posts = $posts;
  usort($sorted_posts, 'sort_post');
  return array_slice($sorted_posts,0,$limit);
}

function sort_post($a, $b) {
  if ($a->likes->count < $b->likes->count) {
    return 1;
  } else if ($a->likes->count > $b->likes->count) {
    return -1;
  } else {
    return 0;
  }
}

function convert_tag_count_to_word_array($tag_count) {
  $word_array = array();
  foreach($tag_count as $x => $x_value) {
    $obj = new stdClass();
    $obj->text = $x;
    $obj->weight = $x_value;
    $word_array[] = $obj;
  }
  return $word_array;
}

function get_tag_count($posts, $tag) {
  $tag_count = array();
  foreach ($posts as $media) {
    $tags = $media->tags;
    foreach($tags as $temp_tag) {
      if(!array_key_exists($temp_tag, $tag_count) && $temp_tag != $tag) {
        $tag_count[$temp_tag] = 1;
      }
      else {
        if($temp_tag != $tag) {
          $tag_count[$temp_tag]++;
        }
      }
    }
  }
  return $tag_count;
}

function get_users_post_like($posts) {
  $users = array();
  foreach($posts as $media) {
    $user_id = $media->user->id;
    $user_image = $media->user->profile_picture;
    $user_fullname = $media->user->full_name;
    $username = $media->user->username;
    $post_num = 1;
    $like_num = $media->likes->count;
    
    $user = array("user_id" => $user_id,
                  "user_image" => $user_image,
                  "user_fullname" => $user_fullname,
                  "username" => $username,
                  "post_num" => $post_num,
                  "like_num" => $like_num);

    $user_idx = array_search($user_id, array_column($users, "user_id"));
    if ($user_idx !== false) {
      $user["post_num"] += $users[$user_idx]["post_num"];
      $user["like_num"] += $users[$user_idx]["like_num"];
      $users[$user_idx] = $user;
    } else {
      array_push($users, $user);
    }
  }
  return $users;
}

function get_top_users($instagram, $users) {
  $max_posts_idx = 0;
  $max_posts = 0;
  $max_likes_idx = 0;
  $max_likes = 0;
  for ($i=0; $i<sizeof($users); $i++) {
    $posts = $users[$i]["post_num"];
    if ($posts > $max_posts) {
      $max_posts_idx = $i;
      $max_posts = $posts;
    } else if ($posts == $max_posts) {
      $user_id = $users[$i]["user_id"];
      $max_posts_user_id = $users[$max_posts_idx]["user_id"];
      
      $user_followers = $instagram->getUser($user_id)->data->counts->followed_by;
      $max_posts_user_followers = $instagram->getUser($max_posts_user_id)->data->counts->followed_by;
      if ($user_followers > $max_posts_user_followers) {
        // Pengguna memiliki follower lebih banyak
        $max_posts_idx = $i;
        $max_posts = $posts;
      }
    }
    $likes = $users[$i]["like_num"];
    if ($likes > $max_likes) {
      $max_likes_idx = $i;
      $max_likes = $likes;
    } else if ($likes == $max_likes) {
      $user_id = $users[$i]["user_id"];
      $max_likes_user_id = $users[$max_likes_idx]["user_id"];

      $user_followers = $instagram->getUser($user_id)->data->counts->followed_by;
      $max_likes_user_followers = $instagram->getUser($max_likes_user_id)->data->counts->followed_by;
      if ($user_followers > $max_likes_user_followers) {
        // Pengguna memiliki follower lebih banyak
        $max_likes_idx = $i;
        $max_likes = $likes;
      }
    }
  }
  return array("max_post_idx" => $max_posts_idx, "max_like_idx" => $max_likes_idx);
}

function get_max_follower_user_id($instagram, $user1_id, $user2_id) {
  $user1_follower = $instagram->getUser($user1_id)->data->counts->followed_by;
  $user2_follower = $instagram->getUser($user2_id)->data->counts->followed_by;
  $max_user_id = $user1_id;
  if ($user1_follower < $user2_follower) {
    $max_user_id = $user2_id;
  }
  return $max_user_id;
}

function get_image_video_count($posts) {
  $image_type_count = 0;
  $video_type_count = 0;
  foreach($posts as $media) {
    if ($media->type === "image") {
      $image_type_count++;
    } else {
      $video_type_count++;
    }
  }
  return array("image" => $image_type_count, "video" => $video_type_count);
}

function get_time_interval($posts) {
	date_default_timezone_set('asia/jakarta');
	$maxdate = 'Jan 1, 0000 1:00:00 AM';
	$mindate = date("M j, Y g:i:s A");
	$timearray = array();
	// Graph
	foreach($posts as $media) {
	  $date = date("M j, Y g:i:s A", $media->created_time);
	  $month = date("m",$media->created_time);
	  $datenum = date("j",$media->created_time);
	  $year = date("Y",$media->created_time);
	  $hr = date("G",$media->created_time);
	  $min = date("i",$media->created_time);
	  $sec = date("s",$media->created_time);
	  $type = $media->type;
	  $timeArrayElement = array($year,$month,$datenum,$hr,$min,$sec,true, $type);
	  
	  if(strtotime($date) > strtotime($maxdate))
		  $maxdate = $date;
	 
	  if(strtotime($date) < strtotime($mindate))
		  $mindate = $date; 
	  
	  $timearray[] = $timeArrayElement;
	}

	$timeinterval = array();
	$minute = false; $second = false; $hour = false; $dt = false; //boolean for time interval
	$diff = strtotime($maxdate) - strtotime($mindate); 
	split_num($diff, $split, $intv, $timesplit);

	if($timesplit == "sec")
		$second = true;
	else if($timesplit == "min")
		$minute = true;
	else if($timesplit == "hr")
		$hour = true;
	else 
		$dt = true;


	$splitnum = round($split);

	if($second)
		$botinterval = floor($timearray[0][5] / 10) * 10;
	else if($minute)
		$botinterval = floor($timearray[0][4] / 10) * 10;
	else if($hour)
		$botinterval = floor($timearray[0][3] / 10) * 10;
	else if($dt)
		$botinterval = floor($timearray[0][2] / 10) * 10;

	if($botinterval == 0)
		$botinterval = "00";      // kondisi 0 

	if($second)
		$timearray[0][5] = $botinterval;
	else if($minute)
		$timearray[0][4] = $botinterval;
	else if($hour)
		$timearray[0][3] = $botinterval;
	else if($dt)
		$timearray[0][2] = $botinterval;
				
				
	$timeinterval[0][0] = new DateTime($timearray[0][0] . '-' . $timearray[0][1] . '-' . $timearray[0][2] . ' ' . $timearray[0][3] . ':' . $timearray[0][4] . ':' . $timearray[0][5]);
		
	$timeinterval[0][1] = 0;		// image count
	$timeinterval[0][2] = 0;		// video count

		
	for($i=1;$i<$splitnum;$i++) {
		if($second) {
			$timeinterval[$i][0] = new DateTime($timearray[0][0] . '-' . $timearray[0][1] . '-' . $timearray[0][2] . ' ' . $timearray[0][3] . ':' . $timearray[0][4] . ':' . $timearray[0][5]);
			$timeinterval[$i][0]->modify("-" . $intv*$i . "second");
		}
		else if($minute) {
			$timeinterval[$i][0] = new DateTime($timearray[0][0] . '-' . $timearray[0][1] . '-' . $timearray[0][2] . ' ' . $timearray[0][3] . ':' . $timearray[0][4] . ':' . $timearray[0][5]);
			$timeinterval[$i][0]->modify("-" . $intv*$i . "minute");
		}
		else if($hour) {
			$timeinterval[$i][0] = new DateTime($timearray[0][0] . '-' . $timearray[0][1] . '-' . $timearray[0][2] . ' ' . $timearray[0][3] . ':' . $timearray[0][4] . ':' . $timearray[0][5]);
			$timeinterval[$i][0]->modify("-" . $intv*$i . "hour");
		}
		else if($dt) {
			$timeinterval[$i][0] = new DateTime($timearray[0][0] . '-' . $timearray[0][1] . '-' . $timearray[0][2] . ' ' . $timearray[0][3] . ':' . $timearray[0][4] . ':' . $timearray[0][5]);
			$timeinterval[$i][0]->modify("-" . $intv*$i . "day");
		}
		$timeinterval[$i][1] = 0;
		$timeinterval[$i][2] = 0;
	}

	foreach($timeinterval as $key => $x) {
		foreach($timearray as $keys => $y) {
			$date_temp = new DateTime($y[0] . '-' . $y[1] . '-' . $y[2] . ' ' . $y[3] . ':' . $y[4] . ':' . $y[5]);
			if($date_temp >= $x[0] && $timearray[$keys][6] == true) {
				if($y[7] == "image")
					$timeinterval[$key][1]++;
				else
					$timeinterval[$key][2]++;
				$timearray[$keys][6] = false;
			}
		}
	}
	return $timeinterval;
}
function split_num($timediff, &$split, &$i, &$time) {
  $time = "sec";
  $i = 5;
  $split = $timediff/$i;
  while($split > 15) {
    if($i < 60) 
      $i += 5;
    else if($i == 60) {
      $i = 300;
      $time = "min";
    }
    else if($i > 60) {
      $i += 300;
      $time = "min";
    }
    else if($i >= 3600) {
      $i += 3600;
      $time = "hr";
    }
    else if($i >= 86400) {
      $i += 3600;
      $time = "dt";
    }
    $split = $timediff/$i + 1;
  }
}

?>