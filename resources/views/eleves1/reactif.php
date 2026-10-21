 <div class="content">

                <div class="titre">
                    <p style="font-size: 20px;"><strong>RELEVE DE NOTES ANNUEL/<strong>ANNUAL TRANSCRIPT </strong></strong></p>
                    <strong>
                        <p ></p>
                    </strong>
                </div>
                <div class="nom-matricule-container">
                    <div class="nom">
                        <p><strong>Nom et Prénoms / Surname and name : </strong>{{$releve->eleve->nom.' '.$releve->eleve->prenom}}</p>
                        <p><strong class="big-traductio">
                              
                            </strong></p>
                    </div>
                    <div class="matricule">
                        <p><strong>Matricule / Registration N°: </strong>{{$releve->eleve->matricule}}</p>
                        <p><strong class="big-traductio">
                              
                            </strong></p>
                    </div>
                </div>
                <div class="date-lieu-container">
                    <div class="date">
                        <p><strong>Né(e) le / Born on: </strong> {{(new DateTime($releve->eleve->date_naiss))->format('d/m/Y')}}</p>
                        <p><strong class="big-traductio"></strong></p>
                    </div>
                    <div class="lieu">
                        <p><strong>A/ At :</strong> {{$releve->eleve->lieu_naiss}}</p>
                        <p><strong class="big-traductio"> </strong></p>
                    </div>
                </div>
                <div class="cycle-filiere">
                    <div class="cycle">
                        <p><strong>Cycle / Cycle  : </strong> {{$cycle->nom}}</p>
                        <p><strong class="big-traductio" style="color: red"> </strong></p>
                    </div>

                    <div class="filiere">
                        <p><strong>Filière / Field : </strong> {{$niveau->nom}}</p>
                        <p class="big-traductio"><strong class="big-traductio"> </strong></p>
                    </div>
                      <div class="specialite">
                        <p><strong>Spécialité / Spéciality : </strong> {{$classe->nom}} </p>
                        </div>

                </div>

                <div class="specialite-annee">
                  - Niveau / Level : </strong>{{$nniveau->nom}}</p>
                        <p class="big-traductio"><strong class="big-traductio">   <strong class="big-traductio" style="margin-left:220px">                              </strong></p>
                    </div>
                    <div class="annee_a">
                        <p><strong>Année académique / Academic year : </strong> {{session('year')}}</p>
                        <p><strong class="big-traductio">  </strong></p>
                    </div>
                </div>
               <table class="table  table-striped table-condensed table-inverse" style="margin-top: 5px; margin-bottom: 5px;">
                    <thead>
                    <th>Codes</th>
                    <th ><strong>Intitulé de l'UE/</strong> <span class="big-traductio">Course title</span></th>
                    
                    <th class="note-column"><strong>Semestre <span class="big-traductio">Semester</span></strong>  </th>
               
                    <th class="note-column"><strong>Note /20</strong>  </th>
                   <!-- <th class="note-column"><strong>Qualité /4</strong>  </th>-->
                    <th class="note-column"><strong>Notation</strong> <span class="big-traductio">Grade</span></th>
                       <th class="note-column"><strong>Crédit</strong></th>

                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;">Unités d'enseignements Fondamentales\<span class="big-traductio">Fundamental courses</span></strong></td>
                    </tr>