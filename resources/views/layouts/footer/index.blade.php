 <!-- Main Footer -->
 <footer class="main-footer footer-style-two">
   <div class="outer-container">
     <div class="auto-container">
       <div class="widgets-section">
         <div class="row">
          <div class="footer-widget contact-widget col-lg-3 offset-lg-1 col-md-6"> <div class="h5 widget-title">Contact</div>
          <div class="widget-content">
            <ul class="social-info">
              <li><a href="{{route('aboutUs')}}">{{$setting->company}}</a></li>
              <li><a href="tel:{{$setting->phone1}}">{{$setting->phone1}}</a></li>
              <li><a href="mailto:{{$setting->email}}">{{$setting->email}}</a></li>
              <li><a href="{{$setting->address1}}">{{$setting->address1}}</a></li>
            </ul>
          </div>
        </div>
           <div class="footer-widget links-widget col-lg-2 offset-lg-1 col-md-6"> <div class="h5 widget-title">Company</div>
             <div class="widget-content">
               <ul class="user-links">
                <li><a href="{{route('home')}}">Home</a></li>
                 <li><a href="{{route('aboutUs')}}">About </a></li>
                 <li><a href="{{route('processStep')}}">Process</a></li>
                 @foreach ($blogCate as $item)
                 <li><a href="{{route('listCateBlog',['slug'=>$item->slug])}}">{{languageName($item->name)}}</a></li>
                 @endforeach
                 <li><a href="{{route('lienHe')}}">Contact Us </a></li>
               </ul>
             </div>
           </div>
           <div class="footer-widget links-widget style-two col-lg-2 col-md-6"> <div class="h5 widget-title">Product</div>
             <div class="widget-content">
               <ul class="user-links">
                 @foreach ($categoryhome as $item)
                 <li><a href="{{route('allListProCate',['danhmuc'=>$item->slug])}}">{{languageName($item->name)}}</a></li>
                 @endforeach
               </ul>
             </div>
           </div> 
          
           <div class="footer-widget links-widget style-two col-lg-2 col-md-6"> <div class="h5 widget-title">Location</div>
             <div class="widget-content">
               {!!$setting->iframe_map!!}
             </div>
           </div> 
         </div>
       </div>
     </div>
     <!-- Widgets Section -->
     <div class="footer-bottom">
       <div class="auto-container">
         <div class="inner-container justify-content-center justify-content-sm-between">
           <p class="copyright-text">© Copyright 2026 by TTMAX, Designed by Tuấn Anh DEV</p>
         </div>
       </div>
     </div></div>
 </footer>
 <!--End Main Footer -->