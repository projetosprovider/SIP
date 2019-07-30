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
                    <li class="breadcrumb-item"><a href="{{ route('delivery-order.index') }}">Ordem de Entrega</a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Editar</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="page-body">

    <form method="post" action="{{route('delivery-order.update', $delivery->uuid)}}">
        {{csrf_field()}}
        {{method_field('PUT')}}
        <div class="row">

              <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h5>Nova Ordem de Entrega</h5>
                        <div class="card-header-right">
                            <ul class="list-unstyled card-option">
                                <li><i class="feather icon-maximize full-card"></i></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-block">

                        <div class="row m-b-30">

                            <div class="col-md-12">

                            <h4>{{ $delivery->client->name }}</h4>

                            </div>

                            <div class="col-md-12">

                              <div class="form-group {!! $errors->has('address_id') ? 'has-error' : '' !!}">
                                  <label class="col-form-label">Endereço</label>
                                  <div class="input-group">
                                    <select class="select2" id="select-address" name="address_id" required>
                                        @foreach($addresses as $address)
                                            <option value="{{$address->uuid}}" {{ $loop->first ? 'selected' : '' }}>{{$address->description}}</option>
                                        @endforeach
                                    </select>
                                  </div>
                                  {!! $errors->first('address_id', '<p class="help-block">:message</p>') !!}
                              </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group {!! $errors->has('delivered_by') ? 'has-error' : '' !!}">
                                  <label class="col-form-label">Entregador</label>
                                    <div class="input-group">
                                      <select class="select2 select-entregador" data-search-user="{{ route('user_search') }}" name="delivered_by" required>
                                          <option value="">Selecione um entregador</option>
                                          @foreach($delivers as $deliver)
                                              <option value="{{$deliver->uuid}}" {{ $deliver->id == $delivery->delivered_by ? 'selected' : '' }}>{{$deliver->name}}</option>
                                          @endforeach
                                      </select>
                                      {!! $errors->first('delivered_by', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-6">

                                <div class="form-group {!! $errors->has('delivery_date') ? 'has-error' : '' !!}">
                                  <label class="col-form-label">Entrega</label>
                                    <div class="input-group">
                                        <input type="text" required class="form-control inputDate" name="delivery_date" value="{{ $delivery->delivery_date ? $delivery->delivery_date->format('d/m/Y') : '' }}"/>
                                        {!! $errors->first('delivery_date', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-12">

                                <div class="form-group {!! $errors->has('annotations') ? 'has-error' : '' !!}">
                                  <label class="col-form-label">Anotações</label>
                                    <div class="input-group">
                                        <textarea rows="5" class="form-control" name="annotations">{{ $delivery->annotations }}</textarea>
                                        {!! $errors->first('annotations', '<p class="help-block">:message</p>') !!}
                                    </div>
                                </div>

                            </div>

                        </div>

                        <button class="btn btn-success btn-sm">Salvar</button>

                      </div>
                  </div>
              </div>

              <div class="col-lg-5">
                  <div class="card">
                      <div class="card-header">
                          <h5>Documentos</h5>
                          <div class="card-header-right">
                              <ul class="list-unstyled card-option">
                                  <li><i class="feather icon-maximize full-card"></i></li>
                              </ul>
                          </div>
                      </div>
                      <div class="card-block">

                        <div class="table-responsive">

                        <table class="table table-hover">

                            <thead>
                                <tr>
                                  <th>#</th>
                                  <th>Tipo</th>
                                  <th>Cliente</th>
                                  <th>Status</th>
                                  <th>Anotações</th>
                                </tr>
                            </thead>

                            <tbody id="table-documents">

                                @foreach($documents as $document)
                                <tr>
                                    @php
                                        $docs = $delivery->documents->pluck('document.uuid');

                                        $checked = '';

                                        if(in_array($document->uuid, $docs->toArray())) {
                                          $checked = 'checked';
                                        }

                                    @endphp

                                    <td><input type="checkbox" {{ $checked }} name="documents[]" value="{{ $document->uuid }}"/></td>
                                    <td>{{ $document->type->name }}</td>
                                    <td>{{ $document->client->name }}</td>
                                    <td>{{ $document->status->name }}</td>
                                    <td>{{ $document->annotations }}</td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                        </div>

                      </div>
                  </div>
              </div>

          </div>
    </form>
</div>

@endsection

@section('scripts')
    <script>

      $(document).ready(function() {

        let selectEntregador = $(".select-entregador");
        let entregador = $("#entregador");

        selectEntregador.on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {

          let self = $(this);
          let route = self.data('search-user');
          let value = self.val();

          $.ajax({
            type: 'GET',
            url: route + '?param=' + value,
            async: true,
            success: function(response) {

              if(response.success) {

                let result = response.data;

                entregador.html("");
                let html = result.name + " - " + result.cpf;
                entregador.append(html);
              }


            }
          })


        });

      });

    </script>
@stop
