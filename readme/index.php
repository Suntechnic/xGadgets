<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if ($arParams['PERMISSION'] < "R") die();

$arGadget['INSTANCE_UID'] = randString(8);

$DirPath = $arGadget['SETTINGS']['DIRPATH'];
if (!$DirPath) $DirPath = '/local/.README';
$SysDirPath = \Bitrix\Main\Application::getDocumentRoot().$DirPath;

$lstFiles = [];
foreach (glob($SysDirPath.'/*.md') as $FileName) {
    $lstFiles[] = $FileName;
}











$lstTabs = [];
foreach ($lstFiles as $I=>$SysFilePath) {
    $lstTabs[] = [
                'DIV' => md5($SysFilePath),
                'TAB' => basename($SysFilePath),
                'INDEX' => $I
            ];
}

?>



<?
if (count($lstTabs) > 1) {
    $tabControl = new \CAdminViewTabControl('tabControl_readme_'.$arGadget['INSTANCE_UID'], $lstTabs);
    $tabControl->Begin();

    include(__DIR__.'/vendor/autoload.php');

    foreach ($lstTabs as $dctTab) { $tabControl->BeginNextTab();

        $SysDirPath = $lstFiles[$dctTab['INDEX']];
        $StrFile = file_get_contents($SysDirPath);

        echo \Michelf\Markdown::defaultTransform($StrFile);
    }
    $tabControl->End();
}
