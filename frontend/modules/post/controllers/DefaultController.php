<?php
namespace frontend\modules\post\controllers;

use frontend\models\Feed;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use frontend\models\User;
use frontend\models\Post;
use frontend\models\Comment;
use frontend\modules\post\models\forms\PostForm;
use frontend\modules\post\models\forms\CommentForm;

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
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $comments = Comment::getCommentsByPostId($id);

        $post = Post::getPostById($id);

        if (!Yii::$app->user->isGuest) {
            $model = new CommentForm($post, $currentUser);

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Comment has been added');

                return $this->refresh();
            }
        }

        return $this->render('view', [
            'currentUser' => $currentUser,
            'comments' => $comments,
            'post' => $post,
            'model' => isset($model) ? $model : null,
        ]);
    }

    /**
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionLike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $id = Yii::$app->request->post('id');

        $post = Post::getPostById($id);

        $post->like($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    /**
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionUnlike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $id = Yii::$app->request->post('id');

        $post = Post::getPostById($id);

        $post->unlike($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    /**
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateComment($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $comment = Comment::getCommentById($id);

        $post = Post::getPostById($comment->post_id);

        $model = new CommentForm($post, $currentUser);

        if ($currentUser->getId() === $comment->user_id) {
            if ($model->load(Yii::$app->request->post()) && $model->saveUpdatedComment($comment)) {
                Yii::$app->session->setFlash('success', 'Comment edited');

                return $this->redirect(['/post/default/view', 'id' => $post->getId()]);
            }
        } else {
            throw new NotFoundHttpException();
        }

        return $this->render('editComment', [
            'currentUser' => $currentUser,
            'model' => $model,
            'comment' => $comment,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDeleteComment($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $model = new Comment();

        $comment = $model::getCommentById($id);

        $post = Post::getPostById($comment->post_id);

        if ($currentUser->getId() === $post->user_id && $model->deleteComment($comment)) {
            Yii::$app->session->setFlash('success', 'Comment deleted');

            return $this->redirect(['/post/default/view', 'id' => $post->getId()]);
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @return array|Response
     * @throws NotFoundHttpException
     */
    public function actionComplain()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        /* @var $currentUser \frontend\models\User */
        $currentUser = Yii::$app->user->identity;

        $post = Post::getPostById($id);

        if ($post->complain($currentUser)) {
            return [
                'success' => true,
                'text' => 'Post reported',
            ];
        }

        return [
            'success' => false,
            'text' => 'Error occurred',
        ];
    }
}
