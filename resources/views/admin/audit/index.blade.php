@extends('skeleton')

@section('sub-content')
<div class="col-md-12">
    <div class="btn-group" style="margin-bottom:10px">
        <a class="btn btn-default" href="{{ url('/admin') }}"><span class="fa fa-arrow-left"></span> Tableau de bord</a>
        <a class="btn btn-primary" href="{{ url('/access-control') }}"><span class="fa fa-lock"></span> Roles et permissions</a>
    </div>

    <h3>Journal des operations</h3>
    <p class="text-muted">Audit des actions effectuees par les utilisateurs.</p>

    <form method="GET" action="{{ url('/audit') }}" class="panel panel-default">
        <div class="panel-body">
            <div class="row">
                <div class="col-md-2">
                    <label>Date debut</label>
                    <input type="date" name="date_debut" value="{{ $date_debut }}" class="form-control">
                </div>
                <div class="col-md-2">
                    <label>Date fin</label>
                    <input type="date" name="date_fin" value="{{ $date_fin }}" class="form-control">
                </div>
                <div class="col-md-3">
                    <label>Utilisateur</label>
                    <select name="id_user" class="form-control">
                        <option value="">Tous</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @if($id_user == $user->id) selected @endif>
                                {{ $user->fullname ? $user->fullname : $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Methode</label>
                    <select name="method" class="form-control">
                        <option value="">Toutes</option>
                        @foreach($methods as $methodItem)
                            <option value="{{ $methodItem }}" @if($method == $methodItem) selected @endif>{{ $methodItem }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Route / action</label>
                    <input type="text" name="route_name" value="{{ $route_name }}" class="form-control" placeholder="Ex: inscription, releve, discipline">
                </div>
            </div>
            <div class="row" style="margin-top:10px">
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-search"></span> Filtrer</button>
                    <a href="{{ url('/audit') }}" class="btn btn-default"><span class="glyphicon glyphicon-refresh"></span> Reset</a>
                </div>
            </div>
        </div>
    </form>

    <div class="row" style="margin-bottom:10px">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-search"></span></span>
                <input type="search" id="audit-live-search" class="form-control" placeholder="Rechercher dans les resultats affiches">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table id="audit-table" class="table table-bordered table-hover table-condensed">
            <thead>
            <tr>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Methode</th>
                <th>Route / action</th>
                <th>URL</th>
                <th>Statut</th>
                <th>Donnees</th>
                <th>IP</th>
            </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at }}</td>
                    <td>{{ $log->user_name ? $log->user_name : ($log->user ? ($log->user->fullname ? $log->user->fullname : $log->user->name) : '-') }}</td>
                    <td><span class="label label-info">{{ $log->method }}</span></td>
                    <td>{{ $log->route_name ? $log->route_name : '-' }}</td>
                    <td>{{ $log->uri }}</td>
                    <td>{{ $log->status_code }}</td>
                    <td>
                        <small>{{ json_encode($log->payload_array) }}</small>
                    </td>
                    <td>{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Aucune operation trouvee.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="paginate" style="text-align:center;">{!! str_replace('/?', '?', $logs->render()) !!}</div>
</div>

<script>
    $(function () {
        var $table = $('#audit-table');
        var $search = $('#audit-live-search');

        if ($.fn.DataTable) {
            var auditTable = $table.DataTable({
                dom: 'Bfrtip',
                paging: false,
                ordering: true,
                info: false,
                order: [[0, 'desc']],
                buttons: [
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        title: 'Journal des operations'
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        title: 'Journal des operations'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> Imprimer',
                        title: 'Journal des operations'
                    }
                ]
            });

            $search.on('keyup change', function () {
                auditTable.search(this.value).draw();
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
