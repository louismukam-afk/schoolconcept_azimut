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
            <td><a href="#edit_payement" data-toggle="modal" onclick="editer_payement({{$p->id}},{{$p->montant}})"><strong><span class="glyphicon glyphicon-edit"></span> {{$p->montant}}</strong></a> </td>
            <td>{{$p->motif}}</td>
            <td>{{$p->date_payement}}</td>
            <td>
            <!-- TO CHANGE -->
                        <a  class="btn btn-warning"  href="@if($p->type == \gesecole\Vars::INSCRIPTION_TYPE){{URL::to('/inscriptions/print/'.$p->id)}} @else{{URL::to('/inscriptions/payement/print/'.$p->id)}}@endif" ><span class="glyphicon glyphicon-print"  ></span> {{  trans('inscription.print_receipe')  }}</a>

        </tr>
        @endforeach
        </tbody>
    </table>
    <a class="btn btn-success" href="{{URL::to('/inscriptions/payement/create/'.$id_eleve)}}"><span class="glyphicon glyphicon-plus"></span>{{  trans('inscription.add_payement')  }}</a>
</div>


  <div class="modal fade" id="edit_payement">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div
                                class="modal-body">



                            <form accept-charset="UTF-8" role="form" method="POST" id="edit_payement_form" action="{{ route('update_payement') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" id="edit_id" value=""/>
                                <fieldset>
                                    <div class="form-group">
                                        <label >{{  trans('inscription.montant')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_montant" name="montant" type="number"  value="{{ old('montant') }}" required autofocus>

                                    </div>

                                
                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                            </form>



                        </div>





                    </div>


                </div>
            </div>

             <script>
        function editer_payement(id,montant)
        {

            document.getElementById("edit_id").value = id;
            document.getElementById("edit_montant").value = montant;

        }
    </script>
    @endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li><strong><a href="{{url('inscriptions/payement/search')}}">@lang('inscription.title_search')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection




