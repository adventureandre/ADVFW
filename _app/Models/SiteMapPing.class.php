<?php
/**
 * Created by PhpStorm.
 * User: ADVENTURE
 * Date: 17/12/17
 * Time: 22:20
 */

class SiteMapPing{

    private $SitemapPing;

    function __construct()
    {
        if (!file_exists('sitemap.xml.gz')):
            $gzip = gzopen('sitemap.xml.gz', 'w9');
            $gmap = file_get_contents('sitemap.xml');
            gzwrite($gzip, $gmap);
            gzclose($gzip);
            $this->ExeSitemapPing();
        endif;
    }



    private function ExeSitemapPing() {
        $this->SitemapPing = array();
        $this->SitemapPing['g'] = 'https://www.google.com/webmasters/tools/ping?sitemap=' . HOME . '/sitemap.xml';
        $this->SitemapPing['b'] = 'https://www.bing.com/webmaster/ping.aspx?siteMap=' . HOME . '/sitemap.xml';

        foreach ($this->SitemapPing as $sitemapUrl):
            $ch = curl_init($sitemapUrl);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_exec($ch);
            curl_close($ch);
        endforeach;
    }

}