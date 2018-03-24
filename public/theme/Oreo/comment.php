<script src="<?=__THEME__?>/js/comment.js"></script>
<div id="comment">
    <div class="comment-list">
        <?=$comment['html']?>
    </div>
	<div class="wantcom">「人生在世，留句话给我吧」</div>
	<div class="send-comment">
    <h4>发表评论</h4>
    <p class="errotext">人生在世，错别字在所难免，无需纠正，<span class="editfrom">##修改表单信息##</span></p>
    <a name="post-comment"></a>
    <form id="comment-form" autocomplete="on" onload="setCookie()">
        <input type="hidden" name="type" value="<?=$comment['type']?>" class="type">
        <input type="hidden" name="value" value="<?=$comment['value']?>" class="value">
        <input type="hidden" name="parent_id" id="parent_id" value="0">
        <div class="comment-form-info">
            <div class="form-group">
                <label for="author">昵称：</label>
                <input type="text" class="form-control" id="author" name="author" aria-required="true" required="required">
            </div>
            <div class="form-group">
                <label for="email">邮箱：</label>
                <input type="email" class="form-control" id="email" name="email" aria-required="true" required="required">
            </div>
            <div class="form-group">
                <label for="url">网址：</label>
                <input type="text" class="form-control" id="url" name="url" aria-required="true" required="required" value="http://">
            </div>
        </div>
        <div class="form-group" style="border: 0px solid #eee;">
            <textarea class="form-control" rows="4" placeholder="世事如书，我偏爱你这一句。" id="comment-content" name="content"></textarea>
        </div>
        <div class="clearfix"></div>
        <div class="send-btn">
        <button type="submit" id="comment-submit" class="btn btn-default">点我，提交评论</button>
        </div>
    </form>
    </div>
	
	
</div>