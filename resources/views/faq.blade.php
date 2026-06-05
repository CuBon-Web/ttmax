@extends('layouts.main.master')
@section('title')
Câu hỏi thường gặp
@endsection
@section('description')
Câu hỏi thường gặp
@endsection
@section('image')
{{url('frontend/images/12.jpg')}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<section class="page-title page-title-6" id="page-title">
    <div class="page-title-wrap bg-overlay bg-overlay-dark-3">
       <div class="bg-section"><img src="{{url('frontend/images/fag.jpg')}}" alt="Background" /></div>
       <div class="container">
          <div class="row">
             <div class="col-12 col-lg-5">
                <div class="title">
                   <h1 class="title-heading">Hỗ trợ khách hàng</h1>
                </div>
             </div>
          </div>
       </div>
    </div>
    <div class="breadcrumb-wrap">
       <div class="container">
          <ol class="breadcrumb d-flex">
             <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
             <li class="breadcrumb-item active" aria-current="page">Hỗ trợ khách hàng</li>
          </ol>
       </div>
    </div>
 </section>
 <section class="faqs faqs-2" id="faqs-1">
    <div class="container">
       <div class="row">
          <div class="col-12 col-lg-6 offset-lg-3">
             <div class="heading heading-18 text-center">
                <p class="heading-subtitle">Bạn đang tìm kiếm điều gì</p>
                <h2 class="heading-title">Câu hỏi thường gặp</h2>
             </div>
          </div>
       </div>
       <div class="accordion accordion-2" id="accordion03">
          <div class="row">
            <div class="col-12 col-lg-6">
                <div class="contact-card">
                    <div class="contact-body">
                       <h5 class="card-heading">Đặt câu hỏi ngay</h5>
                       <form method="post" action="{{route('postcontact')}}">
                         @csrf
                          <div class="row">
                             <div class="col-12 col-md-6">
                                <input class="form-control" type="text" id="contact-name" name="name" placeholder="Họ và tên" required="" />
                             </div>
                             <div class="col-12 col-md-6">
                                <input class="form-control" type="text" id="contact-email" name="email" placeholder="Email" required="" />
                             </div>
                             <div class="col-12 col-md-12">
                                <input class="form-control" type="text" id="contact-phone" name="phone" placeholder="Số điện thoại" required="" />
                             </div>
                             <div class="col-12">
                                <input class="form-control" id="contact-infos" placeholder="Nội dung câu hỏi" name="contact-infos" cols="30" rows="10">
                             </div>
                             <div class="col-12">
                                <button type="submit" class="btn btn--secondary">Gửi <i class="energia-arrow-right"></i></button>
                             </div>
                             <div class="col-12">
                                <div class="contact-result"></div>
                             </div>
                          </div>
                       </form>
                    </div>
                 </div>
            </div>
             <div class="col-12 col-lg-6">
                @forelse($homeFaqs ?? [] as $index => $faq)
                <div>
                    <div class="card{{ $index === 0 ? ' active-acc' : '' }}">
                        <div class="card-heading">
                            <a class="card-link{{ $index === 0 ? '' : ' collapsed' }}"
                                data-bs-toggle="collapse"
                                role="button"
                                aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-controls="faq-collapse-{{ $faq->id }}"
                                href="#faq-collapse-{{ $faq->id }}">{{ $faq->question }}</a>
                        </div>
                        <div class="collapse{{ $index === 0 ? ' show' : '' }}" id="faq-collapse-{{ $faq->id }}" data-bs-parent="#accordion03">
                            <div class="card-body">{!! nl2br(e($faq->answer)) !!}</div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted">Chưa có câu hỏi nào.</p>
                @endforelse
             </div>
             
          </div>
       </div>
    </div>
 </section>
@endsection