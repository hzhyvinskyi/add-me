<?php

/* @var $currentUser \frontend\models\User */
/* @var $model \frontend\modules\post\models\forms\CommentForm */
/* @var $comment \frontend\models\Comment */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('edit_comment','Edit a comment');
?>

<div class="row">

    <div class="col-md-12">

		<?php $form = ActiveForm::begin() ?>

			<?= $form->field($model, 'text')->textarea(['rows' => 8, 'value' => $comment->text])->label(Yii::t('edit_comment', $this->title)) ?>

			<?= Html::submitButton(Yii::t('edit_comment', 'Save changes'), ['class' => 'btn btn-success']) ?>

		<?php ActiveForm::end() ?>

    </div>

</div>
