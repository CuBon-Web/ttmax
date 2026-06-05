@extends('layouts.main.master')
@section('title')
Về Chúng Tôi
@endsection
@section('description')
{{$setting->company}}
@endsection
@section('css')
@endsection
@section('js')
@endsection
@section('content')
<section class="page-title">
    <div class="auto-container">
      <div class="title-outer text-center"> <div class="h1 title">About Us</div>
        <ul class="page-breadcrumb">
          <li><a href="{{route('home')}}">Home</a></li>
          <li>About Us</li>
        </ul>
      </div>
    </div>
  </section>
  <section class="blog-details pt-40 pb-40">
    <div class="container">
      <div class="row">
        <div class="col-xl-12 col-lg-12">
          <div class="blog-details__left">
            
            <div class="blog-details__content">
              <div class="content-post">
                {!!($gioithieu->content)!!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection