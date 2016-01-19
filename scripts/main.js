$(document).ready(function(){
  // Feature tab
  $('ul.tabs').tabs();
  $('.tooltipped').tooltip({delay: 50});

  var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
      sURLVariables = sPageURL.split('&'),
      sParameterName,
      i;

    for (i = 0; i < sURLVariables.length; i++) {
      sParameterName = sURLVariables[i].split('=');

      if (sParameterName[0] === sParam) {
        return sParameterName[1] === undefined ? true : sParameterName[1];
      }
    }
  };

  var hashtag = getUrlParameter("hashtag");
  $.ajax({
    url: 'http://localhost/instagram-api/ajax-search.php',
    type: 'POST',
    data: {hashtag: hashtag},
    dataType: 'json',
    success: function(json) {
      var word_cloud = json.word_cloud;
      $('#word_cloud').jQCloud(word_cloud, {
        autoResize: true
      });

      var posts = json.posts;
      var post_cards = '';
      $.each(posts, function(i, item) {
        if (typeof item == 'object') {
          post_cards += showPost(item);
        }
      })
      $('#latest_posts_cards').append(post_cards);

      var top_posts = json.top_posts;
      var post_cards = '';
      $.each(top_posts, function(i, item) {
        post_cards += showTopPosts(item);
      })
      $('#most_likes_cards').append(post_cards);

      var contributors = json.users;
      var contributors_size = contributors.length;
      var slice_idx = contributors_size/2;
      if (contributors_size%2!=0) {
        slice_idx += 1;
      }

      var contributors_table = '';
      $.each(contributors.slice(0,slice_idx), function(i, item) {
        contributors_table += showContributor(item);
      })
      $('#left_contributors_table').append(contributors_table);
      var contributors_table = '';
      $.each(contributors.slice(slice_idx), function(i, item) {
        contributors_table += showContributor(item);
      })
      $('#right_contributors_table').append(contributors_table);

      var top_contributors_idx = json.top_users;
      showTopContributors(contributors, top_contributors_idx);
    },
    error: function(xhr, desc, err) {
      console.log(xhr);
      console.log('Details: ' + desc + '\n Error: ' + err);
    }
  });
});

function showPost(item) {
  var link = item.link;
  var poster = item.images.low_resolution.url;
  var user_image = item.user.profile_picture;
  var user_fullname = item.user.full_name;
  var username = item.user.username;
  var caption = item.caption.text;

  var post_card = '\
    <div class="col s12 m6 l4">\
      <a href="' + link + '" target="_blank">\
        <div class="card hoverable">\
          <div class="card-image">\
            <img src="' + poster + '" alt="Poster" />\
          </div>\
          <div class="card-content">\
            <div class="row valign-wrapper">\
              <div class="col s3">\
                <img src="' + user_image + '" alt="Profile Picture" class="circle responsive-img" />\
              </div>\
              <div class="col s9 valign">' + 
                user_fullname + '<br />@' + 
                username +
              '</div>\
            </div>\
            <p class="truncate">' + caption + '</p>\
          </div>\
        </div>\
      </a>\
    </div>';
  return post_card;
}

function showTopPosts(item) {
  var link = item.link;
  var like_num = item.likes.count;
  var poster = item.images.low_resolution.url;
  var user_image = item.user.profile_picture;
  var user_fullname = item.user.full_name;
  var username = '@' + item.user.username;
  var caption = item.caption.text;

  var post_card = '\
    <div class="col s12 m12 l4">\
      <h1 class="light center">' + like_num + '</h1>\
      <a href="' + link + '" target="_blank">\
        <div class="card hoverable">\
          <div class="card-image">\
            <img src="' + poster + '" alt="Poster" />\
          </div>\
          <div class="card-content">\
            <div class="row valign-wrapper">\
              <div class="col s3">\
                <img src="' + user_image + '" alt="Profile Picture" class="circle responsive-img" />\
              </div>\
              <div class="col s9 valign">' +
                user_fullname + '<br />' +
                username +
              '</div>\
            </div>\
            <p class="truncate">' + caption + '</p>\
          </div>\
        </div>\
      </a>\
    </div>';
  return post_card;
}

function showContributor(item) {
  var username = '@' + item.username;
  var post_num = item.post_num;
  var like_num = item.like_num;

  var table_row = '\
    <tr>\
      <td>' +  username + '</td>\
      <td>' + post_num + '</td>\
      <td>' + like_num + '</td>\
    </tr>';
  return table_row;
}

function showTopContributors(contributors, top_contributors_idx) {
  var top_post_contributor = contributors[top_contributors_idx.max_post_idx];
  var top_like_contributor = contributors[top_contributors_idx.max_like_idx];

  var user_image = top_post_contributor.user_image;
  var user_fullname = top_post_contributor.user_fullname;
  var username = "@" + top_post_contributor.username;
  var post_num = top_post_contributor.post_num;
  $("#top_contributors #top_post #user_image").attr("src", user_image);
  $("#top_contributors #top_post #user_fullname").append(user_fullname);
  $("#top_contributors #top_post #username").append(username);
  $("#top_contributors #top_post #post_num").append(post_num);

  var user_image = top_like_contributor.user_image;
  var user_fullname = top_like_contributor.user_fullname;
  var username = "@" + top_like_contributor.username;
  var like_num = top_like_contributor.like_num;
  $("#top_contributors #top_like #user_image").attr("src", user_image);
  $("#top_contributors #top_like #user_fullname").append(user_fullname);
  $("#top_contributors #top_like #username").append(username);
  $("#top_contributors #top_like #like_num").append(like_num);
}