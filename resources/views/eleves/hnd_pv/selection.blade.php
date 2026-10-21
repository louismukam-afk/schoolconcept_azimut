@extends('skeleton')
@section('sub-content')
    <div class="btn-toolbar" style="margin-bottom:15px;"><a href="{{ url('/eleves') }}" class="btn btn-default">Accueil élèves</a></div>
    <p>Sélectionnez le cycle, le niveau et la filière, puis choisissez la spécialité pour générer les procès-verbaux anglophones.</p>
    @include('eleves.partials.cycle_niveau_filiere_filter', array('selectionBaseUrl' => url('/eleves/pv_hnd/sessions')))
    <div class="liste-classe"><ul id="specialite_list" class="list-unstyled">
        @foreach($niveaux as $n)
            <li class="niveau-block" data-filiere="{{ $n->id }}" style="display:none;">
                <h4>{{ $n->getNom() }}</h4>
                @foreach($classes as $c)
                    @if($c->id_niveau == $n->id)
                        <p><a href="#" class="btn btn-default cycle-niveau-specialite-link" data-classe="{{ $c->id }}">{{ $c->getNom() }} <span class="glyphicon glyphicon-chevron-right"></span></a></p>
                    @endif
                @endforeach
            </li>
        @endforeach
    </ul></div>
@endsection
@section('breadcrumb')
    <li><a href="{{ url('/eleves') }}">Élèves</a></li><li class="active">Procès-verbaux HND — sélection</li>
@endsection
