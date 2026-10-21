@extends('skeleton')
@section('sub-content')
    @include('perso.functions')

    <style>
        .result-table thead, .result-table thead th {
            height: auto;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>

    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>

    <link rel="stylesheet" href="{{URL::asset('css/releves.css')}}">

    <?php $ne=0 ?>
    @foreach($PV as $releve)
        <?php
        $sCoef=0;
        $TMP=0;
        $TMV=0;
        $subSnote=0;
        $subScoef=0;
        ?>
        <div class="releve">
            @include('perso.header2')

            <div class="content">

                <div class="titre">
                    <p style="font-size: 18px;"><strong>RELEVE DE NOTES ANNUEL</strong></p>
                    <strong>
                        <p class="big-traduction"><strong>ANNUAL TRANSCRIPT</strong></p>
                    </strong>
                </div>

                <div class="nom-matricule-container">
                    <div class="nom">
                        <p><strong>Nom et Prénoms : </strong>{{ $releve->eleve->nom.' '.$releve->eleve->prenom }}</p>
                    </div>
                    <div class="matricule">
                        <p><strong>Matricule : </strong>{{ $releve->eleve->matricule }}</p>
                    </div>
                </div>

                <div class="date-lieu-container">
                    <div class="date">
                        <p><strong>Né(e) le: </strong> {{ (new DateTime($releve->eleve->date_naiss))->format('d/m/Y') }}</p>
                    </div>
                    <div class="lieu">
                        <p><strong>A :</strong> {{ $releve->eleve->lieu_naiss }}</p>
                    </div>
                </div>

                <div class="cycle-filiere">
                    <div class="cycle">
                        <p><strong>Cycle : </strong> {{ $cycle->nom }}</p>
                    </div>
                    <div class="filiere">
                        <p><strong>Filière : </strong> {{ $niveau->nom }}</p>
                    </div>
                </div>

                <div class="specialite-annee">
                    <div class="specialite">
                        <p><strong>Spécialité : </strong> {{ $classe->nom }} <strong>- Niveau : </strong> {{ $nniveau->nom }}</p>
                    </div>
                    <div class="annee_a">
                        <p><strong>Année académique : </strong> {{ session('year') }}</p>
                    </div>
                </div>

                <table class="table table-striped table-condensed table-inverse" style="margin-top: auto; margin-bottom: 1px;">
                    <thead>
                    <th>Codes</th>
                    <th><strong>Intitulé de l'UE/</strong> <span class="big-traduction">Course title</span></th>
                    <th class="note-column"><strong>Semestre <span class="big-traduction">Semester</span></strong></th>
                    <th class="note-column"><strong>Crédits /4</strong></th>
                    <th class="note-column"><strong>Note /20</strong></th>
                    <th class="note-column"><strong>Qualité /4</strong></th>
                    <th class="note-column"><strong>Notation</strong> <span class="big-traduction">Grade</span></th>
                    <th class="note-column"><strong>Observations</strong></th>
                    </thead>
                    <tbody>

                    @foreach(['0'=>'Fondamentales', '1'=>'Transversales', '2'=>'Professionnelles'] as $type => $titre)
                        <tr>
                            <td colspan="8">
                                <strong style="font-size: 12px;">
                                    Unités d'enseignement {{ $titre }}
                                    <span class="big-traduction">{{ $titre=='Fondamentales'?'Common courses':($titre=='Transversales'?'Common courses':'Professional courses') }}</span>
                                </strong>
                            </td>
                        </tr>

                        @foreach($releve->matieres as $matiere)
                            @if($matiere->type == $type)
                                <?php
                                $subSnote += $matiere->note * $matiere->credit;
                                $subScoef += $matiere->credit;
                                $sCoef += $matiere->credit;
                                $TMP++;
                                if($matiere->note>=50) $TMV++;
                                ?>
                                <tr>
                                    <td>{{ $matiere->id_code }}</td>
                                    <td>{{ $matiere->altName }}</td>
                                    <td style="text-align: center">{{ getSemestre($matiere->semestre) }}</td>
                                    <td style="text-align: center">{{ $matiere->credit }}</td>
                                    <td style="text-align: center">{{ round($matiere->note/5,2) }}</td>
                                    <td style="text-align: center">{{ getGrade($matiere->note)[1] }}</td>
                                    <td style="text-align: center">{{ getGrade($matiere->note)[0] }}</td>
                                    <td style="text-align: center">{{ getMention($matiere->note,100) }}</td>
                                </tr>
                            @endif
                        @endforeach

                        <tr>
                            <td colspan="2"></td>
                            <td style="text-align: center;"><strong>Moy/Av</strong></td>
                            <td style="text-align: center;">{{ $subScoef }}</td>
                            <td style="text-align: center;"><strong>{{ round(($subSnote/$subScoef)/5,2) }}</strong></td>
                            <td style="text-align: center">{{ getGrade($subSnote/$subScoef)[1] }}</td>
                            <td style="text-align: center;"><strong>{{ getGrade($subSnote/$subScoef)[0] }}</strong></td>
                            <td style="text-align: center">{{ getMention($subSnote/$subScoef,100) }}</td>
                        </tr>
                        <?php $subSnote=0; $subScoef=0; ?>
                    @endforeach

                    </tbody>
                </table>

                <!-- Résumé annuel -->
                <table class="table result-table table-striped table-condensed table-inverse" style="font-size: 11px;margin-top: auto;">
                    <thead>
                    <th colspan="2">Travail annuel</th>
                    <th>T.M.P</th>
                    <th>T.M.V</th>
                    <th>Moyenne</th>
                    <th>MGP</th>
                    <th>Cote</th>
                    <th colspan="2">Mention</th>
                    </thead>
                    <tbody style="text-align: center">
                    <tr>
                        <?php $moyenne_annuelle = $releve->moyenne; ?>
                        <td rowspan="2">Moy.Annuelle/<span class="big-traduction">Annual Av.</span></td>
                        <td>/ 100</td>
                        <td rowspan="2">{{ $TMP }}</td>
                        <td rowspan="2">{{ $TMV }}</td>
                        <td>{{ round($moyenne_annuelle,2) }}</td>
                        <td rowspan="2">{{ round($moyenne_annuelle/25,2) }}</td>
                        <td rowspan="2">{{ getGrade($moyenne_annuelle)[0] }}</td>
                        <td colspan="2">{{ getMention($moyenne_annuelle,100) }}</td>
                    </tr>
                    <tr>
                        <td>/ 20</td>
                        <td>{{ round($moyenne_annuelle/5,2) }}</td>
                        <td>Decision :</td>
                        <td><strong style="font-size: 13px">{{ getDecision(round($moyenne_annuelle/25,2)) }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="9">
                            <strong class="big-traduction">T.M.P: </strong> <em class="big-traduction">Total des modules prévus</em>
                            &emsp;
                            <strong class="big-traduction">T.M.V: </strong> <em class="big-traduction">Total des modules Validés</em>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="9" style="text-align: center;">
                            (Note: A+(90-100)=Excellent; A(80-89)=Très Bien; A-(75-79)=Bien; B(65-69)=Assez Bien; B-(60-64)=Passable; C+(55-59)=Passable; C(50-54)=Passable; C-(45-49)=Médiocre; D+(40-44)=Faible; D(35-39);E(30-34)=Echec; F(0-29)=Echec)
                        </td>
                    </tr>
                    </tbody>
                </table>

            </div>

            <div class="sign-footer">
                <div class="jury">
                    <p></p>
                </div>
                <div class="dep">
                    <p></p>
                    <p><strong>YAOUNDE Le ..........................</strong></p>
                    <p><u><strong>Le Directeur de l'ISH</strong></u></p>
                </div>
            </div>

            <div class="releve-fait-le" style="font-size: 10px; display: none;">
                <p>Fait le {{ date('d-m-Y') }}</p>
            </div>

            @if($ne>0)
                <div class="copy col-md-12">
                    &copy;  2017 SchoolConcept | by: <a href="http://youth-concept.com" style="color:#fff;" target="_blank">youth-concept</a>
                </div>
            @endif

        </div>
        <?php $ne++; ?>
    @endforeach

@endsection
