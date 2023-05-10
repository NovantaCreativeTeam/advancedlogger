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
        $this->version = '1.0.0';
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
                $this->registerHook('actionObjectUpdateAfter');
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
    }

    public function hookActionObjectUpdateAfter($params)
    {
        // Nothing to do
    }
}