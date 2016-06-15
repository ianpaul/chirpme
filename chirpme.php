<?php
/** Chirpme is a simple Slack slash command that retrieves the latest tweet of a specified username, 
and returns that tweet as a URL.  
The script only works if you supply a valid 
Twitter username. eg. /chirpme ianpaul
It also relies on Slack's Twitter app to make the link look nice.
Credit goes to David McCreath's Is It Up for Slack, 
and Ian Anderson Gray's First Twitter app 
of which this script is an amalgam with minor additions by me.
**/

# This call is for James Mallison's PHP Wrapper for Twitter. 
#You can find it on Github: https://github.com/J7mbo/twitter-api-php
# You'll need to include the wrapper in the same folder as this script.
require_once('TwitterAPIExchange.php');

#These variables are used for the Slack portion of our script. 

$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];

# This checks the Slack token for the slash command, 
# and makes sure the request is from your team.
if($token != '[insert your Slack slash command token here]'){ 
  $msg = ":squirrel: The token for the slash command doesn't match. We're done here until IT fixes it. Don't worry, Squirrelock is on the case.";
  die($msg);
  echo $msg;
}

/** Set Twitter access tokens here--see: https://dev.twitter.com/apps **/
$settings = array(
	'oauth_access_token' => "[insert access token here]",
	'oauth_access_token_secret' => "[insert access token secret here]",
	'consumer_key' => "[insert consumer key here]",
	'consumer_secret' => "[insert consumer secret here]"
);

#This script uses "GET statuses/user_timeline" from the Twitter API v1.1. 
#This is the URL format for that portion of the API. 
$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
#This line is for the wrapper.
$requestMethod = "GET";
#Also for the wrapper. Tells the Twitter API the name of the user we want, 
#and how many tweets we want.
$getfield = '?screen_name='.$text.'&count=1';

#More wrapper stuff.
$twitter = new TwitterAPIExchange($settings);
# This does all the fancy Twitter authentication and then stashes
# the returned JSON data from Twitter in an associative array.
$string = json_decode($twitter->setGetfield($getfield)
                              ->buildOauth($url, $requestMethod)
                              ->performRequest(), $assoc = TRUE);

#The error message that gets returned to Slack if there's a problem. 

if($string["errors"][0]["message"]!= "") {echo "*Oops, we ran into an issue. IT has put Squirrelock on the case.* :squirrel: In the meantime, here's what Twitter had to say: "
.$string[errors][0]["message"].""; exit();}

#This is where we pull the data out of the associative array.
foreach($string as $items)
{
  $reply =  "<https://www.twitter.com/".$items['user']['screen_name']."/status/".$items['id_str']."> \n ";

}

#Now we put the data back into JSON so that Slack will do its styling magic 
#on the returned link.
$payload = json_encode(array(
  "response_type" => "in_channel",
  "text" => $reply,
  "unfurl_media" => "true",
  "unfurl_links" => "true"
));

# Without the header Slack won't recognize you're sending JSON,
# and you'll end up with a mess. 
# See Stack Overflow for more information on that trial: 
# https://stackoverflow.com/questions/37817971/slack-is-printing-raw-json-instead-of-styled-message
header('Content-Type: application/json');

#Tell Slack to print the JSON we've sent (Slack handles styling on its own).
echo $payload;

#We're done here.
?>
