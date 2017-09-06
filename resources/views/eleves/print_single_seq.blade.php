@extends('skeleton')
@section('sub-content')
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_single_seq.css')}}">
    @foreach($eleves as $eleve)
    <div class="bulletin">
        <div class="bulletin-header">
            <div class="school-nav">
                <p><strong>{{$etablissement}}</strong></p>
                <p><strong>{{$adresse}}</strong></p>
                <p>{{$email}}</p>
                <p>{{$web}}</p>
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
                <p>Année scolaire {{session('year')}}</p>
            </div>
            <div class="bulletin-info">
                <div class="info-eleve">
                    <p>{{trans('admin.nom')}}: {{$eleve->nom.' '.$eleve->prenom}}</p>
                    <p>{{trans('eleve.ne')." $eleve->date_naiss ".trans('eleve.a')." $eleve->lieu_naiss "}}</p>
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
                           <td>{{$m->nom.'/'.$m->nom_anglais}}</td>
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
                    <tr>
                        <td><strong>Total</strong></td>
                        <td><strong>Coef</strong></td>
                        <td><strong>Moy/Ave</strong></td>
                        <td><strong>Rang/Ranking</strong></td>
                        <td><strong>M.classe/A. class</strong></td>
                        <td><strong>Max</strong></td>
                        <td><strong>Min</strong></td>
                        <td><strong>Mention</strong></td>
                    </tr>
                    <tr>
                        <td>{{$sTotal}}</td>
                        <td>{{$sTotalCoef}}</td>
                        <td>
                            @foreach($moyenne_eleve as $me)
                                @if($me->id_eleve==$eleve->id)
                                    {{round($me->moyenne,2)}}
                                @endif
                            @endforeach
                        </td>
                        <td style="text-align: center">
                            <?php $y=0; ?>
                            @foreach($moyenne_eleve as $me)
                                <?php $y++; ?>
                                    @if($me->id_eleve==$eleve->id)
                                        {{$y}}
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



            </div>
        </div>

    </div>
    @endforeach
    <?php
    function getMention($note,$max){
        $palier=10;
        if($note<($max*$palier)/100)
            return "Presque nul/Almost zero";
        $palier=20;
        if($note<($max*$palier)/100)
            return "Très faible/Very weak";
        $palier=30;
        if($note<($max*$palier)/100)
            return "Faible/Weak";
        $palier=40;
        if($note<($max*$palier)/100)
            return "Insuffisant/Insufficient";
        $palier=50;
        if($note<($max*$palier)/100)
            return "Médiocre/Poor";
        $palier=60;
        if($note<($max*$palier)/100)
            return "Passable/Fair";
        $palier=70;
        if($note<($max*$palier)/100)
            return "Assez bien/Pretty good";
        $palier=80;
        if($note<($max*$palier)/100)
            return "Bien/Good";
        $palier=90;
        if($note<($max*$palier)/100)
            return "Très bien/Very good";
        $palier=100;
        if($note<($max*$palier)/100)
            return "Excellent";


            return "Parfait/Perfect";
    }
    ?>

@endsection