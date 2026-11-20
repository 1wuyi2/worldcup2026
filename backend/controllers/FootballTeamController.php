<?php

/**
 * 后台国家队管理。
 * @author lzs
 * @date 2026-11-20
 */

namespace backend\controllers;

use common\models\FootballTeam;

class FootballTeamController extends FootballCrudController
{
    protected $modelClass = FootballTeam::class;
    protected $sectionTitle = '球队管理';

    protected function columns(): array
    {
        return ['name', 'country_code', 'group_name'];
    }

    protected function fields(): array
    {
        return [
            'name' => [],
            'country_code' => [],
            'group_name' => [],
            'source_url' => ['type' => 'url'],
        ];
    }
}
