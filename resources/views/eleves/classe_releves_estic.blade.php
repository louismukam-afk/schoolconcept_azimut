@extends('skeleton')
@section('sub-content')
    <h4>{{trans('eleve.choix_classe')}}</h4>
    <hr>
    <div class="liste-classe col-md-12">
        <ul>


            @foreach($niveaux as $n)
                <div class="col-md-6">
                    <h4 class="page-header"> {{$n->getNom()}}</h4>

                    @foreach($classes as $c)
                        <div class="col-md-12">
                            @if($c->id_niveau==$n->id)
                                <li class="col-md-12"><a class="special" data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="choose_level({{$c->id}}, '{{URL::to('/eleves/releve_estic/print/')}}', '')">{{$c->getNom()}}</a></li>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach



        </ul>
    </div>




@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>

    <li class="active"><strong>@lang('admin.choix_classe')</strong></li>
@endsection