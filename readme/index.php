<?php

/**
 * @var array $arParams
 */

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

echo '<div class="readme"><div class="readme-tabs-scroll">';
$tabControl->Begin();
echo '</div>';

include(__DIR__.'/vendor/autoload.php');

$lstParserMD = ['parsedown','mmd'];

foreach ($lstTabs as $dctTab) { $tabControl->BeginNextTab();

    $FileName = $lstFiles[$dctTab['INDEX']];
    $StrFile = file_get_contents($FileName);
    

    if (preg_match('/^\[\/\/\]:#\(([^)]+)\)/', $StrFile, $matches)) {
        $ParserMD = $matches[1];
        if (!in_array($ParserMD, $lstParserMD)) $ParserMD = $lstParserMD[0];
    } else $ParserMD = $lstParserMD[0];

    if ($ParserMD == 'parsedown') {
        if (!isset($parsedown)) $parsedown = new Parsedown();
        echo $parsedown->text($StrFile);
    } elseif ($ParserMD == 'mmd') {
        echo \Michelf\Markdown::defaultTransform($StrFile);
    } else {
        echo $StrFile;
    }
    
    
}
$tabControl->End();
?>
</div>
<style>
    .readme {
        max-width: 640px;
    }

    .readme img {
        all: revert;
        width: 100% !important;
        height: auto !important;
        object-fit: contain !important;
        display: block !important;
    }

    .readme-tabs-scroll {
        max-width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
    }

    .readme-tabs-scroll .adm-detail-tabs-block,
    .readme-tabs-scroll .adm-detail-tabs-block > table {
        width: max-content;
        min-width: 100%;
    }

    .readme-tabs-scroll .adm-detail-tab {
        white-space: nowrap;
    }
</style>
