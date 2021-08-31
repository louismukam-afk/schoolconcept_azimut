@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}

        </div>

    @endif

    @if(isset($custom_error))

        <div class="alert alert-danger">
            {{  $custom_error  }}
        </div>

    @endif

    <p style="font-size: 18px"> <strong>Matricule : {{$e->matricule}} </strong></p>
    <p style="font-size: 18px"> <strong>{{trans('admin.nom')}} : {{$e->nom.' '.$e->prenom}} </strong></p>
    @if(isset($inscription))
        <p style="font-size: 18px"> <strong>{{trans('admin.specialites')}} : {{$inscription->classe->nom}} </strong></p>

    @else
        <div class="alert alert-warning">
            <h4>{{trans('inscription.pas_inscrit')}}</h4>
        </div>
    @endif

    <div class="col-md-12">
        <form class="form-horizontal" id="add_inscription_form" method="post" action="{{URL::to('/inscriptions/payement/store/'.$e->id)}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="id_eleve" value="{{$e->id}}">
            <input type="hidden" name="montant" id="montant" required>

            <div class="">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date">@lang('admin.date')</label>
                            <input type="date" name="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px 10px">
                            <label for="lettre">Montant en lettre : </label>
                            <input type="text" name="lettre" id="lettre" placeholder="EX: dix mille fcfa" class="form-control"  value="{{old('lettre')}}" >

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px 10px">
                            <label for="motif">{{trans('admin.type_p')}} : </label>
                            <select name="type" form="add_inscription_form" class="form-control" onchange="changeTypeAction()" id="typeSelect">
                                <option value="0" >@lang('admin.schoolfees')</option>
                                <option value="-1"  @if(isset($inscription)) id="{{$inscription->id}}" @endif>@lang('admin.inscription')</option>
                                @foreach($frais as $f)
                                    <option value="{{$f->id}}" montant="{{$f->montant}}">{{$f->nom}}</option>

                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group" style="padding: 5px 10px">
                            <label for="motif">{{trans('admin.details')}} : </label>
                            <input type="text" name="motif" id="motif" placeholder="EX: 1ère tranche" class="form-control"  value="{{old('motif')}}" >

                        </div>

                    </div>
                </div>


            </div>
        </form>


        <div class="row">

            <div class="col-md-6 hidden" id="amountAlert" >

                <div>
                    <div class="alert alert-info">
                        <p class="text-center" style="font-size: 28px;font-weight: 600"><span id="amount">10000</span> @lang('inscription.devise')</p>
                    </div>
                </div>
            </div>

            <div class="col-md-6" id="amountInput" >
                <div >
                    <div class="form-group">
                        <label for="montant">{{trans('inscription.montant')}} : </label>
                        <div class="input-group">
                            <input type="number" name="montant2" id="montant2" placeholder="EX: 15000" class="form-control" required value="{{old('montant2')}}" onkeyup="fillMontant()">
                            <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>



        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <input type="submit" class="btn btn-success form-control" onclick="submitForm()" value="{{trans('admin.confirmer')}}" >
            </div>
        </div>

    </div>


    <script>
        function changeTypeAction() {
            ts=$('#typeSelect')[0];
            montant=ts.options[ts.selectedIndex].getAttribute("montant");
            id=ts.options[ts.selectedIndex].getAttribute("id");
            aa=$('#amountAlert');
            ai=$('#amountInput');
            mo=$('#montant');
            mo2=$('#montant2');
            a=$('#amount');

            if(montant){
                mo.val(montant);
                mo2.val(montant);
                a.html(montant);
                ai.val('');

                //ai.addClass('hidden');
                aa.removeClass('hidden');

            }
            else if(id){
                getLeftInscription(id);
            }
            else {
                mo.val('');
                aa.addClass('hidden');
                //ai.removeClass('hidden');
            }
        }

        function getLeftInscription(inscription) {
            aa=$('#amountAlert');
            mo=$('#montant');
            //ai=$('#amountInput');
            a=$('#amount');
            $.ajax({
                url:'{{route('get_left_inscription')}}',
                type:'GET',
                data:{
                    inscription:inscription
                },
                success:function (data) {
                    mo.val(data.montant);
                    mo2.val(data.montant);
                    a.html('{{trans('admin.reste')}}: '+data.montant);
                    aa.removeClass('hidden');



                },
                error:function () {
                    mo.val();
                    mo2.val();
                    aa.addClass('hidden');

                }

            })

        }

        function fillMontant(){
            mo2=$('#montant2');
            mo=$('#montant');
            console.log(mo2.val());
            mo.val(mo2.val());
        }

        function submitForm() {
            form=$('#add_inscription_form');
            form.submit();
        }
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li><strong><a href="{{url('inscriptions/payement')}}">@lang('inscription.title_search')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection