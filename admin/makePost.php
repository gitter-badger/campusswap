<?php
include('../lib/Config.php');

$Config = new Config('../etc/config.ini');

$dir = Config::get('dir');

include($dir . 'functions.php');
include($dir . 'lib/Domains.php');

include($dir . 'lib/vers.php');
include($dir . 'lib/Posts.php');
include($dir . 'lib/Database.php');
include($dir . 'lib/DAO/AuthenticationDAO.php');
include($dir . 'lib/Log.php');

$database = new Database();
$Conn = $database->connection();


if(isset($_GET['count'])){
	$alph = "a b c d e f g h i j k l m n o p q r s t u v w x y z";
	$paragraph = 'Rick Santorum’s sweep of Mitt Romney in Tuesday’s three Republican presidential contests sets the stage for a new and bitter round of intraparty acrimony as Mr Romney once again faces a surging conservative challenge to his claim on the party’s nomination 
	Mr Santorum’s rebuke of Mr Romney could scramble the dynamics of the Republican race even as many in the party’s establishment were urging its most committed activists to finally fall in line behind Mr Romney a former Massachusetts governor Voters in three disparate states forcefully refused to do that on Tuesday 
	Instead the most conservative elements of the Republican Party’s base expressed their unease with Mr Romney by sending a resounding message that they preferred someone else And they collectively revived the candidacy of Mr Santorum who has been languishing in the background since a narrow victory in Iowa’s caucuses at the beginning of the year 
	Mr Santorum’s success on Tuesday night awarded him no delegates from contests that were essentially nonbinding straw polls and drew small turnouts in all three states And Mr Santorum’s campaign has few of the organizational advantages of Mr Romney’s well-financed effort 
	The long-term damage to Mr Romney is difficult to assess His campaign has so far weathered several surges from challengers — Mr Santorum Newt Gingrich Rick Perry and Herman Cain — only to re-emerge as the leading contender to face President Obama in the fall He also has the support of a well-financed “super PAC  which has demonstrated a willingness to spend heavily on advertising critical of Mr Romney’s rivals 
	Aides to Mr Romney said they were preparing to quickly expand their attacks on Mr Santorum’s record as they try to define him aggressively and negatively for voters who still see Mr Santorum largely as a blank slate The advisers said Mr Romney would most likely take part in the attacks on Mr Santorum much like he did in Florida against Mr Gingrich 
	He’s a limited guy he’s been in Washington all his whole life voted for the Bridge to Nowhere voted against right to work Stuart Stevens a top aide to Mr Romney said of Mr Santorum on Tuesday night He’s cut tons and tons of deals lost his own state by 18 points 
	Mr Romney’s campaign is prohibited from coordinating privately with Restore Our Future the super PAC backing him But the public message from Mr Romney’s aides seems to be: hit Santorum now and hit him hard For the moment however officials at Restore Our Future said the group is continuing its criticism of Mr Gingrich with a new ad in Ohio 
	The potential reordering of the campaign comes just as the Republican race was set to enter a more fallow period for the first time since voting began in Iowa on Jan 3 The only votes cast during the next three weeks will be in the small state of Maine and the next presidential debate will not occur for two weeks 
	In interviews on Wednesday morning Mr Santorum predicted that Mr Romney would seek to fill that void with new searing attacks in an effort to blunt the fresh momentum behind Mr Santorum’s campaign 
	“Mitt Romney is saying that I’m not a conservative That’s almost laughable for a moderate Massachusetts governor who has been for big-government programs  Mr Santorum said on “Fox and Friends on Wednesday morning “Look We’ve got the best record He’s going to have to live with that record 
	Mr Santorum said of his victories that he “felt it coming and promised to campaign aggressively in Michigan which votes at the end of the month and said he was already beginning to raise more money to allow him to better compete with Mr Romney as the race moves forward 
	In remarks late Tuesday night Mr Santorum argued that his victories in Colorado Minnesota and Missouri were the first true test of Republican intentions because they were not influenced by a barrage of negative ads like those aired in Iowa Florida and South Carolina 
	The campaigns and the super PACs backing them spent a combined total of just over $500 000 in all three states — a fraction of the tens of millions of dollars spent on primarily negative character attacks in the previous five presidential contests 
	“Tonight we had an opportunity to see what a campaign looks like when one candidate isn’t outspent five or ten to one  Mr Santorum said to an enthusiastic crowd Tuesday night He called the results a “more accurate representation of what the fall race will look like 
	But that may soon change again Mr Romney’s campaign renewed the assault on Mr Santorum’s record in the wee hours of Wednesday morning Mr Stevens the Romney adviser promised “clear contrasts between the two men in the days ahead and accused Mr Santorum of being a creature of Washington 
	“I mean he’s someone who’s been involved in Washington for a very long time and that’s a completely different approach than Governor Romney  Mr Stevens said just before 2 a m Wednesday morning “I just don’t think it’s a time when people are looking to Washington to solve problems with Washington';
	
	//25
	
	$alphabet = explode(" ", $alph);
	$words = explode(" ", $paragraph);
	
	$count = $_GET['count'];
	
	$username[0] = 'vas@hartford.edu';
	$username[1] = 'billy@ccsu.edu';
	$username[2] = 'jimmy@trinity.edu';
	$username[3] = 'johnny@ccsu.edu';
	$username[4] = 'timmmy@wcsu.edu';
	$username[5] = 'tommy@hartford.edu';
	$username[6] = 'harvy@ccsu.edu';
	$username[7] = 'stew@trinity.edu';
	$username[8] = 'parker@ccsu.edu';
	$username[9] = 'wendy@wcsu.edu';
	
	$title = "";
	$post = "";
	$count1 = 0;
	
	for($x = 0; $x < $count; $x++){ //Ammount of posts
		$randTitleCount = rand(3, 7);
	
		$title = 'A test post #' . $count1;
		
		$count1++;
		
		$randPostCount = rand(20, 200);
		for($y = 0; $y < $randPostCount; $y++){ //Ammount of Post Words
			$post = $post . ' ' . $words[rand(0, 700)] . ' ';			
		}
		
		$randUser = rand(0, 9);
		
		$nameExplode = explode('@', $username[$randUser]);
		$user = $nameExplode[0];
		$domain = $nameExplode[1];
		
		$price = rand(1, 200);
		
		echo '<b>Title:</b> ' . $title . ' - <b>POST:</b> ' . $post . ' - <b>USER</b>: ' . $user . ' - <b>domain</b> - ' . $domain . '<br />';
		echo '<br />';
		mysqli_query($Conn, "INSERT INTO posts (item, description, username, domain, price, hits, views, created, modified, img) VALUES ('$title', '$post', '$user', '$domain', '$price', '0', '0', NOW(), NOW(), 'FALSE')");
		
		
		$title = "";
		$post = "";
		$user = "";
		$domain = "";
	}


	
	
	
}


?>