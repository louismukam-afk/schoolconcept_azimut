@extends('skeleton')
@section('sub-content')
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_single_seq.css')}}">
    <a href="#" class="btn btn-warning btn-lg" onclick="window.print()">{{trans('eleve.imprimer_bulletin')}}</a>

    @foreach($eleves as $eleve)
    <div class="bulletin">
        <div class="bulletin-header">
            <div class="school-nav">
                <p><strong>{{$etablissement}}</strong></p>
                <p><strong>{{$adresse}}</strong></p>
                <p>{{$email}}</p>
                <p>{{$numero}}</p>
            </div>
            <div class="logo-nav">

            </div>
            <div class="other-nav">
                <p><strong>{{trans('eleve.republique')}}</strong></p>
                <p>{{trans('eleve.devise')}}</p>
                <p>{{trans('eleve.ministere')}}</p>

            </div>
        </div>
        <div class="bulletin-body">
            <div class="bulletin-title">
                <p style="font-size: 17px"><strong>BULLETIN SEQUENTIEL N°{{$sequence}}</strong></p>
                <p class="traduction">SEQUENTIAL REPORT CARD N° {{$sequence}}</p>
                <p>Année scolaire {{session('year')}}</p>
                <p class="traduction">School year {{session('year')}}</p>
            </div>
            <div class="bulletin-info">
                <div class="info-eleve">
                    <p>Nom/Name : <strong style="font-size: 16px">{{$eleve->nom.' '.$eleve->prenom}}</strong> </p>
                    <p>Née le/Born at {{$eleve->date_naiss}} à/to {{$eleve->lieu_naiss}}</p>
                </div>
                <div class="info-classe">
                    <p>Classe : {{$classe->nom}}</p>
                    <p>Effectif : {{$effectif}}</p>
                </div>
            </div>
            <div class="bulletin-notes">


                <table class="table table-bordered table-striped table-condensed table-inverse custom-table">
                    <thead>
                    <th>{{trans('admin.titre_matiere')}}</th>
                    <th>Note</th>
                    <th>Coef</th>
                    <th>Total</th>
                    <th>Rang/Ranking</th>
                    <th>M. classe/A. class</th>
                    <th>Max/min</th>
                    <th>Appréciation</th>
                    </thead>
                    <tbody>
                    <?php
                            $sTotal=0;;
                            $sTotalCoef=0;
                    $scoef=0;
                    $snote=0;
                    $curGroup=-10;
                    $group=-10;

                    ?>
                   @foreach($matieres as $m)
                       <?php $group=$m->groupe;

                       ?>
                        @if(($curGroup!=$group) && ($curGroup>0))

                            <tr class="groupe-row"><td class="groupe-col"><strong>{{trans('admin.groupe').' '.$curGroup}}</strong></td>
                                 <td><strong>Total:</strong> {{$snote}}</td>
                                 <td></td>
                                 <td><strong>Coef:</strong> {{$scoef}}</td>
                                <td></td>
                                <td><strong>Moyenne/Average :</strong> {{round(($snote/$scoef),2)}}</td>
                                <td></td>
                                <td></td>
                             </tr>
                             <?php
                             $scoef=0;
                             $snote=0;

                             ?>
                        @endif

                       <?php $curGroup=$group; ?>
                       <tr>
                           <td>{{(session('locale')=='en')?$m->nom_anglais:$m->nom}}</td>
                           <td>
                               <?php $z=0;?>
                               @foreach($notes as $n)
                                    @if($n->id_matiere==$m->id)
                                        @if($n->id_eleve==$eleve->id)
                                               <?php
                                               $z++;?>
                                        {{$n->note}}

                                        <?php
                                           $curNote=$n->note;
                                       $scoef+=$m->coef;
                                       $snote+=$n->note*$m->coef;
                                       $sTotal+=$n->note*$m->coef;
                                       $sTotalCoef+=$m->coef;

                                           break; ?>
                                           @endif
                                    @endif
                               @endforeach
                           </td>
                           <td>{{$m->coef}}</td>
                           <td>@if($z>0){{$curNote*$m->coef}}@endif</td>
                           <td>
                               <?php  $x=0; ?>

                               @foreach($notes as $n)
                                   @if($m->id==$n->id_matiere)
                                       <?php
                                       $x++;
                                       ?>
                                       @if($n->id_eleve==$eleve->id)
                                           {{$x}}
                                           <?php break; ?>
                                       @endif
                                   @endif
                               @endforeach

                           </td>
                           <td>
                               @foreach($moyenne_matiere as $mm)
                                   @if($mm->id_matiere==$m->id)
                                      {{round($mm->moyenne,2)}}
                                   @endif
                               @endforeach
                           </td>
                           <td>
                               @foreach($moyenne_matiere as $mm)
                                   @if($mm->id_matiere==$m->id)
                                       {{$mm->max.' / '.$mm->min}}
                                   @endif
                               @endforeach
                           </td>
                           <td>@if($z>0){{getMention($curNote,$m->max)}}@endif</td>
                       </tr>
                   @endforeach
                    <tr class="groupe-row"><td class="groupe-col"><strong>{{trans('admin.groupe').' '.$curGroup}}</strong></td>
                        <td><strong>Total:</strong> {{$snote}}</td>
                        <td></td>
                        <td><strong>Coef:</strong> {{$scoef}}</td>
                        <td></td>
                        <td><strong>Moyenne/Average :</strong> {{round(($snote/$scoef),2)}}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php
                    $scoef=0;
                    $snote=0;

                    ?>
                    <tr style="-webkit-print-color-adjust:exact;">
                        <td><strong>Total</strong></td>
                        <td><strong>Coef</strong></td>
                        <td  style="background: #adadad !important;"><strong>Moy/Ave</strong></td>
                        <td  style="background: #adadad !important;"><strong>Rang/Ranking</strong></td>
                        <td><strong>M.classe/A. class</strong></td>
                        <td><strong>Max</strong></td>
                        <td><strong>Min</strong></td>
                        <td><strong>Mention</strong></td>
                    </tr>
                    <tr>
                        <td>{{$sTotal}}</td>
                        <td>{{$sTotalCoef}}</td>
                        <td style="text-align: center;font-size: 13px;">
                            @foreach($moyenne_eleve as $me)
                                @if($me->id_eleve==$eleve->id)
                                    <strong>{{round($me->moyenne,2)}}</strong>
                                @endif
                            @endforeach
                        </td>
                        <td style="text-align: center;font-size: 13px">
                            <?php $y=0; ?>
                            @foreach($moyenne_eleve as $me)
                                <?php $y++; ?>
                                    @if($me->id_eleve==$eleve->id)
                                        <strong>{{$y}}</strong>
                                    @endif
                                @endforeach
                        </td>
                        <td>{{round($moyenne_classe->moyenne,2)}}</td>
                        <td>{{round($moyenne_classe->max,2)}}</td>
                        <td>{{round($moyenne_classe->min,2)}}</td>
                        <td>{{getMention($sTotal/$sTotalCoef,20)}}</td>
                    </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped table-condensed table-inverse custom-table">
                    <thead>
                    <th></th>
                    <th><strong>Retard/delay</strong></th>
                    <th><strong>abs.J</strong></th>
                    <th><strong>abs.Inj</strong></th>
                    <th><strong>Avert/Warn</strong></th>
                    <th><strong>Blame</strong></th>
                    <th><strong>Excl.</strong></th>
                    </thead>
                    <tbody>
                    <td style="font-size: 14px"><strong>Discipline</strong></td>
                    <?php $u=0 ?>
                    @foreach($disciplines as $d)
                        @if($d->id_eleve==$eleve->id)
                            <td>{{$d->retard}}</td>
                            <td>{{$d->abscence_just}}</td>
                            <td>{{$d->abscence_inj}}</td>
                            <td>{{$d->avertissement}}</td>
                            <td>{{$d->blame}}</td>
                            <td>{{$d->exclusion}}</td>
                            <?php $u++ ?>
                        <?php break; ?>
                        @endif
                    @endforeach
                    @if($u<=0)
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    @endif
                    </tbody>
                </table>


            </div>
        </div>
        <div class="bulletin-footer">
            <div class="parent">
                <p><strong>Le parent</strong></p>
                <p class="traduction">The parent</p>
            </div>
            <div class="titulaire">
                <p><strong>Le titulaire</strong></p>
                <p class="traduction">The main professor</p>
            </div>
            <div class="principal">
                <p><strong>Le Principal</strong></p>
                <p class="traduction">The director</p>
            </div>

        </div>
    </div>
    @endforeach
    <?php
    function getMention($note,$max){
        $palier=10;
        if($note<($max*$palier)/100){
            $mention= (session('locale')=='en')?  "Almost zero" :  "Presque nul";
            return $mention;
        }

        $palier=20;
        if($note<($max*$palier)/100){
            $mention= (session('locale')=='en')?  "Very weak" :  "Très faible";
            return $mention;
        }
        $palier=30;
        if($note<($max*$palier)/100)
        {
            $mention= (session('locale')=='en')?  "Weak" :  "Faible";
            return $mention;
        }
        $palier=40;
        if($note<($max*$palier)/100)
        {
            $mention= (session('locale')=='en')?  "Insufficient" :  "Insuffisant";
            return $mention;
        }
        $palier=50;
        if($note<($max*$palier)/100)
        {
            $mention= (session('locale')=='en')?  "Poor" :  "Médiocre";
            return $mention;
        }
        $palier=60;
        if($note<($max*$palier)/100)
        {
            $mention= (session('locale')=='en')?  "Fair" :  "Passable";
            return $mention;
        }
        $palier=70;
        if($note<($max*$palier)/100)
        {
            $mention= (session('locale')=='en')?  "Pretty good" :  "Assez bien";
            return $mention;
        }        $palier=80;
        if($note<($max*$palier)/100)
        {
            $mention= (session('locale')=='en')?  "Good" :  "Bien";
            return $mention;
        }
        $palier=90;
        if($note<($max*$palier)/100)
        {
            $mention= (session('locale')=='en')?  "Very good" :  "Très bien";
            return $mention;
        }
        $palier=100;
        if($note<($max*$palier)/100)
            return "Excellent";



            $mention= (session('locale')=='en')?  "Perfect" :  "Parfait";
            return $mention;

    }
    ?>

@endsection