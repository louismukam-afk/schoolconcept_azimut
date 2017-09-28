 @include('perso.header')
    <link href="{{URL::asset('css/recu_payement_salaire.css')}}" rel="stylesheet">
 <div class="bulletin-paie">
     <div class="titre">
         <div style="font-size: 23px"><strong>BULLETIN DE PAIE</strong></div>
         <strong class="traduction">PAYSLIP</strong>
     </div>
     <div class="content-mois">
         <div class="mois">
             <p style="font-size: 18px"><strong>{{getMois($payement->mois).' '.date('Y')}}</strong></p>
             <strong class="traduction">{{getMoisEng($payement->mois).' '.date('Y')}}</strong>
         </div>
     </div>
     <div class="identite">
         <p><strong>Nom : {{$personnel->nom}}</strong></p>
         <strong class="traduction">Name</strong>
         <p><strong>Matricule: {{$personnel->matricule}}</strong></p>
         <strong class="traduction">Matricle</strong>
         <p><strong>Fonctions :
                 @foreach($fonctions as $f)
                     @foreach($fonctions2 as $f2)
                         @if($f2->id==$f->id_fonction)
                             / {{$f2->nom}}
                         @endif
                         @endforeach
                @endforeach
             </strong></p>
     </div>

     <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
         <thead>
         <th></th>
         <th></th>
         <th><p><strong>GAIN</strong></p><strong class="traduction">GAIN</strong></th>
         <th><p><strong>RETENU</strong></p><strong class="traduction">RETENU</strong></th>
         </thead>
         <tbody>
             <tr>
                 <td><p><strong>TAUX HORAIRE</strong></p><strong class="traduction">HOURLY RATE</strong></td>
                 <td>{{$payement->taux.' '.trans('inscription.devise').' / '.trans('admin.heure')}}</td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td><p><strong>NOMBRE D'HEURES REALISEES</strong></p><strong class="traduction">REALIZED HOURS</strong></td>
                 <td>{{$payement->heure_realise}}</td>
                 <td></td>
                 <td></td>
             </tr>
             <tr>
                 <td><p><strong>SOLDE MENSUEL</strong></p><strong class="traduction">MONTHLY CASH AMOUNT</strong></td>
                 <td></td>
                 <td>{{$payement->taux*$payement->heure_realise.' '.trans('inscription.devise')}}</td>
                 <td></td>
             </tr>
             <tr>
                 <td><p><strong>SALAIRE DE BASE</strong></p><strong class="traduction">BASE SALARY</strong></td>
                 <td></td>
                 <td>{{$salaire->montant.' '.trans('inscription.devise')}}</td>
                 <td></td>
             </tr>
             <tr>
                 <td><p><strong>PRIMES</strong></p><strong class="traduction"></strong></td>
                 <td></td>
                 <td>{{$payement->primes.' '.trans('inscription.devise')}}</td>
                 <td></td>
             </tr>
             <tr>
                 <td><p><strong>ACCOMPTES</strong></p><strong class="traduction"></strong></td>
                 <td></td>
                 <td></td>
                 <td>{{$payement->acomptes.' '.trans('inscription.devise')}}</td>
             </tr>
             <tr>
                 <td><p><strong>CNSS</strong></p></td>
                 <td></td>
                 <td></td>
                 <td>{{$payement->cnss.' '.trans('inscription.devise')}}</td>
             </tr>
             <tr>
                 <td><p><strong>CAS SOCIALES</strong></p><strong class="traduction">SOCIALES CASES</strong></td>
                 <td></td>
                 <td></td>
                 <td>{{$payement->cas_sociales.' '.trans('inscription.devise')}}</td>
             </tr>
             <tr>
                 <td><p><strong>ASSURANCES</strong></p><strong class="traduction">INSURANCES</strong></td>
                 <td></td>
                 <td></td>
                 <td>{{$payement->assurances.' '.trans('inscription.devise')}}</td>
             </tr>
             <tr>
                 <td><p><strong>AUTRES RETENUES</strong></p><strong class="traduction"></strong></td>
                 <td></td>
                 <td></td>
                 <td>{{$payement->autre_retenues.' '.trans('inscription.devise')}}</td>
             </tr>
             <tr>
                 <td>TOTAL</td>
                 <td></td>
                 <td>{{$payement->taux*$payement->heure_realise+$salaire->montant+$payement->primes.' '.trans('inscription.devise')}}</td>
                 <td>{{$payement->acomptes+$payement->cnss+$payement->cas_sociales+$payement->assurances+$payement->autre_retenues.' '.trans('inscription.devise')}}</td>
             </tr>
         <tr>
             <td><p><strong>NET A PAYER</strong></p><strong class="traduction">EXACT SUM TO BUY</strong></td>
             <td>{{$payement->net_a_payer.' '.trans('inscription.devise')}}</td>
         </tr>
         </tbody>
     </table>
 </div>



<?php
    function getMois($mois){
        if($mois==1) return 'Janvier';
        if($mois==2) return 'Fevrier';
        if($mois==3) return 'Mars';
        if($mois==4) return 'Avril';
        if($mois==5) return 'Mai';
        if($mois==6) return 'Juin';
        if($mois==7) return 'Juillet';
        if($mois==8) return 'Aout';
        if($mois==9) return 'Septembre';
        if($mois==10) return 'Octobre';
        if($mois==11) return 'Novembre';
        if($mois==12) return 'Décembre';
    }
function getMoisEng($mois){
    if($mois==1) return 'January';
    if($mois==2) return 'February';
    if($mois==3) return 'March';
    if($mois==4) return 'April';
    if($mois==5) return 'Mai';
    if($mois==6) return 'June';
    if($mois==7) return 'July';
    if($mois==8) return 'August';
    if($mois==9) return 'September';
    if($mois==10) return 'October';
    if($mois==11) return 'November';
    if($mois==12) return 'Décember';
}
?>