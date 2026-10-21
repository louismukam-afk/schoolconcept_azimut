<?php
    $clean = function ($value) {
        return trim((string) $value);
    };

    $assetPath = function ($value) use ($clean) {
        $path = str_replace('\\', '/', $clean($value));
        if ($path === '') return '';
        if (strpos($path, 'public/') === 0) $path = substr($path, 7);
        if (strpos($path, '/public/') !== false) $path = substr($path, strpos($path, '/public/') + 8);
        return ltrim($path, '/');
    };

    $firstPublicImage = function ($pattern) {
        $matches = glob(public_path($pattern));
        if (!$matches || !isset($matches[0])) return '';
        $publicRoot = rtrim(str_replace('\\', '/', public_path()), '/').'/';
        $path = str_replace('\\', '/', $matches[0]);
        return ltrim(str_replace($publicRoot, '', $path), '/');
    };

    $tutelle = isset($tutelle) ? $tutelle : null;
    $campus = isset($campus) ? $campus : null;

    $campusLogo = $assetPath($campus && isset($campus->logo) ? $campus->logo : '');
    $tutelleName = $tutelle && $clean(isset($tutelle->nom) ? $tutelle->nom : '') !== '' ? $tutelle->nom : '';
    $tutelleLogo = $assetPath($tutelle && isset($tutelle->logo) ? $tutelle->logo : '');
    $facultyName = $tutelle && $clean(isset($tutelle->faculte) ? $tutelle->faculte : '') !== '' ? $tutelle->faculte : '';
    $facultyLogo = $assetPath($tutelle && isset($tutelle->logo_faculte) ? $tutelle->logo_faculte : '');
    $tutellePhone = $tutelle && $clean(isset($tutelle->telephone) ? $tutelle->telephone : '') !== '' ? $tutelle->telephone : '';
    $tutelleEmail = $tutelle && $clean(isset($tutelle->email) ? $tutelle->email : '') !== '' ? $tutelle->email : '';
    $tutelleLocation = $tutelle && $clean(isset($tutelle->localisation) ? $tutelle->localisation : '') !== '' ? $tutelle->localisation : ($tutelle && $clean(isset($tutelle->ville) ? $tutelle->ville : '') !== '' ? $tutelle->ville : '');
    $tutelleWebsite = $tutelle && $clean(isset($tutelle->site_web) ? $tutelle->site_web : '') !== '' ? $tutelle->site_web : ($campus && $clean(isset($campus->site_web) ? $campus->site_web : '') !== '' ? $campus->site_web : '');
    $ministere = $tutelle && $clean(isset($tutelle->ministere_tutelle) ? $tutelle->ministere_tutelle : '') !== '' ? $tutelle->ministere_tutelle : '';
    $facultyNameEn = $tutelle && $clean(isset($tutelle->faculte_english) ? $tutelle->faculte_english : '') !== '' ? $tutelle->faculte_english : '';

    $identity = $tutelleName.' '.$facultyName.' '.$tutelleLocation;
    if ($tutelleLogo === '' && stripos($identity, 'ebolowa') !== false) $tutelleLogo = 'img/ebolowa.webp';
?>
<link rel="stylesheet" href="{{URL::asset('css/custom-header.css')}}">
@include('perso.partials.campus_logo_style')
<div class="custom-header module-custom-header">
    <div class="school-nav">
        <p><strong>REPUBLIQUE DU CAMEROUN</strong></p>
        <p class="traductions">Paix - Travail - Patrie</p>
        <p class="traductions">********</p>
        <p class="traductions">MINISTERE DE L'ENSEIGNEMENT SUPERIEUR</p>
        <p class="traductions">Cycle: BTS -LICENCES</p>
        <div class="module-header-extra">
            @if($tutelleLogo)<img src="{{asset($tutelleLogo)}}" class="module-header-extra-logo" alt="Logo tutelle">@endif
            @if($tutelleName)<div class="module-header-extra-name">{{$tutelleName}}</div>@endif
            @if($ministere)<div class="module-header-extra-line">{{$ministere}}</div>@endif
            @if($tutelleLocation)<div class="module-header-extra-line">Localisation : {{$tutelleLocation}}</div>@endif
            @if($tutellePhone)<div class="module-header-extra-line">Tel : {{$tutellePhone}}</div>@endif
            @if($tutelleEmail)<div class="module-header-extra-line">Email : {{$tutelleEmail}}</div>@endif
            @if($tutelleWebsite)<div class="module-header-extra-line">Site web : {{$tutelleWebsite}}</div>@endif
        </div>
    </div>

    <div class="logo-nav" @if($campusLogo) style="background-image: url('{{asset($campusLogo)}}') !important;" @endif></div>

    <div class="other-nav">
        <p><strong>REPUBLIC OF CAMEROON</strong></p>
        <p class="traductions">Peace - Work - Fatherland</p>
        <p class="traductions">********</p>
        <p class="traductions">MINISTRY OF HIGHER EDUCATION</p>
        <p class="traductions">MASTERS PROFESSIONNELLES</p>
        <div class="module-header-extra">
            @if($facultyLogo)<img src="{{asset($facultyLogo)}}" class="module-header-extra-logo" alt="Logo faculte">@endif
            @if($facultyName)<div class="module-header-extra-name">{{$facultyName}}</div>@endif
            @if($facultyNameEn)<div class="module-header-extra-line">{{$facultyNameEn}}</div>@endif
            @if($campus && $clean(isset($campus->nom) ? $campus->nom : '') !== '')<div class="module-header-extra-line">{{$campus->nom}}</div>@endif
            @if($campus && $clean(isset($campus->telephone) ? $campus->telephone : '') !== '')<div class="module-header-extra-line">Tel : {{$campus->telephone}}</div>@endif
            @if($campus && $clean(isset($campus->email) ? $campus->email : '') !== '')<div class="module-header-extra-line">Email : {{$campus->email}}</div>@endif
        </div>
    </div>
</div>
<div class="titre-ecole" style="font-size: large; font-family: 'Arial Black'; text-align: center;">
    INSTITUT SUPERIEUR AZIMUT
</div>
<div class="div-bar"></div>
