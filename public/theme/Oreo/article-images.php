<div class="post">
    <a href="<?=$v['url']?>" title="<?=$v['title'] ?>">
        <h2><?=$v['title'] ?></h2>
        <div class="body">
            <div class="post-content images-item">
                <div class="img-thumbnail" style="background-image:url(<?php echo empty($v['thumb']) ? '' : $v['thumb'];?>);">
                </div>
                <div class="post-summary">
                    <p><?=$v['description']?></p>
                </div>
            </div>
        </div>
    </a>
    <p class="meta">
        <i class="iconfont icon-time"></i> <?= $v['create_time'] ?>
        <a class="more" href="<?=$v['url']?>"><i class="iconfont icon-link"></i> 查看全文</a>
    </p>
</div>