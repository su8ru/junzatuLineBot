<?php

$check_group = [
	"type" => "template",
	"altText" => "手順1\n参加希望のグループを以下の中から選んでグループ名を送信してください。\n1.メイングル 2.サブグル 3.無法地帯グル",
	"template" => [
		"type" => "buttons",
		"text" => "手順1\n参加希望のグループを以下の中から選んでください。",
		"actions" => [
			[
				"type" => "message",
				"label" => "メイングル",
				"text" => "メイングル",
			],
			[
				"type" => "message",
				"label" => "サブグル",
				"text" => "サブグル",
			],
			[
				"type" => "message",
				"label" => "無法地帯グル",
				"text" => "無法地帯グル",
			],
		],
	],
];

$check_user = [
	"type" => "template",
	"altText" => "荒らしの方・半botの方は「Yes」、荒らしの方・半botでない方は「No」と送信してください。",
	"template" => [
		"type" => "confirm",
		"text" => "荒らしの方・半botの方は「Yes」、荒らしの方・半botでない方は「No」のボタンを押してください。",
		"actions" => [
			[
				"type" => "message",
				"label" => "Yes",
				"text" => "Yes",
			],
			[
				"type" => "message",
				"label" => "No",
				"text" => "No",
			],
		],
	],
];

$diffusion = [
	"type" => "template",
	"altText" => "拡散が完了したら、「拡散完了」と送信してください。",
	"template" => [
		"type" => "confirm",
		"text" => "拡散が完了したら、「拡散完了」を押してください。",
		"actions" => [
			[
				"type" => "message",
				"label" => "拡散完了",
				"text" => "拡散完了",
			],
			[
				"type" => "uri",
				"label" => "拡散する投稿",
				"uri" => "https://linebot.junzatu.com/redirect/diffusion_url.php",
			],
		],
	],
];

$send_key = [
	"type" => "template",
	"altText" => "純雑運営垢: PCからじゃ追加できないですよね…ごめんなさい。\nメイングル:##junzatuMainKey28##\nサブグル:##junzatuSubKey72##",
	"template" => [
		"type" => "buttons",
		"text" => "純雑運営垢を追加してから参加したいグループのボタンを押して、運営垢を選択してKeyとスクリーンショットを送信してください。",
		"actions" => [
			[
				"type" => "uri",
				"label" => "純雑運営垢",
				"uri" => "https://line.me/ti/p/6mGGSWcYPH",
			],
			[
				"type" => "uri",
				"label" => "メイングルKey",
				"uri" => "https://linebot.junzatu.com/redirect/junzatuMainKey.php",
			],
			[
				"type" => "uri",
				"label" => "サブグルKey",
				"uri" => "https://linebot.junzatu.com/redirect/junzatuSubKey.php",
			],
		],
	],
];

?>