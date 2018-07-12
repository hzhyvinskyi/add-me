<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reported posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
				'attribute' => 'id',
			    'format' => 'raw',
				'value' => function ($post) {
    			    /* @var $post \backend\models\Post */
    			    return Html::a($post->id, ['view', 'id' => $post->id]);
				}
			],
            'user_id',
            [
				'attribute' => 'filename',
				'format' => 'raw',
				'value' => function ($post) {
                    /* @var $post \backend\models\Post */
    			    return Html::a(Html::img($post->getImage(), ['width' => '150px']), ['view', 'id' => $post->id]);
				},
			],
			[
				'attribute' => 'description',
			    'format' => 'raw',
				'contentOptions' => ['style' => 'width: 300px; max-width: 300px;'],
				'value' => function ($post) {
    			    /* @var $post \backend\models\Post */
    			    return nl2br(Html::encode(substr($post->description, 0, 30))) . '...';
				},
			],
            'created_at:datetime',
            'complaints',

            ['class' => 'yii\grid\ActionColumn',
				'contentOptions' => ['style' => 'text-align: center'],
				'template' => '{view}&nbsp;&nbsp;&nbsp;{approve}&nbsp;&nbsp;&nbsp;{delete}',
				'buttons' => [
					'approve' => function ($url, $post) {
    				    return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['approve', 'id' => $post->id]);
					}
				],
			],
        ],
    ]); ?>
</div>
