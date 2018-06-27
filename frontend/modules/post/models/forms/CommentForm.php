<?php
namespace frontend\modules\post\models\forms;

use yii\base\Model;
use frontend\models\User;
use frontend\models\Post;
use frontend\models\Comment;

class CommentForm extends Model
{
    const MAX_COMMENT_LENGTH = 3000;

    public $text;

    private $post;
    private $user;

    public function rules()
    {
        return [
            ['text', 'required'],
            ['text', 'string', 'max' => self::MAX_COMMENT_LENGTH],
        ];
    }

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;

        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if ($this->validate()) {
            $comment = new Comment();

            $comment->post_id = $this->post->getId();
            $comment->user_id = $this->user->getId();
            $comment->text = $this->text;

            if ($comment->save(false)) {
                Post::incrPostCommentCount($comment->post_id);

                return true;
            }

            return false;
        }
    }

    /**
     * @param $comment
     * @return mixed
     */
    public function saveEditedComment($comment)
    {
        if ($this->validate('text')) {

            $comment->text = $this->text;

            return $comment->save(false, ['text', 'updated_at']);
        }
    }
}
