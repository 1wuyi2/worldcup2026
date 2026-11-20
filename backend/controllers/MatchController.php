<?php

/**
 * 后台赛程、对阵和赛果管理。
 * @author lzs
 * @date 2026-11-20
 */

namespace backend\controllers;

use common\models\FootballMatch;
use common\models\FootballStadium;
use common\models\FootballTeam;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class MatchController extends FootballCrudController
{
    protected $modelClass = FootballMatch::class;
    protected $sectionTitle = '比赛管理';

    protected function query(): ActiveQuery
    {
        return parent::query()->with(['homeTeam', 'awayTeam', 'stadium']);
    }

    protected function columns(): array
    {
        return [
            'match_time',
            ['label' => '对阵', 'value' => 'fixtureLabel'],
            ['attribute' => 'stadium_id', 'value' => 'stadium.name'],
            'stage',
            ['label' => '比分', 'value' => 'scoreLabel'],
            ['attribute' => 'status', 'value' => 'statusLabel'],
        ];
    }

    protected function fields(): array
    {
        $teams = FootballTeam::find()->orderBy('name')->all();
        $stadiums = FootballStadium::find()->orderBy(['city' => SORT_ASC, 'name' => SORT_ASC])->all();
        $teamField = ['items' => ArrayHelper::map($teams, 'id', 'name'), 'options' => ['prompt' => '请选择球队']];
        $scoreField = ['type' => 'number', 'options' => ['min' => 0]];

        return [
            'home_team_id' => $teamField,
            'away_team_id' => $teamField,
            'stadium_id' => [
                'items' => ArrayHelper::map($stadiums, 'id', static function (FootballStadium $stadium) {
                    return $stadium->city . ' · ' . $stadium->name;
                }),
                'options' => ['prompt' => '暂无场馆'],
            ],
            'match_time' => ['hint' => 'UTC，格式为 YYYY-MM-DD HH:MM:SS。前台自动显示北京时间。'],
            'stage' => [],
            'home_score' => $scoreField,
            'away_score' => $scoreField,
            'status' => ['items' => FootballMatch::STATUS_LABELS],
            'result_note' => ['hint' => '可填写点球大战结果等补充说明。'],
            'source_match_key' => [],
            'source_url' => ['type' => 'url'],
        ];
    }
}
