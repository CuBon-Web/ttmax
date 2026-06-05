@extends('layouts.main.master')
@section('title')
    {{ $product->seo_title ? $product->seo_title : $product->name }}
@endsection
@section('description')
    {{ $product->meta_description ? $product->meta_description : languageName($product->description) }}
@endsection
@section('image')
    @php
        $img = json_decode($product->images);
        $ungdung = json_decode($product->preserve);
    @endphp
    {{ url('' . $img[0]) }}
@endsection
@section('schema')
    @php
        $cleanText = function ($value) {
            $text = (string) $value;
            // Remove zero-width chars that usually appear from copy/paste.
            return preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);
        };
        $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        $toAbsoluteUrl = function ($path) {
            $value = trim((string) $path);
            if ($value === '') {
                return null;
            }
            if (preg_match('/^https?:\/\//i', $value)) {
                return $value;
            }
            return url($value);
        };

        $productUrl = url()->current();
        $homeUrl = route('home');
        $siteUrl = url('/');
        $categoryUrl = !empty($product->cate_slug) ? route('allListProCate', ['danhmuc' => $product->cate_slug]) : null;
        $siteName = $cleanText(config('app.name', 'Website'));
        $productName = $cleanText($product->name ?? '');
        $productDescription = $cleanText($product->meta_description ?: strip_tags(languageName($product->description)));
        $categoryName = $cleanText(optional($product->cate)->name ?? '');
        $sku = $cleanText($product->sku ?? '');
        $allImages = array_values(array_filter(array_map($toAbsoluteUrl, (array) $img)));
        $primaryImage = $allImages[0] ?? null;

        $price = (float) ($product->price ?? 0);
        $discount = (float) ($product->discount ?? 0);
        $offerPrice = $discount > 0 && $discount < $price ? $discount : $price;
        if ($offerPrice <= 0) {
            $offerPrice = $discount > 0 ? $discount : $price;
        }

        $schemaGraph = [
            [
                '@type' => 'WebSite',
                '@id' => $siteUrl . '#website',
                'url' => $siteUrl,
                'name' => $siteName,
                'inLanguage' => 'vi-VN',
            ],
            [
                '@type' => 'Organization',
                '@id' => $siteUrl . '#organization',
                'name' => $siteName,
                'url' => $siteUrl,
            ],
            [
                '@type' => 'BreadcrumbList',
                '@id' => $productUrl . '#breadcrumb',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Trang chủ',
                        'item' => $homeUrl,
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $categoryName !== '' ? $categoryName : 'Sản phẩm',
                        'item' => $categoryUrl ?: route('allProduct'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 3,
                        'name' => $productName,
                        'item' => $productUrl,
                    ],
                ],
            ],
            [
                '@type' => 'Product',
                '@id' => $productUrl . '#product',
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => $productUrl,
                ],
                'name' => $productName,
                'description' => $productDescription,
                'url' => $productUrl,
                'sku' => $sku !== '' ? $sku : null,
                'category' => $categoryName !== '' ? $categoryName : null,
                'image' => $allImages,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $siteName,
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'url' => $productUrl,
                    'priceCurrency' => 'VND',
                    'price' => $offerPrice > 0 ? number_format($offerPrice, 0, '.', '') : null,
                    'availability' => 'https://schema.org/InStock',
                    'itemCondition' => 'https://schema.org/NewCondition',
                    'seller' => [
                        '@type' => 'Organization',
                        '@id' => $siteUrl . '#organization',
                    ],
                ],
            ],
        ];

        if (!empty($primaryImage)) {
            $schemaGraph[1]['logo'] = [
                '@type' => 'ImageObject',
                'url' => $primaryImage,
            ];
        }

        if (empty($schemaGraph[3]['image'])) {
            unset($schemaGraph[3]['image']);
        }
        if (empty($schemaGraph[3]['sku'])) {
            unset($schemaGraph[3]['sku']);
        }
        if (empty($schemaGraph[3]['category'])) {
            unset($schemaGraph[3]['category']);
        }
        if (empty($schemaGraph[3]['offers']['price'])) {
            unset($schemaGraph[3]['offers']);
        }
    @endphp
    <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@graph' => $schemaGraph], $jsonFlags) !!}</script>
