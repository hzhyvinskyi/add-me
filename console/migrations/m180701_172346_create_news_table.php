<?php

use yii\db\Migration;

/**
 * Handles the creation of table `news`.
 */
class m180701_172346_create_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'short_content' => $this->text(),
            'content' => $this->text(),
            'picture' => $this->string()->notNull(),
            'author' => $this->string(50),
            'created_at' => $this->integer()->notNull(),
            'status' => $this->integer(2)->defaultValue(1),
        ]);

        $this->createIndex(
            'idx-news-title',
            'news',
            'title'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropIndex(
            'idx-news-title',
            'news'
        );

        $this->dropTable('news');
    }
}
