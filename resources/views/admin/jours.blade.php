@extends('skeleton')

@section('sub-content')

    <div class="col-md-6">

        @if(isset($success))
            <div class="alert alert-success">
                Succès
            </div>
        @endif

        @if(isset($error))
            <div class="alert alert-danger">
                {{$error}}
            </div>
        @endif

        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <th>#</th>
            <th>Nom</th>
            <th>Observation</th>
            <th>Actions</th>
            </thead>

            <tbody>
            <?php $i=1; ?>
            @foreach($jours as $j)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$j->nom}}</td>
                    <td>{{$j->observation}}</td>
                    <td>
                        <div class="btn-group">
                            <a data-toggle="modal"
                               href="#edit_jour"
                               onclick="editer_jour({{$j->id}},'{{$j->nom}}','{{$j->observation}}')"
                               class="btn btn-primary btn-xs">
                                <span class="glyphicon glyphicon-edit"></span>
                            </a>

                            <a href="{{url('/admin/jours/destroy/'.$j->id)}}"
                               class="btn btn-danger btn-xs">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>

        <button class="btn btn-primary" data-toggle="modal" href="#add_jour">
            <span class="glyphicon glyphicon-plus"></span> Ajouter jour
        </button>

    </div>

    <!-- MODAL AJOUT -->
    <div class="modal fade" id="add_jour">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4>Ajouter Jour</h4>
                </div>

                <div class="modal-body">

                    <form method="POST" action="{{url('/admin/jours/store')}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Observation</label>
                            <input type="text" name="observation" class="form-control">
                        </div>

                        <input class="btn btn-success" type="submit" value="Confirmer">

                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="edit_jour">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4>Modifier Jour</h4>
                </div>

                <div class="modal-body">

                    <form method="POST" action="{{url('/admin/jours/update')}}">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <input type="hidden" id="edit_id_jour" name="id">

                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" id="edit_nom_jour" name="nom" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Observation</label>
                            <input type="text" id="edit_obs_jour" name="observation" class="form-control">
                        </div>

                        <input class="btn btn-success" type="submit" value="Confirmer">

                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function editer_jour(id,nom,observation)
        {
            document.getElementById("edit_id_jour").value = id;
            document.getElementById("edit_nom_jour").value = nom;
            document.getElementById("edit_obs_jour").value = observation;
        }
    </script>

@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">Dashboard</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection
