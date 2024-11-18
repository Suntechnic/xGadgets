<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

$arParameters = [
        'PARAMETERS'=> [
                'DIRPATH' => [
                        'PARENT' => 'BASE',
                        'NAME' => 'Путь к каталогу README',
                        'TYPE' => 'STRING',
                        'DEFAULT' => '/local/.README',
                        'REFRESH' => 'Y',
                    ]
            ],
    ];

return $arParameters;