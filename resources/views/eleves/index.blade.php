@extends('index')
@section('content')
<?php
$homeBlocks = array(
    array('id'=>'notes', 'title'=>'Enregistrement et modification des notes', 'icon'=>'fa-pencil-square-o', 'links'=>array(
        array('/eleves/notes/', 'fa-pencil-square-o', 'Notes par session'),
        array('/eleves/notes/import', 'fa-file-excel-o', 'Importer les notes'),
        array('/eleves/soa/', 'fa-pencil-square-o', 'Notes de Soa'),
        array('/eleves/ndere/', 'fa-pencil-square-o', 'Notes de Ngaoundéré'),
        array('/eleves/estic/', 'fa-pencil-square-o', 'Notes d’Ebolowa'),
    )),
    array('id'=>'attestations', 'title'=>'Attestations de réussite des tutelles', 'icon'=>'fa-graduation-cap', 'links'=>array(
        array('/eleves/attestations_reussite_soa/', 'fa-graduation-cap', 'Attestations de réussite — Soa'),
        array('/eleves/attestations_reussite_ndere/', 'fa-graduation-cap', 'Attestations de réussite — Ngaoundéré'),
        array('/eleves/attestations_reussite_ebolowa/', 'fa-graduation-cap', 'Attestations de réussite — Ebolowa'),
    )),
    array('id'=>'releves', 'title'=>'Relevés de notes', 'icon'=>'fa-file-text-o', 'links'=>array(
        array('/eleves/releves_bts_annuel', 'fa-file-text-o', 'Relevé BTS annuel'),
        array('/eleves/releves_hnd_annuel', 'fa-file-text-o', 'Relevé HND annuel'),
        array('/eleves/releves_bts/', 'fa-file-text-o', 'Relevés BTS par session'),
        array('/eleves/releves_soa/', 'fa-file-text-o', 'Relevés de Soa'),
        array('/eleves/releves_ndere/', 'fa-file-text-o', 'Relevés de Ngaoundéré'),
        array('/eleves/releve_estic/', 'fa-file-text-o', 'Relevés d’Ebolowa'),
        array('/eleves/releves_bamenda/', 'fa-file-text-o', 'Bamenda transcripts'),
        array('/eleves/releves_bts/historique', 'fa-folder-open-o', 'Historique des relevés BTS'),
        array('/eleves/releves_hnd/historique', 'fa-folder-open-o', 'Historique des relevés HND'),
    )),
    array('id'=>'proces-verbaux', 'title'=>'Procès-verbaux de fin de semestre', 'icon'=>'fa-list-alt', 'links'=>array(
        array('/eleves/notes/show', 'fa-list-alt', 'PV par session — BTS / HND'),
        array('/eleves/notes/show_soa', 'fa-list-alt', 'Procès-verbaux de Soa'),
        array('/eleves/notes/show_ndere', 'fa-list-alt', 'Procès-verbaux de Ngaoundéré'),
        array('/eleves/notes/show_esstic', 'fa-list-alt', 'Procès-verbaux d’Ebolowa'),
    )),
    array('id'=>'discipline', 'title'=>'Discipline et présence', 'icon'=>'fa-bell-o', 'links'=>array(
        array('/eleves/discipline/', 'fa-bell-o', trans('eleve.titre_discipline')),
        array('/eleves/disciplineetd', 'fa-check-square-o', 'Effectuer l’appel en salle par spécialité'),
    )),
);
?>
<style>
    .student-home-block { margin-bottom: 24px; }
    .student-home-block .panel-heading { padding: 13px 16px; }
    .student-home-block .panel-title { font-size: 18px; font-weight: 600; }
    .student-home-block .panel-title .fa { margin-right: 8px; }
    .student-home-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; }
    .student-home-grid:before, .student-home-grid:after { display: none; }
    .student-home-link { display: flex; flex-direction: column; justify-content: center; align-items: center; gap: 12px; min-height: 130px; padding: 18px 12px; border: 1px solid #ddd; border-radius: 4px; color: #214761; background: #fff; text-align: center; line-height: 1.4; font-size: 15px; font-weight: 600; }
    .student-home-link:hover, .student-home-link:focus { text-decoration: none; color: #15364b; background: #eef5fa; border-color: #337ab7; }
    @media (max-width: 1100px) { .student-home-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 480px) { .student-home-grid { grid-template-columns: 1fr; } }
</style>
<div class="student-home">
    @foreach($homeBlocks as $block)
    <section class="panel panel-primary student-home-block" id="home-{{ $block['id'] }}" aria-labelledby="heading-{{ $block['id'] }}">
        <div class="panel-heading">
            <h2 class="panel-title" id="heading-{{ $block['id'] }}"><i class="fa {{ $block['icon'] }}" aria-hidden="true"></i>{{ $block['title'] }}</h2>
        </div>
        <div class="panel-body student-home-grid">
            @foreach($block['links'] as $link)
            <a class="student-home-link" href="{{ url($link[0]) }}">
                <i class="fa {{ $link[1] }} fa-3x" aria-hidden="true"></i><span>{{ $link[2] }}</span>
            </a>
            @endforeach
        </div>
    </section>
    @endforeach
</div>
@endsection
@section('breadcrumb')
    <li class="active"><strong>@lang('menu.gestion_notes')</strong></li>
@endsection
