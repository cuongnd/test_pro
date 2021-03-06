<?php
class templateSprflatHelper
{
    public function compileLess($input,$output)
    {

        $cssTemplate=basename($output);

        if(strtolower($cssTemplate)=='bootstrap.css')
        {
            //return;
        }
        $app          = JFactory::getApplication();
        if (!defined('FOF_INCLUDED'))
        {
            require_once JPATH_ROOT . '/libraries/f0f/include.php';
        }
        require_once JPATH_ROOT.'/libraries/f0f/less/less.php';
        $less = new F0FLess;
        $less->setFormatter(new F0FLessFormatterJoomla);

        try
        {
            $less->compileFile($input, $output);

            return true;
        }
        catch (Exception $e)
        {
            $app->enqueueMessage($e->getMessage(), 'error');
        }

    }

}
?>