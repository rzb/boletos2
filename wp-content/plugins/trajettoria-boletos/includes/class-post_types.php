<?php

/*
 * Classe que representa um custom post type
 * Deve ser chamada na @action init
 */

class Servicos extends WP_Plugin_Base {

    private $_name;
    private $_id;
    private $_singular_name;
    private $_taxonomy_id;
    private $_taxonomy_name;
    private $_taxonomy_singular_name;

    public function __construct()
	{
        $this->_name = 'Serviços';
        $this->_singular_name = 'serviço';
        $this->_id = 'servico';
        $this->addAction("publish_{$this->_id}", 'saveAction');
        $this->registerPostType();
    }

    /*
     * Registra o Custom Post
     */

    public function registerPostType() {
        register_post_type($this->_id, array(
            'labels' => array(
                'name' => $this->_name,
                'singular_name' => $this->_singular_name,
                'add_new' => "Novo {$this->_singular_name}",
                'all_items' => "Todos os {$this->_name}",
                'add_new_item' => "Novo {$this->_singular_name}",
                'edit_item' => "Editar {$this->_singular_name}",
                'view_item' => "Visualizar {$this->_singular_name}",
                'search_items' => "Procurar {$this->_name}",
                'menu_name' => $this->_name
            ),
            'public' => true,
            'hierarchical' => false,
            'supports' => array('title', 'editor'),
            'register_meta_box_cb' => array($this, 'doMetaboxes')
        ));
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
    }

    /*
     * Configura Meta Boxes
     */

    public function doMetaBoxes() {
        add_meta_box('boleto-metabox', 'Configurações do boleto', array($this, 'configuracoesBoletoMetabox'));
    }

    public function configuracoesBoletoMetabox() {
        global $post;
        $usar = get_post_meta($post->ID, 'boleto-usar', true);
        $valor = get_post_meta($post->ID, 'boleto-valor', true);
		$dias_vencimento = get_post_meta($post->ID, 'boleto-dias-vencimento', true);
		$taxa = get_post_meta($post->ID, 'boleto-taxa', true);
		$label_descricao = get_post_meta($post->ID, 'boleto-label-descricao', true);
		$permitir_upload = get_post_meta($post->ID, 'boleto-permitir-upload', true);
		
		$checked_usar = $usar == 'on' ? 'checked' : '';
		$checked_permitir_upload = $permitir_upload == 'on' ? 'checked' : '';
		
        ?>
			<script type="text/javascript">
			function boletosUsar()
			{

				if(!jQuery('input#boleto-usar').attr('checked'))
				{
					jQuery('div#boletos-fields-holder').hide();
				}
				else
				{
					jQuery('div#boletos-fields-holder').show();
				}
			}
			
			jQuery(document).ready(function(){
				boletosUsar();
			});
			
			</script>
		
			<div id="usar-boleto-holder" style="padding: 7px; margin-top: 10px; text-align: center; border: 1px solid #ccc; background: #eee; font-size: 14px;"><p><strong>Usar boleto?&nbsp;&nbsp;</strong> <input type="checkbox" name="boleto-usar" id="boleto-usar" onclick="boletosUsar();" <?php echo $checked_usar; ?> /> <span style="font-size: 12px;">&nbsp;&nbsp;&nbsp;&nbsp;Marque o campo ao lado para ativar a opção de emitir boleto para este serviço</span></p></div>
			
			
			<div id="boletos-fields-holder">
			
			<p><em>(*) = Deixe em branco os campos marcados com * para usar as configurações globais.</em></p>
			<p><em>Nos valores, digite números usando a vírgula como separador decimal, com 2 casas decimais. (Exemplo: 4,25)</em></p>
			
			<div style="width: 25%; float: left; font-size: 14px;">
			<p><strong>Valor (R$)</strong><br/><input type="text" name="boleto-valor" id="boleto-valor" value="<?php echo esc_attr($valor) ?>" size="15" /></p>
			</div>
			
			<div style="width: 25%; float: left; font-size: 14px;">
			<p><strong>* Taxa por boleto (R$)</strong><br/><input type="text" name="boleto-taxa" id="boleto-taxa" value="<?php echo esc_attr($taxa) ?>" size="15" /></p>
			</div>
			
			<div style="width: 25%; float: left; font-size: 14px;">
			<p><strong>* Dias para o vencimento</strong><br/><input type="text" name="boleto-dias-vencimento" id="boleto-dias-vencimento" value="<?php echo esc_attr($dias_vencimento) ?>" size="15" /></p>
			</div>
			
			<div style="width: 25%; float: left; font-size: 14px;">
			<p><strong>* Permitir upload de arquivos?</strong><br/><input type="checkbox" name="boleto-permitir-upload" id="boleto-permitir-upload" <?php echo $checked_permitir_upload; ?>/></p>
			</div>

			<div style="clear: both;"></div>
			
			<div style="font-size: 14px;">
			<p>* Label para o campo preenchido pelos clientes:<br/>
			<textarea name="boleto-label-descricao" id="boleto-label-descricao" cols="130" rows="5"><?php echo esc_attr($label_descricao) ?></textarea>
			</p>
			</div>
			
			&copy; Trajettoria TI Ltda.
			</div>
        <?php
    }

    /*
     * @action save do custom post
     */

    public function saveAction($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return $post_id;

        if (!current_user_can('edit_post', $post_id))
            return;
	
		$usar = $_POST["boleto-usar"];
        $valor = trim($_POST["boleto-valor"]);
		$dias_vencimento = trim($_POST["boleto-dias-vencimento"]);
		$taxa = trim($_POST["boleto-taxa"]);
		$label_descricao = $_POST["boleto-label-descricao"];
		$permitir_upload = $_POST["boleto-permitir-upload"];
       
	   
	   if(!preg_match("/^[0-9]+(?:\,[0-9]{2})?$/im", $valor))
		{
			$valor = "ERRO";
		}
		
		if(!preg_match("/^[0-9]+(?:\,[0-9]{2})?$/im", $taxa))
		{
			$taxa = "ERRO";
		}
		
		if(!preg_match("/^[0-9]+?$/im", $dias_vencimento))
		{
			$dias_vencimento = "ERRO";
		}
		
	   
        if (is_null($usar))
            delete_post_meta($post_id, 'boleto-usar');
        else
            update_post_meta($post_id, 'boleto-usar', $usar);
			
		if (is_null($valor))
            delete_post_meta($post_id, 'boleto-valor');
        else
            update_post_meta($post_id, 'boleto-valor', $valor);
			
		if (is_null($dias_vencimento))
            delete_post_meta($post_id, 'boleto-dias-vencimento');
        else
            update_post_meta($post_id, 'boleto-dias-vencimento', $dias_vencimento);
			
		if (is_null($taxa))
            delete_post_meta($post_id, 'boleto-taxa');
        else
            update_post_meta($post_id, 'boleto-taxa', $taxa);
			
		if (is_null($label_descricao))
            delete_post_meta($post_id, 'boleto-label-descricao');
        else
            update_post_meta($post_id, 'boleto-label-descricao', $label_descricao);

		if (is_null($permitir_upload))
            delete_post_meta($post_id, 'boleto-permitir-upload');
        else
            update_post_meta($post_id, 'boleto-permitir-upload', $permitir_upload);	
		
    }

}
?>
