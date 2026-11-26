<?php

/**
 * 公开赛程、比分与比赛详情。
 * @author lzs
 * @date 2026-11-20
 */

namespace frontend\controllers;

use common\models\FootballMatch;
use common\models\FootballTeam;
use common\models\FootballStadium;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MatchController extends Controller
{
    public function actionIndex()
    {
        $request = Yii::$app->request;

        // 1. 从数据库获取实际存在的比赛阶段。
        $stages = FootballMatch::find()
            ->select('stage')
            ->distinct()
            ->orderBy(['stage' => SORT_ASC])
            ->column();

        $stageOptions = [];

        foreach ($stages as $stageName) {
            if ($stageName !== null && $stageName !== '') {
                $stageOptions[$stageName] = $stageName;
            }
        }

        // 2. 获取参赛球队选项。
        $teamOptions = ArrayHelper::map(
            FootballTeam::find()
                ->orderBy([
                    'name' => SORT_ASC,
                    'id' => SORT_ASC,
                ])
                ->all(),
            'id',
            'name'
        );

        // 获取场馆筛选选项。
$stadiumOptions = ArrayHelper::map(
    FootballStadium::find()
        ->orderBy([
            'city' => SORT_ASC,
            'name' => SORT_ASC,
            'id' => SORT_ASC,
        ])
        ->all(),
    'id',
    'name'
);

$statusOptions = FootballMatch::STATUS_LABELS;

// 赛事阶段导航。
// 每个分类对应数据库中实际存在的阶段名称。
$stageGroups = [
    'all' => [
        'label' => '全部',
        'stages' => [],
    ],
    'group' => [
        'label' => '小组赛',
        'stages' => [],
    ],
    'round32' => [
        'label' => '32强',
        'stages' => [],
    ],
    'round16' => [
        'label' => '16强',
        'stages' => [],
    ],
    'quarter' => [
        'label' => '1/4决赛',
        'stages' => [],
    ],
    'semi' => [
        'label' => '半决赛',
        'stages' => [],
    ],
    'third' => [
        'label' => '季军赛',
        'stages' => [],
    ],
    'final' => [
        'label' => '决赛',
        'stages' => [],
    ],
    'other' => [
        'label' => '其他阶段',
        'stages' => [],
    ],
];

// 根据真实阶段名称进行分类。
foreach ($stageOptions as $stageName) {

    if (strpos($stageName, '小组赛') !== false) {

        $key = 'group';

    } elseif (
        strpos($stageName, '32强') !== false
        || strpos($stageName, '三十二强') !== false
        || strpos($stageName, '1/16决赛') !== false
    ) {

        $key = 'round32';

    } elseif (
        strpos($stageName, '16强') !== false
        || strpos($stageName, '十六强') !== false
        || strpos($stageName, '1/8决赛') !== false
        || strpos($stageName, '八分之一决赛') !== false
    ) {

        $key = 'round16';

    } elseif (
        strpos($stageName, '1/4决赛') !== false
        || strpos($stageName, '四分之一决赛') !== false
        || strpos($stageName, '八强') !== false
        || strpos($stageName, '8强') !== false
    ) {

        $key = 'quarter';

    } elseif (
        strpos($stageName, '半决赛') !== false
        || strpos($stageName, '1/2决赛') !== false
    ) {

        $key = 'semi';

    } elseif (
        strpos($stageName, '季军') !== false
        || strpos($stageName, '三四名') !== false
        || strpos($stageName, '三、四名') !== false
        || strpos($stageName, '第三名') !== false
    ) {

        $key = 'third';

    } elseif ($stageName === '决赛') {

        $key = 'final';

    } else {

        $key = 'other';

    }

    $stageGroups[$key]['stages'][] = $stageName;
}

// 不显示数据库中尚不存在的阶段分类。
foreach ($stageGroups as $key => $group) {

    if ($key !== 'all' && empty($group['stages'])) {

        unset($stageGroups[$key]);

    }

}

        // 3. 读取 GET 请求中的筛选参数。
        $phase = $request->get('phase', 'all');
$stage = $request->get('stage', '');
$teamId = $request->get('team_id', '');
$status = $request->get('status', '');
$order = $request->get('order', 'asc');

$phase = is_string($phase)
    && isset($stageGroups[$phase])
    ? $phase
    : 'all';

        // 4. 校验筛选参数。
        $stage = is_string($stage)
            && isset($stageOptions[$stage])
            ? $stage
            : '';

        $teamId = is_scalar($teamId)
            && isset($teamOptions[(string) $teamId])
            ? (string) $teamId
            : '';

        $status = is_scalar($status)
            && (string) $status !== ''
            && isset($statusOptions[(string) $status])
            ? (string) $status
            : '';

        $order = $order === 'desc' ? 'desc' : 'asc';

        // 5. 构造赛事查询。
        $query = FootballMatch::find()
            ->with(['homeTeam', 'awayTeam', 'stadium']);

        // 首先按照赛事大阶段进行筛选。
if ($phase !== 'all') {

    $allowedStages = $stageGroups[$phase]['stages'];

    $query->andWhere([
        'stage' => $allowedStages,
    ]);

    // 防止具体阶段与当前大阶段冲突。
    if (
        $stage !== ''
        && !in_array($stage, $allowedStages, true)
    ) {
        $stage = '';
    }

}

// 再筛选具体比赛阶段，例如小组赛 A组。
if ($stage !== '') {

    $query->andWhere([
        'stage' => $stage,
    ]);

}

        if ($teamId !== '') {
            $query->andWhere([
                'or',
                ['home_team_id' => $teamId],
                ['away_team_id' => $teamId],
            ]);
        }

                if ($status !== '') {
            $query->andWhere(['status' => $status]);
        }

        // 场馆关联筛选。
$stadiumId = $request->get('stadium_id', '');

$stadiumId = is_scalar($stadiumId)
    && (string) $stadiumId !== ''
    && isset($stadiumOptions[(string) $stadiumId])
    ? (string) $stadiumId
    : '';

// 获取当前选中的场馆名称。
$selectedStadiumName = $stadiumId !== ''
    ? $stadiumOptions[$stadiumId]
    : null;

// 按场馆筛选比赛。
if ($stadiumId !== '') {

    $query->andWhere([
        'stadium_id' => $stadiumId,
    ]);

}

        // 6. 时间排序。
        $direction = $order === 'desc'
            ? SORT_DESC
            : SORT_ASC;

        $query->orderBy([
            'match_time' => $direction,
            'id' => $direction,
        ]);

        // 7. 保留 Yii2 数据提供器及分页机制。
        $provider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => [
                'pageSize' => 20,
                'pageSizeLimit' => [20, 20],
            ],
        ]);

        // 8. 向 View 传递查询结果及筛选状态。
        return $this->render('index', [
    'provider' => $provider,
    'stageOptions' => $stageOptions,
    'stageGroups' => $stageGroups,
    'teamOptions' => $teamOptions,
    'statusOptions' => $statusOptions,
    'phase' => $phase,
    'stage' => $stage,
    'teamId' => $teamId,
    'status' => $status,
    'order' => $order,
    'totalMatches' => FootballMatch::find()->count(),

    // 场馆筛选相关数据。
    'stadiumOptions' => $stadiumOptions,
    'stadiumId' => $stadiumId,
    'selectedStadiumName' => $selectedStadiumName,
]);
    }
    /**
 * 世界杯淘汰赛晋级图。
 */
