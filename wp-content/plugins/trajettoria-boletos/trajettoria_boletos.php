<?php
/*
Plugin Name: Trajettoria Boletos
Plugin URI: http://www.trajettoria.com
Description: Plugin para emissao e gerenciamento de boletos bancarios
Author: Trajettoria
Version: 1.0
Author URI: http://www.trajettoria.com
*/

// includes
require_once('includes/util.php');
require_once('includes/class.phpmailer.php');
require_once('includes/class.smtp.php');
require_once('includes/class-base.php');
require_once('includes/class-options.php');
require_once('includes/class-setup.php');
require_once('includes/class-post_types.php');


class TrajettoriaBoletos extends WP_Plugin_Setup {
	
	// constants
	const STATUS_BOLETO_EM_ABERTO = 0;
	const STATUS_BOLETO_CANCELADO = 1;
	const STATUS_BOLETO_PAGO = 2;
	const STATUS_BOLETO_VENCIDO = 3;

	const STATUS_PEDIDO_NAO_INICIADO = 0;
	const STATUS_PEDIDO_EM_EXECUCAO = 1;
	const STATUS_PEDIDO_FINALIZADO = 2;

	const TRAJ_BOLETOS_TABLE = 'traj_boletos';

	
	###############################################################################################################

	# FUNCTION: __construct
	# DESCRIPTION: construtor
	
    public function __construct()
	{
        parent::__construct(); 
		$this->initialize();
    }
	
	
	###############################################################################################################

	# FUNCTION: install
	# DESCRIPTION: chamada na ativação do plugin do WordPres
	public static function install() 
	{

	}
	
	###############################################################################################################

