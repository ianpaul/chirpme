#Chirpme
Chirpme is a simple Slack slash command that retrieves the latest tweet of a specified username, and returns that tweet as a URL. Slack's Twitter app, if enabled, then takes over to display the actual tweet. If you don't supply a valid Twitter username you'll get an error message.

The script is written in PHP and uses [James Mallison's PHP Wrapper for Twitter](https://github.com/J7mbo/twitter-api-php) to do all the OAuth dirty work on Twitter. 

Credit goes to [David McCreath's Is It Up for Slack](https://github.com/mccreath/isitup-for-slack),  and [Ian Anderson Gray's First Twitter app](https://github.com/iagdotme/MyFirstTwitterApp) of which this script is an amalgam with minor additions by me.

#Installation
***TL;DR:*** Run this script and [@J7mbo's wrapper](https://github.com/J7mbo/twitter-api-php) on a server with PHP and PHP cURL. Make sure your team is using Slack's built-in Twitter app for displaying tweets.

To run Chirpme you'll need a Slack team (obviously) and API access to create a new slash command for your team. To grab Twitter data you'll need a Twitter developer account and the appropriate access tokens and keys for a Twitter app. For more information on using Twitter's developer site and getting all the secret codes you need check out [Gray's tutorial](http://iag.me/socialmedia/how-to-create-a-twitter-app-in-8-easy-steps/).

Next you'll also need a server running PHP and PHP cURL. Just plop the script and Mallison's PHP Twitter wrapper into the appropriate place on your server such as `/var/www/html/chirpme`.

Once that's done, check that it's working properly by going to `http://www.YourServer.com/chirpme/chirpme.php` or whatever web address you've chosen for your site. If you see the error message for the slash command token (it's the `$msg` variable in the script) then the script is working.

As long as you have everything configured properly on Slack--check out [McCreath's Is It Up tutorial](https://github.com/mccreath/isitup-for-slack/blob/master/docs/TUTORIAL.md) for the basics--the script should just work.

Chirpme is currently configured to display tweets to all users in a given channel. If you'd rather that all team members see tweets privately just remove the following" `"response_type" => "in_channel",`.

Enjoy.