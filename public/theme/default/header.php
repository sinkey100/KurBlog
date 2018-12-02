
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title><?=$seo_title?></title>
    <meta name="viewport" content="width=device-width,user-scalable=0,initial-scale=1,maximum-scale=1,minimum-scale=1">
    <meta name="description" content="<?=$seo_description?>">
    <meta name="keywords" content="<?=$seo_keywords?>">
    <link rel="stylesheet" href="/static/common/css/bootstrap.css">
    <link rel="stylesheet" href="<?=__PUBLIC__?>/static/style.css">
    <script src="/static/common/js/jquery.min.js"></script>
    <script src="<?=__PUBLIC__?>/static/script.js"></script>
</head>
<body>
<div id="doc">
    <header id="header">
        <div class="fixed">
            <div class="wrapper">
                <div class="hgroup">
                    <a href="/" class="logo">
                        <h1><?php echo $_K['setting']['blog_name'];?></h1>
                    </a>
                </div>
                <nav>
                    <div class="menu-btn">
                        <i class="icon menu"></i>
                    </div>
                    <ul id="nav-list">
                        <?php foreach($_K['menu'] as $v): ?>
                            <li class="<?=$v['class']?>"><a href="<?=$v['url']?>"><?=$v['title']?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
                <form action="/search" role="search">
                    <div class="input">
                        <input name="kw" placeholder="Search"></div>
                    <button type="input">
                        <i class="icon search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="holder">
        </div>
    </header>
    <div id="container">
