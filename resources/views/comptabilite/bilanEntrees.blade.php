@extends('skeleton')
@section('sub-content')
<div class="col-md-12">
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>

    @endif


        <div class="col-md-8" >
            <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
                <caption><h4>
                        @if(isset($date_debut))
                            {{$date_debut.' - '.$date_fin}}
                        @else
                            @lang('admin.toute_lannee')
                        @endif

                    </h4></caption>
                <thead>
                <th>#</th>
                <th>{{trans('inscription.motif')}}</th>
                <th>Date</th>
                <th>@lang('inscription.montant')</th>
                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($payements as $p)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><strong >{{$p->motif}}</strong></td>
                        <td>{{(new DateTime($p->date_payement))->format('d-m-Y')}}</td>
                        <td>{{$p->montant}}</td>
                        <td>                        <a  class="btn btn-warning"  href="@if($p->motif=='Inscription'){{URL::to('/inscriptions/print/'.$p->id)}} @else{{URL::to('/inscriptions/payement/print/'.$p->id)}}@endif" ><span class="glyphicon glyphicon-eye-open"  ></span> {{  trans('admin.verifier')  }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $payements->render()) ); ?></div>

            <h4 class="alert alert-warning">
                Total : <strong>{{$total.' '.trans('inscription.devise')}} </strong>
            </h4>
        </div>
    <div class="col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="panel-title">@lang('admin.intervalle')</div>

            </div>
            <div class="panel-body">
                <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/comptabilite/bilan/entrees') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <fieldset>

                        <div class="form-group">
                            <label for="date_debut">{{  trans('admin.date_debut')  }} : </label>
                            <input placeholder="jj-mm-aaaa" class="form-control" id="date_debut" name="date_debut" type="text"  value="{{ old('date_debut') }}" required>

                        </div>
                        <div class="form-group ">
                            <label for="date_fin">{{  trans('admin.date_fin')  }} : </label>
                            <input placeholder="jj-mm-aaaa" class="form-control" id="date_fin" name="date_fin" type="text"  value="{{ old('date_fin') }}" required >

                        </div>
                        <input placeholder="jj-mm-aaaa" class="btn  btn-primary pull-right " type="submit" value="{{  trans('admin.confirmer')  }}">


                    </fieldset>
                </form>
            </div>
        </div>
    </div>




</div>
    <script>
        $(function () {
            $('#date_debut').datepicker({ dateFormat: 'dd-mm-yy' });
            $('#date_fin').datepicker({ dateFormat: 'dd-mm-yy' });
        })
    </script>
@endsection