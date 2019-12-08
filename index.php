<?php
$json_data = file_get_contents('json_data.json');
$data = json_decode($json_data, true);

$today_activity_list = [];
$today_timestamp = strtotime(gmdate('Y-m-d'));
foreach ($data['activities'] as $activity) {
    if (count($activity) >= 100) {
        break;
    }
    if (strtotime($activity['startDate']) !== $today_timestamp) {
        continue;
    }

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
$today_date = gmdate('Y-m-d');
echo "<h1>
    今日政府資料開放平台 - 電影類 Preview ({$today_date})
    <span>最後更新日: {$data['last_update_date']}</span>
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