public function actionBracket()
{
    $roundDefinitions = [
        [
            'label' => '32强赛',
            'start' => 73,
            'end' => 88,
        ],
        [
            'label' => '16强赛',
            'start' => 89,
            'end' => 96,
        ],
        [
            'label' => '四分之一决赛',
            'start' => 97,
            'end' => 100,
        ],
        [
            'label' => '半决赛',
            'start' => 101,
            'end' => 102,
        ],
        [
            'label' => '决赛',
            'start' => 104,
            'end' => 104,
        ],
    ];

    // 1. 根据外部比赛编号构造查询条件。
    $matchKeys = [];

    for ($number = 73; $number <= 104; $number++) {
        $matchKeys[] = sprintf('FWC26-M%03d', $number);
    }

    // 一次性读取所有淘汰赛及关联球队。
    $matches = FootballMatch::find()
        ->with(['homeTeam', 'awayTeam'])
        ->where(['source_match_key' => $matchKeys])
        ->all();

    // 2. 建立比赛编号与比赛对象的映射。
    $matchMap = [];
    $winners = [];

    foreach ($matches as $match) {

        if (!preg_match(
            '/^FWC26-M(\d{3})$/D',
            $match->source_match_key,
            $parts
        )) {
            continue;
        }

        $number = (int) $parts[1];

        $matchMap[$number] = $match;

        $winners[$number] = $this->resolveMatchWinner($match);
    }

    // 3. 构造各轮比赛集合。
    $roundPools = [];

    foreach ($roundDefinitions as $index => $definition) {

        $roundPools[$index] = [];

        for (
            $number = $definition['start'];
            $number <= $definition['end'];
            $number++
        ) {
            if (isset($matchMap[$number])) {

                $roundPools[$index][$number] =
                    $matchMap[$number];

            }
        }
    }

    // 4. 从决赛逆向构造晋级树。
    $rounds = array_fill(0, 5, []);

    $usedMatches = [];
    $bracketValid = true;

    $buildTree = function ($number, $level) use (
        &$buildTree,
        &$rounds,
        &$usedMatches,
        &$bracketValid,
        $matchMap,
        $roundPools,
        $winners
    ) {

        if (
            !isset($matchMap[$number])
            || isset($usedMatches[$number])
        ) {
            $bracketValid = false;
            return;
        }

        $match = $matchMap[$number];

        $usedMatches[$number] = true;

        $rounds[$level][] = [
            'number' => $number,
            'match' => $match,
            'winnerId' => $winners[$number] ?? null,
        ];

        if ($level === 0) {
            return;
        }

        // 分别寻找本轮两支球队在上一轮获胜的比赛。
        $teamIds = [
            (int) $match->home_team_id,
            (int) $match->away_team_id,
        ];

        foreach ($teamIds as $teamId) {

            $candidates = [];

            foreach (
                $roundPools[$level - 1]
                as $previousNumber => $previousMatch
            ) {

                if (
                    isset($winners[$previousNumber])
                    && $winners[$previousNumber] === $teamId
                ) {
                    $candidates[] = $previousNumber;
                }
            }

            // 每支晋级球队必须对应唯一的上一轮比赛。
            if (count($candidates) !== 1) {
                $bracketValid = false;
                continue;
            }

            $buildTree(
                $candidates[0],
                $level - 1
            );
        }
    };

    $buildTree(104, 4);

    // 5. 验证晋级树完整性。
    $expectedCounts = [16, 8, 4, 2, 1];

    foreach ($expectedCounts as $index => $expected) {

        if (count($rounds[$index]) !== $expected) {

            $bracketValid = false;

        }
    }

    if (count($usedMatches) !== 31) {

        $bracketValid = false;

    }

    // 6. 获取决赛、季军赛及冠军信息。
    $finalMatch = $matchMap[104] ?? null;
    $thirdMatch = $matchMap[103] ?? null;

    $champion = null;

    if (
        $finalMatch !== null
        && isset($winners[104])
    ) {

        $champion = $winners[104]
            === (int) $finalMatch->home_team_id

            ? $finalMatch->homeTeam
            : $finalMatch->awayTeam;
    }

    return $this->render('bracket', [
        'roundDefinitions' => $roundDefinitions,
        'rounds' => $rounds,
        'bracketValid' => $bracketValid,
        'finalMatch' => $finalMatch,
        'thirdMatch' => $thirdMatch,
        'thirdWinnerId' => $winners[103] ?? null,
        'champion' => $champion,
    ]);
}


