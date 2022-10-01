@extends('layouts.app')

@section('content')
    <section class="main-container">
        <h1 class="main-container-title">Vyhľadať v databáze obcí</h1>
        <div class="input-group-lg mb-3 mb-lg-0 me-lg-3" style="min-width: 400px;">
            <input id="my-input" type="text" list="datalistOptions" class="form-control mt-3" style="border-right: none;" placeholder="Zadajte názov">
            <datalist id="datalistOptions">
                @foreach($towns as $town)
                    <option data-value="{{$town->id}}" value="{{$town->name}}">
                @endforeach
            </datalist>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.getElementById("my-input").addEventListener("change", (event) => {
            let shownVal = document.getElementById("my-input").value;
            let value2send = document.querySelector("#datalistOptions option[value='"+shownVal+"']").dataset.value;
            if (!isNaN(Number(value2send))) {
                window.location = '/' + String(value2send);
                document.getElementById("my-input").value = '';
            }
        });
    </script>
@endpush
