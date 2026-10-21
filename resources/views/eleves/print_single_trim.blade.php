@extends('skeleton')
@section('sub-content')
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_single_seq.css')}}">
    <a href="#" class="btn btn-warning btn-lg" onclick="window.print()">{{trans('eleve.imprimer_bulletin')}}</a>
    <div id="hheader" class="hidden">
        @include('perso.header')
    </div>
    <style>
        .bulletin-photo
        {
            position: relative;
            min-height: 13mm;
            padding: 2px;

        }
        .bulletin-photo .photo
        {
            position: absolute;
            left: 0;
            bottom: 0;
            border: solid 1px;
            width: 30mm;
            height: 30mm;

        }
        .bulletin-photo .photo img
        {
           width: inherit;
            height: inherit;

        }
    </style>
        <div id="bulletin-list">

        </div>


    <script>
        function getBulletin(){
            startLoading();
            $.ajax({
                url: '{{url('/eleves/bulletins/print-single-trim')}}',
                type: 'GET',
                data: 'sequence={{$sequence}}&id={{$id_eleve}}&id_c={{$id_classe}}',
                success:function (data) {
                    console.log(data);
                    fillBulletin(data);
                    stopLoading();
                },
                error:function (data) {
                    console.log(data);
                    $('#error-text').html(data.responseJSON.error);
                    jQuery.noConflict();
                    $('#bull_error').modal('show');
                    stopLoading();
                }
            })
        }
        getBulletin();

        function fillBulletin(data) {
            var eleves=data.eleves;
            var bulletin_container=$('#bulletin-list');
            var header=$('#hheader');
            var bulletin_var='';
            for (eleve in eleves)
            {

                bulletin_var='';

                bulletin_header=' <div class="bulletin">'+
                    '<div class="bulletin-header">'+
                    header.html()+

                    ' </div>';

                bulletin_var+=(bulletin_header+
                '<div class="bulletin-body" style="padding-top:0.5cm!important">'+
                '<div class="bulletin-title">'+
                '<p style="font-size: 17px"><strong>BULLETIN TRIMESTRIEL N°'+ (parseInt(data.sequence)-8) +'</strong></p>'+
            '<p class="traduction">QUATERLY REPORT CARD N°  '+ (parseInt(data.sequence)-8) +'</p>'+
            '<p>Année scolaire {{session('year')}}</p>'+
            '<p class="traduction">School year {{session('year')}}</p>'+
            '</div>'+
            '<div class="bulletin-photo">'+
                '<div class="photo">');
                for(p in data.photos){
                    if(eleves[eleve].id==data.photos[p].eleve_id){
                        bulletin_var+=(' <img src="{{URL::asset('/')}}/'+ data.photos[p].url +'" alt="" class="photo-eleve">');
                        break;
                    }
                }

                bulletin_var+=(' </div>'+

                '</div>'+
                '<div class="bulletin-info">'+
                '<div class="info-eleve">'+
                '<p>Nom/Name : <strong style="font-size: 16px">'+ eleves[eleve].nom+' '+((eleves[eleve].prenom)?eleves[eleve].prenom:'') +'</strong> </p>'+
                '<p>Née le/Born at '+ eleves[eleve].date_naiss +' à/to '+ eleves[eleve].lieu_naiss +'</p>'+
            '</div>'+
           ' <div class="info-classe">'+
                '<p>Classe : '+ data.classe.nom +'</p>'+
            '<p>Effectif : '+ data.effectif +'</p>'+
            '</div>'+
           ' </div>'+
           ' <div class="bulletin-notes">'+


                '<table class="table table-bordered table-striped table-condensed table-inverse custom-table">'+
               ' <thead>'+
               ' <th>'+ data.titre_matiere +'</th>'+
                    '  <th style="width: 25px">Mois'+ data.seq1 +'</th>'+
                '<th style="width: 25px">Mois'+ data.seq2 +'</th>'+
                '<th style="width: 25px">Mois'+ data.seq3 +'</th>'+
                '<th>Note</th>'+
                '<th>Coef</th>'+
                '<th>Total</th>'+
                '<th>Rang</th>'+
               '<th>M. classe</th>'+
                '<th>Max/min</th>'+
                '<th>Appréciation</th>'+
               ' </thead>'+
                '<tbody>');

                sTotal=0;
                sTotalCoef=0;
                scoef=0;
                snote=0;
                curGroup=-10;
                group=-10;
                oldMatiere='';

                for(m in data.matieres){
                  group=data.matieres[m].groupe;
                  if((curGroup!=group)&& (curGroup>0)){
                      bulletin_var+=('<tr class="groupe-row"><td class="groupe-col"><strong> '+ ((data.local=='en')?oldMatiere.nom_groupe_dl:oldMatiere.nom_groupe) +'</strong></td>'+

                      '<td></td><td></td>    <td><strong>Total:</strong> '+ snote +'</td>'+
                      '<td></td>'+
                      '<td><strong>Coef:</strong> '+ scoef +'</td>'+
                      '<td></td>');
                      bulletin_var+=('<td><strong>Moy :</strong>');
                      if(scoef>0){
                          mmm=snote/scoef;
                          bulletin_var+=(parseFloat(mmm).toFixed(2));
                      }
                      else{
                          bulletin_var+=('0');
                      }
                      bulletin_var+=('</td>'+
                      '<td></td>'+
                      '<td></td>'+
                          '</tr>');
                      scoef=0;
                      snote=0;


                  }
                  curGroup=group;
                    oldMatiere=data.matieres[m];

                    bulletin_var+=('<tr>'+
                  '<td><strong><span style="line-height: 9px!important;padding-top:0!important;padding-bottom:0!important;margin-top: 0!important;margin-bottom: 0!important;">');
                  if(data.local=='en'){
                      bulletin_var+=(data.matieres[m].nom_anglais);
                  }
                  else {
                    bulletin_var+=(data.matieres[m].nom);
                  }

                    bulletin_var+=('</strong></span><p style="line-height: 9px!important;padding-top:0!important;margin-top: 0!important;font-size: 8px!important;">'+data.matieres[m].personnel+'</p></td>'+
                  '<td>');


                    z=0;
                    for (n in data.notesSeq1){
                        if(data.notesSeq1[n].id_matiere==data.matieres[m].id){
                            if(data.notesSeq1[n].id_eleve==eleves[eleve].id){
                                bulletin_var+=(data.notesSeq1[n].note+ '</td>');
                                break;
                            }
                        }
                    }
                    bulletin_var+='</td> <td>';

                    for (n in data.notesSeq2){
                        if(data.notesSeq2[n].id_matiere==data.matieres[m].id){
                            if(data.notesSeq2[n].id_eleve==eleves[eleve].id){
                                bulletin_var+=(data.notesSeq2[n].note+ '</td>');
                                break;
                            }
                        }
                    }
                    bulletin_var+='</td> <td>';
                    for (n in data.notesSeq3){
                        if(data.notesSeq3[n].id_matiere==data.matieres[m].id){
                            if(data.notesSeq3[n].id_eleve==eleves[eleve].id){
                                bulletin_var+=(data.notesSeq3[n].note+ '</td>');
                                break;
                            }
                        }
                    }


                    bulletin_var+='</td> <td>';

                    for (n in data.notes){
                        if(data.notes[n].id_matiere==data.matieres[m].id){
                            if(data.notes[n].id_eleve==eleves[eleve].id){
                                z++;
                                bulletin_var+=(data.notes[n].note+ '</td>');
                                curNote=data.notes[n].note;
                                scoef+=data.matieres[m].coef;
                                snote+=parseFloat(data.notes[n].note)*data.matieres[m].coef;
                                sTotal+=data.notes[n].note*data.matieres[m].coef;
                                sTotalCoef+=data.matieres[m].coef;
                                break;
                            }
                        }
                    }
                    bulletin_var+=(' <td>'+ data.matieres[m].coef +'</td><td>');

                    if(z>0){
                        bulletin_var+=(curNote*data.matieres[m].coef);
                    }
                    bulletin_var+=('</td> <td>');
                    x=0;
                    for(n in data.notes){
                        if(data.matieres[m].id==data.notes[n].id_matiere){
                            x++;
                            if(data.notes[n].id_eleve==eleves[eleve].id){
                                bulletin_var+=(x);
                                break;
                            }
                        }
                    }
                    bulletin_var+=('</td> <td>');
                    for(mm in data.moyenne_matiere){
                        if(data.moyenne_matiere[mm].id_matiere==data.matieres[m].id){
                            bulletin_var+=(parseFloat(data.moyenne_matiere[mm].moyenne).toFixed(2));
                            break;
                        }
                    }
                    bulletin_var+=('</td> <td>');


                    for(mm in data.moyenne_matiere){
                        if(data.moyenne_matiere[mm].id_matiere==data.matieres[m].id){
                            bulletin_var+=(data.moyenne_matiere[mm].max+' / '+data.moyenne_matiere[mm].min);
                            break;
                        }
                    }

                    bulletin_var+=('</td> <td>');
                    if(z>0){
                        bulletin_var+=getMention(curNote,data.matieres[m].max,data.local);
                    }
                    bulletin_var+=('</td> </tr>');

                }


                if(curGroup>0){
                    bulletin_var+=('<tr class="groupe-row"><td class="groupe-col"><strong>'+ ((data.local=='en')?oldMatiere.nom_groupe_dl:oldMatiere.nom_groupe) +'</strong></td>'+
                        '<td></td><td></td> <td><strong>Total:</strong> '+ snote +'</td>'+
                        '<td></td>'+
                        '<td><strong>Coef:</strong> '+ scoef +'</td>'+
                        '<td></td>');
                    bulletin_var+=('<td><strong>Moy :</strong>');
                    if(scoef>0){
                        mmm=snote/scoef;
                        bulletin_var+=(parseFloat(mmm).toFixed(2));
                    }
                    else{
                        bulletin_var+=('0');
                    }
                    bulletin_var+=('</td>'+
                        '<td></td>'+
                        '<td></td>'+
                        '</tr>');
                }

                scoef=0;
                snote=0;
                bulletin_var+=('  <tr style="-webkit-print-color-adjust:exact;">'+
                '<td colspan="3"><strong>Total</strong></td>'+
                '<td><strong>Coef</strong></td>'+
                ' <td  style="background: #adadad !important;"><strong>Moy</strong></td>'+
                '<td  style="background: #adadad !important;"><strong>Rang</strong></td>'+
                '<td><strong>M.classe</strong></td>'+
                '<td><strong>Max</strong></td>'+
                '<td><strong>Min</strong></td>'+
                '<td><strong>Mention</strong></td>'+
                ' </tr> <tr>'+
                '<td colspan="3">'+ sTotal +'</td>'+
                ' <td>'+ sTotalCoef +'</td>'+
                '<td style="text-align: center;font-size: 13px;">');

                for(me in data.moyenne_eleve){
                    if(data.moyenne_eleve[me].id_eleve==eleves[eleve].id){
                        bulletin_var+=('<strong>'+ parseFloat(data.moyenne_eleve[me].moyenne).toFixed(2) +'</strong>');
                        break;
                    }
                }
                bulletin_var+=('</td>  <td style="text-align: center;font-size: 13px">');
                y=0;
                for(me in data.moyenne_eleve){
                    y++;
                    if(data.moyenne_eleve[me].id_eleve==eleves[eleve].id){
                        bulletin_var+=(' <strong>'+ y +'</strong>');
                    }
                }
                bulletin_var+=('</td>'+
                '<td>'+ parseFloat(data.moyenne_classe.moyenne).toFixed(2) +'</td>'+
                '<td>'+ parseFloat(data.moyenne_classe.max).toFixed(2) +'</td>'+
                ' <td>'+ parseFloat(data.moyenne_classe.min).toFixed(2) +'</td>'+
                ' <td>'+ getMention(sTotal/sTotalCoef,20,data.local) +'</td>'+
                ' </tr>'
                +'</tbody>'+
                '</table>'+
                '<table class="table table-bordered table-striped table-condensed table-inverse custom-table">'+
                ' <thead>'+
                    '<tr><th></th>'+
                    '<th><strong>Retard</strong></th>'+
                    '<th><strong>abs.J</strong></th>'+
                    '<th><strong>abs.Inj</strong></th>'+
                    '<th><strong>Avert</strong></th>'+
                    '<th><strong>Blame</strong></th>'+
                    '<th><strong>Excl.</strong></th>'+
                    '</tr></thead>'+
                    '<tbody>'+
                ' <td style="font-size: 14px"><strong>Discipline</strong></td>');
                for(d in data.disciplines){
                    if(data.disciplines[d].id_eleve==eleves[eleve].id){
                        bulletin_var+=('<td>'+ data.disciplines[d].retard +'</td>'+
                            '<td>'+ data.disciplines[d].abscence_just +'</td>'+
                            '<td>'+ data.disciplines[d].abscence_inj +'</td>'+
                            '<td>'+ data.disciplines[d].avertissement +'</td>'+
                            '<td>'+ data.disciplines[d].blame +'</td>'+
                            '<td>'+ data.disciplines[d].exclusion +'</td></tr>');
                        break;
                    }
                }

                bulletin_var+='<tr class="text-center gray-row"> <td><strong>VISA DU PARENT/TUTEUR</strong></td><td colspan="3"><strong>OBSERVATIONS ET VISA DU PROFESSEUR PRINCIPAL</strong></td><td colspan="3"><strong>OBSERVATIONS ET VISA DU PREFET DES ETUDES</strong></td> </tr>'+
                    '<tr style="height: 1.2cm;"><td></td><td colspan="3"></td><td colspan="3"></td></tr>';

                bulletin_var+=('</tbody> </table> </div> </div>'+
                ' <div class="bulletin-footer">'+

                '<div class="principal">'+
                '<p><strong>Fait le '+ ('0'+(new Date()).getDate()).slice(-2)+'/'+ ('0'+((new Date()).getMonth() +1)).slice(-2)+'/'+ (new Date()).getFullYear() +'</strong></p>'+
                '</div>'+

                '</div>');


                bulletin_container.append(bulletin_var);

            }
        }




        function getMention(note,max,locale) {
            palier=10;
            note=parseFloat(note);
            max=parseFloat(max);
            if(note<(max*palier)/100){
                mention= (locale=='en')?  "Not acquired" :  "Non acquis";
                return mention;
            }

            palier=20;
            if(note<(max*palier)/100){
                mention= (locale=='en')?   "Not acquired" :  "Non acquis";
                return mention;
            }
            palier=30;
            if(note<(max*palier)/100){
                mention= (locale=='en')?   "Not acquired" :  "Non acquis";
                return mention;
            }
            palier=40;
            if(note<(max*palier)/100){
                mention= (locale=='en')?   "Not acquired" :  "Non acquis";
                return mention;
            }
            palier=50;
            if(note<(max*palier)/100){
                mention= (locale=='en')?   "Not acquired" :  "Non acquis";
                return mention;
            }
            palier=54;
            if(note<(max*palier)/100){
                mention= (locale=='en')? "Not acquired" :  "Non acquis";
                return mention;
            }
            palier=55;
            if(note<(max*palier)/100){
                mention= (locale=='en')?  "In process of acquisition" :  "En cours d'acquisition";
                return mention;
            }

            palier=60;
            if(note<(max*palier)/100){
                mention= (locale=='en')?  "In process of acquisition" :  "En cours d'acquisition";
                return mention;
            }
            palier=70;
            if(note<(max*palier)/100){
                mention= (locale=='en')?  "In process of acquisition" :  "En cours d'acquisition";
                return mention;
            }

            palier=74;
            if(note<(max*palier)/100){
                mention= (locale=='en')? "In process of acquisition" :  "En cours d'acquisition";
                return mention;
            }
            palier=80;
            if(note<(max*palier)/100){
                mention= (locale=='en')?  "Acquired" :  "Acquis";
                return mention;
            }
            palier=90;
            if(note<(max*palier)/100){
                mention= (locale=='en')?  "Acquired" :  "Acquis";
                return mention;
            }
            palier=100;
            if(note<(max*palier)/100)
                return "Expert";



            mention= (locale=='en')?  "Expert" :  "Expert";
            return mention;
        }
    </script>

    <div class="modal fade" id="bull_error">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #d9534f;color: white">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Erreur\Error</h4>
                </div>
                <div
                        class="modal-body">

                    <h3 id="error-text" class=""></h3>

                </div>





            </div>


        </div>
    </div>


@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('/eleves/bulletins')}}">@lang('admin.bull_mode')</a></strong></li>

    <li class="active"><strong>@lang('admin.bulletin')</strong></li>
@endsection