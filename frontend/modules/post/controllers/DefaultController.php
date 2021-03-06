<?php
namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\web\NotFoundHttpException;
use frontend\models\User;
use frontend\models\Post;
use frontend\models\Comment;
use frontend\modules\post\models\forms\PostForm;
use frontend\modules\post\models\forms\CommentForm;
use yii\filters\AccessControl;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'view', 'like', 'unlike', 'update-comment', 'delete-comment', 'complain'],
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
     * Renders the create view for the module
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new PostForm(Yii::$app->user->identity);

        if ($model->load(Yii::$app->request->post())) {
            $model->picture = UploadedFile::getInstance($model, 'picture');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('create_post', 'Post created successfully!'));

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
                Yii::$app->session->setFlash('success', Yii::t('post_view', 'Comment has been added'));

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
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $comment = Comment::getCommentById($id);

        $post = Post::getPostById($comment->post_id);

        $model = new CommentForm($post, $currentUser);

        if ($currentUser->getId() === $comment->user_id) {
            if ($model->load(Yii::$app->request->post()) && $model->saveUpdatedComment($comment)) {
                Yii::$app->session->setFlash('success', Yii::t('edit_comment', 'Comment updated'));

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
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $model = new Comment();

        $comment = $model::getCommentById($id);

        $post = Post::getPostById($comment->post_id);

        if ($currentUser->getId() === $post->user_id && $model->deleteComment($comment)) {
            Yii::$app->session->setFlash('success', Yii::t('post_view', 'Comment deleted'));

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
        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        /* @var $currentUser \frontend\models\User */
        $currentUser = Yii::$app->user->identity;

        $post = Post::getPostById($id);

        if ($post->complain($currentUser)) {
            return [
                'success' => true,
                'text' => Yii::t('index', 'Post reported'),
            ];
        }

        return [
            'success' => false,
            'text' => Yii::t('index', 'Error occurred'),
        ];
    }
}
