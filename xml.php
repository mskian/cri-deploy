<?php

header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');
header('Strict-Transport-Security: max-age=63072000');
header('Content-type:application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');
header('X-Robots-Tag: noindex, nofollow', true);

// Convert JSON data to XML - https://craftytechie.com/how-to-convert-json-to-xml-in-php/

$JSON = file_get_contents('YOUR JSON API URL');
$JSON_arr = json_decode($JSON, true);

//print_r($JSON_arr);

$xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><feed xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:atom="http://www.w3.org/2005/Atom" version="2.0"></feed>');

function arraytoXML($json_arr, &$xml)
{
    foreach($json_arr as $key => $value)
    {
        if(is_int($key))
        {
            $key = 'Element'.$key; 
        }
        if(is_array($value))
        {
            $label = $xml->addChild($key);
            arrayToXml($value, $label);
        }
        else
        {
            $xml->addChild($key, htmlspecialchars($value));
        }
    }
}

arraytoXML($JSON_arr, $xml);
print_r($xml->asXML('score.xml'));

?>