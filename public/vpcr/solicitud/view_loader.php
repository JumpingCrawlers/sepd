<?php

include($_SERVER['DOCUMENT_ROOT'].'/vpcr_config.php');

session_start();

include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/DataBase.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/Dominio.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/Competencia.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/CompetenciaConocimiento.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/CompetenciaHabilidad.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/CompetenciaAptitud.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/Evaluacion.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/UsuarioCompetenciaConocimiento.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/UsuarioCompetencia.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/UsuarioCompetenciaHabilidad.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/UsuarioCompetenciaAptitud.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/model/usuarioCompetenciaMerito.php');

include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/front_controller.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/dominio_controller.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/competencia_controller.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/validar_controller.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/usuarios_competencias_controller.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/recertificacion_controller.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/confirmacion_controller.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/listado_controller.php');
include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/finalizado_controller.php');

include($_SERVER['DOCUMENT_ROOT'].'/vpcr/controller/api_controller.php');