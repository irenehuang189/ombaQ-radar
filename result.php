<!doctype html>
<html lang="">
  <head>
    <meta charset="utf-8" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ombaQ radar</title>

    <link rel="apple-touch-icon" href="apple-touch-icon.png" />
    <link rel="icon" type="image/png" href="favicon.ico" />

    <!-- CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqcloud/1.0.4/jqcloud.css" />
    <link rel="stylesheet" href="styles/result.css" />
    
  </head>
  <body>
    <!--[if lt IE 10]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <nav>
      <div class="nav-wrapper z-depth-1 grey darken-4" id="nav-bar">
        <a href="index.php" class="brand-logo">ombaQ Radar</a>
        <ul class="right hide-on-med-and-down row" id="nav-mobile">
          <li>
            <form class="col s8" id="search-form">
              <div class="input-field">
                <i class="material-icons prefix">search</i>
                <input id="hashtag-search" type="text" placeholder="Search" required />
              </div>
            </form>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Features Tab -->
    <div class="row" id="features-tab">
      <div class="col s12">
        <ul class="tabs z-depth-1 grey lighten-4">
          <li class="tab col s3">
            <a class="tooltipped" href="#latest_posts" data-position="bottom" data-delay="50" data-tooltip="A full list of all posts in this report, in reverse chronological order (newest first).">Latest Posts</a>
          </li>
          <li class="tab col s3">
            <a class="tooltipped" href="#most_likes" data-position="bottom" data-delay="50" data-tooltip="The top three most liked posts in this report, including liked counts for each posts.">Most Likes</a>
          </li>
          <li class="tab col s3">
            <a class="tooltipped" href="#contributors" data-position="bottom" data-delay="50" data-tooltip="A complete list of all participants in this report, including how many posts they posted and how many posts they liked.">Contributors</a>
          </li>
          <li class="tab col s3">
            <a class="tooltipped" href="#top_contributors" data-position="bottom" data-delay="50" data-tooltip="The contributor who have many posts and likes.">Top Contributors</a>
          </li>
          <li class="tab col s3">
            <a class="tooltipped" href="#activities_volume" data-position="bottom" data-delay="50" data-tooltip="Activities provides details about the posts in this report, including the time period covered by the report, a graphical timeline showing posts volume during the report period, and posts type breakdown.">Activities Volume</a>
          </li>
        </ul>
      </div>
      <div class="col s12" id="latest_posts">
        <div class="section container">
          <h3 class="header light center">Latest Posts</h3>
          <div id="word_cloud">

          </div>
          <!-- Latest Posts -->
          <div class="row" id="latest_posts_cards">
            
          </div>
        </div>
      </div>
      <div class="col s12" id="most_likes">
        <div class="section container">
          <h3 class="header light center">Most Likes</h3>
          <!-- Most Likes Posts -->
          <div class="row" id="most_likes_cards">
            
          </div>
        </div>
      </div>
      <div class="col s12" id="contributors">
        <div class="section container">
          <h3 class="header light center">Contributors</h3>
          <!-- Contibutors Table -->
          
          <table class="bordered highlight centered col s12 m6 l5">
            <thead>
              <tr>
                  <th data-field="">Username</th>
                  <th data-field="name">Posts</th>
                  <th data-field="price">Likes</th>
              </tr>
            </thead>

            <tbody id="left_contributors_table">

            </tbody>
          </table>
          <table class="bordered highlight centered col s12 m6 l5 offset-l2">
            <thead>
              <tr>
                  <th data-field="">Username</th>
                  <th data-field="name">Posts</th>
                  <th data-field="price">Likes</th>
              </tr>
            </thead>

            <tbody id="right_contributors_table">
              
            </tbody>
          </table>
        </div>
      </div>
      <div class="col s12" id="top_contributors">
        <div class="section container">
          <h3 class="header light center">Top Contributors</h3>
          <div class="row">
            <!-- Posts -->
            <div class="col s12 m6" id="top_post">
              <div class="card-panel grey lighten-3" id>
                <!-- User -->
                <div class="row valign-wrapper">
                  <div class="col s3">
                    <img src="images/sample.jpg" class="circle responsive-img" id="user_image" />
                  </div>
                  <div class="col s9 valign">
                    <div id="user_fullname"></div>
                    <div id="username"></div>
                  </div>
                </div>
                <!-- Number -->
                <div class="row center">
                  <i class="large material-icons">perm_media</i>
                  <h1 class="light"><span id="post_num"></span> Posts</h1>
                </div>
              </div>
            </div>
            <!-- Likes -->
            <div class="col s12 m6" id="top_like">
              <div class="card-panel grey lighten-3">
                <!-- User -->
                <div class="row valign-wrapper">
                  <div class="col s3">
                    <img src="images/sample.jpg" class="circle responsive-img" id="user_image" />
                  </div>
                  <div class="col s9 valign">
                    <div id="user_fullname"></div>
                    <div id="username"></div>
                  </div>
                </div>
                <!-- Number -->
                <div class="row center">
                  <i class="large material-icons">thumb_up</i>
                  <h1 class="light"><span id="like_num"></span> Likes</h1>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col s12" id="activities_volume">
        <div class="section container">
          <h3 class="header light center">Activities Volume</h3>
          <div class="card">
            <div class="card-content">
              <div id="post-volume_chart"></div>
            </div>
          </div>
          <div class="card">
            <div class="card-content">
              <div id="post-type_chart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- JavaScript -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqcloud/1.0.4/jqcloud-1.0.4.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="scripts/main.js"></script>
    <script type="text/javascript" src="scripts/chart.js"></script>
  </body>
</html>
