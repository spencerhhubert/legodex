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
      if ($channels->channels->pageInfo->totalResults < 1) {
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

          printChannelModule($pfp, $name, $channelLink, $subs, $vidDate, $vidLink, false);

          
          $result = mysqli_query($conn, "SELECT * FROM channels WHERE id='$id'");
          $num_rows = mysqli_num_rows($result);

          if ($num_rows) {
            print_r('<div class="container"><h2>This channel has already been added</h2></div>');
          } else {
            $insertChannelDB = "INSERT INTO channels (id, pfp, name, channelLink, subs, vidDate, vidLink) VALUES ('$id', '$pfp', '$name', '$channelLink', '$subs', '$vidDate', '$vidLink');";
            mysqli_query($conn, $insertChannelDB);
            print_r('<div class="container"><h2>Congratulations! Your channel has been added to the LEGO YouTuber Index!</h2></div>');
          }
        }     
      }

      // print_r("</div>");
    ?>
  <!-- </body>
</html> -->


