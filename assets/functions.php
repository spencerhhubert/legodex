<?php

require_once realpath(dirname(__DIR__, 1) . "/vendor/autoload.php");
    use Dotenv\Dotenv;
    $dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
    $dotenv->load();

$channelLoadCount = 25;

if (!function_exists('stripSpacesURL')) {
  function stripSpacesURL($str) {
    $str = str_replace(' ', '+', $str);
    return $str;
  }
}

if (!function_exists('stripChannelURL')) {
  function stripChannelURL($str) {
    if(str_starts_with($str, 'https://')) {
      $str = substr($str, 8);
    }

    if(str_starts_with($str, 'www.')) {
      $str = substr($str, 4);
    }

    if(str_starts_with($str, 'youtube.com/')) {
      $str = substr($str, 12);
    }

    $str = $str . '/null';

    switch($str) {
      case str_starts_with($str, 'c/'):
      case str_starts_with($str, 'channel/'):
      case str_starts_with($str, 'user/'):

        $start = strpos($str, '/') + 1;
        $end = strpos($str, '/', $start);
        return substr($str, $start, $end - $start);
      default:
        $end = strpos($str, '/');
        return substr($str, 0, $end);
    }
  }
}

if (!function_exists('time_elapsed_string')) {
  // Glavić on stack overflow
  function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }
}

$noChannelAlertMessage = "
  <div class='error_message'>
  <div class='container'><p>Your channel couldn't be found</p></div>
  <div class='container'><p>Here are some examples of valid channel URLs:</p></div>
  <div class='container'><p><i>youtube.com/channel/UCjnwN6JTE7jQcSYX27eeT_g</i></p></div>
  <div class='container'><p><i>https://www.youtube.com/c/legospencer11</i></p></div>
  <div class='container'><p><i>youtube.com/rebellug</i></p></div>
  <div class='container'><a href='https://support.google.com/youtube/answer/6180214?hl=en' target='_blank'><p>Here are further directions for obtaining a link to your channel</p></a></div>
  </div>
  ";

$channelListHeader = '
  <div class="container">
    <div class="container channel header">
        <div class="php"></div>
        <p class="name">Channel</p>
        <p class="subs">Subscribers</p>
        <p class="vidDate"><b>Most Recent Video</b></p>
    </div>
  </div>
  ';

$API_KEY = getenv("GOOGLE_API_KEY");
global $API_KEY;

class Search {
  public $base_url = "https://www.googleapis.com/youtube/v3/";
  public $channels;
  public $videos;

  public function mostRecentVideo($channelId) {
    global $API_KEY;
    $playlistId = substr_replace($channelId, "U", 1, 1);
    $API_URL = $this->base_url . "playlistItems?order=date&part=snippet&playlistId=".$playlistId."&maxResults=1&key=".$API_KEY;
    $this->videos = json_decode(file_get_contents($API_URL));
    return $this->videos;
  }

  public function searchChannels($inputSearch) {
    $inputSearch = stripSpacesURL($inputSearch);
    $inputSearch = stripChannelURL($inputSearch);

    global $API_KEY;
    $API_URL = $this->base_url . "channels?part=snippet%2CcontentDetails%2Cstatistics&id=" . $inputSearch . "&key=" . $API_KEY;

    $this->channels = json_decode(file_get_contents($API_URL));

    if ($this->channels->pageInfo->totalResults < 1) {
      global $API_KEY;
      $API_URL = $this->base_url . "channels?part=snippet%2CcontentDetails%2Cstatistics&forUsername=" . $inputSearch . "&key=" . $API_KEY;
      $this->channels = json_decode(file_get_contents($API_URL));
    }
    return $this->channels;
  }

  public function getChannelName($resultNumber) {
      $channelName = $this->channels->items[$resultNumber]->snippet->title;
      return $channelName;
  }

  public function getChannelId($resultNumber) {
      $channelId = $this->channels->items[$resultNumber]->id;
      return $channelId;
  }

  public function getVideoDate() {
      $videoDate = $this->videos->items[0]->snippet->publishedAt;
      $videoDate = str_replace('T', ' ', $videoDate);
      $videoDate = str_replace('Z', '', $videoDate);
      return $videoDate;
  }

