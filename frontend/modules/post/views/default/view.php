<?php

/* @var $post \frontend\models\Post */

use yii\helpers\Html;
?>

<div class="post-default-index">
    <div class="row">
        <div class="col-md-12">
            <?php if ($post->user): ?>
                <h3><?= Html::encode($post->user->username) ?></h3>
            <?php endif; ?>
        </div>
        <div class="col-md-12">
            <img style="width:650px;" src="<?= Html::encode($post->getImage()) ?>">
        </div>
        <div class="col-md-12">
            <?= Html::encode($post->description) ?>
        </div>
    </div>
</div>
