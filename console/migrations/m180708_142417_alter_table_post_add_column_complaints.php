<?php

use yii\db\Migration;

/**
 * Class m180708_142417_alter_table_post_add_column_complaints
 */
class m180708_142417_alter_table_post_add_column_complaints extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->addColumn('{{%post}}', 'complaints', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropColumn('{{%post}}', 'complaints');
    }
}
