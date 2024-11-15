<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if ($arParams['PERMISSION'] <= "R") die();


$refOptions = \App\Settings::getOptionsInfo();
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

}

?>
<form action="" method="post">
    <input type="hidden" name="agm-gadget" value="Y">
    <input type="hidden" name="agm-gadget-id" value="<?=$id?>">
    <?=bitrix_sessid_post()?>
    <h3>Настройки</h3>
    <table>
        <?foreach ($refOptions as $CodeOption=>$dctOption): if (!$dctOption['title'] || !$dctOption['type']) continue;?>
        <tr>
            <td class="adm-detail-content-cell-l"><?=$dctOption['title'];?></td>
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
                        <?if (\App\Settings::getOption($CodeOption)):?>checked<?endif;?> 
                        name="x_app_options[<?=$CodeOption?>]" 
                        value="<?=str_replace('"','',\App\Settings::getOption($CodeOption))?>"
                    />
                <?elseif($dctOption['type'] == 'integer'):?>
                <input 
                        type="num" 
                        <?if (\App\Settings::getOption($CodeOption)):?>checked<?endif;?> 
                        name="x_app_options[<?=$CodeOption?>]" 
                        value="<?=\App\Settings::getOption($CodeOption)?>"
                    />
                <?endif?>
            </td>
        </tr>
        <?endforeach?>
        <?/*
        <tr>
            <td class="adm-detail-content-cell-l">Выключить онлайн кредитование для раздела Уценка</td>
            <td class="adm-detail-content-cell-r"><input type="checkbox" <? if ($arResult['ONLINE_CREDIT_UC'] == 1):?>checked<? endif; ?> name="ONLINE_CREDIT_UC" value="1" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Скрыть цены на сайте</td>
            <td class="adm-detail-content-cell-r"><input type="checkbox" <? if ($arResult['HIDE_PRICES'] == 1):?>checked<? endif; ?> name="HIDE_PRICES" value="1" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Фраза для вывода вместо цены</td>
            <td class="adm-detail-content-cell-r"><input type="text" name="PRICE_PHRASE" value="<?=$arResult['PRICE_PHRASE'];?>" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Отключить оформление заказа</td>
            <td class="adm-detail-content-cell-r"><input type="checkbox" <? if ($arResult['ORDER_OFF'] == 1):?>checked<? endif; ?> name="ORDER_OFF" value="1" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Процент увеличения Второй цены</td>
            <td class="adm-detail-content-cell-r"><input type="number" name="SPRICE_PERCENT" value="<?=$arResult['SPRICE_PERCENT'];?>" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Ограничить количество покупки 1 товара</td>
            <td class="adm-detail-content-cell-r"><input type="checkbox" <? if ($arResult['LIMIT_QUANTITY'] == 1):?>checked<? endif; ?> name="LIMIT_QUANTITY" value="1" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Значение ограничения количества покупки одного товара</td>
            <td class="adm-detail-content-cell-r"><input type="number" name="LIMIT_VALUE" value="<?=$arResult['LIMIT_VALUE'];?>" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Включить предупреждение об изменении характеристик производителем</td>
            <td class="adm-detail-content-cell-r"><input type="checkbox" <? if ($arResult['CHARACTERISTICS_ON'] == 1):?>checked<? endif; ?> name="CHARACTERISTICS_ON" value="1" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Фраза об изменении характеристик производителем</td>
            <td class="adm-detail-content-cell-r"><input type="text" name="CHARACTERISTICS_PHRASE" value="<?=$arResult['CHARACTERISTICS_PHRASE'];?>" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Текст о цене в карточке товара</td>
            <td class="adm-detail-content-cell-r"><textarea cols="20" rows="5" type="text" name="PRICE_TEXT_PRODUCT"><?=$arResult['PRICE_TEXT_PRODUCT'];?></textarea></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Адрес доступа DaData</td>
            <td class="adm-detail-content-cell-r"><input type="text" name="DADATA_URL" value="<?=$arResult['DADATA_URL'];?>" /></td>
        </tr>
        <tr>
            <td class="adm-detail-content-cell-l">Токен доступа DaData</td>
            <td class="adm-detail-content-cell-r"><input type="text" name="DADATA_TOKEN" value="<?=$arResult['DADATA_TOKEN'];?>" /></td>
        </tr>
        */?>
    </table>
    <input type="submit" value="Сохранить" class="adm-btn-save" />
</form>