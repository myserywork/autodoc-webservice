<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Api_model');
    }

    public function index() {
        $this->response(['error' => 'Método não autorizado.'], 405);
    }

    public function atualiza_autodoc() {

        $this->atualizaConveniosWebService();

        $pendencias = $this->Api_model->getPendenciasAtualizacao();

        foreach($pendencias as $pendencia) {

            $convenio_webservice = $this->Api_model->getConveniofromWebService($pendencia['numero_convenio']);
            $convenio_webservice = json_decode($convenio_webservice[0]['dados'], true);

            if(!isset($convenio_webservice['sequencial'])) { 
                echo 'Convenio '. $pendencia['numero_convenio'] .'/'. $pendencia['ano_convenio'] .' não encontrado no webservice.';
                continue;
            }
            
            $convenio_dados_publicos = $this->Api_model->getConveniofromPublicData($pendencia['numero_convenio']);

            if(is_array($convenio_dados_publicos) && count($convenio_dados_publicos) > 0) {
                $convenio_dados_publicos = $convenio_dados_publicos[0];
            } else {
                echo 'Convenio '. $pendencia['numero_convenio'] .'/'. $pendencia['ano_convenio'] .' não encontrado nos dados públicos.';
                continue;
            }

            if(isset($convenio_dados_publicos['ID_PROPOSTA'])) {
                $proposta_dados_publicos = $this->Api_model->getPropostafromPublicData($convenio_dados_publicos['ID_PROPOSTA'])[0];
            }

            //echo '<pre>';
            //print_r($convenio_webservice);
            //echo '</pre>';

            //echo '<pre>';
            //print_r($convenio_dados_publicos);
            //echo '</pre>';

            //echo '<pre>';
            //print_r($proposta_dados_publicos);
            //echo '</pre>';
            

            //echo '<p><b>NR_CONVENIO: </b>'. $convenio_webservice['sequencial'].'</p>';
            //echo '<p><b>NR_PROPOSTA: </b>'. $convenio_webservice['numeroInterno'].'</p>';
            //echo '<p><b>MUNIC_PROPONENTE: </b>'. $convenio_webservice['dadosProponente']['municWS']['nome'].'</p>';
            //echo '<p><b>COD_MUNIC_IBGE: </b>' . $proposta_dados_publicos['COD_MUNIC_IBGE'] . '</p>';
            //echo '<p><b>COD_ORGAO_SUP: </b>'. $convenio_webservice['propostaWS']['orgaoConcedenteWS']['codigo'].'</p>';
            //echo '<p><b>DESC_ORGAO_SUP: </b>' . $proposta_dados_publicos['DESC_ORGAO_SUP'] . '</p>';
            //echo '<p><b>NATUREZA_JURIDICA: </b>'. $proposta_dados_publicos['NATUREZA_JURIDICA'].'</p>';
            //echo '<p><b>DIA_PROP: </b>'. date("d", strtotime($convenio_webservice['propostaWS']['dataPropostaWS'])).'</p>';
            //echo '<p><b>MES_PROP: </b>'. date("m", strtotime($convenio_webservice['propostaWS']['dataPropostaWS'])).'</p>';
            //echo '<p><b>ANO_PROP: </b>'. date("Y", strtotime($convenio_webservice['propostaWS']['dataPropostaWS'])).'</p>';
            //echo '<p><b>DIA_PROPOSTA: </b>'. date("d/m/Y", strtotime($convenio_webservice['propostaWS']['dataPropostaWS'])).'</p>';
            //echo '<p><b>COD_ORGAO: </b>'. $proposta_dados_publicos['COD_ORGAO'].'</p>';
            //echo '<p><b>DESC_ORGAO: </b>'. $proposta_dados_publicos['DESC_ORGAO'].'</p>';
            //echo '<p><b>MODALIDADE: </b>'. $convenio_webservice['propostaWS']['modalidadePropostaWS']['value'].'</p>';
            //echo '<p><b>NM_BANCO: </b>'. $convenio_webservice['propostaWS']['contaBancariaWS']['bancoWS']['nome'].'</p>';
            //echo '<p><b>SITUACAO_CONTA: </b>'. $convenio_webservice['propostaWS']['contaBancariaWS']['situacao'].'</p>';
            //echo '<p><b>SITUACAO_PROJETO_BASICO: </b>'. $convenio_webservice['propostaWS']['situacaoProjetoBasicoWS']['value'].'</p>';
            //echo '<p><b>SIT_PROPOSTA: </b>'. $proposta_dados_publicos['SIT_PROPOSTA'].'</p>';
            //echo '<p><b>DIA_INIC_VIGENCIA_PROPOSTA: </b>'. $convenio_webservice['propostaWS']['cronogramaFisicoWS']['metaCronogramaFisicoWS']['dataInicio'].'</p>';
            //echo '<p><b>DIA_FIM_VIGENCIA_PROPOSTA: </b>'. $convenio_webservice['propostaWS']['cronogramaFisicoWS']['metaCronogramaFisicoWS']['dataFim'].'</p>';
            //echo '<p><b>OBJETO_PROPOSTA: </b>'. $convenio_webservice['propostaWS']['objetoConvenio'].'</p>';
            //echo '<p><b>ITEM_INVESTIMENTO: </b>'. $proposta_dados_publicos['ITEM_INVESTIMENTO'].'</p>';
            //echo '<p><b>ENVIADA_MANDATARIA: </b>'. $proposta_dados_publicos['ENVIADA_MANDATARIA'].'</p>';
            //echo '<p><b>NOME_SUBTIPO_PROPOSTA: </b>'. $proposta_dados_publicos['NOME_SUBTIPO_PROPOSTA'].'</p>';
            //echo '<p><b>DESCRICAO_SUBTIPO_PROPOSTA: </b>'. $proposta_dados_publicos['DESCRICAO_SUBTIPO_PROPOSTA'].'</p>';
            //echo '<p><b>VL_GLOBAL_PROP: </b>'. $convenio_webservice['propostaWS']['valorGlobal'].'</p>';
            //echo '<p><b>VL_REPASSE_PROP: </b>'. $convenio_webservice['propostaWS']['valorRepasse'].'</p>';
            //echo '<p><b>VL_CONTRAPARTIDA_PROP: </b>'. $convenio_webservice['propostaWS']['valorContraPartida'].'</p>';
            //echo '<p><b>DIA: </b>'. date("d", strtotime($convenio_webservice['assinaturaConvenioWS']['dataAssinatura'])).'</p>';
            //echo '<p><b>MES: </b>'. date("m", strtotime($convenio_webservice['assinaturaConvenioWS']['dataAssinatura'])).'</p>';
            //echo '<p><b>ANO: </b>'. date("Y", strtotime($convenio_webservice['assinaturaConvenioWS']['dataAssinatura'])).'</p>';
            //echo '<p><b>DIA_ASSIN_CONV: </b>'. date("d/m/Y", strtotime($convenio_webservice['assinaturaConvenioWS']['dataAssinatura'])).'</p>';
            //echo '<p><b>SIT_CONVENIO: </b>'. $convenio_dados_publicos['SIT_CONVENIO'].'</p>';
            //echo '<p><b>SUBSITUACAO_CONV: </b>'.$convenio_dados_publicos['SUBSITUACAO_CONV'].'</p>';
            //echo '<p><b>SITUACAO_PUBLICACAO: </b>'. $convenio_dados_publicos['SITUACAO_PUBLICACAO'].'</p>';
            //echo '<p><b>INSTRUMENTO_ATIVO: </b>'.$convenio_dados_publicos['INSTRUMENTO_ATIVO'].'</p>';
            //echo '<p><b>IND_OPERA_OBTV: </b>' . $convenio_dados_publicos['IND_OPERA_OBTV'].'</p>'; 
            //echo '<p><b>NR_PROCESSO: </b>'. $convenio_webservice['numeroProcesso'].'</p>';
            //echo '<p><b>UG_EMITENTE: </b>'. $convenio_webservice['propostaWS']['empenhoWS']['ugEmitente'].'</p>';
            //echo '<p><b>DIA_PUBL_CONV: </b>'. $convenio_webservice['publicacaoConvenioWS']['dataPublicacao'].'</p>';
            //echo '<p><b>DIA_INIC_VIGENC_CONV: </b>'. date("d/m/Y", strtotime($convenio_webservice['inicioExecucao'])).'</p>';
            //echo '<p><b>DIA_FIM_VIGENC_CONV: </b>'. date("d/m/Y", strtotime($convenio_webservice['fimExecucao'])).'</p>';
            //echo '<p><b>DIA_FIM_VIGENC_ORIGINAL_CONV: </b>'. $convenio_dados_publicos['DIA_FIM_VIGENC_ORIGINAL_CONV'].'</p>'; 
            //echo '<p><b>DIAS_PREST_CONTAS: </b>'. $convenio_webservice['assinaturaConvenioWS']['prazoPrestacaoContasDias'].'</p>';
            //echo '<p><b>DIA_LIMITE_PREST_CONTAS: </b>'. $convenio_webservice['assinaturaConvenioWS']['dataPrestacaoContas'].'</p>';
            //echo '<p><b>DATA_SUSPENSIVA: </b>' . (isset($convenio_webservice['assinaturaConvenioWS']['celebracaoSuspensivaWS']['dataPrevisaoSuspensiva']) ? $convenio_webservice['assinaturaConvenioWS']['celebracaoSuspensivaWS']['dataPrevisaoSuspensiva'] : '').'</p>'; ;
            //echo '<p><b>DATA_RETIRADA_SUSPENSIVA: </b>' . $convenio_dados_publicos['DATA_RETIRADA_SUSPENSIVA'].'</p>'; 
            //echo '<p><b>DIAS_CLAUSULA_SUSPENSIVA: </b>' . $convenio_dados_publicos['DIAS_CLAUSULA_SUSPENSIVA'].'</p>'; 
            //echo '<p><b>SITUACAO_CONTRATACAO: </b>' . $convenio_dados_publicos['SITUACAO_CONTRATACAO'].'</p>'; 
            //echo '<p><b>IND_ASSINADO: </b>' . $convenio_dados_publicos['IND_ASSINADO'].'</p>'; 
            //echo '<p><b>MOTIVO_SUSPENSAO: </b>' . $convenio_dados_publicos['MOTIVO_SUSPENSAO'].'</p>'; 
            //echo '<p><b>IND_FOTO: </b>' . $convenio_dados_publicos['IND_FOTO'].'</p>'; 
            //echo '<p><b>QTDE_CONVENIOS: </b>' . $convenio_dados_publicos['QTDE_CONVENIOS'].'</p>'; 
            //echo '<p><b>QTD_TA: </b>'. $convenio_dados_publicos['QTD_TA'] .'</p>'; 
            //echo '<p><b>QTD_PRORROGA: </b>' .$convenio_dados_publicos['QTD_PRORROGA'] .'</p>'; 
            //echo '<p><b>VL_GLOBAL_CONV: </b>'. $convenio_webservice['propostaWS']['valorGlobal'] .'</p>'; 
            //echo '<p><b>VL_REPASSE_CONV: </b>'. $convenio_webservice['propostaWS']['valorRepasse'] .'</p>'; 
            //echo '<p><b>VL_CONTRAPARTIDA_CONV: </b>'. $convenio_webservice['propostaWS']['valorContraPartida'].'</p>'; 
            //echo '<p><b>VL_EMPENHADO_CONV: </b>'. $convenio_webservice['propostaWS']['empenhoWS']['valorOriginal'] .'</p>'; 
            //echo '<p><b>VL_DESEMBOLSADO_CONV: </b>'. $convenio_dados_publicos['VL_DESEMBOLSADO_CONV'].'</p>'; 
            //echo '<p><b>VL_SALDO_REMAN_TESOURO: </b>'. $convenio_dados_publicos['VL_SALDO_REMAN_TESOURO'].'</p>'; 
            //echo '<p><b>VL_SALDO_REMAN_CONVENENTE: </b>'. $convenio_dados_publicos['VL_SALDO_REMAN_CONVENENTE'].'</p>'; 
            //echo '<p><b>VL_RENDIMENTO_APLICACAO: </b>'. $convenio_dados_publicos['VL_RENDIMENTO_APLICACAO'].'</p>'; 
            //echo '<p><b>VL_INGRESSO_CONTRAPARTIDA: </b>'. $convenio_dados_publicos['VL_INGRESSO_CONTRAPARTIDA'].'</p>'; 
            //echo '<p><b>VL_SALDO_CONTA: </b>'. $convenio_dados_publicos['VL_SALDO_CONTA'].'</p>'; 
            //echo '<p><b>VALOR_GLOBAL_ORIGINAL_CONV: </b>'. $convenio_dados_publicos['VALOR_GLOBAL_ORIGINAL_CONV'].'</p>'; 
            //echo '<p><b>ID_PROPONENTE: </b>' . $convenio_webservice['dadosProponente']['identificacao'].'</p>';
            //echo '<p><b>IDENTIF_PROPONENTE: </b>'. $convenio_webservice['propostaWS']['proponenteWS']['identificacao'].'</p>';
            //echo '<p><b>NM_PROPONENTE: </b>'. $convenio_webservice['dadosProponente']['nome'].'</p>';
            //echo '<p><b>MUNICIPIO_PROPONENTE: </b>'. $convenio_webservice['dadosProponente']['municWS']['nome'].'</p>';
            //echo '<p><b>UF_PROPONENTE: </b>'. $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']['sigla'].'</p>';
            //echo '<p><b>ENDERECO_PROPONENTE: </b>'. $convenio_webservice['dadosProponente']['endereco'] . ' - ' . $convenio_webservice['dadosProponente']['bairroDistrito'].'. '. $convenio_webservice['dadosProponente']['municWS']['nome'] . ' - '. $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']['sigla'].'. CEP: '.$convenio_webservice['dadosProponente']['cep'].'</p>';
            //echo '<p><b>BAIRRO_PROPONENTE: </b>'. $convenio_webservice['dadosProponente']['bairroDistrito'].'</p>';
            //echo '<p><b>CEP_PROPONENTE: </b>'. $convenio_webservice['dadosProponente']['cep'].'</p>';
            //echo '<p><b>EMAIL_PROPONENTE: </b>'. $convenio_webservice['dadosProponente']['email'].'</p>';
            //echo '<p><b>TELEFONE_PROPONENTE: </b>'. $convenio_webservice['dadosProponente']['telefone'].'</p>';
            //echo '<p><b>FAX_PROPONENTE: </b>'. ((array_key_exists("telexFaxCaixaPostal", $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['telexFaxCaixaPostal'] != "") ? $convenio_webservice['dadosProponente']['telexFaxCaixaPostal'] : '').'</p>';

            $dados = array(
                'NR_CONVENIO' => (array_key_exists('sequencial', $convenio_webservice) && $convenio_webservice['sequencial'] != "") ? $convenio_webservice['sequencial'] : '',
                'NR_PROPOSTA' => (array_key_exists('numeroInterno', $convenio_webservice) && $convenio_webservice['numeroInterno'] != "") ? $convenio_webservice['numeroInterno'] : '',
                'MUNIC_PROPONENTE' => (array_key_exists('dadosProponente', $convenio_webservice) && array_key_exists('municWS', $convenio_webservice['dadosProponente']) && array_key_exists('nome', $convenio_webservice['dadosProponente']['municWS']) && $convenio_webservice['dadosProponente']['municWS']['nome'] != "") ? $convenio_webservice['dadosProponente']['municWS']['nome'] : '',
                'COD_MUNIC_IBGE' => (array_key_exists('COD_MUNIC_IBGE', $proposta_dados_publicos) && $proposta_dados_publicos['COD_MUNIC_IBGE'] != "") ? $proposta_dados_publicos['COD_MUNIC_IBGE'] : '',
                'COD_ORGAO_SUP' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('orgaoConcedenteWS', $convenio_webservice['propostaWS']) && array_key_exists('codigo', $convenio_webservice['propostaWS']['orgaoConcedenteWS']) && $convenio_webservice['propostaWS']['orgaoConcedenteWS']['codigo'] != "") ? $convenio_webservice['propostaWS']['orgaoConcedenteWS']['codigo'] : '',
                'DESC_ORGAO_SUP' => (array_key_exists('DESC_ORGAO_SUP', $proposta_dados_publicos) && $proposta_dados_publicos['DESC_ORGAO_SUP'] != "") ? $proposta_dados_publicos['DESC_ORGAO_SUP'] : '',
                'NATUREZA_JURIDICA' => (array_key_exists('NATUREZA_JURIDICA', $proposta_dados_publicos) && $proposta_dados_publicos['NATUREZA_JURIDICA'] != "") ? $proposta_dados_publicos['NATUREZA_JURIDICA'] : '',
                'DIA_PROP' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('dataPropostaWS', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['dataPropostaWS'] != "") ? date("d", strtotime($convenio_webservice['propostaWS']['dataPropostaWS'])) : '',
                'MES_PROP' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('dataPropostaWS', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['dataPropostaWS'] != "") ? date("m", strtotime($convenio_webservice['propostaWS']['dataPropostaWS'])) : '',
                'ANO_PROP' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('dataPropostaWS', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['dataPropostaWS'] != "") ? date("Y", strtotime($convenio_webservice['propostaWS']['dataPropostaWS'])) : '',
                'DIA_PROPOSTA' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('dataPropostaWS', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['dataPropostaWS'] != "") ? date("d/m/Y", strtotime($convenio_webservice['propostaWS']['dataPropostaWS'])) : '',
                'COD_ORGAO' => (array_key_exists('COD_ORGAO', $proposta_dados_publicos) && $proposta_dados_publicos['COD_ORGAO'] != "") ? $proposta_dados_publicos['COD_ORGAO'] : '',
                'DESC_ORGAO' => (array_key_exists('DESC_ORGAO', $proposta_dados_publicos) && $proposta_dados_publicos['DESC_ORGAO'] != "") ? $proposta_dados_publicos['DESC_ORGAO'] : '',
                'MODALIDADE' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('modalidadePropostaWS', $convenio_webservice['propostaWS']) && array_key_exists('value', $convenio_webservice['propostaWS']['modalidadePropostaWS']) && $convenio_webservice['propostaWS']['modalidadePropostaWS']['value'] != "") ? $convenio_webservice['propostaWS']['modalidadePropostaWS']['value'] : '',
                'NM_BANCO' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('contaBancariaWS', $convenio_webservice['propostaWS']) && array_key_exists('bancoWS', $convenio_webservice['propostaWS']['contaBancariaWS']) && array_key_exists('nome', $convenio_webservice['propostaWS']['contaBancariaWS']['bancoWS']) && $convenio_webservice['propostaWS']['contaBancariaWS']['bancoWS']['nome'] != "") ? $convenio_webservice['propostaWS']['contaBancariaWS']['bancoWS']['nome'] : '',
                'SITUACAO_CONTA' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('contaBancariaWS', $convenio_webservice['propostaWS']) && array_key_exists('situacao', $convenio_webservice['propostaWS']['contaBancariaWS']) && $convenio_webservice['propostaWS']['contaBancariaWS']['situacao'] != "") ? $convenio_webservice['propostaWS']['contaBancariaWS']['situacao'] : '',
                'SITUACAO_PROJETO_BASICO' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('situacaoProjetoBasicoWS', $convenio_webservice['propostaWS']) && array_key_exists('value', $convenio_webservice['propostaWS']['situacaoProjetoBasicoWS']) && $convenio_webservice['propostaWS']['situacaoProjetoBasicoWS']['value'] != "") ? $convenio_webservice['propostaWS']['situacaoProjetoBasicoWS']['value'] : '',
                'SIT_PROPOSTA' => (array_key_exists('SIT_PROPOSTA', $proposta_dados_publicos) && $proposta_dados_publicos['SIT_PROPOSTA'] != "") ? $proposta_dados_publicos['SIT_PROPOSTA'] : '',
                'DIA_INIC_VIGENCIA_PROPOSTA' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('cronogramaFisicoWS', $convenio_webservice['propostaWS']) && array_key_exists('metaCronogramaFisicoWS', $convenio_webservice['propostaWS']['cronogramaFisicoWS']) && array_key_exists('dataInicio', $convenio_webservice['propostaWS']['cronogramaFisicoWS']['metaCronogramaFisicoWS']) && $convenio_webservice['propostaWS']['cronogramaFisicoWS']['metaCronogramaFisicoWS']['dataInicio'] != "") ? $convenio_webservice['propostaWS']['cronogramaFisicoWS']['metaCronogramaFisicoWS']['dataInicio'] : '',
                'DIA_FIM_VIGENCIA_PROPOSTA' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('cronogramaFisicoWS', $convenio_webservice['propostaWS']) && array_key_exists('metaCronogramaFisicoWS', $convenio_webservice['propostaWS']['cronogramaFisicoWS']) && array_key_exists('dataFim', $convenio_webservice['propostaWS']['cronogramaFisicoWS']['metaCronogramaFisicoWS']) && $convenio_webservice['propostaWS']['cronogramaFisicoWS']['metaCronogramaFisicoWS']['dataFim'] != "") ? $convenio_webservice['propostaWS']['cronogramaFisicoWS']['metaCronogramaFisicoWS']['dataFim'] : '',
                'OBJETO_PROPOSTA' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('objetoConvenio', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['objetoConvenio'] != "") ? $convenio_webservice['propostaWS']['objetoConvenio'] : '',
                'ITEM_INVESTIMENTO' => (array_key_exists('ITEM_INVESTIMENTO', $proposta_dados_publicos) && $proposta_dados_publicos['ITEM_INVESTIMENTO'] != "") ? $proposta_dados_publicos['ITEM_INVESTIMENTO'] : '',
                'ENVIADA_MANDATARIA' => (array_key_exists('ENVIADA_MANDATARIA', $proposta_dados_publicos) && $proposta_dados_publicos['ENVIADA_MANDATARIA'] != "") ? $proposta_dados_publicos['ENVIADA_MANDATARIA'] : '',
                'NOME_SUBTIPO_PROPOSTA' => (array_key_exists('NOME_SUBTIPO_PROPOSTA', $proposta_dados_publicos) && $proposta_dados_publicos['NOME_SUBTIPO_PROPOSTA'] != "") ? $proposta_dados_publicos['NOME_SUBTIPO_PROPOSTA'] : '',
                'DESCRICAO_SUBTIPO_PROPOSTA' => (array_key_exists('DESCRICAO_SUBTIPO_PROPOSTA', $proposta_dados_publicos) && $proposta_dados_publicos['DESCRICAO_SUBTIPO_PROPOSTA'] != "") ? $proposta_dados_publicos['DESCRICAO_SUBTIPO_PROPOSTA'] : '',
                'VL_GLOBAL_PROP' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('valorGlobal', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['valorGlobal'] != "") ? $convenio_webservice['propostaWS']['valorGlobal'] : '',
                'VL_REPASSE_PROP' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('valorRepasse', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['valorRepasse'] != "") ? $convenio_webservice['propostaWS']['valorRepasse'] : '',
                'VL_CONTRAPARTIDA_PROP' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('valorContraPartida', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['valorContraPartida'] != "") ? $convenio_webservice['propostaWS']['valorContraPartida'] : '',
                'DIA' => (array_key_exists('assinaturaConvenioWS', $convenio_webservice) && array_key_exists('dataAssinatura', $convenio_webservice['assinaturaConvenioWS']) && $convenio_webservice['assinaturaConvenioWS']['dataAssinatura'] != "") ? date("d", strtotime($convenio_webservice['assinaturaConvenioWS']['dataAssinatura'])) : '',
                'MES' => (array_key_exists('assinaturaConvenioWS', $convenio_webservice) && array_key_exists('dataAssinatura', $convenio_webservice['assinaturaConvenioWS']) && $convenio_webservice['assinaturaConvenioWS']['dataAssinatura'] != "") ? date("m", strtotime($convenio_webservice['assinaturaConvenioWS']['dataAssinatura'])) : '',
                'ANO' => (array_key_exists('assinaturaConvenioWS', $convenio_webservice) && array_key_exists('dataAssinatura', $convenio_webservice['assinaturaConvenioWS']) && $convenio_webservice['assinaturaConvenioWS']['dataAssinatura'] != "") ? date("Y", strtotime($convenio_webservice['assinaturaConvenioWS']['dataAssinatura'])) : '',
                'DIA_ASSIN_CONV' => (array_key_exists('assinaturaConvenioWS', $convenio_webservice) && array_key_exists('dataAssinatura', $convenio_webservice['assinaturaConvenioWS']) && $convenio_webservice['assinaturaConvenioWS']['dataAssinatura'] != "") ? date("d/m/Y", strtotime($convenio_webservice['assinaturaConvenioWS']['dataAssinatura'])) : '',
                'SIT_CONVENIO' => (array_key_exists('SIT_CONVENIO', $convenio_dados_publicos) && $convenio_dados_publicos['SIT_CONVENIO'] != "") ? $convenio_dados_publicos['SIT_CONVENIO'] : '',
                'SUBSITUACAO_CONV' => (array_key_exists('SUBSITUACAO_CONV', $convenio_dados_publicos) && $convenio_dados_publicos['SUBSITUACAO_CONV'] != "") ? $convenio_dados_publicos['SUBSITUACAO_CONV'] : '',
                'SITUACAO_PUBLICACAO' => (array_key_exists('SITUACAO_PUBLICACAO', $convenio_dados_publicos) && $convenio_dados_publicos['SITUACAO_PUBLICACAO'] != "") ? $convenio_dados_publicos['SITUACAO_PUBLICACAO'] : '',
                'INSTRUMENTO_ATIVO' => (array_key_exists('INSTRUMENTO_ATIVO', $convenio_dados_publicos) && $convenio_dados_publicos['INSTRUMENTO_ATIVO'] != "") ? $convenio_dados_publicos['INSTRUMENTO_ATIVO'] : '',
                'IND_OPERA_OBTV' => (array_key_exists('IND_OPERA_OBTV', $convenio_dados_publicos) && $convenio_dados_publicos['IND_OPERA_OBTV'] != "") ? $convenio_dados_publicos['IND_OPERA_OBTV'] : '',
                'NR_PROCESSO' => (array_key_exists('numeroProcesso', $convenio_webservice) && $convenio_webservice['numeroProcesso'] != "") ? $convenio_webservice['numeroProcesso'] : '',
                'UG_EMITENTE' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('empenhoWS', $convenio_webservice['propostaWS']) && array_key_exists('ugEmitente', $convenio_webservice['propostaWS']['empenhoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['ugEmitente'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['ugEmitente'] : '',
                'DIA_PUBL_CONV' => (array_key_exists('publicacaoConvenioWS', $convenio_webservice) && array_key_exists('dataPublicacao', $convenio_webservice['publicacaoConvenioWS']) && $convenio_webservice['publicacaoConvenioWS']['dataPublicacao'] != "") ? $convenio_webservice['publicacaoConvenioWS']['dataPublicacao'] : '',
                'DIA_INIC_VIGENC_CONV' => (array_key_exists('inicioExecucao', $convenio_webservice) && $convenio_webservice['inicioExecucao'] != "") ? date("d/m/Y", strtotime($convenio_webservice['inicioExecucao'])) : '',
                'DIA_FIM_VIGENC_CONV' => (array_key_exists('fimExecucao', $convenio_webservice) && $convenio_webservice['fimExecucao'] != "") ? date("d/m/Y", strtotime($convenio_webservice['fimExecucao'])) : '',
                'DIA_FIM_VIGENC_ORIGINAL_CONV' => (array_key_exists('DIA_FIM_VIGENC_ORIGINAL_CONV', $convenio_dados_publicos) && $convenio_dados_publicos['DIA_FIM_VIGENC_ORIGINAL_CONV'] != "") ? $convenio_dados_publicos['DIA_FIM_VIGENC_ORIGINAL_CONV'] : '',
                'DIAS_PREST_CONTAS' => (array_key_exists('assinaturaConvenioWS', $convenio_webservice) && array_key_exists('prazoPrestacaoContasDias', $convenio_webservice['assinaturaConvenioWS']) && $convenio_webservice['assinaturaConvenioWS']['prazoPrestacaoContasDias'] != "") ? $convenio_webservice['assinaturaConvenioWS']['prazoPrestacaoContasDias'] : '',
                'DIA_LIMITE_PREST_CONTAS' => (array_key_exists('assinaturaConvenioWS', $convenio_webservice) && array_key_exists('dataPrestacaoContas', $convenio_webservice['assinaturaConvenioWS']) && $convenio_webservice['assinaturaConvenioWS']['dataPrestacaoContas'] != "") ? $convenio_webservice['assinaturaConvenioWS']['dataPrestacaoContas'] : '',
                'DATA_SUSPENSIVA' => (array_key_exists('assinaturaConvenioWS', $convenio_webservice) && array_key_exists('celebracaoSuspensivaWS', $convenio_webservice['assinaturaConvenioWS']) && array_key_exists('dataPrevisaoSuspensiva', $convenio_webservice['assinaturaConvenioWS']['celebracaoSuspensivaWS']) && $convenio_webservice['assinaturaConvenioWS']['celebracaoSuspensivaWS']['dataPrevisaoSuspensiva'] != "") ? $convenio_webservice['assinaturaConvenioWS']['celebracaoSuspensivaWS']['dataPrevisaoSuspensiva'] : '',
                'DATA_RETIRADA_SUSPENSIVA' => (array_key_exists('DATA_RETIRADA_SUSPENSIVA', $convenio_dados_publicos) && $convenio_dados_publicos['DATA_RETIRADA_SUSPENSIVA'] != "") ? $convenio_dados_publicos['DATA_RETIRADA_SUSPENSIVA'] : '',
                'DIAS_CLAUSULA_SUSPENSIVA' => (array_key_exists('DIAS_CLAUSULA_SUSPENSIVA', $convenio_dados_publicos) && $convenio_dados_publicos['DIAS_CLAUSULA_SUSPENSIVA'] != "") ? $convenio_dados_publicos['DIAS_CLAUSULA_SUSPENSIVA'] : '',
                'SITUACAO_CONTRATACAO' => (array_key_exists('SITUACAO_CONTRATACAO', $convenio_dados_publicos) && $convenio_dados_publicos['SITUACAO_CONTRATACAO'] != "") ? $convenio_dados_publicos['SITUACAO_CONTRATACAO'] : '',
                'IND_ASSINADO' => (array_key_exists('IND_ASSINADO', $convenio_dados_publicos) && $convenio_dados_publicos['IND_ASSINADO'] != "") ? $convenio_dados_publicos['IND_ASSINADO'] : '',
                'MOTIVO_SUSPENSAO' => (array_key_exists('MOTIVO_SUSPENSAO', $convenio_dados_publicos) && $convenio_dados_publicos['MOTIVO_SUSPENSAO'] != "") ? $convenio_dados_publicos['MOTIVO_SUSPENSAO'] : '',
                'IND_FOTO' => (array_key_exists('IND_FOTO', $convenio_dados_publicos) && $convenio_dados_publicos['IND_FOTO'] != "") ? $convenio_dados_publicos['IND_FOTO'] : '',
                'QTDE_CONVENIOS' => (array_key_exists('QTDE_CONVENIOS', $convenio_dados_publicos) && $convenio_dados_publicos['QTDE_CONVENIOS'] != "") ? $convenio_dados_publicos['QTDE_CONVENIOS'] : '',
                'QTD_TA' => (array_key_exists('QTD_TA', $convenio_dados_publicos) && $convenio_dados_publicos['QTD_TA'] != "") ? $convenio_dados_publicos['QTD_TA'] : '',
                'QTD_PRORROGA' => (array_key_exists('QTD_PRORROGA', $convenio_dados_publicos) && $convenio_dados_publicos['QTD_PRORROGA'] != "") ? $convenio_dados_publicos['QTD_PRORROGA'] : '',
                'VL_GLOBAL_CONV' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('valorGlobal', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['valorGlobal'] != "") ? $convenio_webservice['propostaWS']['valorGlobal'] : '',
                'VL_REPASSE_CONV' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('valorRepasse', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['valorRepasse'] != "") ? $convenio_webservice['propostaWS']['valorRepasse'] : '',
                'VL_CONTRAPARTIDA_CONV' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('valorContraPartida', $convenio_webservice['propostaWS']) && $convenio_webservice['propostaWS']['valorContraPartida'] != "") ? $convenio_webservice['propostaWS']['valorContraPartida'] : '',
                'VL_EMPENHADO_CONV' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('empenhoWS', $convenio_webservice['propostaWS']) && array_key_exists('valorOriginal', $convenio_webservice['propostaWS']['empenhoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['valorOriginal'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['valorOriginal'] : '',
                'VL_DESEMBOLSADO_CONV' => (array_key_exists('VL_DESEMBOLSADO_CONV', $convenio_dados_publicos) && $convenio_dados_publicos['VL_DESEMBOLSADO_CONV'] != "") ? $convenio_dados_publicos['VL_DESEMBOLSADO_CONV'] : '',
                'VL_SALDO_REMAN_TESOURO' => (array_key_exists('VL_SALDO_REMAN_TESOURO', $convenio_dados_publicos) && $convenio_dados_publicos['VL_SALDO_REMAN_TESOURO'] != "") ? $convenio_dados_publicos['VL_SALDO_REMAN_TESOURO'] : '',
                'VL_SALDO_REMAN_CONVENENTE' => (array_key_exists('VL_SALDO_REMAN_CONVENENTE', $convenio_dados_publicos) && $convenio_dados_publicos['VL_SALDO_REMAN_CONVENENTE'] != "") ? $convenio_dados_publicos['VL_SALDO_REMAN_CONVENENTE'] : '',
                'VL_RENDIMENTO_APLICACAO' => (array_key_exists('VL_RENDIMENTO_APLICACAO', $convenio_dados_publicos) && $convenio_dados_publicos['VL_RENDIMENTO_APLICACAO'] != "") ? $convenio_dados_publicos['VL_RENDIMENTO_APLICACAO'] : '',
                'VL_INGRESSO_CONTRAPARTIDA' => (array_key_exists('VL_INGRESSO_CONTRAPARTIDA', $convenio_dados_publicos) && $convenio_dados_publicos['VL_INGRESSO_CONTRAPARTIDA'] != "") ? $convenio_dados_publicos['VL_INGRESSO_CONTRAPARTIDA'] : '',
                'VL_SALDO_CONTA' => (array_key_exists('VL_SALDO_CONTA', $convenio_dados_publicos) && $convenio_dados_publicos['VL_SALDO_CONTA'] != "") ? $convenio_dados_publicos['VL_SALDO_CONTA'] : '',
                'VALOR_GLOBAL_ORIGINAL_CONV' => (array_key_exists('VALOR_GLOBAL_ORIGINAL_CONV', $convenio_dados_publicos) && $convenio_dados_publicos['VALOR_GLOBAL_ORIGINAL_CONV'] != "") ? $convenio_dados_publicos['VALOR_GLOBAL_ORIGINAL_CONV'] : '',
                'ID_PROPONENTE' => (array_key_exists('dadosProponente', $convenio_webservice) && array_key_exists('identificacao', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['identificacao'] != "") ? $convenio_webservice['dadosProponente']['identificacao'] : '',
                'IDENTIF_PROPONENTE' => (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('proponenteWS', $convenio_webservice['propostaWS']) && array_key_exists('identificacao', $convenio_webservice['propostaWS']['proponenteWS']) && $convenio_webservice['propostaWS']['proponenteWS']['identificacao'] != "") ? $convenio_webservice['propostaWS']['proponenteWS']['identificacao'] : '',
                'NM_PROPONENTE' => (array_key_exists('dadosProponente', $convenio_webservice) && array_key_exists('nome', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['nome'] != "") ? $convenio_webservice['dadosProponente']['nome'] : '',
                'MUNICIPIO_PROPONENTE' => (array_key_exists('dadosProponente', $convenio_webservice) && array_key_exists('municWS', $convenio_webservice['dadosProponente']) && array_key_exists('nome', $convenio_webservice['dadosProponente']['municWS']) && $convenio_webservice['dadosProponente']['municWS']['nome'] != "") ? $convenio_webservice['dadosProponente']['municWS']['nome'] : '',
                'UF_PROPONENTE' => (array_key_exists('dadosProponente', $convenio_webservice) && array_key_exists('municWS', $convenio_webservice['dadosProponente']) && array_key_exists('unidadeFederativaWS', $convenio_webservice['dadosProponente']['municWS']) && array_key_exists('sigla', $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']) && $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']['sigla'] != "") ? $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']['sigla'] : '',
                'ENDERECO_PROPONENTE' => (array_key_exists('dadosProponente', $convenio_webservice) && array_key_exists('endereco', $convenio_webservice['dadosProponente']) && array_key_exists('bairroDistrito', $convenio_webservice['dadosProponente']) && array_key_exists('municWS', $convenio_webservice['dadosProponente']) && array_key_exists('nome', $convenio_webservice['dadosProponente']['municWS']) && array_key_exists('unidadeFederativaWS', $convenio_webservice['dadosProponente']['municWS']) && array_key_exists('sigla', $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']) && array_key_exists('cep', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['endereco'] != "" && $convenio_webservice['dadosProponente']['bairroDistrito'] != "" && $convenio_webservice['dadosProponente']['municWS']['nome'] != "" && $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']['sigla'] != "" && $convenio_webservice['dadosProponente']['cep'] != "") ? $convenio_webservice['dadosProponente']['endereco'] . ' - ' . $convenio_webservice['dadosProponente']['bairroDistrito'].'. '. $convenio_webservice['dadosProponente']['municWS']['nome'] . ' - '. $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']['sigla'].'. CEP: '.$convenio_webservice['dadosProponente']['cep'] : '',
                'BAIRRO_PROPONENTE' => (array_key_exists('dadosProponente', $convenio_webservice) && array_key_exists('bairroDistrito', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['bairroDistrito'] != "") ? $convenio_webservice['dadosProponente']['bairroDistrito'] : '',
                'CEP_PROPONENTE' => (array_key_exists('cep', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['cep'] != "") ? $convenio_webservice['dadosProponente']['cep'] : '',
                'EMAIL_PROPONENTE' => (array_key_exists('email', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['email'] != "") ? $convenio_webservice['dadosProponente']['email'] : '',
                'TELEFONE_PROPONENTE' => (array_key_exists('telefone', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['telefone'] != "") ? $convenio_webservice['dadosProponente']['telefone'] : '',
                'FAX_PROPONENTE' => (array_key_exists('telexFaxCaixaPostal', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['telexFaxCaixaPostal'] != "") ? $convenio_webservice['dadosProponente']['telexFaxCaixaPostal'] : '',
            );

            /* Dados de Empenho */
            if (array_key_exists('propostaWS', $convenio_webservice) && array_key_exists('empenhoWS', $convenio_webservice['propostaWS'])) {
                $dados['EMPENHO_ano'] = (array_key_exists('ano', $convenio_webservice['propostaWS']['empenhoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['ano'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['ano'] : '';
                $dados['EMPENHO_codigoFonteRecurso'] = (array_key_exists('celulaOrcamentariaWS', $convenio_webservice['propostaWS']['empenhoWS']) && array_key_exists('codigoFonteRecurso', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']) && $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['codigoFonteRecurso'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['codigoFonteRecurso'] : '';
                $dados['EMPENHO_codigoNaturezaDespesa'] = (array_key_exists('celulaOrcamentariaWS', $convenio_webservice['propostaWS']['empenhoWS']) && array_key_exists('codigoNaturezaDespesa', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']) && $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['codigoNaturezaDespesa'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['codigoNaturezaDespesa'] : '';
                $dados['EMPENHO_codigoPlanoInterno'] = (array_key_exists('celulaOrcamentariaWS', $convenio_webservice['propostaWS']['empenhoWS']) && array_key_exists('codigoPlanoInterno', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']) && $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['codigoPlanoInterno'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['codigoPlanoInterno'] : '';
                $dados['EMPENHO_codigoAutorEmenda'] = (array_key_exists('celulaOrcamentariaWS', $convenio_webservice['propostaWS']['empenhoWS']) && array_key_exists('programaTrabalhoResumidoWS', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']) && array_key_exists('codigoAutorEmenda', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['codigoAutorEmenda'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['codigoAutorEmenda'] : '';
                $dados['EMPENHO_programaTrabalho'] = (array_key_exists('celulaOrcamentariaWS', $convenio_webservice['propostaWS']['empenhoWS']) && array_key_exists('programaTrabalhoResumidoWS', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']) && array_key_exists('programaTrabalho', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['programaTrabalho'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['programaTrabalho'] : '';
                $dados['EMPENHO_programaTrabalhoResumido'] = (array_key_exists('celulaOrcamentariaWS', $convenio_webservice['propostaWS']['empenhoWS']) && array_key_exists('programaTrabalhoResumidoWS', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']) && array_key_exists('programaTrabalhoResumido', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['programaTrabalhoResumido'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['programaTrabalhoResumido'] : '';
                $dados['EMPENHO_resultadoPrimario'] = (array_key_exists('celulaOrcamentariaWS', $convenio_webservice['propostaWS']['empenhoWS']) && array_key_exists('programaTrabalhoResumidoWS', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']) && array_key_exists('resultadoPrimario', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['resultadoPrimario'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['resultadoPrimario'] : '';
                $dados['EMPENHO_unidadeOrcamentaria'] = (array_key_exists('celulaOrcamentariaWS', $convenio_webservice['propostaWS']['empenhoWS']) && array_key_exists('programaTrabalhoResumidoWS', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']) && array_key_exists('unidadeOrcamentaria', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['unidadeOrcamentaria'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['programaTrabalhoResumidoWS']['unidadeOrcamentaria'] : '';
                $dados['EMPENHO_ugResponsavel'] = (array_key_exists('celulaOrcamentariaWS', $convenio_webservice['propostaWS']['empenhoWS']) && array_key_exists('ugResponsavel', $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']) && $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['ugResponsavel'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['celulaOrcamentariaWS']['ugResponsavel'] : '';
                $dados['EMPENHO_numeroEmpenho'] = (array_key_exists('numeroEmpenho', $convenio_webservice['propostaWS']['empenhoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['numeroEmpenho'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['numeroEmpenho'] : '';
                $dados['EMPENHO_situacaoEmpenho'] = (array_key_exists('situacaoEmpenho', $convenio_webservice['propostaWS']['empenhoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['situacaoEmpenho'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['situacaoEmpenho'] : '';
                $dados['EMPENHO_ugEmitenteEmpenho'] = (array_key_exists('ugEmitente', $convenio_webservice['propostaWS']['empenhoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['ugEmitente'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['ugEmitente'] : '';
                $dados['EMPENHO_valorOriginal'] = (array_key_exists('valorOriginal', $convenio_webservice['propostaWS']['empenhoWS']) && $convenio_webservice['propostaWS']['empenhoWS']['valorOriginal'] != "") ? $convenio_webservice['propostaWS']['empenhoWS']['valorOriginal'] : '';
            }

            if (array_key_exists('dadosProponente', $convenio_webservice)) {
                $dados['PROPONENTE_bairroDistrito'] = (array_key_exists('bairroDistrito', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['bairroDistrito'] != "") ? $convenio_webservice['dadosProponente']['bairroDistrito'] : '';
                $dados['PROPONENTE_cep'] = (array_key_exists('cep', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['cep'] != "") ? $convenio_webservice['dadosProponente']['cep'] : '';
                $dados['PROPONENTE_endereco'] = (array_key_exists('endereco', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['endereco'] != "") ? $convenio_webservice['dadosProponente']['endereco'] : '';
                $dados['PROPONENTE_email'] = (array_key_exists('email', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['email'] != "") ? $convenio_webservice['dadosProponente']['email'] : '';
                $dados['PROPONENTE_esferaAdministrativa'] = (array_key_exists('esferaAdministrativaWS', $convenio_webservice['dadosProponente']) && array_key_exists('codigo', $convenio_webservice['dadosProponente']['esferaAdministrativaWS']) && $convenio_webservice['dadosProponente']['esferaAdministrativaWS']['codigo'] != "") ? $convenio_webservice['dadosProponente']['esferaAdministrativaWS']['codigo'] : '';
                $dados['PROPONENTE_municipio'] = (array_key_exists('municWS', $convenio_webservice['dadosProponente']) && array_key_exists('nome', $convenio_webservice['dadosProponente']['municWS']) && $convenio_webservice['dadosProponente']['municWS']['nome'] != "") ? $convenio_webservice['dadosProponente']['municWS']['nome'] : '';
                $dados['PROPONENTE_uf'] = (array_key_exists('municWS', $convenio_webservice['dadosProponente']) && array_key_exists('unidadeFederativaWS', $convenio_webservice['dadosProponente']['municWS']) && array_key_exists('sigla', $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']) && $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']['sigla'] != "") ? $convenio_webservice['dadosProponente']['municWS']['unidadeFederativaWS']['sigla'] : '';
                $dados['PROPONENTE_nome'] = (array_key_exists('nome', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['nome'] != "") ? $convenio_webservice['dadosProponente']['nome'] : '';
                $dados['PROPONENTE_nomeFantasia'] = (array_key_exists('nomeFantasia', $convenio_webservice['dadosProponente']) && $convenio_webservice['dadosProponente']['nomeFantasia'] != "") ? $convenio_webservice['dadosProponente']['nomeFantasia'] : '';
                $dados['PROPONENTE_respExercicio_cpf'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('cpf', $convenio_webservice['dadosProponente']['respExercicioWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['cpf'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['cpf'] : '';
                $dados['PROPONENTE_respExercicio_cargoFuncao'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('cargoFuncao', $convenio_webservice['dadosProponente']['respExercicioWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['cargoFuncao'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['cargoFuncao'] : '';
                $dados['PROPONENTE_respExercicio_email'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('email', $convenio_webservice['dadosProponente']['respExercicioWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['email'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['email'] : '';
                $dados['PROPONENTE_respExercicio_nome'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('nome', $convenio_webservice['dadosProponente']['respExercicioWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['nome'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['nome'] : '';
                $dados['PROPONENTE_respExercicio_orgaoExpedidor'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('orgaoExpedidor', $convenio_webservice['dadosProponente']['respExercicioWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['orgaoExpedidor'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['orgaoExpedidor'] : '';
                $dados['PROPONENTE_respExercicio_rg'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('rg', $convenio_webservice['dadosProponente']['respExercicioWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['rg'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['rg'] : '';
                $dados['PROPONENTE_respExercicio_endereco'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('endereco', $convenio_webservice['dadosProponente']['respExercicioWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['endereco'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['endereco'] : '';
                $dados['PROPONENTE_respExercicio_cep'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('cep', $convenio_webservice['dadosProponente']['respExercicioWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['cep'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['cep'] : '';
                $dados['PROPONENTE_respExercicio_municipioMembro'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('municipioMembroWS', $convenio_webservice['dadosProponente']['respExercicioWS']) && array_key_exists('nome', $convenio_webservice['dadosProponente']['respExercicioWS']['municipioMembroWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['municipioMembroWS']['nome'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['municipioMembroWS']['nome'] : '';
                $dados['PROPONENTE_respExercicio_municipioMembro_uf'] = (array_key_exists('respExercicioWS', $convenio_webservice['dadosProponente']) && array_key_exists('municipioMembroWS', $convenio_webservice['dadosProponente']['respExercicioWS']) && array_key_exists('unidadeFederativaWS', $convenio_webservice['dadosProponente']['respExercicioWS']['municipioMembroWS']) && array_key_exists('sigla', $convenio_webservice['dadosProponente']['respExercicioWS']['municipioMembroWS']['unidadeFederativaWS']) && $convenio_webservice['dadosProponente']['respExercicioWS']['municipioMembroWS']['unidadeFederativaWS']['sigla'] != "") ? $convenio_webservice['dadosProponente']['respExercicioWS']['municipioMembroWS']['unidadeFederativaWS']['sigla'] : '';
            }

            $envio = $this->postCurl($pendencia['returnUrl'], array('API-Key' => 'chaveCerta'), $dados);
            
            if(isset(json_decode($envio)->error)) {

                echo 'Atualizando Falha pendencia '.$pendencia['id'].'<br>';
                $data = array(
                    'status' => 'Falha',
                    'resposta' => $envio
                );    
                $this->Api_model->updateSolicitacoesWhere($data, "id = '" . $pendencia['id']."'");
            } else {
                echo 'Atualizando Enviado pendencia '.$pendencia['id'].'<br>';
                $data = array(
                    'status' => 'Enviado',
                    'resposta' => $envio
                );    
                $this->Api_model->updateSolicitacoesWhere($data, "id = '" . $pendencia['id']."'");
            }
        }
    }

    private function postCurl($url, $headers, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_HEADER, false);
    
        // Preparar os headers para o formato correto
        $formattedHeaders = [];
        foreach ($headers as $key => $value) {
            $formattedHeaders[] = "$key: $value";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $formattedHeaders);
    
        curl_setopt($ch, CURLOPT_POST, true);
    
        // Se os dados são um array, usamos http_build_query para assegurar a correta codificação
        if (is_array($data)) {
            $data = http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
        $output = curl_exec($ch);
        if (curl_errno($ch)) { // Verificação de erro do cURL
            throw new Exception(curl_error($ch));
        }
        $curl_info = curl_getinfo($ch);
        curl_close($ch);
        
        return $output;
    }

    public function atualizaConveniosWebService() {

        $pendencias = $this->Api_model->getPendenciasAtualizacao();

        $msg = "";

        foreach($pendencias as $pendencia) {
            $convenio = $this->Api_model->getConvenio($pendencia['numero_convenio'], $pendencia['ano_convenio'], '22000');

            $convenio = $this->soapXmlToArray($convenio);

            if ($convenio && isset($convenio['env:Body']['ns2:exportaConvenioResponse'])) {

                
                $convenio = $convenio['env:Body']['ns2:exportaConvenioResponse']['return'];

                $idendificacaoProponente = $convenio['propostaWS']['proponenteWS']['identificacao'];
                $tipoIdentificacaoProponente = $convenio['propostaWS']['proponenteWS']['tipoIdentificacaoWS']['codigo'];

                sleep(5);

                $proponente = $this->Api_model->getProponente($idendificacaoProponente, $tipoIdentificacaoProponente);
                $proponente = $this->soapXmlToArray($proponente);

                if ($proponente && isset($proponente['env:Body']['ns2:exportaProponenteResponse'])) {
                    
                    $proponente = $proponente['env:Body']['ns2:exportaProponenteResponse']['return'];
                    $convenio['dadosProponente'] = $proponente;                    
                } else {                    
                    $msg .= "Erro ao resgatar proponente.<br>";
                }
                $dados = array(
                    "nr_convenio" => $pendencia['numero_convenio'],
                    "ano_convenio" => $pendencia['ano_convenio'],
                    "dados" => json_encode($convenio)
                );
                if($this->Api_model->upsert_convenio_webservice($dados)) {
                    $msg .= "Convenio ".$pendencia['numero_convenio']." atualizado com sucesso.<br>";
                } else {
                    $msg .= "Erro ao atualizar convenio ".$pendencia['numero_convenio'].".<br>";
                }
            } else {
                $msg .= "Erro ao resgatar convenio ".$pendencia['numero_convenio'].".<br>";
            }
            sleep(5);
        }
        return;
        //$this->response(['success' => $msg], 200);
    }

    public function requestConvenios() {
        $post = json_decode($this->security->xss_clean($this->input->raw_input_stream));

        if(!isset($post->convenios)) {
            $this->response(['error' => 'Convenios não informados.'], 400);
        }

        $convenios = json_decode($post->convenios);

        if(is_array($convenios) || is_object($convenios)) {
            foreach($convenios as $convenio) {
                $data = array(
                    "numero_convenio" => $convenio->numero_convenio,
                    "ano_convenio" => $convenio->ano_convenio,
                    "returnUrl" => $post->returnUrl
                );
                $this->Api_model->upsert_solicitacoes_atualizacao($data);
            }
        } else {
            $this->response(['error' => 'Convenios não informados.'], 400);
        }
        $this->response(['success' => 'Solicitação de atualização de convenios realizada com sucesso.'], 200);
    }

    public function downloadPublicData_full() {

        $caminho_xml = explode("\\", dirname(__DIR__, 1));
        array_pop($caminho_xml);
        array_pop($caminho_xml);
        $caminho_xml = implode("\\", $caminho_xml);
        $baseDir = $caminho_xml.'/tmp/';

        $zip_files = ['siconv_convenio.zip', 'siconv_proposta.zip', 'siconv_empenho.csv.zip'];
        $csv_filenames = ['siconv_convenio.csv', 'siconv_proposta.csv', 'siconv_empenho.csv'];
        $urls = [
            'https://repositorio.dados.gov.br/seges/detru/siconv_convenio.csv.zip',
            'https://repositorio.dados.gov.br/seges/detru/siconv_proposta.csv.zip',
            'https://repositorio.dados.gov.br/seges/detru/siconv_empenho.csv.zip'
        ];

        foreach (array_merge($zip_files, $csv_filenames) as $file) {
            $filePath = $baseDir . $file;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        foreach ($urls as $index => $url) {
            $zip_file = $baseDir . $zip_files[$index];
            $csv_filename = $csv_filenames[$index];
            $csv_filename_dir = $baseDir . $csv_filenames[$index];
            file_put_contents($zip_file, fopen($url, 'r'));

            $zip = new ZipArchive;
            if ($zip->open($zip_file) === TRUE) {
                $zip->extractTo($baseDir, $csv_filename);
                $zip->close();
                unlink($zip_file);
            }
        }

        $filePath = $baseDir . 'siconv_convenio.csv';
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $i = 0;
            $header = array();
            while ($line = fgets($handle)) {
                if($i == 0) {
                    $header = explode(';', $line);
                } else {
                    $data = explode(';', preg_replace('/"([^"]*)"/','',$line));
                    $dataAssoc = array_combine($header, $data);
                    if ($dataAssoc === false) {
                        continue;
                    }
                    $dataAssoc = $this->cleanKeys($dataAssoc);
                    $formattedData = $this->prepareDataConvenio($dataAssoc);
                    $this->Api_model->upsert_convenio($formattedData);
                }
                $i++;
            }
            fclose($handle);
            echo 'Dados de convenios inseridos com sucesso<br>';
            echo 'Foram inseridos: '.$i.' registros<br>';
            echo 'Memória utilizada: ' .memory_get_peak_usage() / 1024 / 1024, ' MB<br><br>';

        }

        $filePath = $baseDir . 'siconv_proposta.csv';
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $i = 0;
            $header = array();
            while ($line = fgets($handle)) {
                if($i == 0) {
                    $header = explode(';', $line);
                } else {
                    $data = explode(';', preg_replace('/"([^"]*)"/','',$line));
                    $dataAssoc = array_combine($header, $data);
                    if ($dataAssoc === false) {
                        continue;
                    }
                    $dataAssoc = $this->cleanKeys($dataAssoc);
                    if($dataAssoc['COD_ORGAO_SUP'] == '22000') {
                        $formattedData = $this->prepareDataProposta($dataAssoc);
                        $this->Api_model->upsert_proposta($formattedData);
                    }
                }
                $i++;
            }
            fclose($handle);
            echo 'Dados de propostas inseridos com sucesso<br>';
            echo 'Foram inseridos: '.$i.' registros<br>';
            echo 'Memória utilizada: ' .memory_get_peak_usage() / 1024 / 1024, ' MB<br>';
        }
        
        echo 'Data inserted successfully';
    }

    public function downloadPublicData() {

        $caminho_xml = explode("\\", dirname(__DIR__, 1));
        array_pop($caminho_xml);
        array_pop($caminho_xml);
        $caminho_xml = implode("\\", $caminho_xml);
        $baseDir = $caminho_xml.'/tmp/';

        $zip_files = ['siconv_convenio.zip', 'siconv_proposta.zip', 'siconv_empenho.csv.zip'];
        $csv_filenames = ['siconv_convenio.csv', 'siconv_proposta.csv', 'siconv_empenho.csv'];
        $urls = [
            'https://repositorio.dados.gov.br/seges/detru/siconv_convenio.csv.zip',
            'https://repositorio.dados.gov.br/seges/detru/siconv_proposta.csv.zip',
            'https://repositorio.dados.gov.br/seges/detru/siconv_empenho.csv.zip'
        ];

        foreach (array_merge($zip_files, $csv_filenames) as $file) {
            $filePath = $baseDir . $file;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        foreach ($urls as $index => $url) {
            $zip_file = $baseDir . $zip_files[$index];
            $csv_filename = $csv_filenames[$index];
            $csv_filename_dir = $baseDir . $csv_filenames[$index];
            file_put_contents($zip_file, fopen($url, 'r'));

            $zip = new ZipArchive;
            if ($zip->open($zip_file) === TRUE) {
                $zip->extractTo($baseDir, $csv_filename);
                $zip->close();
                unlink($zip_file);
            }
        }
        echo 'Downloaded!';        
    }

    public function processPublicData_convenios() {

        $caminho_xml = explode("\\", dirname(__DIR__, 1));
        array_pop($caminho_xml);
        array_pop($caminho_xml);
        $caminho_xml = implode("\\", $caminho_xml);
        $baseDir = $caminho_xml.'/tmp/';

        $filePath = $baseDir . 'siconv_convenio.csv';
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $i = 0;
            $header = array();
            while ($line = fgets($handle)) {
                if($i == 0) {
                    $header = explode(';', $line);
                } else {
                    $data = explode(';', preg_replace('/"([^"]*)"/','',$line));
                    $dataAssoc = array_combine($header, $data);
                    if ($dataAssoc === false) {
                        continue;
                    }
                    $dataAssoc = $this->cleanKeys($dataAssoc);
                    $formattedData = $this->prepareDataConvenio($dataAssoc);
                    $this->Api_model->upsert_convenio($formattedData);
                }
                $i++;
            }
            fclose($handle);
            echo 'Dados de convenios inseridos com sucesso<br>';
            echo 'Foram inseridos: '.$i.' registros<br>';
            echo 'Memória utilizada: ' .memory_get_peak_usage() / 1024 / 1024, ' MB<br><br>';

        }        
        echo 'Data inserted successfully';
    }


    public function processPublicData_propostas() {

        $caminho_xml = explode("\\", dirname(__DIR__, 1));
        array_pop($caminho_xml);
        array_pop($caminho_xml);
        $caminho_xml = implode("\\", $caminho_xml);
        $baseDir = $caminho_xml.'/tmp/';

        $filePath = $baseDir . 'siconv_proposta.csv';
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $i = 0;
            $header = array();
            while ($line = fgets($handle)) {
                if($i == 0) {
                    $header = explode(';', $line);
                } else {
                    $data = explode(';', preg_replace('/"([^"]*)"/','',$line));
                    $dataAssoc = array_combine($header, $data);
                    if ($dataAssoc === false) {
                        continue;
                    }
                    $dataAssoc = $this->cleanKeys($dataAssoc);
                    if($dataAssoc['COD_ORGAO_SUP'] == '22000') {
                        $formattedData = $this->prepareDataProposta($dataAssoc);
                        $this->Api_model->upsert_proposta($formattedData);
                    }
                }
                $i++;
            }
            fclose($handle);
            echo 'Dados de propostas inseridos com sucesso<br>';
            echo 'Foram inseridos: '.$i.' registros<br>';
            echo 'Memória utilizada: ' .memory_get_peak_usage() / 1024 / 1024, ' MB<br>';

        }
    }

    public function processPublicData_empenhos() {

        $caminho_xml = explode("\\", dirname(__DIR__, 1));
        array_pop($caminho_xml);
        array_pop($caminho_xml);
        $caminho_xml = implode("\\", $caminho_xml);
        $baseDir = $caminho_xml.'/tmp/';

        $filePath = $baseDir . 'siconv_empenho.csv';
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $i = 0;
            $header = array();
            while ($line = fgets($handle)) {
                if($i == 0) {
                    $header = explode(';', $line);
                } else {
                    $data = explode(';', preg_replace('/"([^"]*)"/','',$line));
                    $dataAssoc = array_combine($header, $data);
                    if ($dataAssoc === false) {
                        continue;
                    }
                    $dataAssoc = $this->cleanKeys($dataAssoc);
                    $formattedData = $this->prepareDataEmpenho($dataAssoc);
                    $this->Api_model->upsert_empenhos($formattedData);
                }
                $i++;
            }
            fclose($handle);
            echo 'Dados de empenhos inseridos com sucesso<br>';
            echo 'Foram inseridos: '.$i.' registros<br>';
            echo 'Memória utilizada: ' .memory_get_peak_usage() / 1024 / 1024, ' MB<br>';

        }
    }

    private function cleanKeys($array) {
        $cleanArray = [];
        foreach ($array as $key => $value) {
            $cleanKey = trim($key); // Remove espaços em branco no início e no fim
            $cleanKey = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $cleanKey); // Remove caracteres de controle e estendidos
            $cleanArray[$cleanKey] = $value;
        }
        return $cleanArray;
    }    

    private function prepareDataConvenio($data) {
        // Prepara os dados conforme a necessidade antes de inserir no banco de dados
        // Por exemplo, converter datas e números, ajustar formatação etc.
        return [
            'NR_CONVENIO' => $data['NR_CONVENIO'],
            'ID_PROPOSTA' => $data['ID_PROPOSTA'],
            'DIA' => $data['DIA'],
            'MES' => $data['MES'],
            'ANO' => $data['ANO'],
            'DIA_ASSIN_CONV' => $data['DIA_ASSIN_CONV'],
            'SIT_CONVENIO' => $data['SIT_CONVENIO'],
            'SUBSITUACAO_CONV' => $data['SUBSITUACAO_CONV'],
            'SITUACAO_PUBLICACAO' => $data['SITUACAO_PUBLICACAO'],
            'INSTRUMENTO_ATIVO' => $data['INSTRUMENTO_ATIVO'],
            'IND_OPERA_OBTV' => $data['IND_OPERA_OBTV'],
            'NR_PROCESSO' => $data['NR_PROCESSO'],
            'UG_EMITENTE' => $data['UG_EMITENTE'],
            'DIA_PUBL_CONV' => $data['DIA_PUBL_CONV'],
            'DIA_INIC_VIGENC_CONV' => $data['DIA_INIC_VIGENC_CONV'],
            'DIA_FIM_VIGENC_CONV' => $data['DIA_FIM_VIGENC_CONV'],
            'DIA_FIM_VIGENC_ORIGINAL_CONV' => $data['DIA_FIM_VIGENC_ORIGINAL_CONV'],
            'DIAS_PREST_CONTAS' => $data['DIAS_PREST_CONTAS'],
            'DIA_LIMITE_PREST_CONTAS' => $data['DIA_LIMITE_PREST_CONTAS'],
            'DATA_SUSPENSIVA' => $data['DATA_SUSPENSIVA'],
            'DATA_RETIRADA_SUSPENSIVA' => $data['DATA_RETIRADA_SUSPENSIVA'],
            'DIAS_CLAUSULA_SUSPENSIVA' => $data['DIAS_CLAUSULA_SUSPENSIVA'],
            'SITUACAO_CONTRATACAO' => $data['SITUACAO_CONTRATACAO'],
            'IND_ASSINADO' => $data['IND_ASSINADO'],
            'MOTIVO_SUSPENSAO' => $data['MOTIVO_SUSPENSAO'],
            'IND_FOTO' => $data['IND_FOTO'],
            'QTDE_CONVENIOS' => $data['QTDE_CONVENIOS'],
            'QTD_TA' => $data['QTD_TA'],
            'QTD_PRORROGA' => $data['QTD_PRORROGA'],
            'VL_GLOBAL_CONV' => $data['VL_GLOBAL_CONV'],
            'VL_REPASSE_CONV' => $data['VL_REPASSE_CONV'],
            'VL_CONTRAPARTIDA_CONV' => $data['VL_CONTRAPARTIDA_CONV'],
            'VL_EMPENHADO_CONV' => $data['VL_EMPENHADO_CONV'],
            'VL_DESEMBOLSADO_CONV' => $data['VL_DESEMBOLSADO_CONV'],
            'VL_SALDO_REMAN_TESOURO' => $data['VL_SALDO_REMAN_TESOURO'],
            'VL_SALDO_REMAN_CONVENENTE' => $data['VL_SALDO_REMAN_CONVENENTE'],
            'VL_RENDIMENTO_APLICACAO' => $data['VL_RENDIMENTO_APLICACAO'],
            'VL_INGRESSO_CONTRAPARTIDA' => $data['VL_INGRESSO_CONTRAPARTIDA'],
            'VL_SALDO_CONTA' => $data['VL_SALDO_CONTA'],
            'VALOR_GLOBAL_ORIGINAL_CONV' => $data['VALOR_GLOBAL_ORIGINAL_CONV']
        ];
    }

    private function prepareDataProposta($data) {
        // Prepara os dados conforme a necessidade antes de inserir no banco de dados
        // Por exemplo, converter datas e números, ajustar formatação etc.
        return [
            'ID_PROPOSTA' => $data['ID_PROPOSTA'],
            'UF_PROPONENTE' => $data['UF_PROPONENTE'],
            'MUNIC_PROPONENTE' => $data['MUNIC_PROPONENTE'],
            'COD_MUNIC_IBGE' => $data['COD_MUNIC_IBGE'],
            'COD_ORGAO_SUP' => $data['COD_ORGAO_SUP'],
            'DESC_ORGAO_SUP' => $data['DESC_ORGAO_SUP'],
            'NATUREZA_JURIDICA' => $data['NATUREZA_JURIDICA'],
            'NR_PROPOSTA' => $data['NR_PROPOSTA'],
            'DIA_PROP' => $data['DIA_PROP'],
            'MES_PROP' => $data['MES_PROP'],
            'ANO_PROP' => $data['ANO_PROP'],
            'DIA_PROPOSTA' => $data['DIA_PROPOSTA'],
            'COD_ORGAO' => $data['COD_ORGAO'],
            'DESC_ORGAO' => $data['DESC_ORGAO'],
            'MODALIDADE' => $data['MODALIDADE'],
            'IDENTIF_PROPONENTE' => $data['IDENTIF_PROPONENTE'],
            'NM_PROPONENTE' => $data['NM_PROPONENTE'],
            'CEP_PROPONENTE' => $data['CEP_PROPONENTE'],
            'ENDERECO_PROPONENTE' => $data['ENDERECO_PROPONENTE'],
            'BAIRRO_PROPONENTE' => $data['BAIRRO_PROPONENTE'],
            'NM_BANCO' => $data['NM_BANCO'],
            'SITUACAO_CONTA' => $data['SITUACAO_CONTA'],
            'SITUACAO_PROJETO_BASICO' => $data['SITUACAO_PROJETO_BASICO'],
            'SIT_PROPOSTA' => $data['SIT_PROPOSTA'],
            'DIA_INIC_VIGENCIA_PROPOSTA' => $data['DIA_INIC_VIGENCIA_PROPOSTA'],
            'DIA_FIM_VIGENCIA_PROPOSTA' => $data['DIA_FIM_VIGENCIA_PROPOSTA'],
            'OBJETO_PROPOSTA' => $data['OBJETO_PROPOSTA'],
            'ITEM_INVESTIMENTO' => $data['ITEM_INVESTIMENTO'],
            'ENVIADA_MANDATARIA' => $data['ENVIADA_MANDATARIA'],
            'NOME_SUBTIPO_PROPOSTA' => $data['NOME_SUBTIPO_PROPOSTA'],
            'DESCRICAO_SUBTIPO_PROPOSTA' => $data['DESCRICAO_SUBTIPO_PROPOSTA'],
            'VL_GLOBAL_PROP' => $data['VL_GLOBAL_PROP'],
            'VL_REPASSE_PROP' => $data['VL_REPASSE_PROP'],
            'VL_CONTRAPARTIDA_PROP' => $data['VL_CONTRAPARTIDA_PROP']
        ];
    }
    
    private function prepareDataEmpenho($data) {
        // Prepara os dados conforme a necessidade antes de inserir no banco de dados
        // Por exemplo, converter datas e números, ajustar formatação etc.
        return [
            'ID_PROPOSTA' => $data['ID_PROPOSTA'],
            'ID_EMPENHO' => $data['ID_EMPENHO'],
            'NR_CONVENIO' => $data['NR_CONVENIO'],
            'NR_EMPENHO' => $data['NR_EMPENHO'],
            'TIPO_NOTA' => $data['TIPO_NOTA'],
            'DESC_TIPO_NOTA' => $data['DESC_TIPO_NOTA'],
            'DATA_EMISSAO' => $data['DATA_EMISSAO'],
            'COD_SITUACAO_EMPENHO' => $data['COD_SITUACAO_EMPENHO'],
            'DESC_SITUACAO_EMPENHO' => $data['DESC_SITUACAO_EMPENHO'],
            'UG_EMITENTE' => $data['UG_EMITENTE'],
            'UG_RESPONSAVEL' => $data['UG_RESPONSAVEL'],
            'FONTE_RECURSO' => $data['FONTE_RECURSO'],
            'NATUREZA_DESPESA' => $data['NATUREZA_DESPESA'],
            'PLANO_INTERNO' => $data['PLANO_INTERNO'],
            'PTRES' => $data['PTRES'],
            'VALOR_EMPENHO' => $data['VALOR_EMPENHO']
        ];
    }

    public function gerarAlertas() {
        $convenios_a_vencer_30 = $this->Api_model->getConveniosAVencer(0,30);
        $convenios_a_vencer_60 = $this->Api_model->getConveniosAVencer(30,60);
        $convenios_a_vencer_90 = $this->Api_model->getConveniosAVencer(60,90);

        $alertas = [];

        foreach($convenios_a_vencer_30 as $convenio) {
            $alertas[] = [
                'titulo' => 'O convênio '.$convenio['NR_CONVENIO'].' vencerá em até 30 dias. Início: '.$convenio['DIA_INIC_VIGENC_CONV'].' - Fim: '.$convenio['DIA_FIM_VIGENC_CONV'],
                'data' => date('Y-m-d H:i:s'),
                'convenio' => $convenio['NR_CONVENIO'],
                'urgencia' => 'Alta',
                'ug_responsavel' => $convenio['UG_RESPONSAVEL']
            ];
        }

        foreach($convenios_a_vencer_60 as $convenio) {
            $alertas[] = [
                'titulo' => 'O convênio '.$convenio['NR_CONVENIO'].' vencerá em até 60 dias. Início: '.$convenio['DIA_INIC_VIGENC_CONV'].' - Fim: '.$convenio['DIA_FIM_VIGENC_CONV'],
                'data' => date('Y-m-d H:i:s'),
                'convenio' => $convenio['NR_CONVENIO'],
                'urgencia' => 'Média',
                'ug_responsavel' => $convenio['UG_RESPONSAVEL']
            ];
        }

        foreach($convenios_a_vencer_90 as $convenio) {
            $alertas[] = [
                'titulo' => 'O convênio '.$convenio['NR_CONVENIO'].' vencerá em até 90 dias. Início: '.$convenio['DIA_INIC_VIGENC_CONV'].' - Fim: '.$convenio['DIA_FIM_VIGENC_CONV'],
                'data' => date('Y-m-d H:i:s'),
                'convenio' => $convenio['NR_CONVENIO'],
                'urgencia' => 'Média',
                'ug_responsavel' => $convenio['UG_RESPONSAVEL']
            ];
        }

        if(count($alertas) < 1) {
            echo 'Nenhum alerta gerado!<br>';
            return;
        }

        $dado = array(
            'data' => date('Y-m-d H:i:s'),
            'alertas' => json_encode($alertas)
        );

        $envio = $this->postCurl('http://autodoc.uff.br/api/atualizar_alerta', array('API-Key' => 'atualizar_alerta_key'), $dado);

        //$envio = $this->postCurl('http://autodoc/api/atualizar_alerta', array('API-Key' => 'atualizar_alerta_key'), $dado);
            
        if(isset(json_decode($envio)->error)) {
            echo 'Falha ao enviar os alertas!<br>';
            $data = array(
                'status' => 'Falha',
                'resposta' => $envio
            );
        } else {
            echo 'Alertas atualizados com sucesso!<br>';
            $data = array(
                'status' => 'Enviado',
                'resposta' => $envio
            );
        }
    }

    private function response($data, $status_code) {
        $this->output
            ->set_content_type('application/json')
            ->set_status_header($status_code)
            ->set_output(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT))
            ->_display();
        exit;
    }

    /**
     * Função para converter um XML SOAP em um array
     *
     * @param string $xmlString O conteúdo do XML SOAP em formato de string
     * @return array|false O array resultante da conversão do XML ou false em caso de erro
     */
    private function soapXmlToArray($xmlString) {
        // Suprimir erros e carregar o XML como um objeto SimpleXMLElement
        libxml_use_internal_errors(true);
        $xmlObject = simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($xmlObject === false) {
            // Se ocorrer um erro ao carregar o XML, retornar false e imprimir os erros
            //echo "Falha ao carregar XML:\n";
            foreach(libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
            return false;
        }

        // Remover namespaces do XML
        $xmlObject = $this->removeNamespaces($xmlObject);

        // Converter o objeto SimpleXMLElement em um array
        $array = json_decode(json_encode($xmlObject), true);
        
        return $array;
    }

    /**
     * Função para remover namespaces de um objeto SimpleXMLElement
     *
     * @param SimpleXMLElement $xml O objeto SimpleXMLElement
     * @return SimpleXMLElement O objeto SimpleXMLElement sem namespaces
     */
    private function removeNamespaces($xml) {
        $xmlString = $xml->asXML();
        $xmlString = preg_replace('/xmlns[^=]*="[^"]*"/i', '', $xmlString);
        return simplexml_load_string($xmlString, 'SimpleXMLElement', LIBXML_NOCDATA);
    }
}
