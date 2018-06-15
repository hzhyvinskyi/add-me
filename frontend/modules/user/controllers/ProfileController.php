<?php
namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use yii\web\NotFoundHttpException;

class ProfileController extends Controller
{
    /**
     * @param $nickname
     * @return string
     * @throws NotFoundHttpException
     */
	public function actionView($nickname)
	{
	    return $this->render('view', [
	        'user' => $this->findUser($nickname),
        ]);
	}

    /**
     * @param $nickname
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
	private function findUser($nickname)
    {
        if ($user = User::find()->where(['nickname' => $nickname])
            ->orWhere(['id' => $nickname])->one()) {
            return $user;
        }

        throw new NotFoundHttpException('Page not found.');
    }
}
