<?php

/* @var $currentUser \frontend\models\User */
/* @var $model \frontend\modules\post\models\forms\CommentForm */
/* @var $comment \frontend\models\Comment */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="row">

    <div class="col-md-12">

		<?php $form = ActiveForm::begin() ?>

			<?= $form->field($model, 'text')->textarea(['rows' => 10, 'value' => $comment->text])->label('Edit a comment') ?>

			<?= Html::submitButton('Save changes', ['class' => 'btn btn-success']) ?>

		<?php ActiveForm::end() ?>

    </div>

</div>
