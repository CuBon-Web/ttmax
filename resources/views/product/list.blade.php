@extends('layouts.main.master')
@section('title')
{{$title}}
@endsection
@section('description')
Danh sách {{$title}}
@endsection
@section('image')
{{url(''.$banner[0]->image)}}
@endsection
@section('js')
<script>
    function initProductFilters() {
        const tagCheckboxes = Array.from(document.querySelectorAll('.js-tag-filter'));
        const keywordInput = document.getElementById('js-dom-filter-keyword');
        const clearBtn = document.getElementById('js-dom-filter-clear');
        const resultCount = document.getElementById('js-dom-filter-count');
        const sortSelect = document.getElementById('js-dom-sort-select');
        const minPriceInput = document.getElementById('js-dom-filter-price-min');
        const maxPriceInput = document.getElementById('js-dom-filter-price-max');
        const applyPriceBtn = document.getElementById('js-dom-filter-price-apply');
        const productRow = document.querySelector('.all-products .row');
        const paginationWrap = document.querySelector('.shop-pagination');
        const csrfToken = document.querySelector('meta[name="csrf-token"]');

        function getFilterState() {
            const selectedTags = tagCheckboxes.filter((checkbox) => checkbox.checked).map((checkbox) => checkbox.value);
            const keyword = (keywordInput ? keywordInput.value : '').trim().toLowerCase();
            const minPrice = minPriceInput && minPriceInput.value !== '' ? Number(minPriceInput.value) : null;
            const maxPrice = maxPriceInput && maxPriceInput.value !== '' ? Number(maxPriceInput.value) : null;
            const sortValue = sortSelect ? sortSelect.value : 'newest';
            return { selectedTags, keyword, minPrice, maxPrice, sortValue };
        }

        function updateVisibleCount(count) {
            const total = Number(count || 0);
            if (resultCount) {
                resultCount.textContent = total;
            }
            const toolbarCount = document.getElementById('js-dom-filter-count-toolbar');
            if (toolbarCount) {
                toolbarCount.textContent = total;
            }
        }

        function applyLocalDomFilter(state) {
            if (!productRow) return;
            const productItems = Array.from(productRow.querySelectorAll('.item'));
            const selectedTags = state.selectedTags || [];
            const keyword = state.keyword || '';
            const minPrice = state.minPrice;
            const maxPrice = state.maxPrice;
            const sortValue = state.sortValue || 'newest';

            productItems.forEach((item) => {
                const itemTags = (item.dataset.tags || '').split(',').map((value) => value.trim()).filter(Boolean);
                const itemName = (item.dataset.name || '').toLowerCase();
                const itemPrice = item.dataset.price ? Number(item.dataset.price) : 0;

                const matchedTag = selectedTags.length === 0 || selectedTags.some((tag) => itemTags.includes(tag));
                const matchedKeyword = keyword === '' || itemName.includes(keyword);
                const matchedMinPrice = minPrice === null || itemPrice >= minPrice;
                const matchedMaxPrice = maxPrice === null || itemPrice <= maxPrice;

                item.style.display = matchedTag && matchedKeyword && matchedMinPrice && matchedMaxPrice ? '' : 'none';
            });

            const sortedItems = [...productItems].sort((a, b) => {
                const aPrice = Number(a.dataset.price || 0);
                const bPrice = Number(b.dataset.price || 0);
                const aId = Number(a.dataset.productId || 0);
                const bId = Number(b.dataset.productId || 0);

                if (sortValue === 'oldest') return aId - bId;
                if (sortValue === 'price_asc') return aPrice - bPrice;
                if (sortValue === 'price_desc') return bPrice - aPrice;
                return bId - aId;
            });
            sortedItems.forEach((item) => productRow.appendChild(item));

            updateVisibleCount(productItems.filter((item) => item.style.display !== 'none').length);
        }

        function applyDomFilter() {
            if (!productRow) return;
            const state = getFilterState();
            applyLocalDomFilter(state);
            const sortMap = {
                newest: 'created-desc',
                oldest: 'created-oldest',
                price_asc: 'price-asc',
                price_desc: 'price-desc'
            };
            const payload = {
                fillter: state.selectedTags,
                sortby: sortMap[state.sortValue] || 'created-desc',
                pricemin: state.minPrice,
                pricemax: state.maxPrice,
                keyword: state.keyword,
                cate: productRow.dataset.cate || '',
                type: productRow.dataset.type || '',
                typetwo: productRow.dataset.typetwo || ''
            };

            fetch('{{ route('filterProduct') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken ? csrfToken.getAttribute('content') : ''
                },
                body: JSON.stringify(payload)
            })
            .then((response) => response.json())
            .then((response) => {
                productRow.innerHTML = response.items_html || '';
                if (paginationWrap) {
                    paginationWrap.innerHTML = response.pagination_html || '';
                }
                applyLocalDomFilter(state);
                updateVisibleCount(response.total || response.count || 0);
            })
            .catch(() => {});
        }

        tagCheckboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', applyDomFilter);
        });

        if (keywordInput) {
            keywordInput.addEventListener('input', applyDomFilter);
        }
        if (sortSelect) {
            sortSelect.addEventListener('change', applyDomFilter);
            sortSelect.addEventListener('input', applyDomFilter);
        }
        document.addEventListener('change', function (event) {
            if (event.target && event.target.id === 'js-dom-sort-select') {
                applyDomFilter();
            }
        });
        document.addEventListener('click', function (event) {
            const optionEl = event.target.closest('.selector .nice-select .option');
            if (!optionEl) return;
            setTimeout(applyDomFilter, 0);
        });
        if (applyPriceBtn) {
            applyPriceBtn.addEventListener('click', function (event) {
                event.preventDefault();
                applyDomFilter();
            });
        }
        if (minPriceInput) {
            minPriceInput.addEventListener('keyup', function (event) {
                if (event.key === 'Enter') {
                    applyDomFilter();
                }
            });
        }
        if (maxPriceInput) {
            maxPriceInput.addEventListener('keyup', function (event) {
                if (event.key === 'Enter') {
                    applyDomFilter();
                }
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function (event) {
                event.preventDefault();
                tagCheckboxes.forEach((checkbox) => {
                    checkbox.checked = false;
                });
                if (keywordInput) {
                    keywordInput.value = '';
                }
                if (minPriceInput) {
                    minPriceInput.value = '';
                }
                if (maxPriceInput) {
                    maxPriceInput.value = '';
                }
                if (sortSelect) {
                    sortSelect.value = 'newest';
                }
                applyDomFilter();
            });
        }

        updateVisibleCount(resultCount ? resultCount.textContent : 0);
    }

    function initShopListUi() {
        const sidebarCol = document.querySelector('.shop-sidebar-col');
        const filterToggle = document.querySelector('.js-shop-filter-toggle');
        const sidebarClose = document.getElementById('js-shop-sidebar-close');
        const sidebarOverlay = document.getElementById('js-shop-sidebar-overlay');
        const productRow = document.querySelector('.all-products .product-list-row');
        const gridViewItems = document.querySelectorAll('.grid-view li');

        function closeSidebar() {
            document.body.classList.remove('shop-filter-open');
            if (filterToggle) {
                filterToggle.setAttribute('aria-expanded', 'false');
            }
        }

        function openSidebar() {
            document.body.classList.add('shop-filter-open');
            if (filterToggle) {
                filterToggle.setAttribute('aria-expanded', 'true');
            }
        }

        if (filterToggle) {
            filterToggle.addEventListener('click', function () {
                if (document.body.classList.contains('shop-filter-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
        }
        if (sidebarClose) {
            sidebarClose.addEventListener('click', closeSidebar);
        }
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }

        const gridClassMap = {
            2: ['col-lg-6', 'col-sm-6'],
            3: ['col-lg-4', 'col-sm-6'],
            4: ['col-lg-3', 'col-md-4', 'col-sm-6'],
        };
        const gridRemoveClasses = ['col-lg-3', 'col-lg-4', 'col-lg-6', 'col-md-4', 'col-sm-6'];

        function applyGridColumns(cols) {
            if (!productRow) return;
            const classes = gridClassMap[cols] || gridClassMap[3];
            productRow.querySelectorAll('.product-block.item').forEach((item) => {
                item.classList.remove(...gridRemoveClasses);
                classes.forEach((className) => item.classList.add(className));
            });
        }

        gridViewItems.forEach((item) => {
            item.addEventListener('click', function () {
                gridViewItems.forEach((el) => el.classList.remove('active'));
                item.classList.add('active');
                const cols = Number(item.dataset.cols || 3);
                applyGridColumns(cols);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            initProductFilters();
            initShopListUi();
        });
    } else {
        initProductFilters();
        initShopListUi();
    }
</script>
@endsection
@section('css')
<style>
    .tag-filter-list {
        max-height: 260px;
        overflow-y: auto;
    }
    .tag-filter-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 14px;
    }
    .tag-filter-item label {
        margin-bottom: 0;
        cursor: pointer;
    }
    .dom-filter-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 10px;
        font-size: 13px;
        color: #6b7280;
    }
    .price-filter-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 8px;
    }
    .shop-list-page {
        padding: 40px 0 40px;
    }
    .shop-list-page .shop-sidebar .form-control,
    .shop-list-page .shop-sidebar input[type="search"],
    .shop-list-page .shop-sidebar input[type="number"] {
        width: 100%;
        border: 1px solid rgba(var(--theme-color4-rgb), 0.15);
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 14px;
        background: #fff;
    }
    .shop-list-page .shop-filter-btn {
        width: 100%;
        margin-top: 12px;
        justify-content: center;
    }
    .shop-list-page .shop-filter-empty {
        font-size: 14px;
        color: rgba(var(--theme-color4-rgb), 0.6);
    }
    .shop-list-page .category-list li.active a {
        color: var(--theme-color1);
        font-weight: 600;
    }
    .shop-columns-title-section {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        position: sticky;
        top: 80px;
        z-index: 30;
        background: #fff;
        padding: 0px 18px;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.08);
    }
    .shop-columns-title-section .filter-selector {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 12px;
    }
    .shop-columns-title-section .filter {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid rgba(var(--theme-color4-rgb), 0.15);
        border-radius: 8px;
        padding: 8px 14px;
        background: #fff;
        cursor: pointer;
        font-weight: 600;
        color: var(--headings-color);
    }
    .shop-columns-title-section .selector select {
        min-width: 180px;
    }
    .shop-columns-title-section .grid-view {
        display: flex;
        align-items: center;
        gap: 6px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .shop-columns-title-section .grid-view li {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid rgba(var(--theme-color4-rgb), 0.15);
        border-radius: 8px;
        cursor: pointer;
        color: rgba(var(--theme-color4-rgb), 0.55);
        transition: all 0.2s ease;
    }
    .shop-columns-title-section .grid-view li.active,
    .shop-columns-title-section .grid-view li:hover {
        background: var(--theme-color1);
        border-color: var(--theme-color1);
        color: var(--theme-color1-text-color);
    }
    .shop-list-page .product-list-row {
        --bs-gutter-x: 24px;
        --bs-gutter-y: 24px;
    }
    .shop-list-page .product-block {
        margin-bottom: 0;
    }
    .shop-list-page .product-block .image img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }
    .shop-pagination {
        margin-top: 40px;
        display: flex;
        justify-content: center;
    }
    .shop-sidebar-close {
        display: none;
    }
    .shop-sidebar-overlay {
        display: none;
    }
    @media (max-width: 991.98px) {
        .shop-columns-title-section {
            top: 70px;
            padding: 10px 12px;
        }
        .shop-columns-title-section .filter {
            display: inline-flex;
        }
        .shop-sidebar-col {
            position: fixed;
            top: 0;
            left: 0;
            width: min(320px, 88vw);
            height: 100vh;
            z-index: 10050;
            padding: 20px 16px;
            background: #fff;
            overflow-y: auto;
            transform: translateX(-105%);
            transition: transform 0.3s ease;
            box-shadow: 8px 0 30px rgba(8, 17, 57, 0.12);
        }
        body.shop-filter-open .shop-sidebar-col {
            transform: translateX(0);
        }
        .shop-sidebar-close {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 12px;
            right: 12px;
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 50%;
            background: var(--theme-color-lighter);
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            z-index: 2;
        }
        .shop-sidebar-overlay {
            display: block;
            position: fixed;
            inset: 0;
            background: rgba(8, 17, 57, 0.45);
            z-index: 10040;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        body.shop-filter-open .shop-sidebar-overlay {
            opacity: 1;
            visibility: visible;
        }
    }
    @media (min-width: 992px) {
        .shop-columns-title-section .filter {
            display: none;
        }
    }
</style>
@endsection
@section('content')
<section class="page-title">
    <div class="auto-container">
      <div class="title-outer text-center"> <div class="h1 title">{{$title}}</div>
        <ul class="page-breadcrumb">
          <li><a href="{{route('home')}}">Home</a></li>
          <li>{{$title}}</li>
        </ul>
      </div>
    </div>
  </section>
  <section class="featured-products shop-list-page">
    <div class="auto-container">
      <div class="row clearfix">
        <div class="col-lg-3 col-md-12 col-sm-12 shop-sidebar-col">
          <div class="shop-sidebar" id="shop-filter-sidebar">
            <div class="sidebar-widget category-widget">
              <div class="widget-title"><div class="h5 widget-title">Danh mục</div></div>
              <div class="widget-content">
                <ul class="category-list clearfix">
                  <li class="{{ empty($cate_slug ?? '') ? 'active' : '' }}">
                    <a href="{{ route('allProduct') }}">Tất cả sản phẩm</a>
                  </li>
                  @foreach ($categoryhome as $cateItem)
                  <li class="{{ ($cate_slug ?? '') === $cateItem->slug ? 'active' : '' }}">
                    <a href="{{ route('allListProCate', ['danhmuc' => $cateItem->slug]) }}">{{ languageName($cateItem->name) }}</a>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="sidebar-widget price-filters">
              <div class="widget-title"><div class="h5 widget-title">Khoảng giá</div></div>
              <div class="range-wrap">
                <div class="price-filter-row">
                  <input id="js-dom-filter-price-min" type="number" min="0" class="form-control" placeholder="Giá từ">
                  <input id="js-dom-filter-price-max" type="number" min="0" class="form-control" placeholder="Giá đến">
                </div>
                <button type="button" id="js-dom-filter-price-apply" class="theme-btn btn-style-three shop-filter-btn">
                  <span class="btn-title">Áp dụng giá</span>
                </button>
              </div>
            </div>
            <div class="sidebar-widget tag-filter-widget">
              <div class="widget-title"><div class="h5 widget-title">Lọc theo tag</div></div>
              <div class="dom-filter-head">
                <span>Đang hiển thị: <b id="js-dom-filter-count">{{ $list->total() }}</b></span>
                <a href="#" id="js-dom-filter-clear">Xóa lọc</a>
              </div>
              @if (!empty($filter) && count($filter) > 0)
                @foreach ($filter as $tagCate)
                  @if (!empty($tagCate->tags) && count($tagCate->tags) > 0)
                    <div class="check-box-item mb-3">
                      <h6 class="mb-2">{{ $tagCate->name }}</h6>
                      <div class="tag-filter-list">
                        @foreach ($tagCate->tags as $tag)
                          @php $tagValue = $tag->slug . '-' . $tagCate->slug; @endphp
                          <div class="tag-filter-item">
                            <label for="tag-filter-{{ $tagCate->id }}-{{ $tag->id }}">
                              <input
                                type="checkbox"
                                class="js-tag-filter"
                                id="tag-filter-{{ $tagCate->id }}-{{ $tag->id }}"
                                value="{{ $tagValue }}"
                              >
                              {{ $tag->name }}
                            </label>
                          </div>
                        @endforeach
                      </div>
                    </div>
                  @endif
                @endforeach
              @else
                <p class="shop-filter-empty mb-0">Chưa có dữ liệu tag để lọc.</p>
              @endif
            </div>
          </div>
          <button type="button" class="shop-sidebar-close" id="js-shop-sidebar-close" aria-label="Đóng bộ lọc">&times;</button>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 content-side">
          <div class="mixitup-gallery mt-0 mt-lg-0">
            <div class="shop-columns-title-section mb-30">
              <p class="mb-0">Hiển thị <b id="js-dom-filter-count-toolbar">{{ $list->total() }}</b> sản phẩm</p>
              <div class="filter-selector">
                <button type="button" class="filter js-shop-filter-toggle" aria-expanded="false" aria-controls="shop-filter-sidebar">
                  <span class="filter-icon" aria-hidden="true">
                    <i class="fa-solid fa-sliders"></i>
                  </span>
                  <span>Bộ lọc</span>
                </button>
                <div class="selector">
                  <select id="js-dom-sort-select">
                    <option value="newest">Mới nhất (mặc định)</option>
                    <option value="oldest">Cũ nhất</option>
                    <option value="price_asc">Giá thấp đến cao</option>
                    <option value="price_desc">Giá cao đến thấp</option>
                  </select>
                </div>
                <ul class="grid-view" aria-label="Chế độ hiển thị lưới">
                  <li class="column-2" data-cols="2" title="2 cột"><i class="fa-solid fa-grip"></i></li>
                  <li class="column-3" data-cols="3" title="3 cột"><i class="fa-solid fa-grip-vertical"></i></li>
                  <li class="column-4 active" data-cols="4" title="4 cột (mặc định)"><i class="fa-solid fa-table-cells"></i></li>
                </ul>
              </div>
            </div>
            <div class="all-products list-grid-product-wrap">
              <div
                class="row product-list-row gy-4"
                data-cate="{{ $cate_slug ?? '' }}"
                data-type="{{ $type_slug ?? '' }}"
                data-typetwo="{{ $type_two_slug ?? '' }}"
              >
                @include('layouts.product.filter_grid_items', ['product' => $list])
              </div>
            </div>
            <nav class="shop-pagination">
              {{ $list->links() }}
            </nav>
          </div>
        </div>
        <div class="col-12">
          {!!$content!!}
        </div>
      </div>
    </div>
    <div class="shop-sidebar-overlay" id="js-shop-sidebar-overlay"></div>
  </section>
@endsection