@endsection
@section('css')
<style>
  .product-details__title .product-details__price-old {
    display: block;
    font-size: 18px;
    font-weight: 500;
    color: rgba(var(--theme-color4-rgb), 0.45);
  }
  .product-details__contact-buttons {
    display: flex;
    flex-direction: row;
    flex-wrap: nowrap;
    align-items: stretch;
    gap: 10px;
    margin-top: 24px;
  }
  .product-details__contact-buttons .product-contact-btn {
    flex: 1 1 0;
    min-width: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 11px 10px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    line-height: 1.2;
    text-align: center;
    text-decoration: none;
    color: #fff;
    border: none;
    white-space: nowrap;
    transition: opacity 0.25s ease, transform 0.25s ease;
  }
  .product-details__contact-buttons .product-contact-btn:hover {
    opacity: 0.92;
    transform: translateY(-1px);
    color: #fff;
  }
  .product-details__contact-buttons .product-contact-btn i {
    flex-shrink: 0;
    font-size: 15px;
  }
  .product-contact-btn--phone {
    background-color: var(--theme-color1);
  }
  .product-contact-btn--facebook {
    background-color: #1877f2;
  }
  .product-contact-btn--zalo {
    background-color: #0068ff;
  }
  @media (max-width: 575.98px) {
    .product-details__contact-buttons {
      gap: 6px;
    }
    .product-details__contact-buttons .product-contact-btn {
      padding: 10px 6px;
      font-size: 12px;
      gap: 4px;
      border-radius: 10px;
    }
    .product-details__contact-buttons .product-contact-btn i {
      font-size: 14px;
    }
  }
  .product-detail-gallery {
    position: relative;
  }
  .product-detail-gallery-main {
    border-radius: 20px;
    overflow: hidden;
    background: #f4f6fb;
  }
  .product-detail-gallery-main .image-box {
    display: block;
    margin: 0;
  }
  .product-detail-gallery-main .image-box img {
    width: 100%;
    height: auto;
    min-height: 320px;
    max-height: 520px;
    object-fit: cover;
    display: block;
  }
  .product-detail-gallery-main .swiper-button-prev,
  .product-detail-gallery-main .swiper-button-next {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #fff;
    box-shadow: 0 8px 24px rgba(8, 17, 57, 0.12);
    color: var(--theme-color4);
  }
  .product-detail-gallery-main .swiper-button-prev::after,
  .product-detail-gallery-main .swiper-button-next::after {
    font-size: 16px;
    font-weight: 700;
  }
  .product-detail-gallery-thumbs {
    margin-top: 16px;
  }
  .product-detail-gallery-thumbs .swiper-slide {
    opacity: 0.55;
    cursor: pointer;
    transition: opacity 0.3s ease;
  }
  .product-detail-gallery-thumbs .swiper-slide-thumb-active {
    opacity: 1;
  }
  .product-detail-gallery-thumbs figure {
    margin: 0;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid transparent;
    transition: border-color 0.3s ease;
  }
  .product-detail-gallery-thumbs .swiper-slide-thumb-active figure {
    border-color: var(--theme-color1);
  }
  .product-detail-gallery-thumbs img {
    width: 100%;
    height: 88px;
    object-fit: cover;
    display: block;
  }
  .product-details__specs-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 14px;
    color: var(--headings-color);
  }
  .product-specs-table {
    width: 100%;
    margin: 0;
    border-collapse: collapse;
    font-size: 15px;
  }
  .product-specs-table tr:nth-child(odd) {
    background-color: rgba(var(--theme-color4-rgb), 0.04);
  }
  .product-specs-table td {
    padding: 10px 14px;
    border-bottom: 1px solid rgba(var(--theme-color4-rgb), 0.08);
    vertical-align: top;
  }
  .product-specs-table td:first-child {
    width: 42%;
    font-weight: 600;
    color: var(--headings-color);
  }
  .product-specs-empty {
    color: rgba(var(--theme-color4-rgb), 0.55);
    margin: 0;
  }
