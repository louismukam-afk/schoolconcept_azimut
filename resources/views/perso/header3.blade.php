<?php
    $headerCampus = isset($campus) ? $campus : null;
    if (!$headerCampus && session('campus_id')) {
        $headerCampus = \gesecole\Campus::find(session('campus_id'));
    }

    $cleanHeader = function ($value) {
        return trim((string) $value);
    };

    $campusName = $headerCampus && $cleanHeader(isset($headerCampus->nom) ? $headerCampus->nom : '') !== ''
        ? $headerCampus->nom
        : 'INSTITUT SUPERIEUR AZIMUT';
?>
<link rel="stylesheet" href="{{URL::asset('css/custom-header.css')}}">
@include('perso.partials.campus_logo_style', array('campus' => $headerCampus))
<div class="custom-header">

    <div class="school-nav">
        <p><strong>REPUBLIQUE DU CAMEROUN</strong></p>
        <p class="traductions">Paix – Travail – Patrie</p>
        <p class="traductions">********</p>
        <P class="traductions">MINISTERE DE L'ENSEIGNEMENT SUPERIEUR</P>
        <p class="traductions">Cycle: BTS -LICENCES </p>

    </div>

    <div class="logo-nav">

    </div>
    <div class="other-nav">
        <p><strong>REPUBLIC OF CAMEROON</strong></p>
        <p class="traductions">   Peace – Work – Fatherland</p>
        <p class="traductions">********</p>
        <p class="traductions">MINISTRY OF HIGHER EDUCATION</p>
        <p class="traductions"> MASTERS PROFESSIONNELLES </p>

    </div>

</div>
<div class="titre-ecole" style="font-size: large; font-family: 'Arial Black'; text-align: center;">
    {{ $campusName }}
</div>
<!-- 
<div class="footer " style="text-align: center; font-family: 'Arial Black';font-size: large;" >
    Situé a la fabrique ngousso, a 100m de l'hopital général; Derrière l'école primaire maternelle des alouettes
</div> -->
<div class="div-bar">

</div>




