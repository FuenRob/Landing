<?php

/**
 * Explicación de las plantillas:
 * index.html - LLeva la estructura de la web, header y footer, importante las variables {content} (cuerpo de la página}, {javascript_code} (códigos JS)
 * main.html - Contenido de la página principal (formulario por lo general)
 * result_form.html - Contenido para formulario estándar.
 * highco_ok_form.html - Página de OK de formulario cuando se usa HighCO
 * highco_error_form.html - Página de error de formulario cuando se usa HighCO
 * mail_success.html - Página de confirmación de envío de email cuando se usa HighCO
 */

$config = array(
	'titulo_pagina' => 'Sorteo Sportamina', // Título de la página
	'og_title' => 'Sorteo Sportamina', // Título para compartir en redes sociales
	'og_url' => 'https://www.facebook.com/Sportaminafans-841118175977830/app/583712321804523/', // Página para compartir en redes sociales
	'og_description' => 'Gana 100€ para gastar en Sportamina', // Descripción para compartir en redes sociales
	'og_image' => 'http://sportamina.com/sorteo/themes/borjis/img/logo.png', // Imagen para compartir en redes sociales  200px by 200px.
	'ajax_app' => true, // Si está en true, los datos vendrán por ajax, si está en falso vendrán normales a no ser que en algún formulario se especifique lo contrario. RECOMENDADO DEJARLO TRUE.
	'bbdd' => array(
		'db_enable' => 'on', // Si se va a utilizar la base de datos para guardar datos, ON, si no OFF
		'db_host' => 'localhost', // Servidor de la base de datos
		'db_user' => 'sportami_user', // Usuario de la base de datos
		'db_pass' => '}a=T{Tk4?zAy', // Contraseña del usuario de la base de datos
		'db_name' => 'sportami_prestashop', // Nombre de la base de datos
		),
	'facebook' => array(
		'id_app' => '583712321804523', // ID de la app de Facebook en el caso de que la landing fuera en una app
		'secret_key_app' => '66024a1b171c32d7ed6c80bc2f5a0c1b', // Secrey Key de la app de Facebook
		'share_url' => 'https://www.facebook.com/Sportaminafans-841118175977830/app/583712321804523/', // Url para compartir de Facebook terminada en "/"
		'campaign' => 999001, // para registrar una campaña en la base de datos para saber de donde vienen los usuarios (si no se usa highCO, si se usa highCO irá por el CouponsLists). NUMERICO
		),
	'highco' => array(
		'highco_enable' => 'off', // Si es una landing de highCO ponerlo en on
		'userData' => array(
			'PartnerKey'=>utf8_encode('500C1DA7B1'), // CAMBIAR: cuando nos envíen uno nuevo
			'PartnerPassword'=>utf8_encode('WeNmGmQoOk'), // CAMBIAR: cuando nos envíen uno nuevo
			'PartnerUID'=>118, // CAMBIAR: cuando envíen uno nuevo
			'FirstName'=>utf8_encode('N/A'), // NO TOCAR
			'LastName'=>utf8_encode('N/A'), // NO TOCAR
			'Language'=>5, // NO TOCAR
			'AddressPostalCode'=>utf8_encode('N/A'), // NO TOCAR
			'AddressCity'=>utf8_encode('N/A'), // NO TOCAR
			'CouponsList'=>utf8_encode('2410'), // CAMBIAR: cuando nos envíen uno nuevo
			'URN'=>utf8_encode('N/A'), // NO TOCAR
			'customProperty1'=>utf8_encode(''), // NO TOCAR
			'customProperty2'=>utf8_encode('') // NO TOCAR
			)
		),
	'email' => array(
		'asunto' => 'Tus cupones', // Si se quiere enviar un mail, poner aquí el asunto
		'remitente' => 'ofertas@gideaonline.com', // Idem con el remitente
		)
);