</style>
@endsection
@section('js')
<script>
(function () {
    function initProductDetailGallery() {
        var mainEl = document.querySelector('.product-detail-gallery-main');
        if (!mainEl || typeof Swiper === 'undefined') {
            return;
        }
        var thumbsEl = document.querySelector('.product-detail-gallery-thumbs');
        var thumbsSwiper = null;
        if (thumbsEl) {
            thumbsSwiper = new Swiper(thumbsEl, {
                spaceBetween: 12,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
                breakpoints: {
                    0: { slidesPerView: 3 },
                    576: { slidesPerView: 4 },
                    992: { slidesPerView: 5 },
                },
            });
        }
        new Swiper(mainEl, {
            spaceBetween: 10,
            speed: 600,
            navigation: {
                nextEl: '.product-detail-gallery-next',
                prevEl: '.product-detail-gallery-prev',
            },
            thumbs: thumbsSwiper ? { swiper: thumbsSwiper } : undefined,
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProductDetailGallery);
    } else {
        initProductDetailGallery();
    }
})();
</script>
@endsection
@section('content')
@php
    $productImages = array_values(array_filter((array) ($img ?? []), function ($path) {
        return trim((string) $path) !== '';
    }));
    $productTitle = $product->seo_title ?: $product->name;
    $originalPrice = (float) $product->price;
    $salePrice = (float) $product->discount;
    $displayPrice = ($salePrice > 0 && ($originalPrice <= 0 || $salePrice < $originalPrice)) ? $salePrice : $originalPrice;
    $priceLabel = $displayPrice > 0 ? number_format($displayPrice) . '₫' : 'Liên hệ';
    $hotline = trim((string) ($setting->phone1 ?? ''));
    $hotlineDigits = preg_replace('/[^0-9]/', '', $hotline);
    $facebookUrl = trim((string) ($setting->facebook ?? ''));
    $zaloSetting = isset($setting->zalo) ? trim((string) $setting->zalo) : '';
    $zaloUrl = $zaloSetting !== ''
        ? $zaloSetting
        : ($hotlineDigits ? 'https://zalo.me/' . $hotlineDigits : '');
    $zaloMessage = rawurlencode('Tư vấn sản phẩm: ' . $productTitle);
    if ($zaloUrl && strpos($zaloUrl, 'zalo.me') !== false) {
        $zaloUrl .= (strpos($zaloUrl, '?') !== false ? '&' : '?') . 'text=' . $zaloMessage;
    }
    $categoryUrl = !empty($product->cate_slug)
        ? route('allListProCate', ['danhmuc' => $product->cate_slug])
        : route('allProduct');
@endphp
<section class="page-title" style="background-image: url(/frontend/images/resource/page-title.jpg);">
    <div class="auto-container">
      <div class="title-outer text-center">
        <div class="h1 title">{{ $productTitle }}</div>
        <ul class="page-breadcrumb">
          <li><a href="{{ route('home') }}">Trang chủ</a></li>
          @if ($product->cate)
          <li><a href="{{ $categoryUrl }}">{{ languageName($product->cate->name) }}</a></li>
          @endif
          <li>{{ $productTitle }}</li>
        </ul>
      </div>
    </div>
  </section>
  <section class="product-details pt-60">
    <div class="container pb-30">
      <div class="row">
        <div class="col-lg-6 col-xl-6">
          <div class="product-detail-gallery">
            <div class="swiper product-detail-gallery-main">
              <div class="swiper-wrapper">
                @forelse ($productImages as $image)
                <div class="swiper-slide">
                  <figure class="image-box">
                    <a href="{{ url($image) }}" class="lightbox-image" data-fancybox="product-gallery">
                      <img src="{{ url($image) }}" alt="{{ $productTitle }}" loading="lazy">
                    </a>
                  </figure>
                </div>
                @empty
                <div class="swiper-slide">
                  <figure class="image-box">
                    <img src="" alt="{{ $productTitle }}">
                  </figure>
                </div>
                @endforelse
              </div>
              @if (count($productImages) > 1)
              <div class="product-detail-gallery-prev swiper-button-prev" aria-label="Ảnh trước"></div>
              <div class="product-detail-gallery-next swiper-button-next" aria-label="Ảnh tiếp"></div>
              @endif
            </div>
            @if (count($productImages) > 1)
            <div class="swiper product-detail-gallery-thumbs">
              <div class="swiper-wrapper">
                @foreach ($productImages as $image)
                <div class="swiper-slide">
                  <figure><img src="{{ url($image) }}" alt="{{ $productTitle }}" loading="lazy"></figure>
                </div>
                @endforeach
              </div>
            </div>
            @endif
          </div>
        </div>
        <div class="col-lg-6 col-xl-6 product-info">
          <div class="product-details__top">
            <div class="h3 product-details__title">
              {{ $productTitle }}
              <div class="d-flex align-items-center gap-2 mt-3">
                <span>{{ $priceLabel }}</span>
                @if ($salePrice > 0 && $originalPrice > 0 && $salePrice < $originalPrice)
                <del class="product-details__price-old">{{ number_format($originalPrice) }}₫</del>
                @endif
              </div>
            </div>
          </div>
          <div class="product-details__content">
            @php
              $technicalSpecs = [];
              if (!empty($product->size)) {
                  $decodedSpecs = json_decode($product->size, true);
                  if (is_array($decodedSpecs)) {
                      $technicalSpecs = collect($decodedSpecs)
                          ->map(function ($item) {
                              return [
                                  'title' => trim((string) data_get($item, 'title', '')),
                                  'detail' => trim((string) data_get($item, 'detail', '')),
                              ];
                          })
                          ->filter(function ($item) {
                              return $item['title'] !== '' || $item['detail'] !== '';
                          })
                          ->values()
                          ->all();
                  }
              }
            @endphp
            <div class="product-details__specs">
              @if (count($technicalSpecs) > 0)
              <table class="product-specs-table">
                <tbody>
                  @foreach ($technicalSpecs as $spec)
                  <tr>
                    <td>{{ $spec['title'] !== '' ? $spec['title'] : 'Thông số' }}</td>
                    <td>{{ $spec['detail'] !== '' ? $spec['detail'] : '-' }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p class="product-specs-empty">No technical specifications.</p>
              @endif
            </div>
            <br>
            <p class="product-details__content-text2">
              @if ($product->sku)
              <strong>SKU:</strong> {{ $product->sku }}<br>
              @endif
              @if ($product->cate)
              <strong>Danh mục:</strong> <a href="{{ $categoryUrl }}">{{ languageName($product->cate->name) }}</a><br>
              @endif
              Liên hệ để được tư vấn và báo giá chi tiết.
            </p>
          </div>
          <div class="product-details__contact-buttons">
            @if ($hotline)
            <a href="tel:{{ $hotline }}" class="product-contact-btn product-contact-btn--phone" title="Hotline: {{ $hotline }}">
              <i class="fa-solid fa-phone"></i>
              <span>Gọi ngay</span>
            </a>
            @endif
            @if ($facebookUrl)
            <a href="{{ $facebookUrl }}" class="product-contact-btn product-contact-btn--facebook" target="_blank" rel="noopener noreferrer" title="Facebook">
              <i class="fab fa-facebook-f"></i>
              <span>Facebook</span>
            </a>
            @endif
            @if ($zaloUrl)
            <a href="{{ $zaloUrl }}" class="product-contact-btn product-contact-btn--zalo" target="_blank" rel="noopener noreferrer" title="Zalo">
              <i class="fa-solid fa-comment-dots"></i>
              <span>Zalo</span>
            </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="product-description">
    <div class="container pt-0 pb-90">
      <div class="product-discription">
        <div class="tabs-box">
          <div class="tab-btn-box text-center">
            <ul class="tab-btns tab-buttons clearfix">
              <li class="tab-btn active-btn" data-tab="#tab-1">Description</li>
              <li class="tab-btn" data-tab="#tab-2">Specifications</li>
            </ul>
          </div>
          <div class="tabs-content">
            <div class="tab active-tab" id="tab-1">
              <div class="content-post">
                {!! languageName($product->content) !!}
              </div>
            </div>
            <div class="tab" id="tab-2">
              @if (count($technicalSpecs) > 0)
              <table class="product-specs-table">
                <tbody>
                  @foreach ($technicalSpecs as $spec)
                  <tr>
                    <td>{{ $spec['title'] !== '' ? $spec['title'] : 'Thông số' }}</td>
                    <td>{{ $spec['detail'] !== '' ? $spec['detail'] : '-' }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @else
              <p class="product-specs-empty">No technical specifications.</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="related-product">
    <div class="container pt-0 pb-90">
       <div class="h3">Related Products</div>
      <div class="row clearfix">
        <div class="col">
          <!--MixitUp Galery-->
          <div class="mixitup-gallery">
            <div class="row">
              @foreach ($productlq as $item)
              <div class="col-lg-3 col-md-6 col-sm-12 mb-3">
                @include('layouts.product.home_item', ['pro' => $item])
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
