<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
			    'attribute' => 'id',
				'format' => 'raw',
				'value' => function ($user) {
    			    /* @var $user \backend\models\User */
    			    return Html::a($user->getId(), ['view', 'id' => $user->getId()]);
				}
			],
            'username',
            [
				'attribute' => 'picture',
				'format' => 'raw',
				'value' => function ($user) {
    				/* @var $user \backend\models\User */
    			    return Html::a(Html::img($user->getPicture(), ['width' => '100px']), ['view', 'id' => $user->getId()]);
				},
			],
            'email:email',
            'created_at:datetime',
			[
				'attribute' => 'roles',
				'value' => function ($user) {
    			    /* @var $user \backend\models\User */
    			    return implode(', ', $user->getRoles());
				}
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
