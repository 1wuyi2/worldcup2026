<?php

/**
 * 后台比赛场馆管理。
 * @author lzs
 * @date 2026-11-20
 */

namespace backend\controllers;

use common\models\FootballStadium;

class StadiumController extends FootballCrudController
{
    protected $modelClass = FootballStadium::class;
    protected $sectionTitle = '场馆管理';

    protected function columns(): array
    {
        return ['name', 'city', 'capacity:integer'];
    }

    protected function fields(): array
    {
        return [
            'name' => [],
            'city' => [],
            'capacity' => ['type' => 'number', 'options' => ['min' => 0]],
            'source_url' => ['type' => 'url'],
        ];
    }
}
