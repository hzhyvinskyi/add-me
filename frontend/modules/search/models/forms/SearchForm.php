<?php
namespace frontend\modules\search\models\forms;

use yii\base\Model;
use frontend\modules\search\models\NewsSearch;

class SearchForm extends Model
{
    public $keyword;

    public function rules()
    {
        return [
            ['keyword', 'trim'],
            ['keyword', 'required'],
            ['keyword', 'string', 'min' => 3],
        ];
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function search()
    {
        if ($this->validate()) {
            $model = new NewsSearch();

            return $model->advancedSearch($this->keyword);
        }
    }
}
