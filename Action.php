<?php
class SitemapSelf_Action implements Widget_Interface_Do {

    public function execute() {
        //Do
    }

    public function action(){
        $options = Typecho_Widget::widget('Widget_Options');
        $config = $options->plugin('SitemapSelf');

        require_once 'Sitemap.php';
        $dir = __TYPECHO_ROOT_DIR__ . DIRECTORY_SEPARATOR . $config->dir;

        $sitemap_file = $dir.'/sitemap.xml';
        $sitemap_file_1 = $dir.'/sitemap_1.xml';

        if(file_exists($sitemap_file_1)){
            header("Content-Type:application/xml");
            echo file_get_contents($sitemap_file_1);
        }else if(file_exists($sitemap_file)) {
            header("Content-Type:application/xml");
            echo file_get_contents($sitemap_file);
        }else{
            die("文件不存在");
        }
    }
}