<?
$arModuleCfg = [
	'MODULE_ID' => 'is_pro.exchange2bitrixs',

	/* Настройки модуля */
	'options_list' => [

		/* Пример настройки с выбором (select) */

		'MODULE_MODE' => [ 					/* Имя настройки */
			'type' => 'select', 			/* Тип поля настройки */
			'values' => [					/* Значения настройки */
				'off',
				'test',
				'on'
			],
			'default' => 'off'				/* Значение по умолчанию */
		],

		'IMPORT_FROM_URL' => [
			'type' => 'text',
			'default' => ''
		],

		'TOKEN' => [
			'type' => 'text',
			'default' => ''
		],

	]
];