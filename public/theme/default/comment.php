<script src="/theme/default/static/comment.js"></script>
<div id="comment">
    <h3>评论</h3>
    <div class="comment-list">
        <?=$comment['html']?>
    </div>
    <h3>发表评论</h3>
    <a name="post-comment"></a>
    <form id="comment-form">
        <input type="hidden" name="type" value="<?=$comment['type']?>" class="type">
        <input type="hidden" name="value" value="<?=$comment['value']?>" class="value">
        <input type="hidden" name="parent_id" id="parent_id" value="0">
        <div class="left">
            <div class="form-group">
                <label for="author">昵称</label>
                <input type="text" class="form-control" id="author" name="author">
            </div>
            <div class="form-group">
                <label for="email">邮箱</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="url">网址</label>
                <input type="text" class="form-control" id="url" name="url">
            </div>
        </div>
        <div class="form-group">
            <textarea class="form-control" rows="4" placeholder="在此留下您的看法" id="comment-content" name="content"></textarea>
        </div>
        <button type="submit" id="comment-submit" class="btn btn-default">发表</button>
        <button type="button" class="comment-cancel btn btn-link">取消回复</button>

    </form>
</div>