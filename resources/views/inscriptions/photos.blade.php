@extends('skeleton')
@section('sub-content')
    <style>
        .icon-choose-image
        {
            width: 150px;
            height: 150px;
            border: solid 1px;
            border-radius: 3px;
        }
        .photoClass
        {
            padding: 5px;
        }
        .nom-for-photo
        {
            font-size: 16px;
        }
    </style>

    <div class="add-container col-md-12" style="margin-top: 25px">


        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{URL::route('store_photo')}}" enctype="multipart/form-data">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id_classe" value="{{ $id_classe }}">
            <input type="hidden" name="cycle" value="{{ $id_cycle }}">
            <input type="hidden" name="nniveau" value="{{ $id_nniveau }}">



            <fieldset>
                <?php $i=0; ?>
                @foreach($eleves as $e)
                    <?php $photoSet=false; ?>
                    <div class="col-md-4 photoClass">


                        <div class="form-group">
                            <div id="targetOuter">
                                <div id="targetLayer{{$i}}">
                                    @foreach($photos as $photo)
                                        @if($photo->eleve_id==$e->id)
                                            <img src="{{URL::asset($photo->url)}}"  class="icon-choose-image" />
                                        <?php $photoSet=true;break; ?>
                                        @endif
                                    @endforeach
                                    @if(!$photoSet)
                                            <img src="{{URL::asset('img/avatar.png')}}"  class="icon-choose-image" />
                                        @endif

                                </div>
                                <div  >
                                    <input name="photo[]" id="userImage"  type="file" class=" form-control" onChange="showPreview(this,'targetLayer{{$i}}');" />
                                </div>
                            </div>
                            <label class="nom-for-photo">{{ $e->nom.' '.$e->prenom  }} </label>
                            <input type="hidden" name="eleve[]" value="{{$e->id}}">


                        </div>

                    </div>
                    <?php $i++; ?>
                @endforeach

            </fieldset>
            @if($i!=0)
                <div class="col-md-7">

                    <input type="submit" value="{{trans('admin.confirmer')}}" class="btn btn-success center-block btn-lg">
                </div>
            @else
                <div class="alert alert-info">
                    {{trans('eleve.none')}}
                </div>
            @endif

        </form>
    </div>
    <script>

        function showPreview(objFileInput,elementName) {
            if (objFileInput.files[0]) {
                var fileReader = new FileReader();
                fileReader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                    $("#"+elementName).html('<img src="'+e.target.result+'" width="150px" height="150px" class="icon-choose-image" />');
                    $("#"+elementName).css('opacity','0.8');
                };
                startLoading();
                fileReader.readAsDataURL(objFileInput.files[0]);
                stopLoading();
            }
        }
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection