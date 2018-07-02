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

    public function search()
    {
        if ($this->validate()) {
            $model = new NewsSearch();

            return $model->fulltextSearch($this->keyword);
        }
    }
}
