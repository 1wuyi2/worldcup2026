<?php

/**
 * 模块 A（团队门户与互动模块）五张业务表的数据库迁移。
 *
 * @author wuyi
 * @date 2026-11-19
 */

use yii\db\Migration;

class m261119_190100_create_portal_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%wc_portal_team}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'name' => $this->string(100)->notNull(),
            'description' => $this->text(),
            'logo_path' => $this->string(255),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%wc_portal_member}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'team_id' => $this->bigInteger()->unsigned()->notNull(),
            'name' => $this->string(60)->notNull(),
            'student_no' => $this->string(20),
            'role' => $this->string(60),
            'bio' => $this->text(),
            'avatar_path' => $this->string(255),
            'sort_order' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-wc_portal_member-team_id-sort_order',
            '{{%wc_portal_member}}',
            ['team_id', 'sort_order']
        );
        $this->addForeignKey(
            'fk-wc_portal_member-team_id',
            '{{%wc_portal_member}}',
            'team_id',
            '{{%wc_portal_team}}',
            'id'
        );

        $this->createTable('{{%wc_portal_message}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'display_name' => $this->string(60)->notNull(),
            'content' => $this->text()->notNull(),
            'status' => $this->tinyInteger()->unsigned()->defaultValue(0),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-wc_portal_message-status-created_at',
            '{{%wc_portal_message}}',
            ['status', 'created_at']
        );

        $this->createTable('{{%wc_portal_reply}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'message_id' => $this->bigInteger()->unsigned()->notNull(),
            'content' => $this->text()->notNull(),
            'admin_user_id' => $this->bigInteger()->unsigned(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'uq-wc_portal_reply-message_id',
            '{{%wc_portal_reply}}',
            'message_id',
            true
        );
        $this->addForeignKey(
            'fk-wc_portal_reply-message_id',
            '{{%wc_portal_reply}}',
            'message_id',
            '{{%wc_portal_message}}',
            'id'
        );

        $this->createTable('{{%wc_portal_assignment}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'title' => $this->string(150)->notNull(),
            'category' => $this->string(20)->notNull(),
            'owner_label' => $this->string(60),
            'relative_path' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'status' => $this->tinyInteger()->unsigned()->defaultValue(0),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex(
            'idx-wc_portal_assignment-status-category',
            '{{%wc_portal_assignment}}',
            ['status', 'category']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-wc_portal_reply-message_id',
            '{{%wc_portal_reply}}'
        );
        $this->dropForeignKey(
            'fk-wc_portal_member-team_id',
            '{{%wc_portal_member}}'
        );

        $this->dropTable('{{%wc_portal_assignment}}');
        $this->dropTable('{{%wc_portal_reply}}');
        $this->dropTable('{{%wc_portal_message}}');
        $this->dropTable('{{%wc_portal_member}}');
        $this->dropTable('{{%wc_portal_team}}');
    }
}
