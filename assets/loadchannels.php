<?php
$channelNewCount = 0;

if ($resultCheck > 0) {
    print_r($channelListHeader);
    while ($row = mysqli_fetch_assoc($result)) {
        $pfp = $row['pfp'];
        $name = $row['name'];
        $channelLink = $row['channelLink'];
        $subs = $row['subs'];
        $vidDate = $row['vidDate'];
        $vidLink = $row['vidLink'];
        printChannelModule($pfp, $name, $channelLink, $subs, $vidDate, $vidLink, false);
    }
} else {
    echo "There are no channels";
}