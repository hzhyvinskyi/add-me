<?php
namespace frontend\modules\search\models;

use frontend\models\News;
use Yii;
use yii\helpers\ArrayHelper;

class NewsSearch
{
    /**
     * Finds coincidences through Sphinx search engine
     * @param $keyword
     * @return array
     */
    public function advancedSearch($keyword)
    {
        $params = [
            ':keyword' => $keyword,
        ];

        $sql = "SELECT * FROM idx_news_content WHERE MATCH (:keyword) LIMIT 20 OPTION ranker=WORDCOUNT";

        $data = Yii::$app->sphinx->createCommand($sql)->bindValues($params)->queryAll();

        $ids = ArrayHelper::map($data, 'id', 'id');

        $data = News::find()->where(['id' => $ids])->asArray()->all();

        $data = ArrayHelper::index($data, 'id');

        $result = [];

        foreach ($ids as $element) {
            $result[] = [
                'id' => $element,
                'title' => $data[$element]['title'],
                'content' => $data[$element]['content'],
            ];
        }

        return $result;
    }
}
