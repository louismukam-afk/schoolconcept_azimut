@extends('skeleton')
@section('sub-content')

    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>

    @endif

    <div class="col-md-6 ">
        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <th>#</th>
            <th>Date</th>
            <th>{{trans('admin.quantite')}}</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($realise as $h)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$h->date_heure_realisee}}</td>
                    <td><strong >{{$h->nombre}}</strong></td>
                    <td><a href="{{ URL::to('/personnel/heures-effectuees/destroy/').'/'.$h->id.'?personnel='.$h->id_personnel}}"  class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $realise->render()) ); ?></div>


    </div>
<div class="col-md-12"></div>
<div class="col-md-3">
    <form accept-charset="UTF-8" role="form" method="POST"  action="{{ url('/personnel/heures-effectuees/update/'.$personnel->id) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <fieldset>



            <div class="form-group">
                <label >{{  trans('admin.ajouter')  }} : </label>
                <div class="input-group">
                    <input class="form-control"  name="nombre" type="number"  value="{{ old('nombre') }}" autofocus required>
                    <span class="input-group-addon">{{ trans('admin.heure') }}</span>

                </div>

            </div>
            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


        </fieldset>
    </form>
</div>

@endsection