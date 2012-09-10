<?php
class WP_Plugin_Options extends WP_Plugin_Base {

    const ID = 'WP_Plugin_Options_ID';
    const PREFIX = 'WP_Plugin_Options';

    public function __construct() {
        add_settings_section(
                self::ID, 'SISTEMA DE BOLETOS // Configurações globais', array($this, 'layout'), 'wp_plugin_page_options'
        );
        add_settings_field(
                self::PREFIX . '_banco', 'Banco', array($this, 'bancoRenderer'), 'wp_plugin_page_options', self::ID
        );
        add_settings_field(
                self::PREFIX . '_agencia', 'Agência (sem dígito verificador)', array($this, 'agenciaRenderer'), 'wp_plugin_page_options', self::ID
        );
        add_settings_field(
                self::PREFIX . '_dv_agencia', 'Dígito verificador da agência', array($this, 'dv_agenciaRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_cc', 'Conta (sem dígito verificador)', array($this, 'ccRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_dv_cc', 'Dígito verificador da conta', array($this, 'dv_ccRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_carteira', 'Carteira de boleto', array($this, 'carteiraRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_taxa', 'Taxa por boleto', array($this, 'taxaRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_cedente_nome', 'Razão social do cedente', array($this, 'cedente_nomeRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_cedente_cnpj', 'CNPJ do cedente', array($this, 'cedente_cnpjRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_cedente_endereco1', 'Endereço do cedente (linha 1)', array($this, 'cedente_endereco1Renderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_cedente_endereco2', 'Endereço do cedente (linha 2)', array($this, 'cedente_endereco2Renderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_cedente_logotipo', 'Logotipo do cedente (endereço)', array($this, 'cedente_logotipoRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_dias_vencimento', 'Dias para o vencimento', array($this, 'dias_vencimentoRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_formatos_arquivo', 'Formatos de arquivo aceitos para upload (separados por vírgula)', array($this, 'formatos_arquivoRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_label_descricao', 'Label para o campo preenchido pelos clientes', array($this, 'label_descricaoRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_email', 'E-mail administrativo', array($this, 'emailRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_email_host', 'Host', array($this, 'email_hostRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_email_username', 'Username', array($this, 'email_usernameRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_email_senha', 'Senha', array($this, 'email_senhaRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_email_porta', 'Porta', array($this, 'email_portaRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_email_secure', 'Segurança', array($this, 'email_secureRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_email_auth', 'Autenticação', array($this, 'email_authRenderer'), 'wp_plugin_page_options', self::ID
        );
		add_settings_field(
                self::PREFIX . '_email_from_alias', 'Nome de exibição "De:"', array($this, 'email_from_aliasRenderer'), 'wp_plugin_page_options', self::ID
        );
		
		
        register_setting('wp_plugin_page_options', self::PREFIX . '_banco');
		register_setting('wp_plugin_page_options', self::PREFIX . '_agencia');
		register_setting('wp_plugin_page_options', self::PREFIX . '_dv_agencia');
		register_setting('wp_plugin_page_options', self::PREFIX . '_cc');
		register_setting('wp_plugin_page_options', self::PREFIX . '_dv_cc');
		register_setting('wp_plugin_page_options', self::PREFIX . '_carteira');
		register_setting('wp_plugin_page_options', self::PREFIX . '_taxa');
		register_setting('wp_plugin_page_options', self::PREFIX . '_cedente_nome');
		register_setting('wp_plugin_page_options', self::PREFIX . '_cedente_cnpj');
		register_setting('wp_plugin_page_options', self::PREFIX . '_cedente_endereco1');
		register_setting('wp_plugin_page_options', self::PREFIX . '_cedente_endereco2');
		register_setting('wp_plugin_page_options', self::PREFIX . '_cedente_logotipo');
		register_setting('wp_plugin_page_options', self::PREFIX . '_dias_vencimento');
		register_setting('wp_plugin_page_options', self::PREFIX . '_formatos_arquivo');
		register_setting('wp_plugin_page_options', self::PREFIX . '_label_descricao');
		register_setting('wp_plugin_page_options', self::PREFIX . '_email');
		register_setting('wp_plugin_page_options', self::PREFIX . '_email_host');
		register_setting('wp_plugin_page_options', self::PREFIX . '_email_username');
		register_setting('wp_plugin_page_options', self::PREFIX . '_email_senha');
		register_setting('wp_plugin_page_options', self::PREFIX . '_email_porta');
		register_setting('wp_plugin_page_options', self::PREFIX . '_email_secure');
		register_setting('wp_plugin_page_options', self::PREFIX . '_email_auth');
		register_setting('wp_plugin_page_options', self::PREFIX . '_email_from_alias');

    }
	
	public function  bancoRenderer($args) {
		$html = "<select name='" . self::PREFIX . "_banco' id='" . self::PREFIX . "_banco'>";
		$html .= "<option value='033'>Santander</option>";
		$html .= "</select>";
		
        echo $html;
    }  
	
    public function  agenciaRenderer($args) {
        $value = get_option(self::PREFIX . '_agencia');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_agencia' id='" . self::PREFIX . "_agencia' value='{$value}' />";
        echo $html;
    }    
	
	public function  dv_agenciaRenderer($args) {
        $value = get_option(self::PREFIX . '_dv_agencia');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_dv_agencia' id='" . self::PREFIX . "_dv_agencia' value='{$value}' />";
        echo $html;
    }   
	
	public function  ccRenderer($args) {
        $value = get_option(self::PREFIX . '_cc');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_cc' id='" . self::PREFIX . "_cc' value='{$value}' />";
        echo $html;
    }   
	
	public function  dv_ccRenderer($args) {
        $value = get_option(self::PREFIX . '_dv_cc');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_dv_cc' id='" . self::PREFIX . "_dv_cc' value='{$value}' />";
        echo $html;
    }   
	
	public function  carteiraRenderer($args) {
        $value = get_option(self::PREFIX . '_carteira');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_carteira' id='" . self::PREFIX . "_carteira' value='{$value}' />";
        echo $html;
    }   
   
   public function  taxaRenderer($args) {
        $value = get_option(self::PREFIX . '_taxa');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_taxa' id='" . self::PREFIX . "_taxa' value='{$value}' />";
        echo $html;
    }   
	
	public function  cedente_nomeRenderer($args) {
        $value = get_option(self::PREFIX . '_cedente_nome');
		
		$html = "<input type='text' size='50' name='" . self::PREFIX . "_cedente_nome' id='" . self::PREFIX . "_cedente_nome' value='{$value}' />";
        echo $html;
    }   
	
	public function  cedente_cnpjRenderer($args) {
        $value = get_option(self::PREFIX . '_cedente_cnpj');
		
		$html = "<input type='text' size='20' name='" . self::PREFIX . "_cedente_cnpj' id='" . self::PREFIX . "_cedente_cnpj' value='{$value}' />";
        echo $html;
    }   
	
	public function  cedente_endereco1Renderer($args) {
        $value = get_option(self::PREFIX . '_cedente_endereco1');
		
		$html = "<input type='text' size='50' name='" . self::PREFIX . "_cedente_endereco1' id='" . self::PREFIX . "_cedente_endereco1' value='{$value}' />";
        echo $html;
    }   

	public function  cedente_endereco2Renderer($args) {
        $value = get_option(self::PREFIX . '_cedente_endereco2');
		
		$html = "<input type='text' size='50' name='" . self::PREFIX . "_cedente_endereco2' id='" . self::PREFIX . "_cedente_endereco2' value='{$value}' />";
        echo $html;
    }   
	
	public function  cedente_logotipoRenderer($args) {
        $value = get_option(self::PREFIX . '_cedente_logotipo');
		
		$html = "<input type='text' size='50' name='" . self::PREFIX . "_cedente_logotipo' id='" . self::PREFIX . "_cedente_logotipo' value='{$value}' />";
        echo $html;
    }   
	
	public function  dias_vencimentoRenderer($args) {
        $value = get_option(self::PREFIX . '_dias_vencimento');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_dias_vencimento' id='" . self::PREFIX . "_dias_vencimento' value='{$value}' />";
        echo $html;
    }   
	
	public function  formatos_arquivoRenderer($args) {
        $value = get_option(self::PREFIX . '_formatos_arquivo');
		
		$html = "<input type='text' size='20' name='" . self::PREFIX . "_formatos_arquivo' id='" . self::PREFIX . "_formatos_arquivo' value='{$value}' />";
        echo $html;
    }   
	
	public function  label_descricaoRenderer($args) {
        $value = get_option(self::PREFIX . '_label_descricao');
		
		$html = "<textarea cols='70' rows='5' name='" . self::PREFIX . "_label_descricao' id='" . self::PREFIX . "_label_descricao'>{$value}</textarea>";
        echo $html;
    }   
	
	public function  emailRenderer($args) {
        $value = get_option(self::PREFIX . '_email');
		
		$html = "<input type='text' size='30' name='" . self::PREFIX . "_email' id='" . self::PREFIX . "_email' value='{$value}' />";
        echo $html;
    }   
	
	public function  email_hostRenderer($args) {
        $value = get_option(self::PREFIX . '_email_host');
		
		$html = "<input type='text' size='30' name='" . self::PREFIX . "_email_host' id='" . self::PREFIX . "_email_host' value='{$value}' />";
        echo $html;
    }   
	
	public function  email_usernameRenderer($args) {
        $value = get_option(self::PREFIX . '_email_username');
		
		$html = "<input type='text' size='20' name='" . self::PREFIX . "_email_username' id='" . self::PREFIX . "_email_username' value='{$value}' />";
        echo $html;
    }   
	
	public function  email_senhaRenderer($args) {
        $value = get_option(self::PREFIX . '_email_senha');
		
		$html = "<input type='text' size='20' name='" . self::PREFIX . "_email_senha' id='" . self::PREFIX . "_email_senha' value='{$value}' />";
        echo $html;
    }   
	
	public function  email_portaRenderer($args) {
        $value = get_option(self::PREFIX . '_email_porta');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_email_porta' id='" . self::PREFIX . "_email_porta' value='{$value}' />";
        echo $html;
    }   
	
	public function  email_secureRenderer($args) {
        $value = get_option(self::PREFIX . '_email_secure');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_email_secure' id='" . self::PREFIX . "_email_secure' value='{$value}' />";
        echo $html;
    }   
	
	public function  email_authRenderer($args) {
        $value = get_option(self::PREFIX . '_email_auth');
		
		$html = "<input type='text' size='10' name='" . self::PREFIX . "_email_auth' id='" . self::PREFIX . "_email_auth' value='{$value}' />";
        echo $html;
    }   
	
	public function  email_from_aliasRenderer($args) {
        $value = get_option(self::PREFIX . '_email_from_alias');
		
		$html = "<input type='text' size='30' name='" . self::PREFIX . "_email_from_alias' id='" . self::PREFIX . "_email_from_alias' value='{$value}' />";
        echo $html;
    }   
	
    public function layout() {
		echo "<h4>&copy; Trajettoria TI Ltda.</h4>";
	}

}
?>