/**
 * 根据实际赛果确定获胜球队。
 *
 * 返回球队 ID；无法确定胜者时返回 null。
 */
private function resolveMatchWinner(FootballMatch $match)
{
    // 尚未完赛或缺少比分时，不判断胜者。
    if (
        (int) $match->status !== 2
        || $match->home_score === null
        || $match->away_score === null
    ) {
        return null;
    }

    $homeScore = (int) $match->home_score;
    $awayScore = (int) $match->away_score;

    // 普通比分及加时赛比分。
    if ($homeScore > $awayScore) {
        return (int) $match->home_team_id;
    }

    if ($awayScore > $homeScore) {
        return (int) $match->away_team_id;
    }

    // 比分相同时，进一步读取点球大战结果。
    $note = (string) $match->result_note;

    if (preg_match(
        '/点球大战\s*(\d+)\s*[-–—－:：]\s*(\d+)/u',
        $note,
        $parts
    )) {

        $homePenalties = (int) $parts[1];
        $awayPenalties = (int) $parts[2];

        if ($homePenalties > $awayPenalties) {
            return (int) $match->home_team_id;
        }

        if ($awayPenalties > $homePenalties) {
            return (int) $match->away_team_id;
        }
    }

    return null;
}
    public function actionView($id)
    {
        if (!is_scalar($id)) {
            throw new NotFoundHttpException('未找到该比赛。');
        }

        $match = FootballMatch::find()
            ->with(['homeTeam', 'awayTeam', 'stadium', 'goals.team'])
            ->where(['id' => $id])
            ->one();

        if ($match === null) {
            throw new NotFoundHttpException('未找到该比赛。');
        }

        return $this->render('view', ['match' => $match]);
    }
}