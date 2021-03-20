<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bills System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

</head>
<body>

    <div class="container">

        @include('layout.messages')

        <form action="{{ route('bill.store') }}" method="post">
            @csrf

            <div class="row">

                <div class="col-md-12">

                    <div class="card mt-5">
                        <div class="card-header bg-primary text-light">
                            Nova conta
                        </div>
                        <div class="card-body">

                            <form action="">

                                <div class="row">

                                    <div class="col-md-4 mb-3">
                                        <label for="bill_identifier" class="form-label">Identificação</label>
                                        <input type="text" class="form-control" id="bill_identifier" name="bill_identifier" required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="date" class="form-label">Data</label>
                                        <input type="date" class="form-control" id="bill_date" name="bill_date" required>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label for="bill_amount" class="form-label">Valor</label>
                                        <input type="number" class="form-control" id="bill_amount" name="bill_amount" min="0.01" step="0.01" required>
                                    </div>

                                    <div class="col-md-3 mb-3">
                                        <label for="bill_type" class="form-label">Tipo</label>
                                        <select class="form-select" id="bill_type" name="bill_type" required>
                                            <option value="">- - -</option>
                                            <option value="0">Contas a pagar</option>
                                            <option value="1">Contas a receber</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <button type="submit" class="btn btn-success">Gravar</button>
                                    </div>

                                </div>

                            </form>

                        </div>
                    </div>

                </div>

            </div>
        </form>

        <div class="row">

            <div class="col-md-12">

                <div class="card mt-5">
                    <div class="card-header bg-primary text-light">
                        Extrato
                    </div>
                    <div class="card-body">

                         <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Identificação</th>
                                    <th width="240">Data</th>
                                    <th width="130">Valor</th>
                                    <th width="160">Status</th>
                                    <th width="145"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bills as $bill)
                                @if ($bill->bill_date < now() && $bill->bill_type == 0)
                                    <tr class="table-danger">
                                @else
                                    @if ($bill->bill_stats == 1 && $bill->bill_type == 0)
                                    <tr class="table-danger">
                                    @endif
                                @endif
                                    <td class="align-middle">{{ $bill->id }}</td>
                                    <td class="align-middle">{{ $bill->bill_identifier }}</td>
                                    <td class="align-middle">{{ $bill->bill_date->format('d/m/Y') }}</td>
                                    <td class="align-middle">{{ $bill->price_formated }}</td>
                                    @if ($bill->bill_stats == 1 && $bill->bill_type == 0)
                                        <td class="align-middle"><span class="badge bg-success">Pago em dia</span></td>
                                    @else
                                        @if ($bill->bill_stats == 1 && $bill->bill_type == 1)
                                            <td class="align-middle"><span class="badge bg-success">Recebido em dia</span></td>
                                        @else
                                            @if ($bill->bill_date < now() && $bill->bill_stats != 1)
                                                <td class="align-middle"><span class="badge bg-danger">Atrasado</span></td>
                                            @else
                                                <td class="align-middle"><span class="badge bg-info">Em dia</span></td>
                                            @endif
                                        @endif
                                    @endif
                                    <td class="align-middle">
                                        <button type="button" class="btn btn-success btn-sm"  action="{{ route('bill.update', $bill->id)}}" data-bs-toggle="modal" data-bs-target="#exampleModal">Baixar</button>
                                        <a class="btn btn-danger btn-sm" onclick="deleteInDatabase('{{ route('bill.destroy', $bill->id)}}')">Excluir</a>
                                    </td>
                                </tr>
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-12">

                <div class="card mt-5">
                    <div class="card-header bg-primary text-light">
                        Balanço
                    </div>
                    <div class="card-body">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Total pago</th>
                                    <th>Total a pagar</th>
                                    <th>Total recebido</th>
                                    <th>Total a receber</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="text-success"> {{ $payedValue }} </th>
                                    <th class="text-danger"> {{ $toPayValue }} </th>
                                    <th class="text-primary"> {{ $recivedValue }} </th>
                                    <th> {{ $toReciveValue }} </th>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

            </div>

        </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmação de Baixa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="bill_down_date" class="form-label">Digite a data de pagamento</label>
                    <input type="date" class="form-control" id="bill_down_date" name="bill_down_date" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-primary">Confirmar baixa</button>
            </div>
            </form>
        </div>
    </div>
    <!-- Forms -->
        <form id="delete_form" action="" method="post">

        @csrf
        @method('delete')

      </form>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script>

        const order_by = document.querySelector('#order_by');

        function deleteInDatabase(path){
            if(confirm('Voce tem certeza que deseja excluir esse registro?')){
                const deleteForm = document.querySelector('#delete_form');
                deleteForm.action = path;

                deleteForm.submit();
            }
        }

    </script>

</body>
</html>

