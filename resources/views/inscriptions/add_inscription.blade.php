@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>
        @if(!isset($inscription_edition))
            <div class="col-md-12">
                <a  class="btn btn-warning btn-lg" href="{{URL::to('/inscriptions/print/'.$success)}}"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.print_receipe')}}</a>

            </div>
        @endif

    @endif

    @if(isset($custom_error))

        <div class="alert alert-danger">
            {{  $custom_error  }}
        </div>

    @endif

    @if(isset($old_record))
        <p style="font-size: 18px"> <strong>Matricule : {{$e->matricule}} </strong></p>
        <p style="font-size: 18px"> <strong>{{trans('admin.nom')}} : {{$e->nom.' '.$e->prenom}} </strong></p>

        <div class="col-md-8">
            <form class="form-horizontal" id="edit_inscription_form" method="post" action="{{URL::to('/inscriptions/update/')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id_eleve" value="{{$e->id}}">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>  Cycle: </label>
                        <select form="edit_inscription_form" id="edit_cycle"  name="cycle" class="form-control">
                            @foreach($cycles as $c)
                                <option value="{{$c->id}}" @if($inscription->id_cycle==$c->id) selected @endif>{{$c->nom}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label>@lang('admin.specialites')  : </label>

                        <select form="edit_inscription_form" id="edit_classe" name="classe" class="form-control">
                            <?php $n=0;$i=0; ?>

                            @foreach($classes as $c)
                                @if($i=0)
                                    <optgroup label="{{(App::getLocale()=='en')?$c->niveau->nom_anglais:$c->niveau->nom}}">
                                        <?php  $i++; ?>
                                        @endif
                                        @if($c->id_niveau!=$n)
                                            <?php $n=$c->id_niveau ?>
                                    </optgroup>
                                    <optgroup label="{{(App::getLocale()=='en')?$c->niveau->nom_anglais:$c->niveau->nom}}">
                                        @endif
                                        <option value="{{$c->id}}" @if($inscription->id_classe==$c->id) selected @endif>{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</option>
                                        @endforeach
                                    </optgroup>
                        </select>


                    </div>
                    <div class="form-group">
                        <label>  @lang('admin.nniveau') </label>
                        <select form="edit_inscription_form"  name="nniveau" class="form-control">
                            @foreach($nniveaux as $nn)
                                <option value="{{$nn->id}}" @if($inscription->id_nniveau==$nn->id) selected @endif>{{$nn->nom}}</option>
                            @endforeach
                        </select>

                    </div>
                        <div class="form-group">
                            <label>{{trans('admin.matricule')}} : (@lang('admin.no_matricule'))</label>
                            <input type="text" name="matricule"  class="form-control"  value="{{old('matricule')}}">

                        </div>

                    <input type="submit" class="btn btn-success form-control" value="{{trans('admin.modifier')}}" >
                </div>
            </form>

        </div>

    @elseif(isset($new_record))
        <p style="font-size: 18px"> <strong>Matricule : {{$e->matricule}} </strong></p>
        <p style="font-size: 18px"> <strong>{{trans('admin.nom')}} : {{$e->nom.' '.$e->prenom}} </strong></p>

        <div class="col-md-8">
            <form class="form-horizontal" id="add_inscription_form" method="post" action="{{URL::to('/inscriptions/store/')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id_eleve" value="{{$e->id}}">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>  Cycle: </label>
                        <select form="add_inscription_form" id="cycle" name="cycle" class="form-control" onchange="getScolarite()">
                            <option value=""> -- @lang('admin.choose_cycle') --</option>

                        @foreach($cycles as $c)
                                <option value="{{$c->id}}" @if(old('cycle')==$c->id) selected @endif>{{$c->nom}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label>@lang('admin.specialites')  : </label>

                        <select form="add_inscription_form" id="classe" name="classe" class="form-control" onchange="getScolarite()">
                            <?php $n=0;$i=0; ?>
                                <option value=""> -- @lang('admin.choix_classe') --</option>
                            @foreach($classes as $c)
                                    @if($i=0)
                                        <optgroup label="<{{(App::getLocale()=='en')?$c->niveau->nom_anglais:$c->niveau->nom}}">
                                            <?php  $i++; ?>
                                    @endif
                                @if($c->id_niveau!=$n)
                                    <?php $n=$c->id_niveau ?>
                                    </optgroup>
                                    <optgroup label="{{(App::getLocale()=='en')?$c->niveau->nom_anglais:$c->niveau->nom}}">
                                        @endif
                                <option id="{{$c->niveau->id}}" value="{{$c->id}}" @if(old('classe')==$c->id) selected @endif>{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</option>
                            @endforeach
                                    </optgroup>
                        </select>

                    </div>
                    <div class="form-group">
                        <label>  @lang('admin.nniveau') </label>
                        <select form="add_inscription_form"  name="nniveau" class="form-control">
                            @foreach($nniveaux as $nn)
                                <option value="{{$nn->id}}"  @if(old('nniveau')==$nn->id) selected @endif>{{$nn->nom}}</option>
                            @endforeach
                        </select>

                    </div>


                </div>
                <div class="col-md-6" style="padding-left: 30px">
                    @if($e->matricule==null)
                        <div class="form-group">
                            <label>{{trans('admin.matricule')}} : (@lang('admin.no_matricule'))</label>
                            <input type="text" name="matricule"  class="form-control"  value="{{old('matricule')}}">

                        </div>
                    @endif
                    <div class="form-group">
                        <div id="show_montant" class="alert alert-info hidden"  >
                            <span  style="font-size: 18px">

                                <span id="show_montant_value">25000</span>
                                @lang('inscription.devise')
                            </span>
                        </div>

                        <div id="montant-group" class="hidden">
                            <label for="montant">{{trans('inscription.montant')}} : </label>
                            <div class="input-group">
                                <input type="number" name="montant" id="montant" value=""  class="form-control disabled" required readonly>
                                <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                            </div>
                        </div>


                    </div>
                </div>
                <input type="submit" class="btn btn-success form-control" value="{{trans('inscription.inscrire')}}" >

            </form>

        </div>

    @else
        <div class="col-md-8">
            <form class="form-horizontal" id="add_inscription_form" method="get" action="{{URL::to('/inscriptions/find/')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id_eleve" value="{{session('inscription_new_record')}}">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="search">{{  trans('inscription.recherche')  }} : </label>
                        <div class="input-group">
                            <input type="search" name="search" placeholder="{{trans('inscription.nom')}} ou Matricule" class="form-control" autofocus>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary" ><span class="fa fa-search"> </span>{{trans('inscription.search')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <div class="col-md-10">
            @if(session('search_result'))
                <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
                    <thead>
                    <caption style="font-size: 22px">{{  trans('inscription.search_result')  }}</caption>
                    <th>#</th>
                    <th>Matricule</th>
                    <th>{{  trans('admin.nom')  }}</th>
                    <th>{{  trans('inscription.date_naiss')  }}</th>
                    <th>{{  trans('inscription.lieu_naiss')  }}</th>
                    <th>{{  trans('inscription.sexe')  }}</th>

                    </thead>
                    <tbody>
                    <?php $i=1; ?>
                    @foreach($eleves as $e)
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$e->matricule}}</td>
                            <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                            <td>{{$e->date_naiss}}</td>
                            <td>{{$e->lieu_naiss}}</td>
                            <td>{{$e->sexe}}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li><a style="color: white" href="{{ URL::to('/inscriptions/create/').'/'.$e->id }}"   class="btn-success"><span class="glyphicon glyphicon-cloud-upload"></span> {{  trans('inscription.inscrire')  }}</a></li>
                                        <li><a style="color: white" class="btn-primary"   href="{{ URL::to('/inscriptions/print-certificate/').'/'.$e->id }}"><span class="fa fa-file-text-o"></span> {{  trans('admin.certificat_scolarite')  }}</a></li>
                                    </ul>
                                </div>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $eleves->render()) ); ?></div>

                {{Session::forget('search_result')}}
            @endif
        </div>
    @endif

    <script>
        function getScolarite() {

            var cycle=$('#cycle').val();
            var el=$('#classe')[0];
            var niveau= el.options[el.selectedIndex].id;
            var token='{{csrf_token()}}';

            if(cycle.length>0 && niveau.length>0)
            {
                $.ajax({
                    url:'{{route('get_scolarite')}}',
                    type:'POST',
                    dataType:'html',
                    data:'cycle='+cycle+'&niveau='+niveau+'&_token='+token,
                    complete: function (res,stat) {
                        var obj=JSON.parse(res.responseText);
                        if(obj.inscription<=0)
                        {
                            $('#show_montant').addClass('hidden');
                            $('#montant-group').removeClass('hidden');
                            var montant=$('#montant').removeClass('disabled');
                            montant.attr('readonly',false);
                            montant.val('');

                        }
                        else {
                            $('#show_montant').removeClass('hidden');
                            $('#show_montant_value').html(obj.inscription);
                            $('#montant-group').addClass('hidden');
                            var montant=$('#montant').addClass('disabled');
                            montant.val(obj.inscription);



                        }


                    }
                })
            }
            else {
                $('#show_montant').addClass('hidden');
                $('#montant-group').addClass('hidden');
                $('#montant').val('');

            }


        }
    </script>
@endsection