<?php
namespace frontend\modules\user\models\forms;

use yii\base\Model;
use frontend\models\User;

class UpdateForm extends Model
{
    const MAX_NICKNAME_LENGTH = 30;
    const MAX_ABOUT_LENGTH = 300;

    public $nickname;
    public $about;

    public function rules()
    {
        return [
            ['nickname', 'string', 'max' => self::MAX_NICKNAME_LENGTH],
            ['about', 'string', 'max' => self::MAX_ABOUT_LENGTH],
        ];
    }

    /**
     *
     * @param User $user
     * @return bool
     */
    public function updateUser(User $user)
    {
        if ($this->validate()) {
            $user->nickname = $this->nickname;
            $user->about = $this->about;

            return $user->save(false, ['nickname', 'about', 'updated_at']);
        }

        return false;
    }
}
