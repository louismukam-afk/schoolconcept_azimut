{{--  @if($current_matiere==$nb_matieres)
            <?php $mgp=0;?>
            <td class="note-column">
                @foreach($PV as $pv)
                    @if($pv->id_eleve==$e->id)
                        {{round(($pv->mgp)/1.5,2)}}
                        <?php $mgp=round(($pv->mgp)/6.5,2);
                        if($mgp>=2)
                        {$admis++;}
                        else{$echec++;}
                        break; ?>
                    @endif

                @endforeach

            </td>

            <td class="note-column">{{$mgp}}</td>
            <td class="note-column">
                {{getDecision($mgp)}}
            </td>
        @endif--}}
{{--       @if($current_matiere==$nb_matieres)
                   <th class="note-column" rowspan="2">Moy/20</th>
                   <th class="note-column" rowspan="2">MGP</th>
                   <th class="note-column" rowspan="2">Decision</th>
                   <th class="note-column" rowspan="2">Matières non validées</th>

               @endif--}}