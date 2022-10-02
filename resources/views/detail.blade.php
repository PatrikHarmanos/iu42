@extends('layouts.app')

@section('content')
    <section class="detail-container">
        <h2 class="detail-container-heading">Detail obce</h2>
        <div class="detail-card">
            <div class="detail-card-left">
                <div class="me-3">
                    <p class="detail-card-left-text"><strong>Meno starostu:</strong></p>
                    <p class="detail-card-left-text"><strong>Adresa obecného úradu:</strong></p>
                    <p class="detail-card-left-text"><strong>Telefón:</strong></p>
                    <p class="detail-card-left-text"><strong>Fax:</strong></p>
                    <p class="detail-card-left-text"><strong>Email:</strong></p>
                    <p class="detail-card-left-text"><strong>Web:</strong></p>
                    <p class="detail-card-left-text"><strong>Zemepisné súradnice:</strong></p>
                </div>
                <div>
                    <p class="detail-card-left-text">{{$town->mayor_name}}</p>
                    <p class="detail-card-left-text">{{$town->address}}</p>
                    <p class="detail-card-left-text">{{$town->number}}</p>
                    <p class="detail-card-left-text">{{$town->fax}}</p>
                    <p class="detail-card-left-text">{{$town->email}}</p>
                    <p class="detail-card-left-text">{{$town->web}}</p>
                    <p class="detail-card-left-text">{{$town->latitude}}, {{$town->longitude}}</p>
                </div>
            </div>
            <div class="detail-card-right">
                <img src="{{$town->img_path}}" class="mb-4">
                <h3 class="detail-card-right-town text-primary text-center">{{ $town->name }}</h3>
            </div>
        </div>
    </section>
@endsection
