@extends('relatorios')


@section('relatorio_consultor')

@foreach($relatorios as $key=> $relatorio)
<div class="container-table">
<table>

    <tbody>
    <tr class="colored">
        <td colspan="5" style="color: black;text-align: left;font-weight: bold">{{$relatorio['nome']}}</td>
    </tr>
    <tr class="colored total">
        <td>Período</td>
        <td>Receita Líquida</td>
        <td>Custo Fixo</td>
        <td>Comissão</td>
        <td>Lucro</td>
    </tr>

    @foreach($relatorio as $key => $dados)
        @if($key!='nome' && $key!='total_receita_liquida' && $key!='total_custo_fixo' && $key!='total_lucro' && $key!='total_comissao')
        <tr>
            <td> {{$key}}</td>
            <td>R$ {{$dados['rec_liquida']}}</td>
            <td>R$ {{$dados['custo_fixo']}}</td>
            <td>R$ {{$dados['comissao']}}</td>
            @if($dados['lucro']<0)
                <td style="color: red">R$ {{$dados['lucro']}}</td>
            @else
                <td>R$ {{$dados['lucro']}}</td>
            @endif

        </tr>
        @endif
    @endforeach
    <tr class="colored total">
        <td>Total</td>
        <td>R$ {{$relatorio['total_receita_liquida']}}</td>
        <td>R$ {{$relatorio['total_custo_fixo']}}</td>
        <td>R$ {{$relatorio['total_comissao']}}</td>
        <td>R$ {{$relatorio['total_lucro']}}</td>
    </tr>
    </tbody>
</table>
</div>
<br>
@endforeach
    <br>
@endsection
@section('script')

@endsection
