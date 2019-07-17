<?php

date_default_timezone_set('Asia/Tokyo');

require_once __DIR__ . '/PDOmysql.php';
require_once __DIR__ . '/functions.php';
//require_once __DIR__ . '/buttons.php';
require_once __DIR__ . '/config.php';

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);

//取得データ
$return_msgs=[];
foreach ($json_object->{"events"} as $event) {
	if ($event->{"type"} === "follow") {
		$replyToken = $event->{"replyToken"};
		reply_messages($accessToken, $replyToken, [txt_msg("友達追加ありがとうございます！"),txt_msg("このアカウントは純雑グループ専用です。ご了承ください。")]);
	} else if ($event->{"type"} === "unfollow") {
		putmsglog("[ブロックされました]" . getUserProfile($event->{"source"}->{"userId"}, $accessToken)["displayName"]);

	} else if ($event->{"type"} === "message") {
		//userId
		$userId = $event->{"source"}->{"userId"};

		if ($event->{"message"}->{"type"} !== "text") exit;	//text以外送ってくんな
		$replyToken = $event->{"replyToken"};			//replytoken
		$message_text = $event->{"message"}->{"text"};	//メッセージ内容
		if ($event->{"source"}->{"type"} === "user") {			//個チャ
			switch (true) {
				case (in_array($message_text,["ヘルプ","help","/ヘルプ","/help"])) :
					reply_messages($accessToken, $replyToken, [
						txt_msg("[ヘルプ]\nこのbotはadminグループ専用です。"),
					]);
					break;

				default :
					reply_messages($accessToken, $replyToken, [
						txt_msg("判別できませんでした(´・ω・｀)"),
					]);
			}


	}else if($event->{"source"}->{"type"}==="group"){	//グループ
		//userId,groupId
		$groupId = $event->{"source"}->{"groupId"};
		$userId = $event->{"source"}->{"userId"};

		if($groupId === $admingid){	//adminグル
			switch (true) {
				case ($message_text === "pushtestsub") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("返事するよ！"),
					]);

					push_messages($gbotaccessToken, $jzsubgid, [
						txt_msg("遠隔操作！\n※Pushのテストです"),
					]);
					break;

				case ($message_text === "replytest") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("replyはできたよぉ！"),
					]);
					break;

				//以下遠隔送信
				case ("m" === substr($message_text, 0, 1)) :
					push_messages($gbotaccessToken, $jzmaingid, [
						txt_msg(substr($message_text, 2)),
					]);
					break;

				case ("s" === substr($message_text, 0, 1)) :
					push_messages($gbotaccessToken, $jzsubgid, [
						txt_msg(substr($message_text, 2)),
						txt_msg("はいどーも！"),
					]);
					break;

				case ("l" === substr($message_text, 0, 1)) :
					push_messages($gbotaccessToken, $jzlawlessgid, [
						txt_msg(substr($message_text, 2)),
					]);
					break;
			}
		}

		if(in_array($groupId,[$jzmaingid, $jzsubgid, $jzlawlessgid])){	//メインサブ無法
			switch (true) {
				case ($message_text === "設定:確認") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("[グルbot]\nバージョン: Ver. 1.0\nサーバー: mixhost\nその他: PHP 7.0使用"),
						txt_msg("[グループステータス]\n\n"."groupId: " . $event->{"source"}->{"groupId"} . "\n" . "純雑系列グループのようです。"),
					]);
					break;

				case ($message_text === "getgid") :
					reply_messages($accessToken, $replyToken, [
						txt_msg($groupId),
						txt_msg("なお登録済み"),
					]);
					break;
			}
		}

		}else{ //純雑じゃないグル
			switch (true) {
				case ($message_text === "getgid") :
					putmsglog($event->{"source"}->{"groupId"} . "でgetgidが行われました。");
					reply_messages($accessToken, $replyToken, [txt_msg("groupId: "), txt_msg($event->{"source"}->{"groupId"})]);
					break;

				default :
					putmsglog($event->{"source"}->{"groupId"} . "でメッセージを受信しました。");

			}
		}


	}else if($event->{"source"}->{"type"}==="room"){	//room
		if(in_array($message_text,["ヘルプ","help","/ヘルプ","/help"])){
			reply_messages($accessToken, $replyToken, [txt_msg("ヘルプ\n\n'room'では使用できません。")]);
		}
	} else {
		putmsglog("どこから来たメッセージやねんw");
	}
}


?>
