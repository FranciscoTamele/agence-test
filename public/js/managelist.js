/**'
 * Script responsavel em manipular as duas listas
 */
$(document).ready(function () {

    var interface = $("p#entidade").text();

    // Verifica se a interface e referente ao desempenho do consultor ou cliente
    function isClientInterface() {
        return interface === 'cliente';
    }

    var divBack = document.createElement('div');
    $(divBack).css({
        "background-image": "url(/img/preloader3.gif)",
        "background-repeat": "no-repeat",
        "background-position": "center",
        "width": "100%",
        "height": "450px"
    });

    var lista1 = $('#list1');
    var lista2 = $('#list2');

    var form_rel = $('#form-rel');
    var form_gra = $('#form-gra');
    var form_piz = $('#form-piz');

    var consultores = new Array();

    // Accao responsavel em tirar um cliente ou consultor da lista 1 para lista onde ira-se buscar o desempenho
    $('#btnAdd').click(function () {

        var elements = mover(lista1, lista2);

        for (i = 0; i < elements.length; i++) {
            consultores.push(elements[i][0]);
        }

    });

    // Accao responsavel em tirar um cliente ou consultor da lista 2 para lista1
    $('#btnRemove').click(function () {

        var elements = mover(lista2, lista1);

        if(elements.length!=0){

            for (i = 0; i < elements.length; i++) {

                consultores = consultores.filter(function (consultor) {
                    return !(consultor == elements[i][0]);
                });

            }

        }

    });

    // Funcao responsavel em formatar o valor para moeda brazileira
    function moedaBrazil(n) {
        return n.toLocaleString('pt-BR', {currency: 'BRL', style: 'currency'})
    }

    $("#submitRel").click(function (e) {

        if (consultores.length == 0) {

            e.preventDefault();
            alert("Selecione os dados dos quais pretende buscar o relatorio.");

        } else {

            $("#container-desempenho").empty();
            $("#container-desempenho").css({"height": "400px"});
            $("#container-desempenho").append(divBack);

            if (isClientInterface()) {
                var action = $(form_gra).attr("action");
            } else {
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

            $.ajax({
                url: action + params,
                success: function (result) {

                    $("#container-desempenho").css({"height": "auto"});
                    $("#container-desempenho").empty();

                    if (isClientInterface()) {

                        var dados = organizacaoDeDados(result);

                        var table = document.createElement('table');

                        // Variavel que ira armazenar os totais
                        var totais = [];
                        $.each(dados, function (index, json) {
                            var tr = document.createElement('tr');
                            var max = getMax(json);
                            if (index == 0) {
                                totais.push("Totais");
                                $(tr).attr("class", "colored total");
                                $.each(json, function (index1, json) {
                                    var td = document.createElement('td');
                                    $(td).html(json);
                                    $(tr).append(td);
                                });
                            } else {

                                var tr = document.createElement('tr');
                                $.each(json, function (index1, json) {
                                    var td = document.createElement('td');
                                    if (index1 != 0) {
                                        if (totais[index1] == null) {
                                            totais[index1] = 0;
                                        }
                                        totais[index1] = totais[index1] + parseInt(json);
                                        $(td).html(moedaBrazil(json));
                                        if (max == json) {
                                            $(td).css({"color": "#000eff"});
                                        }
                                    } else {
                                        $(td).html(json);
                                    }


                                    $(tr).append(td);

                                });
                            }
                            $(table).append(tr);
                        });

                        var max = getMax(totais);
                        var tr = document.createElement('tr');

                        $.each(totais, function (index1, json) {
                            var td = document.createElement('td');
                            if (index1 != 0) {
                                $(td).html("R$ " + json);
                                if (max == json) {
                                    $(td).css({"color": "#000eff"});
                                }
                            } else {
                                var td = document.createElement('td');
                                $(td).html(json);
                            }
                            $(tr).append(td);
                        });

                        $(tr).attr("class", "colored total");
                        $(table).append(tr);

                        var div = document.getElementById('container-desempenho');
                        $(div).css({"height": "auto"});
                        $(div).empty();
                        $(div).append(table);

                    } else {

                        var table = document.createElement('table');
                        $.each(result, function (index, objectJson) {

                            var table = document.createElement('table');
                            $(table).append("<tr class='colored'> <td colspan='5' style='color: black;text-align: left;font-weight: bold'>" + objectJson['nome'] + "</td> </tr>");
                            $(table).append("<tr class='colored total'> <td>Período</td> <td>Receita Líquida</td> <td>Custo Fixo</td> <td>Comissão</td> <td>Lucro</td> </tr>");

                            $.each(objectJson, function (indexj, json) {
                                if (indexj != 'nome' && indexj != 'total_receita_liquida' && indexj != 'total_custo_fixo' && indexj != 'total_lucro' && indexj != 'total_comissao') {
                                    var styleLucro = "";
                                    if (parseInt(json['lucro']) < 0) {
                                        styleLucro = "color:red";
                                    }
                                    $(table).append("<tr><td>" + indexj + "</td><td>" + moedaBrazil(json['rec_liquida']) + "</td><td>" + moedaBrazil(json['custo_fixo']) + "</td><td>" + moedaBrazil(json['comissao']) + "</td><td style=" + styleLucro + ">" + moedaBrazil(json['lucro']) + "</td></tr>");
                                }
                            });

                            var styleLucro = "";
                            if (parseInt(objectJson['total_lucro']) < 0) {
                                styleLucro = "color:red";
                            }

                            $(table).append("<tr class='colored total'><td>Total</td><td>" + moedaBrazil(objectJson['total_receita_liquida']) + "</td><td>" + moedaBrazil(objectJson['total_custo_fixo']) + "</td><td>" + moedaBrazil(objectJson['total_comissao']) + "</td><td style='" + styleLucro + "'>" + moedaBrazil(objectJson['total_lucro']) + "</td></tr>");
                            $("#container-desempenho").append(table);
                            $("#container-desempenho").append("<br>");

                        });

                    }

                },
                error: function (error) {

                    $("#container-desempenho").empty();
                    $("#container-desempenho").css({"height": "auto"});
                    $("#container-desempenho").append("<p style='color: red;font-size: 30px;text-align: center;margin-top: 50px;margin-bottom: 50px'>Ocorreu ao carregar os dados</p>");


                }

            });

        }


    });

    $("#submitGra").click(function (e) {

        if (consultores.length == 0) {
            e.preventDefault()
            alert("Selecione os dados dos quais pretende buscar o grafico.")
        } else {

            $("#container-desempenho").empty();
            $("#container-desempenho").append(divBack);

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

            $.ajax({
                url: action + params,
                success: function (result) {

                    $("#container-desempenho").css({"height": "auto"});
                    $("#container-desempenho").empty();

                    if (isClientInterface()) {

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
                            $(div).css({"height": "400px"});
                            var chart = new google.charts.Bar(div);

                            chart.draw(data, google.charts.Bar.convertOptions(options));

                        }

                    } else {

                        var mediaSalario = result.media_salario;

                        google.charts.load('current', {'packages': ['bar']});
                        google.charts.setOnLoadCallback(drawChart);

                        function drawChart() {
                            var data = google.visualization.arrayToDataTable(organizacaoDeDados(result));

                            var options = {
                                chart: {
                                    title: 'Gráfico de desempenho',
                                    subtitle: "Custo Médio Fixo: R$ " + mediaSalario
                                }
                            };

                            var div = document.getElementById('container-desempenho');
                            $(div).empty();
                            $(div).css({"height": "400px"});
                            var chart = new google.charts.Bar(div);

                            chart.draw(data, google.charts.Bar.convertOptions(options));

                        }

                    }


                },
                error: function (error) {

                    $("#container-desempenho").empty();
                    $("#container-desempenho").css({"height": "auto"});
                    $("#container-desempenho").append("<p style='color: red;font-size: 30px;text-align: center;margin-top: 50px;margin-bottom: 50px'>Ocorreu ao carregar os dados</p>");

                }
            });

        }

    });

    $("#submitPiz").click(function (e) {

        if (consultores.length == 0) {
            e.preventDefault()
            alert("Selecione os dados dos quais pretende buscar o diagrama.")
        } else {

            $("#container-desempenho").empty();
            $("#container-desempenho").append(divBack);

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

            $.ajax({
                url: action + params,
                success: function (result) {

                    $("#container-desempenho").css({"height": "auto"});
                    $("#container-desempenho").empty();

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

                },
                error: function (error) {


                    $("#container-desempenho").empty();
                    $("#container-desempenho").css({"height": "auto"});
                    $("#container-desempenho").append("<p style='color: red;font-size: 30px;text-align: center;margin-top: 50px;margin-bottom: 50px'>Ocorreu ao carregar os dados</p>");

                }
            });

        }

    });

    // Move um item de uma lista para outra
    function mover(lista1, lista2) {

        var elements = [];

        lista1.find(":checked").each(function (index, json) {

            if ($(json).attr("value") != '') {


                $(json).remove()
                var opt = new Option();
                opt.value = $(json).attr("value");
                opt.text = $(json).text();
                lista2.append(opt);

                elements.push([$(json).attr("value"), $(json).text()])

            }

        });

        return elements;

    }

    // Funcao responsavel em organizar os dados para criacao de graficos e tabelas
    function organizacaoDeDados(dados) {

        var userId;
        var tituloArr = [];
        var usuarios = [];

        var result;
        result = dados.desempenho;

        if (isClientInterface()) {
            tituloArr.push("Periodo");
        } else {
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
            $.each(usuarios, function (index2, json) {
                arr.push(result[json]['faturas'][index].rec_liquida);
            });
            dados.push(arr);

        });

        return dados;

    }

    function getMax(arr) {
        var max;
        for (var i = 0; i < arr.length; i++) {
            if (i != 0) {
                if (max == null || parseInt(arr[i]) > parseInt(max))
                    max = arr[i];
            }

        }
        return max;
    }

});
