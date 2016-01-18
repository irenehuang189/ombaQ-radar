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
    type: 'GET',
    data: {hashtag: hashtag},
    dataType: 'json',
    success: function(json) {
      var cards_result = '';
      $.each(json, function(i, item) {
        if (typeof item == 'object') {
          var link = item.link;
          var poster = item.images.low_resolution.url;
          var user_image = item.user.profile_picture;
          var user_fullname = item.user.full_name;
          var username = item.user.username;
          var caption = item.caption.text;

          var card_result = '\
            <div class="col s12 m6 l4">\
              <a href="' + link + '" target="_blank">\
                <div class="card hoverable">\
                  <div class="card-image">\
                    <img src="' + poster + '" />\
                  </div>\
                  <div class="card-content">\
                    <div class="row valign-wrapper">\
                      <div class="col s3">\
                        <img src="' + user_image + '" class="circle responsive-img" />\
                      </div>\
                      <div class="col s9 valign">' + 
                        user_fullname + '<br />@' + 
                        username
                      + '</div>\
                    </div>\
                    <p class="truncate">' + caption + '</p>\
                  </div>\
                </div>\
              </a>\
            </div>';
            cards_result += card_result;
        }
      })
      $('#latest_posts_cards').append(cards_result);
    },
    error: function(xhr, desc, err) {
      console.log(xhr);
      console.log('Details: ' + desc + '\n Error: ' + err);
      alert(err);
    }
  });
});