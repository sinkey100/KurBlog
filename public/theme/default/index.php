<?php include __THEME__.'/header.php'; ?>
    <div id="index" class="main wrapper">
        <?php foreach ($data as $v): ?>
            <div class="post">
                <div class="post-header">
                    <h1 class="post-title">
                        <a href="<?=$v['url']?>"><?=$v['title'] ?></a>
                    </h1>
                    <div class="publish-time">
                        <time><?= $v['create_time'] ?></time>
                        发表于
                        <a href="<?= $v['category']['url'] ?>"><?= $v['category']['title'] ?></a>
                    </div>
                </div>
                <div class="post-excerpt">
                    <div class="markdown">
                        <?=$v['description']?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php
echo $data->render();   //输出分页代码
include __THEME__.'/footer.php'; ?>
