<?php

return [
    'menu' => [
        'master' => [
            'title' => 'Мастера'
        ]
    ],
    'ad' => [
        'form' => [
            'tab_specialization_title' => 'Выбрать специализацию',
            'tab_form_title' => 'Описание',
            'tab_properties_title' => 'Дополнительно',
            'tab_contact_title' => 'Контакты'
        ],
        'search_form' => [
            'text' => [
                'label' => 'Ключевое слово'
            ],
            'sort' => [
                'label' => '___sort.label___',
                'items' => [
                    'by_rating' => '___sort.by_rating___',
                    'by_price' => 'по цене',
                    'by_date_publish' => '___sort.by_date___'
                ]
            ],
            
            'toggle' => [
                'list' => 'Список',
                'list_title' => 'Показать списком',
                'map' => 'Карта',
                'map_title' => 'Показать на карте'
            ],
            'count_results' => 'Найден %%count%% объявление;Найдено %%count%% объявления;Найдено %%count%% объявлений',
            
        ],
        'block_search' => [
            'title' => 'Фильтр обьявлений',
            'button' => [
                'text' => 'Найти'
            ],
            'price' => [
                'label' => 'Цена',
                'from' => 'От',
                'to' => 'До'
            ]
        ],
        'block_contacts' => [
            'blankslate' => [
                'title' => 'Контактов нет'
            ]
        ],
        'block_alike_topics' => [
            'title' => 'Похожие объявления',
            
        ],
        'count_read' => 'Просмотров'
    ],
    'category' => [
        'name'  => 'Специализация'
    ]
];