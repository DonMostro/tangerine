<?php

	require_once 'admportal/modulos/usuarios/web/model/UsuariosDAO.php';

	$dao = new UsuariosDAO( $dsn_admportal );

	$data = json_decode( $HTTP_RAW_POST_DATA );
	$resp = array();
	$dao->process( $data, &$resp );

	echo json_encode($resp)."\n";
?>
