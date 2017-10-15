@extends('skeleton')
@section('sub-content')
<h4>@lang('admin.depuis_dernier_payement')</h4>
    <div class="col-md-2 alert alert-info">
        <?php $nul=0; ?>
        {{$realise->nombre?$realise->nombre.' '.trans('admin.heure'):$nul.' '.trans('admin.heure')}}
    </div>
<div class="col-md-12"></div>
<div class="col-md-3">
    <form accept-charset="UTF-8" role="form" method="POST"  action="{{ url('/personnel/heures-effectuees/update/'.$personnel->id) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <fieldset>



            <div class="form-group">
                <label >{{  trans('admin.ajouter')  }} : </label>
                <div class="input-group">
                    <input class="form-control"  name="nombre" type="number"  value="{{ old('nombre') }}" required>
                    <span class="input-group-addon">{{ trans('admin.heure') }}</span>

                </div>

            </div>
            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


        </fieldset>
    </form>
</div>

@endsection