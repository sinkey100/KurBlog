<?php
/*---Name:默认页面---*/
?>
<?php include __THEME__.'/header.php'; ?>


    <link href="/static/common/css/highlight.css?1.1" rel="stylesheet">
    <div id="content">
        <div class="page markdown">
            <div class="page-title">
                <h2><?= $data['title'] ?></h2>
                <span id="article-info">
                <time>发布时间：<?=$data['create_time']?>
                </span>
            </div>
            <div class="body">
                <div class="article-content js-gallery">
                    <?=$data['content']?>
                </div>
            </div>
            <div class="commenthead">
                <p style="font-size: 12px;font-weight: 100;">如果你想转载，请注明来源或者出处</p>
            </div>
        </div>
    </div>
	<script src="/static/common/js/highlight.min.js"></script>
	<script>hljs.initHighlightingOnLoad();</script>
	 <?php
        if($data['allow_comment'] == 1){
            include __THEME__.'/comment.php';
        }

        include __THEME__.'/footer.php'; ?>

