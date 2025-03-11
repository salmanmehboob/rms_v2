<!--**********************************
        Scripts
    ***********************************-->
<!-- Required vendors -->
<script src="{{ Asset('assets/vendor/global/global.min.js') }}"></script>
<script src="{{ Asset('assets/vendor/chart.js/Chart.bundle.min.js')  }}"></script>
<script src="{{ Asset('assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js')  }}"></script>
<script src="{{ Asset('assets/vendor/apexchart/apexchart.js')  }}"></script>

<!-- Dashboard 1 -->
<script src="{{ Asset('assets/js/dashboard/dashboard-1.js')  }}"></script>
<script src="{{ Asset('assets/vendor/swiper/js/swiper-bundle.min.js')  }}"></script>


<!-- JS for dotted map -->
<script src="{{ Asset('assets/vendor/dotted-map/js/contrib/jquery.smallipop-0.3.0.min.js')  }}"></script>
<script src="{{ Asset('assets/vendor/dotted-map/js/contrib/suntimes.js')  }}"></script>
<script src="{{ Asset('assets/vendor/dotted-map/js/contrib/color-0.4.1.js')  }}"></script>

<script src="{{ Asset('assets/vendor/dotted-map/js/world.js')  }}"></script>
<script src="{{ Asset('assets/vendor/dotted-map/js/smallimap.js')  }}"></script>
<script src="{{ Asset('assets/js/dashboard/dotted-map-init.js')  }}"></script>

<!-- Apex Chart -->



<!-- Vectormap -->
<script src="{{ Asset('assets/vendor/jqvmap/js/jquery.vmap.min.js')  }}"></script>
<script src="{{ Asset('assets/vendor/jqvmap/js/jquery.vmap.world.js')  }}"></script>
<script src="{{ Asset('assets/vendor/jqvmap/js/jquery.vmap.usa.js')  }}"></script>
<script src="{{ Asset('assets/js/custom.js')  }}"></script>
<script src="{{ Asset('assets/js/deznav-init.js')  }}"></script>
<script src="{{ Asset('assets/js/demo.js')  }}"></script>
<script src="{{ Asset('assets/js/styleSwitcher.js')  }}"></script>

<script>
var swiper = new Swiper(".mySwiper", {
    slidesPerView: 5,
    //spaceBetween: 30,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    breakpoints: {

        300: {
            slidesPerView: 1,
            spaceBetween: 20,
        },
        416: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        1280: {
            slidesPerView: 4,
            spaceBetween: 10,
        },
        1788: {
            slidesPerView: 5,
            spaceBetween: 20,
        },
    },
});
</script>