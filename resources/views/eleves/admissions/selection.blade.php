@extends('skeleton')
@section('sub-content')
<a href="{{ url('/eleves') }}" class="btn btn-default">Accueil élèves</a>
<h3>Listes des étudiants admis par cycle</h3>
<p>Un PDF global contient les listes par filière, spécialité et niveau du campus sélectionné. Seuls les étudiants ayant validé toutes les matières sont retenus.</p>
@if(count($errors))<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="get" action="{{ url('/eleves/admissions/rapport') }}">
    <div class="row">
        <div class="col-sm-4 form-group"><label for="admission-year">Année académique</label><input id="admission-year" class="form-control" name="annee" required maxlength="50" value="{{ old('annee', $annee) }}"></div>
        <div class="col-sm-5 form-group"><label for="admission-session">Période</label><select id="admission-session" name="session" class="form-control">@foreach($sessions as $key => $label)<option value="{{ $key }}">{{ $label }}</option>@endforeach</select></div>
    </div>
    @foreach($cycles as $cycle)
    <button class="btn btn-primary" style="margin:6px 6px 6px 0" type="submit" name="cycle" value="{{ $cycle->id }}"><i class="fa fa-file-pdf-o"></i> Liste des admis — {{ $cycle->nom }}</button>
    @endforeach
</form>
@endsection
