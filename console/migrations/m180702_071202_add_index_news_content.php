<?php

use yii\db\Migration;

/**
 * Class m180702_071202_add_index_news_content
 */
class m180702_071202_add_index_news_content extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->execute("ALTER TABLE news ADD FULLTEXT INDEX idx_content (content)");
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex('idx_content', 'news');
    }
}
