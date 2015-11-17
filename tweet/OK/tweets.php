<?php
/* 
Configuration Array
 
explanation of each option can be seen here : https://dev.twitter.com/docs/api/1/get/statuses/user_timeline
 
user = the screen_name of the twitter user you wish to query
count = the "maximum" number of items to be returned
retweet = true or false to include retweets in the response
entities = true or false
exclude_replies = true or false to exclude replies
contributor_details = true or false
trim_user = true or false to trim extra user details
 
*/
 
$twitter = array(
	"user" => "sadakpramodh",
	"count" => "4",
	"retweet" => "true",
	"entities" => "true",
	"exclude_replies" => "true",
	"contributor_details" => "false",
	"trim_user" => "false"
);
 
// a small function to convert "created at" time to [blank] minutes/hours/days ago
 
function relativeTime($time)
{
    $delta = strtotime('+2 hours') - strtotime($time);
    if ($delta < 2 * MINUTE) {
        return "1 min ago";
    }
    if ($delta < 45 * MINUTE) {
        return floor($delta / MINUTE) . " min ago";
    }
    if ($delta < 90 * MINUTE) {
        return "1 hour ago";
    }
    if ($delta < 24 * HOUR) {
        return floor($delta / HOUR) . " hours ago";
    }
    if ($delta < 48 * HOUR) {
        return "yesterday";
    }
    if ($delta < 30 * DAY) {
        return floor($delta / DAY) . " days ago";
    }
    if ($delta < 12 * MONTH) {
        $months = floor($delta / DAY / 30);
        return $months <= 1 ? "1 month ago" : $months . " months ago";
    } else {
        $years = floor($delta / DAY / 365);
        return $years <= 1 ? "1 year ago" : $years . " years ago";
    }
}
 
// prepare the array
 
$twitter_feed = array();
 
// form the API url for the request
 
$api_url = "https://api.twitter.com/1/statuses/user_timeline/".$twitter['user'].
	".json?include_entities=".$twitter['entities'].
	"&include_rts=".$twitter['retweet'].
	"&exclude_replies=".$twitter['exclude_replies'].
	"&contributor_details=".$twitter['contributor_details'].
	"&trim_user=".$twitter['trim_user'].
	"&count=".$twitter['count'];
 
// obtain the results 
 
$json = file_get_contents($api_url, true);
 
// decode the json response as a PHP array
 
$decode = json_decode($json, true);
 
//check for error during the last decode
if(json_last_error != JSON_ERROR_NONE) {
	// http://www.php.net/manual/en/function.json-last-error.php
	$twitter_feed[] = array('error' => "Unable to decode response");
} elseif(isset($decode['errors'])) {
	// just grabbing the first error listed
	$twitter_feed[] = array('error' => $decode['errors'][0]['message']);
} else {
	// if no decode or twitter response errors then proceed.
 
	foreach($decode as $tweet) {
		// If you are including retweets, you may want to check the status
		// as the main text is truncated as opposed to the original tweet
 
		// If you used the trim_user option, the retweeted user screen name will not be avaialble
 
		if (isset($tweet['retweeted_status'])) {
			$tweet_text = "RT @{$tweet['retweeted_status']['user']['screen_name']}: 
			{$tweet['retweeted_status']['text']}";
		} else {
			$tweet_text = $tweet['text'];
		}
 
		$twitter_feed[] = array(
			'text' => $tweet_text, 
			'created_at' => relativeTime($tweet['created_at']),
			'link' => "http://twitter.com/".$twitter['user']."/status/".$tweet['id']
		);
 
		unset($tweet_text);
	}
}
 
unset($decode, $json, $tweet);
?>
 
<?php
 
// in a later portion of your code or page you can break down the array like so:
 
foreach($twitter_feed as $tweet) {
	echo "<a href=\"{$tweet['link']}\" target=\"_blank\">{$tweet['text']}</a><br>{$tweet['created_at']}<br><br>";
}
 
?>