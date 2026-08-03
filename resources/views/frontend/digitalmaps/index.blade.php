@extends('layouts.main')

@section('title', 'Bản đồ số')

@push('styles')
    <link href="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.css" rel="stylesheet" />
@endpush

@section('content')
    <main class="admin-content-wrapper digitalmaps-wrapper">
        <div class="digitalmaps-header">
            <div class="digitalmaps-header-left">
                <h1 class="digitalmaps-title">Bản đồ số</h1>
                <p class="digitalmaps-subtitle">Quản lý dữ liệu bản đồ, lớp dữ liệu GIS và thông tin đô thị.</p>
            </div>
            <div class="digitalmaps-header-right">
                <div class="digitalmaps-search-box">
                    <i class="ph ph-magnifying-glass"></i>
                    <input type="text" placeholder="Tìm kiếm địa điểm, tuyến đường...">
                </div>
                <button class="digitalmaps-btn" id="btn-locate"><i class="ph ph-crosshair"></i> Định vị vị trí hiện tại</button>
                <button class="digitalmaps-btn" id="btn-fullscreen"><i class="ph ph-corners-out"></i> Toàn màn hình</button>
                <button class="digitalmaps-btn" id="btn-reset"><i class="ph ph-arrows-counter-clockwise"></i> Reset bản đồ</button>
            </div>
        </div>

        <div class="digitalmaps-container" id="digitalmaps-fullscreen-container">
            <aside class="digitalmaps-sidebar" id="digitalmaps-sidebar">
                <!-- Header Sidebar -->
                <div class="digitalmaps-sidebar-header" id="sidebar-toggle-btn" title="Thu gọn / Mở rộng">
                    <div class="digitalmaps-sidebar-title">
                        <i class="ph ph-layers"></i> <span class="sidebar-title-text">Lớp dữ liệu</span>
                    </div>
                    <i class="ph ph-caret-left digitalmaps-sidebar-toggle-icon" id="sidebar-toggle-icon"></i>
                </div>

                <!-- Nội dung Sidebar -->
                <div class="digitalmaps-sidebar-content" id="sidebar-content">
                    <div class="digitalmaps-search-layer-wrap">
                        <div class="digitalmaps-layer-search">
                            <i class="ph ph-magnifying-glass"></i>
                            <input type="text" id="digitalmaps-search-layer" placeholder="Tìm kiếm lớp dữ liệu...">
                        </div>
                    </div>

                    <div class="digitalmaps-layer-list" id="digitalmaps-layer-list">
                        <div class="digitalmaps-layer-item all-toggle">
                            <div class="digitalmaps-layer-info">
                                <i class="ph ph-check-square-offset"></i>
                                Bật/Tắt tất cả
                            </div>
                            <label class="digitalmaps-switch">
                                <input type="checkbox" id="toggle-all-layers" checked>
                                <span class="digitalmaps-slider"></span>
                            </label>
                        </div>

                        @php
                            $layers = [
                                ['id' => 'cap-phep-xay-dung', 'icon' => 'ph-crane',                  'name' => 'Cấp phép xây dựng'],
                                ['id' => 'chung-cu',          'icon' => 'ph-buildings',              'name' => 'Chung cư'],
                                ['id' => 'phan-anh',          'icon' => 'ph-warning',                'name' => 'Phản ánh'],
                                ['id' => 'ho-kinh-doanh',     'icon' => 'ph-storefront',             'name' => 'Hộ kinh doanh'],
                                ['id' => 'nha-tro',           'icon' => 'ph-house-line',             'name' => 'Nhà trọ'],
                                ['id' => 'truong-hoc',        'icon' => 'ph-graduation-cap',         'name' => 'Trường học'],
                                ['id' => 'co-quan',           'icon' => 'ph-bank',                   'name' => 'Vị trí cơ quan'],
                                ['id' => 'xu-ly-vi-pham',     'icon' => 'ph-siren',                  'name' => 'Xử lý vi phạm'],
                                ['id' => 'dat-cong',          'icon' => 'ph-tree',                   'name' => 'Đất công'],
                                ['id' => 'du-an',             'icon' => 'ph-projector-screen',       'name' => 'Dự án'],
                                ['id' => 'dia-chinh',         'icon' => 'ph-map-trifold',            'name' => 'Bản đồ địa chính'],
                                ['id' => 'quy-hoach',         'icon' => 'ph-compass-tool',           'name' => 'Quy hoạch xây dựng'],
                                ['id' => 'khu-pho',           'icon' => 'ph-map-pin-line',           'name' => 'Bản đồ khu phố'],
                                ['id' => 'ranh-gioi',         'icon' => 'ph-bounding-box',           'name' => 'Ranh giới hành chính phường'],
                                ['id' => 'ranh-gioi-cu',      'icon' => 'ph-clock-counter-clockwise','name' => 'Ranh giới hành chính phường cũ'],
                            ];
                        @endphp
                        @foreach($layers as $layer)
                            <div class="digitalmaps-layer-item layer-item-row" data-name="{{ strtolower($layer['name']) }}">
                                <div class="digitalmaps-layer-info">
                                    <i class="ph {{ $layer['icon'] }}"></i>
                                    {{ $layer['name'] }}
                                </div>
                                <label class="digitalmaps-switch">
                                    <input type="checkbox" class="layer-toggle-checkbox" data-layer="{{ $layer['id'] }}" checked>
                                    <span class="digitalmaps-slider"></span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>

            <div class="digitalmaps-map-area">
                <div id="digitalmaps-map"></div>
                <div class="digitalmaps-floating-controls">
                    <button class="digitalmaps-control-btn" id="btn-zoom-in"  title="Phóng to"><i class="ph ph-plus"></i></button>
                    <button class="digitalmaps-control-btn" id="btn-zoom-out" title="Thu nhỏ"><i class="ph ph-minus"></i></button>
                    <button class="digitalmaps-control-btn" id="btn-compass"  title="Chỉ hướng Bắc"><i class="ph ph-navigation-arrow"></i></button>
                    <button class="digitalmaps-control-btn" id="btn-geolocate" title="Vị trí của tôi"><i class="ph ph-crosshair-simple"></i></button>
                </div>
                <div class="digitalmaps-legend">
                    <h4>Chú giải bản đồ</h4>
                    <div class="digitalmaps-legend-item"><div class="digitalmaps-color-box" style="background:#2196F3;"></div> Chung cư</div>
                    <div class="digitalmaps-legend-item"><div class="digitalmaps-color-box" style="background:#4CAF50;"></div> Trường học</div>
                    <div class="digitalmaps-legend-item"><div class="digitalmaps-color-box" style="background:#9C27B0;"></div> Cơ quan</div>
                    <div class="digitalmaps-legend-item"><div class="digitalmaps-color-box" style="background:#FF9800;"></div> Dự án</div>
                    <div class="digitalmaps-legend-item"><div class="digitalmaps-color-box" style="background:#009688;"></div> Đất công</div>
                    <div class="digitalmaps-legend-item"><div class="digitalmaps-color-box" style="background:#F44336;"></div> Điểm nóng</div>
                    <div class="digitalmaps-legend-item"><div class="digitalmaps-color-box" style="background:#FFC107;"></div> Hộ kinh doanh</div>
                    <div class="digitalmaps-legend-item"><div class="digitalmaps-color-box" style="background:#795548;"></div> Nhà trọ</div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="https://unpkg.com/maplibre-gl@3.6.2/dist/maplibre-gl.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const INIT_LNG  = 105.8455;
            const INIT_LAT  = 21.5928;
            const INIT_ZOOM = 12;
            const MAP_STYLE = 'https://tiles.openfreemap.org/styles/bright';

            let map;
            let markers = [];

            const categoryConfig = {
                'chung-cu':      { color: '#2196F3', icon: 'ph-buildings',        name: 'Chung cư' },
                'truong-hoc':    { color: '#4CAF50', icon: 'ph-graduation-cap',   name: 'Trường học' },
                'co-quan':       { color: '#9C27B0', icon: 'ph-bank',             name: 'Cơ quan' },
                'du-an':         { color: '#FF9800', icon: 'ph-projector-screen', name: 'Dự án' },
                'dat-cong':      { color: '#009688', icon: 'ph-tree',             name: 'Đất công' },
                'phan-anh':      { color: '#F44336', icon: 'ph-warning',          name: 'Phản ánh' },
                'ho-kinh-doanh': { color: '#FFC107', icon: 'ph-storefront',       name: 'Hộ kinh doanh' },
                'nha-tro':       { color: '#795548', icon: 'ph-house-line',       name: 'Nhà trọ' },
            };

            const reportData = @json($reports ?? []);

            document.getElementById('sidebar-toggle-btn').addEventListener('click', function() {
                const sidebar = document.getElementById('digitalmaps-sidebar');
                sidebar.classList.toggle('collapsed');
                setTimeout(() => {
                    if (map) {
                        map.resize();
                    }
                }, 300);
            });

            function initMap() {
                map = new maplibregl.Map({
                    container:        'digitalmaps-map',
                    style:            MAP_STYLE,
                    center:           [INIT_LNG, INIT_LAT],
                    zoom:             INIT_ZOOM,
                    minZoom:          8,
                    attributionControl: true,
                    customAttribution: [
                        '<a href="https://openfreemap.org" target="_blank">© OpenFreeMap</a>',
                        '<a href="https://www.openmaptiles.org" target="_blank">© OpenMapTiles</a>',
                        '<a href="https://www.openstreetmap.org/copyright" target="_blank">© OpenStreetMap contributors</a>',
                    ],
                });

                map.on('load', () => {
                    createMarkers();
                    bindLayerToggle();
                    bindControls();
                    searchLayer();
                });
            }

            function createMarkers() {
                reportData.forEach(item => {
                    const config = categoryConfig[item.category] ?? categoryConfig['co-quan'];

                    const el = document.createElement('div');
                    el.className = 'digitalmaps-marker';
                    el.style.backgroundColor = config.color;
                    el.innerHTML = `<i class="ph ${config.icon}"></i>`;

                    const popupHtml = `
                        <h3 class="digitalmaps-popup-title">${item.title}</h3>
                        <span class="digitalmaps-popup-type">${config.name}</span>
                        <p class="digitalmaps-popup-address"><i class="ph ph-map-pin"></i> ${item.lat}, ${item.lng}</p>
                        <a href="/reports/${item.id}" class="digitalmaps-popup-btn">Xem chi tiết</a>
                    `;

                    const popup = new maplibregl.Popup({
                        offset:       25,
                        closeButton:  true,
                        closeOnClick: false,
                    }).setHTML(popupHtml);

                    const marker = new maplibregl.Marker({ element: el })
                        .setLngLat([item.lng, item.lat])
                        .setPopup(popup)
                        .addTo(map);

                    markers.push({
                        instance: marker,
                        category: item.category,
                        visible:  true,
                    });
                });
            }

            function bindLayerToggle() {
                const toggleAll      = document.getElementById('toggle-all-layers');
                const layerCheckboxes = document.querySelectorAll('.layer-toggle-checkbox');

                toggleAll.addEventListener('change', function () {
                    layerCheckboxes.forEach(cb => { cb.checked = this.checked; });
                    toggleAllLayers(this.checked);
                });

                layerCheckboxes.forEach(cb => {
                    cb.addEventListener('change', function () {
                        const category = this.getAttribute('data-layer');
                        const show     = this.checked;

                        markers.forEach(m => {
                            if (m.category !== category) return;
                            if (show) { m.instance.addTo(map); m.visible = true; }
                            else      { m.instance.remove();   m.visible = false; }
                        });

                        const allChecked = Array.from(layerCheckboxes).every(c => c.checked);
                        toggleAll.checked = allChecked;
                    });
                });
            }

            function toggleAllLayers(show) {
                markers.forEach(m => {
                    if (show && !m.visible) { m.instance.addTo(map); m.visible = true; }
                    if (!show && m.visible) { m.instance.remove();   m.visible = false; }
                });
            }

            function searchLayer() {
                const searchInput = document.getElementById('digitalmaps-search-layer');
                const items = document.querySelectorAll('.layer-item-row');

                searchInput.addEventListener('input', function () {
                    const val = this.value.toLowerCase().trim();
                    items.forEach(item => {
                        const name = item.getAttribute('data-name') ?? '';
                        item.style.display = name.includes(val) ? 'flex' : 'none';
                    });
                });
            }

            function bindControls() {
                document.getElementById('btn-zoom-in') .addEventListener('click', () => map.zoomIn());
                document.getElementById('btn-zoom-out').addEventListener('click', () => map.zoomOut());
                document.getElementById('btn-compass') .addEventListener('click', () => map.resetNorth());

                function geolocate() {
                    if (!navigator.geolocation) return;
                    navigator.geolocation.getCurrentPosition(pos => {
                        map.flyTo({
                            center: [pos.coords.longitude, pos.coords.latitude],
                            zoom:   14,
                        });
                    });
                }

                document.getElementById('btn-geolocate').addEventListener('click', geolocate);
                document.getElementById('btn-locate')   .addEventListener('click', geolocate);

                document.getElementById('btn-reset').addEventListener('click', () => {
                    map.flyTo({ center: [INIT_LNG, INIT_LAT], zoom: INIT_ZOOM });
                });

                document.getElementById('btn-fullscreen').addEventListener('click', () => {
                    const container = document.getElementById('digitalmaps-fullscreen-container');
                    if (!document.fullscreenElement) {
                        container.requestFullscreen().catch(err => {
                            console.error('Fullscreen error:', err.message);
                        });
                    } else {
                        document.exitFullscreen();
                    }
                });
            }

            initMap();
        });
    </script>
@endpush