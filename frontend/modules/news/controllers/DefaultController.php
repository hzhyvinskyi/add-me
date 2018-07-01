<?php
namespace frontend\modules\news\controllers;

use Yii;
use frontend\models\News;
use yii\web\Controller;

/**
 * Default controller for the `news` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the view page for the module
     * @return string
     */
    public function actionView($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/index']);
        }

        $item = News::findNewsItem($id);

        return $this->render('view', [
            'item' => $item,
        ]);
    }
}
