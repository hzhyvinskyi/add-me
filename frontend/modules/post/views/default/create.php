<?php

/* @var $this \yii\web\View */
/* @var $model \frontend\modules\post\models\forms\PostForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('create_post', 'Create post');
?>

<div class="post-default-index">

	<h1><?= Yii::t('create_post', $this->title) ?></h1>

	<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($model, 'picture')->fileInput()->label(Yii::t('create_post', 'Picture')) ?>
		<?= $form->field($model, 'description')->textarea(['rows' => 8])->label(Yii::t('create_post', 'Description')) ?>
		<?= Html::submitButton(Yii::t('create_post', 'Create'), ['class' => 'btn btn-primary']) ?>
	<?php ActiveForm::end(); ?>

</div>
