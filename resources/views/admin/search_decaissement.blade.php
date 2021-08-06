@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>


    @endif

    @if(isset($custom_error))

        <div class="alert alert-danger">
            {{  $custom_error  }}
        </div>

    @endif


    <div class="col-md-8">
        <form class="form-horizontal" id="search_form" method="get" action="{{URL::to('/admin/retrait/')}}">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="search">{{  trans('inscription.recherche')  }} : </label>
                    <div class="input-group">
                        <input type="search" name="search" placeholder="{{trans('inscription.nom')}}" class="form-control" autofocus autocomplete="true">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary" ><span class="fa fa-search"> </span>{{trans('inscription.search')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="col-md-8">
        <a data-toggle="modal" data-backdrop="false" href="#add_retrait" class="btn btn-warning"><span class="fa fa-money"></span> {{trans('admin.nouveau_retrait')}}</a>
    </div>

    <div class="col-md-8">
            <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
                <thead>
                <th>#</th>
                <th>{{  trans('admin.nom')  }}</th>
                <th>{{  trans('inscription.motif')  }}</th>
                <th>{{  trans('inscription.montant')  }}</th>
                <th>Date</th>

                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($decaissement as $d)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>
                            {{$d->nom_personnel}}
                        <td>{{$d->motif}}</td>
                        <td><a href="#edit_payement" data-toggle="modal" onclick="editer_payement({{$d->id}},{{$d->montant}})"><strong><span class="glyphicon glyphicon-edit"></span> {{$d->montant}}</strong></a> </td>
                        <td>{{$d->date}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $decaissement->render()) ); ?></div>

    </div>

    <div class="modal fade" id="add_retrait">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="POST" id="add_retrait_form" action="{{ url('/admin/retrait/store/') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <fieldset>
                            <div class="form-group">
                                <label >{{  trans('admin.choix_personnel')  }} : </label>
                                <select name="personnel" form="add_retrait_form" class="form-control" autocomplete="true">
                                    @foreach($personnel as $p)
                                        <option value="{{  $p->id  }}" @if($p->id == old('personnel')) selected @endif>{{  $p->nom  }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" >
                                <label >{{  trans('inscription.montant')  }} : <span class="required">*</span></label>
                                <input class="form-control" name="montant" type="number"  value="{{ old('montant') }}" min="0" required>

                            </div>
                            <div class="form-group">
                                <label >{{  trans('inscription.motif')  }} : <span class="required">*</span></label>
                                <textarea name="motif" form="add_retrait_form" class="form-control" required>{{old('motif')}}</textarea>
                            </div>

                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>

    <div class="modal fade" id="edit_payement">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div class="modal-body">
                    <form accept-charset="UTF-8" role="form" method="POST" id="edit_payement_form" action="{{ route('update_retrait_amount') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="edit_id" value=""/>
                        <fieldset>
                            <div class="form-group">
                                <label >{{  trans('inscription.montant')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="edit_montant" name="montant" type="number"  value="{{ old('montant') }}" required min="0">
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
@endsection
@section('breadcrumb')
    <li><strong><a href="{{url('comptabilite')}}">@lang('admin.comptabilite')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection