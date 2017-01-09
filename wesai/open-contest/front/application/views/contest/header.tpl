<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="format-detection" content="telephone=no" />
    <title>
    {if !empty($authorizer_app)}
        {$industry_name.title_list}—{$authorizer_app.nick_name}
    {else}
        {$industry_name.title_list}
    {/if}
    </title>
    <meta name="keywords" content="{$industry_name.items_tobuy}" />
    <meta name="description" content="{$industry_name.title_list}" />
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="icon" href='{"img/wesailogo.png"|cdnurl}'>
    <link rel="stylesheet" href="{'css/mainred.css'|cdnurl}">
</head>
<body>
{include file='../contest/_loading.tpl'}
