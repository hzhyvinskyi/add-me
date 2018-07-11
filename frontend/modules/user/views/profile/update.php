<?php

/* @var $this \yii\web\View */
/* @var $model \frontend\modules\user\models\forms\UpdateForm */
/* @var $currentUser \frontend\models\User */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = $currentUser->username;
?>

<div class="row">
	<div class="col-sm-12">
		<h3><?= Html::encode($this->title) . ' - ' . Yii::t('user_update', 'Profile editing') ?></h3>
		<br>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'nickname')->textInput(['value' => $currentUser->nickname])->label(Yii::t('user_update', 'Nickname')) ?>

        <?= $form->field($model, 'about')->textarea(['rows' => 6, 'value' => $currentUser->about])->label(Yii::t('user_update', 'About')) ?>

        <?= Html::submitButton(Yii::t('user_update', 'Update'), ['class' => 'btn btn-success']) ?>&nbsp;&nbsp;

        <?= Html::a(Yii::t('user_update', 'Go back'), ['/user/profile/view', 'nickname' => $currentUser->getNickname()], ['class' => 'btn btn-danger']) ?>

        <?php ActiveForm::end(); ?>
	</div>
</div>
