@extends('skeleton')
@section('sub-content')
<div class="col-md-8">
    @if(isset($success))

    <div class="alert alert-success">
        {{  trans('admin.succes')  }}
    </div>

    @endif

    <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
        <thead>
        <caption style="font-size: 22px">{{  trans('inscription.liste_paiement').' : '.$name  }} </caption>
        <th>#</th>
        <th>{{  trans('inscription.numero')  }}</th>
        <th>{{  trans('inscription.montant')  }}</th>
        <th>{{  trans('inscription.motif')  }}</th>
        <th>Date</th>
        </thead>
        <tbody>
        <?php $i=1; ?>
        @foreach($payements as $p)
        <tr>
            <td>{{$i++}}</td>
            <td><strong >{{$p->numero}}</strong></td>
            <td> {{$p->montant}}</td>
            <td>{{$p->motif}}</td>
            <td>{{$p->date_payement}}</td>
            <td>

                        <a  class="btn btn-warning"  href="@if($p->motif=='Inscription'){{URL::to('/inscriptions/print/'.$p->id)}} @else{{URL::to('/inscriptions/payement/print/'.$p->id)}}@endif" ><span class="glyphicon glyphicon-print"  ></span> {{  trans('inscription.print_receipe')  }}</a>

        </tr>
        @endforeach
        </tbody>
    </table>
    <a class="btn btn-success" href="{{URL::to('/inscriptions/payement/create/'.$id_eleve)}}"><span class="glyphicon glyphicon-plus"></span>{{  trans('inscription.add_payement')  }}</a>
</div>
    @endsection