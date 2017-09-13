<html>
    <head>
        <title>SchoolConcept</title>
        <link rel="shortcut icon" href="{{URL::asset('favicon.ico')}}" type="image/x-icon">
        <!-- BOOTSTRAP STYLES-->
        <link href="{{ URL::asset('css/bootstrap.css')  }}" rel="stylesheet" />
        <!-- FONTAWESOME STYLES-->
        <link href="{{ URL::asset('css/font-awesome.css')  }}" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
        <link href="{{ URL::asset('css/custom.css')  }}" rel="stylesheet" media="screen"/>
    </head>
    <body  style="background: url('{{URL::asset('img/back-school1.jpg')}}');background-size: cover">
    <div style="background-color: rgba(0,0,0,0.3)">
        <div class="container-fluid">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        <div class="page-header">
                            <h2>SchoolConcept - Configuration</h2>
                        </div>
                        <div class="">
                            <h4>{{trans('admin.premier_msg')}}</h4>
                        </div>
                    </div>
                </div>
                <div class="p-body" style="background-color: transparent;color: #c9e2b3">

                    <div class="col-md-12">
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


                    <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                        @if(!session('start_part_2'))
                        <form accept-charset="UTF-8" role="form" method="POST" id="start_form" action="{{url('/start/store')}}" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <fieldset>
                                <div class="form-group">
                                    <label>{{  trans('admin.nom_e')  }} : <span class="required">*</span></label>
                                    <input class="form-control"  name="nom" type="text"  value="{{ old('nom') }}"  autofocus required>

                                </div>

                                <div class="form-group">
                                    <label for="nom">{{  trans('admin.initiales_e')  }} : <span class="required">*</span></label>
                                    <input class="form-control"  name="initiales" type="text"  value="{{ old('initiales') }}" required >

                                </div>
                                <div class="form-group">
                                    <label>{{  trans('admin.adresse_e')  }} : <span class="required">*</span></label>
                                    <input class="form-control"  name="adresse"   value="{{ old('adresse') }}" required>

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.numeros_e')  }} : <span class="required">*</span></label>
                                    <input class="form-control"  name="numero"   value="{{ old('numero') }}" required>

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.year')  }} : <span class="required">*</span></label>
                                    <input class="form-control"  name="annee_scolaire"   value="{{ old('annee_scolaire') }}" placeholder="EX: 2017-2018" required>

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.site_web_e')  }} : </label>
                                    <input class="form-control"  name="web"   value="{{ old('web') }}">

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.email_e')  }} : </label>
                                    <input class="form-control"  name="email"   value="{{ old('email') }}">

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.logo_e')  }} : </label>
                                    <input type="file" class="form-control btn btn-warning"  name="logo"   value="{{ old('logo') }}">

                                </div>
                                <input class="btn  btn-success pull-right" type="submit" value="{{  trans('admin.suivant')  }} >>">


                            </fieldset>
                        </form>
                        @else
                            {{\Illuminate\Support\Facades\Session::forget('start_part_2')}}
                            {{session(['superAdmin'=>true])}}
                            <form class="form-signin" method="post" action="{{ url('/auth/register') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                <span id="reauth-email" class="reauth-email"></span>
                                <div class="form-group">
                                    <label class="control-label">{{trans('admin.username')}}</label>
                                    <div >
                                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">{{trans('admin.fullname')}}</label>
                                    <div >
                                        <input type="text" class="form-control" name="fullname" value="{{ old('fullname') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label">{{trans('admin.password')}}</label>
                                    <div >
                                        <input type="password" class="form-control" name="password">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class=" control-label">{{trans('admin.password_again')}}</label>
                                    <div >
                                        <input type="password" class="form-control" name="password_confirmation">
                                    </div>
                                </div>
                                <input class="btn  btn-success pull-right" type="submit" value="{{  trans('admin.terminer_config')  }} >>">

                            </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>

    </div>





    </body>
</html>