@extends('skeleton')
@section('sub-content')
    <div class="col-md-6">
        @if(isset($success))
            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif






            <?php $i=1; ?>
            @foreach($personnel as $p)


                    <strong ></strong>
            <UL>
               <LI> <a href="{{ URL::to('/personnel/dispense/').'/'.$p->id }}">{{$p->nom}}</a></LI>

                </UL>

            @endforeach


    </div>

@endsection