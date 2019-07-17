<?php
//ぐるぼっと
date_default_timezone_set('Asia/Tokyo');

//require_once __DIR__ . '/PDOmysql.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/buttons.php';
require_once __DIR__ . '/config.php';
//require_once __DIR__ . '/weather.php';

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
						txt_msg("[ヘルプ]\n参加希望：参加受付開始"),
					]);
					break;

				case (in_array($message_text,["参加希望","参加申請","参加受付","参加したい"])) :
					reply_messages($accessToken, $replyToken, [
						txt_msg("参加希望ありがとうございます！\n参加手続きを開始します。"),
						var_msg($check_group),
					]);
					break;

				case ($message_text === "メイングル") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("手順2\n次に、要項に同意していただく必要があります。"),
						rule_confirm("メイングル"),
						txt_msg("要項URL:\nhttp://junzatu.com/rule/main/"),
					]);
					break;

				case ($message_text === "サブグル") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("手順2\n次に、要項に同意していただく必要があります。"),
						rule_confirm("サブグル"),
						txt_msg("要項URL:\nhttp://junzatu.com/rule/sub/"),
					]);
					break;

				case ($message_text === "無法地帯グル") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("無法地帯グルはこちら(*'▽')ﾉ"),
						txt_msg("http://line.me/ti/g/dXF9h7HGb_"),
					]);
					break;

				case ($message_text === "同意します") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("手順3\n純粋に雑談ぐるだよメイングル・サブグルには荒らしの方・半botの方は参加できません。"),
						var_msg($check_user),
					]);
					break;

				case ($message_text === "No") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("手順4\n最後に、グループの拡散をお願いします。"),
						var_msg($diffusion),
					]);
					break;

				case ($message_text === "拡散完了") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("手順5\nお疲れ様でした！あとは、純雑運営垢にKeyと拡散したスクリーンショットを送信してください。\n確認でき次第招待いたします。"),
						var_msg($send_key),
					]);
					putlog($json_object);
					break;

				case ($message_text === "同意しません") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("申し訳ありません、参加には要項に同意していただく必要があります。ご了承ください。"),
					]);
					break;

				case ($message_text === "Yes") :
					reply_messages($accessToken, $replyToken, [
						txt_msg("申し訳ありません、荒らしの方・半botの方は参加できません。ご了承ください。"),
					]);
					break;

				default :
					reply_messages($accessToken, $replyToken, [
						txt_msg("判別できませんでした(´・ω・｀)"),
					]);
			}
		}/*else if($event->{"source"}->{"type"}==="group"){	//グループ
			if(in_array($event->{"source"}->{"groupId"},[$jzmaingid, $jzsubgid, $jzlawlessgid])){ //純雑
				//userId,groupId
				$groupId = $event->{"source"}->{"groupId"};
				$userId = $event->{"source"}->{"userId"};

				//文字数カウント
				$over = true ;
				if (strpos($message_text, "以下のように設定されています") === false || strpos($message_text, "※このヘルプは、グループでは10分に一回のみ表示できます。") === false) {
					if ($event->{"source"}->{"groupId"} === $jzmaingid and mb_strlen($message_text, 'UTF-8') > 480) {
						reply_messages($accessToken, $replyToken, [
							txt_msg("480字を超えています。\n\n参考(メイングルの場合): \n240字で場合により注意\n480字で注意"),
							txt_msg("文字数: " . mb_strlen($message_text, 'UTF-8') . "字"),
							txt_msg("[注意] @" . getUserProfile($event->{"source"}->{"userId"}, $accessToken)["displayName"] . "\n長文の送信(480文字-メイングル)\nコメント:botによる自動注意です"),
						]);
						$over = false ;
					} else if ($event->{"source"}->{"groupId"} === $jzmaingid and mb_strlen($message_text, 'UTF-8') > 240) {
						reply_messages($accessToken, $replyToken, [
							txt_msg("240字を超えています。\n\n参考(メイングルの場合): \n240字で場合により注意\n480字で注意"),
							txt_msg("文字数: " . mb_strlen($message_text, 'UTF-8') . "字"),

						]);
						$over = false ;
					} else if ($event->{"source"}->{"groupId"} === $jzsubgid and mb_strlen($message_text, 'UTF-8') > 720) {
						reply_messages($accessToken, $replyToken, [
							txt_msg("720字を超えています。\n\n参考(サブグルの場合): \n480字で場合により注意\n720字で注意"),
							txt_msg("文字数: " . mb_strlen($message_text, 'UTF-8') . "字"),
							txt_msg("[注意] @" . getUserProfile($event->{"source"}->{"userId"}, $accessToken)["displayName"] . "\n長文の送信(720文字-サブグル)\nコメント:botによる自動注意です"),
						]);
						$over = false ;
					} else if ($event->{"source"}->{"groupId"} === $jzsubgid and mb_strlen($message_text, 'UTF-8') > 480) {
						reply_messages($accessToken, $replyToken, [
							txt_msg("480字を超えています。\n\n参考(サブグルの場合): \n480字で場合により注意\n720字で注意"),
							txt_msg("文字数: " . mb_strlen($message_text, 'UTF-8') . "字"),
						]);
						$over = false ;
					}
				}

				//通常のキーワード
				if ($over) {
					switch (true) {

						case (in_array($message_text, ["ヘルプ","help","/ヘルプ","/help"])) :
							reply_messages($accessToken, $replyToken, [
								txt_msg("[ヘルプ]\n\n・ヘルプ ヘルプ(これ)を表示\n・グル主 グル主のアカウントを表示(しりちゃん任せ)\n・設定:確認 現在の状況を表示\n・検索:'調べたいキーワード' '調べたいキーワード'をGoogleで検索"),
								txt_msg("頑張って機能を増やしていきます><")
							]);
							break;

						case ($message_text === "設定:確認" || $message_text === "純雑:設定確認") :
							reply_messages($accessToken, $replyToken, [
								txt_msg("[環境]\nバージョン: Ver. 1.5\nサーバー: mixhost\nその他: PHP 7.0使用"),
								txt_msg("[ぐるぼっと]\nメンテナンスモード: オン\nデバッグモード: オフ"),
								txt_msg("[グループステータス]\n"."groupId: " . $groupId . "\n" . "純雑系列グループのようです。"),
							]);
							break;

						case ($message_text === "グル主") :
							reply_messages($accessToken, $replyToken, [
								txt_msg("グル主のアカウントですね？"),txt_msg("siri:グル作成者")
							]);
							break;

						case ($message_text === "公式垢") :
							reply_messages($accessToken, $replyToken, [
								txt_msg("メンテナンス中につき現在この機能は使用できません。ご迷惑をおかけします。"),
							]);
							break;



						case (in_array($message_text,["%なう","%now"])) :
							reply_messages($accessToken, $replyToken, [
								txt_msg("今は\n" . date('Y年m月d日 D H:i:s') . "\nです！"),
							]);
							break;

						case (preg_match("/^検索:/", $message_text)) :
							$search = substr($message_text,7);
							$items = google_search($search);

							if($resJson !== false){

								$long_url = "https://www.google.co.jp/search?q=" . urlencode($search);

								reply_messages($accessToken, $replyToken, [
									txt_msg("「" . $search . "」の検索結果\n" .get_googl_url($long_url) . "\n※なぜか英語版Googleの検索結果ですがご了承ください。"),
									txt_msg("1." . $items[0]->{"title"} . "\n" . get_googl_url($items[0]->{"link"})),
									txt_msg("2." . $items[1]->{"title"} . "\n" . get_googl_url($items[1]->{"link"})),
								]);
							};
							break;

						case ($message_text === "権限確認") :
							if (in_array($userId,$admin)){
								reply_messages($accessToken, $replyToken, [
									txt_msg("あなたは Admin の権限を持っています！"),
								]);
							}else{
								reply_messages($accessToken, $replyToken, [
									txt_msg("あなたはとくに権限を持っていません！"),
								]);
							}
							break;

						case ($message_text === "testPush") :
							push_messages($accessToken, $groupId, [
								txt_msg("これはPushAPIのtestです！動いたよ！やったね！"),
							]);
							break;

						case ($message_text === "純雑:確認") :
							reply_messages($accessToken, $replyToken, [
								txt_msg(getUserProfileGroup($groupId, $userId, $accessToken)["displayName"] . "さんですね？正常に動作しています！"),
							]);
							break;

						case (in_array($message_text, ["純雑:角煮", "純雑:角煮ｺﾞﾄｺﾞﾄ", "純雑:角煮ｺﾄｺﾄ"])) :
							reply_messages($accessToken, $replyToken, [
								txt_msg(getUserProfileGroup($groupId, $userId, $accessToken)["displayName"] . "さんですね？正常に煮込まれています！"),
							]);
							break;

						case (strpos($message_text, "おは") !== false) :
							reply_messages($accessToken, $replyToken, [
								txt_msg("おはよっ！"),
							]);
							break;

						case (strpos($message_text, "おやす") !== false) :
							reply_messages($accessToken, $replyToken, [
								txt_msg("おやすみなさい。ゆっくり休んでくださいね。"),
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
		}else{
			putmsglog("どこから来たメッセージやねんw");
		}*/
	}
}


?>
