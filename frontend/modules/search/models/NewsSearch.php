<?php
namespace frontend\modules\search\models;

use Yii;

class NewsSearch
{
    public function fulltextSearch($keyword)
    {
        $params = [
            ':keyword' => $keyword,
        ];

        $sql = "SELECT * FROM news WHERE MATCH (content) AGAINST (:keyword) LIMIT 20";

        return Yii::$app->db->createCommand($sql)->bindValues($params)->queryAll();
    }
}
