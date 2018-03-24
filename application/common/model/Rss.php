<?php
/**
 * Created by PhpStorm.
 * User: Sinkey
 * Date: 2018/3/24
 * Time: 23:33
 */

namespace app\common\model;

class Rss
{
    public $title;
    public $link;
    public $description;
    public $language = "en-us";
    public $pubDate;
    public $items;
    public $tags = [];

    public function RSS()
    {
        $this->items = array();
        $this->tags  = array();
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function setPubDate($when)
    {
        if(strtotime($when) == false)
            $this->pubDate = date("D, d M Y H:i:s ", $when) . "GMT";
        else
            $this->pubDate = date("D, d M Y H:i:s ", strtotime($when)) . "GMT";
    }

    public function getPubDate()
    {
        if(empty($this->pubDate))
            return date("D, d M Y H:i:s ") . "GMT";
        else
            return $this->pubDate;
    }

    public function addTag($tag, $value)
    {
        $this->tags[$tag] = $value;
    }

    public function out()
    {
        $out  = $this->header();
        $out .= "<channel>\n";
        $out .= "<title>" . $this->title . "</title>\n";
        $out .= "<link>" . $this->link . "</link>\n";
        $out .= "<description>" . $this->description . "</description>\n";
        $out .= "<language>" . $this->language . "</language>\n";
        $out .= "<pubDate>" . $this->getPubDate() . "</pubDate>\n";

        foreach($this->tags as $key => $val) $out .= "<$key>$val</$key>\n";
        foreach($this->items as $item) $out .= $item->out();

        $out .= "</channel>\n";

        $out .= $this->footer();

        $out = str_replace("&", "&amp;", $out);

        return $out;
    }

    public function serve($contentType = "application/xml")
    {
        $xml = $this->out();
        header("Content-type: $contentType");
        echo $xml;
    }

    public function header()
    {
        $out  = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
        $out .= '<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/">' . "\n";
        return $out;
    }

    public function footer()
    {
        return '</rss>';
    }
}