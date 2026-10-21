@extends('skeleton')
@section('sub-content')
    @if(!Request::is('eleves/notes/show_esstic'))
        <div class="btn-toolbar" style="margin-bottom:15px;">
            <a href="{{ url('/eleves/notes/deliberations') }}" class="btn btn-success">
                <span class="glyphicon glyphicon-check"></span> D&eacute;lib&eacute;rations par crit&egrave;res
            </a>
            <a href="{{ url('/eleves/notes/deliberations/rapport') }}" class="btn btn-info">Rapport des d&eacute;lib&eacute;rations</a>
        </div>
    @endif
    <h4>{{trans('eleve.choix_classe')}}</h4>
    <hr>
    @include('eleves.partials.cycle_niveau_filiere_filter', array('selectionBaseUrl' => Request::is('eleves/notes/show_esstic') ? URL::to('/eleves/notes/matieres/show_esstic/') : URL::to('/eleves/notes/matieres/show/')))
    <div class="liste-classe col-md-12">
        <ul>
           



                    @foreach($niveaux as $n)
                        <div class="col-md-6 niveau-block" data-filiere="{{$n->id}}" style="display:none;">
                            <h4 class="page-header"> {{$n->getNom()}}</h4>

                            @foreach($classes as $c)
                                <div class="col-md-12">
                                    @if($c->id_niveau==$n->id)
                                        <li class="col-md-12">
                                            <a class="special cycle-niveau-specialite-link" href="#" data-classe="{{$c->id}}">{{$c->getNom()}}</a>
                                            @if(!Request::is('eleves/notes/show_esstic'))
                                                <a class="btn btn-success btn-xs cycle-niveau-specialite-link" href="#" data-classe="{{$c->id}}" data-selection-url="{{ url('/eleves/notes/deliberations') }}" style="margin-left:10px;">D&eacute;lib&eacute;rer</a>
                                            @endif
                                        </li>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach



        </ul>
    </div>






@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('eleves/notes')}}">Notes</a></strong></li>

    <li class="active"><strong>@lang('admin.choix_classe')</strong></li>
@endsection
