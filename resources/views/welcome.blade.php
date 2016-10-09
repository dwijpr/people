@extends('layouts.app')

@section('style')
<style>
    body {
        padding: 0;
    }
    .full {
        position: absolute;
        top: -50px;
        width: 100%;
        height: 100%;
    }
    .title {
        padding-top: 50px;
        font-size: 84px;
        font-weight: 100;
    }
</style>
@endsection

@section('content')
<table class="full">
    <tr>
        <td class="text-center title">
            {{ config('app.name') }}
        </td>
    </tr>
</table>
@endsection
