@extends('skeleton')
@section('sub-content')
    @include('perso.functions')

    <style>
        .result-table thead,.result-table thead th
        {
            height: 5px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>
    <a id="btnPrint" classe="{{$classe}}" cycle="{{$cycle}}" nniveau="{{$nniveau}}" href="#" class="btn btn-lg btn-warning" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')</a>

    <link rel="stylesheet"  href="{{URL::asset('css/releves.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/custom-header.css')}}">


    <div id="releves-container">

   </div>



    <script>

        $(function () {
            get_releves();
        });

        function get_releves(){
            btPrint=$('#btnPrint');
            classe=btPrint.attr('classe');
            cycle=btPrint.attr('cycle');
            nniveau=btPrint.attr('nniveau');

            $.ajax({
                url:'{{route('get_releves')}}',
                type:'GET',
                data:{
                  id:classe,
                  cycle:cycle,
                  nniveau:nniveau

                },
                beforeSend: function () {
                    showLoader();
                },
                success: function (data) {
                    //console.log(data);
                    fillReleves(data);

                },
                error: function () {
                    alert('Une erreur est survenue\n An error has occured');
                },
                complete: function () {
                    hideLoader();
                }


            })

        }

        function fillReleves(data){
            unit='';
            rCont=$('#releves-container');

            //variables
            eleves=data['eleves'];
            header=data['header'];
            PV=data['PV'];
            matieres=data['matieres'];
            nb_matieres=data['nb_matieres'];
            classe=data['classe'];
            cycle=data['cycle'];
            nniveau=data['nniveau'];
            niveau=data['niveau'];
            semestre=data['semestre'];
            cYear=data['year'];

            ne=0;
             for(items in PV){
                 if(PV.hasOwnProperty(items)){
                    releve=PV[items];
                }
                else
                {
                    releve=items;
                }

            sCoef=0;
            TMP=0;
            TMV=0;
            sNote=0;
            mat=releve.matieres



            //début relevé

        unit='<div class="releve">'+
                    header+

                '<div class="content">'+

                '<div class="titre">'+
                '<p style="font-size: 22px;"><strong>RELEVE DE NOTES ANNUEL</strong></p>'+
           ' <strong>'+
            '<p class="big-traduction">ANNUAL TRANSCRIPT </p>'+
            '</strong>'+
           ' </div>'+
           ' <div class="nom-matricule-container">'+
               ' <div class="nom">'+
                '<p><strong>Nom et Prénoms : </strong>'+ releve.eleve.nom +' '+(releve.eleve.prenom?releve.eleve.prenom:'') +'</p>'+
            '<p><strong class="traduction">'+
                'Surname and name'+
            '</strong></p>'+
           ' </div>'+
           ' <div class="matricule">'+
                '<p><strong>Matricule : </strong>'+ releve.eleve.matricule +'</p>'+
           ' <p><strong class="traduction">'+
                'Registration N°'+
       ' </strong></p>'+
           ' </div>'+
           ' </div>'+
            '<div class="date-lieu-container">'+
               ' <div class="date">'+
                '<p><strong>Née le: </strong> '+ releve.eleve.date_naiss +'</p>'+
            '<p><strong class="traduction">Born on</strong></p>'+
           ' </div>'+
            '<div class="lieu">'+
                '<p><strong>A :</strong> '+ releve.eleve.lieu_naiss +'</p>'+
            '<p><strong class="traduction">At </strong></p>'+
                '</div>'+
                '</div>'+
               ' <div class="cycle-filiere">'+
                '<div class="cycle">'+
                '<p><strong>Cycle : </strong> '+ cycle.nom +'</p>'+
            '<p><strong class="traduction">Cycle : </strong></p>'+
            '</div>'+
            '<div class="filiere">'+
                '<p><strong>Filière : </strong> '+ niveau.nom +'</p>'+
            '<p class="traduction"><strong class="traduction">Discpline : </strong> '+ niveau.nom_anglais +'</p>'+
            '</div>'+

           ' </div>'+

            '<div class="specialite-annee">'+
                '<div class="specialite">'+
                '<p><strong>Spécialité : </strong> '+ classe.nom +' <strong>- Niveau : </strong>'+ nniveau.nom + '</p>'+
            '<p class="traduction"><strong class="traduction">Spéciality : </strong> '+ classe.nom_anglais +'  <strong class="traduction">- Niveau : </strong>'+ nniveau.nom + '</p>'+
            '</div>'+
            '<div class="annee_a">'+
                '<p><strong>Année académique : </strong> '+ cYear +'</p>'+
           ' <p><strong class="traduction">Academic year : </strong></p>'+
           ' </div>'+
            '</div>'+
            '<table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px; margin-bottom: 10px;">'+
               '<thead>'+
               ' <th ><strong>Intitulé de l\'UE</strong> <span class="traduction">UE title</span></th>'+
            '<th class="note-column"><strong>Semestre <span class="traduction">Semester</span></strong>  </th>'+
                '<th class="note-column"><strong>Crédit</strong></th>'+
               ' <th class="note-column"><strong>Moy /20</strong>  </th>'+
                '<th class="note-column"><strong>Qualité /4</strong>  </th>'+
                '<th class="note-column"><strong>Mention</strong> <span class="traduction">Grade</span></th>'+

               ' </thead>'+
                '<tbody>'+
               ' <tr>'+
                '<td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;">Unités d\'enseignements Fondamentales\ <span class="traduction">Fundamental</span></strong></td>'+
            '</tr>';

                for(item in mat){
                if(mat.hasOwnProperty(item)){
                    matiere=mat[item];
                }
                else
                {
                    matiere=item;
                }
            if(matiere.type==0){

                if(isNaN(matiere.note)){
                    cuN=0;
                    showed='-';
                }
                else{
                    cuN=matiere.note;
                    showed=(cuN/5).toFixed(2);
                }

                sNote+=cuN*matiere.credit;
                sCoef+=matiere.credit;
                TMP++;
                if(cuN>=50)
                    TMV++;

                unit+='<tr>' +
                    '<td>'+ matiere.altName +'</td>'+

                    '<td>'+ getSemestre(matiere.semestre) +'</td>'+
                    '<td>'+ matiere.credit +'</td>'+
                    '<td>'+ showed +'</td>'+
                    '<td>'+ getGrade(matiere.note)[1] +'</td>'+
                    '<td>'+ getGrade(matiere.note)[0] +'</td>'+
                    '</tr>';
            }
        }







            unit+='<tr>'+
            '<td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;">Unités d\'enseignement Transversales\<span class="traduction">Transversal</span></strong></td>'+
           ' </tr>';
                   for(item in mat){
                if(mat.hasOwnProperty(item)){
                    matiere=mat[item];
                }
                else
                {
                    matiere=item;
                }
            if(matiere.type==1){

                if(isNaN(matiere.note)){
                    cuN=0;
                    showed='-';
                }
                else{
                    cuN=matiere.note;
                    showed=(cuN/5).toFixed(2);
                }

                sNote+=cuN*matiere.credit;
                sCoef+=matiere.credit;
                TMP++;
                if(cuN>=50)
                    TMV++;

                unit+='<tr>' +
                    '<td>'+ matiere.altName +'</td>'+

                    '<td>'+ getSemestre(matiere.semestre) +'</td>'+
                    '<td>'+ matiere.credit +'</td>'+
                    '<td>'+ showed +'</td>'+
                    '<td>'+ getGrade(matiere.note)[1] +'</td>'+
                    '<td>'+ getGrade(matiere.note)[0] +'</td>'+
                    '</tr>';
            }
        }




                unit+='<tr>'+
            '<td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;">Unités d\'enseignements Professionnelles\<span class="traduction">Professional</span></strong></td>'+
            '</tr>';
                 for(item in mat){
                if(mat.hasOwnProperty(item)){
                    matiere=mat[item];
                }
                else
                {
                    matiere=item;
                }
            if(matiere.type==2){

                if(isNaN(matiere.note)){
                    cuN=0;
                    showed='-';
                }
                else{
                    cuN=matiere.note;
                    showed=(cuN/5).toFixed(2);
                }

                sNote+=cuN*matiere.credit;
                sCoef+=matiere.credit;
                TMP++;
                if(cuN>=50)
                    TMV++;

                unit+='<tr>' +
                    '<td>'+ matiere.altName +'</td>'+

                    '<td>'+ getSemestre(matiere.semestre) +'</td>'+
                    '<td>'+ matiere.credit +'</td>'+
                    '<td>'+ showed +'</td>'+
                    '<td>'+ getGrade(matiere.note)[1] +'</td>'+
                    '<td>'+ getGrade(matiere.note)[0] +'</td>'+
                    '</tr>';
            }
        }



                moy=sNote/sCoef;
            moy4=moy/25;
            moy20=moy/5;
            cD=new Date();
            unit+='</tbody>'+
            '</table>'+



            '<table class="table result-table table-striped table-condensed table-inverse" style="font-size: 11px;margin-top: 0;">'+
                '<thead>'+
                '<th colspan="2">Travail annuel</th>'+
                '<th ">Crédit</th>'+
            '<th>T.M.P</th>'+
            '<th>T.M.V</th>'+
            '<th>Moyenne</th>'+
            '<th>MGP</th>'+
            '<th>Cote</th>'+
            '<th colspan="2">Mention</th>'+
                '</thead>'+
                '<tbody style="text-align: center">'+
                '<tr>'+
               ' <td rowspan="2">Moy.Annuelle/<span class="traduction">Annual Av.</span></td>'+
            '<td>/ 100 </td>'+
                    '<td rowspan="2">'+ sCoef +'</td>'+
            '<td rowspan="2">'+ TMP +'</td>'+
                '<td rowspan="2">'+ TMV +'</td>'+
                '<td>'+ moy.toFixed(2) +'</td>'+
                '<td rowspan="2">'+ moy4.toFixed(2) +'</td>'+
                '<td rowspan="2">'+ getGrade(moy)[0] +'</td>'+
                '<td colspan="2">'+ getMention(moy,100) +'</td>'+

                '</tr>'+
                '<tr>'+
               ' <td>/ 20</td>'+
                '<td>'+ moy20.toFixed(2) +'</td>'+
                '<td>Decision :</td>'+
            '<td><strong style="font-size: 13px">'+ getDecision(moy4) +'</strong></td>'+
                '</tr>'+
                '<tr>'+
                '<td colspan="10">'+
                '<strong class="traduction">T.M.P: </strong> <em class="traduction">Total des modules prévus</em>'+
            '&emsp;'+
        '<strong class="traduction">T.M.V: </strong> <em class="traduction">Total des modules Validés</em>'+

            '</td>'+


            '</tr>'+

            '</tbody>'+




            '</table>'+

            '</div>'+




            '<div class="sign-footer">'+
                '<div class="jury">'+
                '<p><strong class="traduction"></strong></p>'+
                '<P></P>'+
                '<p><strong><u>Le Responsable des Affaires Académiques</u></strong></p>'+

            '</div>'+
            '<div class="dep">'+
                '<p></p>'+
                '<p><strong>YAOUNDE Le ..........................</strong></p>'+
            '<p><u><strong>Le Directeur</strong></u></p>'+
            '</div>'+
            '</div>'+
            '<div class="releve-fait-le" style="font-size: 10px;display: none">'+
                '<p>Fait le '+ cD.getDate()+'/'+cD.getMonth()+'/'+cD.getFullYear() +'</p>'+
            '</div>';
                if(ne>0){
                    unit+='<div class="copy col-md-12" >'+
                      '&copy;  2017 SchoolConcept | by: <a href="http://youth-concept.com" style="color:#fff;" target="_blank">youth-concept</a>'+
                            '</div>';
                }

                unit+='</div>';
            ne++;


                rCont.append(unit);

            }

        }


        function getGrade(note){
             var grade=[];
            if(note>=80){
                grade[0]='A';
                grade[1]=4;
                return grade;
            }
            if(note>=75){
                grade[0]='A-';
                grade[1]=3.7;
                return grade;
            }
            if(note>=70){
                grade[0]='B+';
                grade[1]=3.3;
                return grade;
            }
            if(note>=65){
                grade[0]='B';
                grade[1]=3;
                return grade;
            }
            if(note>=60){
                grade[0]='B-';
                grade[1]=2.7;
                return grade;
            }
            if(note>=55){
                grade[0]='C+';
                grade[1]=2.3;
                return grade;
            }
            if(note>=50){
                grade[0]='C';
                grade[1]=2;
                return grade;
            }

            if(note>=45){
                grade[0]='C-';
                grade[1]=1.7;
                return grade;
            }
            if(note>=40){
                grade[0]='D+';
                grade[1]=1.3;
                return grade;
            }
            if(note>=35){
                grade[0]='D';
                grade[1]=1;
                return grade;
            }
            if(note>=30){
                grade[0]='E';
                grade[1]=0;
                return grade;
            }
            grade[0]='F';
            grade[1]=0;
            return grade;

        }

        function getMention(note,max){
            locale='{{App::getLocale()}}';
            palier=10;
            if(note<(max*palier)/100){
                mention= (locale =='en')?  "Almost zero" :  "Presque nul";
                return mention;
            }

            palier=20;
            if(note<(max*palier)/100){
                mention= (locale=='en')?  "Very weak" :  "Très faible";
                return mention;
            }

            palier=30;
            if(note<(max*palier)/100){
                mention= (locale=='en')?  "Weak" :  "Faible";
                return mention;
            }

            palier=40;
           if(note<(max*palier)/100){
                mention= (locale=='en')?  "Insufficient" :  "Insuffisant";
                return mention;
            }

            palier=50;
            if(note<(max*palier)/100){
                mention= (locale=='en')?  "Poor" :  "Médiocre";
                return mention;
            }

            palier=60;
             if(note<(max*palier)/100){
                mention= (locale=='en')?  "Fair" :  "Passable";
                return mention;
            }

            palier=70;
           if(note<(max*palier)/100){
                mention= (locale=='en')?  "Pretty good" :  "Assez bien";
                return mention;
            }

            palier=80;
           if(note<(max*palier)/100){
                mention= (locale=='en')?  "Good" :  "Bien";
                return mention;
            }

            palier=90;
            if(note<(max*palier)/100){
                mention= (locale=='en')? "Very good" :  "Très bien";
                return mention;
            }

            palier=100;
            if(note<(max*palier)/100)
                return "Excellent";



            mention= (locale=='en')?  "Perfect" :  "Parfait";
            return mention;

        }

        function getDecision(mgp){
            if(mgp>=2){
                return '{{trans('admin.admis')}}';

            }else
                return '{{trans('admin.echec')}}';
        }

        function getSemestre(val){
            if (val==1){
                return 1;
            }
            if (val==2){
                return 2;
            }
            if (val==0){
                return '1 & 2';
            }
        }

    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('eleves/releves')}}">@lang('admin.choix_classe')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection