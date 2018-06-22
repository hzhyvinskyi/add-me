<?php
namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use frontend\models\Post;
use frontend\modules\post\models\forms\PostForm;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the create view for the module
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        $model = new PostForm(Yii::$app->user->identity);

        if ($model->load(Yii::$app->request->post())) {
            $model->picture = UploadedFile::getInstance($model, 'picture');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Post created successfully!');

                return $this->goHome();
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Renders the create view for the module
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'post' => $this->getPostId($id),
        ]);
    }

    /**
     * @param $id
     * @return Post|null
     * @throws NotFoundHttpException
     */
    private function getPostId($id)
    {
        if ($post = Post::findOne($id)) {
            return $post;
        }

        throw new NotFoundHttpException();
    }
}
