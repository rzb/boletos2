<?php 

require_once('../../../wp-load.php');

global $wpdb;
global $wp_query;

$cpf = str_replace(array('.','-'), '', $_GET['cpf']);
$email = $_GET['email'];
$totalBoletos = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(id) 
												 FROM " . TrajettoriaBoletos::TRAJ_BOLETOS_TABLE . " 
												 WHERE email='$email' AND cpf='$cpf' AND status_boleto=" . TrajettoriaBoletos::STATUS_BOLETO_EM_ABERTO ) );

if($totalBoletos>0) {
	$boletos = $wpdb->get_results( "SELECT * FROM " . TrajettoriaBoletos::TRAJ_BOLETOS_TABLE . " WHERE email='$email' AND cpf='$cpf' AND status_boleto=" . TrajettoriaBoletos::STATUS_BOLETO_EM_ABERTO );
	
	?>
	<legend>Boletos emitidos:</legend>
	<table class="table table-striped table-custom-padding" id="segunda-via-table" >
		<thead>
			<tr class="bol-thead">
				<th class="col-head col-data"><span>Emissão</span></th>
				<th class="col-head col-data"><span>Vencimento</span></th>
				<th class="col-head col-servico"><span>Serviço</span></th>
				<th class="col-head col-valor"><span>Valor</span></th>
				<th class="col-head col-status"><span>Status</span></th>
				<th class="col-head"><span>Ver boleto</span></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($boletos as $bol) { ?>
			<tr>
				<td><?php echo substr_replace( date_to_br( $bol->data_criacao ), "", 10 ); ?></td>
				<td><?php echo substr_replace( date_to_br( $bol->data_vencimento ), "", 10 ); ?></td>
				<td><?php echo $bol->servico; ?></td>
				<td>R$ <?php echo number_format( $bol->valor, 2, ',', '.' ); ?></td>
				<td class="data bol-statuspedido">
					<?php
						switch ( $bol->status_pedido ) {
							case TrajettoriaBoletos::STATUS_PEDIDO_NAO_INICIADO:
								echo "Aguardando";
								break;
							case TrajettoriaBoletos::STATUS_PEDIDO_EM_EXECUCAO:
								echo "Em execução";
								break;
							case TrajettoriaBoletos::STATUS_PEDIDO_FINALIZADO:
								echo "Finalizado";
								break;
							default:
								break;
						}
					?>
				</td>
				<td><a href="?trajettoria_page=get_boleto&cpf=<?php echo $bol->cpf; ?>&nosso_numero=<?php echo $bol->nosso_numero; ?>" target="_blank" aria-label="Boletos"><img src="http://localhost/boletos2/wp-content/plugins/trajettoria-boletos/img/icon_boleto_16x16.png" alt=""></a></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	
	<?php 
} else {
	// return false
	echo "false";
}

?>