<?php

$theme = 'demo'; // Cambiar en función del tema que se quiera usar
$debug = true; // Cambiar en producción

if ($debug){
	ini_set('display_errors', 'on');
}

//$domain = $_SERVER['HTTP_HOST'];
define('_ROOT_DIR_', realpath(dirname(__FILE__)));
define('_THEMES_DIR_', _ROOT_DIR_.'/themes/');
define('_JS_DIR_', _ROOT_DIR_.'/res/js/');

define('_THEME_DIR_', _THEMES_DIR_.$theme.'/'); // Elijo el tema de la landing, esto en teoría se podría hacer con un .htaccess y redireccionando en función de donde viniera la URL, o por BBDD cogiendo el domain

$theme_dir = 'themes/'.$theme.'/'; //ruta del tema para las plantillas

require_once 'functions.inc.php';

if (!checkFile(_THEME_DIR_.'config.inc.php'))
	die('No se encuentra el archivo de configuración del tema en '._THEME_DIR_.'config.inc.php');

require_once _THEME_DIR_.'config.inc.php';

$action = getValue('action', 'index'); // Voy a ver dónde voy.
$ajax = getValue('ajax'); // A ver si la petición es para AJAX o normal.

/**
* Preparo los datos de la webapp
*/
$codes = array();

// Si hay conexion a la base de datos
if (!empty($config['bbdd']['db_enable']) && $config['bbdd']['db_enable'] == 'on')
	$mysql_use = true;
else
	$mysql_use = false;

// Si existen variables de highCO, lo activo
if (!empty($config['highco']['highco_enable']) && $config['highco']['highco_enable'] == 'on')
	$highco_use = true;
else
	$highco_use = false;

// Si facebook tiene app miro el app_data
if (!empty($config['facebook']['secret_key_app']))
	$referer = get_referer($_POST['signed_request'], $config['facebook']['secret_key_app']);
else
	$referer = null;

// Si facebook tiene url para compartir cargo el sdk de facebook
if (!empty($config['facebook']['share_url'])){
	$fb_vars = array(
		'{id_app}' => $config['facebook']['id_app'],
		'{secret_key_app}' => $config['facebook']['secret_key_app'],
		'{share_url}' => $config['facebook']['share_url'],
		'{campaign}' => $config['facebook']['campaign']
		);
	$codes[] = getTemplate('facebook', $fb_vars, 'js');
}
else
	$fb_vars = array();

// Si la app es ajax, cargo el js de ajax para interactuar con los elementos de la web
if ($config['ajax_app'])
	$codes[] = getTemplate('ajax', array(), 'js');

$javascript_code = implode("\r\n", $codes);

