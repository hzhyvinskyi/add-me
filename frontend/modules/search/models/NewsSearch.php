<?php
namespace frontend\modules\search\models;

use Yii;

class NewsSearch
{
    /**
     * Finds coincidences via fulltext search
     * @param $keyword
     * @return array
     * @throws \yii\db\Exception
     */
    public function fulltextSearch($keyword)
    {
        $params = [
            ':keyword' => $keyword,
        ];

        $sql = "SELECT * FROM news WHERE MATCH (content) AGAINST (:keyword) LIMIT 20";

        return Yii::$app->db->createCommand($sql)->bindValues($params)->queryAll();
    }
}
