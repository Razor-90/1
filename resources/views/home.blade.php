@extends('layouts')

@section('content')
    <div class="container" style="margin-top: 50px;">
        <div class="row">
            <h1>Личный кабинет пользователя {{ auth()->user()->name }}</h1>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <div class="col">
                <form method="post" action="{{ route('user.checkPromoCode') }}">
                    @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Введите промокод" aria-label="Введите промокод"  id="promo_code" name="promo_code"  aria-describedby="button-addon2" required>
                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Проверить</button>
                </div>
                </form>
            </div>
            <div class="col">
                <h2>Мои промокоды:</h2>
                <ol>
                    @foreach ($promoCodes as $promoCode)
                        <li>{{ $promoCode->code }} | {{ $promoCode->promo_code_selection_date ?? 'Not Selected' }}</li>
                    @endforeach
                </ol>
            </div>

            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Выйти</button>
            </form>
        </div>
    </div>







@endsection
