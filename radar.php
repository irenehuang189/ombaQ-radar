<?php
function get_users_post_like($posts) {
  $users = array();
  foreach($posts as $media) {
    $user_id = $media->user->id;
    $username = $media->user->username;
    $post_num = 1;
    $like_num = $media->likes->count;
    
    $user = array("user_id" => $user_id,
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
?>