	# FUNCTION: uninstall
	# DESCRIPTION: chamada na desativação do plugin do WordPres
	public static function uninstall() 
	{
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	
	###############################################################################################################

	# FUNCTION: initialize
	# DESCRIPTION: chamada toda vez que o site é carregado com o plugin ativo. Prepara o ambiente de execução.
	public function initialize()
	{
		if(!is_admin())
		{
			// shortcodes
			$this->addShortcode('trajettoria-pedido', 'pagina_pedido');
			$this->addShortcode('trajettoria-painel-boletos', 'pagina_painel_boletos');
			$this->addShortcode('trajettoria-segunda-via', 'pagina_segunda_via');
			
			// css 
			wp_register_style( 'bootstrap-css', plugins_url( '/css/bootstrap.css', __FILE__ ), FALSE );
			wp_enqueue_style( 'bootstrap-css' );
			wp_register_style( 'boletos-style-css', plugins_url( '/css/style-boletos.css', __FILE__ ), FALSE );
			wp_enqueue_style( 'boletos-style-css' );
			
			// js
			wp_register_script( 'bootstrap-js', plugins_url( '/js/bootstrap.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'bootstrap-js' );
			wp_register_script( 'jquery-form-js', plugins_url( '/js/jquery.form.2.67.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'jquery-form-js' );
			wp_register_script( 'jquery-masked-input-js', plugins_url( '/js/jquery.maskedinput-1.3.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'jquery-masked-input-js' );
			wp_register_script( 'jquery-validate-js', plugins_url( '/js/jquery.validate.js', __FILE__ ), array( 'jquery', 'jquery-form-js' ) );
			wp_enqueue_script( 'jquery-validate-js' );
			wp_register_script( 'common-js', plugins_url('/js/common.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'common-js' );
			// TODO: use tinyMCE for textareas
		
			// query vars
			$this->addFilter('query_vars', 'query_vars');
			
			// template redirect
			$this->addAction('template_redirect', 'template_redirect');
		}
		

	}


	###############################################################################################################

	# FUNCTION: template_redirect
	# DESCRIPTION: redireciona para o template de "Serviços" e "Ver-Boleto"
	public function template_redirect()
	{
        $page = get_query_var('trajettoria_page');
        if( $page === 'servicos' )
		{
            require_once('template-servicos.php');
            exit();
        }
		else if ( $page === 'get_boleto' )
		{
			$key = get_query_var('key');
			$nosso_numero = get_query_var('nosso_numero');
			$cpf = get_query_var('cpf');
			require_once("get_boleto.php?key=$key&nosso_numero=$nosso_numero&cpf=$cpf");
            exit();
		}
    }
	
	###############################################################################################################

	# FUNCTION: pagina_pedido
	# DESCRIPTION: renderiza a página Pedido, shortcode trajettoria-pedido
	public static function pagina_pedido()
	{
		$prod_id = get_query_var('prod_id');

		

		?>
		<div class="alignright">
			<ul class="nav nav-pills">
			  <li class="active"><a>ETAPA 1: Cadastro</a></li>
			  <li><a>ETAPA 2: Revisão</a></li>
			  <li><a>ETAPA 3: Boleto</a></li>
			</ul>
		</div>
		<div class="clear"></div>
		<?php
		
		
		if(!$s = self::_get_servico($prod_id))
		{
			echo "<h3>Serviço não encontrado.</h3>";
		}
		else // serviço encontrado
		{
			echo "<div class='alert alert-info alert-big'>";
			echo "<p><span class='left_label'>Serviço:</span> <strong>" . $s['title'] . "</strong></p>";
			echo "<p><span class='left_label'>Valor:</span> <strong>R$ " . $s['valor'] . "</strong></p>";
			echo "</div>";
			
			echo "<form enctype='multipart/form-data' method='post' action='' name='frmPedido' id='frmPedido' class='form-horizontal'>";
			echo "<input type='hidden' name='hidden_prod_id' id='hidden_prod_id' value='" . $prod_id . "' />";
			echo "<input type='hidden' name='hidden_step' id='hidden_step' value='2' />";
			
			echo "<fieldset>";
			
			?>
	
			<div class="control-group">
			<label class="control-label" for="nome">Nome:</label>
			<div class="controls">
				<input type="text" class="input-xlarge" id="nome" name="nome">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="cpf">CPF:</label>
			<div class="controls">
				<input type="text" class="input-medium" id="cpf" name="cpf">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="instituicao">Instituição:</label>
			<div class="controls">
				<input type="text" class="input-xlarge" id="instituicao" name="instituicao">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="email">E-mail:</label>
			<div class="controls">
				<input type="text" class="input-xlarge" id="email" name="email">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="telefone">Telefone:</label>
			<div class="controls">
				<input type="text" class="input-medium" id="telefone" name="telefone">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="celular">Celular:</label>
			<div class="controls">
				<input type="text" class="input-medium" id="celular" name="celular">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="endereco">Endereço:</label>
			<div class="controls">
				<textarea name="endereco" id="endereco" class="input-xlarge" rows="3" cols="150"></textarea>
				<p class="help-block">Exemplo: Av. Paulista, 2200 - cj. 161</p>
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="cep">CEP:</label>
			<div class="controls">
				<input type="text" class="input-small" id="cep" name="cep">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="cidade">Cidade:</label>
			<div class="controls">
				<input type="text" class="input-medium" id="cidade" name="cidade">
			</div>
			</div>
			
			<div class="control-group">
			<label class="control-label" for="uf">UF:</label>
			<div class="controls">
				<select name="uf" id="uf" class="input-medium">
				<option value>Selecione...</option>
				<option value="ac">Acre</option>
				<option value="al">Alagoas</option>
				<option value="am">Amazonas</option>
				<option value="ap">Amapá</option>
				<option value="ba">Bahia</option>
				<option value="ce">Ceará</option>
				<option value="df">Distrito Federal</option>
				<option value="es">Espírito Santo</option>
				<option value="go">Goiás</option>
				<option value="ma">Maranhão</option>
				<option value="mt">Mato Grosso</option>
				<option value="ms">Mato Grosso do Sul</option>
				<option value="mg">Minas Gerais</option>
				<option value="pa">Pará</option>
				<option value="pb">Paraíba</option>
				<option value="pr">Paraná</option>
				<option value="pe">Pernambuco</option>
				<option value="pi">Piauí</option>
				<option value="rj">Rio de Janeiro</option>
				<option value="rn">Rio Grande do Norte</option>
				<option value="ro">Rondônia</option>
				<option value="rs">Rio Grande do Sul</option>
				<option value="rr">Roraima</option>
				<option value="sc">Santa Catarina</option>
				<option value="se">Sergipe</option>
				<option value="sp">São Paulo</option>
				<option value="to">Tocantins</option>
				</select>
			</div>
			</div>
			
			
			<div class="control-group">
			<label class="control-label" for="descricao"><?php echo $s['label_descricao']; ?>:</label>
			<div class="controls">
				<textarea name="descricao" id="descricao" class="input-xxlarge" rows="5" cols="150"></textarea>
			</div>
			</div>
			
			<?php if ($s['permitir_upload']) : ?>
			
			<div class="control-group">
			<label class="control-label" for="arquivos[]">Envio de arquivos:</label>
			<div class="controls">
				<p class="help-block">Selecione o(s) arquivo(s) necessário(s) para a análise estatística.<br/>
				Você pode selecionar até 5 arquivos.<br/>
				Nós recomendamos agrupá-los num único arquivo compactado.<br/>
				São aceitos os formatos: <strong><?php echo $s['formatos_arquivo']; ?></strong>.</p>
				<div id="arquivos-holder">
					<div class='linha-arquivo'>
						<input class="input-file" id="arquivos[]" name="arquivos[]" type="file"><button class='btn btn-danger remover-arquivo' onclick="return false;"><i class="icon-trash icon-white"></i> remover</button>
					</div>
				</div>
			<button class='btn btn-success' id='novo-arquivo' onclick='return false;'><i class="icon-plus icon-white"></i> adicionar outro arquivo...</button>					
			</div>
			</div>
			
			<script type="text/javascript">
			jQuery(document).ready(function(){
			
				// File upload inputs
				jQuery('#novo-arquivo').click(function(){
					cont_arq = jQuery('.linha-arquivo').length;
					cont_arq++;
					if(cont_arq < 6) {
						jQuery("<div class='linha-arquivo'><input class='input-file' id='arquivos[]' name='arquivos[]' type='file'><button class='btn btn-danger remover-arquivo' onclick='return false;'><i class='icon-trash icon-white'></i> remover</button></div>").appendTo(jQuery('#arquivos-holder'));
						
						jQuery('.remover-arquivo').click(function(){
							jQuery(this).parent('.linha-arquivo').remove();
							cont_arq = jQuery('.linha-arquivo').length;
							if(cont_arq < 6)
							{
								jQuery('#novo-arquivo').show();
							}
						});
						
						if(cont_arq == 5) jQuery('#novo-arquivo').hide();
					}
				});
				jQuery('.remover-arquivo').click(function(){
					jQuery(this).parent('.linha-arquivo').remove();
					cont_arq = jQuery('.linha-arquivo').length;
					if(cont_arq < 6)
					{
						jQuery('#novo-arquivo').show();
					}
				});
				
				// Validate
				jQuery('#frmPedido').validate({
					rules: {
						
					},
					messages: {
					
					}
				});

				// Input masks
				jQuery('#cpf').mask('999.999.999-99');
				jQuery('#telefone').mask('(99) 99999999? ********************');
				jQuery('#celular').mask('(99) 99999999?9');
				jQuery('#cep').mask('99999-999');
				
			});
			
			function redireciona(url)
			{
				window.location.href = url;
			}
			
			</script>
			
			<?php endif; ?>
			
			<?php
			echo "<div class='form-actions'>";
			echo "<button type='submit' name='submit' id='submit' class='btn btn-primary'>Próximo >></button>";
			echo "<a name='cancel' id='cancel' class='small-link' href='" .  get_site_url() . "'>Cancelar</a>";
			echo "</div>";
			echo "</fieldset>";
			echo "</form>";
			
		}
			

	}
	
	###############################################################################################################

	# FUNCTION: pagina_painel_boletos
	# DESCRIPTION: renderiza a página Painel de Boletos, shortcode trajettoria-painel-boletos
	public static function pagina_painel_boletos()
	{
		
		global $wpdb;
		
		// abaixo: exemplos
		// echo self::_helper_boleto_link('JNDHF8Y4GRUFHDJF', '0087998', '35941385854'); 
		// $prod_id = get_query_var('prod_id');
		// echo "<br><br>prod_id = " . $prod_id;
		// echo "<br><br>valor da propriedade 'label': " . self::_get_setting('label_descricao');
		
		if ( current_user_can('manage_options') ) {
			
			$menu = '<div class="alignright">';
			$menu .= '	<ul class="nav nav-pills">';
			$menu .=		'<li '; 
			if( !isset($_GET['modo']) || $_GET['modo'] != 'clientes' ) { 
				$menu .= "class=active"; 
			}
			$menu .= '><a href="?modo=todos">Boletos</a></li>';
			$menu .=		'<li ';
			if( $_GET['modo'] == 'clientes' ) { 
				$menu .= "class=active"; 
			}
			$menu .= '><a href="?modo=clientes">Clientes</a></li>';
			$menu .=	'</ul>';
			$menu .= '</div>';
			
			echo $menu;
			
			/* conta quantos boletos existem sempre que a página é carregada 
			 * 
			 * se não estivermos filtrando por CPF, contamos o total geral de boletos
			 * senão, contamos os boletos do cliente do CPF em questão
			 * */
			if( !get_query_var("cpf") )
				$totalBoletos = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . self::TRAJ_BOLETOS_TABLE ) );
			else
				$totalBoletos = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE cpf=" . get_query_var("cpf") ) );
			
			/* conta quantos clientes existem sempre que a página é carregada 
			 * */
			$totalClientes = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(distinct cpf) FROM " . self::TRAJ_BOLETOS_TABLE ) );
			
			switch ( get_query_var('modo') ) {
				
				case 'clientes':					
					// preparando paginação
					if ( !isset($_GET['order_by']) ) {
						$order = 'cpf';
					} else {
						$order = get_query_var('order_by');
					}
					if ( !isset($_GET['sort']) ) {
						$sort = 'desc';
					} else {
						$sort = get_query_var('sort');
					}
					if ( !isset($_GET['limit']) ) {
						$limit = 20;
					} else {
						$limit = get_query_var('limit');
					}
					if ( !isset($_GET['offset']) || $_GET['offset'] < 0 ) {
						$offset = 0;
					} else {
						$offset = get_query_var('offset');
					}
					$clientes = $wpdb->get_results( "SELECT * FROM " . self::TRAJ_BOLETOS_TABLE . " GROUP BY cpf ORDER BY $order $sort LIMIT $limit OFFSET $offset", OBJECT_K );
					
					?>
					<form method="POST" enctype="multipart/form-data" action="" class="form-inline">
						<table class="table table-striped table-custom-padding" id="clientes-table" >
							<thead>
								<tr class="bol-tpagination">
									<th colspan="6" >
										<div class="row-fluid table-header">
											<div class="span4 center text">Mostrando clientes <?php echo $offset+1; ?> a <?php echo sizeof($clientes) + $offset; ?> de <?php echo $totalClientes; ?></div>
											<div class="span4 center pagination-custom">
												<ul>
													<li><a href="?modo=clientes&offset=0&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; ?>"><button class="btn btn-small btn-primary" type="button"><<</button></a></li>
													<li><a href="?modo=clientes&offset=<?php if ($offset-$limit-1 < 0) echo 0; else echo $offset-$limit; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; ?>"><button class="btn btn-small btn-primary" type="button"><</button></a></li>
													<li><input type="text" class="input-mini" id="ir-para-pagina" value="<?php echo floor($offset / $limit) + 1; ?>" /></li>
													<li><a href="?modo=clientes&offset=<?php if ($offset+$limit+1 > $totalClientes) echo $offset; else echo $offset+$limit; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; ?>"><button class="btn btn-small btn-primary" type="button">></button></a></a></li>
													<li><a href="?modo=clientes&offset=<?php if( $totalClientes % $limit == 0 ) echo $totalClientes - $limit; else echo $totalClientes - $totalClientes % $limit; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; ?>"><button class="btn btn-small btn-primary" type="button">>></button></a></a></li>
												</ul>
											</div>
											<div class="span4 center form-inline">
												<label for="boletos-por-pagina">Clientes por página:</label>
												<select name="limit" class="input-mini" id="boletos-por-pagina">
													<option class="active"><?php echo $limit; // @todo popular dinamicamente essa select com a quantidade real de páginas até no máximo 20 ?></option>
													<option value="1">1</option>
													<option value="5">5</option>
													<option value="10">10</option>
													<option value="15">15</option>
													<option value="20">20</option>
												</select>
											</div>
										</div> 
									</th>
								</tr>
								<tr class="bol-thead">
									<th class="col-head <?php if($order=="cpf" && $sort=="asc") echo "sort-asc"; elseif($order=="cpf" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=clientes&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "cpf"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; ?>">CPF</a>
									</th>
									<th class="col-head col-nome <?php if($order=="nome" && $sort=="asc") echo "sort-asc"; elseif($order=="nome" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=clientes&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "nome"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; ?>">Nome</a>
									</th>
									<th class="col-head <?php if($order=="email" && $sort=="asc") echo "sort-asc"; elseif($order=="email" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=clientes&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "email"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; ?>">E-mail</a>
									</th>
									<th class="col-head col-status-boleto <?php if($order=="status_boleto" && $sort=="asc") echo "sort-asc"; elseif($order=="status_boleto" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=clientes&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "status_boleto"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; ?>">Boleto em aberto?</a>
									</th>
									<th class="col-head col-status-pedido <?php if($order=="status_pedido" && $sort=="asc") echo "sort-asc"; elseif($order=="status_pedido" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=clientes&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "status_pedido"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; ?>">Pedido em aberto?</a>
									</th>
									<th class="col-head col-opcoes">
										<span>Opções</span>
									</th>
								</tr>
							</thead>
							<tbody>
				
								
							<?php foreach ( $clientes as $c ) { ?>
								<tr class='cliente-$c->id'>
									<td class="data cliente-cpf"><?php echo $c->cpf; ?></td>
									<td class="data cliente-nome"><?php echo $c->nome; ?></td>
									<td class="data cliente-email"><?php echo $c->email; ?></td>
									<td class="data cliente-statusbol">
										<?php 
											$totalBolAbertos = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE cpf = " . $c->cpf . " AND status_boleto = " . self::STATUS_BOLETO_EM_ABERTO ) );
											if ( $totalBolAbertos > 0 ) {
												echo "Sim";
											} else {
												echo "Não";
											}
										?>
									</td>
									<td class="data cliente-statuspedido">
										<?php
											$totalPedAbertos = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE cpf=" . $c->cpf . " AND ( status_pedido = " . self::STATUS_PEDIDO_EM_EXECUCAO . " OR status_pedido = " . self::STATUS_PEDIDO_NAO_INICIADO . " )" ) );
											if ( $totalPedAbertos > 0 ) {
												echo "Sim";
											} else {
												echo "Não";
											}
										?>
									</td>
									<td class="data cliente-opcoes">
										<select name="cliente-opcao[<?php echo $c->cpf; ?>]" class="opcao cliente-opcao">
											<option value="selecione">Selecione</option>
											<option value="boletos_<?php echo $c->cpf; ?>">Ver boletos/pedidos</option>
											<option value="dados_<?php echo $c->cpf; ?>">Ver dados pessoais</option>
										</select>
									</td>
								</tr>
							<?php } ?>
				
							</tbody>
						</table>
					</form>
					
					<?php 
					break;
				
				default:
					
					// tratando ação quick-change
					$msg["quick_change"] = "";
					if ( isset( $_POST['submit_quickchange'] ) ) {
						
						if ( preg_match( '/[^0-9]/', $_POST['nosso_numero'] ) == TRUE || $_POST['nosso_numero'] === "" ) {
							$msg["quick_change"] = '<div class="alert alert-error fade in alert-custom-margin"><button type="button" class="close" data-dismiss="alert">×</button>Por favor, preencha corretamente o campo "nosso número". Ele deve conter apenas números.</div>';
						} else {
							$totalBoletos = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE nosso_numero = {$_POST['nosso_numero']}" ) );
							if ($totalBoletos > 0) {
								
								switch ( $_POST['submit_quickchange'] ) {
									case 'Marcar como pago':
										$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_boleto' => self::STATUS_BOLETO_PAGO ), array( 'nosso_numero' => $_POST['nosso_numero'] ) );
										$msg["quick_change"] = '<div class="alert alert-success fade in alert-custom-margin"><button type="button" class="close" data-dismiss="alert">×</button>Boleto <strong>' . $_POST['nosso_numero'] . '</strong> foi marcado como pago!</div>';
										break;
									case 'Marcar como não pago':
										// checa se boleto venceu
										$statusBoleto = $wpdb->get_var( $wpdb->prepare( "SELECT status_boleto FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE nosso_numero = " . $_POST['nosso_numero'] ) );
										if( $statusBoleto != self::STATUS_BOLETO_VENCIDO ) {
											$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_boleto' => self::STATUS_BOLETO_EM_ABERTO ), array( 'nosso_numero' => $_POST['nosso_numero'] ) );
											$msg["quick_change"] = '<div class="alert alert-success fade in alert-custom-margin"><button type="button" class="close" data-dismiss="alert">×</button>Boleto <strong>' . $_POST['nosso_numero'] . '</strong> foi marcado como não-pago!</div>';
										} else {
											$msg["quick_change"] = '<div class="alert alert-error fade in alert-custom-margin"><button type="button" class="close" data-dismiss="alert">×</button>Boleto <strong>' . $_POST['nosso_numero'] . '</strong> passou da data de vencimento.</div>';
										}
										break;
									case 'Cancelar':
										$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_boleto' => self::STATUS_BOLETO_CANCELADO ), array( 'nosso_numero' => $_POST['nosso_numero'] ) );
										$msg["quick_change"] = '<div class="alert alert-success fade in alert-custom-margin"><button type="button" class="close" data-dismiss="alert">×</button>Boleto <strong>' . $_POST['nosso_numero'] . '</strong> foi cancelado!</div>';
										break;
									case 'Excluir':
										$wpdb->delete( self::TRAJ_BOLETOS_TABLE, array( 'nosso_numero' => $_POST['nosso_numero'] ) );
										$msg["quick_change"] = '<div class="alert alert-success fade in alert-custom-margin"><button type="button" class="close" data-dismiss="alert">×</button>Boleto <strong>' . $_POST['nosso_numero'] . '</strong> foi deletado!</div>';
										break;
									default:
										break;
								}
							} else {
								$msg["quick_change"] = '<div class="alert alert-error fade in alert-custom-margin"><button type="button" class="close" data-dismiss="alert">×</button>Boleto <strong>' . $_POST['nosso_numero'] . '</strong> inexistente...</div>';
							}
						}
					}
					
					// tratando ação bol-opcao
					if ( isset( $_POST['bol-single'] ) ) {
						
						foreach ( $_POST['bol-single'] as $bolID => $option ) {
							if( $option != "selecione" ) {
								switch ( $option ) {
									case "pago_$bolID":
										$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_boleto' => self::STATUS_BOLETO_PAGO ), array( 'id' => $bolID ) );
										break;
									case "nao-pago_$bolID":
										$statusBoleto = $wpdb->get_var( $wpdb->prepare( "SELECT status_boleto FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE id = " . $bolID ) );
										if( $statusBoleto != self::STATUS_BOLETO_VENCIDO ) {
											$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_boleto' => self::STATUS_BOLETO_EM_ABERTO ), array( 'id' => $bolID ) );
										}
										break;
									case "cancelar_$bolID":
										$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_boleto' => self::STATUS_BOLETO_CANCELADO ), array( 'id' => $bolID ) );
										break;
									case "nao-iniciado_$bolID":
										$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_pedido' => self::STATUS_PEDIDO_NAO_INICIADO ), array( 'id' => $bolID ) );
										break;
									case "em-execucao_$bolID":
										$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_pedido' => self::STATUS_PEDIDO_EM_EXECUCAO ), array( 'id' => $bolID ) );
										break;
									case "finalizado_$bolID":
										$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_pedido' => self::STATUS_PEDIDO_FINALIZADO ), array( 'id' => $bolID ) );
										break;
									case "excluir_$bolID":
										$wpdb->delete( self::TRAJ_BOLETOS_TABLE, array( 'id' => $bolID ) );
										break;
									case "ver_$bolID": // talvez aqui seja melhor usar uma âncora alterando as query vars
										$key = $wpdb->get_var( "SELECT key_boleto FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE id = " . $bolID );
										$link = self::_helper_boleto_link( $key );
										echo "<meta http-equiv='refresh' content='0;url=$link' />";
										exit;
										break;
									case "segunda-via_$bolID":
										// @todo chamar página boleto pré-poluando os campos 
										break;
									case "enviar_$bolID": 
										// A opção “enviar para cliente” envia um e-mail ao cliente, contendo um link para “ver-boleto” usando a key como query string.$ids = implode(", ", $boletoID);
										$boleto = $wpdb->get_row( "SELECT id, email, nome, key_boleto FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE id = " . $bolID, OBJECT );
										// @todo preparar texto de subject e content 
										$content = self::_helper_boleto_link( $boleto->key_boleto );
										self::_send_mail($boleto->email, "Boleto", $content);
										break;
									case "pedido_$bolID": // talvez aqui seja melhor usar uma âncora alterando as query vars
										break;
									default:
										break;
								}
								break;
							}
						}
					}
					
					// tratando ação bulk-action
					if ( isset( $_POST['boleto'] ) ) {
						
						$boletoID = preg_replace('/[^-0-9]/', '', $_POST['boleto'] );
						if($_POST['bulk-action'] != "selecione") {
							switch ( $_POST['bulk-action'] ) {
								case "pago":
									foreach ( $boletoID as $id ) {
										$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_boleto' => self::STATUS_BOLETO_PAGO ), array( 'id' => $id ) );
									}
									// @todo verificar se alterações no banco obtiveram mesmo sucesso para definir mensagem de resultado... aqui e em todas as $msg
									$msg["bulk_action"] = '<div class="alert fade in"><button type="button" class="close" data-dismiss="alert">×</button>Os boletos selecionados foram marcados como pagos!</div>';
									break;
								case "nao-pago":
									// checa se boleto venceu
									foreach ( $boletoID as $id ) {
										$statusBoleto = $wpdb->get_var( $wpdb->prepare( "SELECT status_boleto FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE id = " . $id ) );
										if( $statusBoleto != self::STATUS_BOLETO_VENCIDO ) {
											$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_boleto' => self::STATUS_BOLETO_EM_ABERTO ), array( 'id' => $id ) );
										}
									}
									break;
								case "cancelar":
									foreach ( $boletoID as $id ) {
										$wpdb->update( self::TRAJ_BOLETOS_TABLE, array( 'status_boleto' => self::STATUS_BOLETO_CANCELADO ), array( 'id' => $id ) );
									}
									break;
								case "excluir":
									foreach ( $boletoID as $id ) {
										$wpdb->delete( self::TRAJ_BOLETOS_TABLE, array( 'id' => $id ) );
									}
									break;
								case "enviar":
									// A opção “enviar para cliente” envia um e-mail ao cliente, contendo um link para “ver-boleto” usando a key como query string.
									$ids = implode(", ", $boletoID);
									$boletos = $wpdb->get_results( "SELECT id, email, nome, key_boleto FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE id IN (" . $ids . ")", OBJECT_K );
									
									foreach ( $boletos as $bol ) {
										// @todo preparar texto de subject e content 
										$content = self::_helper_boleto_link( $bol->key_boleto );
										self::_send_mail($bol->email, "Boleto", $content);
									}
									
									break;
								default:
									break;
							}
						}
					}
					
					// preparando resumo de valores dos boletos
					$somaTotal = $wpdb->get_results( "SELECT status_boleto, sum( valor ) as total FROM " . self::TRAJ_BOLETOS_TABLE . " GROUP BY status_boleto", OBJECT_K );
					if ( array_key_exists(self::STATUS_BOLETO_EM_ABERTO, $somaTotal) )	
						$totalNaoPago = $somaTotal[self::STATUS_BOLETO_EM_ABERTO]->total;
					else
						$totalNaoPago = 0;
					if ( array_key_exists(self::STATUS_BOLETO_PAGO, $somaTotal) )
						$totalPago = $somaTotal[self::STATUS_BOLETO_PAGO]->total;
					else
						$totalPago = 0;
					if ( array_key_exists(self::STATUS_BOLETO_VENCIDO, $somaTotal) )
						$totalVencido = $somaTotal[self::STATUS_BOLETO_VENCIDO]->total;
					else
						$totalVencido = 0;
						
					
					// preparando paginação
					if ( !isset($_GET['order_by']) ) {
						$order = 'data_vencimento';
					} else {
						$order = get_query_var('order_by');
					}
					if ( !isset($_GET['sort']) ) {
						$sort = 'desc';
					} else {
						$sort = get_query_var('sort');
					}
					if ( !isset($_GET['limit']) ) {
						$limit = 20;
					} else {
						$limit = get_query_var('limit');
					}
					if ( !isset($_GET['offset']) || $_GET['offset'] < 0 ) {
						$offset = 0;
					} else {
						$offset = get_query_var('offset');
					}
					if ( !isset($_GET['cpf']) ) {
						$boletos = $wpdb->get_results( "SELECT t1.*, t2.post_title FROM " . self::TRAJ_BOLETOS_TABLE . " AS t1 LEFT JOIN wp_posts AS t2 ON (t1.post_id = t2.ID) order by $order $sort LIMIT $limit OFFSET $offset", OBJECT_K );
					} else {
						$cpf = get_query_var('cpf');
						$boletos = $wpdb->get_results( "SELECT * FROM " . self::TRAJ_BOLETOS_TABLE . " WHERE cpf=$cpf ORDER BY $order $sort LIMIT $limit OFFSET $offset", OBJECT_K );
					}
					
			?>
			
					<div class="clear"></div>
					
					<div class="alert alert-info alert-big center" id="resumo-boletos">
						<div class="row-fluid">
  							<div class="span4">Pago: <span>R$<?php echo number_format( $totalPago, 2, ',', '.' ); ?></span></div>
  							<div class="span4">Não pago: <span>R$<?php echo number_format( $totalNaoPago, 2, ',', '.' ); ?></span></div>
  							<div class="span4">Vencido: <span>R$<?php echo number_format( $totalVencido, 2, ',', '.' ); ?></span></div>
						</div>
					</div>
					
					<div class="alert alert-info alert-big center" id="quick-change">
						<form class="form-inline" method="POST" enctype="multipart/form-data" action="">
							<label for="nosso_numero">Nosso Número:</label>
							<input type="text" name="nosso_numero" class="input-small" id="nosso-numero" />
							<input type="submit" name="submit_quickchange" value="Marcar como pago" class="btn" id="button-marcar-pago" />
							<input type="submit" name="submit_quickchange" value="Marcar como não pago" class="btn" id="button-marcar-naopago" />
							<input type="submit" name="submit_quickchange" value="Cancelar" class="btn" id="button-cancelar" />
							<input type="button" value="Excluir" class="btn" id="button-excluir" />	
							<div class="modal hide fade in" id="excluirBoleto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							  <div class="modal-header">
							    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							    <h3 id="excluir-boleto-ModalLabel">Confirmar exclusão de boleto</h3>
							  </div>
							  <div class="modal-body">
							    <p>Tem certeza que deseja prosseguir? Essa ação não pode ser revertida.</p>
							  </div>
							  <div class="modal-footer">
							    <input type="submit" name="submit_quickchange" value="Excluir" class="btn" />
							    <button class="btn btn-primary close-modal" data-dismiss="modal" aria-hidden="true">Cancelar</button>
							  </div>
							</div>				
						</form>
						<?php echo $msg["quick_change"]; ?>
						<span id="msg-quick-change"></span>
					</div>
					
					<form method="POST" enctype="multipart/form-data" action="" class="form-inline" id="boletos">
						<table class="table table-striped table-custom-padding" id="bol-table" >
							<thead>
								<tr class="bol-tpagination">
									<th colspan="9" >
										<div class="row-fluid table-header">
											<div class="span4 center text">Mostrando boletos <?php echo $offset+1; ?> a <?php echo sizeof($boletos) + $offset; ?> de <?php echo $totalBoletos; ?></div>
											<div class="span4 center pagination-custom">
												<ul>
													<li><a href="?modo=todos&offset=0&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>"><button class="btn btn-small btn-primary" type="button"><<</button></a></li>
													<li><a href="?modo=todos&offset=<?php if ($offset-$limit-1 < 0) echo 0; else echo $offset-$limit; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>"><button class="btn btn-small btn-primary" type="button"><</button></a></li>
													<li><input type="text" class="input-mini" id="ir-para-pagina" value="<?php echo floor($offset / $limit) + 1; ?>" /></li>
													<li><a href="?modo=todos&offset=<?php if ($offset+$limit+1 > $totalBoletos) echo $offset; else echo $offset+$limit; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>"><button class="btn btn-small btn-primary" type="button">></button></a></li>
													<li><a href="?modo=todos&offset=<?php if( $totalBoletos % $limit == 0 ) echo $totalBoletos - $limit; else echo $totalBoletos - $totalBoletos % $limit; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>"><button class="btn btn-small btn-primary" type="button">>></button></a></li>
												</ul>
											</div>
											<div class="span4 center form-inline">
												<label for="boletos-por-pagina">Boletos por página:</label>
												<select name="limit" class="input-mini" id="boletos-por-pagina">
													<option class="active"><?php echo $limit; // @todo popular dinamicamente essa select com a quantidade real de páginas até no máximo 20 ?></option>
													<option value="1">1</option>
													<option value="5">5</option>
													<option value="10">10</option>
													<option value="15">15</option>
													<option value="20">20</option>
												</select>
											</div>
										</div> 
									</th>
								</tr>
								<tr class="bol-thead">
									<th class="col-head"></th>
									<th class="col-head <?php if($order=="nosso_numero" && $sort=="asc") echo "sort-asc"; elseif($order=="nosso_numero" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=todos&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "nosso_numero"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>">Nosso Número</a>
									</th>
									<th class="col-head <?php if($order=="data_criacao" && $sort=="asc") echo "sort-asc"; elseif($order=="data_criacao" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=todos&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "data_criacao"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>">Emissão</a>
									</th>
									<th class="col-head <?php if($order=="data_vencimento" && $sort=="asc") echo "sort-asc"; elseif($order=="data_vencimento" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=todos&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "data_vencimento"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>">Vencimento</a>
									</th>
									<th class="col-head <?php if($order=="nome" && $sort=="asc") echo "sort-asc"; elseif($order=="nome" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=todos&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "nome"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>">Cliente</a>
									</th>
									<th class="col-head <?php if($order=="post_title" && $sort=="asc") echo "sort-asc"; elseif($order=="post_title" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=todos&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "post_title"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>">Serviço</a>
									</th>
									<th class="col-head <?php if($order=="status_boleto" && $sort=="asc") echo "sort-asc"; elseif($order=="status_boleto" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=todos&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "status_boleto"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>">Status Boleto</a>
									</th>
									<th class="col-head <?php if($order=="status_pedido" && $sort=="asc") echo "sort-asc"; elseif($order=="status_pedido" && $sort=="desc") echo "sort-desc"; else echo "sort"; ?>">
										<a href="?modo=todos&offset=<?php echo $offset; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo "status_pedido"; ?>&sort=<?php if ($sort == "desc") echo "asc"; else echo "desc"; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>">Status Pedido</a>
									</th>
									<th class="col-head col-opcoes">
										<span>Opções</span>
									</th>
								</tr>
							</thead>
							<tbody>
				
								
							<?php foreach ( $boletos as $bol ) { ?>
								<tr class='bol-$bol->id'>
									<td class="check bol-check"><input type='checkbox' name='boleto[]' value='<?php echo $bol->id ?>' /></td>
									<td class="data bol-nossonumero"><?php echo $bol->nosso_numero; ?></td>
									<td class="data bol-dtemissao"><?php echo substr_replace( date_to_br( $bol->data_criacao ), "", 10 ); ?></td>			
									<td class="data bol-dtvencimento"><?php echo substr_replace( date_to_br( $bol->data_vencimento ), "", 10 ); ?></td>
									<td class="data bol-cliente"><?php echo $bol->nome; ?></td>
									<td class="data bol-servico"><?php echo $bol->post_title; ?></td>
									<td class="data bol-statusbol">
										<?php 
											switch ( $bol->status_boleto ) {
												case self::STATUS_BOLETO_EM_ABERTO:
													echo "Aberto"; 
													break;
												case self::STATUS_BOLETO_CANCELADO:
													echo "Cancelado";
													break;
												case self::STATUS_BOLETO_PAGO:
													echo "Pago";
													break;
												case self::STATUS_BOLETO_VENCIDO:
													echo "Vencido";
													break;
												default:
													break;
											}
										?>
									</td>
									<td class="data bol-statuspedido">
										<?php
											switch ( $bol->status_pedido ) {
												case self::STATUS_PEDIDO_NAO_INICIADO:
													echo "Aguardando";
													break;
												case self::STATUS_PEDIDO_EM_EXECUCAO:
													echo "Em execução";
													break;
												case self::STATUS_PEDIDO_FINALIZADO:
													echo "Finalizado";
													break;
												default:
													break;
											}
										?>
									</td>
									<td class="data bol-opcoes">
										<select name="bol-single[<?php echo $bol->id; ?>]" class="opcao bol-opcao">
											<option value="selecione">Selecione</option>
											<optgroup label="Mudar status do boleto:">
												<option value="pago_<?php echo $bol->id; ?>">Pago</option>
												<option value="nao-pago_<?php echo $bol->id; ?>">Aberto</option>
												<option value="cancelar_<?php echo $bol->id; ?>">Cancelado</option>
											</optgroup>
											<optgroup label="Mudar status do pedido:">
												<option value="nao-iniciado_<?php echo $bol->id; ?>">Aguardando</option>
												<option value="em-execucao_<?php echo $bol->id; ?>">Em execução</option>
												<option value="finalizado_<?php echo $bol->id; ?>">Finalizado</option>
											</optgroup>
											<option value="ver_<?php echo self::_helper_boleto_link( $bol->key_boleto ); ?>">Ver boleto</option>
											<option value="pedido_<?php echo $bol->id; ?>">Ver pedido</option>
											<option value="enviar_<?php echo $bol->id; ?>">Enviar para cliente</option>
											<option value="segunda-via_<?php echo $bol->id; ?>">Gerar segunda via</option>
											<option value="excluir_<?php echo $bol->id; ?>">Excluir</option>
										</select>
									</td>
								</tr>
							<?php } ?>
				
							</tbody>
						</table>
						
						<div class="form-actions center">
							<label for="bulk-action">Com marcados:</label>
							<select name="bulk-action" id="bulk-action">
								<option value="selecione">Selecione</option>
								<option value="pago">Marcar como pago</option>
								<option value="nao-pago">Marcar como aberto</option>
								<option value="cancelar">Cancelar</option>
								<option value="excluir">Excluir</option>
								<option value="enviar">Enviar para cliente</option>
							</select>
						</div>
						
					</form>
					
			<?php
					
					break;
			}
			
			?>
			
			<div class="modal hide fade in" id="excluir-boleto-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			    <h3 id="excluir-boleto-ModalLabel">Confirmar exclusão de boleto</h3>
			  </div>
			  <div class="modal-body">
			    <p>Tem certeza que deseja prosseguir? Essa ação não pode ser revertida.</p>
			  </div>
			  <div class="modal-footer">
			    <input type="submit" id="excluir-boleto" value="Excluir" class="btn" />
			    <button class="btn btn-primary close-modal" data-dismiss="modal" aria-hidden="true">Cancelar</button>
			  </div>
			</div>
			
			<div class="modal hide fade in" id="generic-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
			
			<script type="text/javascript">

				jQuery(document).ready(function() {
					
					jQuery("#ir-para-pagina").keyup(function(e) {
						if(e.keyCode == 13) {
							var intRegex = /^\d+$/;
							var pagina = jQuery(this).val();
							var offset = <?php echo $limit; ?> * (pagina - 1);
							if (pagina < 1 || pagina > <?php echo ceil( $totalBoletos / $limit ); ?>|| !intRegex.test(pagina) ) {
								offset = <?php echo $offset; ?>
							}
							window.location.href = '<?php echo get_permalink() . "?modo=" . get_query_var('modo'); ?>&offset=' + offset + '&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>';
						}
					});
					
					jQuery("#boletos-por-pagina").change(function() {
						window.location.href = '<?php echo get_permalink() . "?modo=" . get_query_var('modo'); ?>&offset=0&limit=' + jQuery(this).val() + '&order_by=<?php echo $order; ?>&sort=<?php echo $sort; if (get_query_var("cpf")) echo "&cpf=" . get_query_var("cpf"); ?>';
					});

					jQuery(".cliente-opcao").change(function() {
						var option = jQuery(this).val().split("_");
						var cpf = option[1];
						switch (option[0]) {
							case "boletos":
								window.location.href = '<?php echo get_permalink() . "?modo=todos&offset=0"; ?>&limit=<?php echo $limit; ?>&order_by=<?php echo $order; ?>&sort=<?php echo $sort; ?>&cpf=' + cpf;
								break;
							case "dados":
								jQuery.ajax({
									url: "<?php echo plugins_url("popups.php",__FILE__); ?>?popup=cliente&cpf=" + cpf,
								 	dataType: "html"
								}).done(function(data){
									jQuery("#generic-modalLabel").html("Detalhes do cliente");
									jQuery("#generic-modal").html(data);
									jQuery("#generic-modal").modal("show");
								});

								break;
						}
						
					});

					jQuery(".bol-opcao").change(function() {
						var option = jQuery(this).val().split("_");
						var id = option[1];
						switch (option[0]) {
							case "excluir":
								// usar modal para confirmar exclusão do boleto
								jQuery("#excluir-boleto-modal").modal("show");
								break;
							case "ver":
								// chama ver-boleto passando a key
								redireciona(id);
								break;
							case "segunda-via":
								// chama “boleto”, para que seja criado um NOVO boleto 
								// (neste caso, os campos são pré-populados com os dados do cliente, 
								// valor, etc., 
								// MAS aplicando uma nova data de vencimento a partir da data atual de emissão). 
								break;
							case "pedido":
								jQuery.ajax({
									url: "<?php echo plugins_url("popups.php",__FILE__); ?>?popup=pedido&bol-id=" + id,
								 	dataType: "html"
								}).done(function(data){
									jQuery("#generic-modalLabel").html("Detalhes do pedido");
									jQuery("#generic-modal").html(data);
									jQuery("#generic-modal").modal("show");
								});
								
								break;
							default:
								jQuery("#boletos").submit();
								break;
						}
					});

					jQuery("#bulk-action").change(function() {
						var option = jQuery(this).val();
						switch (option) {
							case "excluir":
								jQuery("#excluir-boleto-modal").modal("show");
								break;
							default:
								jQuery("#boletos").submit();
								break;
						}
					});

					jQuery("#button-excluir").click(function() {
						var nossoNumero = jQuery("#nosso-numero").val();
						var intRegex = /^\d+$/;
						if ( !intRegex.test(nossoNumero) ) {
							jQuery("#msg-quick-change").html('<div class="alert alert-error fade in alert-custom-margin"><button type="button" class="close" data-dismiss="alert">×</button>Por favor, preencha corretamente o campo "nosso número". Ele deve conter apenas números.</div>');
						} else {
							jQuery("#excluirBoleto").modal("show");
						}
					});
					
					jQuery("#excluir-boleto").click(function() {
						jQuery("#boletos").submit();
					});


					jQuery(".close-modal").click(function(){
						jQuery(".opcao").val("Selecione");
						jQuery("#bulk-action").val("Selecione");
					});


					function redireciona(url)
					{
						window.location.href = url;
					}
					
				});
				
			</script>
			
			<?php
			
		}
	}
	
	###############################################################################################################

	# FUNCTION: pagina_segunda_via
	# DESCRIPTION: renderiza a página Segunda Via de Boleto, shortcode trajettoria-segunda-via
	public static function pagina_segunda_via()
	{
		
		global $wpdb;
		?>
		
		<div class="alignright">
			<ul class="nav nav-pills">
				<li class="active" id="etapa1"><a>Etapa 1</a></li>
				<li id="etapa2"><a>Etapa 2</a></li>
			</ul>
		</div>
		<div id="segunda-via-content">
			<form method="GET" enctype="multipart/form-data" action="" class="form-horizontal" id="segunda-via">
			 <div id="input-error"></div>
			  <legend>Digite seus dados abaixo:</legend>
			  <div class="control-group">
			    <label class="control-label" for="cpf">CPF</label>
			    <div class="controls">
			      <input type="text" name="cpf" id="cpf" placeholder="cpf">
			    </div>
			  </div>
			  <div class="control-group">
			    <label class="control-label" for="email">E-mail</label>
			    <div class="controls">
			      <input type="text" name="email" id="email" placeholder="e-mail">
			    </div>
			  </div>
			  <input type="hidden" name="segunda-via" value="submit" />
			  <div class="form-actions">
			      <button type="submit" class="btn" id="segunda-via-submit">OK</button>
			  </div>
			</form>
		</div>
		
		<script type="text/javascript">

			jQuery(document).ready(function(e) {

				jQuery("#segunda-via").submit(function(e) {
					var cpf = jQuery("#cpf").val();
					var email = jQuery("#email").val();
					jQuery.ajax({
						url: "<?php echo plugins_url("segunda-via.php",__FILE__); ?>?cpf=" + cpf + "&email=" + email,
					 	dataType: "html"
					}).done(function(data) {
						if(data=="false") {
							// não há boletos... mostra alert(bootstrap) de erro dentro do formulário
							jQuery('#input-error').html('<div class="alert alert-error fade in alert-custom-margin"><button type="button" class="close" data-dismiss="alert">×</button>Não existe boleto em aberto para o CPF e e-mail digitados.</div>');
						} else {
							// mostra tabela com boletos encontrados substituindo o formulário
							jQuery('#etapa1').removeClass('active');
							jQuery('#etapa2').addClass('active');
							jQuery('#segunda-via-content').html(data);
						}
					});
					e.preventDefault();
				});

				jQuery('#segunda-via').validate({
					rules:{
						cpf:{
							required: true
						},
						email:{
							required: true,
							email: true
						}
					},
					messages:{
						cpf:{
							required: "O campo CPF é obrigatório."
						},
						email:{
							required: "O campo E-mail é obrigatório.",
							email: "Você deve digitar um e-mail válido."
						}
					}
				});

				jQuery('#cpf').mask('999.999.999-99');
				
			});
		
		</script>
		
		<?php
		
	}
	
	###############################################################################################################

	# FUNCTION: query_vars
	# DESCRIPTION: adiciona query vars usadas neste plugin.
	public static function query_vars($vars)
	{
		$vars[] = 'prod_id';
		$vars[] = 'modo';
		$vars[] = 'offset';
		$vars[] = 'limit';
		$vars[] = 'order_by';
		$vars[] = 'sort';
		$vars[] = 'cpf';
		$vars[] = 'key';
		$vars[] = 'nosso_numero';
		$vars[] = 'trajettoria_page';
		return $vars;
	}	
	
	###############################################################################################################

	# FUNCTION: _get_setting
	# DESCRIPTION: obtém o valor da propriedade 'prop', a partir da tabela wp_options
	private static function _get_setting($prop)
	{
		return get_option(WP_Plugin_Options::PREFIX . '_' . $prop);
	}
	
	###############################################################################################################

	# FUNCTION: _send_mail
	# DESCRIPTION: envia um e-mail usando o servidor SMTP informado nas configurações globais do plugin
	private static function _send_mail( $to, $subject, $content )
	{

		$host = self::_get_setting( "email_host" );
		$auth = self::_get_setting( "email_auth" );
		$secure = self::_get_setting( "email_secure" );
		$port = self::_get_setting( "email_porta" );
		$username = self::_get_setting( "email_username" );
		$password = self::_get_setting( "email_senha" );
		$from = self::_get_setting( "email" );
		$name = self::_get_setting( "email_from_alias" );
		
		SendMail($host, $auth, $secure, $port, $username, $password, $from, $name, $to, $subject, $content);
		
	}

	###############################################################################################################

	# FUNCTION: _helper_boleto_link
	# DESCRIPTION: cria o URL para a página de renderização do boleto. Fornecer 'key' OU ('nosso_numero' e 'cpf')
	private static function _helper_boleto_link($key=NULL, $nosso_numero=NULL, $cpf=NULL)
	{
		$key = urlencode($key);
		$nosso_numero = urlencode($nosso_numero);
		$cpf = urlencode($cpf);
		
		return get_site_url() . "/ver-boleto/?key=$key&nosso_numero=$nosso_numero&cpf=$cpf";
	}

	###############################################################################################################
	
	# FUNCTION: _get_servico
	# DESCRIPTION: retorna um array com os detalhes do serviço com post_id igual a 'prod_id'
	# Este array possui as chaves: id, title, content, excerpt, usar_boleto, valor, taxa, permitir_upload, 
	# label_descricao, dias_vencimento, formatos_arquivo
	private static function _get_servico($prod_id)
	{
		$p = get_post($prod_id);
		if($p)
		{
		
			// TODO: checar se é um "serviço"
			
			$r = array();
			$r['id'] = $prod_id;
			$r['title'] = $p->post_title;
			$r['content'] = $p->post_content;
			$r['excerpt'] = $p->post_excerpt;
			$r['usar_boleto'] = checkbox_to_bool(get_post_meta($prod_id, 'boleto-usar', TRUE));
			$r['valor'] = get_post_meta($prod_id, 'boleto-valor', TRUE);
			$r['taxa'] = get_post_meta($prod_id, 'boleto-taxa', TRUE);
			$r['permitir_upload'] = checkbox_to_bool(get_post_meta($prod_id, 'boleto-permitir-upload', TRUE));
			$r['label_descricao'] = get_post_meta($prod_id, 'boleto-label-descricao', TRUE);
			$r['dias_vencimento'] = get_post_meta($prod_id, 'boleto-dias-vencimento', TRUE);
			
			//TODO: recuperar valores globais se estiver em branco ou com erro algum dos campos acima
			//TODO: recuperar os formatos de arquivo
			
			return $r;
		}
		else
		{
			return FALSE;
		}
	}
} // end class "TrajettoriaBoletos"

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
register_activation_hook( __FILE__, array( 'TrajettoriaBoletos', 'install' ) );
register_deactivation_hook( __FILE__, array( 'TrajettoriaBoletos', 'uninstall' ) );
$__traj_boletos = new TrajettoriaBoletos();
?>
