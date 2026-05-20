@extends('skeleton')

@section('sub-content')
    <div class="add-container col-md-12" style="margin-top: 25px">

        @include('perso.functions')

        <?php
        // 🔹 Si le controller n’a pas donné libelleType, on le recalcule ici
        if (!isset($libelleType)) {
            switch ((int)$sequence) {
                case 1: $libelleType = 'CC 1'; break;
                case 2: $libelleType = 'CC 2'; break;
                case 3: $libelleType = trans('admin.session_normale').' 1'; break;
                case 4: $libelleType = trans('admin.session_normale').' 2'; break;
                case 5: $libelleType = trans('admin.rattrapage').' 1'; break;
                case 6: $libelleType = trans('admin.rattrapage').' 2'; break;
                case 7: $libelleType = 'TP 1'; break;
                case 8: $libelleType = 'TP 2'; break;
                default: $libelleType = '---';
            }
        }

        // 🔹 Pourcentage de la session (0 par défaut)
        $pourcentage = isset($pourcentage) ? (float) $pourcentage : 0.0;

        // 🔹 Année affichée
        $anneeAffichee = isset($annee_scolaire) ? $annee_scolaire : session('year');
        ?>

        <div class="row">
            <h3 class="col-md-10">
                {{ (App::getLocale()=='en') ? $matiere->nom_anglais : $matiere->nom }}
                :
                {{ $libelleType }}
                @if($pourcentage > 0)
                    ({{ $pourcentage }} %)
                @endif
                - {{ $anneeAffichee }}
            </h3>

            <div class="col-md-2" style="display: none">
                <a href="#" class="btn-xs btn btn-danger">
                    <span class="glyphicon glyphicon-trash"></span> @lang('admin.del')
                </a>
            </div>
        </div>

        <hr>

        <form accept-charset="UTF-8"
              role="form"
              method="POST"
              id="add_level_form"
              action="{{ URL::to('/eleves/notes/store/') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            {{-- Contexte de la note --}}
            <input type="hidden" name="id_matiere"      value="{{ $matiere->id }}">
            <input type="hidden" name="sequence"        value="{{ $sequence }}">
            <input type="hidden" name="id_classe"       value="{{ $id_classe }}">
            <input type="hidden" name="cycle"           value="{{ $id_cycle }}">
            <input type="hidden" name="nniveau"         value="{{ $id_nniveau }}">
            <input type="hidden" name="annee_scolaire"  value="{{ $anneeAffichee }}">

            <fieldset>
                <?php $i = 0; ?>

                @foreach($eleves as $e)
                    <?php
                    // Note existante éventuelle
                    $noteExistante = null;
                    if (isset($notes)) {
                        foreach ($notes as $n) {
                            if ($n->id_eleve == $e->id) {
                                $noteExistante = $n;
                                break;
                            }
                        }
                    }

                    // Valeur à afficher dans le champ (saisie sur 20)
                    $valAffichee = '';
                    if ($noteExistante) {
                        if ($pourcentage > 0) {
                            // note_stockée = saisie * (pourcentage / 20)
                            // => saisie = note_stockée * 20 / pourcentage
                            $valAffichee = round(($noteExistante->note * 20) / $pourcentage, 2);
                        } else {
                            $valAffichee = round($noteExistante->note, 2);
                        }
                      /*  dump($pourcentage);
                        die();
                        dump($noteExistante);
                        die();
                        dump($valAffichee);
                        die();*/
                    }
                    ?>

                    <div class="row" col-md-10>
                        <div class="form-group col-md-6">
                            <input type="checkbox"
                                   name="eleve[]"
                                   value="{{ $e->id }}"
                                   onchange="onCheckedChange(this,'note{{ $i }}')"
                                   @if($sequence != 5 && $sequence != 6) checked @endif
                                   tabindex="-1">
                            <label>{{ $e->nom.' '.$e->prenom }} :</label>
                        </div>

                        <div class="form-group col-md-4 row">
                            <div class="input-group">
                                <input type="number"
                                       id="note{{ $i }}"
                                       name="note[]"
                                       step="0.0001"
                                       class="form-control"
                                       @if($sequence == 5 || $sequence == 6) disabled @endif
                                       value="{{ $valAffichee }}"
                                       required>

                                <span class="input-group-addon">/ 20</span>
                            </div>
                        </div>
                    </div>

                    <?php $i++; ?>
                @endforeach

            </fieldset>

            @if($i != 0)
                <div class="col-md-7">
                    <input type="submit"
                           value="{{ trans('admin.confirmer') }}"
                           class="btn btn-success center-block btn-lg">
                </div>
            @else
                <div class="alert alert-info">
                    {{ trans('eleve.none') }}
                </div>
            @endif

        </form>
    </div>

    <script>
        function onCheckedChange(checkElt, inputId) {
            var input = document.getElementById(inputId);
            if (!input) return;
            input.disabled = !checkElt.checked;
        }
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{ url('eleves') }}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{ url('eleves/notes') }}">Notes</a></strong></li>
    <li>
        <a href="{{ url('eleves/notes/edit') }}">
            <strong>@lang('admin.choix_classe')</strong>
        </a>
    </li>
    <li>
        <a href="{{ url('eleves/notes/matieres').'?id='.$id_classe.'&cycle='.$id_cycle.'&nniveau='.$id_nniveau }}">
            <strong>@lang('admin.titre_matiere')</strong>
        </a>
    </li>
    <li class="active"><strong>{{ $titre }}</strong></li>
@endsection
