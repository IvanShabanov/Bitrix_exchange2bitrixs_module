<?php
if (file_exists(__DIR__ . "/install/module.cfg.php")) {
	include(__DIR__ . "/install/module.cfg.php");
};

CModule::IncludeModule($arModuleCfg['MODULE_ID']);
global $DBType;

$arClasses=array(
	/* Библиотеки и слассы для авто загрузки */
	/*
	'IS_PRO\exchange2bitrixs\lib'=>'lib/lib.php',
    'IS_PRO\exchange2bitrixs\cMain_exchange2bitrixs'=>'classes/general/cMain_exchange2bitrixs.php'
	*/
);

CModule::AddAutoloadClasses($arModuleCfg['MODULE_ID'], $arClasses);
