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
  <div class='container'><p>Most modern YouTube channels don't have a valid username</p></div>
  <div class='container'><p>Your channel ID can also be found as a random string of characters at the end of your channel's URL</p></div>
  <div class='container'><p>Example: https://youtube.com/channel/<b><i>UCRwAFgDj1WbAik2c-AdSSig</i><b></p></div>
  <div class='container'><a href='https://support.google.com/youtube/answer/3250431?hl=en' target='_blank'><p>Here are further directions for find your channel ID in the YouTube Settings</p></a></div>
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
  public $mostRecentVideo;

  public function mostRecentVideo($channelId) {
    global $API_KEY;
    $playlistId = substr_replace($channelId, "U", 1, 1);
    $API_URL = $this->base_url . "playlistItems?order=date&part=snippet&playlistId=".$playlistId."&maxResults=25&key=".$API_KEY;
    $this->videos = json_decode(file_get_contents($API_URL));
    $this->mostRecentVideo = 0;
    for ($i = 0; $i < count($this->videos->items); $i++) {
      if (fixDateFormat($this->videos->items[$i]->snippet->publishedAt) > fixDateFormat($this->videos->items[$this->mostRecentVideo]->snippet->publishedAt) ) {
        $this->mostRecentVideo = $i;
      }
    }
    return $this->videos;
  }

  public function searchChannels($inputSearch) {
      global $API_KEY;
      $inputSearch = stripSpacesURL($inputSearch);
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
      $videoDate = $this->videos->items[$this->mostRecentVideo]->snippet->publishedAt;
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
      $videoId = $this->videos->items[$this->mostRecentVideo]->snippet->resourceId->videoId;
      $videoLink = "https://youtube.com/watch?v=" . $videoId;
      return $videoLink;
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


function printChannelModule($pfp, $name, $channelLink, $subs, $vidDate, $vidLink, bool $addButton) {
  print_r('
  <div class="container"><div class="container channel">
  <div class="php"><a href="' . $channelLink . '" target="_blank"><img src=' . $pfp . '></a></div>
  <a href="' . $channelLink . '" target="_blank" class="name"><p>' . $name . '</p></a>
  <p class="subs">' . number_format_short($subs) . '</p>
  <a class="vidDate" href="' . $vidLink . '" target="_blank"><p>' . time_elapsed_string(zuluTime($vidDate)) . '</p></a>
  ');
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
