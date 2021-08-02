@extends('skeleton')
@section('sub-content')
    <style>
        .big-title{
            display: none;
        }
    </style>
    <input style="opacity: 0" type="text" id="qrcode-input" name="qrcode" autofocus>
    <div id="scan-error-message" class="hidden text-center" >
        <h3 style="color: red; max-width: 600px; margin-left: auto; margin-right: auto" class="">@lang('admin.bad_code')</h3>
    </div>
    <div style="display: flex; justify-content: center">
        <img style="margin-left: auto; margin-right: auto; width: 300px;" src="{{asset('img/scan-code.png')}}" alt="">
    </div>
    <h2 style="margin-top: 0" class="text-center text-primary text-uppercase">@lang('admin.process_scan')</h2>

    <script>

    </script>
@endsection


@section('breadcrumb')
    <!--<li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>-->
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection