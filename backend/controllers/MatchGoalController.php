<?php

/**
 * 后台比赛进球管理。
 * @author lzs
 * @date 2026-11-20
 */

namespace backend\controllers;

use common\models\FootballMatch;
use common\models\FootballMatchGoal;
use common\models\FootballTeam;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class MatchGoalController extends FootballCrudController
{
    protected $modelClass = FootballMatchGoal::class;
    protected $sectionTitle = '进球管理';

    protected function query(): ActiveQuery
    {
        return parent::query()->with(['match.homeTeam', 'match.awayTeam', 'team']);
    }

    protected function columns(): array
    {
        return [
            ['attribute' => 'match_id', 'value' => 'match.fixtureLabel'],
            ['attribute' => 'team_id', 'value' => 'team.name'],
            'scorer_name',
            'minute_text',
            ['attribute' => 'goal_type', 'value' => 'typeLabel'],
        ];
    }

    protected function fields(): array
    {
        $matches = FootballMatch::find()->with(['homeTeam', 'awayTeam'])->orderBy(['match_time' => SORT_DESC])->all();
        $teams = FootballTeam::find()->orderBy('name')->all();

        return [
            'match_id' => [
                'items' => ArrayHelper::map($matches, 'id', static function (FootballMatch $match) {
                    return $match->shanghaiKickoff . ' · ' . $match->fixtureLabel;
                }),
                'options' => ['prompt' => '请选择比赛'],
            ],
            'team_id' => [
                'items' => ArrayHelper::map($teams, 'id', 'name'),
                'options' => ['prompt' => '请选择参赛球队'],
                'hint' => '乌龙球选择得分一方。点球大战的罚球请在赛果中另行说明。',
            ],
            'scorer_name' => [],
            'minute_text' => ['hint' => '例如 42 或 90+3。'],
            'goal_type' => ['items' => FootballMatchGoal::TYPE_LABELS],
            'source_url' => ['type' => 'url'],
        ];
    }
}
