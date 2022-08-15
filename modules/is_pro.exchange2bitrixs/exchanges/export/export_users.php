<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');



$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$token = $request->getQuery('token');
if ($token == '') {
	die();
};
include(__DIR__.'/../install/module.cfg.php');
Bitrix\Main\Loader::includeModule($arModuleCfg['MODULE_ID']);

//Получаем список пользователей
$rsUsers = CUser::GetList($by="", $order="",[],array("SELECT"=>array("UF_*")));
while ($user = $rsUsers->Fetch()){

    $arUsers[$user['ID']] = $user;

    if($user['PERSONAL_PHOTO']){
        $arFileTmp = CFile::ResizeImageGet(
            $user['PERSONAL_PHOTO'],
            array("width" => 1000, "height" => 1000),
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );

        $arUsers[$user['ID']]['PERSONAL_PHOTO'] = 'http://' . $_SERVER['SERVER_NAME'] . $arFileTmp["src"];
    }

    $userGroups = CUser::GetUserGroup($user['ID']);

    $arUsers[$user['ID']]['A']['GROUPS'] = $userGroups;
}

echo json_encode($arUsers);

die();
