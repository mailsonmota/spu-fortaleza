<?php
class ErrorController extends BaseController
{
    public function errorAction()
    {
        $this->_helper->layout->enableLayout();
        
        $errors = $this->_getParam('error_handler');
        
        switch ($errors->type) { 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error 
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }

    public function unauthorizedAction()
    {
        $this->_helper->layout->enableLayout();
        $this->renderScript('error/unauthorized.phtml');
    }

    public function norouteAction()
    {
        
    }
}