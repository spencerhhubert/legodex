<?php
include_once 'assets/dbconn.php';
include 'assets/functions.php';

$luckyChannelId = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM channels ORDER BY RAND() LIMIT 1"));
$luckyChannelLink = makeChannelLink($luckyChannelId['id']);

header("Location: " . $luckyChannelLink);