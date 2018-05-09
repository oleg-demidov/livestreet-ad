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
    'count_page_line' => 2,
    'per_page'  => 2,
    'count_alike' => 5
];


$config['$root$']['block']['ads_search'] = array(
    'action' => array('masters'),
    'blocks' => array(
        'right' => array(
            'component@ad:topic.block.ad-search'   => array('priority' => 2, 'plugin' => 'ad'),
            'component@ad:topic.block.ad-tags'   => array('priority' => 1)
        )
    )
);

$config['$root$']['block']['topic_ad'] = array(
    'action' => array('blog' => ['{topic}']),
    'blocks' => array(
        'right' => array(
            'component@ad:topic.block.user-author'   => array('priority' => 10),
            'component@user.block.actions'   => array('priority' => 9),
            'component@ad:user.block.contacts'   => array('priority' => 8),
            'component@ad:topic.block.alike-topics'   => ['priority' => 7]
        )
    )
);

return $config;