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
	    $currentUser = Yii::$app->user->identity;

	    return $this->render('view', [
	        'user' => $this->findUser($nickname),
            'currentUser' => $currentUser,
        ]);
	}

    /**
     * @param $nickname
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
	private function findUser($nickname)
    {
        if ($user = User::find()->where('nickname=:nickname', [':nickname' => $nickname])->one()) {
            return $user;
        } elseif ($user = User::find()->where('id=:nickname', [':nickname' => $nickname])->one()) {
            return $user;
        }

        throw new NotFoundHttpException('Page not found.');
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionSubscribe($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $user = $this->findUserById($id);

        $currentUser->followUser($user);

        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUnsubscribe($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $user = $this->findUserById($id);

        $currentUser->unfollowUser($user);

        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }

    /**
     * @param $id
     * @return User|null
     * @throws NotFoundHttpException
     */
    private function findUserById($id)
    {
        if ($user = User::findOne($id)) {
            return $user;
        }

        throw new NotFoundHttpException();
    }
}
