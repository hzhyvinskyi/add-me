<?php

/* @var $model \frontend\modules\post\models\forms\PostForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="post-default-index">

	<h1>Create post</h1>

	<?php $form = ActiveForm::begin(); ?>
		<?= $form->field($model, 'picture')->fileInput() ?>
		<?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>
		<?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
	<?php ActiveForm::end(); ?>

</div>
