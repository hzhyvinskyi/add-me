<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode(Yii::t('contact', $this->title)) ?></h1>

    <p>
        <?= Yii::t('contact', 'If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.') ?>
    </p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label(Yii::t('contact', 'Name')) ?>

                <?= $form->field($model, 'email')->label(Yii::t('contact', 'Email')) ?>

                <?= $form->field($model, 'subject')->label(Yii::t('contact', 'Subject')) ?>

                <?= $form->field($model, 'body')->textarea(['rows' => 6])->label(Yii::t('contact', 'Текс сообщения')) ?>

                <?= $form->field($model, 'verifyCode')->label(Yii::t('contact', 'Verification Code'))->widget(Captcha::className(), [
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('contact', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
