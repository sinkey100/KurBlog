<?php
/*---Name:默认页面---*/
?>
<?php include __THEME__.'/header.php'; ?>
<section id=page class="main wrapper">
    <h2><?=$data['title']?></h2>
    <div class=post>
        <div class="post-content markdown">
            <?=$data['content']?>
        </div>
        <h4 class=post-eof>
            <span>EOF</span>
        </h4>
    </div>
</section>

<?php include __THEME__.'/footer.php'; ?>
