<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if ($arParams['PERMISSION'] < "R") die();

$arGadget['INSTANCE_UID'] = randString(8);

$DirPath = $arGadget['SETTINGS']['DIRPATH'];
if (!$DirPath) $DirPath = '/local/.README';
$SysDirPath = \Bitrix\Main\Application::getDocumentRoot().$DirPath;

$lstFiles = [];
foreach (glob($SysDirPath.'/*.md') as $FileName) {
    if(substr(file_get_contents($FileName),0,14) == '[//]:#(hidden)') continue;
    $lstFiles[] = $FileName;
}











$lstTabs = [];
foreach ($lstFiles as $I=>$SysFilePath) {
    $lstTabs[] = [
                'DIV' => md5($SysFilePath),
                'TAB' => substr(basename($SysFilePath),0,-3),
                'INDEX' => $I
            ];
}


$tabControl = new \CAdminViewTabControl('tabControl_readme_'.$arGadget['INSTANCE_UID'], $lstTabs);
$tabControl->Begin();

include(__DIR__.'/vendor/autoload.php');

foreach ($lstTabs as $dctTab) { $tabControl->BeginNextTab();

    $FileName = $lstFiles[$dctTab['INDEX']];
    $StrFile = file_get_contents($FileName);

    echo \Michelf\Markdown::defaultTransform($StrFile);
}
$tabControl->End();

