<script type="text/javascript">
    var url = '{{ asset('/') }}';
</script>

<script src="{{asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/feather-icons/feather.min.js')}}"></script>
<script src="{{asset('assets/admin/js/pages/plugins/lord-icon-2.1.0.js')}}"></script>
<script src="{{asset('assets/admin/js/plugins.js')}}"></script>

<!-- apexcharts -->
<script src="{{asset('assets/admin/libs/apexcharts/apexcharts.min.js')}}"></script>

<!-- Vector map-->
<script src="{{asset('assets/admin/libs/jsvectormap/js/jsvectormap.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/jsvectormap/maps/world-merc.js')}}"></script>

<!--Swiper slider js-->
<script src="{{asset('assets/admin/libs/swiper/swiper-bundle.min.js')}}"></script>

<!-- Dashboard init -->
<script src="{{asset('assets/admin/js/pages/dashboard-ecommerce.init.js')}}"></script>

<!-- sortablejs -->
<script src="{{asset('assets/admin/libs/sortablejs/Sortable.min.js')}}"></script>

<!-- nestable init js -->
<script src="{{asset('assets/admin/js/pages/nestable.init.js')}}"></script>

<!-- App js -->
<script src="{{asset('assets/admin/js/app.js')}}"></script>

<script src="{{asset('assets/admin/libs/flatpickr/flatpickr.min.js')}}"></script>

<script type='text/javascript' src='https://cdn.jsdelivr.net/npm/toastify-js'></script>

<script src="{{asset('assets/admin/libs/sweetalert2/sweetalert2.min.js')}}"></script>

@if(View::hasSection('particles') || request()->routeIs('staff.login') || request()->routeIs('staff.forgot-password') || request()->routeIs('staff.reset-password.form'))
<script src="{{asset('assets/admin/libs/particles.js/particles.js')}}"></script>
<!-- particles app js -->
<script>
    // Only initialize particles if the container exists
    if (document.getElementById('auth-particles')) {
        particlesJS("auth-particles",{particles:{number:{value:90,density:{enable:!0,value_area:800}},color:{value:"#ffffff"},shape:{type:"circle",stroke:{width:0,color:"#000000"},polygon:{nb_sides:5},image:{src:"img/github.svg",width:100,height:100}},opacity:{value:.8,random:!0,anim:{enable:!0,speed:1,opacity_min:0,sync:!1}},size:{value:4,random:!0,anim:{enable:!1,speed:4,size_min:.2,sync:!1}},line_linked:{enable:!1,distance:150,color:"#ffffff",opacity:.4,width:1},move:{enable:!0,speed:2,direction:"none",random:!1,straight:!1,out_mode:"out",attract:{enable:!1,rotateX:600,rotateY:1200}}},interactivity:{detect_on:"canvas",events:{onhover:{enable:!0,mode:"bubble"},onclick:{enable:!0,mode:"repulse"},resize:!0},modes:{grab:{distance:400,line_linked:{opacity:1}},bubble:{distance:400,size:4,duration:2,opacity:.8,speed:3},repulse:{distance:200},push:{particles_nb:4},remove:{particles_nb:2}}},retina_detect:!0,config_demo:{hide_card:!1,background_color:"#b61924",background_image:"",background_position:"50% 50%",background_repeat:"no-repeat",background_size:"cover"}});
    }
</script>
@endif
<!-- password-addon init -->
<script src="{{asset('assets/admin/js/pages/password-addon.init.js')}}"></script>

<script src="{{asset('assets/admin/libs/datatables/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/datatables/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/datatables/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/datatables/js/dataTables.buttons.min.js')}}"></script>
{{--<script src="https://cdn.datatables.net/select/3.0.0/js/dataTables.select.min.js"></script>--}}
<script src="{{asset('assets/admin/libs/datatables/js/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/datatables/js/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/datatables/js/vfs_fonts.js')}}"></script>
<script src="{{asset('assets/admin/libs/datatables/js/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/datatables/js/jszip.min.js')}}"></script>

<script src="{{ url('assets/admin/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js') }}"></script>

<!-- quill js -->
{{--<script src="{{asset('assets/admin/libs/quill/quill.min.js')}}"></script>--}}

<!-- init js -->
{{--<script src="{{asset('assets/admin/js/pages/form-editor.init.js')}}"></script>--}}


<!-- filepond js -->
<script src="{{asset('assets/admin/libs/filepond/filepond.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/filepond-plugin-file-validate-type/filepond-plugin-file-validate-type.min.js')}}"></script>

<!--nft create init js-->
<script src="{{asset('assets/admin/js/pages/apps-nft-create.init.js')}}"></script>

<!-- profile-setting init js -->
<script src="{{asset('assets/admin/js/pages/profile-setting.init.js')}}"></script>

<!-- select2 min js -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<!-- glightbox js -->
<script src="{{asset('assets/admin/libs/glightbox/js/glightbox.min.js')}}"></script>

<!-- isotope-layout -->
<script src="{{asset('assets/admin/libs/isotope-layout/isotope.pkgd.min.js')}}"></script>

<script src="{{asset('assets/admin/js/pages/gallery.init.js')}}"></script>


{{--TODO :: need to add js as we use plugins--}}



<script>
    $(document).ready(function () {
        $('.select2-multiple').select2();

        FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginFileValidateType);
        const multiImgPond = FilePond.create(document.querySelector('.filepond-multiple-images'), {
            allowMultiple: true,
            acceptedFileTypes: ['image/*'],
            labelFileTypeNotAllowed: 'Only image files are allowed.',
            fileValidateTypeDetectType: (source, type) => Promise.resolve(type),
        });
        multiImgPond.on('addfile', (error, file) => {
            if (error) {
                setTimeout(() => {
                    multiImgPond.removeFile(file.id);
                }, 2000); // wait 2 seconds to show the error
            }
        });
        FilePond.create(document.querySelector('.form-filepond-images'), {
            acceptedFileTypes: ['image/*'],
            labelFileTypeNotAllowed: 'Only image files are allowed.',
            fileValidateTypeDetectType: (source, type) => Promise.resolve(type),
        });

    });
</script>
