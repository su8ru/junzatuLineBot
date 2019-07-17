<?php

require_once __DIR__ . '/config.php';

date_default_timezone_set('Asia/Tokyo');

function txt_msg($text){
	return [
		"type" => "text",
		"text" => $text
	];
}

function img_msg($original, $preview){
	return [
		"type" => "image",
		"originalContentUrl" => $original,
		"previewImageUrl" => $preview,
	];
}

function action($lavel, $text){
    return [
        "type" => "postback",
        "label" => $lavel,
        "text" => $text,
    ];
}

function button_msg($altText, $title, $text, $actions){
    return [
        "type" => "template",
        "altText" => $altText,
        "template" => [
            "type" => "buttons",
            "title" => $title,
            "text" => $text,
            "actions" => $actions,
        ],
    ];
}

function var_msg($v){
	return $v;
}

function rule_confirm($group){
	return [
		"type" => "template",
		"altText" => "altTextだよ！",
		"template" => [
			"type" => "confirm",
			"text" => $group . "版要項に同意しますか？",
			"actions" => [
				[
					"type" => "message",
					"label" => "同意する",
					"text" => "同意します",
				],
				[
					"type" => "message",
					"label" => "同意しない",
					"text" => "同意しません",
				],
			],
		],
	];
}

function confilm_msg($altText, $text, $actions){
    return [
        "type" => "template",
        "altText" => $altText,
        "template" => [
            "type" => "confilm",
            "text" => $text,
            "actions" => $actions,
        ],
    ];
}

//メッセージの返信
function reply_messages($accessToken, $replyToken, $return_msgs){
	//ポストデータ
	$post_data = [
		"replyToken" => $replyToken,
		"messages" => $return_msgs
	];

	//curl実行
	$ch = curl_init("https://api.line.me/v2/bot/message/reply");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charser=UTF-8',
		'Authorization: Bearer ' . $accessToken
	));
	$result = curl_exec($ch);
	putlog($result);
	curl_close($ch);
}


function push_messages($accessToken, $toUser, $msgs, $showlog=false){
	//ポストデータ
	$post_data = [
		"to" => $toUser,
		"messages" => $msgs
	];

	//curl実行
	$ch = curl_init("https://api.line.me/v2/bot/message/push");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charser=UTF-8',
		'Authorization: Bearer ' . $accessToken
	));
	$result = curl_exec($ch);
	curl_close($ch);
	if($showlog){
		echo $result;
	}
}

function multi_messages($accessToken, $users, $msgs){
	//ポストデータ
	$post_data = [
		"to" => $users,
		"messages" => $msgs
	];

	//curl実行
	$ch = curl_init("https://api.line.me/v2/bot/message/multicast");
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charser=UTF-8',
		'Authorization: Bearer ' . $accessToken
	));
	$result = curl_exec($ch);
	curl_close($ch);
}

function getUserProfile($id, $accessToken){
	$ch = curl_init("https://api.line.me/v2/bot/profile/".$id);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charser=UTF-8',
		'Authorization: Bearer ' . $accessToken
	));
	$response = curl_exec($ch);
	curl_close($ch);
	return json_decode($response,true);
}   //追加されている必要あり

function getUserProfileGroup($gid, $uid, $accessToken){
	$ch = curl_init("https://api.line.me/v2/bot/group/".$gid."/member/".$uid);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json; charser=UTF-8',
		'Authorization: Bearer ' . $accessToken
	));
	$response = curl_exec($ch);
	curl_close($ch);
	return json_decode($response,true);
	//グループ内でのみ使用可能
}

function getGroupName($groupId){
	switch(true){
		case ($groupId === $jzmaingid) :
			return "純雑メイングル[jzmain]";

		case ($groupId === $jzsubgid) :
			return "純雑サブグル[jzsub]";

		case ($groupId === $jzlawlessgid) :
			return "純雑無法地帯グル[jzlawless]";

		default :
			return "error：登録されていないグループの可能性があります。";
	}
}

function get_googl_url($long_url){
	$api_url = 'https://www.googleapis.com/urlshortener/v1/url';
	$api_key = 'AIzaSyAXqxfTm-BPHYOO1UrbjFPNQspgL2a7YB8';
	$curl = curl_init("$api_url?key=$api_key");
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, '{"longUrl":"' . $long_url . '"}');
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$res = curl_exec($curl);
	curl_close($curl);
	$json = json_decode($res);
	$googl_url = $json->{'id'};
	return $googl_url;
}   //goo.gl

function google_search($search){
	$paramAry = array(
		'hl' => 'ja',
		'q' => $search,
		'key' => 'AIzaSyAXqxfTm-BPHYOO1UrbjFPNQspgL2a7YB8',
		'cx' => '010011882786758242786:cdk9qieahjg',
		'alt' => 'json',
	);
	$param = http_build_query($paramAry);

	$reqUrl = 'https://www.googleapis.com/customsearch/v1?' . $param;
	$resJson = @file_get_contents($reqUrl, true);
	if($resJson !== false){
		$googleres = json_decode($resJson);
		$items = $googleres->{'items'};
	}
	return $items;
}



function putlog($s){
	file_put_contents ( "log.txt", $s."\n", FILE_APPEND );
}

function putmsglog($s){
	file_put_contents ( "msg_log.txt", $s."\n", FILE_APPEND );
}

?>
