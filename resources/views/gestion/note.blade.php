<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{  isset($page_title) ? $page_title : (isset($titre) ? $titre : session('big_title'))  }}</title>
    <link rel="shortcut icon" href="{{URL::asset('img/log.png')}}" type="image/x-icon">
    <!-- BOOTSTRAP STYLES-->
    <link href="{{ URL::asset('dist/css/bootstrap.min.css')  }}" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="{{ URL::asset('css/font-awesome.css')  }}" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="{{ URL::asset('css/custom.css')  }}" rel="stylesheet" media="screen"/>
    <link href="{{ URL::asset('js/jquery-ui-1.12.1/jquery-ui.css')  }}" rel="stylesheet" media="screen"/>
    <link href="{{ URL::asset('js/datatables/datatables.min.css')  }}" rel="stylesheet"/>
    <link href="{{ URL::asset('js/chosen_v1.8.7/chosen.min.css')  }}" rel="stylesheet"/>


    <!-- GOOGLE FONTS-->

    <script src="{{ URL::asset('js/jquery.min.js')  }}"></script>

    <script src="{{ URL::asset('js/jquery-ui-1.12.1/jquery-ui.js')  }}"></script>
    <script src="{{ URL::asset('js/chosen_v1.8.7/chosen.jquery.min.js')}}"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="{{ URL::asset('dist/js/bootstrap.min.js')  }}"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="{{ URL::asset('js/custom.js')  }}"></script>

    <script src="{{ URL::asset('js/datatables/datatables.min.js')  }}"></script>


    <script>
        function startLoading() {
            // $('.loading-back').css('display','inline');
            $('.loading_icon').css('display','inline');
        }
        function stopLoading() {
            //$('.loading-back').css('display','none');
            $('.loading_icon').css('display','none');
        }

        function showLoader() {
            $('.loader').removeClass('hidden');
        }
        function hideLoader() {
            $('.loader').addClass('hidden');

        }
    </script>

    <style>
        #loader {
            display: none;
        }

        .loader{
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 100000;
            background-color: rgba(0, 0, 0, 0.6);
            top: 0;
            left: 0;
        }
        .loader img {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 150px;
            transform: translate(-50%, -50%);
        }
        #qrcode-input {
            height: 0;
        }
    </style>
</head>



<body>
<div  class="loader hidden">
    <img src="{{asset('img/loading3.gif')}}" alt="">
</div>
<div class="loading-back">  </div>
<div class="col-md-12" >
    <a href="#"><img class="back-button" src="{{URL::asset('img/back_button.png')}}" onclick="history.back()" width="50px" height="50px"></a>

</div>
<?php $u=\Illuminate\Support\Facades\Auth::user();
if(session('nom_etab')==null){
    $p=\gesecole\parametres::all();
    $nom_etab=$p->where('nom','nom_etablissement',false)->first();
    session(['nom_etab'=>$nom_etab->valeur]);
}
?>

<div class="loading_icon" style="
    position: fixed;
    Left: calc(50% - 75px);
    top: calc(50% - 37.5px);
    z-index: 10000;
     width: 150px;
     height: 75px;
         box-shadow: 0 10px 20px rgba(0, 0, 0, .5);
     display: none;
">
    <img src="{{URL::asset('img/loading.gif')}}" alt="" style="width: inherit;height: inherit">
</div>

