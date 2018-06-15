<?php

/* @var $this \yii\web\View */
/* @var $user \frontend\models\User */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

$this->title = $user->username;
?>

<h3><?= Html::encode($user->username) ?></h3>
<p><?= HtmlPurifier::process($user->about) ?></p>
