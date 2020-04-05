<?php
/**
 * SiteMap by Myself
 * 
 * @package     SitemapSelf
 * @author      凌三
 * @@version    0.1
 * @link        https://www.igetyou.cn/
 */
class SitemapSelf_Plugin  implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Contents_Post_Edit')->write = array('SitemapSelf_Plugin', 'generater');
        Helper::addRoute('sitemap','/sitemap','SitemapSelf_Action','action');
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $dir = new Typecho_Widget_Helper_Form_Element_Text('dir', null, '/', _t('路径'), _t('Sitemap文件存放的路径，请确保此目录可写！'));
        $form->addInput($dir);

    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}


    public static function generater($content) {

		$options = Typecho_Widget::widget('Widget_Options');
        $config = $options->plugin('SitemapSelf');

        require_once 'Sitemap.php';
        $dir = __TYPECHO_ROOT_DIR__ . DIRECTORY_SEPARATOR . $config->dir;

        if(!is_writable($dir))
        {
            @chmod($dir, 0777);
            if(!is_writable($dir))
            {   throw new exception('指定的目录不可写');    }
        }

        $url = $options->siteUrl . '/' . ltrim($config->dir, '/');
        $xsl = Typecho_Common::url('usr/plugins/SitemapSelf/sitemap.xsl', $options->siteUrl);
        $sm = new Silk_Sitemap($dir, $url, true, 1000, $xsl);


        $archives = Typecho_Widget::widget("Widget_Contents_Post_Recent","pageSize=1000");
        $bHasNew = false;
        if($archives->have()){
            while($row = $archives->next()){
                $sm->add($archives->permalink, "always", 10, $archives->modified);
                $bHasNew = true;
            }

        }
        if($bHasNew) $sm->save();

        return $content;
    }

}


