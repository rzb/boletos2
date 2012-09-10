<?php

abstract class WP_Plugin_Base {

    public function __construct() 
	{
        //Adicionando alguns actions normalmente usadas para permitir que eu só crie o método nas classes filhas
        $this->addAction('init', 'init');
        $this->addAction('widgets_init', 'registerWidgets');
        $this->addAction('admin_menu', 'adminMenu');
        $this->addAction('admin_init', 'adminInit');
    }
    
    /*
     * Adiciona um hook e mapeia para algum método da classe
     */

    private function addHook($hook, $method, $hookType = 'action') {
        if (!isset($hook) && !isset($method) || !method_exists($this, $method))
            return false;

        switch ($hookType) {
            case 'action' :
                add_action($hook, array($this, $method));
                break;
            case 'filter' :
                add_filter($hook, array($this, $method));
                break;
			case 'shortcode' :
                add_shortcode($hook, array($this, $method));
                break;
        }
    }

    protected function addAction($hook, $method) {
        $this->addHook($hook, $method, 'action');
    }

    protected function addFilter($hook, $method) {
        $this->addHook($hook, $method, 'filter');
    }

	protected function addShortcode($hook, $method) {
        $this->addHook($hook, $method, 'shortcode');
    }
}

?>
