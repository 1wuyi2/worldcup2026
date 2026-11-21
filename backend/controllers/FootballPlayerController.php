<?php

/**
 * 后台球员管理。
 * @author lzs
 * @date 2026-11-20
 */

namespace backend\controllers;

use common\models\FootballPlayer;
use common\models\FootballTeam;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

class FootballPlayerController extends FootballCrudController
{
    protected $modelClass = FootballPlayer::class;
    protected $sectionTitle = '球员管理';

    protected function query(): ActiveQuery
    {
        return parent::query()->with('team');
    }

    protected function columns(): array
    {
        return ['name', ['attribute' => 'team_id', 'value' => 'team.name'], 'number', 'position'];
    }

    protected function fields(): array
    {
        $teams = FootballTeam::find()->orderBy('name')->all();

        return [
            'team_id' => ['items' => ArrayHelper::map($teams, 'id', 'name'), 'options' => ['prompt' => '请选择球队']],
            'name' => [],
            'number' => ['type' => 'number', 'options' => ['min' => 0]],
            'position' => [],
            'source_url' => ['type' => 'url'],
        ];
    }
}
