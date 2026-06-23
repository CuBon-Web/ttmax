@extends('layouts.main.master')
@section('title')
    {{ $setting->company }}
@endsection
@section('description')
    {{ $setting->webname }}
@endsection
@section('image')
    @php
        $ogBanner = $banner->first();
        $ogImage = $ogBanner && $ogBanner->image ? url($ogBanner->image) : url($setting->logo ?? '');
    @endphp
    {{ $ogImage }}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
     <!-- start banner section two  -->
     <section class="banner-section-two">
        <div class="shape-1 bounce-x"></div>
        <div class="shape-2"><img src="/frontend/images/shape-10.png" alt=""></div>
        <div class="swiper banner-bg-slider">
          <div class="swiper-wrapper">
            @foreach ($banner as $item)
            <div class="swiper-slide">
                <div class="bg bg-image"><img src="{{ $item->image ? url($item->image) : '' }}" alt=""></div>
                <div class="outer-box">
                  <div class="row">
                    <div class="banner-content col-xxl-12">
                      <div class="inner-content wow fadeInUp" data-wow-delay="200ms">
                        <div class="feature-box">
                          <div class="inner-box">
                            <div class="feature">
                              <div class="icon"><img src="/frontend/images/check2-1.png" alt=""></div>
                              <div class="text">Welcome to TTMAX</div>
                            </div>
                          </div>
                        </div>
                        <div class="h1 banner-title">{!!($item->title)!!}</div>
                        <div class="bottom-box">
                          <a class="btn-style-three theme-btn" href="{{$item->link}}">
                            <span class="btn-title">View More</span>
                            <span class="btn-arrow-right"><i class="fa-light fa-arrow-right"></i></span>
                          </a>
                          <div class="text banner-description">{!!languageName($item->description)!!}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          {{-- <div class="banner-slider-pagination"></div> --}}
          <div class="swiper-button-prev"></div>
          <div class="swiper-button-next"></div>
        </div>
      </section>
      <!-- end banner section two  -->
  
      <!-- start about section two  -->
      <section id="discover-about-id" class="about-section-two">
        <div class="auto-container">
          <div class="row">
            <div class="image-column col-xl-6 col-md-7">
              <div class="inner-column wow fadeInUp" data-wow-delay="200ms">
                <div class="image-box">
                  <div class="image one">
                    @if (!empty($gioithieu) && is_array(json_decode($gioithieu->image ?? '[]', true)) && count(json_decode($gioithieu->image, true)) > 0)
                    <img src="{{ json_decode($gioithieu->image, true)[0] }}" alt="">
                    @endif
                    
                  </div>
                  <div class="image two">
                    @if (!empty($gioithieu) && is_array(json_decode($gioithieu->image ?? '[]', true)) && count(json_decode($gioithieu->image, true)) > 1)
                    <img src="{{ json_decode($gioithieu->image, true)[1] }}" alt="">
                    @endif
                  </div>
                </div>
                <div class="info-box">
                  <div class="icon"><img src="/frontend/images/phone-1.png" alt=""></div>
                  <div class="info">
                    <div class="text">Call to anytime</div>
                    <a href="tel:+{{$setting->phone1}}" class="title">{{$setting->phone1}}</a>
                  </div>
                </div>
              </div>
            </div>
            <div class="content-column col-xl-6 col-md-10" style="margin: auto;">
              <div class="inner-column wow fadeInUp" data-wow-delay="400ms">
                <div class="sec-title"> <div class="h6 sub-title">About Us</div> <div class="h2 title ">{{$setting->company}}</div>
                  <div class="text">{!! $gioithieu->description ?? '' !!}</div>
                </div>
                <a class="btn-style-three theme-btn" href="{{route('aboutUs')}}">
                  <span class="btn-title">View More </span>
                  <span class="btn-arrow-right"><i class="fa-light fa-arrow-right"></i></span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- end about section two  -->
  
      <!-- Services Section -->
      <section class="services-section">
        <div class="auto-container">
          <div class="row outer-box">
            <div class="col-xl-12">
              <div class="sec-title text-center"> <div class="h6 sub-title">Products</div> <div class="h2 title ">OUR BUSINESS</div>
              </div>
            </div>
            @foreach ($categoryhome as $key => $item)
            <div class="col-xl-4">
              <div class="service-block">
                <div class="inner-block">
                  <div class="content">
                    <div class="count">0{{$key + 1}}</div> <div class="h4 title"><a href="{{route('allListProCate',['danhmuc'=>$item->slug])}}">{{languageName($item->name)}}</a></div>
                  </div>
                  <div class="image">
                    <a href="{{route('allListProCate',['danhmuc'=>$item->slug])}}">
                      <img src="{{ $item->avatar ? url($item->avatar) : '' }}" alt="{{ languageName($item->name) }}">
                      <img src="{{ $item->avatar ? url($item->avatar) : '' }}" alt="{{ languageName($item->name) }}">
                      <div class="text">{!!languageName($item->content)!!}</div>
                    </a>
                  </div>
                  <a class="btn-more" href="{{route('allListProCate',['danhmuc'=>$item->slug])}}"><span class="btn-title">View More</span> <span class="line"></span> <span class="arrow fal fa-arrow-right"></span></a>
                </div>
              </div>
            </div>
            @endforeach
            
          </div>
        </div>
      </section>
      <!-- End Services Section -->
      <!-- Featured Products -->
      <section class="featured-products-section">
        <div class="auto-container">
          <div class="sec-title text-center">
            <div class="h6 sub-title">Products</div>
            <div class="h2 title">OUR PRODUCTS</div>
          </div>
          @php $featuredProducts = $homePro ?? collect(); @endphp
          @if ($featuredProducts->isNotEmpty())
          <div class="featured-products-slider-outer">
            <div class="swiper featured-products-slider">
              <div class="swiper-wrapper">
                @foreach ($featuredProducts as $pro)
                <div class="swiper-slide">
                  @include('layouts.product.home_item', ['pro' => $pro])
                </div>
                @endforeach
              </div>
            </div>
            <div class="featured-products-nav" aria-label="Product slider navigation">
              <div class="featured-products-button-prev swiper-button-prev" aria-label="Previous"></div>
              <div class="featured-products-pagination swiper-pagination"></div>
              <div class="featured-products-button-next swiper-button-next" aria-label="Next"></div>
            </div>
          </div>
          @else
          <p class="featured-products-empty">No featured products.</p>
          @endif
          @if ($featuredProducts->isNotEmpty())
          <div class="featured-products-actions text-center">
            <a class="btn-style-three theme-btn" href="{{ route('allProduct') }}">
              <span class="btn-title">View All Products</span>
              <span class="btn-arrow-right"><i class="fa-light fa-arrow-right"></i></span>
            </a>
          </div>
          @endif
        </div>
      </section>
      <!-- End Featured Products -->
      <!-- Projects Section -->
      <section class="projects-section-two">
        <div class="auto-container">
          <div class="row">
            <div class="col-xl-12">
              <div class="sec-title text-center"> <div class="h6 sub-title">Why Choose Us</div> <div class="h2 title ">Our Difference</div>
              </div>
            </div>
            @foreach ($whyChoose as $key => $item)
            <div class="col-xl-12">
              <div class="project-block-two{{ $key % 2 === 1 ? ' project-block-two--reverse' : '' }}">
                <div class="inner-block">
                  <div class="image">
                    <a href="">
                      <img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ languageName($item->title) }}">
                      <img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ languageName($item->title) }}">
                    </a>
                  </div>
                  <div class="content">
                    <div class="inner"> <div class="h3 title"><a href="">{{languageName($item->title)}}</a></div>
                      <div class="text">{!!languageName($item->description)!!}</div>
                      @if ($item->link)
                        <a class="btn-style-two" href="{{$item->link}}"><span class="btn-title">View Project</span></a>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </section>
      <!-- End Projects Section -->
  
      
  
      <!-- Clients Section One-->
      <section class="clients-section-two">
        <div class="outer-container">
          <div class="outer-box">
            <div class="swiper-container">
              <div class="swiper clients-swiper-two">
                <div class="swiper-wrapper">
                  @foreach ($Partner as $item)
                  <div class="swiper-slide">
                    <div class="client-block-two">
                      <div class="inner-box">
                        <div class="image-box">
                          <figure class="image">
                            <img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->name ?? '' }}">
                            <img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ $item->name ?? '' }}">
                          </figure>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                  
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!--End Clients Section One -->
  
      <!-- Testimonial Section Start -->
      <section class="testimonial-section-two">
        <div class="outer-box">
          <div class="sec-title text-center"> <div class="h6 sub-title">Testimonial</div> <div class="h2 title ">The Best Customers Says About Our Action</div>
          </div>
          <div class="swiper-container">
            <div class="swiper testi-swiper-two pb-0">
              <div class="swiper-wrapper">
                @foreach ($ReviewCus as $item)
                <div class="testimonial-block-two swiper-slide">
                  <div class="inner-block">
                    <div class="author-box">
                      <div class="author-image"><img src="{{ $item->avatar ? url($item->avatar) : '' }}" alt="{{ languageName($item->name) }}"></div>
                      <div class="author-info"> <div class="h5 name">{{languageName($item->name)}}</div>
                        <div class="designation">{{languageName($item->position)}}</div>
                      </div>
                      <div class="quote"><i class="fa fa-quote-right"></i></div>
                    </div>
                    <div class="content">
                      <div class="rating-star">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                      </div>
                      <div class="text">“{!!languageName($item->content)!!}</div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
              <div class="testi-pagination-dots"></div>
            </div>
          </div>
        </div>
      </section>
      <!-- Section end -->
  
      <!-- start video section -->
      <section class="video-section-two">
        <div class="outer-box">
          <div class="bg-image wow reveal-top tm-gsap-img-parallax overflow-hidden"><img src="/frontend/images/video2-1.jpg" alt=""></div>
          <div class="video-box wow fadeInUp animated" data-wow-delay="200ms">
            <a class="play-now-one play-now" href="{{$setting->fax}}" data-fancybox="gallery" data-caption="">
              <i class="fa-sharp fa-solid fa-play"></i>
            </a>
          </div>
        </div>
      </section>
      <!-- end video section -->
  
      <!-- start faq-section-two -->
      <section class="faq-section-two pt-0">
          <div class="auto-container">
            <div class="sec-title text-center"> <div class="h6 sub-title">FAQS</div> <div class="h2 title ">Answers to Your Most Common Queries!</div>
            </div>
            @php
              $homeFaqs = $homeFaqs ?? collect();
              $faqHalf = (int) ceil($homeFaqs->count() / 2);
              $homeFaqsLeft = $homeFaqs->take($faqHalf);
              $homeFaqsRight = $homeFaqs->slice($faqHalf);
            @endphp
            <div class="row">
              <div class="faq-column col-lg-6">
                <div class="inner-column wow fadeInUp" data-wow-delay="200ms">
                  <div class="faq-box">
                    <div class="inner-box">
                      <ul class="accordion-box">
                        @forelse($homeFaqsLeft as $item)
                        <li class="accordion block{{ $loop->first ? ' active-block' : '' }}">
                          <div class="acc-btn">{{ languageName($item->question) }}
                            <i class="fa-solid fa-plus"></i>
                          </div>
                          <div class="acc-content{{ $loop->first ? ' current' : '' }}">
                            <div class="content">
                              <div class="text">{!! languageName($item->answer) !!}</div>
                            </div>
                          </div>
                        </li>
                        @empty
                        @endforelse
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="faq-column col-lg-6">
                <div class="inner-column wow fadeInUp" data-wow-delay="200ms">
                  <div class="faq-box">
                    <div class="inner-box">
                      <ul class="accordion-box">
                        @forelse($homeFaqsRight as $item)
                        <li class="accordion block{{ $loop->first ? ' active-block' : '' }}">
                          <div class="acc-btn">{{ languageName($item->question) }}
                            <i class="fa-solid fa-plus"></i>
                          </div>
                          <div class="acc-content{{ $loop->first ? ' current' : '' }}">
                            <div class="content">
                              <div class="text">{!! languageName($item->answer) !!}</div>
                            </div>
                          </div>
                        </li>
                        @empty
                        @endforelse
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </section>
      <!-- end faq-section-two -->
  
      <!-- start contact-section-two -->
      <section class="contact-section-two">
        <div class="outer-box">
          <div class="shape-1"><img src="/frontend/images/shape-15.png" alt=""></div>
          <div class="shape-2 bounce-x"><img src="/frontend/images/shape-16.png" alt=""></div>
          <div class="auto-container">
            <div class="row">
              <div class="content-column col-xl-6">
                <div class="inner-column">
                  <div class="sec-title light"> <div class="h6 sub-title">Contact Us</div> <div class="h2 title">Get in Touch with Our Plumbing Experts</div>
                  </div>
                  <div class="content-box">
                    <div class="inner-box">
                      <div class="content">
                        <div class="info-box">
                          <div class="icon"><img src="/frontend/images/contact2-1.png" alt=""></div>
                          <div class="info">
                            <span>Phone:</span>
                            <span>{{$setting->phone1}}</span> 
                          </div>
                        </div>
                        <div class="info-box">
                          <div class="icon"><img src="/frontend/images/contact2-2.png" alt=""></div>
                          <div class="info">
                            <span>Email:</span>
                            <span><a href="mailto:{{$setting->email}}">{{$setting->email}}</a></span> 
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-column col-xl-6">
                <div class="inner-column">
                  <form action="{{route('postcontact')}}" method="post">
                    @csrf
                    <div class="row">
                      <div class="col-md-6 wow fadeInUp animated" data-wow-delay=".2s">
                        <div class="form-clt">
                          <input type="text" name="name" id="name" placeholder="Full Name*" required value="{{old('name')}}">
                        </div>
                      </div>
                      <div class="col-md-6 wow fadeInUp animated" data-wow-delay=".4s">
                        <div class="form-clt">
                          <input type="email" name="form_email" id="email" placeholder="Email Address*" value="{{old('email')}}">
                        </div>
                      </div>
                      <div class="col-md-12 wow fadeInUp animated" data-wow-delay=".6s">
                        <div class="form-clt">
                          <input name="tel" id="tel" type="text" placeholder="Phone*" value="{{old('tel')}}">
                        </div>
                      </div>
                      <div class="col-md-12 wow fadeInUp animated" data-wow-delay=".2s">
                        <div class="form-clt">
                          <textarea name="form_message" class="required" placeholder="Type Your Message" value="{{old('form_message')}}"></textarea>
                        </div>
                      </div>
                      <div class="col-md-12 wow fadeInUp animated" data-wow-delay=".2s">
                        <button class="btn-style-three theme-btn" type="submit">
                          <span class="btn-title">Send Message </span>
                          <span class="btn-arrow-right"><i class="fa-light fa-arrow-right"></i></span>
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <!-- end contact-section-two -->
  
      <!-- blog-section-two Start -->
      <section class="blog-section-two">
        <div class="auto-container">
          <div class="sec-title-box">
            <div class="sec-title"> <div class="h6 sub-title">Our Blog</div> <div class="h2 title">Check out latest news update & articles</div>
            </div>
          </div>
        </div>
        <div class="outer-box">
          <div class="swiper-container blog-two-slider pb-0 overflow-hidden">
            <div class="swiper-wrapper">
              @foreach ($hotnews as $item)
              <div class="swiper-slide">
                <!-- News Block -->
                <div class="blog-block-two">
                  <div class="inner-block">
                    <div class="image">
                      <a href="{{route('detailBlog',['slug'=>$item->slug])}}">
                        <img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ languageName($item->title) }}">
                        <img src="{{ $item->image ? url($item->image) : '' }}" alt="{{ languageName($item->title) }}">
                      </a>
                    </div>
                    <div class="content">
                      <div class="date"><i class="fa-solid fa-calendar"></i> {{date_format($item->created_at,'d/m/Y')}}</div> <div class="h3 title"><a href="{{route('detailBlog',['slug'=>$item->slug])}}">{{languageName($item->title)}}</a></div>
                      <div class="text line_2">{!!languageName($item->description)!!}</div>
                    </div>
                    <a class="btn-more" href="{{route('detailBlog',['slug'=>$item->slug])}}"><span class="btn-title">Read More</span> <span class="line"></span> <span class="arrow fal fa-arrow-right"></span></a>
                  </div>
                </div>
              </div>
              @endforeach
              
            </div>
          </div>
        </div>
      </section>
      <!-- blog-section-two Section -->
@endsection
