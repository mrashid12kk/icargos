
 <!-- Footer -->
            <footer class="mobile-hide-footer">
                <div class="footer-main pad-120 white-clr">
                    <div class="theme-container container">               
                        <div class="row">
                            <div class="col-md-3 col-sm-6 footer-widget">
                                <a href="index.php#" style="width: 200px;
                                display: inline-block;
                                position: relative;"> 
                                <span class="site-logo footer-animater-logo"> 
                                <!-- <img src="images/happy gif 220x108.png" alt="logo" />  -->
                                <img class="footer-animater" src="assets/img/logo/footer_btm-img.png"> 
                                <img class="animate" src="assets/img/logo/hap2.png" > 
                                </span>
                                </a>
                            </div>
                            <div class="col-md-3 col-sm-6 footer-widget">
                                <h2 class="title-1 fw-900">quick links</h2>
                                <ul>
                                    <li> <a href="index.php">Home</a> </li>
                                    <li> <a href="about-us.php">About</a> </li>
                                    <li> <a href="contact-us.php">Contact</a> </li>
                                    
                                </ul>
                            </div>
                            <div class="col-md-3 col-sm-6 footer-widget">
                                <h2 class="title-1 fw-900">important links</h2>
                                <ul>
                                    
                                    <li> <a href="tracking.php">Tracking</a> </li>
                                    <li> <a href="privacy1.php">Privacy Policy</a> </li>
                                    <li> <a href="terms_conditions.php">Terms & Conditions</a> </li>
                                </ul>
                            </div>
                            <div class="col-md-3 col-sm-6 footer-widget">
                                <h2 class="title-1 fw-900">get in touch</h2>
                                
                                
                                
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer-bottom">
                    <div class="theme-container container">               
                        <div class="row">
                            <div class="col-md-6 col-sm-6">
                                <p> © Copyright 2019, Transco Logistics Courier Services | All rights reserved </p>                            
                            </div>
                            <div class="col-md-6 col-sm-6 text-right">
                                <p> Design and Developed by <a href="https://www.itvision.com.pk/" class="main-clr" target="_blank">IT Vision (Pvt.) Ltd.</a> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- /.Footer -->

<div id="footer_wrap">
   <div class="container">
      <div class="clearfix">
           
        <div class="footer_icons chat_icon">
        <a href="orders.php" class="fa fa-shopping-cart">
           <p>Requests</p>
        </a>
        </div>
        <div class="footer_icons">
            <a href="tracking.php" class="fa fa-cog">
               <p>Tracking</p>
            </a>
        </div>
              <div class="footer_icons">
            <a class="shape" href="profile.php">
            <img class="shape-plus" src="http://bityo2.techneez.com/uploads/logo_image/Shape 2.png" alt="">
            <img class="shape-minus" src="http://bityo2.techneez.com/uploads/logo_image/Shape 1.png" alt="">
            </a>
         </div>
          <div class="footer_icons">
            <a href="editprofile.php" class="fa fa-user">
               <p>Edit Profile</p>
            </a>
        </div>    
        <div class="footer_icons chat_icon">
        <a href="logout.php" class="fa fa-sign-out">
           <p>Logout</p>
        </a>
        </div>
           </div>
   </div>
</div>


        </main>
        <!-- / Main Wrapper -->




<script type="text/javascript">
function googleTranslateElementInit() {
    // ,includedLanguages:'en,ar'
  new google.translate.TranslateElement({pageLanguage: 'en'}, 'google_translate_element');
}
</script>

<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <!-- /Popup: Login --> 

        <!-- Search Popup 
        <div class="search-popup">
            <div>
                <div class="popup-box-inner">
                    <form>
                        <input class="search-query" type="text" placeholder="Search and hit enter" />
                    </form>
                </div>
            </div>
            <a href="javascript:void(0)" class="close-search"><i class="fa fa-close"></i></a>
        </div>-->
        <!-- / Search Popup -->

        <!-- Main Jquery JS -->
        <!-- Bootstrap JS -->
        <script src="admin/assets/js/bootstrap/bootstrap.min.js"></script>  
        <script src="admin/assets/js/datatables.min.js"></script>
        <!-- Bootstrap Select JS -->
        <script src="assets/plugins/bootstrap-select-1.10.0/dist/js/bootstrap-select.min.js" type="text/javascript"></script>    
        <!-- OwlCarousel2 Slider JS -->
        <script src="assets/plugins/owl.carousel.2/owl.carousel.min.js" type="text/javascript"></script>   
        <!-- Sticky Header -->
        <script src="assets/js/jquery.sticky.js"></script>
        <!-- Wow JS -->
        <script src="assets/plugins/WOW-master/dist/wow.min.js" type="text/javascript"></script>
        <!-- Data binder -->
		
        <script src="assets/plugins/data.binder.js/data.binder.js" type="text/javascript"></script>
 	 
        <!-- Slider JS -->        


        <!-- Theme JS -->
        <script src="assets/js/theme.js" type="text/javascript"></script>
        <script type="text/javascript">
          $('.dataTable').DataTable({
            scrollY:        "370px",
              scrollCollapse: true,
              ordering: false,
              // pageLength: 5,
              responsive: true,
              dom: "<'row'<'col-sm-4'l><'col-sm-4'f><'col-sm-4 text-right'B>>t<'bottom'p><'clear'>",
              // dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                  {extend: 'copy'},
                  {extend: 'csv'},
                  {extend: 'excel', title: 'ExampleFile'},
                  {extend: 'pdf', title: 'ExampleFile'},
                  {extend: 'print',
                   customize: function (win){
                          $(win.document.body).addClass('white-bg');
                          $(win.document.body).css('font-size', '10px');
                          $(win.document.body).find('table')
                                  .addClass('compact')
                                  .css('font-size', 'inherit');
                  }
                  }
              ]
        })
          $('.simple_dataTable').DataTable({
            scrollY:        "370px",
              scrollCollapse: true,
               ordering: false,
              // pageLength: 5,
              responsive: true,
              dom: "<'row'<'col-sm-4'><'col-sm-4'><'col-sm-4 text-right'>>t<'bottom'p><'clear'>",
              // dom: '<"html5buttons"B>lTfgitp',
              buttons: [
                  {extend: 'copy'},
                  {extend: 'csv'},
                  {extend: 'excel', title: 'ExampleFile'},
                  {extend: 'pdf', title: 'ExampleFile'},
                  {extend: 'print',
                   customize: function (win){
                          $(win.document.body).addClass('white-bg');
                          $(win.document.body).css('font-size', '10px');
                          $(win.document.body).find('table')
                                  .addClass('compact')
                                  .css('font-size', 'inherit');
                  }
                  }
              ]
        })
        </script>
    </body>
    </html>