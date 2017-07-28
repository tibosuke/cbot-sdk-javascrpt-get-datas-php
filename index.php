<?php
 header("Content-Type: image/gif");
 //キャッシュされないようにヘッダを設定
 header("Expires:Fri, 10 May 2013 00:00:00 GMT");
 header("Cache-Control:private, no-cache, no-cache=Set-Cookie, must-revalidate");
 header("Pragma: no-cache");
 $id = $_GET['cid'];
 $type = $_GET['type'];
 $url = $_GET['url'];
 $ref = $_GET['ref'];
 $pt = $_GET['pt'];
 $res = $_GET['res'];
 $tcuid = $_GET['_tcuid'];
 $tcsid = $_GET['_tcsid'];
 //1×1の透過画像を返す（ここではbase64エンコードを使っている）
 echo base64_decode("R0lGODlhAQABAIAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw%3D%3D");
 //以降、アクセス集計処理を記載

// クライントからこの2つが送られてくる想定
$api_key = $_GET['api_key'];
$app_id = $_GET['app_id'];

$api_url = 'https://';
$env = getenv('ENV');
if ($env == 'prod') {
  $api_url = $api_url . "api-sdk.engage-bot.asia";
} else {
  $api_url = $api_url . "api-sdk.cbot-dev.com";
}

if(empty($type)){
	$type = "Log"
}

$fields = array(
  'eventName' => $type,
  'eventData' => array(
    // データを追加する場合はここに追加する
    'type' => 'web',
    'data1' => 'data1',
    'id' => urlencode($id),
    'url' => urlencode($url),
    'ref' => urlencode($ref),
    'pt' => urlencode($pt),
    'res' => urlencode($res),
    'tcuid' => urlencode($tcuid),
    'tcsid' => urlencode($tcsid),
    // http://qiita.com/kojii/items/0ecece2c1b30b4f3f6e4
    // >UIDはCookieのexpier属性(有効期限)を一年から二年とした長期間有効なCookieに設定することでユーザーを一意に識別します。
    // UIDをdistinctIdとして、ユーザーを一意と認識するために仕様する
    'distinctId' => urlencode($tcuid),
  )
);

// Stage環境でのテスト用のキー
// if (!isset($api_key)) $api_key = "6780e8bdce1646079a403ae759f76327";
// if (!isset($app_id)) $app_id = "1c0e34c3802d4f558c91bd7573a1270e";

$headers = array(
  'x-api-key:' . $api_key,
  'x-app-id:' . $app_id,
  'Content-Type:application/json'
);

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $api_url . '/events');
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($fields));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($curl);
print_r($response);
curl_close($curl);
?>
