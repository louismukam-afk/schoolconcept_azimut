@extends('skeleton')
@section('sub-content')
    <div class="row">
        <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>


    </div>
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">


    @include('perso.header')

<div class="titre text-center">
    <h3> @lang('admin.analyse_note_classe')</h3>

</div>

    <div class="fiche-head" style="position: relative;padding: 5px 10px;margin-top: 25px;">
        <p style="font-size: 15px"> <strong>@lang('admin.classe'):</strong> {{$classe->nom}}</p>
    </div>
    <div class="fiche-head" style="position: relative;padding: 5px 10px;margin-bottom: 15px">
        <p> <strong>
                @if($sequence<=6)
                    @lang('eleve.sequence') {{$sequence}}
                @elseif($sequence<=9)
                    @lang('eleve.trimestre') {{$sequence-6}}
                @else
                    @lang('admin.year')
                @endif
            </strong> </p>
    </div>

    <div class="list-cont">

        <table  class="table  table-bordered  table-striped table-condensed table-inverse" id="idTableListe" style="margin-top: 5px;">
        <thead>
            <tr>
                <th rowspan="2">@lang('admin.matiere')</th>
                <th colspan="3">Total</th>
                <th colspan="4">Notes>=10</th>
                <th colspan="4">Notes [08 - 09,99]</th>
                <th colspan="4">Note < 08</th>
                <th rowspan="2">Max</th>
                <th rowspan="2">min</th>
                <th rowspan="2">Moy/Av</th>
                <th rowspan="2">@lang('admin.enseignant')</th>
            </tr>
        <tr>
            <th>F</th>
            <th>G</th>
            <th>T</th>
            <th>F</th>
            <th>G</th>
            <th>T</th>
            <th>%</th>
            <th>F</th>
            <th>G</th>
            <th>T</th>
            <th>%</th>
            <th>F</th>
            <th>G</th>
            <th>T</th>
            <th>%</th>

        </tr>

        </thead>
        <tbody>
        @foreach($matieres as  $m)
            <?php $n1=$notes->where('id_matiere',$m->id,false);$nb=$n1->count();

            $nb10=0;
            $nb89=0;
            $nb8=0;
            foreach ($notes->where('id_matiere',$m->id,false) as $n)
                {
                    if($n->note>=10)
                        $nb10++;
                    if($n->note>=8 && $n->note<10)
                        $nb89++;
                    if($n->note<8)
                        $nb8++;
                }

            $nb=$n1->count();
            $max=0;$min=0;
            $moy=0;
            $som=0;
            ?>
            <tr>
                <td>
                    {{(App::getLocale()=='en')?$m->nom_anglais:$m->nom}}
                </td>
                <td>
                    <?php $f=0; ?>
                    @foreach($n1 as $n)
                        <?php $som+=$n->note; ?>
                        @if($n->note>$max)
                                <?php $max=$n->note; ?>
                            @endif
                            @if($n->note<$min)
                                <?php $min=$n->note; ?>
                            @endif
                        @if(isIn($n->id_eleve ,$femmes))
                            <?php $f++ ?>
                            @endif
                    @endforeach
                        <?php
                        if($nb<=0)
                            $nb=1;
                        $moy=$som/$nb ?>
                    {{$f}}
                </td>
                <td>{{$nb-$f}}</td>
                <td>{{$nb}}   </td>

                <td>  <?php $f=0; ?>
                    @foreach($n1 as $n)

                        @if(isIn($n->id_eleve ,$femmes) && $n->note>=10)
                            <?php $f++ ?>
                        @endif
                    @endforeach
                    {{$f}}
                </td>
                <td>{{$nb10-$f}}</td>
                <td>{{$nb10}}</td>
                <td>{{round($nb10*100/$nb,2)}}%</td>

                <td>  <?php $f=0; ?>
                    @foreach($n1 as $n)

                        @if(isIn($n->id_eleve ,$femmes) && $n->note>=8 && $n->note<10)
                            <?php $f++ ?>
                        @endif
                    @endforeach
                    {{$f}}
                </td>
                <td>{{$nb89-$f}}</td>
                <td>{{$nb89}}</td>
                <td>{{round($nb89*100/$nb,2)}}%</td>

                <td>  <?php $f=0; ?>
                    @foreach($n1 as $n)

                        @if(isIn($n->id_eleve ,$femmes) && $n->note<8)
                            <?php $f++ ?>
                        @endif
                    @endforeach
                    {{$f}}
                </td>
                <td>{{$nb8-$f}}</td>
                <td>{{$nb8}}</td>
                <td>{{round($nb8*100/$nb,2)}}%</td>


                <td>{{round($max,2)}}</td>
                <td>{{round($min,2)}}</td>
                <td>{{round($moy,2)}}</td>
                <td>
                    @foreach($dispenses as $d)
                        @if($d->id_matiere==$m->id)
                        {{($d->personnel)?$d->personnel->nom:''}}
                            <?php break; ?>
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>


</div>

    <?php
    function isIn($id,$array)
    {
        $i=0;
        $trouve=false;
        while ($i<count($array) && !$trouve)
        {
            if ($array[$i]==$id){
                $trouve=true;
            }
            $i++;
        }
        return $trouve;
    }
    ?>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('/eleves/autres')}}">@lang('admin.autre_doc')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection