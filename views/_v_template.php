<!DOCTYPE html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> 
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en">
   <![endif]-->
   <!--[if IE 7]>    
   <html class="no-js lt-ie9 lt-ie8" lang="en">
      <![endif]-->
      <!--[if IE 8]>    
      <html class="no-js lt-ie9" lang="en">
         <![endif]-->
         <!--[if gt IE 8]><!--> 
         <html class="no-js" lang="en">
            <!--<![endif]-->
            <head>
               <title><?php if(isset($title)) echo $title; ?></title>
               <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
               <!-- Controller Specific JS/CSS -->
               <?php if(isset($client_files_head)) echo $client_files_head; ?>
               <!-- Mobile viewport optimized: h5bp.com/viewport -->
               <meta name="viewport" content="width=device-width, initial-scale=1"/>
               <link rel="stylesheet" href="/css/style.css">
               <link rel="stylesheet" media="all" href="/css/lessframework.css"/>
               <!-- All JavaScript at the bottom, except this Modernizr build.
                  Modernizr enables HTML5 elements & feature detects for optimal performance.
                  Create your own custom Modernizr build: www.modernizr.com/download/ -->
               <script src="/js/modernizr-2.5.3.min.js"></script>
            </head>
            <body>
               <!-- HEADER -->
               <header>
                  <!-- header wrapper -->
                  <div class="wrapper cf">
                     <div id="logo">
                        <a href="/" ><img src="/img/logo.png" alt="" /></a>
                     </div>
                  </div>
                  <!-- ENDS header wrapper -->
                  <!-- nav -->
                  <nav class="cf">
                     <div class="wrapper cf">
                        <ul id="nav" class="sf-menu">
                           <li><a href="/">HOME<i><b></b></i></a></li>
                           <?php if($user): ?>
                              <li><a href="/users/profile">PROFILE<i><b></b></i></a></li>
                              <li><a href="/users/follow">FOLLOW<i><b></b></i></a></li>
                              <li><a href="/posts/trend">TREND<i><b></b></i></a></li>
                              <li><a href="/users/logout">LOGOUT<i><b></b></i></a></li>
                           <?php else: ?>
                              <li><a href="/users/signup">SIGN UP<i><b></b></i></a></li>
                           <?php endif; ?>
                        </ul>
                        <div id="combo-holder"></div>
                     </div>
                  </nav>
                  <!-- ends nav -->
               </header>
               <!-- ENDS HEADER -->
               <!-- MAIN -->
               <div role="main" id="main">
                  <?php if(isset($content)) echo $content; ?>
               </div>
               <!-- ENDS MAIN -->
               <footer>
                  <!-- wrapper -->
                  <div class="wrapper cf">
                     <div id="footer-bottom">CSCIE-15 Project 2 - <a href="mailto:mahendran.nair@gmail.com">Mahendran Sreedevi</a><br/>Vintage Template designed by <a href="http://www.luiszuno.com" >luiszuno.com</a></div>
                  </div>
                  <!-- ENDS wrapper -->
               </footer>
               <!-- JavaScript at the bottom for fast page loading -->
               <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
               <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
               <script>window.jQuery || document.write('<script src="/js/jquery-1.7.1.min.js"><\/script>')</script>
               <!-- scripts concatenated and minified via build script -->
               <script src="/js/custom.js"></script>
               <script src="/js/css3-mediaqueries.js"></script>
               <script src="/js/tabs.js"></script>
               <script  src="/js/poshytip-1.1/src/jquery.poshytip.min.js"></script>
               <?php if(isset($client_files_body)) echo $client_files_body; ?>
               <!-- end scripts -->
            </body>
         </html>