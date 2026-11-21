<?php

/**
 * 模块 C 球队、球员、场馆、比赛和进球表。
 * @author lzs
 * @date 2026-11-20
 */

use yii\db\Migration;

class m261120_233000_create_football_tables extends Migration
{
    public function safeUp()
    {
        $options = 'ENGINE=InnoDB DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';
        $this->createTable('{{%wc_football_team}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'name' => $this->string(100)->notNull(),
            'country_code' => $this->string(10)->notNull()->unique(),
            'group_name' => $this->string(10),
        ] + $this->metadata(), $options);

        $this->createTable('{{%wc_football_player}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'team_id' => $this->bigInteger()->unsigned()->notNull(),
            'name' => $this->string(100)->notNull(),
            'number' => $this->integer()->unsigned(),
            'position' => $this->string(30),
        ] + $this->metadata(), $options);
        $this->addForeignKey('fk-football-player-team', '{{%wc_football_player}}', 'team_id', '{{%wc_football_team}}', 'id');

        $this->createTable('{{%wc_football_stadium}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'name' => $this->string(100)->notNull(),
            'city' => $this->string(100)->notNull(),
            'capacity' => $this->integer()->unsigned(),
        ] + $this->metadata(), $options);

        $this->createTable('{{%wc_football_match}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'home_team_id' => $this->bigInteger()->unsigned()->notNull(),
            'away_team_id' => $this->bigInteger()->unsigned()->notNull(),
            'stadium_id' => $this->bigInteger()->unsigned(),
            'match_time' => $this->dateTime()->notNull(),
            'stage' => $this->string(30)->notNull(),
            'home_score' => $this->integer()->unsigned(),
            'away_score' => $this->integer()->unsigned(),
            'status' => $this->tinyInteger()->unsigned()->notNull()->defaultValue(0),
            'result_note' => $this->string(255),
            'source_match_key' => $this->string(80)->unique(),
        ] + $this->metadata(), $options);
        $this->addCheck('ck-football-match-teams', '{{%wc_football_match}}', '[[home_team_id]] <> [[away_team_id]]');
        $this->createIndex('idx-football-match-time', '{{%wc_football_match}}', 'match_time');
        $this->addForeignKey('fk-football-match-home', '{{%wc_football_match}}', 'home_team_id', '{{%wc_football_team}}', 'id');
        $this->addForeignKey('fk-football-match-away', '{{%wc_football_match}}', 'away_team_id', '{{%wc_football_team}}', 'id');
        $this->addForeignKey('fk-football-match-stadium', '{{%wc_football_match}}', 'stadium_id', '{{%wc_football_stadium}}', 'id');

        $this->createTable('{{%wc_football_match_goal}}', [
            'id' => $this->bigPrimaryKey()->unsigned(),
            'match_id' => $this->bigInteger()->unsigned()->notNull(),
            'team_id' => $this->bigInteger()->unsigned()->notNull(),
            'scorer_name' => $this->string(100)->notNull(),
            'minute_text' => $this->string(12),
            'goal_type' => $this->string(20)->notNull()->defaultValue('normal'),
        ] + $this->metadata(), $options);
        $this->addForeignKey('fk-football-goal-match', '{{%wc_football_match_goal}}', 'match_id', '{{%wc_football_match}}', 'id');
        $this->addForeignKey('fk-football-goal-team', '{{%wc_football_match_goal}}', 'team_id', '{{%wc_football_team}}', 'id');
    }

    public function safeDown()
    {
        $this->dropTable('{{%wc_football_match_goal}}');
        $this->dropTable('{{%wc_football_match}}');
        $this->dropTable('{{%wc_football_stadium}}');
        $this->dropTable('{{%wc_football_player}}');
        $this->dropTable('{{%wc_football_team}}');
    }

    private function metadata()
    {
        return [
            'source_url' => $this->string(500),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ];
    }
}
