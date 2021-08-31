@extends('skeleton')
@section('sub-content')
    <link rel="stylesheet" href="{{asset('css/carte.css')}}">

    @if(isset($inscription))
        @if($inscription== 0)
            <div class="alert alert-danger">
                @lang('inscription.pas_inscrit')
            </div>
        @endif
        @else
        <div class="container">
            <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>


        </div>
        <?php $k=0 ?>

        @for($i=0;$i<$n;$i++)
            <div class="card-group">

                @for($j=0;$j<5;$j++)

                    <div class="row card-row">

                        @if(isset($eleves[$k]))
                            <?php $e=$eleves[$k] ?>
                            <div class="card-e left">
                                <div class="card-e-box">
                                    <div class="card-header">
                                        <div class="logo">
                                        </div>
                                        <div class="school text-uppercase">
                                          {{$nom_e}}
                                        </div>

                                    </div>

                                    <div class="titre text-center text-uppercase">
                                        {{trans_choice('admin.carte_e',1)}}
                                    </div>
                                    <div class="card-body">
                                        <div class="card-infos">
                                            <div class="infos-group text-uppercase">
                                                <div class="field-title">Matricule</div>
                                                <div>{{$e->matricule}}</div>
                                            </div>
                                            <div class="infos-group text-uppercase">
                                                <div class="field-title ">Nom</div>
                                                <div>{{$e->nom.' '.$e->prenom}}</div>
                                            </div>
                                            <div class="infos-group text-uppercase">
                                                <div class="field-title ">Né(e) à</div>
                                                <div>{{$e->lieu_naiss.', le '.(new DateTime($e->date_naiss))->format('d/m/Y')}}</div>
                                            </div>
                                            <div class="infos-group text-uppercase">
                                                <div class="field-title">Année scolaire:</div>
                                                <div>{{$annee_scolaire}}</div>
                                            </div>
                                            <div class="infos-group text-uppercase">
                                                <div class="field-title">Option</div>
                                                <div>{{$classe->nom}} - {{$nniveau->nom}}</div>
                                            </div>



                                        </div>
                                        <?php $url='img/avatar.png' ?>
                                        @foreach($photos as $p)
                                            @if($p->eleve_id==$e->id)

                                                <?php $url=$p->url ;break; ?>
                                            @endif

                                        @endforeach
                                        <div class="card-aside">
                                            <div class="card-photo">
                                                <img src="{{asset($url)}}" alt="">
                                            </div>
                                            <div class="card-sign text-uppercase">
                                                Le directeur
                                            </div>
                                        </div>
                                        <div class="text-uppercase fait-le">
                                            <div class="field-title ">Fait le {{date('d/m/Y')}}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        @endif
                        <?php $k++ ?>

                        @if(isset($eleves[$k]))
                            <?php $e=$eleves[$k] ?>


                            <div class="card-e right">

                                <div class="card-e-box">
                                    <div class="card-header">
                                        <div class="logo">
                                        </div>
                                        <div class="school text-center text-uppercase">
                                            {{$nom_e}}
                                        </div>
                                    </div>

                                    <div class="titre text-center text-uppercase">
                                        {{trans_choice('admin.carte_e',1)}}
                                    </div>
                                    <div class="card-body">
                                        <div class="card-infos">
                                            <div class="infos-group text-uppercase">
                                                <div class="field-title">Matricule</div>
                                                <div>{{$e->matricule}}</div>
                                            </div>
                                            <div class="infos-group ">
                                                <div class="field-title ">Nom</div>
                                                <div>{{$e->nom.' '.$e->prenom}}</div>
                                            </div>
                                            <div class="infos-group text-uppercase">
                                                <div class="field-title ">Né(e) à</div>
                                                <div>{{$e->lieu_naiss.', le '.(new DateTime($e->date_naiss))->format('d/m/Y')}}</div>
                                            </div>
                                            <div class="infos-group text-uppercase">
                                                <div class="field-title">Année académique:</div>
                                                <div>{{$annee_scolaire}}</div>
                                            </div>
                                            <div class="infos-group text-uppercase">
                                                <div class="field-title">Option</div>
                                                <div>{{$classe->nom}} - {{$nniveau->nom}}</div>
                                            </div>



                                        </div>
                                        <?php $url='img/avatar.png' ?>
                                        @foreach($photos as $p)
                                            @if($p->eleve_id==$e->id)

                                                <?php $url=$p->url ;break; ?>
                                            @endif

                                        @endforeach
                                        <div class="card-aside">
                                            <div class="card-photo">
                                                <img src="{{asset($url)}}" alt="">
                                            </div>
                                            <div class="card-sign text-uppercase">
                                                Le directeur
                                            </div>
                                        </div>
                                        <div class="text-uppercase fait-le">
                                            <div class="field-title ">Fait le {{date('d/m/Y')}}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endif
                        <?php $k++ ?>


                    </div>
                @endfor


            </div>
        @endfor






    @endif

@endsection