<?php

/**
 * 公开的球队、球员和场馆资料。
 * @author lzs
 * @date 2026-11-20
 */

namespace frontend\controllers;
use common\models\FootballMatch;
use common\models\FootballPlayer;
use common\models\FootballStadium;
use common\models\FootballTeam;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class FootballController extends Controller
{
    public function actionTeams()
{
    $request = Yii::$app->request;

    // 1. 获取全部参赛球队数量。
    $totalTeams = (int) FootballTeam::find()->count();

    // 2. 获取数据库中实际存在的小组。
    $groups = FootballTeam::find()
        ->select('group_name')
        ->distinct()
        ->where(['not', ['group_name' => null]])
        ->andWhere(['<>', 'group_name', ''])
        ->orderBy(['group_name' => SORT_ASC])
        ->column();

    // 3. 获取并校验搜索参数。
    $keyword = $request->get('keyword', '');
    $selectedGroup = $request->get('group', '');

    $keyword = is_string($keyword)
        ? mb_substr(trim($keyword), 0, 100)
        : '';

    $selectedGroup = is_string($selectedGroup)
        && in_array($selectedGroup, $groups, true)
        ? $selectedGroup
        : '';

    // 4. 构造国家队查询。
    $query = FootballTeam::find();

    // 支持按国家队名称或 FIFA 球队代码搜索。
    if ($keyword !== '') {

        $query->andWhere([
            'or',
            ['like', 'name', $keyword],
            ['like', 'country_code', strtoupper($keyword)],
        ]);

    }

    // 按照所属小组进一步筛选。
    if ($selectedGroup !== '') {

        $query->andWhere([
            'group_name' => $selectedGroup,
        ]);

    }

    // 5. 按照小组和球队编号排序。
    $teams = $query
        ->orderBy([
            'group_name' => SORT_ASC,
            'id' => SORT_ASC,
        ])
        ->all();

    // 6. 将球队按照所属小组组织。
    $groupedTeams = [];

    foreach ($teams as $team) {

        $group = $team->group_name ?: '未分组';

        $groupedTeams[$group][] = $team;

    }

    // 7. 将查询结果传递给页面。
    return $this->render('teams', [
        'groupedTeams' => $groupedTeams,
        'groups' => $groups,
        'totalTeams' => $totalTeams,
        'totalGroups' => count($groups),
        'filteredCount' => count($teams),
        'keyword' => $keyword,
        'selectedGroup' => $selectedGroup,
    ]);
}

    public function actionPlayers($team_id = null)
{
    $request = Yii::$app->request;

    // 1. 获取全部球员数量。
    $totalPlayers = (int) FootballPlayer::find()->count();

    // 2. 获取参赛国家队列表。
    $teamOptions = \yii\helpers\ArrayHelper::map(
        FootballTeam::find()
            ->orderBy([
                'name' => SORT_ASC,
                'id' => SORT_ASC,
            ])
            ->all(),
        'id',
        'name'
    );

    // 3. 获取数据库中实际存在的球员位置。
    $positions = FootballPlayer::find()
        ->select('position')
        ->distinct()
        ->where(['not', ['position' => null]])
        ->andWhere(['<>', 'position', ''])
        ->column();

    // 按照足球场上从后向前的顺序排列。
    $preferredPositions = [
        '门将',
        '后卫',
        '中场',
        '前锋',
    ];

    $positions = array_merge(
        array_intersect($preferredPositions, $positions),
        array_diff($positions, $preferredPositions)
    );

    // 4. 读取搜索及位置筛选参数。
    $keyword = $request->get('keyword', '');
    $position = $request->get('position', '');

    $keyword = is_string($keyword)
        ? mb_substr(trim($keyword), 0, 100)
        : '';

    $position = is_string($position)
        && in_array($position, $positions, true)
        ? $position
        : '';

    // 5. 构造球员查询。
    $team = null;

    $query = FootballPlayer::find()
        ->with('team');

    // 保留国家队页面及比赛详情传入的 team_id。
    if ($team_id !== null && $team_id !== '') {

        $team = is_scalar($team_id)
            ? FootballTeam::findOne(['id' => $team_id])
            : null;

        if ($team === null) {

            throw new NotFoundHttpException(
                '未找到该球队。'
            );

        }

        $query->andWhere([
            'team_id' => $team->id,
        ]);
    }

    // 6. 球员姓名搜索。
    if ($keyword !== '') {

        $query->andWhere([
            'like',
            'name',
            $keyword,
        ]);

    }

    // 7. 球员位置筛选。
    if ($position !== '') {

        $query->andWhere([
            'position' => $position,
        ]);

    }

    // 8. 根据当前浏览方式确定排序规则。
    if ($team !== null) {

        // 浏览指定国家队时，按照球衣号码排列。
        $query->orderBy([
            'number' => SORT_ASC,
            'name' => SORT_ASC,
            'id' => SORT_ASC,
        ]);

    } else {

        // 浏览全部球员时，按照姓名排列。
        $query->orderBy([
            'name' => SORT_ASC,
            'id' => SORT_ASC,
        ]);

    }

    // 9. 保留服务端分页机制。
    $provider = new ActiveDataProvider([
        'query' => $query,
        'sort' => false,
        'pagination' => [
            'pageSize' => 26,
            'pageSizeLimit' => [26, 26],
        ],
    ]);

    // 10. 获取当前国家队的球员总数。
    $teamPlayerCount = $team !== null
        ? (int) FootballPlayer::find()
            ->where(['team_id' => $team->id])
            ->count()
        : null;

    // 11. 渲染球员页面。
    return $this->render('players', [
        'team' => $team,
        'provider' => $provider,
        'teamOptions' => $teamOptions,
        'positions' => $positions,
        'keyword' => $keyword,
        'position' => $position,
        'totalPlayers' => $totalPlayers,
        'teamPlayerCount' => $teamPlayerCount,
    ]);
}

    public function actionStadiums()
{
    // 1. 加载场馆扩展展示配置。
    $stadiumMetadata = require Yii::getAlias(
        '@frontend/config/stadiums.php'
    );

    // 2. 定义主办国家及展示顺序。
    $countries = [
        'MEX' => [
            'name' => '墨西哥',
            'english_name' => 'Mexico',
        ],
        'CAN' => [
            'name' => '加拿大',
            'english_name' => 'Canada',
        ],
        'USA' => [
            'name' => '美国',
            'english_name' => 'United States',
        ],
    ];

    // 3. 查询数据库中的全部场馆。
    $stadiums = FootballStadium::find()
        ->orderBy(['id' => SORT_ASC])
        ->all();

    // 4. 通过数据库分组查询统计各场馆承办的比赛。
    $matchCounts = FootballMatch::find()
        ->select([
            'stadium_id',
            'match_count' => new \yii\db\Expression('COUNT(*)'),
        ])
        ->where(['not', ['stadium_id' => null]])
        ->groupBy('stadium_id')
        ->indexBy('stadium_id')
        ->asArray()
        ->all();

    // 5. 获取并校验国家筛选参数。
    $selectedCountry = Yii::$app->request->get(
        'country',
        ''
    );

    $selectedCountry = is_string($selectedCountry)
        && isset($countries[$selectedCountry])
        ? $selectedCountry
        : '';

    // 6. 初始化国家分组及统计数量。
    $groupedStadiums = [];
    $countryCounts = [];

    foreach ($countries as $code => $country) {
        $groupedStadiums[$code] = [];
        $countryCounts[$code] = 0;
    }

    $groupedStadiums['OTHER'] = [];

    // 7. 将真实场馆记录与展示配置组合。
    foreach ($stadiums as $stadium) {

        $metadata = $stadiumMetadata[$stadium->id] ?? [];

        $countryCode = $metadata['country'] ?? 'OTHER';

        if (!isset($countries[$countryCode])) {
            $countryCode = 'OTHER';
        }

        if (isset($countryCounts[$countryCode])) {
            $countryCounts[$countryCode]++;
        }

        if (
            $selectedCountry !== ''
            && $selectedCountry !== $countryCode
        ) {
            continue;
        }

        $groupedStadiums[$countryCode][] = [
            'stadium' => $stadium,
            'metadata' => $metadata,
            'matchCount' => (int) (
                $matchCounts[$stadium->id]['match_count']
                ?? 0
            ),
        ];
    }

    // 8. 重点场馆优先展示。
    foreach ($groupedStadiums as &$items) {

        usort($items, static function ($left, $right) {

            $leftFeatured = !empty(
                $left['metadata']['featured']
            );

            $rightFeatured = !empty(
                $right['metadata']['featured']
            );

            if ($leftFeatured !== $rightFeatured) {
                return (int) $rightFeatured
                    <=> (int) $leftFeatured;
            }

            return (int) $left['stadium']->id
                <=> (int) $right['stadium']->id;
        });
    }

    unset($items);

    // 9. 统计当前筛选结果。
    $filteredCount = 0;

    foreach ($groupedStadiums as $items) {
        $filteredCount += count($items);
    }

    // 10. 将场馆数据传递给页面。
    return $this->render('stadiums', [
        'countries' => $countries,
        'countryCounts' => $countryCounts,
        'groupedStadiums' => $groupedStadiums,
        'selectedCountry' => $selectedCountry,
        'totalStadiums' => count($stadiums),
        'totalCountries' => count(
            array_filter($countryCounts)
        ),
        'totalMatches' => (int) FootballMatch::find()->count(),
        'filteredCount' => $filteredCount,
    ]);
}
}
