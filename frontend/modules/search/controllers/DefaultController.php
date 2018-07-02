<?php
namespace frontend\modules\search\controllers;

use Yii;
use yii\web\Controller;
use frontend\modules\search\models\forms\SearchForm;

/**
 * Default controller for the `search` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/index']);
        }

        $model = new SearchForm();

        $results = null;

        if ($model->load(Yii::$app->request->post())) {
            $results = $model->search();
        }

        return $this->render('index', [
            'model' => $model,
            'results' => $results,
        ]);
    }
}
