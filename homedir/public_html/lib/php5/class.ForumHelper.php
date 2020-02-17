<?php
class ForumHelper extends Main {
    function decoratePostBody($content) {
        // format URLs
        $content = preg_replace("#(^|\s)((http|https|html)://([^ \s\",<]*))#im", "<a href=\"\\2\">\\4</a>", $content);
        $content = preg_replace("#(^|\s)(www\.[^ \s\",<]*)#im", "<a href=\"http://\\2\">\\2</a>", $content);
        // format e-mails
        $content = preg_replace("#(^|\s)([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#im", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $content);
        // format newlines
        $content = nl2br($content);
        return $content;
    }
}
?>