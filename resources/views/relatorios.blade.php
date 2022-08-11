{{-- Todas as views adicionadas tem acesso aos dados vindos do controller--}}
@extends('page')

@section('title',"Desenpenho clientes")

@section('body')
    {{--    Por padrao a interface e referente a consultores --}}
    @php

        $classConsultores = "btn btn-success";
        $classClientes = "btn btn-outline-success";
        $entidade = "Consultores";

        $url_desempenho = "/desenpenho/consultores";


    @endphp

    @if($for == 'cliente')
        @php
            $classConsultores = "btn btn-outline-success";
            $classClientes = "btn btn-success";
            $entidade = "Clientes";

            $url_desempenho = "/desenpenho/clientes";

        @endphp
    @endif
    <p id="entidade" hidden>{{$for}}</p>
    <div class="row m-3 mt-5">
        <div class="col-lg-12">
            <a class="{{$classConsultores}}" href="/d-consultores" role="button">Consultores</a> <a
                class="{{$classClientes}}" href="/d-clientes" role="button">Clientes</a>
        </div>
    </div>
    <div class="row m-3 p-2 container-c-l" style="background: #FAFAFA">

        {{--         Cabelhao do container--}}
        <div class="col-lg-12 p-2">

            <div class="row cabecalho">
                <div class="col-md-2 mb-3 mt-2 title-periodo">
                    Período
                </div>
                <div class="col-md-8 mb-3">
                    <div class="row">
                        <div class="col-md-4 mt-2">
                            <div class="month-date">
                                <select id="initMonth">
                                    @foreach($meses as $key=>$mes)
                                        <option value="{{$key}}">{{$mes}}</option>
                                    @endforeach
                                </select>
                                <select id="initYear" class="mid-left">
                                    @foreach($anos as $ano)
                                        <option value="{{$ano}}">{{$ano}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="month-date">
                                <span style="margin-top: 10px"> á </span>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <div class="month-date">
                                <select id="endMonth" class="mid-right">
                                    @foreach($meses as $key=>$mes)
                                        <option value="{{$key}}">{{$mes}}</option>
                                    @endforeach
                                </select>
                                <select id="endYear">
                                    @foreach($anos as $ano)
                                        <option value="{{$ano}}">{{$ano}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-2"></div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-2">
                <div class="container-entity">
                    {{$entidade}}
                </div>
            </div>

            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-5">
                        <select multiple style="width:100%;height: 200px" id="list1">
                            @foreach($consultores_clientes as $consultor)
                                <option value="{{$consultor['codico']}}">{{$consultor['nome']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <div class="container-add-remove">
                            <button id="btnAdd"> >></button>
                            <button id="btnRemove"> <<</button>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <select multiple style="width:100%;height: 200px" id="list2">
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="estatistica-btn-container">
                    <div>
                        <form id="form-rel" method="GET" action="{{$url_desempenho}}/relatorio">
                            <input id="submitRel" type="button" value="Relatorio">
                        </form>
                        <form id="form-gra" method="GET" action="{{$url_desempenho}}/grafico">
                            <input id="submitGra" type="button" value="Grafico">
                        </form>
                        <form id="form-piz" method="GET" action="{{$url_desempenho}}/pizza">
                            <input id="submitPiz" type="button" value="Pizza">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row m-3">
        <div style="overflow-y: hidden;overflow-x: auto;" id="container-desempenho" class="col-lg-12">
            @yield('relatorio_consultor')
        </div>

    </div>
@endsection

@section('scripts')

    <script type="text/javascript" src="/js/chart-loader.js"></script>
    <script type="text/javascript" src="/js/plotly-latest.min.js"></script>
    <script type="text/javascript" src="/js/managelist.js"></script>

@endsection

