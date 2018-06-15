<?php

/* @var $this \yii\web\View */
/* @var $user \frontend\models\User */

use yii\helpers\Html;

$this->title = $user->username;
?>

<p>Hello!</p>
<p>It's <?= Html::encode($user->username) ?>'s page!</p>
