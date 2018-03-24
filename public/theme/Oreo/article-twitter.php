<div class="post">
    <div class="body">
        <div class="post-content images-item">
            <a href="<?=$v['url']?>" title="<?=$v['title'] ?>">
                <div id="twitter_say" class="post-summary">
                    <p><?=$v['description']?></p>
                </div>
            </a>
        </div>
    </div>
    <p class="meta">
        <i class="iconfont icon-time"></i> <?= $v['create_time'] ?>
        <a class="more" href="<?=$v['url']?>"><i class="iconfont icon-link"></i> 查看全文</a>
    </p>
</div>