@extends('skeleton')

@section('sub-content')
    <style>
        .access-header {
            margin-bottom: 15px;
        }
        .access-panel .panel-heading {
            font-weight: 700;
        }
        .access-role-check {
            display: inline-block;
            min-width: 150px;
            margin: 2px 8px 2px 0;
            font-weight: 400;
        }
        .access-muted {
            color: #777;
            font-size: 12px;
        }
        .access-user-search {
            margin-bottom: 10px;
        }
        .access-users-table select {
            min-width: 170px;
        }
        .access-users-table .access-role-cell {
            min-width: 240px;
        }
        .access-users-table .access-action-cell {
            min-width: 110px;
        }
    </style>

    <div class="col-md-12">
        @if(isset($success))
            <div class="alert alert-success">{{ trans('admin.succes') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="row access-header">
            <div class="col-md-7">
                <h4 style="margin-top: 0;">Gestion des roles et permissions</h4>
                <p class="access-muted">Attribuer les actions autorisees a chaque utilisateur.</p>
            </div>
            <div class="col-md-5">
                <form method="GET" action="{{ url('/access-control') }}">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control" placeholder="Rechercher un utilisateur" value="{{ isset($search_term) ? $search_term : '' }}">
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span> Rechercher</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-default access-panel">
            <div class="panel-heading">Nouveau role</div>
            <div class="panel-body">
                <form method="POST" action="{{ route('access_roles_store') }}" class="form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nom du role</label>
                            <input type="text" name="name" class="form-control" placeholder="Ex: Caissiere" required>
                        </div>
                        <div class="col-md-6">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Responsabilite du role">
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button class="btn btn-success form-control" type="submit"><span class="glyphicon glyphicon-plus"></span> Creer</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="panel panel-default access-panel">
            <div class="panel-heading">Attribuer les roles aux utilisateurs</div>
            <div class="panel-body table-responsive">
                <div class="row access-user-search">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                            <input type="search" id="access-user-live-search" class="form-control" placeholder="Rechercher avant d'activer ou d'affecter une fonction">
                        </div>
                    </div>
                </div>
                <table id="access-users-table" class="table table-bordered table-condensed table-striped access-users-table">
                    <thead>
                    <tr>
                        <th>Utilisateur</th>
                        <th>Email</th>
                        <th>Personnel</th>
                        <th>Fonction</th>
                        <th>Statut</th>
                        <th>Super admin</th>
                        <th>Roles</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <?php
                            $formId = 'access-user-form-'.$user->id;
                            $selectedPersonnel = $user->id_personnel ? $user->id_personnel : '';
                            $selectedFonction = ($selectedPersonnel && isset($personnel_fonctions[$selectedPersonnel])) ? $personnel_fonctions[$selectedPersonnel] : '';
                        ?>
                        <tr>
                            <td>
                                <form id="{{ $formId }}" method="POST" action="{{ route('access_users_roles', $user->id) }}"></form>
                                <input type="hidden" form="{{ $formId }}" name="_token" value="{{ csrf_token() }}">
                                <strong>{{ $user->fullname ? $user->fullname : $user->name }}</strong>
                                <div class="access-muted">{{ $user->name }}</div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <select name="id_personnel" form="{{ $formId }}" class="form-control input-sm">
                                    <option value="">Aucun personnel</option>
                                    @foreach($personnels as $personnel)
                                        <option value="{{ $personnel->id }}" @if($selectedPersonnel == $personnel->id) selected @endif>{{ $personnel->nom }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="id_fonction" form="{{ $formId }}" class="form-control input-sm">
                                    <option value="">Aucune fonction</option>
                                    @foreach($fonctions as $fonction)
                                        <option value="{{ $fonction->id }}" @if($selectedFonction == $fonction->id) selected @endif>{{ $fonction->nom }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center">
                                <label class="checkbox-inline">
                                    <input type="checkbox" form="{{ $formId }}" name="active" value="1" @if($user->active) checked @endif> Actif
                                </label>
                            </td>
                            <td class="text-center">
                                <label class="checkbox-inline">
                                    <input type="checkbox" form="{{ $formId }}" name="is_super_admin" value="1" @if(isset($user->is_super_admin) && $user->is_super_admin) checked @endif> Oui
                                </label>
                            </td>
                            <td class="access-role-cell">
                                @foreach($roles as $role)
                                    <label class="access-role-check">
                                        <input type="checkbox" form="{{ $formId }}" name="roles[]" value="{{ $role->id }}" @if($user->accessRoles->contains($role->id)) checked @endif>
                                        {{ $role->name }}
                                    </label>
                                @endforeach
                                @if(count($roles) == 0)
                                    <span class="access-muted">Aucun role cree.</span>
                                @endif
                            </td>
                            <td class="access-action-cell">
                                <button class="btn btn-primary btn-xs" form="{{ $formId }}" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> Enregistrer</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel panel-default access-panel">
            <div class="panel-heading">Permissions par role</div>
            <div class="panel-body table-responsive">
                <table class="table table-bordered table-condensed">
                    <thead>
                    <tr>
                        <th>Role</th>
                        <th>Description</th>
                        <th>Permissions activees</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td><strong>{{ $role->name }}</strong></td>
                            <td>{{ $role->description ? $role->description : 'Aucune description' }}</td>
                            <td>{{ $role->actions->count() }}</td>
                            <td>
                                <a href="{{ route('access_roles_permissions_edit', $role->id) }}" class="btn btn-primary btn-xs">
                                    <span class="glyphicon glyphicon-lock"></span> Gerer les permissions
                                </a>
                                <a href="{{ route('access_roles_delete', $role->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Supprimer ce role ?')">
                                    <span class="glyphicon glyphicon-trash"></span> Supprimer
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center access-muted">Cree d'abord un role pour attribuer des permissions.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(function () {
            var $table = $('#access-users-table');
            var $search = $('#access-user-live-search');

            if ($.fn.DataTable) {
                var accessUsersTable = $table.DataTable({
                    dom: 'rtip',
                    pageLength: 25,
                    order: [[0, 'asc']],
                    language: {
                        zeroRecords: 'Aucun utilisateur trouve',
                        info: 'Affichage _START_ a _END_ sur _TOTAL_ utilisateurs',
                        infoEmpty: 'Aucun utilisateur',
                        paginate: {
                            previous: 'Precedent',
                            next: 'Suivant'
                        }
                    }
                });

                $search.on('keyup change', function () {
                    accessUsersTable.search(this.value).draw();
                });
            }
            else {
                $search.on('keyup change', function () {
                    var term = $(this).val().toLowerCase();
                    $table.find('tbody tr').each(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(term) !== -1);
                    });
                });
            }
        });
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{ url('admin') }}">@lang('admin.dashboard')</a></strong></li>
    <li class="active"><strong>{{ $titre }}</strong></li>
@endsection
