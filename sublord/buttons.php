<?php

$attention_type = [
	"type" => "template",
	"altText" => "1.R18\n2.淫夢\n3.スタ爆・連投\n4.拡散\n5.kick\n6.招待",
	"template" => [
		"type" => "buttons",
		"text" => "注意の種類を選んで！",
		"actions" => [
			[
				"type" => "message",
				"label" => "R18",
				"text" => "R18",
			],
			[
				"type" => "message",
				"label" => "淫夢",
				"text" => "淫夢",
			],
			[
				"type" => "message",
				"label" => "スタ爆・連投",
				"text" => "スタ爆・連投",
			],
			[
				"type" => "message",
				"label" => "拡散",
				"text" => "拡散",
			],
			[
				"type" => "message",
				"label" => "kick",
				"text" => "kick",
			],
			[
				"type" => "message",
				"label" => "招待",
				"text" => "招待",
			],
		],
	],
];

$diffused = [
	"type" => "template",
	"altText" => "1.荒らし・荒らし団体拡散\n2.スタプレ拡散\n3.チェンメ拡散",
	"template" => [
		"type" => "buttons",
		"text" => "拡散の種類を選んで",
		"actions" => [
			[
				"type" => "message",
				"label" => "荒らし・荒らし団体",
				"text" => "荒らし・荒らし団体拡散",
			],
			[
				"type" => "message",
				"label" => "スタプレ",
				"text" => "スタプレ拡散",
			],
			[
				"type" => "message",
				"label" => "チェンメ",
				"text" => "チェンメ拡散",
			],
		],
	],
];


?>