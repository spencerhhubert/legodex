<!-- <html>
  <head>
  </head>
  <body> -->
    <?php
      include 'assets/functions.php';
      include_once 'assets/dbconn.php';

      $channels = new Search();
      $channels->searchChannels($_POST["input"]);
      // print_r("<div class='container'>");


      $channelNotFound = false;
      if (isset($channels->channels->pageInfo->resultsPerPage)) {
        if ($channels->channels->pageInfo->resultsPerPage < 1) {
          $channelNotFound = true;
        }
      }

      if (isset($channels->channels->pageInfo->totalResults)) {
        if ($channels->channels->pageInfo->totalResults < 1) {
          $channelNotFound = true;
        }
      }

      if ($channelNotFound == true) {
        print_r($noChannelAlertMessage);
      } else {
        print_r($channelListHeader);
        
        for ($i = 0; $i <= count($channels->channels->items) - 1; $i++) {
          // echo("<pre>");
          // print_r($channels->channels);

          $channels->mostRecentVideo($channels->getChannelId($i));

          $id = $channels->getChannelId($i);
          $pfp = $channels->getProfilePictureURL($i);
          $name = $channels->getChannelName($i);
          $channelLink = $channels->getChannelLink($i);
          $subs = $channels->getSubCount($i);
          $vidDate = $channels->getVideoDate();
          $vidLink = $channels->getVideoLink();
          $isLive = $channels->checkLive($id)[0];
          $liveLink = $channels->checkLive($id)[1];

          printChannelModule($pfp, $name, $channelLink, $subs, $vidDate, $vidLink, false, $isLive, $liveLink);

          $result = mysqli_query($conn, "SELECT * FROM channels WHERE id='$id'");
          $num_rows = mysqli_num_rows($result);

          if ($num_rows > 0) {
            print_r('<div class="container"><h2 style="margin-top: 10px;">This channel has already been added</h2></div>');
          } else {
            $insertChannelDB = "INSERT INTO channels (id, pfp, name, channelLink, subs, vidDate, vidLink, isLive, liveLink) VALUES ('$id', '$pfp', '$name', '$channelLink', '$subs', '$vidDate', '$vidLink', '$isLive', '$liveLink');";
            mysqli_query($conn, $insertChannelDB);
            print_r('<div class="container"><div><h2 style="margin-top: 15px; text-align: center;">Congratulations! Your channel has been added to the LEGO YouTuber Index!</h2><h2 style="margin-top: 5px; text-align: center;">Share with your friends to grow the index!</h2></div></div>');
          }
        }     
      }

      // print_r("</div>");
    ?>
  <!-- </body>
</html> -->


