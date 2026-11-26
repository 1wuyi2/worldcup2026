<?php

/**
 * 世界杯场馆摄影名录。
 */

use frontend\assets\AppAsset;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\View;

$this->title = '世界杯场馆';

$this->params['fullBleed'] = true;
$this->params['hideBreadcrumbs'] = true;

// Leaflet 地图库样式。
$this->registerCssFile(
    '@web/vendor/leaflet/leaflet.css',
    [
        'depends' => [AppAsset::className()],
    ]
);

// 场馆页面样式。
$this->registerCssFile('@web/css/football.css', [
    'depends' => [AppAsset::className()],
]);

// Leaflet 地图库。
$this->registerJsFile(
    '@web/vendor/leaflet/leaflet.js',
    [
        'position' => View::POS_END,
    ],
    'leaflet-library'
);

// 场馆地图交互逻辑。
$this->registerJsFile(
    '@web/js/stadium-map.js',
    [
        'position' => View::POS_END,
    ],
    'stadium-map-script'
);

/*
 * 生成本地场馆摄影。
 * 图片缺失时展示文字占位，避免破损图片。
 */
$renderStadiumImage = static function (
    $stadium,
    $metadata
) {

    $filename = $metadata['image'] ?? '';

    if (
        $filename !== ''
        && preg_match('/^[a-z0-9-]+\.jpg$/D', $filename)
        && is_file(
            Yii::getAlias('@webroot/images/stadium/')
            . $filename
        )
    ) {
        return Html::img(
            '@web/images/stadium/' . $filename,
            [
                'class' => 'fs-venue__image',
                'alt' => $stadium->name . '场馆摄影',
                'loading' => 'lazy',
                'decoding' => 'async',
            ]
        );
    }

    return Html::tag(
        'div',
        Html::encode($stadium->name),
        ['class' => 'fs-venue__placeholder']
    );
};


/*
 * 校验数据库中的资料来源链接。
 */
$isValidSource = static function ($url) {

    return is_string($url)
        && filter_var($url, FILTER_VALIDATE_URL)
        && in_array(
            strtolower(
                (string) parse_url($url, PHP_URL_SCHEME)
            ),
            ['http', 'https'],
            true
        );
};
/*
 * 将当前筛选范围内的场馆资料转换为地图数据。
 */

$mapVenues = [];

foreach ($groupedStadiums as $countryCode => $items) {

    foreach ($items as $item) {

        $stadium = $item['stadium'];
        $metadata = $item['metadata'];

        // 没有地理坐标的场馆不添加地图标记。
        if (
            !isset($metadata['latitude'])
            || !isset($metadata['longitude'])
        ) {
            continue;
        }

        $filename = $metadata['image'] ?? '';

        $imageUrl = '';

        if (
            preg_match('/^[a-z0-9-]+\.jpg$/D', $filename)
            && is_file(
                Yii::getAlias('@webroot/images/stadium/')
                . $filename
            )
        ) {
            $imageUrl = Url::to(
                '@web/images/stadium/' . $filename
            );
        }

        $mapVenues[] = [

            'id' => (int) $stadium->id,

            'name' => $stadium->name,

            'englishName' =>
                $metadata['english_name']
                ?? $stadium->name,

            'city' => $stadium->city,

            'englishCity' =>
                $metadata['english_city']
                ?? $stadium->city,

            'country' => $countryCode,

            'countryName' =>
                $countries[$countryCode]['name']
                ?? '其他',

            'latitude' =>
                (float) $metadata['latitude'],

            'longitude' =>
                (float) $metadata['longitude'],

            'capacity' =>
                $stadium->capacity !== null
                    ? (int) $stadium->capacity
                    : null,

            'matchCount' => (int) $item['matchCount'],

            'image' => $imageUrl,

            'featured' =>
                $metadata['featured'] ?? '',

            'matchUrl' => Url::to([
                '/match/index',
                'stadium_id' => $stadium->id,
            ]),

            'anchor' =>
                'stadium-' . (int) $stadium->id,

        ];
    }
}

?>

