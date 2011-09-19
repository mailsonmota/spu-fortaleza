<?php
/**
 * Verifica se o controlador/action existe
 * @package SPU
 */
class Plugin_Error extends Zend_Controller_Plugin_Abstract
{
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $dispatcher = Zend_Controller_Front::getInstance()->getDispatcher();

        $controllerName = $request->getControllerName();
        if (empty($controllerName)) {
            $controllerName = $dispatcher->getDefaultController();
        }
        $className = $dispatcher->formatControllerName($controllerName);
        if ($className) {
            try {
                // if this fails, an exception will be thrown and caught below, indicating that the class
                // can't be loaded.
                @Zend_Loader::loadClass($className, $dispatcher->getControllerDirectory());
                $actionName = $request->getActionName();
                if (empty($actionName)) {
                    $actionName = $dispatcher->getDefaultAction();
                }
                $methodName = $dispatcher->formatActionName($actionName);

                $class = new ReflectionClass($className);
                if ($class->hasMethod($methodName)) {
                    // all is well - exit now
                    return;
                }
            }
            catch (Zend_Exception $e) {
                // Couldn't load the class. No need to act yet, just catch the exception and fall out of the if
            }
        }

        // we only arrive here if can't find controller or action
        $request->setControllerName('error');
        $request->setActionName('noroute');
        $request->setDispatched(false);
    }
}