<?php
/**
 * Таблица БД
 */
//$config['$root$']['db']['table']['page_main_page'] = '___db.table.prefix___page';
/**
 * Роутинг
 */
$config['$root$']['router']['page']['masters'] = 'PluginAd_ActionMasters';

$config['acl'] = [
    'user' => [
        'category' => [
            'max' => 3,
            'min' => 1
        ],
        'choose_ad_blog' => false
    ]
];

return $config;