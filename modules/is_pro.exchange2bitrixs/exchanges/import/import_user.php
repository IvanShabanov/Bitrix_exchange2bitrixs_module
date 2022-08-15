<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');



$arTotal = array();

$url = 'https://your_site.ru/exportUSERS.php';

$postdata = array();

$post = http_build_query($postdata);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
$response = curl_exec($ch);
curl_close($ch);

//Преобразовываем std в массив
$response = json_decode($response, True);

$arTotal['Получено пользователей'] = count($response);
$arTotal['Занесено'] = 0;

if(!CModule::IncludeModule("iblock")){die();}

/* Таблица сообветсвия ГРУПП пользователей */
/*  */
$tableOfGroups = [];

$connection = Bitrix\Main\Application::getConnection('default');
$sqlHelper = $connection->getSqlHelper();

foreach ($response as $key => $value){

	$rsUser = CUser::GetByLogin($value['LOGIN']);
	if($arUser = $rsUser->Fetch()) {
		continue;
	};

    $arFields = [];

    foreach ($value as $k => $v){

        switch ($k) {
            case 'PERSONAL_PHOTO':
                if($v){
                    $arIMAGE = CFile::MakeFileArray($value['PERSONAL_PHOTO']);
                    $arIMAGE["MODULE_ID"] = "main";
                    $arFields[$k] = $arIMAGE;
                }
                break;
            case 'A':
                $groupIDS = [];
                //По таблице соответствия групп проставляем требуемые уровни доступа
                foreach ($v['GROUPS'] as $name => $val){
					if ($tableOfGroups[$val] != '') {
                    	array_push($groupIDS, $tableOfGroups[$val]);
					} else {
						array_push($groupIDS, $val);
					}
                }
                $arFields['GROUP_ID'] = $groupIDS;
                break;
            case 'LID':
                break;
            case 'IS_ONLINE':
                break;
            case 'PASSWORD':
                break;
            case 'CHECKWORD':
                break;
            default:
                if($v){
                    $arFields[$k] = $v;
                }
                break;
        }
    }

    $arFields['PASSWORD'] = 'blabla';
    $arFields['CHECKWORD'] = 'blabla';

	$user = new CUser;
    $ID = $user->Add($arFields);
    if (intval($ID) > 0){
        $arTotal['Занесено'] += 1;

        $connection->queryExecute("UPDATE b_user SET PASSWORD='".$sqlHelper->forSql ($value["PASSWORD"])."', CHECKWORD='".$sqlHelper->forSql ($value["CHECKWORD"])."' WHERE ID='".$ID."'");

    }else{
        $arTotal['Ошибки'] = $arTotal['Ошибки'].'
        '.$user->LAST_ERROR;
    }

}