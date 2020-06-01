<?php
    include_once 'assets/dbconn.php';
    include 'assets/functions.php';
    include 'assets/header.php';
?>

<body onload="XCloseEscapeKey()">
<div class="container">
    <div class="item" style="align-self: center;"><img src="assets/images/logo-low-res.png" style="height: 60px;" class="homepageLogo"></div>
    <div class="item"><h1 style="text-align: center;">The LEGO YouTuber Index</h1></div>
</div>

<div class="container">
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