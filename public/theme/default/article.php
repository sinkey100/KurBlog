<?php include __THEME__.'/header.php'; ?>
<link href="/static/common/css/highlight.css" rel="stylesheet">
    <script src="/static/common/js/highlight.min.js"></script>
    <div id="post" class="main wrapper">
        <div class="post">
            <div class="post-header">
                <h1 class="post-title"><?= $data['title'] ?></h1>
                <div class="publish-time">
                    <time><?=$data['create_time']?></time>
                    发表于 <a href="<?=$data['category']['url']?>"><?=$data['category']['title']?></a>
                </div>
            </div>
                <?=$data['content']?>
            <h4 class="post-eof"><span>EOF</span></h4>

        </div>
        <?php
        if($data['allow_comment'] == 1){
            include 'comment.php';
        }
        ?>
    </div>
    <script>hljs.initHighlightingOnLoad();</script>

<?php include __THEME__.'/footer.php'; ?>
