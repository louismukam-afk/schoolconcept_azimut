@extends('skeleton')
@section('sub-content')

    <h3>Sélection du Cycle et Niveau</h3>

    <form method="POST" action="{{ route('appel.classes') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="row">

            <div class="col-md-4">
                <label>Cycle</label>
                <select name="id_cycle" class="form-control" required>
                    @foreach($cycles as $c)
                        <option value="{{ $c->id }}">{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Niveau</label>
                <select name="id_niveau" class="form-control" required>
                    @foreach($niveaux as $n)
                        <option value="{{ $n->id }}">{{ $n->nom }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <br>

        <button type="submit" class="btn btn-success">
            Afficher les classes
        </button>

    </form>

@endsection
