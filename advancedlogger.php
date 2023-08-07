<?php 


if (!defined('_PS_VERSION_')) {
    exit;
}

class AdvancedLogger extends Module
{
    public function __construct()
    {
        $this->name = 'advancedlogger';
        $this->tab = 'administration';
        $this->version = '1.0.1';
        $this->author = 'Novanta';
        $this->displayName = ('Advanced Logger');
        $this->description = ('This module install a logger to track what appends under the hood');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();
    } 

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function install() 
    {
        return 
            parent::install() && 
                $this->registerHook('actionObjectDeleteBefore') &&
                $this->registerHook('actionObjectDeleteAfter') &&
                $this->registerHook('actionObjectUpdateBefore') &&
                $this->registerHook('actionObjectUpdateAfter') &&
                $this->registerHook('actionObjectAddBefore') &&
                $this->registerHook('actionObjectAddAfter');
    }

    public function uninstall() 
    {
        return parent::uninstall();
    }

    public function hookActionObjectDeleteBefore($params) 
    {
        /** @var ObjectModel */
        $object = $params['object'];
        if($object && get_class($object) == 'Combination') 
        {
            PrestaShopLogger::addLog(sprintf('Combination deleted for Product %s', $object->id_product) , 2, null, get_class($object), $object->id, false, Context::getContext()->employee->id);
        }
        else if($object && get_class($object) == 'SpecificPrice') 
        {
            ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
            $stack = ob_get_clean();
            PrestaShopLogger::addLog(sprintf('SpecificPrice deleted: StackTrace: %s', $stack), 2, null, get_class($object), $object->id, false, Context::getContext()->employee->id);
        }
    }

    public function hookActionObjectDeleteAfter($params) 
    {
        // Nothing to do
    }

    public function hookActionObjectUpdateBefore($params) 
    {
        $object = $params['object'];
        if($object && get_class($object) == 'Combination') 
        {
            PrestaShopLogger::addLog(sprintf('Combination updated for Product %s', $object->id_product) , 1, null, get_class($object), $object->id, false, Context::getContext()->employee->id);
        } 
        else if($object && get_class($object) == 'SpecificPrice') 
        {
            ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
            $stack = ob_get_clean();
            PrestaShopLogger::addLog(sprintf('SpecificPrice updated: StackTrace: %s', $stack), 2, null, get_class($object), $object->id, false, Context::getContext()->employee->id);
        }
    }

    public function hookActionObjectUpdateAfter($params)
    {
        // Nothing to do
    }

    public function hookActionObjectAddBefore($params) 
    {
        $object = $params['object'];
        if($object && get_class($object) == 'SpecificPrice') 
        {
            ob_start();
            debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 20);
            $stack = ob_get_clean();
            PrestaShopLogger::addLog(sprintf('SpecificPrice added: StackTrace: %s', $stack), 2, null, get_class($object), $object->id, false, Context::getContext()->employee->id);
        }
    }

    public function hookActionObjectAddAfter($params) 
    {
        // Nothing to do
    }
}