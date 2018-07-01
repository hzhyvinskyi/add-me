<?php

/* @var $list \frontend\models\News */

?>

<h3>Latest news</h3>

<hr>

<?php foreach ($list as $item): ?>
    <h4>
        <a href="<?= Yii::$app->urlManager->createUrl(['/news/default/view', 'id' => $item->getId()]) ?>">
            <?= $item->title ?>
        </a>
    </h4>
    <p><?= $item->short_content ?></p>
	<hr>
<?php endforeach; ?>
