<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{  session('big_title')  }}</title>
    <link rel="shortcut icon" href="{{URL::asset('favicon.ico')}}" type="image/x-icon">
	<!-- BOOTSTRAP STYLES-->
    <link href="{{ URL::asset('css/bootstrap.css')  }}" rel="stylesheet" />
     <!-- FONTAWESOME STYLES-->
    <link href="{{ URL::asset('css/font-awesome.css')  }}" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
    <link href="{{ URL::asset('css/custom.css')  }}" rel="stylesheet" media="screen"/>
    <link href="{{ URL::asset('js/jquery-ui/jquery-ui.css')  }}" rel="stylesheet" media="screen"/>
    <link href="{{ URL::asset('js/datatables/datatables.min.css')  }}" rel="stylesheet"/>


    <!-- GOOGLE FONTS-->

    <script src="{{ URL::asset('js/jquery.min.js')  }}"></script>
    <script src="{{ URL::asset('js/jquery-ui/jquery-ui.min.js')  }}"></script>
    <!-- BOOTSTRAP SCRIPTS -->
    <script src="{{ URL::asset('js/bootstrap.min.js')  }}"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="{{ URL::asset('js/custom.js')  }}"></script>

   <!-- <script src="{{ URL::asset('js/datatables/datatables.min.js')  }}"></script> -->

<script>
    function startLoading() {
        $('.loading-back').css('display','inline');
        $('.loading_icon').css('display','inline');
    }
    function stopLoading() {
        $('.loading-back').css('display','none');
        $('.loading_icon').css('display','none');
    }
</script>
</head>
<body>
<div class="loading-back">  </div>
<div class="col-md-12" >
    <img class="back-button" src="{{URL::asset('img/back_button.png')}}" onclick="history.back()" width="50px" height="50px">
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
    Left: calc(50% - 37.5px);
    top: calc(50% - 37.5px);
    z-index: 10000;
     width: 75px;
     height: 75px;
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

                    <a href="{{ url('/auth/logout') }}">{{  trans('admin.logout')  }}</a>
                </span>
                <div ><span class="annee">{{  trans('admin.year')  }} <span>{{  session('year') }}</span> <a data-toggle="modal"  href="#change-year" class="btn btn-success btn-xs">{{  trans('admin.edit')  }}</a></span></div>
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
        <div class="col-md-4">
            <div class="modal fade" id="change-year">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div
                                class="modal-body">



                            <form accept-charset="UTF-8" role="form" method="POST" id="edit_classe_form" action="{{ url('/update-school-year/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                                <div class="form-group">
                                    <div class="row" style="text-align: center">
                                        <div class="col-md-2"><input type="text" name="first_year" placeholder="2017" class="form-control"></div> <span class="col-md-1 " style="font-size: 20px;font-weight: 600;"> - </span><div class="col-md-2"><input class="form-control" type="text" name="second_year" placeholder="2018" ></div>
                                    </div>

                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </form>


                        </div>





                    </div>


                </div>
            </div>
        </div>

        <!-- /. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                 


                    <li class="active-link">
                        <a href="#" ><i class="fa fa-desktop "></i>{{  trans('menu.titre')  }}</a>
                    </li>

                    <li>
                        <a href="{{  URL::to('/')  }}"><i class="fa fa-home "></i>{{  trans('menu.accueil')  }}</a>
                    </li>

                    <li>
                        <a href="{{URL::to('/inscriptions/')}}"><i class="fa fa-table "></i>{{  trans('menu.gestion_inscriptions')  }}</a>
                    </li>
                    <li>
                        <a href="{{URL::to('/eleves/')}}"><i class="fa fa-edit "></i>{{  trans('menu.gestion_notes')  }}</a>
                    </li>


                    <li>
                        <a href="{{URL::to('/personnel/')}}"><i class="fa fa-steam   "></i>{{  trans('menu.gestion_personnel')  }}</a>
                    </li>

                    <li>
                        <a href="{{URL::to('/comptabilite/')}}"><i class="fa fa-money"></i>{{  trans('admin.comptabilite')  }}</a>
                    </li>

                    <li style="display: none;">
                        <a href="#"><i class="fa fa-bar-chart-o"></i>{{  trans('menu.bilan')  }}</a>
                    </li>

                    <li>
                        <a href="{{  URL::to('/admin/')  }}"><span class="glyphicon glyphicon-lock"></span> {{  trans('menu.admin')  }}</a>
                    </li>
                    @if($u->role<=0)
                    <li>
                        <a href="{{  URL::to('/admin/blk')  }}"><span class="fa fa-ban"></span>BLK</a>
                    </li>

                        <li>
                            <a href="{{  URL::to('/admin/unblk')  }}"><span class="fa fa-ban"></span>UBLK</a>
                        </li>
                    @endif

                    
                </ul>
                            </div>

        </nav>
        <!-- /. NAV SIDE  -->
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">

                     <h2 class="big-title">{{  session('big_title')  }}</h2>
                    </div>
                </div>              
                 <!-- /. ROW  -->
                  <hr />
                <div class="row">
                <div style="height: 70px; background: #214761" class="action"></div>
                    <div class="col-lg-12 ">
                     @if(isset($isHome))
                        <div class="alert alert-info">
                            @if (Auth::guest())
                            @else
                             <strong>{{trans('admin.bienvenue')}} {{$u->fullname}} ! </strong>
                                <a data-toggle="modal" data-backdrop="false" href="#edit_pass" class="pull-right" style="color: #BE5C00"><span class="fa fa-lock"></span> @lang('admin.modifier_pass')</a>
                            @endif
                        </div>
                    @endif
                       
                    </div>

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


