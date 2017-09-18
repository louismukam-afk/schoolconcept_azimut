@extends('skeleton')
@section('sub-content')
    @include('perso.header')
    <link href="{{URL::asset('css/recu_payement_salaire.css')}}" rel="stylesheet">
    <div class="titre-salaire">
        <strong>
            <p>RECU DE PAIEMENT DE SALAIRE</p>
            <p class="traduction">SALARY PAYMENT RECEIPT</p>
        </strong>

    </div>
    <div class="body-salaire">
        <p>Mr/Mme <strong>Nganda Tcheuwo Stéphanie Julienne</strong> atteste avoir recu paiement :</p>
        <p class="traduction">Mr/Mrs <strong class="traduction">Nganda Tcheuwo Stéphanie Julienne</strong> certifies having received payment: :</p>

        <p class="from" style="margin-top: 20px"><strong>DE :</strong> Chinda Melong Cyrille Joel</p>
        <p class="traduction"><strong class="traduction">FROM :</strong> Chinda Melong Cyrille Joel</p>

        <p style="margin-top: 20px"><strong>DU MONTANT DE :</strong> 175 000 @lang('inscription.devise')</p>
        <p class="traduction"><strong class="traduction">OF THE AMOUNT OF :</strong> 175 000 @lang('inscription.devise')</p>

        <p style="margin-top: 20px"><strong>POUR :</strong> Salaire</p>
        <p class="traduction"><strong class="traduction">FOR :</strong> Salary</p>

    </div>
@endsection