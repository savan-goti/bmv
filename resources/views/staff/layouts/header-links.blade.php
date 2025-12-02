<!-- App favicon -->
<link rel="shortcut icon" href="{{ \App\Models\Setting::first()->favicon??asset('assets/img/no_img.jpg') }}" type="image/x-icon" />

<!-- quill css -->
{{--<link href="{{asset('assets/admin/libs/quill/quill.core.css')}}" rel="stylesheet" type="text/css" />--}}
{{--<link href="{{asset('assets/admin/libs/quill/quill.bubble.css')}}" rel="stylesheet" type="text/css" />--}}
{{--<link href="{{asset('assets/admin/libs/quill/quill.snow.css')}}" rel="stylesheet" type="text/css" />--}}

<!-- jquery JS -->
<script src="{{asset('assets/js/jquery-3.6.0.js')}}"></script>
<script src="{{asset('assets/js/jquery.validate.js')}}"></script>

<!-- jsvectormap css -->
<link href="{{asset('assets/admin/libs/jsvectormap/css/jsvectormap.min.css')}}" rel="stylesheet" type="text/css"/>

<!--Swiper slider css-->
<link href="{{asset('assets/admin/libs/swiper/swiper-bundle.min.css')}}" rel="stylesheet" type="text/css"/>

<!-- Layout config Js -->
<script src="{{asset('assets/admin/js/layout.js')}}"></script>
<!-- Bootstrap Css -->
<link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
<!-- Icons Css -->
<link href="{{asset('assets/admin/css/icons.min.css')}}" rel="stylesheet" type="text/css"/>
<!-- App Css-->
<link href="{{asset('assets/admin/css/app.min.css')}}" rel="stylesheet" type="text/css"/>
<!-- custom Css-->
<link href="{{asset('assets/admin/css/custom.min.css')}}" rel="stylesheet" type="text/css"/>

<link href="{{asset('assets/admin/libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />

<!--datatable css-->
<link rel="stylesheet" href="{{asset('assets/admin/libs/datatables/css/dataTables.bootstrap5.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/admin/libs/datatables/css/responsive.bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/admin/libs/datatables/css/buttons.dataTables.min.css')}}" />

<!-- dropzone css -->
<link rel="stylesheet" href="{{asset('assets/admin/libs/dropzone/dropzone.css')}}" type="text/css" />

<!-- Filepond css -->
<link rel="stylesheet" href="{{asset('assets/admin/libs/filepond/filepond.min.css')}}" type="text/css" />
<link rel="stylesheet" href="{{asset('assets/admin/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.css')}}">

<!-- select2 min css -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<!-- glightbox css -->
<link href="{{asset('assets/admin/libs/glightbox/css/glightbox.min.css')}}" rel="stylesheet" type="text/css" />

<!-- isotope-layout css -->
<!-- <link href="{{asset('assets/admin/libs/isotope-layout/isotope.pkgd.min.css')}}" rel="stylesheet" type="text/css" /> -->

{{--TODO :: need to add upcoming css as we use the plugin--}}
