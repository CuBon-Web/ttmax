@extends('layouts.main.master')
@section('title')
Liên hệ với chúng tôi
@endsection
@section('description')
Liên hệ với chúng tôi
@endsection
@section('image')
{{url(''.$setting->logo)}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<section class="page-title">
    <div class="auto-container">
      <div class="title-outer text-center"> <div class="h1 title">Contact Us</div>
        <ul class="page-breadcrumb">
          <li><a href="{{route('home')}}">Home</a></li>
          <li>Contact Us</li>
        </ul>
      </div>
    </div>
  </section>
  <section class="contact-details">
    <div class="container pt-110 pb-70">
      <div class="row">
        <div class="col-xl-7 col-lg-6">
          <div class="sec-title black">
             <div class="h3">Feel free to write</div>
          </div>
          <!-- Contact Form -->
          <form id="contact_form" name="contact_form" action="{{route('postcontact')}}" method="post" novalidate="novalidate">
            @csrf
            <div class="row">
              <div class="col-sm-6">
                <div class="mb-3">
                  <input name="name" class="form-control" type="text" placeholder="Enter Name">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="mb-3">
                  <input name="email" class="form-control required email" type="email" placeholder="Enter Email">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <div class="mb-3">
                  <input name="phone" class="form-control" type="text" placeholder="Enter Phone">
                </div>
              </div>
            </div>
            <div class="mb-3">
              <textarea name="mess" class="form-control required" rows="7" placeholder="Enter Message"></textarea>
            </div>
            <div class="mb-5">
              <input name="form_botcheck" class="form-control" type="hidden" value="">
              <button type="submit" class="theme-btn btn-style-one"><span class="btn-title">Send message</span></button>
            </div>
          </form>
        </div>
        <div class="col-xl-5 col-lg-6">
          <div class="contact-details__right">
            <div class="sec-title black">
               <div class="h3">Get in touch with us</div>
              <div class="text">{{$setting->webname}}</div>
            </div>
            <ul class="list-unstyled contact-details__info">
              <li>
                <div class="icon">
                  <span class="fa-classic fa-light fa-phone-plus"></span>
                </div>
                <div class="text"> <div class="h6 mb-1">Have any question?</div>
                  <a href="tel:{{$setting->phone1}}"><span>Free</span> {{$setting->phone1}}</a>
                </div>
              </li>
              <li>
                <div class="icon">
                  <span class="fal fa-envelope"></span>
                </div>
                <div class="text"> <div class="h6 mb-1">Write email</div>
                  <a href="mailto:{{$setting->email}}">{{$setting->email}}</a>
                </div>
              </li>
              <li>
                <div class="icon">
                  <span class="fal fa-location-arrow"></span>
                </div>
                <div class="text"> <div class="h6 mb-1">Visit anytime</div>
                  <span>{{$setting->address1}}</span>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>





@endsection