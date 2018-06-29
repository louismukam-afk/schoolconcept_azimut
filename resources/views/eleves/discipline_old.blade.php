@extends('skeleton')
@section('sub-content')
    <div class="col-md-12">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

        <div class="etat-disc">
            <h4>{{trans('eleve.etat_discipline').' '.$eleve->nom.' '.$eleve->prenom}}</h4>
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>Informations</h4>

                    </div>


                </div>
                <div class="panel-body">
                    <div class="col-md-4">
                        <strong style="font-size: 16px">{{trans('eleve.retard')}}</strong> : {{ $discipline->retard}}
                    </div>
                    <div class="col-md-4">
                        <strong style="font-size: 16px">{{trans('eleve.abscence_i')}}</strong>( {{trans('eleve.heure')}}) : {{$discipline->abscence_inj}}
                    </div>
                    <div class="col-md-4">
                        <strong style="font-size: 16px">{{trans('eleve.abscence_j')}}</strong>( {{trans('eleve.heure')}}) : {{$discipline->abscence_just}}
                    </div>
                    <div class="col-md-4">
                        <strong style="font-size: 16px">{{trans('eleve.avertissement')}}</strong> :{{$discipline->avertissement}}
                    </div>
                    <div class="col-md-4">
                        <strong style="font-size: 16px">{{trans('eleve.blame')}}</strong> : {{$discipline->blame}}
                    </div>
                    <div class="col-md-4">
                        <strong style="font-size: 16px">{{trans('eleve.exclusion')}}</strong>( {{trans('eleve.jour')}}) : {{$discipline->exclusion}}
                    </div>
                </div>
                <div class="panel-footer">
                    <a class="btn btn-primary"  data-toggle="modal" data-backdrop="false" href="#edit-discipline"><span class="glyphicon glyphicon-edit"></span> {{trans('admin.edit')}}</a>
                </div>
            </div>

        </div>

            <div class="modal fade" id="edit-discipline">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div class="modal-body">
                            <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/eleves/discipline/update/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" value="{{$eleve->id}}">

                                <fieldset>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-5">
                                            <label for="retard">{{  trans('eleve.retard')  }} : <span class="required">*</span></label>
                                            <input class="form-control" id="retard" name="retard" type="number"  value="{{ $discipline->retard }}" required autofocus>

                                        </div>

                                        <div class="form-group col-md-5">
                                            <label for="abscence_inj">{{  trans('eleve.abscence_i')  }} : <span class="required">*</span></label>
                                            <input class="form-control" id="abscence_inj" name="abscence_inj" type="number"  value="{{ $discipline->abscence_inj }}" required>

                                        </div>
                                    </div>
                                    <div class="row col-md-12">
                                        <div class="form-group col-md-5">
                                            <label for="abscence_just">{{  trans('eleve.abscence_j')  }} : <span class="required">*</span></label>
                                            <input class="form-control" id="abscence_just" name="abscence_just" type="number"  value="{{ $discipline->abscence_just }}" required >

                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="avertissement">{{  trans('eleve.avertissement')  }} : <span class="required">*</span></label>
                                            <input class="form-control" id="avertissement" name="avertissement" type="number"  value="{{ $discipline->avertissement }}" required >

                                        </div>
                                    </div>
                                  <div class="row col-md-12">
                                      <div class="form-group col-md-5">
                                          <label for="blame">{{  trans('eleve.blame')  }} : <span class="required">*</span></label>
                                          <input class="form-control" id="blame" name="blame" type="number"  value="{{ $discipline->blame }}" required >

                                      </div>
                                      <div class="form-group col-md-5">
                                          <label for="exclusion">{{  trans('eleve.exclusion')  }} : <span class="required">*</span></label>
                                          <input class="form-control" id="exclusion" name="exclusion" type="number"  value="{{ $discipline->exclusion }}" required >

                                      </div>
                                  </div>




                                </fieldset>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                            </form>

                        </div>

                    </div>
                </div>
            </div>
    </div>

@endsection