@extends('base')

@section('content')

<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>Documentos</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="{{ route('home') }}"> <i class="feather icon-home"></i> </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('documents.index') }}"> Documentos </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Informações</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="page-body">

  <div class="row">

    <div class="col-xl-12 col-lg-12 filter-bar">

      <div class="card">
          <div class="card-block">
              <div class=" waves-effect waves-light m-r-10 v-middle issue-btn-group">

                @if($document->status_id == 1)

                  @permission('create.ordem.entrega')
                    <a href="{{route('delivery-order.create', ['client' => $document->client->uuid, 'document[]' => $document->uuid])}}" class="btn btn-sm btn-success waves-effect waves-light m-r-15 m-b-5 m-t-5"><i class="fa fa-truck"></i>Gerar Entrega</a>
                  @endpermission
    
                  @permission('edit.documentos')
                    <a href="{{route('documents.edit', ['id' => $document->uuid])}}" class="btn btn-sm btn-primary waves-effect waves-light m-r-15 m-b-5 m-t-5"><i class="fa fa-edit"></i> Editar</a>
                  @endpermission

                  @permission('delete.documentos')
                    <a href="#!" data-route="{{route('documents.destroy', ['id' => $document->uuid])}}" class="btn btn-sm btn-danger waves-effect waves-light m-r-15 m-b-5 m-t-5 btnRemoveItem"><i class="fa fa-trash"></i> Remover</a>
                  @endpermission

                @endif

              </div>
          </div>
      </div>
    </div>

  </div>

  <div class="row">

    <div class="col-md-12">

        <div class="card">
            <div class="card-header bg-c-green update-card">
                <h5 class="text-white">Informações do Documento</h5>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">


                    </ul>
                </div>
            </div>
            <div class="card-block">
              <p class="m-t-30">Código: <b>#{{ str_pad($document->id, 6, "0", STR_PAD_LEFT) }}</b></p>
              <p>Tipo: {{ $document->type->name }}</p>
              <p>Empresa: <a href="{{route('clients.show', $document->client->uuid)}}"><b>{{ $document->client->name }}</b></a></p>
              <p>Funcionário: {{ $document->employee->name ?? '' }}</p>
              <p>Referencia: {{ $document->reference ?? '' }}</p>

              @if($document->address)
                @php
                    $formated = $document->address->description . ' - ' . $document->address->document . ' - ' . $document->address->street . ', ' . $document->address->number . ', ' . $document->address->district . ' - ' . $document->address->city . ' / ' . $document->address->state . ' - ' . $document->address->zip;
                @endphp

                <p>Endereço: {{ $formated }}</p>

              @endif
              <p>Adicionado por: {{ $document->creator->person->name ?? '' }} em {{ $document->created_at->format('d/m/Y H:i:s') }} <label class="label label-inverse-primary">{{ $document->created_at->diffForHumans() }}</label></p>

              @php

                $status = $document->status->id;

                $bgColor = 'success';

                switch($status) {
                  case '1':
                    $bgColor = 'primary';
                    break;
                  case '2':
                    $bgColor = 'warning';
                    break;
                  case '3':
                    $bgColor = 'success';
                    break;
                  case '4':
                    $bgColor = 'danger';
                    break;
                }

              @endphp

              <p>Situação: <span class="label label-{{$bgColor}}"> {{$document->status->name}} </span></p>

            </div>
        </div>

        @if($deliveryOrder)
          <div class="alert alert-primary background-primary">
              <p class="text-white">Este documento está vinculado à Ordem de Entrega: <a class="btn btn-success btn-sm" href="{{ route('delivery-order.show', $deliveryOrder->uuid) }}">#{{ str_pad($deliveryOrder->id, 6, "0", STR_PAD_LEFT) }}</a></p>
          </div>
        @endif

    </div>

  </div>

</div>

@endsection

@section('scripts')

<script></script>

@endsection
