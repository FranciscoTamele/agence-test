<?php

namespace App\Http\Controllers;

use App\Models\Desempenho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceiroController extends Controller
{
     const MESES = [
        1 => 'Janeiro',
        2 => 'Fevereiro',
        3 => 'Março',
        4 => 'Abril',
        5 => 'Maio',
        6 => 'Junho',
        7 => 'Julho',
        8 => 'Agosto',
        9 => 'Setembro',
        10 => 'Outubro',
        11 => 'Novembro',
        12 => 'Dezembro'
    ];

    public function financeiro()
    {
        return redirect("/d-consultores");
    }

    // Consultores **********************************************************
    public function consultores()
    {

        return View('relatorios', [
            'for' => 'consultor',
            'meses' => $this->getMesesFaturas(),
            'anos' => $this->getAnosFaturas(),
            'consultores_clientes'=>$this->getConsultores()
        ]);
    }

    public function consultoresRelatorioDes(Request $request){

        $dados = $this->dadosDeRequisicaoConsultores();

        return View('relatorio_consultores', [
            'for' => 'consultor',
            'meses' => $this->getMesesFaturas(),
            'anos' => $this->getAnosFaturas(),
            'relatorios'=> Desempenho::getDesempenho($dados['consultores'], $dados['inicio'], $dados['final']),
            'consultores_clientes'=>$this->getConsultores()
        ]);

    }

    public function consultoresPizzaDes(){

        $dados = $this->dadosDeRequisicaoConsultores();

        $desempenho = Desempenho::getDesempenho($dados['consultores'], $dados['inicio'], $dados['final']);

        header('Content-Type:application/json');
        echo json_encode($desempenho,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function consultoresGraficoDes(){

        $dados = $this->dadosDeRequisicaoConsultores();

        $desempenho = Desempenho::getDesempenhoGraficoConsultores($dados['consultores'], $dados['inicio'], $dados['final']);


        header('Content-Type:application/json');
        echo json_encode($desempenho,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }


    // Clientes **********************************************************
    public function clientes()
    {

        return View('relatorios', [
            'for' => 'cliente',
            'meses' => $this->getMesesFaturas(),
            'anos' => $this->getAnosFaturas(),
            'consultores_clientes'=>$this->getClientes()
        ]);
    }

    public function clientesRelatorioDesem(Request $request){

        $dados = $this->dadosDeRequisicaoConsultores();

        header('Content-Type:application/json');
        $dados = Desempenho::getDesempenhoClientes($dados['consultores'], $dados['inicio'], $dados['final']);
        echo json_encode($dados,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public function clientesPizzaDesem(Request $request){

        $dados = $this->dadosDeRequisicaoConsultores();

        header('Content-Type:application/json');
        $dados = Desempenho::getDesempenhoGrafico($dados['consultores'], $dados['inicio'], $dados['final']);
        echo json_encode($dados,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public function clientesGraficoDesem(Request $request){

        $dados = $this->dadosDeRequisicaoConsultores();

        header('Content-Type:application/json');
        $dados = Desempenho::getDesempenhoClientes($dados['consultores'], $dados['inicio'], $dados['final']);
        echo json_encode($dados,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    /**********************************************************************/
    private function getMesesFaturas(){

        $result = DB::select('select distinct(month(data_emissao)) as mes from cao_fatura order by month(data_emissao) asc');

        $meses = $this->toArray($result);
        $meses = array_map(function($mes){
            return $mes['mes'];
        },$meses);

        $newMeses=[];

        foreach ($meses as $key => $mes){
            $newMeses[$key+1]=self::MESES[$key+1];
        }

        return $newMeses;
    }

    /**
     * Metodo responsavel por retornar os anos que tem alguma fatura
     * @return array
     */
    private function getAnosFaturas(){
        $result = DB::select('select distinct(year(data_emissao)) as ano from cao_fatura order by data_emissao asc');

        $anos = $this->toArray($result);
        $anos = array_map(function($ano){
            return $ano['ano'];
        },$anos);

        return $anos;
    }

    /**
     * Metodo responsavel em buscar os consultores
     * @return array[]
     */
    private function getConsultores(){
        $consultores = DB::select("select u.co_usuario as codico, u.no_usuario as nome from cao_usuario u join permissao_sistema as ps
        on u.co_usuario=ps.co_usuario and ps.co_sistema=1 and ps.in_ativo='s' and ps.co_tipo_usuario in (0,1,2)");

        $consultores = $this->toArray($consultores);
        return $consultores;
    }

    /**
     * Metodo responsavel em buscar os consultores
     * @return array[]
     */
    private function getClientes(){
        $clientes = DB::select("select co_cliente as codico, no_razao as nome from cao_cliente where tp_cliente=:tipo and no_razao!='' order by no_razao",[
            "tipo"=>"A"
        ]);

        $clientes = $this->toArray($clientes);
        return $clientes;
    }

    private function toArray($dados){
        $dados = array_map(function ($value) {
            return (array)$value;
        }, $dados);
        return $dados;
    }

    /***************************************************************************************************************/

    public function dadosDeRequisicaoConsultores(){

        $urlParams = $_GET;

        $datainit = $urlParams['datainit'];
        $dataend =  $urlParams['dataend'];
        unset($urlParams['datainit']);
        unset($urlParams['dataend']);


        $dataendSlides = explode("-",$dataend);

        // Busca de quantos dias tem o mes
        $dias=cal_days_in_month(CAL_GREGORIAN,$dataendSlides[1],$dataendSlides[0]);

        $datainit = $datainit.'-1';
        $dataend = $dataend.'-'.$dias;

        $consultores="";

        for ($i=0;$i<sizeof($urlParams);$i++){

            if($i==sizeof($urlParams)-1){
                $consultores.="'".$urlParams[$i]."'";
            }else{
                $consultores.="'".$urlParams[$i]."',";
            }
        }

        return [
            "consultores"=>$consultores,
            "inicio"=>$datainit,
            "final"=>$dataend
        ];

    }

}