<div id="wrapper">
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="adjust-nav">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand logo" href="#">
                    <img src="{{URL::asset('img/school-concept.png')}}" />

                </a>
            </div>
            <div class="nom-etab"><strong>{{session('nom_etab')}}</strong></div>


            <span class="logout-spn" >

                    <a href="{{ url('/gestion/login/etudiant/disconnect_user') }}">{{  trans('admin.logout')  }}</a>
                </span>
            <div ><span class="annee">{{  trans('admin.year')  }} <span>{{  $selected_inscription->annee_scolaire }}</span></span></div>
            <div class="langage ">
                <div class="lang dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle"><strong style="color: white">@lang('admin.langage') :</strong> @if(App::getLocale()=='fr')Francais @else English @endif <span class="caret"></span></a>
                    <ul class="dropdown-menu navbar-inverse">
                        <li><a href="{{url('language/french')}}"><strong>Français</strong></a></li>
                        <li><a href="{{url('language/english')}}"><strong>English</strong></a></li>
                    </ul>
                </div>

            </div>

        </div>
    </div>



    <div id="page-wrapper" >
        <div id="page-inner">
            <div class="row hidden">
                <div class="col-md-12">

                    <h2 class="big-title">{{  session('big_title')  }}</h2>
                </div>
            </div>
            <div class="col-md-12">
                <a  class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Imprimer</a>

            </div>
            <link rel="stylesheet" href="{{URL::asset('css/certificat.css')}}">

            <!-- /. ROW  -->
            <hr style="margin-top: 0;margin-bottom: 0" />
            <div class="row-fluid">
                <ul class="breadcrumb" style="margin-bottom: 0;padding: 2px 15px;font-size: 16px">

                    @yield('breadcrumb')
                </ul>
                <h3 style="text-align:center;" > {{  trans('admin.notess')  }}  <strong> {{$eleve->nom}} </strong></h3>
                <h4 >   <strong>{{  trans('admin.cycle')  }}</strong> : {{$selected_inscription->cycle->nom}} </h4>
                <h4 >  <strong> {{  trans('admin.special')  }}</strong> : {{$selected_inscription->classe->getNom()}} </h4>
                <h4 ><strong>{{  trans('admin.niveauss')  }}</strong>  : {{$selected_inscription->nniveau->nom }} </h4>

                <table class="table"  border="2"  style="margin-top: 15px; border: 2">
                    <thead>

                   <tr>
                       <th rowspan="2">#</th>
                       <th rowspan="2">{{  trans('admin.matiere')  }}</th>
                       <td colspan="4">{{  trans('admin.semestre')  }} </td>
                       <td colspan="4">{{  trans('admin.semestre')  }} 2</td>

                   </tr>
                   <tr>
                       <th>{{  trans('admin.note_cc1')  }}</th>
                       <th>{{  trans('admin.note_sn1')  }}</th>
                       <th>{{  trans('admin.note_Rattrapage1')  }}</th>
                       <th> Total /20 </th>
                       <th>{{  trans('admin.note_cc2')  }}</th>
                       <th>{{  trans('admin.note_sn2')  }}</th>
                       <th>{{  trans('admin.note_Rattrapage2')  }}</th>
                       <th> Total /20 </th>
                   </tr>
                    </thead>
                    <tbody>
                    <?php $i=1; ?>
                    @foreach($matieres as $m)


                        <tr>
                            <?php $s1=0 ; $s1=0 ;?>
                            <td>{{$i++}}</td>
                            <td><strong>{{$m->getNom()}}</strong></td>
                            <td>
                                @foreach($notes as $n)
                                    @if($n->id_matiere == $m->id && $n->sequence == 1)
                                        {{round($n->note / 1.5, 2)}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($notes as $n)
                                    @if($n->id_matiere == $m->id && $n->sequence == 3)
                                        {{round($n->note / 3.5, 2)}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($notes as $n)
                                    @if($n->id_matiere == $m->id && $n->sequence == 5)
                                        {{round($n->note / 3.5, 2)}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                           
                           </td>
                           
                          
                            <td>
                                @foreach($notes as $n)
                                    @if($n->id_matiere == $m->id && $n->sequence == 2)
                                        {{round($n->note / 1.5, 2)}}
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($notes as $n)
                                    @if($n->id_matiere == $m->id && $n->sequence == 4)
                                        {{round($n->note / 3.5, 2)}}
                                    @endif
                                @endforeach
                            </td>

                            <td>
                                @foreach($notes as $n)
                                    @if($n->id_matiere == $m->id && $n->sequence == 6)
                                        {{round($n->note / 3.5, 2)}}
                                    @endif
                                @endforeach
                            </td>
                            <td></td>
                        </tr>


                        @endforeach

                    </tbody>
                </table>

            </div>


            <div class="row" >
                <div class="col-md-12">
                    <div class="alert alert-danger print-error-msg" style="display: none">
                        <ul> </ul>
                    </div>
                    <div class="alert alert-success print-success-msg" style="display: none">
                        <ul> </ul>
                    </div>
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

            </div>
            <!-- /. ROW  -->

        @yield('content')
        <!-- /. ROW  -->

            <!-- /. ROW  -->
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
</div>


<div class="footer">


    <div class="row">
        <div class="col-md-12 copy" >
            &copy;  2017 SchoolConcept | by: <a href="http://youth-concept.com" style="color:#fff;" target="_blank">youth-concept</a>
        </div>
    </div>
</div>






</body>




</html>
