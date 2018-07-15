<?php
namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\User;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use frontend\modules\user\models\forms\PictureForm;
use yii\web\UploadedFile;
use yii\web\Response;
use frontend\models\Post;
use frontend\modules\user\models\forms\UpdateForm;
use yii\filters\AccessControl;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['view', 'upload-picture', 'delete-picture', 'update', 'subscribe', 'unsubscribe'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function() {
                    throw new ForbiddenHttpException(Yii::t('error', 'You are not allowed to perform this action.'));
                }
            ],
        ];
    }

    /**
     * @param $nickname
     * @return string
     * @throws NotFoundHttpException
     */
	public function actionView($nickname)
	{
	    $currentUser = Yii::$app->user->identity;

	    $modelPicture = new PictureForm();

	    $currentUserPosts = Post::findCurrentUserPosts($currentUser->getId());

	    return $this->render('view', [
	        'user' => $this->findUser($nickname),
            'currentUser' => $currentUser,
            'modelPicture' => $modelPicture,
            'currentUserPosts' => $currentUserPosts,
        ]);
	}

    /**
     * Handles profile image upload via ajax request
     */
	public function actionUploadPicture()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PictureForm();

        $model->picture = UploadedFile::getInstance($model, 'picture');

        if ($model->validate()) {
            $user = Yii::$app->user->identity;

            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture);

            if ($user->save(false, ['picture'])) {
                return [
                    'success' => true,
                    'pictureUri' => Yii::$app->storage->getFile($user->picture),
                ];
            }
        }

        return ['success' => false, 'errors' => $model->getErrors()];
    }

    /**
     * Deletes profile picture
     * @return Response
     */
    public function actionDeletePicture()
    {
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        if ($currentUser->deletePicture()) {
            Yii::$app->session->setFlash('success', Yii::t('profile_view', 'Picture deleted'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('profile_view', 'Error occurred'));
        }

        return $this->redirect(['/user/profile/view', 'nickname' => $currentUser->getNickname()]);
    }

    /**
     * Updates user's data
     * @return string|Response
     */
    public function actionUpdate()
    {
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $model = new UpdateForm();

        if ($model->load(Yii::$app->request->post()) && $model->updateUser($currentUser))
        {
            Yii::$app->session->setFlash('success', Yii::t('profile_view', 'Your data has been updated successfully'));

            return $this->redirect(['/user/profile/view', 'nickname' => $currentUser->getNickname()]);
        }

        return $this->render('update', [
            'model' => $model,
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
