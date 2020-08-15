<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-129292476-4"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'UA-129292476-4');
</script>

<?php
include_once 'assets/dbconn.php';
include 'assets/functions.php';

$luckyChannelId = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM channels ORDER BY RAND() LIMIT 1"));
$luckyChannelLink = makeChannelLink($luckyChannelId['id']);

header("Location: " . $luckyChannelLink);
?>