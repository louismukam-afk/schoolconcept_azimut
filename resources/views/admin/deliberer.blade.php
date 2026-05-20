@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>

    @endif

    <div class="col-md-6">
        <h3 class="page-header  alert alert-warning">@lang('semestre') 1</h3>
        <ul>
            <li><strong class="col-md-6">CC</strong>
                <?php $finded=false; ?>
            @foreach($verrous as $v)
                @if($v->sequence==1)
                        <a href="{{url('/admin/verrou-note/activate/1')}}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok"></span> @lang('admin.activer')</a>
                    <?php $finded=true;break; ?>
                    @endif
                @endforeach
                @if(!$finded)
                    <a href="{{url('/admin/verrou-note/deactivate/1')}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-ban-circle"></span> @lang('admin.desactiver')</a>
                @endif
            </li>
            <li><strong class="col-md-6">@lang('admin.session_normale')</strong>
                <?php $finded=false; ?>
                @foreach($verrous as $v)
                    @if($v->sequence==3)
                        <a href="{{url('/admin/verrou-note/activate/3')}}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok"></span> @lang('admin.activer')</a>
                        <?php $finded=true;break; ?>
                    @endif
                @endforeach
                @if(!$finded)
                    <a href="{{url('/admin/verrou-note/deactivate/3')}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-ban-circle"></span> @lang('admin.desactiver')</a>
                @endif</li>
            <li><strong class="col-md-6">@lang('admin.rattrapage')</strong>
                <?php $finded=false; ?>
                @foreach($verrous as $v)
                    @if($v->sequence==5)
                        <a href="{{url('/admin/verrou-note/activate/5')}}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok"></span> @lang('admin.activer')</a>
                        <?php $finded=true;break; ?>
                    @endif
                @endforeach
                @if(!$finded)
                    <a href="{{url('/admin/verrou-note/deactivate/5')}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-ban-circle"></span> @lang('admin.desactiver')</a>
                @endif</li>
        </ul>
    </div>
<div class="col-md-6">
    <h3 class="page-header  alert alert-warning">@lang('semestre') 2</h3>
    <ul>
        <li><strong class="col-md-6">CC</strong>
            <?php $finded=false; ?>
            @foreach($verrous as $v)
                @if($v->sequence==2)
                    <a href="{{url('/admin/verrou-note/activate/2')}}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok"></span> @lang('admin.activer')</a>
                    <?php $finded=true;break; ?>
                @endif
            @endforeach
            @if(!$finded)
                <a href="{{url('/admin/verrou-note/deactivate/2')}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-ban-circle"></span> @lang('admin.desactiver')</a>
            @endif</li>
        <li><strong class="col-md-6">@lang('admin.session_normale')</strong>
            <?php $finded=false; ?>
            @foreach($verrous as $v)
                @if($v->sequence==4)
                    <a href="{{url('/admin/verrou-note/activate/4')}}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok"></span> @lang('admin.activer')</a>
                    <?php $finded=true;break; ?>
                @endif
            @endforeach
            @if(!$finded)
                <a href="{{url('/admin/verrou-note/deactivate/4')}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-ban-circle"></span> @lang('admin.desactiver')</a>
            @endif</li>
        <li><strong class="col-md-6">@lang('admin.rattrapage')</strong>
            <?php $finded=false; ?>
            @foreach($verrous as $v)
                @if($v->sequence==6)
                    <a href="{{url('/admin/verrou-note/activate/6')}}" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-ok"></span> @lang('admin.activer')</a>
                    <?php $finded=true;break; ?>
                @endif
            @endforeach
            @if(!$finded)
                <a href="{{url('/admin/verrou-note/deactivate/6')}}" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-ban-circle"></span> @lang('admin.desactiver')</a>
            @endif</li>
    </ul>
</div>


@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection