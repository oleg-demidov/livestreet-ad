<?php
/**
 * Таблица БД
 */
//$config['$root$']['db']['table']['page_main_page'] = '___db.table.prefix___page';
/**
 * Роутинг
 */
$config['router']['page'] = 'masters';

$config['$root$']['router']['page'][$config['router']['page']] = 'PluginAd_ActionAds';

$config['acl'] = [
    'user' => [
        'category' => [
            'max' => 3,
            'min' => 1
        ],
        'choose_ad_blog' => false
    ]
];

$config['topic'] = [
    'count_page_line' => 10,
    'per_page'  => 10
];


$config['$root$']['block']['ads_search'] = array(
    'action' => array('masters'),
    'blocks' => array(
        'right' => array(
            'component@ad:topic.block-topic-ad-search'   => array('priority' => 300, 'plugin' => 'ad')
        )
    )
);

return $config;