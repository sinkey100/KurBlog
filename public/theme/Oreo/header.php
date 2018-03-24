
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title><?=$title?> - <?=$_K['setting']['blog_name']?></title>
    <meta name="viewport" content="width=device-width,user-scalable=0,initial-scale=1,maximum-scale=1,minimum-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
	<link type="image/vnd.microsoft.icon" href="<?=__THEME__?>/images/favicon.png" rel="shortcut icon">
    <link href="<?=__THEME__?>/css/main.css?ver=2.2" type="text/css" rel="stylesheet"/>
    <link href="<?=__THEME__?>/css/oreo-style.css" type="text/css" rel="stylesheet"/>
    <script src="/static/common/js/jquery.min.js"></script>
</head>
<body>
<div class="oreo-wrapper">
    <div class="oreo-header">
        <div id="header">
            <p class="logo"><a href="/"><img src="<?=__THEME__?>/images/face.jpg" alt=""></a></p>
            <ul>
                <li><a href="/"><i class="iconfont icon-user"></i> <?php echo $_K['setting']['blog_name'];?></a></li>
                <li><a href="/single/after95"><i class="iconfont icon-birthdaycake"></i> After 95</a></li>
                <li><a href="/single/map"><i class="iconfont icon-local"></i> GuangZhou</a></li>
                <li><a href="/single/phper"><i class="iconfont icon-work"></i> phper</a></li>
            </ul>
        </div>
        <div id="menu" class="clearfix">
            <div class="search">
                <form action="/search" role="search">
                    <div class="input">
                        <input name="kw" placeholder="时间真是个残忍的坏蛋"></div>
                    <button alt="Search" id="searchsubmit" type="submit">
                        Search
                    </button>
                </form>
            </div>
            <div id="nav">
                <ul>
				<?php foreach($_K['menu'] as $v): ?>
                            <li><a href="<?=$v['url']?>"><?=$v['title']?></a></li>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
