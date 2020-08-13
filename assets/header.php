<?php // echo date('l jS \of F Y h:i:s A'); ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>The LEGO YouTuber Index</title>
  <link rel='icon' href='assets/images/logo-low-res.png' type='image/x-icon'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- <link rel="stylesheet" type="text/css" href="assets/style.css?Thursday 24th of April 2008 04:45:21 PM" /> -->
  <link href="assets/style.css" rel="stylesheet" type="text/css">
  <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet'>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="assets/functions.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://kit.fontawesome.com/21a7b3848c.js" crossorigin="anonymous"></script>

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-129292476-4"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-129292476-4');
  </script>

  <script
    src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
    crossorigin="anonymous">
  </script>

  <script>
  function removeButton(total, current) {
    if (total <= current) {
          let loadMore = document.querySelector('#loadMore');
          loadMore.style.display = 'none';
        }  
  }

  //load more channels
    $(document).ready(function() {
      var allcount = Number($('#all').val());
      let growthRate = 25;
      let channelCount = growthRate;
      removeButton(allcount, channelCount);

      $("#loadMore").click(function() {
        channelCount += growthRate;
        document.getElementById("channels").insertAdjacentHTML("afterend", "<div class='container' id='loadWheel'><i class='fas fa-cog fa-spin'></i></div>");
        $("#channels").load("assets/loadmorechannels.php", {
            channelNewCount: channelCount
        });

        document.getElementById("loadWheel").remove();

        removeButton(allcount, channelCount);

      });

      //search channels
      $("#submit").click(function() {
        let inputSearch = $("#search").val();
        document.getElementById("results").innerHTML = "<div class='container'><i class='fas fa-cog fa-spin'></i></div>";
        $("#results").load("addchannel.php", {
          input: inputSearch
        });
      });

      //enter key for search channel form
      let searchForm = $("#search");
      searchForm.keyup("keyup", function(event) {
        if (event.keyCode === 13) {
          event.preventDefault();
          document.getElementById("submit").click();
        }
      });
    });
  </script>
</head>