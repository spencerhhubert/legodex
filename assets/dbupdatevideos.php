<?php
include 'functions.php';
include_once 'dbconn.php';

$result = mysqli_query($conn, "SELECT * FROM channels");
$resultCheck = mysqli_num_rows($result);

if ($resultCheck > 0) {
    $test = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];

        $currentChannel = new Search();
        $currentChannel->mostRecentVideo($id);
        $vidDate = $currentChannel->getVideoDate();
        $vidLink = $currentChannel->getVideoLink();

        $update = "UPDATE channels SET
        vidDate = '$vidDate',
        vidLink = '$vidLink'
        WHERE id = '$id'
        ";

        mysqli_query($conn, $update);
        echo $test++ . " ";

    }
    echo "<br><br>Video database update complete<br><br>" . $test . " Entries updated";
} else {
    echo "I'm broken";
}