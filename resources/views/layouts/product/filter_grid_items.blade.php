@if (count($product) > 0)
    @foreach ($product as $item)
        @php
            $itemTags = [];
            if (!empty($item->tags)) {
                $decodedTags = json_decode($item->tags, true);
                if (is_array($decodedTags)) {
                    $itemTags = $decodedTags;
                }
            }
            $itemPriceForFilter = (float) ($item->discount > 0 ? $item->discount : $item->price);
            $img = json_decode($item->images, true) ?? [];
            $productUrl = route('detailProduct', [
                'cate' => $item->cate_slug,
                'type' => $item->type_slug ? $item->type_slug : 'loai',
                'id' => $item->slug,
            ]);
            $originalPrice = (float) $item->price;
            $salePrice = (float) $item->discount;
        @endphp
        <div
            class="product-block col-lg-4 col-md-4 col-sm-6 item"
            data-tags="{{ implode(',', $itemTags) }}"
            data-name="{{ strtolower(trim((string) languageName($item->name))) }}"
            data-price="{{ $itemPriceForFilter }}"
            data-product-id="{{ $item->id }}"
        >
            <div class="inner-box">
                <div class="image">
                    <a href="{{ $productUrl }}">
                        <img src="{{ $img[0] ?? '' }}" alt="{{ languageName($item->name) }}" loading="lazy" decoding="async">
                    </a>
                </div>
                <div class="content">
                    <div class="h4"><a href="{{ $productUrl }}">{{ languageName($item->name) }}</a></div>
                    <span class="price">
                        @if ($salePrice > 0)
                            {{ number_format($salePrice) }}₫
                            @if ($originalPrice > 0 && $salePrice < $originalPrice)
                                <del>{{ number_format($originalPrice) }}₫</del>
                            @endif
                        @elseif ($originalPrice > 0)
                            {{ number_format($originalPrice) }}₫
                        @else
                            Liên hệ
                        @endif
                    </span>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="col-12">
        <div class="alert alert-light mb-0">Không có sản phẩm nào.</div>
    </div>
@endif
