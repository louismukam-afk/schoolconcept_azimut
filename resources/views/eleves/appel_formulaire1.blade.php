<div class="col-md-3">
    <label>Jour</label>
    <select name="id_jour" class="form-control" required>
        <option value="">-- Sélectionner un jour --</option>
        @foreach($jours as $j)
            <option value="{{ $j->id }}">
                {{ $j->nom }}
            </option>
        @endforeach
    </select>
</div>
