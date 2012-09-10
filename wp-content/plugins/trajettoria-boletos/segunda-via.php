<?php 

require_once('../../../wp-load.php');

global $wpdb;
global $wp_query;

$cpf = $_GET['cpf'];
$email = $_GET['email'];
$totalBoletos = $wpdb->get_var($wpdb->prepare("SELECT COUNT (id)
											   FROM " . self::TRAJ_BOLETOS_TABLE . "
											   WHERE cpf=$cpf AND email=$email AND status_boleto=" . TrajettoriaBoletos::STATUS_BOLETO_EM_ABERTO));
if($totalBoletos>0) {
	// return true
	?>
	true
	<?php 
} else {
	// return false
	?>
	false
	<?php
}

?>