<?php

class BlogController extends BaseController
{

    public function indexAction()
    {


        echo '<pre>';
        var_dump($blog);
        echo '</pre>';
        die("---- DIE ----");
    }

    public function ultimaNoticiaAjaxAction()
    {
        $this->ajaxNoRender();

        if ($this->isPostAjax()) {
            try {
                $blog = new Alfresco_Rest_Blog($this->getBaseUrlAlfresco(), $this->getTicket());

                $blog = $blog->getBlogPostsForDays($this->getBlogParams()->site->name);
            } catch (Exception $exc) {
                die();
            }
            
            if ($blog["total"] != 0) {
                $noticia_html = $this->_montarHtmlNoticia($blog["items"][0]);
                
                echo $noticia_html;
            } else
                echo false;
        } else {
            die();
        }
    }
    
    private function _montarHtmlNoticia($noticia = array())
    {
        $html = '';
        
        if (isset($noticia)) {
            $html .= "<h2>{$noticia["title"]}</h2>";
            $html .= "{$noticia["content"]}";
        }
        
        return $html;
    }

}
