<?php

class WP_Plugin_Setup extends WP_Plugin_Base {

    public function __construct()
	{
        parent::__construct();
    }

    /*
      Registra Widget's
      @action widgets_init
     */

    public function registerWidgets()
	{
    }

    /*
      Executa algumas funções na @action init
     */

    public function init()
	{
        $this->addRewriteRules();
		$this->registerPostTypes();
    }

    private function registerPostTypes() 
	{
        $eventos = new Servicos();
    }
    
	private function addRewriteRules()
	{
        add_rewrite_rule( 'servicos/page/?([0-9]{1,})', 'index.php?servicos_page=servicos&paged=$matches[1]', 'top' );
        add_rewrite_rule( 'servicos', 'index.php?trajettoria_page=servicos&paged=1', 'top' );
		add_rewrite_rule( 'ver-boleto', 'index.php?trajettoria_page=get_boleto', 'top' );
    }
	
    public function adminInit() {
        if (class_exists('WP_Plugin_Options'))
            new WP_Plugin_Options();
    }

    public function adminMenu() {
        add_menu_page('Configurações do Sistema de Boletos', 'Boletos', 'add_users', 'traj_boletos_config', array($this, 'PluginOptionsPageLayout'), plugins_url('/trajettoria-boletos/img/icon_boleto_16x16.png'));
    }

    public function PluginOptionsPageLayout() {
        ?>
        <form action="options.php" method="post">    
            <?php
            settings_fields('wp_plugin_page_options');
            do_settings_sections('wp_plugin_page_options');
            ?>
            <p class="submit">
                <input type="submit" value="Salvar Alterações" class="button-primary" id="submit" name="submit">
            </p>
        </form>
        <?php
    }

}
?>
