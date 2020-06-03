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
        $currentChannel->searchChannels($id);

        $pfp = $currentChannel->getProfilePictureURL(0);
        $name = $currentChannel->getChannelName(0);
        $channelLink = $currentChannel->getChannelLink(0);
        $subs = $currentChannel->getSubCount(0);

        $update = "UPDATE channels SET pfp = '$pfp', name = '$name', channelLink = '$channelLink', subs = '$subs' WHERE id = '$id'";

        mysqli_query($conn, $update);
        $test++;

    }
    echo "Channel info database update complete<br><br>" . $test . " Entries updated";
} else {
    echo "I'm broken";
}