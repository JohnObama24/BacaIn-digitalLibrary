@extends('layouts.member')

@section('content')
    <x-hero />
    <x-feature />
    <x-book-carousel :books="$books" />
    <x-book-popular :popularBooks="$popularBooks" />
@endsection
