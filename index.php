<?php
    include_once 'assets/dbconn.php';
    include 'assets/functions.php';
    include 'assets/header.php';
?>

<body onload="XCloseEscapeKey()">
<div class="container" style="margin-bottom: 5px; padding: 5px;">
    <div class="item" style="align-self: center;"><img src="assets/images/logo-mid-res.png" style="height: 90px;" class="homepageLogo"></div>
    <div class="item"><div><h1 style="text-align: left; font-size: .8em; font-weight: normal;"><i>The</i></h1><h1 style="text-align: left; margin-top: -3px; font-weight: bolder; margin-bottom: 0;">LEGO<br>YouTuber<br>Index</h1></div></div>
</div>

<div class="container" style="margin-bottom: 5px;">
    <div class="container buttons">
    <button onclick="on()" style="margin: 0px 10px;">
        <h2>Add my channel</h2>
    </button>
    <form action="feelinglucky.php" target="_blank">
        <button style="margin: 0px 10px;">
            <h2>I'm feeling lucky</h2>
        </button>
    </form>
    </div>
</div>

<div id="channels">
    <?php
        $result = mysqli_query($conn, "SELECT * FROM channels ORDER BY vidDate DESC LIMIT $channelLoadCount");
        $resultCheck = mysqli_num_rows($result);

        $allcount_query = "SELECT count(*) as allcount FROM channels";
        $allcount_result = mysqli_query($conn, $allcount_query);
        $allcount_fetch = mysqli_fetch_array($allcount_result);
        $allcount = $allcount_fetch['allcount'];

        include 'assets/loadchannels.php';
    ?>  
</div>

<div class="container"><button id="loadMore"><p>Show More</p></button></div>
<input type="hidden" id="all" value="<?php echo $allcount; ?>">

<?php
include 'overlay.php';
include 'assets/footer.php';
?>

</body>
</html>