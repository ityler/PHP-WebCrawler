#!/usr/local/bin/php
<?php
/**** PHP Web Crawler news-image ****/
/** Get related image from stored rss feeds **/
/**** Tyler Normile ****/

set_time_limit(0);
ini_set('memory_limit', '-1'); 
ini_set('display_errors',true);
date_default_timezone_set('America/New_York');
include '/var/www/automate/news_image/conn.php';
include '/var/www/automate/news_image/dom_parser.php'; 

/******** Geek - Find Images ********/
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='Geek'";

if ($result = mysqli_query($dblink, $query))	{

	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
		$link = $row['Link'];
		$id = $row['id'];
		$html = file_get_html($link);
		foreach ($html->find('figure') as $e) {
			$found = $e->find('img', 0);
			$imgsrc = $found->src;
			
			$ext = end(explode(".",$imgsrc)); // explode and find img type
			if ($ext == "gif") { // remove .gif images
				$imgsrc = ''; // set to none 
			}
			
			if ($imgsrc == "")
			{
				$imgsrc = "../assets/img/fallback_img/default_img_geek.png";
			}
			
			$ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
			if (!$dblink->query($ins_query)){
				echo 'Error: '.$dblink->error;
			}
				//echo 'link:'.$link.'<br>'; // dev purp output used link
				//echo 'Src:'.$imgsrc.'<br>'; // dev purp output found img src 	
		}
		$html->clear();
	}
	mysqli_free_result($result);
}


/******** Engadget - Find Images ********/
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='Engadget'";

if ($result = mysqli_query($dblink, $query))	{

	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
		$link = $row['Link'];
		$id = $row['id'];
		$html = file_get_html($link);
		foreach ($html->find('div[id=body]') as $e) {
			$found = $e->find('img', 0);
			$imgsrc = $found->src;
			
			if ($imgsrc == "")
			{
				$imgsrc = "../assets/img/fallback_img/default_img_engadget.png";
			}
			
			$ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
			if (!$dblink->query($ins_query)){
				echo 'Error: '.$dblink->error;
			}
			
			//echo 'link:'.$link.'<br>'; // dev purp output used link
			//echo 'Src:'.$imgsrc.'<br><br>'; // dev purp output found img src 
		}
		$html->clear();
	}
	mysqli_free_result($result); // Free result set
}


/******** Wired - Find Images ********/
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='Wired'";

if ($result = mysqli_query($dblink, $query))	{

	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
		$link = $row['Link'];
		$id = $row['id'];
		$html = file_get_html($link);
		foreach ($html->find('div.entry') as $e) {
			$found = $e->find('img', 1); // WIRED - Use 2nd image in div.(0,1,2...)
			$imgsrc = $found->src;
			
			if ($imgsrc == "")
			{
				$imgsrc = "../assets/img/fallback_img/default_img_wired.png";
			}
			
			$ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
			if (!$dblink->query($ins_query)){
				echo 'Error: '.$dblink->error;
			}
			
			//echo 'link:'.$link.'<br>'; // dev purp output used link
			//echo 'Src:'.$imgsrc.'<br><br>'; // dev purp output found img src 
		}
		$html->clear();
	}
	mysqli_free_result($result); // Free result set
} 


/******** Gizmodo - Find Images ********/ //Not working for slideshow articles, storing '' in db!
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='Gizmodo'";

if ($result = mysqli_query($dblink, $query))	{

	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
		$link = $row['Link'];
		$id = $row['id'];
		$html = file_get_html($link);
		foreach ($html->find('div.post-content') as $e) {
			$found = $e->find('img', 0);
			$imgsrc = $found->src;
			
			if ($imgsrc == "")
			{
				$imgsrc = "../assets/img/fallback_img/default_img_gizmodo.png";
			}
			
			$ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
			if (!$dblink->query($ins_query)){
				echo 'Error: '.$dblink->error;
			}

			//echo 'link:'.$link.'<br>'; // dev purp output used link
			//echo 'Src:'.$imgsrc.'<br><br>'; // dev purp output found img src 
		}
		$html->clear();			
	}
	mysqli_free_result($result); // Free result set
}


/******** The Verge - Find Images ********/
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='The Verge'";

if ($result = mysqli_query($dblink, $query))	{

	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
		$link = $row['Link'];
		$id = $row['id'];
		$html = file_get_html($link);
		foreach ($html->find('div.story-image') as $e) {
			$found = $e->find('img', 0);
			$imgsrc = $found->src;
			
			if ($imgsrc == "")
			{
				$imgsrc = "../assets/img/fallback_img/default_img_verge.png";
			}
			
			$ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
			if (!$dblink->query($ins_query)){
				echo 'Error: '.$dblink->error;
			}
			
			//echo 'link:'.$link.'<br>'; // dev purp output used link
			//echo 'Src:'.$imgsrc.'<br><br>'; // dev purp output found img src 
		}
		$html->clear();
	}
	mysqli_free_result($result); // Free result set
}


/******** Tech Crunch - Find Images ********/
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='TechCrunch'";