  public function getProfilePictureURL($resultNumber) {
      $profilePictureURL = $this->channels->items[$resultNumber]->snippet->thumbnails->default->url;
      return $profilePictureURL;
  }

  public function getChannelLink($resultNumber) {
      $channelLink = "https://youtube.com/channel/" . $this->getChannelId($resultNumber);
      return $channelLink;
  }

  public function getSubCount($resultNumber) {
      $subCount = $this->channels->items[$resultNumber]->statistics->subscriberCount;
      return $subCount;
  }

  public function getVideoLink() {
      $videoId = $this->videos->items[0]->snippet->resourceId->videoId;
      $videoLink = "https://youtube.com/watch?v=" . $videoId;
      return $videoLink;
  }

  public function checkLive($id) {
    global $API_KEY;
    $isLive = "false";
    $liveLink = "";
    $resultCheck = 0;

    $API_URL = $this->base_url . "search?part=snippet&channelId=" . $id . "&type=video&eventType=live&key=" . $API_KEY;
    // $results = json_decode(file_get_contents($API_URL));
    // $resultCheck = $results->pageInfo->totalResults;
    if ($resultCheck > 0) {
      $isLive = "true";
      $liveLink = "https://www.youtube.com/watch?v=" . $results->items[0]->id->videoId;
    }

    $output = array($isLive, $liveLink);
    return $output;
  }
}

function makeChannelLink($id) {
  $channelLink = "https://youtube.com/channel/" . $id;
  return $channelLink;
}

function zuluTime($timestamp) {
  $timestamp = $timestamp . "Z";
  return $timestamp;
}

function fixDateFormat($date) {
  $date = str_replace('T', ' ', $date);
  $date = str_replace('Z', '', $date);
  return $date;
}


function printChannelModule($pfp, $name, $channelLink, $subs, $vidDate, $vidLink, bool $addButton, $isLive, $liveLink) {
  print_r('
  <div class="container"><div class="container channel">
  <div class="php"><a href="' . $channelLink . '" target="_blank"><img src=' . $pfp . '></a></div>
  <a href="' . $channelLink . '" target="_blank" class="name"><p>' . $name . '</p></a>
  <p class="subs">' . number_format_short($subs) . '</p>');

  if ($isLive == "false") {
    print_r('
    <a class="vidDate" href="' . $vidLink . '" target="_blank"><p>' . time_elapsed_string(zuluTime($vidDate)) . '</p></a>
    ');
  }

  if ($isLive == "true") {
    print_r('
    <a class="vidDate" href="' . $liveLink . '" target="_blank"><p>🔴 Live Now!</p></a>
    ');
  }

  if ($addButton == true) {
    print_r('<button class="select"><p>Add</p></button>');
  }
  print_r('</div></div>');
}

/**
 * Function that converts a numeric value into an exact abbreviation
 * 
 * stolen from: https://ourcodeworld.com/articles/read/786/how-to-create-the-abbreviation-of-a-number-in-php
 */
function number_format_short( $n, $precision = 2 ) {
	if ($n < 900) {
		// 0 - 900
		$n_format = number_format($n, $precision);
		$suffix = '';
	} else if ($n < 900000) {
		// 0.9k-850k
		$n_format = number_format($n / 1000, $precision);
		$suffix = 'K';
	} else if ($n < 900000000) {
		// 0.9m-850m
		$n_format = number_format($n / 1000000, $precision);
		$suffix = 'M';
	} else if ($n < 900000000000) {
		// 0.9b-850b
		$n_format = number_format($n / 1000000000, $precision);
		$suffix = 'B';
	} else {
		// 0.9t+
		$n_format = number_format($n / 1000000000000, $precision);
		$suffix = 'T';
	}
  // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
  // Intentionally does not affect partials, eg "1.50" -> "1.50"
	if ( $precision > 0 ) {
		$dotzero = '.' . str_repeat( '0', $precision );
		$n_format = str_replace( $dotzero, '', $n_format );
	}
	return (float)$n_format . $suffix;
}
