<?php

/**
 * 为模块 A 作业和留言增加成员归属、发布来源及后台操作人员字段。
 *
 * @author wuyi
 * @date 2026-11-26
 */

use yii\db\Migration;

class m261126_150000_add_portal_publisher_relations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%wc_portal_assignment}}',
            'member_id',
            $this->bigInteger()->unsigned()->null()->after('category')
        );
        $this->createIndex(
            'idx-wc_portal_assignment-member_id',
            '{{%wc_portal_assignment}}',
            'member_id'
        );
        $this->addForeignKey(
            'fk-wc_portal_assignment-member_id',
            '{{%wc_portal_assignment}}',
            'member_id',
            '{{%wc_portal_member}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addColumn(
            '{{%wc_portal_message}}',
            'member_id',
            $this->bigInteger()->unsigned()->null()->after('content')
        );
        $this->addColumn(
            '{{%wc_portal_message}}',
            'source_type',
            $this->tinyInteger()->unsigned()->notNull()->defaultValue(0)->after('member_id')
        );
        $this->addColumn(
            '{{%wc_portal_message}}',
            'created_by_user_id',
            $this->integer()->null()->after('source_type')
        );
        $this->createIndex(
            'idx-wc_portal_message-member_id',
            '{{%wc_portal_message}}',
            'member_id'
        );
        $this->createIndex(
            'idx-wc_portal_message-source-status-created_at',
            '{{%wc_portal_message}}',
            ['source_type', 'status', 'created_at']
        );
        $this->createIndex(
            'idx-wc_portal_message-created_by_user_id',
            '{{%wc_portal_message}}',
            'created_by_user_id'
        );
        $this->addForeignKey(
            'fk-wc_portal_message-member_id',
            '{{%wc_portal_message}}',
            'member_id',
            '{{%wc_portal_member}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-wc_portal_message-member_id',
            '{{%wc_portal_message}}'
        );
        $this->dropIndex(
            'idx-wc_portal_message-created_by_user_id',
            '{{%wc_portal_message}}'
        );
        $this->dropIndex(
            'idx-wc_portal_message-source-status-created_at',
            '{{%wc_portal_message}}'
        );
        $this->dropIndex(
            'idx-wc_portal_message-member_id',
            '{{%wc_portal_message}}'
        );
        $this->dropColumn('{{%wc_portal_message}}', 'created_by_user_id');
        $this->dropColumn('{{%wc_portal_message}}', 'source_type');
        $this->dropColumn('{{%wc_portal_message}}', 'member_id');

        $this->dropForeignKey(
            'fk-wc_portal_assignment-member_id',
            '{{%wc_portal_assignment}}'
        );
        $this->dropIndex(
            'idx-wc_portal_assignment-member_id',
            '{{%wc_portal_assignment}}'
        );
        $this->dropColumn('{{%wc_portal_assignment}}', 'member_id');
    }
}
