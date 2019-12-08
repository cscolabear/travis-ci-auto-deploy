<?php

function file_get_contents_utf8($fn) {
    $opts = array(
        'http' => array(
        'method'=>"GET",
        'header'=>"Content-Type: text/html; charset=utf-8"
        )
    );

    $context = stream_context_create($opts);
    $result = @file_get_contents($fn,false,$context);
    return $result;
}

$api_url = 'https://cloud.culture.tw/frontsite/trans/SearchShowAction.do?method=doFindTypeJ&category=8';
$json_raw_data = file_get_contents_utf8($api_url);
$json_data = json_decode($json_raw_data, true);

usort($json_data, function($a, $b) {
    return $a['hitRate'] <=> $b['hitRate'];
});

$data = [
    'activities' => $json_data,
    'last_update_date_at' => time(),
];
file_put_contents('./json_data.json', json_encode($data));

echo '----DONE----';
