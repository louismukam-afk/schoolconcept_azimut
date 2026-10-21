@extends('skeleton')

@section('sub-content')
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <strong>Historique et parcours de l'etudiant</strong>
                <button class="btn btn-xs btn-default pull-right" onclick="window.print()">
                    <span class="fa fa-print"></span> Imprimer
                </button>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <h3 style="margin-top:0;">{{ $eleve->nom }} {{ $eleve->prenom }}</h3>
                        <table class="table table-bordered table-condensed">
                            <tr><th>Matricule</th><td>{{ $eleve->matricule }}</td></tr>
                            <tr><th>Date de naissance</th><td>{{ $eleve->date_naiss }}</td></tr>
                            <tr><th>Lieu de naissance</th><td>{{ $eleve->lieu_naiss }}</td></tr>
                            <tr><th>Sexe</th><td>{{ $eleve->sexe }}</td></tr>
                            <tr><th>Nationalite</th><td>{{ $eleve->nationalite }}</td></tr>
                            <tr><th>Telephone</th><td>{{ $eleve->tel }}</td></tr>
                            <tr><th>Email</th><td>{{ $eleve->email }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h4>Parents et tuteur</h4>
                        <table class="table table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th>Type</th>
                                <th>Nom</th>
                                <th>Profession</th>
                                <th>Telephone</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach(array('pere' => 'Pere', 'mere' => 'Mere', 'tuteur' => 'Tuteur') as $key => $label)
                                <?php $parent = $parents[$key]; ?>
                                <tr>
                                    <td>{{ $label }}</td>
                                    <td>{{ $parent ? $parent->nom : '' }}</td>
                                    <td>{{ $parent ? $parent->profession : '' }}</td>
                                    <td>{{ $parent ? trim($parent->tel1.' '.$parent->tel2) : '' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <p><strong>Lien avec le tuteur :</strong> {{ $eleve->lien_tuteur }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">
                <strong>Parcours academique dans l'etablissement</strong>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Annee academique</th>
                            <th>Campus</th>
                            <th>Cycle</th>
                            <th>Filiere</th>
                            <th>Specialite</th>
                            <th>Niveau</th>
                            <th>Moyenne generale</th>
                            <th>Decision</th>
                            <th>Rang</th>
                            <th>Discipline</th>
                            <th>Paiements</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1; ?>
                        @forelse($parcours as $ligne)
                            <?php
                                $inscription = $ligne['inscription'];
                                $resultat = $ligne['resultat'];
                                $discipline = $ligne['discipline'];
                            ?>
                            <tr>
                                <td>{{ $i++ }}</td>
                                <td>
                                    <strong>{{ $inscription->annee_scolaire }}</strong><br>
                                    <small>{{ $inscription->date_inscription }}</small>
                                </td>
                                <td>{{ $inscription->campus ? $inscription->campus->nom : '' }}</td>
                                <td>{{ $inscription->cycle ? $inscription->cycle->nom : '' }}</td>
                                <td>{{ ($inscription->classe && $inscription->classe->niveau) ? $inscription->classe->niveau->getNom() : '' }}</td>
                                <td>{{ $inscription->classe ? $inscription->classe->getNom() : '' }}</td>
                                <td>{{ $inscription->nniveau ? $inscription->nniveau->nom : '' }}</td>
                                <td>
                                    @if($resultat['moyenne'] !== null)
                                        <strong>{{ $resultat['moyenne'] }}/20</strong>
                                        @if($resultat['sequence'] !== null)
                                            <br><small>Sequence {{ $resultat['sequence'] }}</small>
                                        @endif
                                        @if(isset($resultat['source']))
                                            <br><small>{{ $resultat['source'] }}</small>
                                        @endif
                                    @else
                                        Non renseignee
                                        @if(isset($resultat['source']))
                                            <br><small>{{ $resultat['source'] }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($resultat['decision'] == 'Admis')
                                        <span class="label label-success">Admis</span>
                                    @elseif($resultat['decision'] == 'Ajourne')
                                        <span class="label label-warning">Ajourne</span>
                                    @else
                                        <span class="label label-default">Non renseignee</span>
                                    @endif
                                </td>
                                <td>
                                    @if($resultat['rang'])
                                        {{ $resultat['rang'] }} / {{ $resultat['effectif'] }}
                                    @else
                                        Non renseigne
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $discipline['absences'] }}</strong> absence(s),
                                    <strong>{{ $discipline['heures'] }}</strong> heure(s)<br>
                                    <small>
                                        Retards: {{ $discipline['retards'] }},
                                        justifiees: {{ $discipline['justifiees'] }},
                                        non justifiees: {{ $discipline['non_justifiees'] }},
                                        exclusions: {{ $discipline['exclusions'] }}
                                    </small>
                                </td>
                                <td>{{ number_format($ligne['paiements'], 0, ',', ' ') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center">Aucune inscription trouvee pour cet etudiant.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-warning">
            <div class="panel-heading">
                <strong>Details disciplinaires par annee academique</strong>
            </div>
            <div class="panel-body">
                @forelse($parcours as $ligne)
                    <?php
                        $inscription = $ligne['inscription'];
                        $details = $ligne['discipline']['details'];
                    ?>
                    <h4>{{ $inscription->annee_scolaire }} - {{ $inscription->classe ? $inscription->classe->getNom() : '' }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed">
                            <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Heures</th>
                                <th>Statut</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($details as $detail)
                                <tr>
                                    <td>{{ $detail['date'] }}</td>
                                    <td>{{ $detail['type'] }}</td>
                                    <td>{{ $detail['heures'] }}</td>
                                    <td>{{ $detail['statut'] }}</td>
                                    <td>{{ $detail['description'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Aucun incident disciplinaire enregistre pour cette annee.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                @empty
                    <p>Aucune inscription trouvee pour cet etudiant.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li><strong><a href="{{url('/inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li><strong><a href="{{url('/inscriptions/historique-etudiant')}}">Historique etudiant</a></strong></li>
    <li class="active"><strong>{{ $eleve->nom }} {{ $eleve->prenom }}</strong></li>
@endsection
