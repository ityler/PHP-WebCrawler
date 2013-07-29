#!/usr/bin/php
<?php
/**** PHP Web Crawler ****/
/** Crawl RSS feeds and store related data **/
/**** Tyler Normile ****/
set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('display_errors',true);
date_default_timezone_set('America/New_York');
include __DIR__ . '/dom.php'; # for PHP 5.3+
include '/var/www/automate/news/conn_rss.php';


$rsses[] = "http://feeds.mashable.com/mashable/tech";
$rsses[] = "http://www.engadget.com/rss.xml";
$rsses[] = "http://www.geek.com/articles/gadgets/feed/";
$rsses[] = "http://www.geek.com/articles/apple/feed/";
$rsses[] = "http://www.geek.com/articles/mobile/feed/";
$rsses[] = "http://www.geek.com/articles/games/feed/";
$rsses[] = "http://www.geek.com/articles/chips/feed/";
$rsses[] = "http://www.geek.com/articles/hacks/feed/";
$rsses[] = "http://feeds.wired.com/wired/index?format=xml";
$rsses[] = "http://feeds.gawker.com/gizmodo/excerpts.xml";
$rsses[] = "http://www.theverge.com/rss/index.xml";
$rsses[] = "http://feeds.feedburner.com/TechCrunch";
$rsses[] = "http://feeds.feedburner.com/ommalik";
$rsses[] = "http://feeds.slashgear.com/slashgear";
$rsses[] = "http://www.digitaltrends.com/feed/";
$rsses[] = "http://feeds.venturebeat.com/Venturebeat";\

$doc = new DOMDocument(); 

foreach ($rsses as $e)
{
	echo "doing $e...<br>";
	$feed = $e;
	if ($feed == "http://feeds.mashable.com/mashable/tech")
	{
		$feed = "Mashable";
	}
	if ($feed == "http://www.engadget.com/rss.xml")
	{
		$feed = "Engadget";
	}
	if ($feed == "http://feeds.wired.com/wired/index?format=xml")
	{
		$feed = "Wired";
	} 
	if (strpos($feed, 'geek.com') !== false)
	{
		$feed = "Geek";
	}
	if ($feed == "http://feeds.gawker.com/gizmodo/excerpts.xml")
	{
		$feed = "Gizmodo";
	}
	if ($feed == "http://www.theverge.com/rss/index.xml")
	{
		$feed = "The Verge";
	}
	if ($feed == "http://feeds.feedburner.com/TechCrunch")
	{
		$feed = "TechCrunch";
	}
	if ($feed == "http://feeds.feedburner.com/ommalik")
	{
		$feed = "GigaOm";
	}
	if ($feed == "http://feeds.slashgear.com/slashgear")
	{
		$feed = "Slashgear";
	}
	if ($feed == "http://www.digitaltrends.com/feed/")
    {
        $feed = "Digital Trends";
    }
	if ($feed == "http://feeds.venturebeat.com/Venturebeat")
    {
        $feed = "VentureBeat";
    }

	$doc->load($e);
	
	// check resource[i] root node is 'entry'
	$entrys = $doc->getElementsByTagName("entry"); 
	$has = false;
	foreach( $entrys as $entry ) 
	{
		$has = true;
	}
	
	// check resource[i] root node is 'item'
	if(!$has)
	{
		$entrys = $doc->getElementsByTagName("item");
	}
	
	foreach( $entrys as $entry ) 
	{ 
		//url of the article in feed, 
		//headline of the article, 
		//summary text of article, 
		//time article was posted, 
		//time added to DB, 
		//image associated with article, 
		//source of the article
	
		//get rss title
		$titles = $entry->getElementsByTagName( "title" );
		$title = $titles->item(0)->nodeValue;
		
		//get rss link
		$links = $entry->getElementsByTagName( "link" );
		$link = $links->item(0)->nodeValue;
		//if link Value is NULL, then get href
		if($link == "")
		{
			$links = $entry->getElementsByTagName( "link" );
			$link = $links->item(0)->getattribute("href");
		}
		//get rss description
		$descriptions = $entry->getElementsByTagName( "description" );
		$description = $descriptions->item(0)->nodeValue;
		//if description Value is NULL, then Tag name "content"
		if($description == "")
		{
			$descriptions = $entry->getElementsByTagName( "content" );
			$description = $descriptions->item(0)->nodeValue;
		}
		// if description Value is still NULL, then Tag name "encoded" (Wired Feeds)
		if ($description == "")
		{
			$descriptions = $entry->getElementsByTagName( "encoded" );
			$description = $descriptions->item(0)->nodeValue;
		}
		
		
		//format description
		$html = str_get_html($description);
		//remove images
		foreach ($html->find("img") as $d)
		{
			$d->outertext = "";
		}
		
		//get 50 words and remove specific char 
		$description = trim($html->plaintext);
		$arr = explode(' ',str_replace('* ',"",$description));
		$i = 0;
		$description = "";
		foreach ($arr as $c)
		{
			$i++;
			if($i<=50)
			{
				$description .= $c." ";
			}
		}
		$description = trim(str_replace('</img>','',$description));
		$description = trim(str_replace('[HTML1]','',$description));
		
		//get rss pubDate
		$pubDates = $entry->getElementsByTagName( "pubDate" );
		$pubDate = $pubDates->item(0)->nodeValue;
		//if pubDate Value is NULL, then Tag name "published"
		if($pubDate == "")
		{
			$pubDates = $entry->getElementsByTagName( "published" );
			$pubDate = $pubDates->item(0)->nodeValue;
		}
		
		//get image
		$images = $entry->getElementsByTagName( "thumbnail" );
		$image = $images->item(0)->nodeValue;
		$arr = explode('src="',$image);
		if(count($arr) > 1)
		{
			$image = $arr[1];
			$arr1 = explode('"',$image);
			$image = $arr1[0];
		}
		
		//convert datetime to normal datetime
		$time = strtotime($pubDate);
		$end_date = date('Y-m-d : h:i:s',$time);
		
		//get current datetime
		$now = time();
		$now_date = date('Y-m-d : h:i:s',$now);
		
		//check the link in the DB, store to DB if did not get
		$sql = "select * from DB-Name where ? = '".mysql_real_escape_string($title)."'";
		$rsts = mysql_query($sql) or die($sql);
		$count = mysql_num_rows($rsts);
		
		/*** Check for Verge feeds - Checks for polygon.com article in the link ***/
		if (strpos($link,'polygon.com') !== false)
		{	
			$count = 1; // Sets count so query will not place entry in db
		}
		if($count<=0)
		{
			//Insert data to DB
			$sql = "insert into DB-NAME (?,?,?,?,?,?,?) values (
			'".mysql_real_escape_string($feed)."','".mysql_real_escape_string($title)."',
			'".mysql_real_escape_string($link)."','".mysql_real_escape_string($description)."',
			'".mysql_real_escape_string($end_date)."','".mysql_real_escape_string($image)."',
			'".mysql_real_escape_string($now_date)."')";
			mysql_query($sql) or die($sql);
		}
		
	}
	echo "$feed is done.<br>";
}
?>
