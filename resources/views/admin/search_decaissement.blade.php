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
        <form class="form-horizontal" id="add_inscription_form" method="get" action="{{URL::to('/search/retrait/')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="view" value="admin\search_decaissement">
            <input type="hidden" name="titre" value="{{trans('inscription.recherche')}}">
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
        <a data-toggle="modal" data-backdrop="false" href="#add_retrait" class="btn btn-warning btn-lg"><span class="fa fa-money"></span> {{trans('admin.nouveau_retrait')}}</a>
    </div>

    <div class="col-md-8">
        @if(session('search_result'))
            <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
                <thead>
                <caption style="font-size: 22px">{{  trans('inscription.search_result')  }}</caption>
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
                                @foreach($personnel as $p)
                                    @if($p->id==$d->id_personnel)
                                        <strong>{{$p->nom}}</strong>
                                        <?php break; ?>
                                    @endif
                                @endforeach
                        <td>{{$d->motif}}</td>
                        <td>{{$d->montant}}</td>
                        <td>{{$d->date}}</td>

                    </tr>
                @endforeach
                </tbody>
            </table>
            {{Session::forget('search_result')}}

            <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $decaissement->render()) ); ?></div>

        @endif
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
                                <input class="form-control" name="montant" type="text"  value="{{ old('montant') }}"  required>

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
@endsection