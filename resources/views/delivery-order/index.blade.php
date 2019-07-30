@extends('base')

@section('content')

<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Ordem de Entrega</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Ordem de Entrega</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="page-body">


  <div class="row">

    <div class="col-xl-12 col-lg-12 filter-bar">
      <nav class="navbar navbar-light bg-faded m-b-30 p-10">
          <ul class="nav navbar-nav">
              <li class="nav-item active">
                  <a class="nav-link" href="#!">Filtros: <span class="sr-only">(current)</span></a>
              </li>
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#!" id="bydate" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-clock-time"></i> Data</a>
                  <div class="dropdown-menu" aria-labelledby="bydate">
                      <a class="dropdown-item" href="?date=recente">Recente</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="?date=hoje">Hoje</a>
                      <a class="dropdown-item" href="?date=ontem">Ontem</a>
                      <a class="dropdown-item" href="?date=semana">Nesta Semana</a>
                      <a class="dropdown-item" href="?date=mes">Neste Mês</a>
                      <a class="dropdown-item" href="?date=ano">Neste Ano</a>
                  </div>
              </li>
              <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#!" id="bystatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-chart-histogram-alt"></i>Situação</a>
                  <div class="dropdown-menu" aria-labelledby="bystatus">
                      <a class="dropdown-item" href="?status=">Todos</a>
                      <div class="dropdown-divider"></div>
                      @foreach(\App\Helpers\Helper::deliveryStatus() as $status)
                          <a class="dropdown-item" href="?status={{$status->id}}">{{$status->name}}</a>
                      @endforeach
                  </div>
              </li>

              @if(auth()->user()->isAdmin())
                  <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#!" id="bystatus" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="icofont icofont-users-alt-5"></i>Cliente</a>
                      <div class="dropdown-menu" aria-labelledby="bystatus">
                          <a class="dropdown-item" href="?client=">Todos</a>
                          <div class="dropdown-divider"></div>
                          @foreach(\App\Helpers\Helper::clients() as $client)
                              <a class="dropdown-item" href="?client={{$client->id}}">{{$client->name}}</a>
                          @endforeach
                      </div>
                  </li>
              @endif

              <li class="nav-item">
                <form action="?">
                <div class="input-group" style="margin-top: 5px;margin-bottom: 5px;">
                  <input type="text" name="q" class="form-control" style="width: 200px;" placeholder="Pesquisar"/>
                  <span class="input-group-addon"><i class="feather icon-search"></i>
                  </span>
                </div>
              </li>

          </ul>
          <div class="nav-item nav-grid">
              @permission('create.ordem.entrega')
                <a class="btn bottom-right btn-primary btn-sm" href="{{route('delivery-order.create')}}">Nova OE</a>
              @endpermission
          </div>

      </nav>
    </div>

  </div>

  <div class="row">
    @forelse ($orders as $order)

    @php

      $status = $order->status->id;

      $bgColor = 'success';

      switch($status) {
        case '2':
          $bgColor = 'warning';
          break;
        case '3':
          $bgColor = 'primary';
          break;
        case '4':
          $bgColor = 'primary';
          break;
        case '5':
          $bgColor = 'danger';
          break;
      }

    @endphp

    <div class="col-sm-4">
        <div class="card card-border-{{ $bgColor }}">
            <div class="card-header">
                <a href="{{ route('delivery-order.show', $order->uuid) }}" class="card-title">#{{ str_pad($order->id, 6, "0", STR_PAD_LEFT) }}</a>
                <a href="{{ route('delivery-order.show', $order->uuid) }}"><b>{{$order->name}}</b></a>
                <a href="{{ route('delivery-order.show', $order->uuid) }}">
                  <span class="label label-{{$bgColor}} f-right"> {{$order->status->name}} </span></a>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-sm-12">
                        <p class="task-detail">Cliente: <b>{{ $order->client->name }}</b>
                          <br/>
                          <label class="label label-inverse-primary">{{$order->address->street}}, {{$order->address->number}} - {{$order->address->district}}, {{$order->address->city}}</label>
                        </p>
                        <p class="task-detail">Entregador: {{ $order->user ? $order->user->person->name : '-' }}</p>
                        <hr/>

                        <p class="task-due">Adicionado:
                        <label class="label label-inverse-success pull-right">{{ $order->created_at->format('d/m/Y H:i') }} {{ $order->created_at->diffForHumans() }}</label></p>
                        <hr/>
                        @if( $order->delivered_at)
                        <p class="task-due">Entregue:
                        <label class="label label-inverse-primary pull-right">{{ $order->delivered_at->format('d/m/Y H:i') }} {{ $order->delivered_at->diffForHumans() }}</label></p>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    @empty

      <div class="col-md-12 col-lg-12">

        <div class="widget white-bg no-padding">
            <div class="p-m text-center">
                <h1 class="m-md"><i class="fas fa-bullhorn fa-2x"></i></h1>
                <br/>
                <h6 class="font-bold no-margins">
                    Nenhuma ordem de entrega foi registrada até o momento.
                </h6>
            </div>
        </div>

      </div>

    @endforelse
  </div>

</div>

@endsection
