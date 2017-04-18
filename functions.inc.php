<?php

function base64_url_decode($input) {
  return base64_decode(strtr($input, '-_', '+/'));
}

function get_referer($signed_request, $secret){

	if (!$signed_request || !$secret)
		return null;

	list($encoded_sig, $payload) = explode('.', $signed_request, 2);
	// decode the data
	$sig = base64_url_decode($encoded_sig);
	$data = json_decode(base64_url_decode($payload), true);

	// confirm the signature
	$expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
	if ($sig !== $expected_sig) {
		error_log('Bad Signed JSON signature!');
		return null;
	}

	$app_data = $data['app_data']; // CouponList_idReferer

	return $app_data;
}

function _log($log = 'general', $msg)
{
	$date = date('Y-m-d');
	$time = date('H:i:s');
	$fp = fopen(dirname(__FILE__).'/logs/'.$log.'_'.$date.'.txt', 'a+');
	fwrite($fp, $date.' - '.utf8_encode($msg));
	fclose($fp);
}

function getTemplate($template, $template_vars = array(), $type = 'html')
{
	if (!$template)
		return false;

	$theme_file = ($type == 'html' ? _THEME_DIR_ : _JS_DIR_).$template.'.'.$type;

	if (!file_exists($theme_file))
		die('No se encontró la plantilla '.$theme_file.' del tema');

	$vars = array_keys($template_vars);
	$values = $template_vars;

	$getHtml = file_get_contents($theme_file);
	$output = str_replace($vars,$values, $getHtml);

	return $output;
}

function checkFile($filename)
{
	if (!file_exists($filename) || !is_readable($filename))
		return false;

	return true;
}

function getValue($key, $default_value = false)
{
    if (!isset($key) || empty($key) || !is_string($key)) {
        return false;
    }

    $ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : $default_value));

    if (is_string($ret)) {
        return stripslashes(urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret))));
    }

    return $ret;
}