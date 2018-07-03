<?php

/* @var $this \yii\web\View */
/* @var $model \frontend\modules\search\models\forms\SearchForm */
/* @var $results \frontend\modules\search\models\NewsSearch */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\helpers\HighlightHelper;

$this->title = 'News search';
$this->registerMetaTag([
	'name' => 'description',
	'content' => 'Mysql full-text search',
]);
?>

<h1>News search form</h1>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'keyword')->hint('example: voluptatem') ?>
    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end(); ?>

<?php if ($results): ?>
	<br>
    <?php foreach ($results as $item): ?>
        <h3>
			<a href="<?= Yii::$app->urlManager->createUrl(['/news/default/view', 'id' => $item['id']]) ?>">
				<?= $item['title'] ?>
			</a>
		</h3>
        <br>
        <?= HighlightHelper::process($model->keyword, $item['content']) ?>
        <hr>
    <?php endforeach; ?>
<?php elseif ($results > 1): ?>
	<br>
	<p>No search results</p>
<?php endif; ?>
