@extends('skeleton')
@section('sub-content')
    <div class="col-md-12">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
    <h4>{{trans('eleve.choix_mat')}}</h4>
    <hr>
    <div class="liste-classe col-md-9">
        <ul>
            @foreach($matieres as $m)
                <li><a  data-toggle="modal" data-backdrop="false" href="#choose_seq" onclick="choix_seq({{$m->id}})">{{$m->nom}}</a></li>
            @endforeach
        </ul>
    </div>


    <div class="modal fade" id="choose_seq">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="POST" id="choix_seq_form" action="{{ url('/eleves/notes/create/'.$id) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="id" id="id" value=""/>
                        <fieldset>
                            <div class="form-group">
                                <label for="niveau">{{  trans('eleve.choix_seq')  }} : </label>
                                <select  name="sequence" form="choix_seq_form" class="form-control" >
                                    <option value="1">Séquence 1</option>
                                    <option value="2">Séquence 2</option>
                                    <option value="3">Séquence 3</option>
                                    <option value="4">Séquence 4</option>
                                    <option value="5">Séquence 5</option>
                                    <option value="6">Séquence 6</option>
                                </select>
                            </div>


                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}" autofocus>


                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>
    </div>
    <script>
        function choix_seq(id)
        {
            document.getElementById("id").value = id;
        }
    </script>
@endsection