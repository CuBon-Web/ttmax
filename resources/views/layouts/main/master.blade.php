{{-- https://html.kodesolution.com/2026/fixify-html/page-contact.html --}}
<!DOCTYPE html>
<html lang="en">

<head>
    @php
        $seoCanonical = trim($__env->yieldContent('canonical')) ?: seo_canonical_url();
        $seoRobots = trim($__env->yieldContent('robots')) ?: seo_robots_directive();
        $seoTitle = trim($__env->yieldContent('title'));
        $seoDescription = trim($__env->yieldContent('description'));
        $seoImage = trim($__env->yieldContent('image'));
        $seoSiteName = $setting->webname ?? ($setting->company ?? config('app.name'));
    @endphp
    <meta charset="UTF-8" />
    <meta name="theme-color" content="#d70018">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seoTitle }}</title>
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <meta http-equiv="Content-Language" content="en" />
    <link rel="alternate" href="{{ $seoCanonical }}" hreflang="en" />
    <meta name="description" content="{{ $seoDescription }}">
    <meta name="robots" content="{{ $seoRobots }}" />
    <meta name="googlebot" content="{{ $seoRobots }}">
    <meta name="revisit-after" content="3 days" />
    <meta name="rating" content="General">
    <meta name="application-name" content="{{ $seoSiteName }}" />
    <meta name="theme-color" content="#ed3235" />
    <meta name="msapplication-TileColor" content="#ed3235" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-title" content="{{ $seoSiteName }}" />
    @if($seoImage)
    <link rel="apple-touch-icon-precomposed" href="{{ $seoImage }}" sizes="700x700">
    @endif
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:image" content="{{ $seoImage }}">
    <meta property="og:site_name" content="{{ $seoSiteName }}">
    <meta property="og:image:alt" content="{{ $seoTitle }}">
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="en_US" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $seoTitle }}" />
    <meta name="twitter:description" content="{{ $seoDescription }}" />
    <meta name="twitter:image" content="{{ $seoImage }}" />
    <meta name="twitter:url" content="{{ $seoCanonical }}" />
    <meta itemprop="name" content="{{ $seoTitle }}">
    <meta itemprop="description" content="{{ $seoDescription }}">
    <meta itemprop="image" content="{{ $seoImage }}">
    <meta itemprop="url" content="{{ $seoCanonical }}">
    <link rel="canonical" href="{{ $seoCanonical }}">
    @if($seoImage)
    <link rel="image_src" href="{{ $seoImage }}" />
    @endif
    <link rel="shortcut icon" href="{{url(''.$setting->favicon)}}" type="image/x-icon">
    <link rel="icon" href="{{url(''.$setting->favicon)}}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @hasSection('schema')
        @yield('schema')
    @else
        @include('partials.seo-organization')
    @endif
   <!-- Styles Include -->
    <link href="/frontend/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/frontend/css/style.css" rel="stylesheet" />
    <link href="/frontend/callbutton/call-button.css" rel="stylesheet" />
    <link href="/frontend/googletranslate/google-translate-lang.css" rel="stylesheet" />
   @yield('css')
</head>

<body>
   {{-- EN: HTML gốc từ DB. VI: đặt cookie GT (en → vi) trước khi widget tải. --}}
   <script>
   (function () {
       var KEY = 'gt_site_lang';
       var lang = 'en';
       var expired = 'googtrans=;path=/;expires=Thu, 01 Jan 1970 00:00:00 GMT';
       var host = location.hostname;
       var domains = [null];

       try {
           var stored = localStorage.getItem(KEY);
           if (stored === 'en' || stored === 'vi') lang = stored;
       } catch (e) {}

       if (host && host.indexOf('.') !== -1 && host !== 'localhost') {
           var root = host.replace(/^www\./, '');
           domains.push('.' + root, root);
       }

       domains.forEach(function (d) {
           document.cookie = d ? expired + ';domain=' + d : expired;
       });

       if (lang === 'vi') {
           var secure = location.protocol === 'https:' ? ';Secure' : '';
           document.cookie = 'googtrans=%2Fen%2Fvi;path=/;max-age=31536000;SameSite=Lax' + secure;
       }
   })();
   </script>
   <div id="google_translate_element" aria-hidden="true"></div>
   <div class="page-wrapper">

    <div class="preloader"></div>

    <!-- Back-to-top start -->
    <div class="back-to-top-wrapper">
      <button id="back_to_top" type="button" class="back-to-top-btn">
        <svg width="12" height="7" viewBox="0 0 12 7" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M11 6L6 1L1 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
      </button>
    </div>
    <!-- Back-to-top start -->

   @include('layouts.header.index')
   @yield('content')
   @include('layouts.footer.index')
  </div>
   @include('partials.call-button')
   @include('partials.book-now-modal')
   <script src="/frontend/js/jquery.js"></script>
   <script src="/frontend/js/popper.min.js"></script>
   <script src="/frontend/js/bootstrap.min.js"></script>
   <script src="/frontend/js/jquery.fancybox.js"></script>
   <script src="/frontend/js/jquery-ui.js"></script>
   <script src="/frontend/js/gsap.js"></script>
   <script src="/frontend/js/gsap.min.js"></script>
   <script src="/frontend/js/ScrollTrigger.min.js"></script>
   <script src="/frontend/js/splitType.js"></script>
   <script src="/frontend/js/gsap-scroll-smoother.js"></script>
   <script src="/frontend/js/ScrollSmoother.min.js"></script>
   <script src="/frontend/js/gsap-scroll-to-plugin.js"></script>
   <script src="/frontend/js/SplitText.min.js"></script>
   <script src="/frontend/js/aos.js"></script>
   <script src="/frontend/js/wow.js"></script>
   <script src="/frontend/js/select2.min.js"></script>
   <script src="/frontend/js/appear.js"></script>
   <script src="/frontend/js/knob.js"></script>
   <script src="/frontend/js/swiper.min.js"></script>
   <script src="/frontend/js/custom-gsap.js"></script>
   <script src="/frontend/js/jquery-scrolltofixed-min.js"></script>
   <script src="/frontend/js/script.js"></script>
    <script src="/frontend/callbutton/call-button.js"></script>
    <script src="/frontend/googletranslate/google-translate-lang.js?v=5"></script>
   @yield('js')
</body>

</html>