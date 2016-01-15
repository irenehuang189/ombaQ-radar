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
?>