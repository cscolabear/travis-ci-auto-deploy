<?php
$json_data = file_get_contents('json_data.json');
$data = json_decode($json_data, true);

if (!$data) {
    throw new Exception('json data not found!');
}

$today_activity_list = [];
define('TODAY_TIMESTAMP', strtotime(gmdate('Y-m-d')));

function isActivityPeriod(string $start_date, string $end_date) {
    $start_date_timestamp = strtotime($start_date);
    $end_date_timestamp = strtotime($end_date);

    if (
        $end_date_timestamp > TODAY_TIMESTAMP
        && TODAY_TIMESTAMP >= $start_date_timestamp
    ) {
        return true;
    }

    return false;
}

foreach ($data['activities'] as $activity) {
    if (count($activity) >= 100) {
        break;
    }

    if (!isActivityPeriod($activity['startDate'], $activity['endDate'])) {
        continue;
    }
    $start_date_timestamp = strtotime($activity['startDate']);
    $end_date_timestamp = strtotime($activity['endDate']);

    $today_activity_list[] = [
        'title' => $activity['title'],
        'desc' => $activity['descriptionFilterHtml'],
        'source_url' => $activity['sourceWebPromote'],
        'source_name' => $activity['sourceWebName'],
    ];
}
?>
<style>
h1 span {
    font-size: 10px;
}
table {
  width: 90%;
  text-align: left;
  border-collapse: collapse;
  font-size: 12px;
}
table, th, td {
    padding: 0.2rem;
    vertical-align: top;
    border: 1px solid #1C6EA4;
    background-color: #EEEEEE;
}
.desc {
    font-size: 10px;
}
</style>

<?php
date_default_timezone_set('Asia/Taipei');
$today_date = gmdate('Y-m-d');

$dt = new DateTime();
$dt->setTimestamp($data['last_update_date_at']);
$last_update_date = $dt->format('Y-m-d H:i:s');

echo "<h1>
    今日政府資料開放平台 - 電影類 Preview ({$today_date})
    <span>最後更新日: {$last_update_date}</span>
</div>";
echo '
<table>
    <tr>
        <th>title</th>
        <th>source_url</th>
        <th>source_name</th>
        <th>desc</th>
    </tr>
';
foreach ($today_activity_list as $activity) {
    $desc = nl2br($activity['desc']);
    echo "
        <tr>
            <td>{$activity['title']}</td>
            <td>{$activity['source_url']}</td>
            <td>{$activity['source_name']}</td>
            <td class=\"desc\">{$desc}</td>
        </tr>
    ";
}
echo '</table>';
?>
<div>
    open api source: <a href="https://data.gov.tw/dataset/6010" target="_blank">政府資料開放平台 - 電影類</a>
</div>
