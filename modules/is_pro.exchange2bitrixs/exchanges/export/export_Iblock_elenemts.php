<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$token = $request->getQuery('token');
$filter = $request->getQuery('filter');
if (($token == '') || ($filter == '')) {
	die();
};
include(__DIR__.'/../install/module.cfg.php');
Bitrix\Main\Loader::includeModule($arModuleCfg['MODULE_ID']);



$filter = json_decode($filter, true);

$arResult = [];
/* выберим нужные элементы инфоблока */
$rsElements = \Bitrix\Iblock\ElementTable::getList([
	'filter' => $filter,
	'select' => [
		'ID',
		'IBLOCK_ID',
		'*'
	]
]);

while ($arEl = $rsElements->fetch()) {
	/* Получим свойтва элемента */
	$obProp = \CIBlockElement::GetProperty(
		$arEl['IBLOCK_ID'],
		$arEl['ID'],
		[],
		['CODE ' => [
		/* Коды нужных свойств */
			'*',
		]]
	);
	while ($arProp = $obProp->GetNext()) {
		$arEl['PROPERTIES'][$arProp['CODE']]['PROP'] = $arProp;
		$arEl['PROPERTIES'][$arProp['CODE']]['VALUES'][] = $arProp['VALUE'];
		$arEl['PROPERTIES'][$arProp['CODE']]['VALUES_ENUM'][] = $arProp['VALUE_ENUM'];
		$arEl['PROPERTIES'][$arProp['CODE']]['~VALUES'][] = $arProp['~VALUE'];
		$arEl['PROPERTIES'][$arProp['CODE']]['~VALUES_ENUM'][] = $arProp['~VALUE_ENUM'];
	};

	$arResult[$arEl['ID']] = $arEl;
};

echo json_encode($arResult);
die();