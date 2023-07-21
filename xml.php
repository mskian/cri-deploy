<?php

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('Content-Type: text/xml; charset=utf-8', true);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
header('X-Robots-Tag: noindex, nofollow', true);
header('Expires: Thu, 1 Jan 1970 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0',false);
header('Pragma: no-cache');

$url = "YOUR JSON API URL";

$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.89 Safari/537.36');
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 0);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$data = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

$obj = json_decode($data);
$title = ($obj->livescore->title);
$score = ($obj->livescore->current);
$update = ($obj->livescore->update);
$batsman = ($obj->livescore->batsman);
$batsmanrun = ($obj->livescore->batsmanrun);
$batsmanballs = ($obj->livescore->ballsfaced);
$bowler = ($obj->livescore->bowler);
$bowlerover = ($obj->livescore->bowlerover);
$bowlerruns = ($obj->livescore->bowlerruns);
$bowlerwickets = ($obj->livescore->bowlerwickets);

$rss = new SimpleXMLElement('<rss xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom"></rss>');
$rss->addAttribute('version', '2.0');
$channel = $rss->addChild('channel');
$atom = $rss->addChild('atom:atom:link');
$atom->addAttribute('href', 'https://example.com/feed');
$atom->addAttribute('rel', 'self');
$atom->addAttribute('type', 'application/rss+xml');
$title = $channel->addChild('title',$title);
$description = $channel->addChild('description','Live Cricket Score RSS XML Feed');
$link = $channel->addChild('link','https://example.com/');
$language = $channel->addChild('language','en-us');
$date_f = date("r", time());
$build_date = gmdate(DATE_RFC2822, strtotime($date_f)); 
$lastBuildDate = $channel->addChild('lastBuildDate',$date_f);
$generator = $channel->addChild('generator','Live Cricket Score XML');
$ttl = $channel->addChild('ttl', 2);

$item = $channel->addChild('item');
$title = $item->addChild('title', $title);
$description = $item->addChild('description', htmlspecialchars("🔴 " . $score . " ┃ " . $update . " <br><br>➡️ Batsman: " . $batsman . " " . $batsmanrun . $batsmanballs . " <br><br>➡️ Bowler: " . $bowler . " over: " . $bowlerover . " Runs: " . $bowlerruns . " Wickets: " . $bowlerwickets));
$guid_link = $item->addChild("link", "https://example.com/live-cricket-score/");  
$guid_link = $item->addChild("guid", "https://example.com/live-cricket-score/");
$pubDate = $item->addChild('pubDate', $date_f);

echo $rss->asXML(); 

?>