if ($result = mysqli_query($dblink, $query))	{

	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
		$link = $row['Link'];
		$id = $row['id'];
		$html = file_get_html($link);
		foreach ($html->find('div.media-container') as $e) {
			$found = $e->find('img', 0);
			$imgsrc = $found->src;
			
			/* Remove image parameters from url */
			$trimparts = explode("?",$imgsrc); 
			$imgsrc = $trimparts['0'];
			
			if ($imgsrc == "")
			{
				$imgsrc = "../assets/img/fallback_img/default_img_tc.png";
			}
			
			$ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
			if (!$dblink->query($ins_query)){
				echo 'Error: '.$dblink->error;
			}
			
			//echo 'link:'.$link.'<br>'; // dev purp output used link
			//echo 'Src:'.$imgsrc.'<br><br>'; // dev purp output found img src 
		}
		$html->clear();
	}
	mysqli_free_result($result); // Free result set
}


/******** GigaOm - Find Images ********/
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='GigaOm'";

if ($result = mysqli_query($dblink, $query))	{

	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
		$link = $row['Link'];
		$id = $row['id'];
		$html = file_get_html($link);
		foreach ($html->find('div.thumbnail') as $e) {
			$found = $e->find('img', 0);
			$imgsrc = $found->src;
			
			/* Remove img formatting via url '?' */
			$trimparts = explode("?",$imgsrc);
			$imgsrc = $trimparts['0'];
			
			if ($imgsrc == "")
			{
				$imgsrc = "../assets/img/fallback_img/default_img_gigaom.png";
			}
			
			$ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
			if (!$dblink->query($ins_query)){
				echo 'Error: '.$dblink->error;
			}
			
			//echo 'link:'.$link.'<br>'; // dev purp output used link
			//echo 'Src:'.$imgsrc.'<br><br>'; // dev purp output found img src 
		}
		$html->clear();
	} 
	mysqli_free_result($result); // Free result set 
}


/******** SlashGear - Find Images ********/
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='Slashgear'";

if ($result = mysqli_query($dblink, $query))	{

	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
		$link = $row['Link'];
		$id = $row['id'];
		$html = file_get_html($link);
		foreach ($html->find('div.entry_single') as $e) {
			$found = $e->find('img', 0);
			$imgsrc = $found->src;
			
			if ($imgsrc == "")
			{
				$imgsrc = "../assets/img/fallback_img/default_img_slashgear.png";
			}
			
			$ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
			if (!$dblink->query($ins_query)){
				echo 'Error: '.$dblink->error;
			}

			echo 'link:'.$link.'<br>'; // dev purp output used link
			echo 'Src:'.$imgsrc.'<br><br>'; // dev purp output found img src 
		}
		$html->clear();
	}
	mysqli_free_result($result); // Free result set
}


/********** Digital Trends - Find Images *******/
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='Digital Trends'";

if ($result = mysqli_query($dblink, $query))    {

        /* fetch associative array */
        while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
                $link = $row['Link'];
                $id = $row['id'];
                $html = file_get_html($link);
                foreach ($html->find('div.first-image') as $e) {
                        $found = $e->find('img', 0);
                        $imgsrc = $found->src;

                        if (empty($imgsrc))
                        {
                                $imgsrc = "../assets/img/fallback_img/default_img_dtrends.png";
                        }

                        $ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
                        if (!$dblink->query($ins_query)){
                                echo 'Error: '.$dblink->error;
                        }

                        echo 'link:'.$link.'<br>'; // dev purp output used link
                        echo 'Src:'.$imgsrc.'<br><br>'; // dev purp output found img src
                }
                $html->clear();
        }
        mysqli_free_result($result); // Free result set
}


/********* Venture Beat - Find Images ************/
$query = "SELECT `id`,`Link` FROM `Articles` WHERE `Image` ='' AND `Source`='VentureBeat'";

if ($result = mysqli_query($dblink, $query))	{

	/* fetch associative array */
	while ($row = mysqli_fetch_assoc($result)) {  // loop through data rows found
		$link = $row['Link'];
		$id = $row['id'];
		echo $id;
		echo '<br>';
		echo $link;
		echo '<br>';
		
		$html = file_get_html($link);
		foreach ($html->find('div.entry-content-thumbnail') as $e) {
			$found = $e->find('img', 1);
			$imgsrc = $found->src;
			
			/* Remove img formatting via url '?' */
			$trimparts = explode("?",$imgsrc);
			$imgsrc = $trimparts['0'];
			
			if ($imgsrc == "")
			{
				$imgsrc = "../assets/img/fallback_img/default_img_venturebeat.png";
			}
			
			$ins_query = "UPDATE `Articles` SET `Image`='$imgsrc' WHERE `Link`='$link' AND `id`='$id' LIMIT 1";
			if (!$dblink->query($ins_query)){
				echo 'Error: '.$dblink->error;
			}
				echo 'link:'.$link.'<br>'; // dev purp output used link
				echo 'Src:'.$imgsrc.'<br>'; // dev purp output found img src 	
		}
		$html->clear();
	}
	mysqli_free_result($result);
}

mysqli_close($dblink); // Close DB link
?>