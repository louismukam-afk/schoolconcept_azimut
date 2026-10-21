<h3>
    Etat Discipline — {{ $classe->nom }}
</h3>

<strong>Cycle :</strong> {{ $cycle->nom }} |
<strong>Filière :</strong> {{ $niveau->nom }} |
<strong>Niveau :</strong> {{ $n_niveau->nom }} |
<strong>Période :</strong> {{ $date_debut }} au {{ $date_fin }} |
<strong>Année :</strong> {{ $annee }}

<br><br>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Matricule</th>
        <th>Nom étudiant</th>
        <th>Total heures absence</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $index => $row)
    <tr>
        <td>{{ $index+1 }}</td>
        <td>{{ $row->matricule }}</td>
        <td>{{ $row->nom }} {{ $row->prenom }}</td>
        <td>{{ $row->total_absence }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
