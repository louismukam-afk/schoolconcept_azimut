@extends('skeleton')

@section('sub-content')
    <style>
        .permission-section {
            background: #fff8e1;
            border: 1px solid #f0dfaa;
            border-radius: 6px;
            padding: 14px;
            margin-bottom: 15px;
        }
        .permission-section h5 {
            color: #111;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .permission-item {
            display: block;
            padding: 7px 8px;
            margin-bottom: 6px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.65);
            min-height: 44px;
        }
        .permission-action {
            margin-left: 8px;
            font-weight: 700;
            font-size: 12px;
        }
        .permission-meta {
            display: block;
            margin-left: 25px;
            color: #777;
            font-size: 11px;
        }
        .permission-help {
            display: block;
            margin-left: 25px;
            color: #555;
            font-size: 12px;
        }
    </style>

    <div class="col-md-12">
        @if(isset($success))
            <div class="alert alert-success">
                {{ trans('admin.succes') }}
                @if(isset($sync_count))
                    <br>{{ $sync_count }} actions disponibles ont ete synchronisees avec les routes et le catalogue.
                @endif
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-8">
                <h4 style="margin-top: 0;">Permissions du role : {{ $role->name }}</h4>
                <p class="text-muted">{{ $role->description ? $role->description : 'Selectionner les actions autorisees pour ce role.' }}</p>
            </div>
            <div class="col-md-4 text-right">
                <form method="POST" action="{{ route('access_roles_actions_sync', $role->id) }}" style="display:inline-block; margin-right: 5px;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-refresh"></span> Synchroniser les actions</button>
                </form>
                <a href="{{ url('/access-control') }}" class="btn btn-default"><span class="glyphicon glyphicon-arrow-left"></span> Retour</a>
            </div>
        </div>

        <form method="POST" action="{{ route('access_roles_permissions_update', $role->id) }}" id="role-permissions-form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Rechercher une action</label>
                            <input type="text" class="form-control" id="permission-search" placeholder="Ex: inscription, cycle, tutelle, note, generer pv">
                        </div>
                        <div class="col-md-6 text-right">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-default permission-toggle" data-action="check-all">Tout cocher</button>
                            <button type="button" class="btn btn-default permission-toggle" data-action="uncheck-all">Tout decocher</button>
                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($sections as $section => $items)
                <div class="permission-section">
                    <h5>{{ $section }}</h5>
                    <div class="row permission-list">
                        @foreach($items as $item)
                            <label class="col-md-6 permission-item" data-search="{{ strtolower($item['keywords']) }}">
                                <input type="checkbox" name="actions[]" value="{{ $item['name'] }}" @if(in_array($item['name'], $selected_actions)) checked @endif>
                                <span class="permission-action {{ $item['danger'] ? 'text-danger' : 'text-primary' }}">
                                    {{ $item['label'] }}
                                </span>
                                <span class="permission-help">{{ isset($item['keywords']) ? $item['keywords'] : '' }}</span>
                                <span class="permission-meta">{{ $item['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="text-right" style="margin-bottom: 20px;">
                <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span> Enregistrer les permissions</button>
            </div>
        </form>

        <script>
            (function () {
                var search = document.getElementById('permission-search');
                if (search) {
                    search.addEventListener('input', function () {
                        var term = this.value.toLowerCase();
                        var items = document.querySelectorAll('.permission-item');
                        for (var i = 0; i < items.length; i++) {
                            var text = (items[i].innerText + ' ' + (items[i].getAttribute('data-search') || '')).toLowerCase();
                            items[i].style.display = text.indexOf(term) >= 0 ? '' : 'none';
                        }
                    });
                }

                var buttons = document.querySelectorAll('.permission-toggle');
                for (var b = 0; b < buttons.length; b++) {
                    buttons[b].addEventListener('click', function () {
                        var checked = this.getAttribute('data-action') === 'check-all';
                        var items = document.querySelectorAll('.permission-item');
                        for (var i = 0; i < items.length; i++) {
                            if (items[i].style.display !== 'none') {
                                items[i].querySelector('input[type="checkbox"]').checked = checked;
                            }
                        }
                    });
                }
            })();
        </script>
    </div>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{ url('admin') }}">@lang('admin.dashboard')</a></strong></li>
    <li><strong><a href="{{ url('/access-control') }}">Roles et permissions</a></strong></li>
    <li class="active"><strong>{{ $role->name }}</strong></li>
@endsection
