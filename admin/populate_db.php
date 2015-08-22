<?php
include('../lib/Util/Config.php');

$Config = new Config('../etc/config.ini');

$dir = $Config->get('dir');

include $dir . 'admin/adminHead.php';

require_once $dir . 'lib/Faker/Faker/autoload.php';
$faker = Faker\Factory::create();

if(isset($_GET['count'])){
	
	$count = $_GET['count'];
	
        $domains = $DomainsDAO->getAllDomain();
        $domain_size = counnt($all_domains);
	
	$title = "";
	$post = "";
	$count = 0;
	
	for($x = 0; $x < $count; $x++){ //Ammount of posts
		$randTitleCount = rand(3, 7);
	
		$title = 'A test post #' . $count1;
		
		$count1++;
		
		$randPostCount = rand(20, 200);
		for($y = 0; $y < $randPostCount; $y++){ //Ammount of Post Words
			$post = $post . ' ' . $words[rand(0, 700)] . ' ';			
		}
		
                
		$rand_user = $faker->name;
                $rand_domain =  $domains[rand(0, $domain_size)];
                $rand_fn = $rand_user . '@' . $rand_domain;
		
		
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