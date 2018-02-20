@extends('skeleton')
@section('sub-content')
    @include('perso.functions')

    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')</a>

    <link rel="stylesheet"  href="{{URL::asset('css/releves.css')}}">
    <?php $ne=0 ?>
    @foreach($PV as $releve)
        <?php
        $sCoef=0;

        ?>
        <div class="releve">
            @include('perso.header')
            <div class="content">
                <div class="titre">
                    <p style="font-size: 22px;"><strong>RELEVE DE NOTES ANNUEL</strong></p>
                    <strong>
                        <p class="big-traduction">ANNUAL TRANSCRIPT </p>
                    </strong>
                </div>
                <div class="nom-matricule-container">
                    <div class="nom">
                        <p><strong>Nom et Prénoms : </strong>{{$releve->eleve->nom.' '.$releve->eleve->prenom}}</p>
                        <p><strong class="traduction">
                                Surname and name
                            </strong></p>
                    </div>
                    <div class="matricule">
                        <p><strong>Matricule : </strong>{{$releve->eleve->matricule}}</p>
                        <p><strong class="traduction">
                                Registration N°
                            </strong></p>
                    </div>
                </div>
                <div class="date-lieu-container">
                    <div class="date">
                        <p><strong>Née le: </strong> {{(new DateTime($releve->eleve->date_naiss))->format('d/m/Y')}}</p>
                        <p><strong class="traduction">Born on</strong></p>
                    </div>
                    <div class="lieu">
                        <p><strong>A :</strong> {{$releve->eleve->lieu_naiss}}</p>
                        <p><strong class="traduction">At </strong></p>
                    </div>
                </div>
                <div class="cycle-filiere">
                    <div class="cycle">
                        <p><strong>Cycle : </strong> {{$cycle->nom}}</p>
                        <p><strong class="traduction">Cycle : </strong></p>
                    </div>
                    <div class="filiere">
                        <p><strong>Filière : </strong> {{$niveau->nom}}</p>
                        <p class="traduction"><strong class="traduction">Discpline : </strong>{{$niveau->nom_anglais}}</p>
                    </div>

                </div>

                <div class="specialite-annee">
                    <div class="specialite">
                        <p><strong>Spécialité : </strong> {{$classe->nom}} <strong>- Niveau : </strong>{{$nniveau->nom}}</p>
                        <p class="traduction"><strong class="traduction">Spéciality : </strong> {{$classe->nom_anglais}}  <strong class="traduction">- Niveau : </strong>{{$nniveau->nom}}</p>
                    </div>
                    <div class="annee_a">
                        <p><strong>Année académique : </strong> {{session('year')}}</p>
                        <p><strong class="traduction">Academic year : </strong></p>
                    </div>
                </div>
                <table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px;">
                    <thead>
                    <th ><strong>Intitulé de l'UE</strong> <span class="traduction">UE title</span></th>
                    <th class="note-column"><strong>Semestre <span class="traduction">Semester</span></strong>  </th>
                    <th class="note-column"><strong>Crédit</strong></th>
                    <th class="note-column"><strong>Moy /100</strong>  </th>
                    <th class="note-column"><strong>Qualité /4</strong>  </th>
                    <th class="note-column"><strong>Mention</strong> <span class="traduction">Grade</span></th>

                    </thead>
                    <tbody>
                    <tr>
                        <td><strong style="font-size: 12px;margin-left: 80px">Transversales\<span class="traduction">Transversal</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                        @foreach($matieres as $m)
                            @if(($matiere->id_matiere == $m->id) && ($m->type==0))
                            <td>
                                {{$m->nom}} \ <span class="traduction">{{$m->nom_anglais}}</span>
                            </td>
                            <td>{{getSemestre($m->semestre)}}</td>
                            <td>{{$matiere->credit}}</td>
                            <td>{{$matiere->note}}</td>
                            <td>{{getGrade($matiere->note)[1]}}</td>
                            <td>{{getGrade($matiere->note)[0]}}</td>
                        </tr>
                            <?php break; ?>
                            @endif
                        @endforeach
                    @endforeach

                    <tr>
                        <td><strong style="font-size: 12px;margin-left: 80px">Professionnelles\<span class="traduction">Professional</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                        @foreach($matieres as $m)
                            @if(($matiere->id_matiere == $m->id) && ($m->type==1))
                                <td>
                                    {{$m->nom}} \ <span class="traduction">{{$m->nom_anglais}}</span>
                                </td>
                                <td>{{getSemestre($m->semestre)}}</td>
                                <td>{{$matiere->credit}}</td>
                                <td>{{$matiere->note}}</td>
                                <td>{{getGrade($matiere->note)[1]}}</td>
                                <td>{{getGrade($matiere->note)[0]}}</td>
                                </tr>
                                <?php break; ?>
                            @endif
                        @endforeach
                    @endforeach

                    </tbody>
                </table>
                <div class="req" style="font-size: 15px; text-align: center">
                    <p>Moyenne générale pondérée(MGP) \ <span class="traduction">Grade Point Average(GPA)</span>  : <strong>{{round($releve->mgp,2)}} /4</strong> </p>
                    <p>Décision \ <span class="traduction">Decision </span> : <strong>{{getDecision(round($releve->mgp,2))}}</strong> </p>
                </div>
            </div>
            <div class="sign-footer">
                <div class="jury">
                    <p><strong>Le président du jury</strong></p>
                    <p><strong class="traduction">The president of the jury</strong></p>
                </div>
                <div class="dep">
                    <p><strong>Le ..........................</strong></p>
                    <p><strong>Le chef de département</strong></p>
                    <p><strong class="traduction">The head of Department</strong></p>
                </div>
            </div>
            <div class="releve-fait-le" style="font-size: 10px;display: none">
                <p>Fait le {{date('d-m-Y')}}</p>
            </div>
            @if($ne>0)
            <div class="copy col-md-12" >
                &copy;  2017 SchoolConcept | by: <a href="http://youth-concept.com" style="color:#fff;" target="_blank">youth-concept</a>
            </div>
            @endif
        </div>
        <?php $ne++; ?>
    @endforeach

@endsection