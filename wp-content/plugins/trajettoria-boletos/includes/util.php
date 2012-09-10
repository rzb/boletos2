<?php
//-----------------------------------------------------------------------------------------
// Copyright (C) 2012 Trajettoria Tecnologia da Informação Ltda. Todos os direitos reservados. All rights reserved.
//-----------------------------------------------------------------------------------------

# FUNCTION: check_cpf
# DESCRIPTION: verifica se foi informado um CPF consistente, calculado seu dígito verificador.

function check_cpf($cpf)
{
	$dv_informado = substr($cpf, 9,2);
	for($i=0; $i<=8; $i++)
	{
		$digito[$i] = substr($cpf, $i,1);
	}

	/*Agora sera calculado o valor do decimo digito de verificacao*/
	$posicao = 10;
	$soma = 0;
	for($i=0; $i<=8; $i++)
	{
		$soma = $soma + $digito[$i] * $posicao;
		$posicao = $posicao - 1;
	}
	$digito[9] = $soma % 11;
	if($digito[9] < 2)
	{
		$digito[9] = 0;
	}
	else
	{
		$digito[9] = 11 - $digito[9];
	}
	/*Agora sera calculado o valor do decimo primeiro digito de verificacao*/
	$posicao = 11;
	$soma = 0;
	for ($i=0; $i<=9; $i++)
	{
		$soma = $soma + $digito[$i] * $posicao;
		$posicao = $posicao - 1;
	}
	$digito[10] = $soma % 11;
	if ($digito[10] < 2)
	{
		$digito[10] = 0;
	}
	else
	{
		$digito[10] = 11 - $digito[10];
	}
	/*Nessa parte do script sera verificado se o digito verificador e igual ao informado pelo
	 usuario*/
	$dv = $digito[9] * 10 + $digito[10];
	return ($dv == $dv_informado);
}

###############################################################################################################

# FUNCTION: SendMail
# DESCRIPTION: envia um e-mail via SMTP

function SendMail($host, $auth, $secure, $port, $username, $password, $from, $name, $to, $subject, $content)
{
	// Create PHPMailer object
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail = new PHPMailer();

	// Define server connection info
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsSMTP(); 												# Will be SMTP
	$mail->Host 		= $host; 									# SMTP server add
	$mail->SMTPAuth 	= $auth;	 								# Does it use SMTP auth? (optional)
	$mail->SMTPSecure 	= $secure; 									# Sets the prefix to the server
	$mail->Port			= $port;									# Set the SMTP port for the server
	$mail->Username		= $username;								# SMTP server user
	$mail->Password 	= $password;								# SMTP server password

	// Define sender
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->From = $from;											# Your e-mail
	$mail->FromName = $name;										# Your name

	// Define receiver(s)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->AddAddress($to, 'Trajettoria');

	// Define msg type
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->IsHTML(true); 											# Will be HTML
	$mail->CharSet = 'utf-8'; // Charset (optional)

	// And finally the MESSAGE (Subject and Body)
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->Subject  = $subject;		 								# Subject
	$mail->Body = $content;											# Body
	$mail->AltBody = "";											# Alternative Body for non-HTML content

	// Send e-mail
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$sent = $mail->Send();											# Pa!

	// Cleaners
	// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
	$mail->ClearAllRecipients();
	$mail->ClearAttachments();
	
	return $sent;
}

###############################################################################################################

# FUNCTION: GetPassword
# DESCRIPTION: gera uma senha aleatória com 'lenght' caracteres

function GetPassword($length)
{
	$valid_chars = 'abcdxywzABCDZYWZ0123456789!@#$%&*';
	$max = strlen($valid_chars) - 1;
	for($i=0; $i < $length; $i++)
	{
		$password_nocoded .= $valid_chars{mt_rand(0, $max)};
	}
	return $password_nocoded;
}

###############################################################################################################

# FUNCTION: PrimeirosCaracteres
# DESCRIPTION: se 'str' possuir mais que 'n' caracteres, trunca a string e concatena '...' ao final. Útil quando
# se quer mostrar uma string num local de tamanho limitado

function PrimeirosCaracteres($str, $n)
{
	$temp = (strlen($str) > $n) ? "..." : "";
	return substr($str, 0, $n) . $temp;
}

###############################################################################################################

# FUNCTION: NowDatetime
# DESCRIPTION: retorna a data/hora atual no formato MySQL

function NowDatetime()
{
	return date("Y-m-d H:i:s");
}

###############################################################################################################

# FUNCTION: date_to_br
# DESCRIPTION: converte uma data/hora em formato MySQL para o formato dd/mm/yyyy H:m:s

function date_to_br($mysql_datetime) 
{
	$dateTime = explode( ' ', $mysql_datetime );
	$dateTime[0] = explode( '-', $dateTime[0] );
	$dateTime = $dateTime[0][2].'/'.$dateTime[0][1].'/'.$dateTime[0][0].' '.$dateTime[1];

	return $dateTime;
}
	
###############################################################################################################

# FUNCTION: date_to_br
# DESCRIPTION: converte uma data/hora em formato MySQL para o formato dd/mm/yyyy H:m:s

function FilterData($variable)
{
	#gera warning se a conexao com o banco nao estiver estabelecida
	return mysql_real_escape_string(strip_tags($variable));
}

###############################################################################################################

# FUNCTION: create_guid
# DESCRIPTION: gera um GUID (Global Unique Identifier) de 32 posições, com mais 4 hífens acessórios

function create_guid($namespace = '')
{
	static $guid = '';
	$uid = uniqid("", true);
	$data = $namespace;
	$data .= $_SERVER['REQUEST_TIME'];
	$data .= $_SERVER['HTTP_USER_AGENT'];
	$data .= $_SERVER['REMOTE_ADDR'];
	$data .= $_SERVER['REMOTE_PORT'];
	$hash = strtoupper(hash('ripemd128', $uid . $guid . md5($data)));
	$guid = substr($hash,  0,  8) .
	'-' .
	substr($hash,  8,  4) .
	'-' .
	substr($hash, 12,  4) .
	'-' .
	substr($hash, 16,  4) .
	'-' .
	substr($hash, 20, 12);
	return $guid;
}

###############################################################################################################

# FUNCTION: print_array
# DESCRIPTION: helper php que imprime (print_r) um array, intercalado por <pre> e </pre>. Para debug.


function print_array($arr)
{
	if(is_array($arr))
	{
		echo "<br><pre>";
		print_r($arr);
		echo "</pre><br>";
	}
}

###############################################################################################################

# FUNCTION: get_ip
# DESCRIPTION: returns current client IP address

function get_ip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) 
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

###############################################################################################################

# FUNCTION: checkbox_to_bool
# DESCRIPTION: convert 'on' values to TRUE

function checkbox_to_bool($val)
{
	return ($val == 'on');
}

 ?>
