<?php
/** 
 * As configurações básicas do WordPress.
 *
 * Esse arquivo contém as seguintes configurações: configurações de MySQL, Prefixo de Tabelas,
 * Chaves secretas, Idioma do WordPress, e ABSPATH. Você pode encontrar mais informações
 * visitando {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. Você pode obter as configurações de MySQL de seu servidor de hospedagem.
 *
 * Esse arquivo é usado pelo script ed criação wp-config.php durante a
 * instalação. Você não precisa usar o site, você pode apenas salvar esse arquivo
 * como "wp-config.php" e preencher os valores.
 *
 * @package WordPress
 */

// ** Configurações do MySQL - Você pode pegar essas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('DB_NAME', 'wp_boletos2');

/** Usuário do banco de dados MySQL */
define('DB_USER', 'root');

/** Senha do banco de dados MySQL */
define('DB_PASSWORD', 'krieger');

/** nome do host do MySQL */
define('DB_HOST', 'localhost');

/** Conjunto de caracteres do banco de dados a ser usado na criação das tabelas. */
define('DB_CHARSET', 'utf8');

/** O tipo de collate do banco de dados. Não altere isso se tiver dúvidas. */
define('DB_COLLATE', '');

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * Você pode alterá-las a qualquer momento para desvalidar quaisquer cookies existentes. Isto irá forçar todos os usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '`MiXk{cD6mUJY3X9_Zyqk]}]<3@3!P(,/.sEUxd<3Q|WLqOCNX03ZW6H_OnY?C4i');
define('SECURE_AUTH_KEY',  'C3-ArAPDy`?UcQ_VH=,o}]8HC9!tx79-Tp3(y0b>$e%7rz{qzYl3R*e~ WP%Zilz');
define('LOGGED_IN_KEY',    'GNlaV&jWm2OqaIx)rlSBbHfz*b}U^)tX@mWU1/`vfv&T6n#_Ae9a)Wl[g=9>FoA>');
define('NONCE_KEY',        'Q|E0w6Fbo8d}l:e6$8l$UNw<BB}%PNtFQ$i]/It^!Lpvq%@t5=m *&OfrcntT<r.');
define('AUTH_SALT',        '$Kkyt%j6|}BfyEsXE:3BFX9_fRkZXp1,JHAsMR[6sUH%kxm;o?0VcNE/)*s}d6i<');
define('SECURE_AUTH_SALT', '|A4HB4eLOFfl7Kkl nLd;41*y!su@Yy2i;mzhh3wjl}De]jUD)/((n5oDoQ1VNOz');
define('LOGGED_IN_SALT',   'O56puGv)nx@A.|^^ZCGzJNf9&Z}zP~;Nz7boRUH2:#M^AdsdX&wzB)(dDCSkf^]e');
define('NONCE_SALT',       'qj<$j3pi[3VlLCQVK[6(KV,-a@uV=jvY39_c[{_vI7.o;W>dpb_<+YLcT~FiF0CI');

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der para cada um um único
 * prefixo. Somente números, letras e sublinhados!
 */
$table_prefix  = 'wp_';

/**
 * O idioma localizado do WordPress é o inglês por padrão.
 *
 * Altere esta definição para localizar o WordPress. Um arquivo MO correspondente ao
 * idioma escolhido deve ser instalado em wp-content/languages. Por exemplo, instale
 * pt_BR.mo em wp-content/languages e altere WPLANG para 'pt_BR' para habilitar o suporte
 * ao português do Brasil.
 */
define('WPLANG', 'pt_BR');

/**
 * Para desenvolvedores: Modo debugging WordPress.
 *
 * altere isto para true para ativar a exibição de avisos durante o desenvolvimento.
 * é altamente recomendável que os desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 */
define('WP_DEBUG', false);

/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');
	
/** Configura as variáveis do WordPress e arquivos inclusos. */
require_once(ABSPATH . 'wp-settings.php');
