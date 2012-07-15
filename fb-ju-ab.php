<?php
// the feedback-join us-about page
// anyone can view this page, and this will have a link to homepage if logged in
// else we just show the sign in button
  include('includes/config.php');
  include('includes/aux_functions.php');
  require_once('includes/facebook.php');
  $params = array('next' => 'logout.php');
  $facebook = new Facebook(array(
    "appId"   => '267545116676306',
    "secret"  => '5e33d3900a4253af9159a512ca49b6d1'
  ));
  $params = array('scope' => 'publish_stream,publish_actions',
	  'next' => 'http://localhost/iitdebates/home.php');
  $user = $facebook->getUser();
  $signed_in = false;
  if ($user) {
    try {
      $profile = $facebook->api('/me', 'GET');
      $signed_in = true;
    } catch (FacebookApiException $e) {
  }
  } else {
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>IIT Debates</title>
    <link rel="stylesheet" href="includes/bootstrap/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="includes/style.css"/>
    <link rel="icon" href="includes/favicon.ico"/>
  </head>
  <body>
    <div id="header">
      <span class="logo"><a href="home.php">IIT Debates</a></span>
      <span class="options">
        <ul>
        <?php if ($signed_in):?>
          <li class="search-form">
            <input class="navbar-search" type="text" id="friend-search" data-provide="typeahead" placeholder="Search" autocomplete="off">
            <div class="icon-search icon-black"></div>
          </li>
          <li class="log-out-link"><a href="home.php">Home</a></li>
          <li class="log-out-link"><a href="<?php echo $facebook->getLogoutUrl($params);?>">Log Out</a></li>
        <?php else:?>
          <li class="log-out-link"><a href="<?php echo $facebook->getLoginUrl($params);?>">Sign Up</a></li>
        <?php endif;?>
        </ul>
      </span>
    </div>
    <div class="well fill">
      <div class="tabbable"> <!-- Only required for left/right tabs -->
        <ul class="nav nav-tabs" id="cnt-btns">
          <li class="active"><a href="#feedback" data-toggle="tab">Feedback</a></li>
          <li><a href="#join-us" data-toggle="tab">Join Us</a></li>
          <li><a href="#about" data-toggle="tab">About</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="feedback">
            <div id="contact-box">
              <form class="form-horizontal" id="contact-form">
                <fieldset>
                  <legend>Feedback</legend>
                  We would love to hear from you about the site, and how it can be improved. We are completely open to new ideas so please pitch in!
                  <div class="control-group">
                    <label class="control-label" for="fname">Name</label>
                    <div class="controls">
                      <input type="text" class="input-xlarge" name="fname" id="cname">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="email">EMail</label>
                    <div class="controls">
                      <input type="text" type="email" name="email" class="input-xlarge" id="email">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="cmt">Feedback</label>
                    <div class="controls">
                      <textarea name="cmt" class="input-xlarge cmt" rows=10 id="cmt"></textarea>
                    </div>
                  </div>
                  <div class="form-actions">
                    <input class="submit btn btn-primary" type="submit" value="Submit"/>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
          <div class="tab-pane" id="join-us">
            <div id="contact-box2">
              <form class="form-horizontal" id="contact-form2">
                <fieldset>
                  <legend>Join Us</legend>
                    <p>
                      We don't believe that the social web is the next big thing. It was once and now people are just tired to seeing each other all day long. Now that you have a billion people online, the next challenge is how you keep them <em>engaged</em>. We among many others believe that these people now need tools to engage and interact with each other in novel ways and that is precisely what we are doing here.
                    </p>
                    <p>
                      We need help! Seriously, we have too many ideas and too few fingers to code all of them. We are a small team of people with a passion for building <em>something</em> for the web. Web development is probably the hardest thing that is not taught in school, and hence the only way to learn it is to hack it. If you like to hack through the night (such a clinch&eacute; really) or you like where we are going with all of this, please drop us your mail and we will contact you. We are firm on building this for the long term.
                    </p>
                  <div class="control-group">
                    <label class="control-label" for="email2">EMail</label>
                    <div class="controls">
                      <input type="text" type="email" name="email2" class="input-xlarge" id="email2">
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="cmt2">About you</label>
                    <div class="controls">
                      <textarea name="cmt2" class="input-xlarge cmt" rows=10 id="cmt2"></textarea>
                    </div>
                  </div>
                  <div class="form-actions">
                    <input class="submit btn btn-primary" type="submit" value="Submit"/>
                  </div>
                </fieldset>
              </form>
            </div>
          </div>
          <div class="tab-pane" id="about">
            <form class="form-horizontal">
              <fieldset>
                <legend>About IIT Debates</legend>
                  <p>
                    IIT Debates is motivated by the fact that there are so many of my friends out there, and I <em>don't</em> how to interact with them in different ways. Obviously, I can write on their walls, tweet to them, email them. But this is generic, and doesn't add any special meaning to your content. So the premise of this website is <strong>debating</strong>. There could have been many, but we chose debating since it is somewhat unique.
                  </p>
              </fieldset>
            </form>
          </div>
        </div>
      </div>
    </div>
  <script src="includes/jquery-1.7.2.min.js"></script>
  <script src="includes/bootstrap/js/bootstrap.min.js"></script>
  <script src="includes/fb-ju-ab.js"></script>
  <script src="includes/js/jquery.validate.min.js"></script>
  <script src="includes/js/validate.js"></script>
  </body>
</html>
