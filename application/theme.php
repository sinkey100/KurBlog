<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/3/16
 * Time: 15:18
 */

return [

    'mail' => '<table cellpadding="0" cellspacing="0" class="email-container" align="center"
width="550" style="font-size: 15px; font-weight: normal; line-height: 22px; text-align: left; border: 1px solid rgb(177, 213, 245); width: 550px;">
    <tbody>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" class="padding" width="100%" style="padding-left: 40px; padding-right: 40px; padding-top: 30px; padding-bottom: 35px;">
                    <tbody>
                        <tr class="logo">
                            <td align="center">
                                <table class="logo" style="margin-bottom: 10px;">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <span style="font-size: 22px;padding: 10px 20px;margin-bottom: 5%;color: #65c5ff;border: 1px solid;box-shadow: 0 5px 20px -10px;border-radius: 2px;display: inline-block;">
                                                    {blog_name}
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr class="content">
                            <td>
                                <hr style="height: 1px;border: 0;width: 100%;background: #eee;margin: 15px 0;display: inline-block;">
                                <p>
                                    Hi {parent_author}!
                                    <br>
                                    您在"{title}"下的回复:
                                </p>
                                <p style="background: #eee;padding: 1em;text-indent: 2em;line-height: 30px;">
                                    {parent_content}
                                </p>
                                <p>
                                    {author}对您回复
                                </p>
                                <p style="background: #eee;padding: 1em;text-indent: 2em;line-height: 30px;">
                                    {content}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align="center">
                                <table cellpadding="12" border="0" style="font-size: 16px; font-weight: bold; line-height: 25px; color: #444444; text-align: left;">
                                    <tbody>
                                        <tr>
                                            <td style="text-align: center;">
                                                <a target="_blank" style="color: #fff;background: #65c5ff;box-shadow: 0 5px 20px -10px #44b0f1;border: 1px solid #44b0f1;width: 200px;font-size: 14px;padding: 10px 0;border-radius: 2px;margin: 10% 0 5%;text-align:center;display: inline-block;text-decoration: none;"
                                                href="{url}">
                                                    去查看
                                                </a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>',
    'comment' => [
        'comment_start' => '<li class="comment alt thread-alt" id="comment-{id}">
        <div class="comment_body">
        <div class="c-avatar">
            <img alt="" class="avatar photo" height="36" width="36" src="{avatar}"
                 style="display: block;">
        </div>
        <div class="c-main">
            <div class="c-meta">
                <span class="c-author">
                    <a href="{url}" rel="external nofollow" class="url" target="_blank">
                       {author}
                    </a>
                </span>
               <span class="c-time">
               {time}
                </span>
                <a rel="nofollow" class="comment-reply-link" data-id="{id}" data-author="{author}" href="#post-comment">回复</a>
            </div>
           <div class="c-content">
           {content}
            </div>
        </div></div>',
        'comment_end' => '</li>',
        'child_start' => '<ul class="children">',
        'child_end' => '</ul>',
        'empty' => '<div id="empty_comment">暂无评论</div>'
    ]

];