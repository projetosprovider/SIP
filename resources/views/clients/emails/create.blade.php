@extends('base')

@section('content')

<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>E-mails do Cliente</h4>
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
                        <a href="{{ route('clients.show', $client->uuid) }}"> Cliente </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#!">Novo E-mail</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="page-body">

  <div class="card">
      <div class="card-header">
          <h5>Novo E-mail</h5>
      </div>
      <div class="card-block">

        <form class="formValidation" data-parsley-validate action="{{ route('email.store') }}" method="post">
            {{csrf_field()}}
            <div class="row">

              <input type="hidden" name="client_id" value="{{ $client->uuid }}"/>

              <div class="form-group col-md-12"><label class="control-label">E-mail</label>
                  <input type="email" required id="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Informe o E-mail">
              </div>

            </div>

            <button type="submit" class="btn btn-success btn-sm">Salvar</button>
            <a href="{{ route('clients.show', $client->uuid) }}" class="btn btn-danger btn-sm">Cancelar</a>

        </form>

      </div>
  </div>
</div>

@endsection
