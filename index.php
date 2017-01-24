<html>
<head>
<style>
html,body{
	background-color:#333 !important;
	color:#fff !important;
}
.card_box{
			border-radius: 20px;
			background-color: #ddd;
		}
</style>
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<br/><br/>
<div class="col-lg-offset-3 col-lg-6 card_box">
<center><img src="twitteroauth/twitter.png" width=100></center><br/>
<center>

<form action="" method="post">
    <input name="user_name"   placeholder="Twitter Handle"/>

    <input type="submit" class="btn btn-success btn-xs"  name="Search" value="Search"/>

</form>
</center>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php
if(isset($_POST['Search'])){
include "twitteroauth/twitteroauth.php";

$consumer_key = "nVMI98t9pXdAXQWOIjk3nEfJq";
$consumer_secret = "DVMoDeNZqAL33JggJGvmDvyod3PLSjsmIP4g49P8KFi9JVXIyS";
$access_token = "48636066-3vY2ugc23HqHc8P3UvF9fesvTXvoMN0BhzsyHIsKi";
$access_token_secret = "rz8ia51Ibx3RlKhZNiR2O4r0tLF2s2N1lkCoMzUpnDUO2";

$twitter = new TwitterOAuth($consumer_key,$consumer_secret,$access_token,$access_token_secret);
$screename = $_POST['user_name'];
$twts = $twitter->get('https://api.twitter.com/1.1/search/tweets.json?q=iamsrk&result_type=recent&count=20');
$tweets = $twitter->get('statuses/user_timeline', ['count' => 200, 'exclude_replies' => true, 'screen_name' => $screename, 'include_rts' => false]);
	$totalTweets[] = $tweets;




	$page = 0;
	for ($count = 200; $count < 800; $count += 200) {
		$max = count($totalTweets[$page]) - 1;
		$tweets = $twitter->get('statuses/user_timeline', ['count' => 200, 'exclude_replies' => true, 'max_id' => $totalTweets[$page][$max]->id_str, 'screen_name' => 'iamsrk', 'include_rts' => false]);
		$totalTweets[] = $tweets;
		$page += 1;
	}
	// printing recent tweets on screen
	$index = 1;

	foreach ($totalTweets as $page) {
		foreach ($page as $key) {
			if (!empty( $key->entities->hashtags[0]->text)){

				$hashtags_r[] = $key->entities->hashtags[0]->text;

			//echo $index . ':' . $key->entities->hashtags[0]->text . '<br>';

			$index++;
			}
		}
	}


	$hashcount = array_count_values(array_map('strtolower', $hashtags_r));
	arsort($hashcount);
	//echo json_encode($hashcount,JSON_PRETTY_PRINT);
	$hashcount = array_slice($hashcount,0,10);
	echo "You Searched for $screename\n";
	echo "===========================\n\n";
	foreach($hashcount as $keys => $values){

		print_r ("<b>#$keys</b> is used $values times\n");
	}
		//print_r($hashcount);
}
?>
