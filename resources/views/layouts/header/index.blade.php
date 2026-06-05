 <!-- Main Header-->
 <header class="main-header header-style-two">
   <div class="header-upper">
     <div class="inner-box">
       <div class="info-box">
         <div class="info">
           <i class="icon fa-regular fa-location-dot"></i>
           <span>{{$setting->address1}}</span>
         </div>
         <div class="info">
           <i class="icon fa-solid fa-envelope"></i>
           <span><a href="mailto:{{$setting->email}}" >{{$setting->email}}</a></span>
         </div>
       </div>
       <div class="info-box">
         <div class="info style-one">
           <i class="icon fa-solid fa-clock"></i>
           <span>Mon - Fri: 9.00 am - 8.00pm </span>
         </div>
       </div>
     </div>
   </div>
   <div class="header-lower wow fadeInUp" data-wow-delay="300ms">
     <div class="inner-container">
       <!-- Main box -->
       <div class="main-box">
         <div class="left-box">
           <div class="logo-box">
             <div class="logo">
               <a href="{{route('home')}}"><img width="80" src="{{url($setting->logo)}}" alt="Logo" /></a>
             </div>
           </div>

           <!--Nav Box-->
           <div class="nav-outer">
             <nav class="nav main-menu">
               <ul class="navigation">
                 <li class="current">
                   <a href="{{route('home')}}">Home</a>
                 </li> 
                 <li><a href="{{route('aboutUs')}}">About Us</a></li>
                 <li class="dropdown"><a href="{{route('allProduct')}}">Product</a>
                   <ul>
                    @foreach ($categoryhome as $item)
                    <li class="{{count($item->typeCate) > 0 ? 'dropdown' : ''}}"><a href="{{route('allListProCate',['danhmuc'=>$item->slug])}}">{{languageName($item->name)}}</a>
                      @if (count($item->typeCate) > 0)
                      <ul>
                         @foreach ($item->typeCate as $i)
                         <li><a href="{{route('allListType',['danhmuc'=>$item->slug,'loaidanhmuc'=>$i->slug])}}">{{languageName($i->name)}}</a></li>
                         @endforeach
                      </ul>
                      <div class="dropdown-btn"><i class="fa fa-angle-down"></i></div>
                      @endif

                    
                  </li>
                  @endforeach
                  </ul>
                 </li>
                 <li>
                  <a href="{{route('processStep')}}">Process</a>
                </li> 
                @foreach ($blogCate as $item)
                <li class="dropdown"><a href="{{route('listCateBlog',['slug'=>$item->slug])}}">{{languageName($item->name)}}</a>
                  <ul>
                    @foreach ($item->typeCate as $type)
                    <li><a href="{{route('listTypeBlog',['slug'=>$type->slug])}}">{{languageName($type->name)}}</a></li>
                    @endforeach
                  </ul>
                </li>
                @endforeach
                 <li><a href="{{route('lienHe')}}">Contact</a></li>
               </ul>
             </nav>
           </div>
         </div>
         <div class="right-box">
           <!-- Phone Box -->
           @include('partials.google-translate-lang')
           <a
             class="theme-btn btn-style-two"
             href="javascript:void(0)"
             data-bs-toggle="modal"
             data-bs-target="#bookNowModal"
             role="button"
           >
             <span class="btn-title">Book Now</span>
           </a>
         </div>
         <!--Mobile Navigation Toggler-->
         <div class="mobile-nav-toggler"><span class="icon lnr-icon-bars"></span></div>
       </div>
     </div>
   </div>

   <!-- Mobile Menu  -->
   <div class="mobile-menu">
     <div class="menu-backdrop"></div>
     <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
     <nav class="menu-box">
       <div class="upper-box">
         <div class="nav-logo">
           <a href="{{route('home')}}"><img width="80" src="{{url($setting->logo)}}" alt="" /></a>
         </div>
         <div class="close-btn"><i class="icon fa fa-times"></i></div>
       </div>
       <ul class="navigation clearfix">
         <!--Keep This Empty / Menu will come through Javascript-->
       </ul>
       <ul class="contact-list-one">
         <li>
           <i class="icon lnr-icon-envelope1"></i>
           <span class="title">Send Email</span>
           <div class="text"><a href="mailto:{{$setting->email}}">{{$setting->email}}</a></div>
         </li>
       </ul>
       <ul class="social-links">
         <li>
           <a href="{{$setting->facebook}}"><i class="icon fab fa-facebook-f"></i></a>
         </li>
         <li>
           <a href="{{$setting->instagram}}"><i class="icon fab fa-instagram"></i></a>
         </li>
         <li>
           <a href="{{$setting->tiktok}}"><i class="icon fab fa-tiktok"></i></a>
         </li>
         <li>
           <a href="{{$setting->youtube}}"><i class="icon fab fa-youtube"></i></a>
         </li>
       </ul>
     </nav>
   </div>
   <!-- End Mobile Menu -->

   <!-- Header Search -->
   <div class="search-popup">
     <span class="search-back-drop"></span>
     <button class="close-search"><span class="fa fa-times"></span></button>

     <div class="search-inner">
       <form method="post" action="">
         <div class="form-group">
           <input type="search" name="search-field" value="" placeholder="Search..." required="" />
           <button type="submit"><i class="fa fa-search"></i></button>
         </div>
       </form>
     </div>
   </div>
   <!-- End Header Search -->

   <!-- Sticky Header  -->
   <div class="sticky-header">
     <div class="outer-container">
       <div class="inner-container">
         <!--Logo-->
         <div class="logo">
           <a href="{{route('home')}}"><img width="80" src="{{url($setting->logo)}}" alt="" /></a>
         </div>

         <div class="right-box">
           <!--Right Col-->
           <div class="nav-outer">
             <!-- Main Menu -->
             <nav class="main-menu">
               <div class="navbar-collapse show collapse clearfix">
                 <ul class="navigation clearfix">
                   <!--Keep This Empty / Menu will come through Javascript-->
                 </ul>
               </div>
             </nav>
             <!-- Main Menu End-->

             <!--Mobile Navigation Toggler-->
             <div class="mobile-nav-toggler"><span class="icon lnr-icon-bars"></span></div>
           </div>
           <a
             class="theme-btn btn-style-one"
             href="javascript:void(0)"
             data-bs-toggle="modal"
             data-bs-target="#bookNowModal"
             role="button"
           >
             <span class="btn-title">Book Now</span>
           </a>
         </div>
       </div>
     </div>
   </div>
   <!-- End Sticky Menu -->
 </header>
 <!--End Main Header -->