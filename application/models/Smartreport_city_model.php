<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Smartreport_city_model extends CI_Model{

    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // get all
    public function insertData($table,$data)
    {
        $this->db->insert($table, $data);
    }    

    function total_rows_city($q = NULL) {        
        $this->db->like('idcity', $q);
	    $this->db->or_like('city_name', $q);
        $this->db->from('smartreport_city');
    return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data_city($limit, $start = 0, $q = NULL) {        
        $this->db->order_by('city_name', 'ASC');
        $this->db->like('idcity', $q);
	    $this->db->or_like('city_name', $q);
        $this->db->from('smartreport_city');
        $this->db->limit($limit, $start);
    return $this->db->get()->result();
    }

    public function updateDataCity($table, $data, $idcity)
    {
        $this->db->where('idcity', $idcity);
        $this->db->update("$table", $data);

        return true;
    }

    function deleteDataCity($idcity)
    {
        $this->db->where('idcity', $idcity);
        $this->db->delete('smartreport_city');
    }

}

/* End of file smartreport_city_model.php */
/* Location: ./application/models/smartreport_city_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2019-09-06 05:13:14 */
/* http://harviacode.com */