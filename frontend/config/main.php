<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        [
            'class' => 'frontend\components\LanguageSelector',
        ],
    ],
    'controllerNamespace' => 'frontend\controllers',
	'modules' => [
		'user' => [
			'class' => 'frontend\modules\user\Module',
		],
        'post' => [
            'class' => 'frontend\modules\post\Module',
        ],
        'news' => [
            'class' => 'frontend\modules\news\Module',
        ],
        'search' => [
            'class' => 'frontend\modules\search\Module',
        ],
	],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
			'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
			'hostInfo' => 'http://add-me.com',
            'rules' => [
                'profile/<nickname:\w+>' => 'user/profile/view',
                'post/<id:\d+>' => 'post/default/view',
                'post/edit-comment/<id:\d+>' => 'post/default/edit-comment',
                'post/delete-comment/<id:\d+>' => 'post/default/delete-comment',
                'news/item/<id:\d+>' => 'news/default/view',
            ],
        ],
        'feedService' => [
            'class' => 'frontend\components\FeedService',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                ],
            ],
        ],
    ],
    'params' => $params,
];
