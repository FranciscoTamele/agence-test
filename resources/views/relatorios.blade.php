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
    @yield('script')

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script>

        /**'
         * Script responsavel em manipular as duas listas
         */

        $(document).ready(function () {

            var interface = $("p#entidade").text();

            // Verifica se a interface e referente ao desempenho do consultor ou cliente
            function isClientInterface(){
                return interface==='cliente';
            }

            console.log(isClientInterface());

            var lista1 = $('#list1');
            var lista2 = $('#list2');

            var form_rel = $('#form-rel');
            var form_gra = $('#form-gra');
            var form_piz = $('#form-piz');

            var consultores = new Array();

            // Accao responsavel em tirar um cliente ou consultor da lista 1 para lista onde ira-se buscar o desempenho
            $('#btnAdd').click(function () {

                var element = mover(lista1, lista2);
                if (element != null) {
                    consultores.push(element.val());
                }

            });

            // Accao responsavel em tirar um cliente ou consultor da lista 2 para lista1
            $('#btnRemove').click(function () {

                var element = mover(lista2, lista1);
                if (element != null) {
                    consultores = consultores.filter(function (consultor) {
                        return !(consultor == element.val());
                    });
                }

            });

            $("#submitRel").click(function (e) {

                if (consultores.length == 0) {
                    e.preventDefault();
                    alert("Selecione os dados dos quais pretende buscar o relatorio.");
                } else {

                            if(isClientInterface()){
                                var action = $(form_gra).attr("action");
                            }else{
                                var action = $(form_rel).attr("action");
                            }

                            var params = "?";

                            for (i = 0; i < consultores.length; i++) {
                                if (i == (consultores.length - 1)) {
                                    params += (i + "=" + consultores[i]);
                                } else {
                                    params += (i + "=" + consultores[i] + "&");
                                }
                            }

                            var datainicio = "&datainit=" + $("#initYear").find(":checked").val() + "-" + $("#initMonth").find(":checked").val();
                            var datafim = "&dataend=" + $("#endYear").find(":checked").val() + "-" + $("#endMonth").find(":checked").val();

                            params += datainicio;
                            params += datafim;

                            console.log(action+params);
                            $.ajax({
                                url: action + params,
                                success: function (result) {

                                    if(isClientInterface()){

                                        var dados = organizacaoDeDados(result);

                                        var table = document.createElement('table');

                                        // Variavel que ira armazenar os totais
                                        var totais = [];
                                        $.each(dados,function(index, json){
                                            var tr = document.createElement('tr');
                                            var max=getMax(json);
                                            if(index==0){
                                                totais.push("Totais");
                                                $(tr).attr("class","colored total");
                                                $.each(json,function(index1,json){
                                                    var td = document.createElement('td');
                                                    $(td).html(json);
                                                    $(tr).append(td);
                                                });
                                            }else{

                                                var tr = document.createElement('tr');
                                                $.each(json,function(index1,json){
                                                    var td = document.createElement('td');
                                                    if(index1!=0){
                                                        if(totais[index1]==null){
                                                            totais[index1]=0;
                                                        }
                                                        totais[index1]=totais[index1]+parseInt(json);
                                                        $(td).html("R$ "+json);
                                                        if(max==json){
                                                            $(td).css({"color":"#000eff"});
                                                        }
                                                    }else{
                                                        $(td).html(json);
                                                    }


                                                    $(tr).append(td);

                                                });
                                            }
                                            $(table).append(tr);
                                        });

                                        var max = getMax(totais);
                                        var tr=document.createElement('tr');

                                        $.each(totais,function(index1,json){
                                            var td = document.createElement('td');
                                            if(index1!=0){
                                                $(td).html("R$ "+json);
                                                if(max==json){
                                                    $(td).css({"color":"#000eff"});
                                                }
                                            }else{
                                                var td = document.createElement('td');
                                                $(td).html(json);
                                            }
                                            $(tr).append(td);
                                        });

                                        $(tr).attr("class","colored total");
                                        $(table).append(tr);

                                        var div = document.getElementById('container-desempenho');
                                        $(div).css({"height":"auto"});
                                        $(div).empty();
                                        $(div).append(table);

                                    }else{


                                        var table = document.createElement('table');
                                        $.each(result,function (index,objectJson){
                                            $.each(objectJson,function (index,son){
                                                console.log(index);
                                            });
                                        });

                                    }




                                }
                            });

                    }


            });

            $("#submitGra").click(function (e) {

                if (consultores.length == 0) {
                    e.preventDefault()
                    alert("Selecione os dados dos quais pretende buscar o grafico.")
                } else {

                    var action = $(form_gra).attr("action");
                    var params = "?";

                    for (i = 0; i < consultores.length; i++) {
                        if (i == (consultores.length - 1)) {
                            params += (i + "=" + consultores[i]);
                        } else {
                            params += (i + "=" + consultores[i] + "&");
                        }
                    }

                    var datainicio = "&datainit=" + $("#initYear").find(":checked").val() + "-" + $("#initMonth").find(":checked").val();
                    var datafim = "&dataend=" + $("#endYear").find(":checked").val() + "-" + $("#endMonth").find(":checked").val();

                    params += datainicio;
                    params += datafim;


                    console.log(action+params);
                    $.ajax({
                        url: action + params,
                        success: function (result) {

                            if(isClientInterface()){

                                google.charts.load('current', {'packages': ['bar']});
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() {
                                    var data = google.visualization.arrayToDataTable(organizacaoDeDados(result));

                                    var options = {
                                        chart: {
                                            title: 'Gráfico de desempenho'
                                        }
                                    };

                                    var div = document.getElementById('container-desempenho');
                                    $(div).empty();
                                    $(div).css({"height":"400px"});
                                    var chart = new google.charts.Bar(div);

                                    chart.draw(data, google.charts.Bar.convertOptions(options));

                                }

                            }else{

                                var mediaSalario = result.media_salario;

                                google.charts.load('current', {'packages': ['bar']});
                                google.charts.setOnLoadCallback(drawChart);

                                function drawChart() {
                                    var data = google.visualization.arrayToDataTable(organizacaoDeDados(result));

                                    var options = {
                                        chart: {
                                            title: 'Gráfico de desempenho',
                                            subtitle: "Custo Médio Fixo: R$ "+mediaSalario
                                        }
                                    };

                                    var div = document.getElementById('container-desempenho');
                                    $(div).empty();
                                    $(div).css({"height":"400px"});
                                    var chart = new google.charts.Bar(div);

                                    chart.draw(data, google.charts.Bar.convertOptions(options));

                                }

                            }




                        }
                    });

                }

            });

            $("#submitPiz").click(function (e) {

                if (consultores.length == 0) {
                    e.preventDefault()
                    alert("Selecione os dados dos quais pretende buscar o diagrama.")
                } else {

                    var action = $(form_piz).attr("action");
                    var params = "?";

                    for (i = 0; i < consultores.length; i++) {
                        if (i == (consultores.length - 1)) {
                            params += (i + "=" + consultores[i]);
                        } else {
                            params += (i + "=" + consultores[i] + "&");
                        }
                    }

                    var datainicio = "&datainit=" + $("#initYear").find(":checked").val() + "-" + $("#initMonth").find(":checked").val();
                    var datafim = "&dataend=" + $("#endYear").find(":checked").val() + "-" + $("#endMonth").find(":checked").val();

                    params += datainicio;
                    params += datafim;

                    console.log(action + params);

                    $.ajax({
                        url: action + params,
                        success: function (result) {

                            var xArray = [];
                            var yArray = [];

                            $.each(JSON.parse(result), function (index, jsonObject) {
                                xArray.push(jsonObject.nome);
                                yArray.push(jsonObject.total_receita_liquida);
                            });

                            var layout = {title: "Gráfico de desempenho"};

                            var data = [{labels: xArray, values: yArray, type: "pie"}];

                            var div = document.getElementById('container-desempenho');
                            $(div).empty();
                            Plotly.newPlot("container-desempenho", data, layout);

                        }
                    });

                }

            });

            function organizacaoDeDados(dados){

                var userId;
                var tituloArr = [];
                var usuarios = [];

                var result;
                result=dados.desempenho;

                if(isClientInterface()){
                    tituloArr.push("Periodo");
                }else{
                    tituloArr.push("Mês e Ano");
                }

                $.each(result, function (index, objectJson) {
                    tituloArr.push(objectJson['nome']);
                });

                var dados = [];

                $.each(result, function (index, objectJson) {
                    usuarios.push(index);
                    userId = index;
                });

                dados.push(tituloArr);

                $.each(result[userId]['faturas'], function (index, objectJson) {

                    var arr = [];
                    arr.push(index);
                    $.each(usuarios,function(index2, json){
                        arr.push(result[json]['faturas'][index].rec_liquida);
                    });
                    dados.push(arr);

                });

                return dados;

            }

            function getMax(arr) {
                var max;
                for (var i=0 ; i<arr.length ; i++) {
                    if(i!=0){
                        if (max == null || parseInt(arr[i]) > parseInt(max))
                            max = arr[i];
                    }

                }
                return max;
            }

        });

        function submitForm(consultores, form) {

            for (i = 0; i < consultores.length; i++) {
                var input = document.createElement('input');
                input.setAttribute("name", i);
                input.setAttribute("value", consultores[i]);
                input.setAttribute("type", "hidden");
                form.append(input);
            }

            var input = document.createElement('input');
            input.setAttribute("name", "datainit");
            input.setAttribute("value", $("#initYear").find(":checked").val() + "-" + $("#initMonth").find(":checked").val());
            input.setAttribute("type", "hidden");
            form.append(input);

            var input = document.createElement('input');
            input.setAttribute("name", "dataend");
            input.setAttribute("value", $("#endYear").find(":checked").val() + "-" + $("#endMonth").find(":checked").val());
            input.setAttribute("type", "hidden");
            form.append(input);

            form.submit();

        }

        // Move um item de uma lista para outra
        function mover(lista1, lista2) {
            var val = lista1.find(":checked");
            // Se um opccao estiver selecionada retorna valor acima de 0
            if (val.length != 0) {
                val.remove()
                var opt = new Option();
                opt.value = val.val();
                opt.text = val.text();
                lista2.append(opt);
                return val;
            } else {
                return null;
            }
        }



    </script>

    <script>


    </script>
@endsection

