@extends('skeleton')
@section('sub-content')
    <div class="col-md-6">
        @include('perso.functions')
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

        <table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <th>#</th>
            <th>Role</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($roles as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong >
                            {{getRole($c->value)}}
                        </strong></td>
                    <td><a href="{{route('delete_role',$c->id)}}"   class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.ajouter').' un role'  }}</button>

        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="role-form" action="{{ route('store_role') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <fieldset>

                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="role">Role : </label>
                                        <select  name="role" form="role-form" class="form-control">
                                            @if($validated)
                                                <option value="1">Super Admin</option>
                                            @endif
                                            <option value="2">{{trans('admin.editeur')}}</option>
                                            <option value="16">{{trans('admin.g_comptabilite')}}</option>
                                            <option value="17">{{trans('admin.g_personnel')}}</option>
                                            <option value="18">{{trans('admin.g_notes')}}</option>
                                            <option value="18.5">{{trans('admin.enseignant')}}</option>
                                            <option value="19">{{trans('admin.g_inscriptions')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

    </div>

@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li><strong><a href="{{url('utilisateurs')}}">@lang('admin.utilisateurs')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection