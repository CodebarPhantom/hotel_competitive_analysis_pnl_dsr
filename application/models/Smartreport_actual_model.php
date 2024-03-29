<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Smartreport_actual_model extends CI_Model
{

    
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function getDataAll($table, $order_column, $order_type){
        $this->db->order_by("$order_column", "$order_type");
        $query = $this->db->get("$table");
        $result = $query->result();
        $this->db->save_queries = false;
        return $result;
    }

    
    function get_data_pnlcategory() {        
        $this->db->order_by("pnlcategory_order", "ASC");	    
        $this->db->from("smartreport_pnlcategory");
        $this->db->where("pnlcategory_status = 'active'");
        return $this->db->get()->result();
    }

    function select_pnllist_percategory($idpnlcategory){ 
        $this->db->select('pl.idpnl, pl.pnl_name,  pc.idpnlcategory, pc.pnl_category, pl.pnl_status, pl.pnl_order');  
        $this->db->order_by('pl.pnl_order', 'ASC');  
        $this->db->like('pl.pnl_name');  
        $this->db->from('smartreport_pnllist as pl');
        $this->db->join('smartreport_pnlcategory as pc', 'pl.idpnlcategory = pc.idpnlcategory', 'left'); 
        $this->db->where('pc.idpnlcategory', $idpnlcategory);
        return $this->db->get()->result();
    }

    function select_pnlbyiddate($idhotels,$idp, $date_actual){ 
        $this->db->select('*');
        $this->db->from('smartreport_actual');
        $this->db->where('idpnl', $idp);
        $this->db->where('idhotels', $idhotels);
        $this->db->where('date_actual', $date_actual);
        return $this->db->get();
    }

    function delete_pnlbyiddate($idhotels,$idp, $date_actual){        
        
        $this->db->where('idhotels', $idhotels);
        $this->db->where('idpnl', $idp );
        $this->db->where('date_actual', $date_actual);
        $this->db->delete('smartreport_actual');
    }

    function insert_batch_data($table, $data_pnl){ 
        $this->db->insert_batch($table, $data_pnl);
    }

   
    function get_total_actual($idpnl, $idhotels, $month, $year ){ 
        $this->db->select("sum(actual_value) as TOTAL_ACTUAL");
        $this->db->from("smartreport_actual");
        $this->db->where("idpnl ='$idpnl' AND MONTH(date_actual) = '$month'AND YEAR(date_actual) = '$year' AND idhotels = '$idhotels'");
        return $this->db->get()->row();
    }

    function get_total_budget($idpnl, $idhotels, $month, $year ){ 
        $this->db->select("sum(budget_value) as TOTAL_BUDGET");
        $this->db->from("smartreport_budget");
        $this->db->where("idpnl ='$idpnl' AND MONTH(date_budget) = '$month'AND YEAR(date_budget) = '$year' AND idhotels = '$idhotels'");
        return $this->db->get()->row();
    }

    function get_total_actual_ytd($idpnl, $idhotels, $startdate_ytd, $enddate_ytd ){ 
        $this->db->select("sum(actual_value) as TOTAL_ACTUAL");
        $this->db->from("smartreport_actual");
        $this->db->where("idpnl ='$idpnl' AND date_actual BETWEEN '$startdate_ytd' AND '$enddate_ytd' AND idhotels = '$idhotels'");
        return $this->db->get()->row();
    }

    function get_total_budget_ytd($idpnl, $idhotels, $startdate_ytd, $enddate_ytd ){ 
        $this->db->select("sum(budget_value) as TOTAL_BUDGET");
        $this->db->from("smartreport_budget");
        $this->db->where("idpnl ='$idpnl' AND date_budget BETWEEN '$startdate_ytd' AND '$enddate_ytd' AND idhotels = '$idhotels'");
        return $this->db->get()->row();
    }

    function get_grandtotal_pnlcategory_actual($idpnlcategory, $idhotels, $month, $year){ 
        $this->db->select("sum(sb.actual_value) as GRANDTOTAL_PNLCATEGORY");
        $this->db->from("smartreport_actual as sb");
        $this->db->join("smartreport_pnllist as sp", "sb.idpnl = sp.idpnl", "left");
        $this->db->where("sp.idpnlcategory= '$idpnlcategory' AND MONTH(sb.date_actual)='$month' AND YEAR(sb.date_actual) = '$year' and sb.idhotels = '$idhotels'");
        return $this->db->get()->row();
    }

    function get_grandtotal_pnlcategory_budget($idpnlcategory, $idhotels, $month, $year){
        $this->db->select("sum(sb.budget_value) as GRANDTOTAL_PNLCATEGORY");
        $this->db->from("smartreport_budget as sb");
        $this->db->join("smartreport_pnllist as sp", "sb.idpnl = sp.idpnl", "left");
        $this->db->where("sp.idpnlcategory= '$idpnlcategory' AND MONTH(sb.date_budget)='$month' AND YEAR(sb.date_budget) = '$year' and sb.idhotels = '$idhotels'");
        return $this->db->get()->row();
    }

    function get_grandtotal_pnlcategory_actual_ytd($idpnlcategory, $idhotels, $startdate_ytd, $enddate_ytd){ 
        $this->db->select("sum(sb.actual_value) as GRANDTOTAL_PNLCATEGORY");
        $this->db->from("smartreport_actual as sb");
        $this->db->join("smartreport_pnllist as sp", "sb.idpnl = sp.idpnl", "left");
        $this->db->where("sp.idpnlcategory= '$idpnlcategory' AND date_actual BETWEEN '$startdate_ytd' AND '$enddate_ytd' AND sb.idhotels = '$idhotels'");
        return $this->db->get()->row();
    }

    function get_grandtotal_pnlcategorybudget($idpnlcategory, $idhotels, $month, $year){ 
        $this->db->select("sum(sb.budget_value) as GRANDTOTAL_PNLCATEGORY");
        $this->db->from("smartreport_budget as sb");
        $this->db->join("smartreport_pnllist as sp", "sb.idpnl = sp.idpnl", "left");
        $this->db->where("sp.idpnlcategory= '$idpnlcategory' AND MONTH(sb.date_budget)='$month' AND YEAR(sb.date_budget) = '$year' and sb.idhotels = '$idhotels'");
        return $this->db->get()->row();
    }

    function get_grandtotal_pnlcategory_budget_ytd($idpnlcategory, $idhotels, $startdate_ytd, $enddate_ytd){ 
        $this->db->select("sum(sb.budget_value) as GRANDTOTAL_PNLCATEGORY");
        $this->db->from("smartreport_budget as sb");
        $this->db->join("smartreport_pnllist as sp", "sb.idpnl = sp.idpnl", "left");
        $this->db->where("sp.idpnlcategory= '$idpnlcategory' AND date_budget BETWEEN '$startdate_ytd' AND '$enddate_ytd' AND sb.idhotels = '$idhotels'");
        return $this->db->get()->row();
    }    

    function select_actualpnlbydate($idh, $idpnl, $date_actual){
        $this->db->select('*');
        $this->db->from('smartreport_actual');
        $this->db->where('idhotels', $idh );
        $this->db->where('idpnl', $idpnl);
        $this->db->where('date_actual', $date_actual);
        return $this->db->get();
    }

    function update_actualpnlbyid($id,$data){
        $this->db->where('idactual', $id);
        $this->db->update('smartreport_actual',$data);
        return true;
    }

    function insertData($table,$data){
        $this->db->insert($table,$data);
    } 
    


}