<?php

use yii\db\Migration;

/**
 * Class m180709_174354_alter_table_feed_add_fk_feed_post_id
 */
class m180709_174354_alter_table_feed_add_fk_feed_post_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createIndex(
            'idx-feed-post_id',
            '{{%feed}}',
            'post_id'
        );

        $this->addForeignKey(
            'fk-feed-post_id',
            '{{%feed}}',
            'post_id',
            '{{%post}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropForeignKey(
            'fk-feed-post_id',
            '{{%feed}}'
        );

        $this->dropIndex(
            'idx-feed-post_id',
            '{{%feed}}'
        );
    }
}