<div class="football-explore football-stadiums">

    <!-- ======================================
         场馆专题页头
         ====================================== -->

    <section class="fs-hero">

        <div class="wc-container fs-hero__intro">

            <div class="fs-hero__heading">

                <span class="fs-hero__eyebrow">
                    THE STAGES OF FOOTBALL / 2026
                </span>

                <h1 class="fs-hero__title">
                    足球的舞台，<br>
                    世界的坐标。
                </h1>

            </div>

            <div class="fs-hero__information">

                <p class="fs-hero__description">
                    跨越三个国家，从城市天际线到绿茵赛场，
                    探索承载世界杯记忆的比赛场馆。
                </p>

                <div class="fs-hero__stats">

                    <div class="fs-hero__stat">

                        <strong>
                            <?= number_format($totalStadiums) ?>
                        </strong>

                        <span>比赛场馆</span>

                    </div>

                    <div class="fs-hero__stat">

                        <strong>
                            <?= number_format($totalCountries) ?>
                        </strong>

                        <span>主办国家</span>

                    </div>

                    <div class="fs-hero__stat">

                        <strong>
                            <?= number_format($totalMatches) ?>
                        </strong>

                        <span>世界杯赛事</span>

                    </div>

                </div>

            </div>

        </div>


        <!-- 建筑摄影 -->

        <figure class="fs-hero__visual">

            <?= Html::img(
                '@web/images/stadium/sofi-stadium.jpg',
                [
                    'class' => 'fs-hero__image',
                    'alt' => '洛杉矶 SoFi Stadium 建筑外观及水面',
                    'fetchpriority' => 'high',
                ]
            ) ?>

            <figcaption class="fs-hero__caption">

                <div class="wc-container">

                    <span>LOS ANGELES / SOFI STADIUM</span>

                    <span>洛杉矶体育场 · 美国</span>

                </div>

            </figcaption>

        </figure>

    </section>


    <!-- ======================================
         探索世界杯公共导航
         ====================================== -->

    <div class="football-navigation">

        <div class="wc-container">

            <?= $this->render('_nav') ?>

        </div>

    </div>
    <!-- ======================================
         交互式场馆地图
         ====================================== -->

    <section
        id="fs-map-section"
        class="fs-map-section"
        aria-labelledby="fs-map-title"
    >

        <div class="wc-container">

            <header class="fs-map-header">

                <div>

                    <span class="fs-section-eyebrow">
                        THE GEOGRAPHY OF FOOTBALL
                    </span>

                    <h2 id="fs-map-title">
                        横跨北美的足球舞台。
                    </h2>

                    <p>
                        从太平洋沿岸到大西洋海岸，
                        在地图上探索世界杯比赛场馆。
                    </p>

                </div>

                <span class="fs-map-header__count">

                    <?= count($mapVenues) ?>
                    座场馆

                </span>

            </header>


            <!-- 地图国家筛选 -->

            <nav
                class="fs-map-filters"
                aria-label="地图国家筛选"
            >

                <?= Html::a(
                    '全部场馆',
                    [
                        '/football/stadiums',
                        '#' => 'fs-map-section',
                    ],
                    [
                        'class' => 'fs-map-filter'
                            . (
                                $selectedCountry === ''
                                    ? ' is-active'
                                    : ''
                            ),
                        'aria-current' =>
                            $selectedCountry === ''
                                ? 'page'
                                : null,
                    ]
                ) ?>

                <?php foreach (
                    $countries as $code => $country
                ): ?>

                    <?= Html::a(
                        Html::tag(
                            'span',
                            '',
                            [
                                'class' =>
                                    'fs-map-dot fs-map-dot--'
                                    . strtolower($code),
                                'aria-hidden' => 'true',
                            ]
                        )
                        . Html::encode($country['name'])
                        . Html::tag(
                            'small',
                            (string) $countryCounts[$code]
                        ),
                        [
                            '/football/stadiums',
                            'country' => $code,
                            '#' => 'fs-map-section',
                        ],
                        [
                            'class' => 'fs-map-filter'
                                . (
                                    $selectedCountry === $code
                                        ? ' is-active'
                                        : ''
                                ),
                            'aria-current' =>
                                $selectedCountry === $code
                                    ? 'page'
                                    : null,
                        ]
                    ) ?>

                <?php endforeach; ?>

            </nav>


            <!-- 地图与场馆信息 -->

            <div class="fs-map-layout">

                <div class="fs-map-canvas">

                    <div
                        id="stadium-map"
                        aria-label="世界杯场馆地理分布地图"
                    ></div>

                    <div
                        id="stadium-map-error"
                        class="fs-map-error"
                        role="status"
                        hidden
                    >

                        地图底图暂时无法正常加载，
                        你仍然可以使用场馆名录。

                    </div>

                </div>


                <!-- 当前场馆资料 -->

                <aside
                    id="stadium-map-detail"
                    class="fs-map-detail"
                    aria-live="polite"
                >

                    <div class="fs-map-detail__visual">

                        <img
                            id="map-venue-image"
                            alt=""
                            hidden
                        >

                        <span id="map-venue-placeholder">
                            WORLD CUP 2026
                        </span>

                    </div>

                    <div class="fs-map-detail__content">

                        <span
                            id="map-venue-location"
                            class="fs-map-detail__location"
                        >
                            EXPLORE THE VENUES
                        </span>

                        <h3 id="map-venue-name">
                            选择一座场馆
                        </h3>

                        <p id="map-venue-english">
                            点击地图中的场馆标记。
                        </p>

                        <span
                            id="map-venue-featured"
                            class="fs-map-detail__featured"
                            hidden
                        ></span>

                        <div
                            id="map-venue-stats"
                            class="fs-map-detail__stats"
                            hidden
                        >

                            <div>

                                <strong id="map-venue-capacity">
                                    —
                                </strong>

                                <span>场馆容量</span>

                            </div>

                            <div>

                                <strong id="map-venue-matches">
                                    —
                                </strong>

                                <span>承办比赛</span>

                            </div>

                        </div>

                        <div
                            id="map-venue-links"
                            class="fs-map-detail__links"
                            hidden
                        >

                            <a id="map-venue-anchor" href="#fs-directory">
                                浏览场馆资料 ↓
                            </a>

                            <a id="map-venue-match" href="#">
                                查看场馆赛事 →
                            </a>

                        </div>

                    </div>

                </aside>

            </div>


            <!-- 地图辅助操作 -->

            <div class="fs-map-tools">

                <span>
                    拖动地图或使用缩放按钮，
                    点击标记查看场馆。
                </span>

                <button
                    type="button"
                    id="stadium-map-reset"
                >
                    重置地图视角
                </button>

            </div>

            <noscript>

                <p>
                    交互式地图需要启用 JavaScript，
                    你仍然可以浏览下方的场馆名录。
                </p>

            </noscript>

            <script
                type="application/json"
                id="stadium-map-data"
            ><?= Json::htmlEncode($mapVenues) ?></script>

        </div>

    </section>

    <!-- ======================================
         场馆摄影名录
         ====================================== -->

    <section
        id="fs-directory"
        class="fs-directory"
    >

        <div class="wc-container">

            <header class="fs-directory__header">

                <div>

                    <span class="fs-section-eyebrow">
                        HOST COUNTRIES / VENUES
                    </span>

                    <h2>探索主办场馆</h2>

                    <p>
                        从墨西哥到加拿大，再到美国，
                        认识每一座世界杯比赛场馆。
                    </p>

                </div>

                <div class="fs-directory__count">

                    <strong>
                        <?= number_format($filteredCount) ?>
                    </strong>

                    <span>座场馆</span>

                </div>

            </header>


            <!-- ======================================
                 主办国家筛选
                 ====================================== -->

            <nav
                class="fs-country-nav"
                aria-label="场馆国家筛选"
            >

                <?= Html::a(
                    '全部',
                    [
                        '/football/stadiums',
                        '#' => 'fs-directory',
                    ],
                    [
                        'class' => 'fs-country-nav__link'
                            . (
                                $selectedCountry === ''
                                    ? ' is-active'
                                    : ''
                            ),
                        'aria-current' =>
                            $selectedCountry === ''
                                ? 'page'
                                : null,
                    ]
                ) ?>

                <?php foreach ($countries as $code => $country): ?>

                    <?php
                    $active = $selectedCountry === $code;
                    ?>

                    <?= Html::a(
                        Html::encode($country['name'])
                        . Html::tag(
                            'small',
                            (string) $countryCounts[$code]
                        ),
                        [
                            '/football/stadiums',
                            'country' => $code,
                            '#' => 'fs-directory',
                        ],
                        [
                            'class' => 'fs-country-nav__link'
                                . ($active ? ' is-active' : ''),
                            'aria-current' =>
                                $active ? 'page' : null,
                        ]
                    ) ?>

                <?php endforeach; ?>

            </nav>


            <!-- ======================================
                 各国家场馆
                 ====================================== -->

            <?php foreach (
                $groupedStadiums as $countryCode => $items
            ): ?>

                <?php if (empty($items)) {
                    continue;
                } ?>

                <?php

                $country = $countries[$countryCode] ?? [
                    'name' => '其他场馆',
                    'english_name' => 'Other Venues',
                ];

                ?>

                <section class="fs-country">

                    <!-- 国家区域标题 -->

                    <header class="fs-country__heading">

                        <div class="fs-country__identity">

                            <?php if (
                                isset($countries[$countryCode])
                            ): ?>

                                <?= Html::img(
                                    '@web/images/flags/'
                                    . $countryCode
                                    . '.svg',
                                    [
                                        'class' =>
                                            'fs-country__flag',
                                        'alt' => '',
                                        'aria-hidden' => 'true',
                                        'loading' => 'lazy',
                                    ]
                                ) ?>

                            <?php endif; ?>

                            <div>

                                <span class="fs-country__english">

                                    <?= Html::encode(
                                        strtoupper(
                                            $country['english_name']
                                        )
                                    ) ?>

                                </span>

                                <h3>

                                    <?= Html::encode(
                                        $country['name']
                                    ) ?>

                                </h3>

                            </div>

                        </div>

                        <span class="fs-country__count">

                            <?= count($items) ?> 座场馆

                        </span>

                    </header>


                    <!-- 场馆摄影网格 -->

                    <div class="fs-venues-grid">

                        <?php foreach ($items as $item): ?>

                            <?php

                            $stadium = $item['stadium'];

                            $metadata = $item['metadata'];

                            $matchCount = $item['matchCount'];

                            $isFeatured = !empty(
                                $metadata['featured']
                            );

                            ?>

                            <article
                                id="stadium-<?= (int) $stadium->id ?>"
                                class="fs-venue<?= $isFeatured
                                    ? ' fs-venue--featured'
                                    : '' ?>"
                            >

                                <!-- 场馆摄影 -->

                                <div class="fs-venue__visual">

                                    <?= $renderStadiumImage(
                                        $stadium,
                                        $metadata
                                    ) ?>

                                </div>


                                <!-- 场馆资料 -->

                                <div class="fs-venue__content">

                                    <div class="fs-venue__location">

                                        <?= Html::encode(
                                            strtoupper(
                                                $metadata['english_city']
                                                ?? $stadium->city
                                            )
                                        ) ?>

                                    </div>

                                    <?php if ($isFeatured): ?>

                                        <span class="fs-venue__featured-label">

                                            <?= Html::encode(
                                                $metadata['featured']
                                            ) ?>

                                        </span>

                                    <?php endif; ?>

                                    <h4 class="fs-venue__title">

                                        <?= Html::encode(
                                            $stadium->name
                                        ) ?>

                                    </h4>

                                    <p class="fs-venue__english">

                                        <?= Html::encode(
                                            $metadata['english_name']
                                            ?? $stadium->city
                                        ) ?>

                                    </p>


                                    <!-- 数据统计 -->

                                    <div class="fs-venue__stats">

                                        <div class="fs-venue__stat">

                                            <strong>

                                                <?= $stadium->capacity !== null
                                                    ? number_format(
                                                        (int) $stadium->capacity
                                                    )
                                                    : '—' ?>

                                            </strong>

                                            <span>场馆容量</span>

                                        </div>

                                        <div class="fs-venue__stat">

                                            <strong>

                                                <?= number_format(
                                                    $matchCount
                                                ) ?>

                                            </strong>

                                            <span>承办比赛</span>

                                        </div>

                                    </div>


                                    <!-- 关联赛事及数据来源 -->

                                    <div class="fs-venue__links">

                                        <?= Html::a(
                                            '查看场馆赛事 →',
                                            [
                                                '/match/index',
                                                'stadium_id' =>
                                                    $stadium->id,
                                            ],
                                            [
                                                'class' =>
                                                    'fs-venue__match-link',
                                            ]
                                        ) ?>

                                        <?php if (
                                            $isValidSource(
                                                $stadium->source_url
                                            )
                                        ): ?>

                                            <?= Html::a(
                                                '资料来源 ↗',
                                                $stadium->source_url,
                                                [
                                                    'class' =>
                                                        'fs-venue__source',
                                                    'target' => '_blank',
                                                    'rel' =>
                                                        'noopener noreferrer',
                                                ]
                                            ) ?>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </article>

                        <?php endforeach; ?>

                    </div>

                </section>

            <?php endforeach; ?>


            <!-- 无数据状态 -->

            <?php if ($filteredCount === 0): ?>

                <div class="ft-empty">

                    <h3>暂无场馆资料</h3>

                    <p>
                        当前筛选条件下没有可展示的场馆。
                    </p>

                    <?= Html::a(
                        '查看全部场馆 →',
                        [
                            '/football/stadiums',
                            '#' => 'fs-directory',
                        ],
                        ['class' => 'ft-empty__link']
                    ) ?>

                </div>

            <?php endif; ?>


            <!-- ======================================
                 页面底部
                 ====================================== -->

            <footer class="fs-directory__footer">

                <p>
                    场馆容量与承办比赛数量根据项目数据库展示。
                    场馆摄影可能拍摄于不同年份及不同赛事期间。
                </p>

                <?= Html::a(
                    '前往赛事中心 →',
                    ['/match/index'],
                    ['class' => 'fs-directory__next']
                ) ?>

            </footer>

        </div>

    </section>

</div>