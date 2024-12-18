<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if ($arParams['PERMISSION'] <= "R") die();

$arGadget['INSTANCE_UID'] = randString(8);

$refOptions = array_filter(\App\Settings::getOptionsInfo(), function ($dctO) {
        return ($dctO['title'] && $dctO['type']);
    });
// отфильтруем только опции с описанным типом и тайтлом


if (
        $_SERVER['REQUEST_METHOD'] == 'POST' 
        && $_REQUEST['agm-gadget'] == 'Y' 
        && $_REQUEST['agm-gadget-id'] == $id
    ) {
    foreach ($_REQUEST['x_app_options'] as $CodeOption=>$Value) {
        if ($refOptions[$CodeOption]) {
            \App\Settings::setOption($CodeOption,$Value);
        }
    }
    LocalRedirect('/bitrix/admin/index.php');
}




$lstTabs = [];
$refTabs = ['more' => ['index' => 0, 'options' => []]];
foreach ($refOptions as $CodeOption=>$dctOption) {
    if (!$dctOption['tab']) $dctOption['tab'] = 'Разное';
    $TabCode = md5($dctOption['tab']);
    if (!$refTabs[$TabCode]) {
        $refTabs[$TabCode] = ['index' => count($lstTabs), 'options' => []];
        $lstTabs[] = [
                'DIV' => $TabCode,
                'TAB' => $dctOption['tab']
            ];
    }

    $refTabs[$TabCode]['options'][] =  $CodeOption;
}


?>
<form action="" method="post">
    <input type="hidden" name="agm-gadget" value="Y">
    <input type="hidden" name="agm-gadget-id" value="<?=$id?>">
    <?=bitrix_sessid_post()?>

    <?
    if (count($lstTabs) > 1) {
        $tabControl = new \CAdminViewTabControl('tabControl_appoptions_'.$arGadget['INSTANCE_UID'], $lstTabs);
        $tabControl->Begin();
    }

    foreach ($lstTabs as $dctTab) {
        $lstOptionsCode = $refTabs[$dctTab['DIV']]['options'];
        if ($tabControl) $tabControl->BeginNextTab();
        ?>
        <table>
            <?foreach ($lstOptionsCode as $CodeOption): $dctOption = $refOptions[$CodeOption]?>
            <tr>
                <td class="adm-detail-content-cell-l">
                    <?if ($dctOption['hint']):?>
                    <span id="app_options_<?=$arGadget['INSTANCE_UID']?>_<?=$CodeOption?>">?</span>
                    <script>
                    BX.hint_replace(
                            BX('app_options_<?=$arGadget['INSTANCE_UID']?>_<?=$CodeOption?>'), 
                            '<?=CUtil::JSEscape($dctOption['hint']);?>'
                        );
                    </script>
                    <?endif?>
                    <?=$dctOption['title'];?>
                </td>
                <td class="adm-detail-content-cell-r">
                    <?if($dctOption['type'] == 'bool'):?>
                    <input 
                            type="hidden" 
                            name="x_app_options[<?=$CodeOption?>]" 
                            value="0"
                        />
                    <input 
                            type="checkbox" 
                            <?if (\App\Settings::getOption($CodeOption)):?>checked<?endif;?> 
                            name="x_app_options[<?=$CodeOption?>]" 
                            value="1"
                        />
                    <?elseif($dctOption['type'] == 'string'):?>
                    <input 
                            type="text"
                            name="x_app_options[<?=$CodeOption?>]" 
                            value="<?=str_replace('"','',\App\Settings::getOption($CodeOption))?>"
                        />
                    <?elseif($dctOption['type'] == 'text'):?>
                    <textarea
                            style="width:100%"
                            name="x_app_options[<?=$CodeOption?>]" 
                        ><?=\App\Settings::getOption($CodeOption)?></textarea>
                    <?elseif($dctOption['type'] == 'integer' || $dctOption['type'] == 'float'):?>
                    <input 
                            type="num"
                            name="x_app_options[<?=$CodeOption?>]" 
                            value="<?=\App\Settings::getOption($CodeOption)?>"
                        />
                    <?endif?>
                </td>
                
            </tr>
            <?if ($dctOption['exampleMethod']):?>
            <tr><td colspan="2">
                <?=$dctOption['exampleMethod']()?>
            </td></tr>
            <?endif?>
            <?endforeach?>
        </table>
        <?
    }
    if ($tabControl) $tabControl->End();
    ?>
    <input type="submit" value="Сохранить" class="adm-btn-save" />
</form>