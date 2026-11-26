/**
 * WorldCup2026 Stadium Map
 *
 * 功能：
 * 1. 读取 PHP 提供的场馆 JSON 数据。
 * 2. 初始化 Leaflet 地图。
 * 3. 按国家颜色生成场馆标记。
 * 4. 点击标记更新场馆资料。
 * 5. 支持定位到摄影名录。
 * 6. 支持重置地图视角。
 */

(function () {

    'use strict';


    // ==========================================
    // 1. 获取地图相关 DOM 元素
    // ==========================================

    const mapElement =
        document.getElementById('stadium-map');

    const dataElement =
        document.getElementById('stadium-map-data');

    const errorElement =
        document.getElementById('stadium-map-error');

    const resetButton =
        document.getElementById('stadium-map-reset');

    if (!mapElement || !dataElement) {

        return;

    }


    // ==========================================
    // 2. 错误状态处理
    // ==========================================

    function showMapError() {

        if (errorElement) {

            errorElement.hidden = false;

        }

    }

    if (typeof window.L === 'undefined') {

        showMapError();

        mapElement.classList.add(
            'is-unavailable'
        );

        return;

    }


    // ==========================================
    // 3. 解析 PHP 提供的场馆数据
    // ==========================================

    let venues = [];

    try {

        venues = JSON.parse(
            dataElement.textContent
        );

        if (!Array.isArray(venues)) {

            throw new Error(
                'Invalid stadium map data'
            );

        }

        venues = venues.filter(function (venue) {

            return Number.isFinite(venue.latitude)
                && Number.isFinite(venue.longitude)
                && venue.latitude >= -90
                && venue.latitude <= 90
                && venue.longitude >= -180
                && venue.longitude <= 180;

        });

    } catch (error) {

        console.error(
            'Failed to read stadium map data:',
            error
        );

        showMapError();

        return;

    }

    if (venues.length === 0) {

        showMapError();

        return;

    }


    // ==========================================
    // 4. 定义主办国家颜色
    // ==========================================

    const countryColors = {

        MEX: '#27845f',

        CAN: '#b95349',

        USA: '#426e9d'

    };


    // ==========================================
    // 5. 初始化 Leaflet 地图
    // ==========================================

    const map = L.map('stadium-map', {

        center: [37, -97],

        zoom: 4,

        minZoom: 2,

        maxZoom: 18,

        scrollWheelZoom: false,

        zoomControl: true,

        attributionControl: true

    });


    // ==========================================
    // 6. 添加 OpenStreetMap 底图
    // ==========================================

    const tileLayer = L.tileLayer(

        'https://tile.openstreetmap.org/{z}/{x}/{y}.png',

        {

            maxZoom: 19,

            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright"'
                + ' target="_blank" rel="noopener noreferrer">'
                + 'OpenStreetMap</a> contributors'

        }

    );

    let loadedTiles = 0;

    let failedTiles = 0;

    tileLayer.on('tileload', function () {

        loadedTiles++;

        if (errorElement) {

            errorElement.hidden = true;

        }

    });

    tileLayer.on('tileerror', function () {

        failedTiles++;

        if (loadedTiles === 0 && failedTiles >= 3) {

            showMapError();

        }

    });

    tileLayer.addTo(map);


    // ==========================================
    // 7. 获取右侧资料面板
    // ==========================================

    const detail = {

        image:
            document.getElementById(
                'map-venue-image'
            ),

        placeholder:
            document.getElementById(
                'map-venue-placeholder'
            ),

        location:
            document.getElementById(
                'map-venue-location'
            ),

        name:
            document.getElementById(
                'map-venue-name'
            ),

        english:
            document.getElementById(
                'map-venue-english'
            ),

        featured:
            document.getElementById(
                'map-venue-featured'
            ),

        stats:
            document.getElementById(
                'map-venue-stats'
            ),

        capacity:
            document.getElementById(
                'map-venue-capacity'
            ),

        matches:
            document.getElementById(
                'map-venue-matches'
            ),

        links:
            document.getElementById(
                'map-venue-links'
            ),

        anchor:
            document.getElementById(
                'map-venue-anchor'
            ),

        match:
            document.getElementById(
                'map-venue-match'
            )

    };


    // ==========================================
    // 8. 格式化数据库数字
    // ==========================================

    function formatNumber(value) {

        if (value === null || value === undefined) {

            return '—';

        }

        return Number(value).toLocaleString(

            'en-US'

        );

    }


    // ==========================================
    // 9. 更新右侧场馆资料
    // ==========================================

    function updateDetail(venue) {

        detail.location.textContent =

            venue.englishCity.toUpperCase()

            + ' / '

            + venue.countryName;


        detail.name.textContent = venue.name;

        detail.english.textContent =
            venue.englishName;


        // 更新场馆图片。

        if (venue.image) {

            detail.image.hidden = true;

            detail.placeholder.hidden = false;

            detail.image.onload = function () {

                detail.image.hidden = false;

                detail.placeholder.hidden = true;

            };

            detail.image.onerror = function () {

                detail.image.hidden = true;

                detail.placeholder.hidden = false;

            };

            detail.image.alt =
                venue.name + '场馆摄影';

            detail.image.src = venue.image;

        } else {

            detail.image.onload = null;

            detail.image.onerror = null;

            detail.image.hidden = true;

            detail.image.removeAttribute('src');

            detail.placeholder.hidden = false;

        }


        // 揭幕战或决赛场馆标识。

        detail.featured.textContent =
            venue.featured || '';

        detail.featured.hidden =
            !venue.featured;


        // 更新数据库统计。

        detail.capacity.textContent =
            formatNumber(venue.capacity);

        detail.matches.textContent =
            formatNumber(venue.matchCount);

        detail.stats.hidden = false;


        // 更新关联业务链接。

        detail.anchor.href =
            '#' + venue.anchor;

        detail.match.href =
            venue.matchUrl;

        detail.links.hidden = false;

    }


    // ==========================================
    // 10. 创建自定义场馆标记
    // ==========================================

    const markers = new Map();

    let activeVenueId = null;

    function createMarkerIcon(venue, selected) {

        const color =
            countryColors[venue.country]

            || '#758779';

        const size = selected ? 24 : 16;

        return L.divIcon({

            className:
                'fs-map-marker-container',

            html:

                '<span class="fs-map-marker'

                + (selected ? ' is-selected' : '')

                + '" style="--marker-color:'

                + color

                + '"></span>',

            iconSize: [size, size],

            iconAnchor: [
                size / 2,
                size / 2
            ]

        });

    }


    // ==========================================
    // 11. 选中场馆
    // ==========================================

    function selectVenue(venue) {

        // 恢复上一个标记。

        if (
            activeVenueId !== null
            && markers.has(activeVenueId)
        ) {

            const previous =
                markers.get(activeVenueId);

            previous.marker.setIcon(

                createMarkerIcon(
                    previous.venue,
                    false
                )

            );

            previous.marker.setZIndexOffset(0);

        }

        // 高亮当前标记。

        const current =
            markers.get(venue.id);

        if (current) {

            current.marker.setIcon(

                createMarkerIcon(
                    venue,
                    true
                )

            );

            current.marker.setZIndexOffset(1000);

        }

        activeVenueId = venue.id;

        updateDetail(venue);

    }


    // ==========================================
    // 12. 生成地图标记
    // ==========================================

    const markerGroup = L.featureGroup();

    venues.forEach(function (venue) {

        const marker = L.marker(

            [
                venue.latitude,
                venue.longitude
            ],

            {

                icon: createMarkerIcon(
                    venue,
                    false
                ),

                title: venue.name,

                alt: venue.name,

                keyboard: true,

                riseOnHover: true

            }

        );


        // 使用文本节点，避免 HTML 注入。

        const tooltipContent =
            document.createElement('span');

        tooltipContent.textContent =
            venue.name;

        marker.bindTooltip(

            tooltipContent,

            {

                direction: 'top',

                offset: [0, -8],

                opacity: 0.95

            }

        );

        marker.on('click', function () {

            selectVenue(venue);

        });

        marker.addTo(markerGroup);

        markers.set(venue.id, {

            marker: marker,

            venue: venue

        });

    });

    markerGroup.addTo(map);


    // ==========================================
    // 13. 根据当前场馆范围调整地图
    // ==========================================

    function resetMapView() {

        if (venues.length === 1) {

            map.setView(

                [
                    venues[0].latitude,
                    venues[0].longitude
                ],

                10

            );

            return;

        }

        map.fitBounds(

            markerGroup.getBounds(),

            {

                padding: [36, 36],

                maxZoom: 7,

                animate: false

            }

        );

    }

    resetMapView();


    // ==========================================
    // 14. 默认展示重点场馆
    // ==========================================

    const defaultVenue =

        venues.find(function (venue) {

            return Boolean(venue.featured);

        })

        || venues[0];

    selectVenue(defaultVenue);


    // ==========================================
    // 15. 重置地图按钮
    // ==========================================

    if (resetButton) {

        resetButton.addEventListener(

            'click',

            function () {

                resetMapView();

            }

        );

    }


    // ==========================================
    // 16. 定位下方场馆摄影名录
    // ==========================================

    detail.anchor.addEventListener(

        'click',

        function (event) {

            const targetId =
                detail.anchor.hash.slice(1);

            const target =
                document.getElementById(targetId);

            if (!target) {

                return;

            }

            event.preventDefault();

            const reduceMotion =
                window.matchMedia(
                    '(prefers-reduced-motion: reduce)'
                ).matches;

            target.scrollIntoView({

                behavior:
                    reduceMotion ? 'auto' : 'smooth',

                block: 'start'

            });

            window.history.replaceState(

                null,

                '',

                '#' + targetId

            );

        }

    );


    // ==========================================
    // 17. 窗口尺寸变化处理
    // ==========================================

    if ('ResizeObserver' in window) {

        const observer = new ResizeObserver(

            function () {

                map.invalidateSize({

                    pan: false

                });

            }

        );

        observer.observe(mapElement);

    }

})();