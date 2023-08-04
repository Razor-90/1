@extends('layouts.admin')

@section('content')
    <h1>Промокоды</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Код</th>
            <th>ФИО</th>
            <th>Телефон</th>
            <th>Город</th>
            <th>Дата</th>

        </tr>
        </thead>
        <tbody>
        @foreach ($usedPromoCodes  as $promoCode)
            <tr>
                <td>{{ $promoCode->id }}</td>
                <td>{{ $promoCode->code }}</td>
                <td>{{ $promoCode->user->name ?? 'N/A' }}</td> <!-- Access the associated user's name -->
                <td>{{ $promoCode->user->phone ?? 'N/A' }}</td> <!-- Access the associated user's name -->
                <td>{{ $promoCode->user->city ?? 'N/A' }}</td>
                <td>{{ $promoCode->promo_code_selection_date ?? 'Not Selected' }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>

    <form action="{{ route('promo_codes.upload') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file">
        <button type="submit">Загрузить промокоды</button>
    </form>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection
