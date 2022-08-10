<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Desempenho extends Model
{
    use HasFactory;

    /************************************************* Consultores *******************************************************/

    public static function getDesempenho($consultores, $datainit, $dataend){

        // consulta ao banco de dados dos consultos, suas OS e  faturas
        $consultoresDes = DB::select("select us.co_usuario as codico, us.no_usuario as nome,os.co_os,f.co_fatura,f.valor,f.data_emissao,f.comissao_cn,f.total_imp_inc
        from cao_os as os join cao_usuario as us on us.co_usuario = os.co_usuario and os.co_usuario in(".$consultores.")
        join cao_fatura f on os.co_os = f.co_os and f.data_emissao >= '".$datainit."' and f.data_emissao <= '".$dataend."' order by f.data_emissao asc");

        // consulta dos salarios
        $consultoresSal = DB::select("select *,co_usuario as codico from cao_salario where co_usuario in (".$consultores.")");

        $consultoresDes = array_map(function ($value) {
            return (array)$value;
        }, $consultoresDes);

        $consultoresSal = array_map(function ($value) {
            return (array)$value;
        }, $consultoresSal);

        return self::gerarRelatorioConsultores($consultoresDes,$consultoresSal);

    }

    /**
     * @param $consultoresDes array consultos com suas respectivas O.S e faturas
     * @param $consultoresSal array dados salariais dos consultores
     * @return array dados calculados : receita_liquida, comissao, custo_fixo e lucro
     */
    private static function gerarRelatorioConsultores($consultoresDes, $consultoresSal=null){

        $desempenho=[];

        foreach ($consultoresDes as $key => $fatura) {

            if(isset($desempenho[$fatura['codico']])){

                $imp=($fatura['total_imp_inc']/100*$fatura['valor']);
                $receita = $fatura['valor'] - $imp;
                $comissao = $fatura['valor'] - ($fatura['comissao_cn']/100*$imp);

                $data = date('m-Y',strtotime($fatura['data_emissao']));

                if(isset($desempenho[$fatura['codico']][$data])){

                    $desempenho[$fatura['codico']][$data]['rec_liquida'] = $desempenho[$fatura['codico']][$data]['rec_liquida'] + $receita;
                    $desempenho[$fatura['codico']][$data]['comissao'] = $desempenho[$fatura['codico']][$data]['comissao'] + $comissao;

                }else{

                    $desempenho[$fatura['codico']][$data]=[
                        "rec_liquida"=> $receita,
                        "comissao"=> $comissao,
                        "lucro"=>0,
                        "custo_fixo"=>0
                    ];

                }

            }else{

                $imp=($fatura['total_imp_inc']/100*$fatura['valor']);
                $receita = $fatura['valor'] - $imp;
                $comissao = $fatura['valor'] - ($fatura['comissao_cn']/100*$imp);

                $data = date('m-Y',strtotime($fatura['data_emissao']));

                $desempenho[$fatura['codico']]=[
                    'nome'=>$fatura['nome'],
                    'total_receita_liquida'=>0,
                    'total_custo_fixo'=>0,
                    'total_lucro'=>0,
                    'total_comissao'=>0,
                    $data=>[
                        "rec_liquida"=> $receita,
                        "comissao"=> $comissao,
                        "lucro"=>0,
                        "custo_fixo"=>0
                    ]
                ];

            }

        }

// Definir salarios

        if($consultoresSal!=null){
            foreach ($desempenho as $key => $desem){

                foreach ($consultoresSal as $consultore){

                    if($key == $consultore['codico']){

                        foreach ($desem as $data => $values){
                            if($data!='nome' && $data!='total_receita_liquida' && $data!='total_custo_fixo' && $data!='total_lucro' && $data!='total_comissao'){
                                $desempenho[$key][$data]['custo_fixo']=$consultore['brut_salario'];
                                $desempenho[$key][$data]['lucro']=$desempenho[$key][$data]['rec_liquida'] - ( $desempenho[$key][$data]['custo_fixo'] + $desempenho[$key][$data]['comissao']);
                            }
                        }

                    }
                }
            }
        }



// Calculo dos totais
        foreach ($desempenho as $key => $desem){
            foreach ($desem as $data => $values) {
                if ($data != 'nome' && $data != 'total_receita_liquida' && $data != 'total_custo_fixo' && $data != 'total_lucro' && $data != 'total_comissao') {
                    $desempenho[$key]['total_receita_liquida'] =$desempenho[$key]['total_receita_liquida'] +$desempenho[$key][$data]['rec_liquida'];
                    $desempenho[$key]['total_custo_fixo'] =$desempenho[$key]['total_custo_fixo'] +$desempenho[$key][$data]['custo_fixo'];
                    $desempenho[$key]['total_lucro'] =$desempenho[$key]['total_lucro'] +$desempenho[$key][$data]['lucro'];
                    $desempenho[$key]['total_comissao'] =$desempenho[$key]['total_comissao'] +$desempenho[$key][$data]['comissao'];
                }
            }
        }

        return $desempenho;

    }

    public static function getDesempenhoGraficoConsultores($consultores, $datainit, $dataend){

        // consulta ao banco de dados dos consultos, suas OS e  faturas
        $consultoresDes = DB::select("select us.co_usuario as codico, us.no_usuario as nome, os.co_os,f.co_fatura,f.valor,f.data_emissao,f.comissao_cn,f.total_imp_inc
        from cao_os as os join cao_usuario as us on us.co_usuario = os.co_usuario and os.co_usuario in(".$consultores.")
        join cao_fatura f on os.co_os = f.co_os and f.data_emissao >= '".$datainit."' and f.data_emissao <= '".$dataend."' order by f.data_emissao asc");

        // consulta dos salarios
        $consultoresSal = DB::select("select * from cao_salario where co_usuario in (".$consultores.")");

        $consultoresDes = array_map(function ($value) {
            return (array)$value;
        }, $consultoresDes);

        $consultoresSal = array_map(function ($value) {
            return (array)$value;
        }, $consultoresSal);

        $total=0;
        foreach ($consultoresSal as $consult){
            $total+=$consult['brut_salario'];
        }

        $media = $total/sizeof($consultoresSal);

        return [
            "media_salario"=>$media,
            "desempenho"=>self::gerarGraficoConsultores($consultoresDes)
        ];

    }

    /**
     * @param $consultoresDes array consultos com suas respectivas O.S e faturas
     * @return array dados calculados : receita_liquida
     */
    private static function gerarGraficoConsultores($consultoresDes){

        $datasFaturas = [];
        $i=0;

        /**
         * Criar faturas
         */
        foreach($consultoresDes as $res){
            if(!isset($datasFaturas[$res['data_emissao']])){
                $datasFaturas[$res['data_emissao']]=[
                    "rec_liquida"=> 0
                ];
                $i++;
            }
        }

        $desempenho=[];
        foreach ($consultoresDes as $result){

            if(!isset($desempenho[$result['codico']])){
                $desempenho[$result['codico']]=[
                    "nome"=>$result['nome'],
                    "faturas"=>$datasFaturas
                ];
            }

        }

        foreach ($consultoresDes as $result){

            $imp=($result['total_imp_inc']/100*$result['valor']);
            $receita = $result['valor'] - $imp;

            $desempenho[$result['codico']]['nome']=$result['nome'];
            $desempenho[$result['codico']]['faturas'][$result['data_emissao']]['rec_liquida'] = $desempenho[$result['codico']]['faturas'][$result['data_emissao']]['rec_liquida']+$receita;

        }

        return $desempenho;

    }

    /************************************************* Clientes *******************************************************/

    public static function getDesempenhoGrafico($clientes, $datainit, $dataend){

        // consulta ao banco de dados dos consultos, suas OS e  faturas
        $clientes = DB::select("select f.co_cliente as codico,c.no_razao as nome,f.co_fatura,f.valor,f.data_emissao,f.comissao_cn,f.total_imp_inc from cao_fatura as f join cao_cliente as c on f.co_cliente = c.co_cliente and f.co_cliente in(".$clientes.") and f.data_emissao >= '".$datainit."' and f.data_emissao < '".$dataend."' order by f.data_emissao asc");

        $clientes = array_map(function ($value) {
            return (array)$value;
        }, $clientes);

        return self::gerarRelatorioConsultores($clientes,null);

    }

    public static function getDesempenhoClientes($consultores, $datainit, $dataend){

        // consulta ao banco de dados dos consultos, suas OS e  faturas
        $clientes = DB::select("select f.co_cliente as codico,c.no_razao as nome,f.co_fatura,f.valor,f.data_emissao,f.comissao_cn,f.total_imp_inc from cao_fatura as f join cao_cliente as c on f.co_cliente = c.co_cliente and f.co_cliente in(".$consultores.") and f.data_emissao >='".$datainit."' and f.data_emissao <'".$dataend."' order by f.data_emissao asc");

        $clientes = array_map(function ($value) {
            return (array)$value;
        }, $clientes);

        return [
            "desempenho"=>self::gerarGraficoConsultores($clientes)
        ];

    }



}
