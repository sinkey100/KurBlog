<?php
/*---Name:链接---*/
?>
<?php include __THEME__.'/header.php'; ?>
<section id="page-link" class="main wrapper ">
    <h2><?=$data['title']?></h2>

    <ul class="list">
        <?php foreach($_K['link'] as $v): ?>
            <li>
                <a href="<?=$v['url']?>" target="_blank" class="external">
                    <h4><?=$v['title']?></h4>
                    <p><?=$v['description']?></p>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

</section>

<?php include __THEME__.'/footer.php'; ?>

