<?php

if (is_file(MODX_BASE_PATH . $plugin_base . 'SassParser.php') && $modx->documentObject['contentType'] === 'text/css' && $modx->documentObject['cacheable']) {

	require_once MODX_BASE_PATH . $plugin_base . 'SassParser.php';
	
	$options = array(
		'syntax' => isset($syntax) && in_array($syntax, array('scss', 'sass')) ? $syntax : 'scss',
		'style'  => isset($style)  && in_array($style,  array('nested', 'compact', 'compressed')) ? $style : 'nested',
		'cache'  => false
	);

	$parser = new SassParser($options);

	$content = $modx->documentObject['content'];

	try {
		$content = $parser->toCss($content);
	} catch (Exception $e) {
		$err = $e->__toString();
		$content = '/*' . "\n" . $err . "\n" . '*/' . "\n";
		$content .= 'body:before {
white-space: pre;
font-family: monospace;
content: "' . strtr(addslashes($err), array("\n" => '\\A')) . '"; }';
	}

	$modx->documentObject['content'] = $content;
}
