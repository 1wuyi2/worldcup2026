<?php

/**
 * 比赛中的进球事件。
 * @author lzs
 * @date 2026-11-20
 */

namespace common\models;

class FootballMatchGoal extends FootballRecord
{
    public const TYPE_LABELS = [
        'normal' => '普通进球',
        'own_goal' => '乌龙球',
        'penalty' => '比赛内点球',
    ];

    public static function tableName()
    {
        return '{{%wc_football_match_goal}}';
    }

    public function rules()
    {
        return array_merge(parent::rules(), [
            [['scorer_name', 'minute_text'], 'trim'],
            [['match_id', 'team_id', 'scorer_name'], 'required'],
            [['match_id', 'team_id'], 'integer', 'min' => 1],
            ['match_id', 'exist', 'targetClass' => FootballMatch::class, 'targetAttribute' => 'id'],
            ['team_id', 'exist', 'targetClass' => FootballTeam::class, 'targetAttribute' => 'id'],
            ['team_id', 'validateScoringTeam'],
            ['scorer_name', 'string', 'max' => 100],
            ['minute_text', 'default'],
            ['minute_text', 'match', 'pattern' => '/\A[0-9]{1,3}(?:\+[0-9]{1,2})?\z/', 'message' => '请填写进球分钟，例如 42 或 90+3。'],
            ['goal_type', 'default', 'value' => 'normal'],
            ['goal_type', 'in', 'range' => array_keys(self::TYPE_LABELS)],
        ]);
    }

    public function validateScoringTeam($attribute)
    {
        if ($this->hasErrors('match_id')) {
            return;
        }

        $match = $this->match;
        if ($match === null || !$this->isParticipant($match->home_team_id, $match->away_team_id)) {
            $this->addError($attribute, '记分归属球队必须是本场比赛的参赛队。');
        }
    }

    public function transactions()
    {
        return [self::SCENARIO_DEFAULT => self::OP_INSERT | self::OP_UPDATE];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $teams = FootballMatch::lockTeams($this->match_id);
        if ($teams === false || !$this->isParticipant($teams['home_team_id'], $teams['away_team_id'])) {
            $this->addError('team_id', '比赛对阵已变化，请重新选择记分归属球队。');
            return false;
        }

        return true;
    }

    private function isParticipant($homeTeamId, $awayTeamId)
    {
        return (string) $this->team_id === (string) $homeTeamId || (string) $this->team_id === (string) $awayTeamId;
    }

    public function attributeLabels()
    {
        return [
            'match_id' => '比赛',
            'team_id' => '记分归属球队',
            'scorer_name' => '进球者',
            'minute_text' => '进球分钟',
            'goal_type' => '类型',
            'source_url' => '资料来源',
        ];
    }

    public function getMatch()
    {
        return $this->hasOne(FootballMatch::class, ['id' => 'match_id']);
    }

    public function getTeam()
    {
        return $this->hasOne(FootballTeam::class, ['id' => 'team_id']);
    }

    public function getTypeLabel()
    {
        return self::TYPE_LABELS[$this->goal_type];
    }

    public function getMinuteOrder()
    {
        if ($this->minute_text === null || $this->minute_text === '') {
            return [PHP_INT_MAX, 0, $this->id];
        }

        $parts = explode('+', $this->minute_text, 2);
        return [(int) $parts[0], (int) ($parts[1] ?? 0), $this->id];
    }
}
