@extends('skeleton')
@section('sub-content')
    <div class="col-md-7">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

<form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/eleves/discipline/justifions/') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <fieldset>
        @foreach($discipline as $d)
            <input class="form-control" id="nom" name="justify" type="hidden"  value="{{$d->id}}">

        <div class="form-group col-md-12">
                <label for="motif_justification">Motif :</label>
                <textarea id="motif_justification" name="motif_justification" class="form-control"></textarea>
            </div>

        <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">
@endforeach

    </fieldset>
</form>
    </div>
    @endsection


@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('eleves/discipline')}}">@lang('inscription.title_search')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection