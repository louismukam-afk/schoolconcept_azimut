@extends('skeleton')
@section('sub-content')
<div class="col-md-12">
    <p><a class="btn btn-default" href="{{ url('/admin/campus') }}"><span class="fa fa-arrow-left"></span> Retour aux campus</a></p>
    <h3>{{ $titre }} : {{ $campus->nom }}</h3>
    @if($errors->any())
        <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="post" action="{{ url('/admin/campus/update') }}" class="panel panel-default" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id" value="{{ $campus->id }}">
        <div class="panel-body">
            <div class="row">
                @foreach(array('nom'=>'Nom', 'sigle'=>'Sigle', 'ville'=>'Ville', 'localisation'=>'Localisation',
                    'numero_agrement'=>'Numéro d’agrément', 'reference'=>'Référence', 'telephone'=>'Téléphone',
                    'email'=>'Email', 'site_web'=>'Site web', 'boite_postale'=>'Boîte postale') as $field=>$label)
                <div class="col-md-4 form-group">
                    <label for="campus_{{ $field }}">{{ $label }}</label>
                    <input id="campus_{{ $field }}" name="{{ $field }}" type="{{ $field === 'email' ? 'email' : 'text' }}"
                        class="form-control" value="{{ old($field, $campus->$field) }}" @if($field === 'nom') required @endif>
                </div>
                @endforeach
                <div class="col-md-4 form-group">
                    <label for="campus_date_creation">Date de création</label>
                    <input type="date" id="campus_date_creation" name="date_creation" class="form-control"
                        value="{{ old('date_creation', $campus->date_creation ? substr($campus->date_creation, 0, 10) : '') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="campus_logo_file">Remplacer le logo</label>
                    <input type="file" id="campus_logo_file" name="logo_file" class="form-control" accept="image/*">
                    @if($campus->logo)<img src="{{ asset($campus->logo) }}" alt="Logo du campus" style="max-height:70px;max-width:180px;margin-top:10px">@endif
                </div>
                <div class="col-md-6 form-group">
                    <label for="campus_logo">Chemin du logo</label>
                    <input id="campus_logo" name="logo" class="form-control" value="{{ old('logo', $campus->logo) }}" placeholder="img/logo.png">
                </div>
                @foreach(array('autorisation_ouverture'=>'Autorisation d’ouverture', 'arrete_creation'=>'Arrêté de création') as $field=>$label)
                <div class="col-md-6 form-group">
                    <label for="campus_{{ $field }}">{{ $label }}</label>
                    <textarea id="campus_{{ $field }}" name="{{ $field }}" class="form-control" rows="3">{{ old($field, $campus->$field) }}</textarea>
                </div>
                @endforeach
            </div>
            <button type="submit" class="btn btn-success"><span class="fa fa-save"></span> Enregistrer les modifications</button>
            <a class="btn btn-default" href="{{ url('/admin/campus') }}">Annuler</a>
        </div>
    </form>
</div>
@endsection
@section('breadcrumb')
<li><a href="{{ url('/admin') }}">Administration</a></li>
<li><a href="{{ url('/admin/campus') }}">Campus</a></li>
<li class="active">Modifier</li>
@endsection
