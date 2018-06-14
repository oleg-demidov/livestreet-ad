<?php
/**
 * Таблица БД
 */
//$config['$root$']['db']['table']['page_main_page'] = '___db.table.prefix___page';
/**
 * Роутинг
 */
$config['router']['page'] = 'masters';

Config::Set('router.page.community', 'ActionIndex');
//$config['$root$']['router']['page']['community'] = 'ActionIndex';
$config['$root$']['router']['page']['index'] = 'PluginAd_ActionIndex';

$config['$root$']['router']['page'][$config['router']['page']] = 'PluginAd_ActionAds';
$config['$root$']['db']['table']['topic_ad'] = '___db.table.prefix___topic';

$config['country_code'] = 'kz'; //Отсекает поиск по другим регионам

$config['acl'] = [
    'user' => [
        'category' => [
            'max' => 3,
            'min' => 1
        ],
        'choose_ad_blog' => false
    ]
];

$config['menu'] = [
    'position' => 1
];

$config['topic'] = [
    'count_page_line' => 5,
    'per_page'  => 10,
    'count_alike' => 5,
    'count_words_item' => 20
];


$config['$root$']['block']['ads_search'] = array(
    'action' => array('masters'),
    'blocks' => array(
        'right' => array(
            'component@ad:topic.block.ad-search'   => array('priority' => 2, 'plugin' => 'ad'),
            'component@ad:topic.block.ad-tags'   => array('priority' => 1),
            'component@ad:topic.block.button-add'   => ['priority' => 11]
        )
    )
);

$config['$root$']['block']['topic_ad'] = array(
    'action' => array('blog' => ['{topic}']),
    'blocks' => array(
        'right' => array(
            'component@ad:topic.block.user-author'   => array('priority' => 10),
            'component@user.block.actions'   => array('priority' => 9),
            'component@ad:user.block.contacts'   => array('priority' => 8)
        )
    )
);

$config['$root$']['block']['rule_topic_type'] = array(
    'action' => array(
        'content' => array('add2', 'edit2'),
    )
);

/**
 * Настройки вывода блоков
 */
$config['$root$']['block']['rule_index_blog'] = array(
    'action' => array(
        'community',
        'blog' => array('{topics}', '{blog}')
    ),
    'blocks' => array(
        'right' => array(
            'activityRecent' => array('priority' => 100),
            'topicsTags'   => array('priority' => 50),
            'blogs'  => array('params' => array(), 'priority' => 1)
        )
    ),
    'clear'  => false,
);

return $config;