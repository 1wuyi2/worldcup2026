<?php

/**
 * 比赛时间、对阵、赛果和关联的进球。
 * @author lzs
 * @date 2026-11-20
 */

namespace common\models;

use DateTimeImmutable;
use DateTimeZone;

class FootballMatch extends FootballRecord
{
    public const STATUS_LABELS = [
        0 => '未开始',
        1 => '进行中',
        2 => '完赛',
        3 => '延期／取消',
    ];

    public static function tableName()
    {
        return '{{%wc_football_match}}';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['stage', 'match_time', 'source_match_key', 'result_note'], 'trim'],
            [['home_team_id', 'away_team_id', 'match_time', 'stage'], 'required'],
            [['home_team_id', 'away_team_id', 'stadium_id'], 'integer', 'min' => 1],
            ['home_team_id', 'exist', 'targetClass' => FootballTeam::class, 'targetAttribute' => 'id'],
            ['away_team_id', 'exist', 'targetClass' => FootballTeam::class, 'targetAttribute' => 'id'],
            ['away_team_id', 'compare', 'compareAttribute' => 'home_team_id', 'operator' => '!=', 'message' => '对阵双方必须不同。'],
            ['stadium_id', 'exist', 'targetClass' => FootballStadium::class, 'targetAttribute' => 'id'],
            ['match_time', 'datetime', 'format' => 'php:Y-m-d H:i:s', 'timeZone' => 'UTC'],
            ['stage', 'string', 'max' => 30],
            [['stadium_id', 'home_score', 'away_score', 'source_match_key', 'result_note'], 'default'],
            [['home_score', 'away_score'], 'integer', 'min' => 0, 'max' => 4294967295],
            ['status', 'default', 'value' => 0],
            ['status', 'in', 'range' => array_keys(self::STATUS_LABELS)],
            ['result_note', 'string', 'max' => 255],
            ['source_match_key', 'string', 'max' => 80],
            ['source_match_key', 'unique'],
        ]);
    }

    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_UPDATE];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!$insert && ($this->isAttributeChanged('home_team_id', false) || $this->isAttributeChanged('away_team_id', false))) {
            // 进球写入也先锁这条比赛，避免校验通过后对阵双方被并发修改。
            $teams = self::lockTeams($this->id);
            if ($teams === false || (string) $teams['home_team_id'] !== (string) $this->getOldAttribute('home_team_id')
                || (string) $teams['away_team_id'] !== (string) $this->getOldAttribute('away_team_id')) {
                $this->addError('home_team_id', '对阵信息已更新，请刷新页面后重试。');
                return false;
            }
            $existingGoal = self::getDb()->createCommand(
                'SELECT id FROM {{%wc_football_match_goal}}
                 WHERE match_id = :match AND team_id NOT IN (:home, :away) LIMIT 1 FOR UPDATE',
                [':match' => $this->id, ':home' => $this->home_team_id, ':away' => $this->away_team_id]
            )->queryScalar();
            if ($existingGoal !== false) {
                $this->addError('away_team_id', '现有进球归属于拟移除的球队，请先处理这些进球。');
                return false;
            }
        }

        return true;
    }

    public static function lockTeams($id)
    {
        return self::getDb()->createCommand(
            'SELECT home_team_id, away_team_id FROM {{%wc_football_match}} WHERE id = :id FOR UPDATE',
            [':id' => $id]
        )->queryOne();
    }

    public function attributeLabels()
    {
        return [
            'home_team_id' => '对阵一方',
            'away_team_id' => '对阵另一方',
            'stadium_id' => '比赛场馆',
            'match_time' => '开球时间（UTC）',
            'stage' => '比赛阶段',
            'home_score' => '第一队比分',
            'away_score' => '第二队比分',
            'status' => '状态',
            'result_note' => '赛果说明',
            'source_match_key' => '外部比赛编号',
            'source_url' => '资料来源',
        ];
    }

    public function getHomeTeam()
    {
        return $this->hasOne(FootballTeam::class, ['id' => 'home_team_id']);
    }

    public function getAwayTeam()
    {
        return $this->hasOne(FootballTeam::class, ['id' => 'away_team_id']);
    }

    public function getStadium()
    {
        return $this->hasOne(FootballStadium::class, ['id' => 'stadium_id']);
    }

    public function getGoals()
    {
        return $this->hasMany(FootballMatchGoal::class, ['match_id' => 'id']);
    }

    public function getOrderedGoals()
    {
        $goals = $this->goals;
        usort($goals, static function (FootballMatchGoal $left, FootballMatchGoal $right) {
            return $left->minuteOrder <=> $right->minuteOrder;
        });

        return $goals;
    }

    public function getFixtureLabel()
    {
        return $this->homeTeam->name . ' vs ' . $this->awayTeam->name;
    }

    public function getScoreLabel()
    {
        return $this->home_score !== null && $this->away_score !== null
            ? $this->home_score . ' - ' . $this->away_score
            : '暂无比分';
    }

    public function getStatusLabel()
    {
        return self::STATUS_LABELS[$this->status];
    }

    public function getShanghaiKickoff()
    {
        return (new DateTimeImmutable($this->match_time, new DateTimeZone('UTC')))
            ->setTimezone(new DateTimeZone('Asia/Shanghai'))
            ->format('Y-m-d H:i');
    }
}
