@extends('layouts.main.master')
@section('title')
    {{ $blog_detail->seo_title ? $blog_detail->seo_title : languageName($blog_detail->title) }}
@endsection
@section('description')
    {{ $blog_detail->meta_description ? $blog_detail->meta_description : languageName($blog_detail->description) }}
@endsection
@section('image')
    {{ url('' . $blog_detail->image) }}
@endsection
@section('schema')
    @php
        $cleanText = function ($value) {
            $text = (string) $value;
            // Remove zero-width chars that usually appear from copy/paste.
            return preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);
        };
        $jsonFlags = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
        $postTitle = $cleanText(languageName($blog_detail->title));
        $postDescription = $cleanText(
            $blog_detail->meta_description ?: strip_tags(languageName($blog_detail->description))
        );
        $postContentText = trim($cleanText(strip_tags(languageName($blog_detail->content))));
        preg_match_all('/[\p{L}\p{N}]+/u', $postContentText, $wordMatches);
        $postWordCount = count($wordMatches[0]);
        $postUrl = url()->current();
        $homeUrl = route('home');
        $categoryUrl = route('listCateBlog', ['slug' => $blog_detail->category]);
        $siteName = $setting->webname ?? ($setting->company ?? 'Website');
        $publisherName = $setting->company ?? $siteName;
        $publisherLogo = !empty($setting->logo) ? url($setting->logo) : url('' . $blog_detail->image);
    @endphp
    <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "WebSite",
      "@id": {!! json_encode(url('/') . '#website', $jsonFlags) !!},
      "url": {!! json_encode(url('/'), $jsonFlags) !!},
      "name": {!! json_encode($siteName, $jsonFlags) !!}
    },
    {
      "@type": "Organization",
      "@id": {!! json_encode(url('/') . '#organization', $jsonFlags) !!},
      "name": {!! json_encode($publisherName, $jsonFlags) !!},
      "url": {!! json_encode(url('/'), $jsonFlags) !!},
      "logo": {
        "@type": "ImageObject",
        "url": {!! json_encode($publisherLogo, $jsonFlags) !!}
      }
    },
    {
      "@type": "BreadcrumbList",
      "@id": {!! json_encode($postUrl . '#breadcrumb', $jsonFlags) !!},
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Trang chủ",
          "item": {!! json_encode($homeUrl, $jsonFlags) !!}
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": {!! json_encode($cleanText(languageName($blog_detail->category)), $jsonFlags) !!},
          "item": {!! json_encode($categoryUrl, $jsonFlags) !!}
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": {!! json_encode($postTitle, $jsonFlags) !!},
          "item": {!! json_encode($postUrl, $jsonFlags) !!}
        }
      ]
    },
    {
      "@type": "BlogPosting",
      "@id": {!! json_encode($postUrl . '#article', $jsonFlags) !!},
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": {!! json_encode($postUrl, $jsonFlags) !!}
      },
      "headline": {!! json_encode($postTitle, $jsonFlags) !!},
      "description": {!! json_encode($postDescription, $jsonFlags) !!},
      "articleSection": {!! json_encode($cleanText(languageName($blog_detail->category)), $jsonFlags) !!},
      "keywords": {!! json_encode(implode(', ', $blogTags ?? []), $jsonFlags) !!},
      "inLanguage": "vi-VN",
      "wordCount": {{ $postWordCount }},
      "datePublished": {!! json_encode(optional($blog_detail->created_at)->toIso8601String(), $jsonFlags) !!},
      "dateModified": {!! json_encode(optional($blog_detail->updated_at)->toIso8601String(), $jsonFlags) !!},
      "image": [
        {
          "@type": "ImageObject",
          "url": {!! json_encode(url(''.$blog_detail->image), $jsonFlags) !!}
        }
      ],
      "author": {
        "@type": "Person",
        "name": {!! json_encode($cleanText($blog_detail->author ?: 'Admin'), $jsonFlags) !!}
      },
      "publisher": {
        "@type": "Organization",
        "@id": {!! json_encode(url('/') . '#organization', $jsonFlags) !!}
      }
    }
  ]
}
</script>
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<section class="page-title">
    <div class="auto-container">
      <div class="title-outer text-center"> <div class="h1 title">{{languageName($blog_detail->title)}}</div>
        <ul class="page-breadcrumb">
          <li><a href="{{route('home')}}">Home</a></li>
          <li><a href="{{route('listCateBlog',['slug'=>$blog_detail->category])}}">{{languageName($blog_detail->cate->name)}}</a></li>
          <li>{{languageName($blog_detail->title)}}</li>
        </ul>
      </div>
    </div>
  </section>
  <section class="blog-details pt-40 pb-40">
    <div class="container">
      <div class="row">
        <div class="col-xl-8 col-lg-7">
          <div class="blog-details__left">
            <div class="blog-details__img">
              <img src="{{url(''.$blog_detail->image)}}" alt="{{languageName($blog_detail->title)}}">
              <div class="blog-details__date">{{date_format($blog_detail->created_at,'m/y')}}</div>
            </div>
            <div class="blog-details__content">
              <ul class="list-unstyled blog-details__meta">
                <li><a href="news-details.html"><i class="fas fa-user-circle"></i> Admin</a> </li>
              </ul> <div class="h3 blog-details__title">{{languageName($blog_detail->title)}}</div>
             <div class="content-post">
               {!! languageName($blog_detail->content) !!}
             </div>
            </div>
            <div class="blog-details__bottom">
              <p class="blog-details__tags">
                <span>Tags</span>
                @forelse (($blogTags ?? []) as $tag)
                <a href="{{ route('listBlogTag', ['tag' => urlencode($tag)]) }}">#{{ $tag }}</a>
                @empty
                <a href="javascript:void(0)">Chưa có tag</a>
                @endforelse
              </p>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-5 blog-details__sidebar-col">
          <div class="sidebar blog-details__sidebar">
            <div class="sidebar__single sidebar__post"> <div class="h3 sidebar__title">Latest Posts</div>
              <ul class="sidebar__post-list list-unstyled">
                @foreach ($blognew as $item)
                <li>
                  <div class="sidebar__post-image"> <img src="{{url(''.$item->image)}}" alt="{{languageName($item->title)}}"> </div>
                  <div class="sidebar__post-content">
                     <div class="h3"> <span class="sidebar__post-content-meta"><i class="fas fa-user-circle"></i>Admin</span> <a href="{{route('detailBlog',['slug'=>$item->slug])}}">{{languageName($item->title)}}</a>
                    </div>
                  </div>
                </li>
                @endforeach
              </ul>
            </div>
            <div class="sidebar__single sidebar__category"> <div class="h3 sidebar__title">Categories</div>
              <ul class="sidebar__category-list list-unstyled">
                @foreach ($categoryhome as $item)
                <li><a href="{{route('allListProCate',['danhmuc'=>$item->slug])}}">{{languageName($item->name)}}<span class="lnr-icon-arrow-right"></span></a> </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
