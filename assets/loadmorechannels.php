<?php
    include 'dbconn.php';
    include 'functions.php';
    $channelNewCount = $_POST['channelNewCount'];
    $result = mysqli_query($conn, "SELECT * FROM channels ORDER BY isLive DESC, vidDate DESC LIMIT $channelNewCount");
    $resultCheck = mysqli_num_rows($result);

    include 'loadchannels.php'
?>