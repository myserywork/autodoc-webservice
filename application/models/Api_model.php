<?php
class Api_model extends CI_Model {

    private $webservice_endpoint = null;
    private $xml_exporta_convenio = null;
    private $xml_exporta_proposta = null;
    private $xml_exporta_proponente = null;

    public function __construct() {
        parent::__construct();
        $this->load->library('SimpleBrowser');
        $this->load->helper('url');
        $this->webservice_endpoint = 'https://ws.convenios.gov.br/siconv-siconv-interfaceSiconv-1.0/InterfaceSiconvHandlerBeanImpl?wsdl';

        $xml_dir = dirname(__DIR__, 1);

        $this->xml_exporta_convenio = file_get_contents($xml_dir . "/xml_models/exporta_convenio.xml");
        $this->xml_exporta_proposta = file_get_contents($xml_dir . "/xml_models/exporta_proposta.xml");
        $this->xml_exporta_proponente = file_get_contents($xml_dir . "/xml_models/exporta_proponente.xml");        
    }

    public function getConvenio($sequencial,$ano,$orgao) {

        $xml_data_convenio = str_replace(
            array('{sequencial}','{ano}','{orgao}'),
            array($sequencial,$ano,$orgao),
            $this->xml_exporta_convenio
        );

        $this->simplebrowser->setHeaders(array(
            'Content-Type: text/xml; charset=utf-8',
            'Content-Length: ' . strlen($xml_data_convenio)
        ));
    
        $convenio = $this->simplebrowser->post($this->webservice_endpoint, $xml_data_convenio);

        if (!$convenio) {
            return false;
        } else {
            return $convenio;
        }
    }

    public function getProponente($identificacao, $tipo_identificacao) {

        $xml_data_proponente = str_replace(
            array('{identificacao}','{tipo_identificacao}'),
            array($identificacao,$tipo_identificacao),
            $this->xml_exporta_proponente
        );

        $this->simplebrowser->setHeaders(array(
            'Content-Type: text/xml; charset=utf-8',
            'Content-Length: ' . strlen($xml_data_proponente)
        ));
    
        $proponente = $this->simplebrowser->post($this->webservice_endpoint, $xml_data_proponente);

        if (!$proponente) {
            return false;
        } else {
            return $proponente;
        }
    }

    public function upsert_convenio_webservice($data) {
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function ($value) {
            return $this->db->escape($value);
        }, array_values($data)));
    
        $updates = implode(', ', array_map(function ($key) {
            return "$key=VALUES($key)";
        }, array_keys($data)));
    
        $sql = "INSERT INTO dados_webservice ($keys) VALUES ($values)
                ON DUPLICATE KEY UPDATE $updates";
    
        if($this->db->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    public function upsert_convenio($data) {
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function ($value) {
            return $this->db->escape($value);
        }, array_values($data)));
    
        $updates = implode(', ', array_map(function ($key) {
            return "$key=VALUES($key)";
        }, array_keys($data)));
    
        $sql = "INSERT INTO dados_convenios_publico ($keys) VALUES ($values)
                ON DUPLICATE KEY UPDATE $updates";
    
        $this->db->query($sql);
    } 

    public function upsert_proposta($data) {
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function ($value) {
            return $this->db->escape($value);
        }, array_values($data)));
    
        $updates = implode(', ', array_map(function ($key) {
            return "$key=VALUES($key)";
        }, array_keys($data)));
    
        $sql = "INSERT INTO dados_proposta_publico ($keys) VALUES ($values)
                ON DUPLICATE KEY UPDATE $updates";
    
        $this->db->query($sql);
    } 

    public function upsert_empenhos($data) {
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function ($value) {
            return $this->db->escape($value);
        }, array_values($data)));
    
        $updates = implode(', ', array_map(function ($key) {
            return "$key=VALUES($key)";
        }, array_keys($data)));
    
        $sql = "INSERT INTO dados_empenhos_publico ($keys) VALUES ($values)
                ON DUPLICATE KEY UPDATE $updates";
    
        $this->db->query($sql);
    } 

    public function upsert_solicitacoes_atualizacao($data) {
        $keys = implode(', ', array_keys($data));
        $values = implode(', ', array_map(function ($value) {
            return $this->db->escape($value);
        }, array_values($data)));
    
        $updates = implode(', ', array_map(function ($key) {
            return "$key=VALUES($key)";
        }, array_keys($data)));
    
        $sql = "INSERT INTO solicitacoes_atualizacao ($keys) VALUES ($values)
                ON DUPLICATE KEY UPDATE $updates";
    
        $this->db->query($sql);
    }

    public function updateSolicitacoesWhere($data, $where) {
        $updates = implode(', ', array_map(function ($key, $value) {
            return "$key = '$value'";
        }, array_keys($data), array_values($data)));
    
        $sql = "UPDATE solicitacoes_atualizacao SET $updates WHERE $where";
    
        $this->db->query($sql);
    }

    public function getPendenciasAtualizacao() {
        $sql = "SELECT * FROM solicitacoes_atualizacao WHERE status IN ('Pendente','Falha')";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getConveniofromWebService($nr_convenio) {
        $sql = "SELECT * FROM dados_webservice WHERE nr_convenio = '$nr_convenio'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getConveniofromPublicData($nr_convenio) {
        $sql = "SELECT * FROM dados_convenios_publico WHERE NR_CONVENIO = '$nr_convenio'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getPropostafromPublicData($id_proposta) {
        $sql = "SELECT * FROM dados_proposta_publico WHERE ID_PROPOSTA = '$id_proposta'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getConveniosAVencer($diasInicio, $diasFim) {
        $query = $this->db->query("
            SELECT `NR_CONVENIO`, `DIA_INIC_VIGENC_CONV`, `DIA_FIM_VIGENC_CONV`, (SELECT 
            `UG_RESPONSAVEL` 
          FROM 
            `dados_empenhos_publico` 
          WHERE 
            `NR_CONVENIO` COLLATE utf8mb4_unicode_ci = `dados_convenios_publico`.`NR_CONVENIO` COLLATE utf8mb4_unicode_ci
          ORDER BY 
            `DATA_EMISSAO` DESC 
          LIMIT 1) AS `UG_RESPONSAVEL`
            FROM `dados_convenios_publico` 
            WHERE STR_TO_DATE(`DIA_FIM_VIGENC_CONV`, '%d/%m/%Y') 
            BETWEEN CURDATE() + INTERVAL ? DAY AND CURDATE() + INTERVAL ? DAY
        ", array($diasInicio, $diasFim));
    
        // Retorna os resultados como um array
        return $query->result_array();
    }
}
?>