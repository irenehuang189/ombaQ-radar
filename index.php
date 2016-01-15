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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css" />
    <link rel="stylesheet" href="styles/main.css" />

  </head>
  <body>
    <!--[if lt IE 10]>
      <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    <main>
      <div class="center-align container" id="index-banner">
        <div class="section">
          <h1 class="header">ombaQ Radar</h1>
          <h5 class="header col s12 light">a snap insight from your latest Instagram data</h5>
        </div>

        <div class="row section">
          <form class="col s5 offset-s3" id="search-form">
            <div class="input-field">
              <i class="material-icons prefix">search</i>
              <input class="center" id="hashtag-search" type="text" placeholder="Search" required />
            </div>
          </form>
        </div>

        <div class="section">
          <h6 class="row">Popular Queries:</h6>
          <div class="col s1" id="tags">
            <div class="chip">
              Tag
            </div>
          </div>
        </div>
      </div>
    </main>
    <footer class="page-footer z-depth-1 grey lighten-4 black-text center-align">
      © Copyright 2015 ombaQ, All Rights Reserved
    </footer>

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0-beta1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/js/materialize.min.js"></script>
  </body>
</html>
