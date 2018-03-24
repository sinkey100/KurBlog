<?php include __THEME__.'/header.php'; ?>


<div id="content">
<div class="list_post">
<?php foreach ($data as $v): ?>
    <?php
        switch ($v['category_id']){
            case 2:
                include 'article-twitter.php';
                break;
            case 3:
                include 'article-images.php';
                break;
            default:
                include 'article-dairy.php';
        }
    ?>
<?php endforeach; ?>
</div>
<div class="page_nav">
<?php echo str_replace(['&laquo;','&raquo;'],['Previous page','Next page'],$data->render());   //输出分页代码 ?>
</div>
<?php include __THEME__.'/footer.php'; ?>
