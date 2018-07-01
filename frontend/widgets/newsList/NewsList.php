<?php
namespace frontend\widgets\newsList;

use Yii;
use yii\base\Widget;
use frontend\models\News;

class NewsList extends Widget
{
    public $showLimit = null;

    public function run()
    {
        $max = Yii::$app->params['newsLimit'];

        if ($this->showLimit) {
            $max = $this->showLimit;
        }

        $list = News::getNewsList($max);

        return $this->render('news', [
            'list' => $list,
        ]);
    }
}
