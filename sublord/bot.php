<?php

date_default_timezone_set('Asia/Tokyo');

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/buttons.php';
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
		if ($event->{"message"}->{"type"} !== "text") exit;
		$replyToken = $event->{"replyToken"};			//replytoken
		$message_text = $event->{"message"}->{"text"};	//メッセージ内容
		if ($event->{"source"}->{"type"} === "user") {			//個チャ
			switch (true) {
				case (in_array($message_text,["ヘルプ","help","/ヘルプ","/help"])) :
					reply_messages($accessToken, $replyToken, [
						txt_msg("[ヘルプ]"),
						txt_msg("・注意生成 注意を生成します。ボタンに従って生成してください。"),
					]);
					break;
				
				case ($message_text === "注意生成") :
					reply_messages($accessToken, $replyToken, [
						var_msg($attention_type),
					]);
					break;
				
				case ($message_text === "R18") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("[注意] @\nR18(メイングル)\n特記事項：\"\"\nコメント：\n*サブ主による注意です*"),
						txt_msg("\"\"の中には対象のキーワードを入れてください。"),
					]);
					break;
				
				case ($message_text === "淫夢") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("[注意] @\n淫夢(メイングル)\n特記事項：\"\"\nコメント：\n*サブ主による注意です*"),
						txt_msg("\"\"の中には対象のキーワードを入れてください。"),
					]);
					break;
				
				case ($message_text === "スタ爆・連投") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("[注意] @\nスタ爆・連投\nコメント：\n*サブ主による注意です*"),
					]);
					break;
				
				case ($message_text === "拡散") :
					reply_messages($accessToken, $replyToken, [
						var_msg($diffused),
					]);
					break;
					
					case ($message_text === "荒らし・荒らし団体拡散") :
						reply_messages($accessToken, $replyToken, [
							txt_msg("[注意] @\n荒らし・荒らし団体拡散\nコメント：\n*サブ主による注意です*"),
						]);
						break;
					
					case ($message_text === "スタプレ拡散") :
						reply_messages($accessToken, $replyToken, [
							txt_msg("[注意] @\nスタプレ拡散\nコメント：\n*サブ主による注意です*"),
						]);
						break;
					
					case ($message_text === "チェンメ拡散") :
						reply_messages($accessToken, $replyToken, [
							txt_msg("[注意] @\nチェーンメール拡散\nコメント：\n*サブ主による注意です*"),
						]);
						break;
				
				case ($message_text === "kick") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("[注意] @\nkick\nコメント：\m*サブ主による注意です*"),
					]);
					break;
				
				case ($message_text === "招待") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("[注意] @\n招待\nコメント：\m*サブ主による注意です*"),
					]);
					break;
				
				default :
					reply_messages($accessToken, $replyToken, [
						txt_msg("判別できませんでした(´・ω・｀)"),
					]);
			}
		}else if($event->{"source"}->{"type"}==="group"){	//グループ
			if(in_array($event->{"source"}->{"groupId"},[$jzsublord])){
				
			}else{ //純雑じゃないグル
				switch (true) {
					case ($message_text==="getgid") :
						putmsglog($event->{"source"}->{"groupId"} . "でgetgidが行われました。");
						reply_messages($accessToken, $replyToken, [txt_msg("groupId: "), txt_msg($event->{"source"}->{"groupId"})]);
						break;
					
					default:
						putmsglog($event->{"source"}->{"groupId"} . "でメッセージを受信しました。");
					
				}
			}
		}else if($event->{"source"}->{"type"}==="room"){	//room
			if(in_array($message_text,["ヘルプ","help","/ヘルプ","/help"])){
				reply_messages($accessToken, $replyToken, [txt_msg("ヘルプ\n\n'room'では使用できません。")]);
			}
		}else{
			putmsglog("どこから来たメッセージやねんw");
		}
	}
}


?>