if (!$action || $action == 'index'){
	$form_vars = array('{referer_id}' => $referer, '{theme_dir}' => $theme_dir);
	$content_page = getTemplate('main', $form_vars); // Contenido de la página principal
}
else {
	$action_vars = array('{theme_dir}' => $theme_dir);
	if ($action == 'submit_form'){

		/** Si está activo MySQL guardo los datos del formulario en la tabla `landing_users`
		 * Si existe el referer, guardo la "participación" en `landing_partner_user_promo` y añado una participación en la tabla `landing_users` al usuario indicado
		 */
		if ($mysql_use){
			$mysqli = new mysqli($config['bbdd']['db_host'], $config['bbdd']['db_user'], $config['bbdd']['db_pass'], $config['bbdd']['db_name']);
			$mysqli->set_charset('utf8');
			$fields = array();
			$values = array();
			$referer = null;
			$coupon = null;
			foreach ($_POST as $var => $value)
			{
				if ($var != 'referer_id')
				{
					if ($var != 'action' && $var != 'ajax' && $var != 'envia'){
						$fields[] = $var;
						$values[] = '"'.$mysqli->real_escape_string($value).'"';
						if ($highco_use)
							$config['highco']['userData'][$var] = $value;
					}
				}
				else{
					if (!empty($value))
						list($coupon, $referer) = explode('_', $value);
					else{
						$coupon = null;
						$referer = null;
					}
				}
			}

			$sql = 'INSERT IGNORE INTO `landing_users` ('.implode(',', $fields).', `fecha`) VALUES ('.implode(',', $values).', NOW())';

			if (!$mysqli->query($sql))
			{
				_log('SQL', 'Error INSERT: '.$mysqli->error.' data: '.print_r($_POST,true));
				if ($debug)
					die($mysqli->error);
			}

			$id_insert = $mysqli->insert_id;

			if ($referer != null && $coupon != null && $id_insert != $referer){
				$check_referer = $mysqli->query('SELECT `id_referer` FROM `landing_partner_user_promo` WHERE `id_referer` = '.(int)$referer.' AND `CouponsList` = '.(int)$coupon.' AND id_customer = '.(int)$id_insert);

				if ($check_referer->num_rows == 0)
				{
					// Si no devuelve nada, es que es nuevo afiliado y por lo tanto le sumo una participación al anterior
					$mysqli->query('INSERT IGNORE INTO `landing_partner_user_promo` (`id_referer`, `id_customer`, `CouponsList`) VALUES ('.(int)$referer.','.(int)$id_insert.','.(int)$coupon.')');
					$mysqli->query('UPDATE `landing_users` SET `participaciones` = `participaciones`+1 WHERE `id` = '.(int)$referer);
				}
			}
		}

		if ($highco_use){

			if (!$debug){
				$client = new SoapClient('http://195.200.165.154/Services/PixiboxInt_Dev.asmx?wsdl', array("soap_version" => SOAP_1_2));
				$reply = $client->CreateUpdateUserresamail($config['highco']['userData']);
			}
			else{
				$reply = new stdClass();
			   	//$reply->CreateUpdateUserResaMailResult = 'NOOK_Result=1764_1';
			   	$reply->CreateUpdateUserResaMailResult = 'OK_Result=1764_0';
			}

			if (stristr($reply->CreateUpdateUserResaMailResult, 'NOOK_Result') !== FALSE){
				_log('SOAP', 'Return: '.$reply->CreateUpdateUserResaMailResult. ' data: '.print_r($config['highco']['userData'],true));
				$template = 'highco_error_form';
			}
			else if (stristr($reply->CreateUpdateUserResaMailResult, 'OK_Result') !== FALSE){

				if ($mysql_use)
					$mysqli->query('UPDATE `landing_users` SET `PartnerUID` = "'.$config['highco']['userData']['PartnerUID'].'", `PartnerKey` = "'.$config['highco']['userData']['PartnerKey'].'", `CouponsList` = "'.$config['highco']['userData']['CouponsList'].'" WHERE `id` = '.(int)$id_insert); // Actualizo la tabla con los datos de highco

				$action_vars['{url_coupon}'] = 'http://195.200.165.154/Pixiboxmasterprint.aspx?Email='.$config['highco']['userData']['URN'].'&partnerkey='.$config['highco']['userData']['PartnerKey'];  // Añado la url para descargar
				$action_vars['{email}'] = $config['highco']['userData']['URN']; // El mail
				$action_vars['{PartnerKey}'] = $config['highco']['userData']['PartnerKey'];
				$action_vars['{FirstName}'] = $config['highco']['userData']['FirstName']; // Nombre del usuario
				$action_vars['{LastName}'] = $config['highco']['userData']['LastName']; // Apellidos

				$template = 'highco_ok_form';
			}
		}
		else
		{
			if (!empty($config['facebook']['campaign']) && $mysql_use)
					$mysqli->query('UPDATE `landing_users` SET `CouponsList` = "'.$config['facebook']['campaign'].'" WHERE `id` = '.(int)$id_insert); // Meto la campaña de facebook como CouponList para poder hacer url de compartir con la campaña de facebook si existe

			$template = 'result_form';
		}
	}
	else if ($action == 'send_email'){
		$user_email = getValue('userEmail');

		if ($user_email){
			$asunto_mail = $config['email']['asunto'];
			$cabeceras = 'From: ' . $config['email']['remitente'] . "\r\n";
			$cabeceras .= "Content-Type: text/html; charset=utf-8\r\n";

			/** Cojo el nombre del usuario **/
			if ($mysql_use){
				$mysqli = new mysqli($config['bbdd']['db_host'], $config['bbdd']['db_user'], $config['bbdd']['db_pass'], $config['bbdd']['db_name']);
				$mysqli->set_charset('utf8');
				$sql = 'SELECT `id`, `FirstName`, `LastName` FROM `landing_users` WHERE `URN` = "'.$user_email.'"';
				$exec = $mysqli->query($sql);

				if (!$exec && $debug)
					echo $mysqli->error;

				$datos = $exec->fetch_assoc();
			}
			/** Fin **/

			$para = $user_email;

			if ($highco_use){
				$partner_key = getValue('partnerKey');
				$url = 'http://195.200.165.154/Pixiboxmasterprint.aspx?Email='.$user_email.'&partnerkey='.$partner_key;
			}

			$mail_vars = array();
			if (isset($datos) || $url){
				$mail_vars = array(
					'{url}' => (isset($url) ? $url : ''),
					'{FirstName}' => (isset($datos['FirstName']) ? $datos['FirstName'] : ''),
					'{LastName}' =>  (isset($datos['LastName']) ? $datos['LastName'] : ''),
					'{asunto}' => $config['email']['asunto'],
				);
			}

			$template_mail = getTemplate('email', $mail_vars);
			if (!mail($para, $asunto_mail, $template_mail, $cabeceras))
				_log('EMAIL', 'No se pudo enviar el correo');
		}

		$template = 'mail_success';
	}

	// Genero una url para compartir, siempre y cuando exista la url ya por defecto y se haya usado mysql (para conseguir referer) y en este caso highCO, aquí habría que modificar el referer si se quisiera poder compartir sólo con el id de usuario (para casos fuera de highCO), si no cogerá la url de compartir sin ningún parámetro extra.
	if (!empty($fb_vars['{share_url}']) && $mysql_use){
		$coupon = (isset($config['highco']['userData']['CouponsList']) && $highco_use ? $config['highco']['userData']['CouponsList'] : (!empty($config['facebook']['campaign']) ? $config['facebook']['campaign'] : null));

		$user_id = (isset($id_insert) && $id_insert ? $id_insert : (isset($datos) ? $datos['id'] : null));

		if ($coupon != null && $user_id != null)
			$fb_vars['{share_url}'] .= '?app_data='.$coupon.'_'.$user_id;
	}

	$template_vars = array_merge($action_vars,$fb_vars);
	$content_page = getTemplate($template, $template_vars);
}
// Si el contenido no es ajax, lo que hago es cargar el index con el contenido de la página correspondiente.
if (!$ajax)
{
	$template_vars = array(
		'{title}' => $config['titulo_pagina'],
		'{content}' => $content_page,
		'{og_url}' => $config['og_url'],
		'{og_title}' => $config['og_title'],
		'{og_description}' => $config['og_description'],
		'{og_image}' => $config['og_image'],
		'{theme_dir}' => $theme_dir,
		'{javascript_code}' => (isset($javascript_code) ? '<script>'.$javascript_code.'</script>' : '')
		);

	$main_template = getTemplate('index', $template_vars);

	echo $main_template;
}
else
{
	die($content_page); // Si la petición es AJAX, muestro el contenido directamente
}