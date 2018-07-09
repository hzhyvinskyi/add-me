<?php

use yii\db\Migration;

/**
 * Class m180709_175246_alter_table_comment_add_fk_comment_post_id
 */
class m180709_175246_alter_table_comment_add_fk_comment_post_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->createIndex(
            'idx-comment-post_id',
            '{{%comment}}',
            'post_id'
        );

        $this->addForeignKey(
            'fk-comment-post_id',
            '{{%comment}}',
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
            'fk-comment-post_id',
            '{{%comment}}'
        );

        $this->dropIndex(
            'idx-comment-post_id',
            '{{%comment}}'
        );
    }
}
