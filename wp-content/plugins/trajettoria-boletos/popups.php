<?php

require_once('../../../wp-load.php');
		
global $wpdb;
global $wp_query;

$popup = $_GET["popup"];
switch ($popup) {
	case 'cliente':
		$cpf = $_GET["cpf"];
		$boletos = $wpdb->get_results( "SELECT * 
										FROM " . TrajettoriaBoletos::TRAJ_BOLETOS_TABLE . " 
										WHERE cpf = $cpf
										ORDER BY data_criacao ASC" , 
										OBJECT );
										
		$totalBoletos = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) 
														 FROM " . TrajettoriaBoletos::TRAJ_BOLETOS_TABLE . "
														 WHERE cpf = $cpf" ) );
		
		$somaPedidos = $wpdb->get_var( $wpdb->prepare( "SELECT SUM( valor ) FROM " . TrajettoriaBoletos::TRAJ_BOLETOS_TABLE . " WHERE cpf = $cpf" ) );

?>

		<div class="modal-header">
			<button type="button" class="close close-modal" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="generic-modalLabel"><?php echo $boletos[0]->nome; ?></h3>
		</div>
		<div class="modal-body">
			<div class="resumo resumo-cliente">
				<div class="row-fluid">
					<span class="span4">CPF: <?php echo $boletos[0]->cpf; ?></span>
					<span class="span4">Tel: <?php echo $boletos[0]->telefone; ?></span>
					<span class="span4">Cel: <?php echo $boletos[0]->celular; ?></span>
				</div>
				<div class="row-fluid">
					<span class="span12">Endereço: <?php echo $boletos[0]->endereco; ?></span>
				</div>
			</div>
			<div class="resumo resumo-pedido">
				<div class="row-fluid">
					<span class="span6">Data do primeiro pedido: <?php echo date_to_br( $boletos[0]->data_criacao ); ?></span>
					<span class="span6">Total de pedidos: R$<?php echo number_format( $somaPedidos, 2, ',', '.' ); ?></span>
				</div>
			</div>
			
			<table class="table table-striped table-condensed table-custom-padding table-popup" >
				<thead>
					<tr class="bol-thead">
						<th class="col-head col-nossonumero">
							<span>Nosso Número</span>
						</th>
						<th class="col-head col-data">
							<span>Dt. Emissão</span>
						</th>
						<th class="col-head col-data">
							<span>Dt. Venc.</span>
						</th>
						<th class="col-head col-servico">
							<span>Serviço</span>
						</th>
						<th class="col-head col-status">
							<span><abbr 
title="Status do boleto:
0 = Aberto
1 = Cancelado
2 = Pago
3 = Vencido">B</abbr>		</span>
						</th>
						<th class="col-head col-status">
							<span><abbr 
title="Status do pedido:
0 = Aguardando
1 = Em execução
2 = Finalizado">P</abbr>	</span>
						</th>
						<th class="col-head input-medium">
							<span>Opções</span>
						</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ( $boletos as $bol ) { ?>
					<tr>
						<td class="col-nossonumero"><?php echo $bol->nosso_numero; ?></td>
						<td class="col-data"><?php echo substr_replace( date_to_br( $bol->data_criacao ), "", 10 ); ?></td>
						<td class="col-data"><?php echo substr_replace( date_to_br( $bol->data_vencimento ), "", 10 ); ?></td>
						<td class="col-servico"><?php echo $bol->servico; ?></td>
						<td class="col-status"><?php echo $bol->status_boleto; ?></td>
						<td class="col-status"><?php echo $bol->status_pedido; ?></td>
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
								<option value="ver_<?php echo $bol->id; ?>">Ver boleto</option>
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
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary close-modal" data-dismiss="modal" aria-hidden="true">Fechar</button>
		</div>
		

<?php
		break;
	case 'pedido':
		
		$bol_id = $_GET['bol-id'];
		$pedido = $wpdb->get_row( "SELECT t1.*, t2.post_title
									   FROM " . TrajettoriaBoletos::TRAJ_BOLETOS_TABLE . " AS t1
									   LEFT JOIN wp_posts AS t2
									   ON (t1.post_id = t2.ID)
									   WHERE t1.id = $bol_id", OBJECT );
?>
		<div class="modal-header">
			<button type="button" class="close close-modal" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="generic-modalLabel">Pedido #<?php echo $pedido->nosso_numero; ?></h3>
		</div>
		<div class="modal-body">
			<div class="resumo resumo-cliente">
				<div class="linha">
					<div class="etiqueta">Serviço:</div><div class="conteudo"><?php echo $pedido->post_title; ?></div>
				</div>
				<div class="linha">
					<div class="etiqueta">Cliente:</div><div class="conteudo"><?php echo $pedido->nome; ?></div>
				</div>
				<div class="linha">
					<div class="etiqueta">CPF:</div><div class="conteudo"><?php echo $pedido->cpf; ?></div>
				</div>
				<div class="linha">
					<div class="etiqueta">Endereço:</div><div class="conteudo"><?php echo $pedido->endereco; ?></div>
				</div>
				<div class="linha">
					<div class="etiqueta">Tel:</div><div class="conteudo"><?php echo $pedido->telefone; ?></div><div class="etiqueta">Cel:</div><div class="conteudo"><?php echo $pedido->celular; ?></div>
				</div>
			</div>
			<div class="resumo resumo-pedido">
				<div class="linha">
					<div class="etiqueta">Emitido em:</div><div class="conteudo"><?php echo substr_replace( date_to_br( $pedido->data_criacao ), "", 10 ); ?></div>
				</div>
				<div class="linha">
					<div class="etiqueta">Vence em:</div><div class="conteudo"><?php echo substr_replace( date_to_br( $pedido->data_vencimento ), "", 10 ); ?></div>
				</div>
				<div class="linha">
					<div class="etiqueta">Valor:</div><div class="conteudo">R$<?php echo number_format( $pedido->valor, 2, ',', '.' ); ?></div>
				</div>
				<div class="linha">
					<div class="etiqueta">Taxa do boleto:</div><div class="conteudo">R$<?php echo number_format( $pedido->taxa_boleto, 2, ',', '.' ); ?></div>
				</div>
				<div class="linha">
					<div class="etiqueta">Descrição:</div><div class="conteudo"><?php echo $pedido->descricao; ?></div>
				</div>
			</div>
			<div class="linha">
				<a href="#">Ver boleto</a>
			</div>
			<div class="resumo resumo-arquivos">
				
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary close-modal" data-dismiss="modal" aria-hidden="true">Fechar</button>
		</div>
<?php
		//@todo ver boleto e download de arquivos
		break;
	default:
		
		break;
}

?>

<script>

jQuery(document).ready(function(){

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

});

</script>
