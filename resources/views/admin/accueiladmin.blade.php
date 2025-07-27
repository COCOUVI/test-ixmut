@extends('layouts.master')

@section('title', 'Historique des comptes')

@section('page-title', 'Comptes Créés')

@section('content')

    <livewire:admin.performance-chart :scores="$scores" />


@endsection