<div class="modal fade" id="edit_pass">
    <div class="modal-dialog">
        <div class="modal-content col-md-6">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">x</button>
                <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
            </div>
            <div
                    class="modal-body">



                <form accept-charset="UTF-8" role="form" method="POST" action="#">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <fieldset>

                        <div class="form-group">
                            <label for="ancien_mot_de_passe">{{  trans('admin.ancien_pass')  }} : <span class="required">*</span></label>
                            <input class="form-control" id="ancien_mot_de_passe" name="ancien_mot_de_passe" type="password"  value="{{ old('ancien_mot_de_passe') }}" required autofocus>

                        </div>
                        <div class="form-group">
                            <label for="nouveau_mot_de_passe">{{  trans('admin.nouveau_pass')  }} : <span class="required">*</span></label>
                            <input class="form-control" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" type="password"  value="{{ old('nouveau_mot_de_passe') }}" required >

                        </div>
                        <div class="form-group">
                            <label for="password_again">{{  trans('admin.password_again')  }} : <span class="required">*</span></label>
                            <input class="form-control" id="password_again" name="password_again" type="password"  value="{{ old('password_again') }}" required >

                        </div>
                        <a id="submit_new_pass" class="btn  btn-success" >{{  trans('admin.confirmer')  }}</a>


                    </fieldset>
                </form>


            </div>





        </div>


    </div>
</div>
    <div class="footer">
      
    
            <div class="row">
                <div class="col-md-12 copy" >
                    &copy;  2017 SchoolConcept | by: <a href="http://youth-concept.com" style="color:#fff;" target="_blank">youth-concept</a>
                </div>
            </div>
        </div>
          

     <!-- /. WRAPPER  -->
    <!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
    <!-- JQUERY SCRIPTS -->

    
   
</body>
<script>
    $(function () {
        $('#submit_new_pass').click(function () {
            $('#edit_pass').modal('hide')
            $.post('{{url('/user-profile/edit-password')}}',{ _token:'{{ csrf_token() }}',ancien_mot_de_passe:$("#ancien_mot_de_passe").val(),nouveau_mot_de_passe:$("#nouveau_mot_de_passe").val(),password_again:$("#password_again").val() },function (data) {
                $(".print-success-msg").find("ul").html('');
                $(".print-error-msg").find("ul").html('');
                $(".print-success-msg").css('display', 'none');
                $(".print-error-msg").css('display', 'none');
                if(data.success)
                    printSuccessMsg(data)
                else
                    printErrorMsg(data);

            });
        });
        function printErrorMsg (msg) {

             if (msg.indexOf("html")>=0) {
                window.location.href='{{url('/')}}';
                return 0;
            }
            if(msg)
            $(".print-error-msg").css('display', 'block');

            $.each(msg, function (key, value) {
                $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
            });

        }
        function printSuccessMsg (msg) {

            if(msg)
                $(".print-success-msg").css('display', 'block');

            $.each(msg, function (key, value) {
                $(".print-success-msg").find("ul").append('<li>' + value + '</li>');
            });

        }
    });


    function export_to_excel(idTable)
    {

        var tab_text="<table ><tr >";
        var textRange; var j=0;
        tab = document.getElementById(idTable); // id of table

        for(j = 0 ; j < (tab.rows.length) ; j++)
        {
            tab_text=tab_text+tab.rows[j].innerHTML+"</tr>";
            //tab_text=tab_text+"</tr>";
        }

        tab_text=tab_text+"</table>";
        tab_text= tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
        tab_text= tab_text.replace(/<img[^>]*>/gi,""); // remove if u want images in your table
        tab_text= tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input paras
        tab_text= tab_text.replace(/<a[^>]*>|<\/a>/gi, "");
        tab_text= tab_text.replace(/<div[^>]*>|<\/div>/gi, "");
        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
        {
            txtArea1.document.open("txt/html","replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa=txtArea1.document.execCommand("SaveAs",true,"dataExport.xls");
        }
        else                 //other browser not tested on IE 11
            sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));


        return (sa);
    }

      function getNniveau(cycle) {
             $('#nniveau').empty();
            $('.loading_icon').css('display','inline');
            $.ajax({
                type: 'GET',
                url: '{{url('/admin/nniveaux/get/')}}/'+cycle,
                timeout: 20000,
                success: function (data) {
                    $.each(data,function (key,val) {
                        $('#nniveau').append('<option value="'+val.id+'">'+val.nom+'</option>');
                    })
                    $('.loading_icon').css('display','none');
                },
                error: function (data) {
                    $('.loading_icon').css('display','none');
                }


            });
        }

</script>
</html>
