<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1">
	<link rel="shortcut icon" href="/favicon.ico">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" href="{'build/main.css'|cdnurl}">	
	<title>{if !empty($title)}{$title}{/if} {if !empty($authorizer_app) && $authorizer_app.fk_corp != 2}—{$authorizer_app.nick_name}{/if}</title>
</head>
<body>

