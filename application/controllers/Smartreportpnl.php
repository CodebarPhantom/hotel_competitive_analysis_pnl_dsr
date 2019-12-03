<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Smartreportpnl extends CI_Controller{


  function __construct(){
    parent::__construct();
    if($this->session->userdata('logged_in') !== TRUE){
          redirect('errorpage/error403');
    }
        $this->load->model('Smartreport_users_model');
        $this->load->model('Smartreport_city_model');
        $this->load->model('Smartreport_hotels_model');
        $this->load->model('Smartreport_departement_model');
        $this->load->model('Smartreport_dsr_model');
        $this->load->model('Smartreport_hca_model');
        $this->load->model('Smartreport_pnl_model');
        $this->load->model('Smartreport_actual_model');
        $this->load->model('Dashboard_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->library('uploadfile');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('mydate');
        $this->load->helper('text');      
    }

    function pnl_category(){
        $user_level = $this->session->userdata('user_level');
        if($user_level === '1' || $user_level === '2' || $user_level ==='3' ){
        $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));
            
            if ($q <> '') {
                $config['base_url'] = base_url() . 'smartreportpnl/pnl-category?q=' . urlencode($q);
                $config['first_url'] = base_url() . 'smartreportpnl/pnl-category?q=' . urlencode($q);
            } else {
                $config['base_url'] = base_url() . 'smartreportpnl/pnl-category';
                $config['first_url'] = base_url() . 'smartreportpnl/pnl-category';
            }

            $config['per_page'] = 10;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Smartreport_pnl_model->total_rows_pnlcategory($q);
            $smartreport_pnlcategory = $this->Smartreport_pnl_model->get_limit_data_pnlcategory($config['per_page'], $start, $q);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $page_data['page_name'] = 'list_categorypnl';        
            $page_data['lang_dashboard'] = $this->lang->line('dashboard');
            $page_data['lang_dashboard_statistic'] = $this->lang->line('dashboard_statistic');
            $page_data['lang_add_city'] = $this->lang->line('add_city');
            $page_data['lang_list_users'] = $this->lang->line('list_users');
            $page_data['lang_hotel'] = $this->lang->line('hotel');
            $page_data['lang_add_hotel'] = $this->lang->line('add_hotel');
            $page_data['lang_list_hotels'] = $this->lang->line('list_hotels');
            $page_data['lang_competitor_hotels'] = $this->lang->line('competitor_hotels');
            $page_data['lang_analysis'] = $this->lang->line('analysis');        
            $page_data['lang_hotel_comp_anl'] = $this->lang->line('hotel_comp_anl');        
            $page_data['lang_dsr'] = $this->lang->line('dsr');
            $page_data['lang_city'] = $this->lang->line('city');
            $page_data['lang_add_city'] = $this->lang->line('add_city');
            $page_data['lang_list_city'] = $this->lang->line('list_city');
            $page_data['lang_departement'] = $this->lang->line('departement');
            $page_data['lang_add_departement'] = $this->lang->line('add_departement');
            $page_data['lang_list_departement'] = $this->lang->line('list_departement');
            $page_data['lang_setting'] = $this->lang->line('setting');
            $page_data['lang_user'] = $this->lang->line('user');
            $page_data['lang_search'] = $this->lang->line('search');
            $page_data['lang_pnl'] = $this->lang->line('pnl');
            $page_data['lang_pnl_category'] = $this->lang->line('pnl_category');
            $page_data['lang_pnl_list'] = $this->lang->line('pnl_list');
            $page_data['lang_budget'] = $this->lang->line('budget');
            $page_data['lang_pnl_budget'] = $this->lang->line('pnl_budget');
            $page_data['lang_expense'] = $this->lang->line('expense');
            $page_data['lang_pnl_expense'] = $this->lang->line('pnl_expense');
            $page_data['lang_category_hotels'] = $this->lang->line('category_hotels');
            $page_data['lang_statistic_dsr'] = $this->lang->line('statistic_dsr');            

            $page_data['lang_input_success'] = $this->lang->line('input_success');
            $page_data['lang_success_input_data'] = $this->lang->line('success_input_data');
            $page_data['lang_delete_success'] = $this->lang->line('delete_success');
            $page_data['lang_delete_data'] = $this->lang->line('delete_data');
            $page_data['lang_delete_confirm'] = $this->lang->line('delete_confirm');
            $page_data['lang_success_delete_data'] = $this->lang->line('success_delete_data');
            $page_data['lang_update_success'] = $this->lang->line('update_success');
            $page_data['lang_success_update_data'] = $this->lang->line('success_update_data'); 
            $page_data['lang_cancel_data'] = $this->lang->line('cancel_data');
            $page_data['lang_cancel_confirm'] = $this->lang->line('cancel_confirm'); 
            $page_data['lang_submit'] = $this->lang->line('submit');
            $page_data['lang_close'] = $this->lang->line('close');
            
            $page_data['lang_order'] = $this->lang->line('order');
            $page_data['lang_status'] = $this->lang->line('status');
            $page_data['lang_search_pnl_category'] = $this->lang->line('search_pnl_category');
            $page_data['lang_add_pnl_category'] = $this->lang->line('add_pnl_category');
            $page_data['lang_edit_pnl_category'] = $this->lang->line('edit_pnl_category');
            $page_data['lang_choose_status'] = $this->lang->line('choose_status');
            $page_data['lang_active'] = $this->lang->line('active');
            $page_data['lang_inactive'] = $this->lang->line('inactive');

            $page_data['smartreport_pnlcategory_data'] = $smartreport_pnlcategory;
            $page_data['q'] = $q;
            $page_data['pagination'] = $this->pagination->create_links();
            $page_data['total_rows'] = $config['total_rows'];
            $page_data['start'] = $start;       
            $this->load->view('smartreport/index', $page_data);
        }else{
        redirect('errorpage/error403');
        }
    }

    function insert_pnl_category(){    
        $user_level = $this->session->userdata('user_level');
        if($user_level === '1' || $user_level === '2' || $user_level ==='3' ){
        $data = array(
            'pnl_category' => strtoupper($this->input->post('pnlcategory_name',TRUE)),
            'pnlcategory_status' =>$this->input->post('pnlcategory_status',TRUE),
            'pnlcategory_order' =>$this->input->post('pnlcategory_order',TRUE),
            'date_created' =>date('Y-m:d H:i:s')
            );  
            $this->Smartreport_pnl_model->insertData('smartreport_pnlcategory',$data);
            $this->session->set_flashdata('input_success','message');        
            redirect(site_url('smartreportpnl/pnl-category'));
            }else{
            redirect('errorpage/error403');
        }      
    }

    function update_pnl_category(){
        $user_level = $this->session->userdata('user_level');
            if($user_level === '1' || $user_level === '2' || $user_level ==='3'){
            $data = array(
                'pnl_category' => strtoupper($this->input->post('pnlcategory_name',TRUE)),
                'pnlcategory_status' =>$this->input->post('pnlcategory_status',TRUE),                
                'pnlcategory_order' =>$this->input->post('pnlcategory_order',TRUE)
            );  
            
                $this->Smartreport_pnl_model->update_data_pnlcategory('smartreport_pnlcategory', $data, $this->input->post('idpnlcategory', TRUE));
                $this->session->set_flashdata('update_success','message');
                redirect(site_url('smartreportpnl/pnl-category'));
            }else{
                redirect('errorpage/error403');
            }
    }

    function pnl_list(){
        $user_level = $this->session->userdata('user_level');
        if($user_level === '1' || $user_level === '2' || $user_level ==='3' ){
            $q = urldecode($this->input->get('q', TRUE));
                $start = intval($this->input->get('start'));
                
                if ($q <> '') {
                    $config['base_url'] = base_url() . 'smartreportpnl/pnl-list?q=' . urlencode($q);
                    $config['first_url'] = base_url() . 'smartreportpnl/pnl-list?q=' . urlencode($q);
                } else {
                    $config['base_url'] = base_url() . 'smartreportpnl/pnl-list';
                    $config['first_url'] = base_url() . 'smartreportpnl/pnl-list';
                }
        
                $config['per_page'] = 10;
                $config['page_query_string'] = TRUE;
                $config['total_rows'] = $this->Smartreport_pnl_model->total_rows_pnllist($q);
                $smartreport_pnllist = $this->Smartreport_pnl_model->get_limit_data_pnllist($config['per_page'], $start, $q);
        
                $this->load->library('pagination');
                $this->pagination->initialize($config);
        
                $page_data['page_name'] = 'list_pnl';        
                $page_data['lang_dashboard'] = $this->lang->line('dashboard');
                $page_data['lang_dashboard_statistic'] = $this->lang->line('dashboard_statistic');
                $page_data['lang_add_city'] = $this->lang->line('add_city');
                $page_data['lang_list_users'] = $this->lang->line('list_users');
                $page_data['lang_hotel'] = $this->lang->line('hotel');
                $page_data['lang_add_hotel'] = $this->lang->line('add_hotel');
                $page_data['lang_list_hotels'] = $this->lang->line('list_hotels');
                $page_data['lang_competitor_hotels'] = $this->lang->line('competitor_hotels');
                $page_data['lang_analysis'] = $this->lang->line('analysis');        
                $page_data['lang_hotel_comp_anl'] = $this->lang->line('hotel_comp_anl');        
                $page_data['lang_dsr'] = $this->lang->line('dsr');
                $page_data['lang_city'] = $this->lang->line('city');
                $page_data['lang_add_city'] = $this->lang->line('add_city');
                $page_data['lang_list_city'] = $this->lang->line('list_city');
                $page_data['lang_departement'] = $this->lang->line('departement');
                $page_data['lang_add_departement'] = $this->lang->line('add_departement');
                $page_data['lang_list_departement'] = $this->lang->line('list_departement');
                $page_data['lang_setting'] = $this->lang->line('setting');
                $page_data['lang_user'] = $this->lang->line('user');
                $page_data['lang_search'] = $this->lang->line('search');
                $page_data['lang_pnl'] = $this->lang->line('pnl');
                $page_data['lang_pnl_category'] = $this->lang->line('pnl_category');
                $page_data['lang_pnl_list'] = $this->lang->line('pnl_list');
                $page_data['lang_budget'] = $this->lang->line('budget');
                $page_data['lang_pnl_budget'] = $this->lang->line('pnl_budget');
                $page_data['lang_expense'] = $this->lang->line('expense');
                $page_data['lang_pnl_expense'] = $this->lang->line('pnl_expense'); 
                $page_data['lang_category_hotels'] = $this->lang->line('category_hotels');
                $page_data['lang_statistic_dsr'] = $this->lang->line('statistic_dsr');               
        
                $page_data['lang_input_success'] = $this->lang->line('input_success');
                $page_data['lang_success_input_data'] = $this->lang->line('success_input_data');
                $page_data['lang_delete_success'] = $this->lang->line('delete_success');
                $page_data['lang_delete_data'] = $this->lang->line('delete_data');
                $page_data['lang_delete_confirm'] = $this->lang->line('delete_confirm');
                $page_data['lang_success_delete_data'] = $this->lang->line('success_delete_data');
                $page_data['lang_update_success'] = $this->lang->line('update_success');
                $page_data['lang_success_update_data'] = $this->lang->line('success_update_data'); 
                $page_data['lang_cancel_data'] = $this->lang->line('cancel_data');
                $page_data['lang_cancel_confirm'] = $this->lang->line('cancel_confirm'); 
                $page_data['lang_submit'] = $this->lang->line('submit');
                $page_data['lang_close'] = $this->lang->line('close');
                
                $page_data['lang_order'] = $this->lang->line('order');
                $page_data['lang_status'] = $this->lang->line('status');
                $page_data['lang_choose_status'] = $this->lang->line('choose_status');
                $page_data['lang_active'] = $this->lang->line('active');
                $page_data['lang_inactive'] = $this->lang->line('inactive');                
                $page_data['lang_search_pnl_list'] = $this->lang->line('search_pnl_list');
                $page_data['lang_category_pnl'] = $this->lang->line('category_pnl');
                $page_data['lang_add_pnl_list'] = $this->lang->line('add_pnl_list');
                $page_data['lang_edit_pnl_list'] = $this->lang->line('edit_pnl_list');
                $page_data['lang_choose_pnl_category'] = $this->lang->line('choose_pnl_category');
        
                $page_data['smartreport_pnllist_data'] = $smartreport_pnllist;
                $page_data['q'] = $q;
                $page_data['pagination'] = $this->pagination->create_links();
                $page_data['total_rows'] = $config['total_rows'];
                $page_data['start'] = $start;       
                $this->load->view('smartreport/index', $page_data);
            }else{
              redirect('errorpage/error403');
            }
    }

    function insert_pnl_list(){
        $user_level = $this->session->userdata('user_level');
        if($user_level === '1' || $user_level === '2' || $user_level ==='3' ){
        $data = array(
            'pnl_name' => ucwords($this->input->post('pnl_name',TRUE)),
            'idpnlcategory' => $this->input->post('idpnlcategory',TRUE),
            'pnl_status' =>$this->input->post('pnl_status',TRUE),
            'pnl_order'=>$this->input->post('pnl_order',TRUE),
            'date_created' =>date('Y-m:d H:i:s')
            );  
            $this->Smartreport_pnl_model->insertData('smartreport_pnllist',$data);
            $this->session->set_flashdata('input_success','message');        
            redirect(site_url('smartreportpnl/pnl-list'));
            }else{
            redirect('errorpage/error403');
        }    
    }

    function update_pnl_list(){
        $user_level = $this->session->userdata('user_level');
            if($user_level === '1' || $user_level === '2' || $user_level ==='3' ){
            $data = array(
                'pnl_name' => ucwords($this->input->post('pnl_name',TRUE)),
                'idpnlcategory' => $this->input->post('idpnlcategory',TRUE),
                'pnl_status' =>$this->input->post('pnl_status',TRUE),
            );  
            
                $this->Smartreport_pnl_model->update_data_pnllist('smartreport_pnllist', $data, $this->input->post('idpnl', TRUE));
                $this->session->set_flashdata('update_success','message');
                redirect(site_url('smartreportpnl/pnl-list'));
            }else{
                redirect('errorpage/error403');
            }
    }

    function budget_pnl(){
        $user_level = $this->session->userdata('user_level');
            if($user_level === '1' || $user_level === '2' || $user_level ==='3' ){
            $page_data['page_name'] = 'budget_pnl';
            //$getyear_budget = strtotime($this->input->get('year_budget', TRUE));
           // $year_budget = date("Y", $getyear_budget);        
            $page_data['lang_dashboard'] = $this->lang->line('dashboard');
            $page_data['lang_dashboard_statistic'] = $this->lang->line('dashboard_statistic');
            $page_data['lang_add_city'] = $this->lang->line('add_city');
            $page_data['lang_list_users'] = $this->lang->line('list_users');
            $page_data['lang_hotel'] = $this->lang->line('hotel');
            $page_data['lang_add_hotel'] = $this->lang->line('add_hotel');
            $page_data['lang_list_hotels'] = $this->lang->line('list_hotels');
            $page_data['lang_competitor_hotels'] = $this->lang->line('competitor_hotels');
            $page_data['lang_analysis'] = $this->lang->line('analysis');        
            $page_data['lang_hotel_comp_anl'] = $this->lang->line('hotel_comp_anl');        
            $page_data['lang_dsr'] = $this->lang->line('dsr');
            $page_data['lang_city'] = $this->lang->line('city');
            $page_data['lang_add_city'] = $this->lang->line('add_city');
            $page_data['lang_list_city'] = $this->lang->line('list_city');
            $page_data['lang_departement'] = $this->lang->line('departement');
            $page_data['lang_add_departement'] = $this->lang->line('add_departement');
            $page_data['lang_list_departement'] = $this->lang->line('list_departement');
            $page_data['lang_setting'] = $this->lang->line('setting');
            $page_data['lang_user'] = $this->lang->line('user');
            $page_data['lang_search'] = $this->lang->line('search');
            $page_data['lang_pnl'] = $this->lang->line('pnl');
            $page_data['lang_pnl_category'] = $this->lang->line('pnl_category');
            $page_data['lang_pnl_list'] = $this->lang->line('pnl_list');
            $page_data['lang_budget'] = $this->lang->line('budget');
            $page_data['lang_pnl_budget'] = $this->lang->line('pnl_budget');
            $page_data['lang_expense'] = $this->lang->line('expense');
            $page_data['lang_pnl_expense'] = $this->lang->line('pnl_expense');  
            $page_data['lang_category_hotels'] = $this->lang->line('category_hotels');
            $page_data['lang_statistic_dsr'] = $this->lang->line('statistic_dsr');          

            $page_data['lang_input_success'] = $this->lang->line('input_success');
            $page_data['lang_success_input_data'] = $this->lang->line('success_input_data');
            $page_data['lang_delete_success'] = $this->lang->line('delete_success');
            $page_data['lang_delete_data'] = $this->lang->line('delete_data');
            $page_data['lang_delete_confirm'] = $this->lang->line('delete_confirm');
            $page_data['lang_success_delete_data'] = $this->lang->line('success_delete_data');
            $page_data['lang_update_success'] = $this->lang->line('update_success');
            $page_data['lang_success_update_data'] = $this->lang->line('success_update_data'); 
            $page_data['lang_cancel_data'] = $this->lang->line('cancel_data');
            $page_data['lang_cancel_confirm'] = $this->lang->line('cancel_confirm'); 
            $page_data['lang_submit'] = $this->lang->line('submit');
            $page_data['lang_close'] = $this->lang->line('close');
            
            $page_data['lang_order'] = $this->lang->line('order');
            $page_data['lang_add_data'] = $this->lang->line('add_data');
            $page_data['lang_date'] = $this->lang->line('date');
            $page_data['lang_description'] = $this->lang->line('description');

            $page_data['lang_status'] = $this->lang->line('status');
            $page_data['lang_search_pnl_category'] = $this->lang->line('search_pnl_category');
            $page_data['lang_add_pnl_category'] = $this->lang->line('add_pnl_category');
            $page_data['lang_edit_pnl_category'] = $this->lang->line('edit_pnl_category');
            $page_data['lang_choose_status'] = $this->lang->line('choose_status');
            $page_data['lang_active'] = $this->lang->line('active');
            $page_data['lang_inactive'] = $this->lang->line('inactive');
            $page_data['lang_month'] = $this->lang->line('month');
            $page_data['lang_year'] = $this->lang->line('year');   
            $page_data['lang_budget_data'] = $this->lang->line('budget_data'); 
            $page_data['lang_add_data_bypnl'] = $this->lang->line('add_data_bypnl'); 
            $page_data['lang_select_month'] = $this->lang->line('select_month'); 
            $page_data['lang_select_year'] = $this->lang->line('select_year');   
            $page_data['lang_choose_pnl_category'] = $this->lang->line('choose_pnl_category');
            $page_data['lang_choose_pnl_list'] = $this->lang->line('choose_pnl_list');    
            $page_data['lang_budget'] = $this->lang->line('budget');    
            
            $smartreport_pnlcategory = $this->Smartreport_pnl_model->get_data_pnlcategory();
            $page_data['smartreport_pnlcategory_data'] = $smartreport_pnlcategory;
            $page_data['dateToView'] =$this->input->get('year_budget', TRUE);

            $this->load->view('smartreport/index', $page_data);
        }else{
            redirect('errorpage/error403');
        }
    }

    function insert_budget_pnl(){
        $user_level = $this->session->userdata('user_level');
        if($user_level === '1' || $user_level === '2' || $user_level ==='3' ){
            $idhotels= $this->session->userdata('user_hotel');
            $year_budget = $this->input->post('year_budget');
            $month_budget = $this->input->post('month_budget');
            $idpnl = $_POST['idpnl'];
            $budget_value = $_POST['budget_value'];            
            $date_budget = $year_budget.'-'.$month_budget.'-'.'01';
            $data_budget = array();
            $count_budget = 0;

            foreach($idpnl as $idp ){       
        
                if($idp != ''){
                      $dt_pnl = $this->Smartreport_pnl_model->select_pnlbyiddate($idhotels,$idp,$date_budget)->num_rows();
                    if($dt_pnl > 0){
                      $this->Smartreport_pnl_model->delete_pnlbyiddate($idhotels,$idp,$date_budget);
                    }
                    array_push($data_budget,array(
                      //'idanalysis'=> $idhotels[$count_anl].str_replace("-","",$date_analysis),
                      'idpnl'=> $idpnl[$count_budget],
                      'budget_value'=>$budget_value[$count_budget],
                      'idhotels'=>$idhotels,
                      'date_budget'=>$date_budget,
                      'date_created' => date("Y-m-d H:i:s")
                      
                    ));
                    $count_budget++;
              }
            }
            
              
            $this->Smartreport_pnl_model->insert_batch_data('smartreport_budget',$data_budget); 
            $this->session->set_flashdata('input_success','message');        
            redirect(site_url('smartreportpnl/budget-pnl'));
        }else{
            redirect('errorpage/error403');
        }
    }

    function add_budget_data_bypnl(){
        $user_level = $this->session->userdata('user_level');
        if($user_level === '1' || $user_level === '2' || $user_level === '3'){
        
        $idhotels= $this->session->userdata('user_hotel');
        $year_budget = $this->input->post('year_budget' , TRUE);
        $month_budget = $this->input->post('month_budget', TRUE);
        $date_budget = $year_budget.'-'.$month_budget.'-'.'01'; 
        $idpnl = $this->input->post('idpnllist', TRUE);      
        $data_budget = $this->Smartreport_pnl_model->select_budgetpnlbydate($idhotels,$idpnl,$date_budget);
            if($data_budget->num_rows() > 0){
            $data = array(    
                'idpnl'=>$idpnl,
                'budget_value'=>$this->input->post('budget_value', TRUE),
                'date_budget'=>$date_budget);  
                $this->Smartreport_pnl_model->update_budgetpnlbyid($data_budget->row()->idbudget,$data);

            }else{
            $data = array(       
                'idhotels'=> $idhotels,
                'idpnl'=>$idpnl,
                'budget_value'=>$this->input->post('budget_value', TRUE),
                'date_budget'=>$date_budget,
                'date_created' => date("Y-m-d H:i:s")
                );  
                $this->Smartreport_pnl_model->insertData('smartreport_budget',$data);
            }
            $this->session->set_flashdata('input_success','message');        
            redirect(site_url('smartreportpnl/budget-pnl'));
        }else{
            redirect('errorpage/error403');
        } 
    }

    function actual_pnl(){
        $user_level = $this->session->userdata('user_level');
            if($user_level === '1' || $user_level === '2' || $user_level ==='3' ){
            $page_data['page_name'] = 'actual_pnl';
            //$getyear_budget = strtotime($this->input->get('year_budget', TRUE));
           // $year_budget = date("Y", $getyear_budget);        
            $page_data['lang_dashboard'] = $this->lang->line('dashboard');
            $page_data['lang_dashboard_statistic'] = $this->lang->line('dashboard_statistic');
            $page_data['lang_add_city'] = $this->lang->line('add_city');
            $page_data['lang_list_users'] = $this->lang->line('list_users');
            $page_data['lang_hotel'] = $this->lang->line('hotel');
            $page_data['lang_add_hotel'] = $this->lang->line('add_hotel');
            $page_data['lang_list_hotels'] = $this->lang->line('list_hotels');
            $page_data['lang_competitor_hotels'] = $this->lang->line('competitor_hotels');
            $page_data['lang_analysis'] = $this->lang->line('analysis');        
            $page_data['lang_hotel_comp_anl'] = $this->lang->line('hotel_comp_anl');        
            $page_data['lang_dsr'] = $this->lang->line('dsr');
            $page_data['lang_city'] = $this->lang->line('city');
            $page_data['lang_add_city'] = $this->lang->line('add_city');
            $page_data['lang_list_city'] = $this->lang->line('list_city');
            $page_data['lang_departement'] = $this->lang->line('departement');
            $page_data['lang_add_departement'] = $this->lang->line('add_departement');
            $page_data['lang_list_departement'] = $this->lang->line('list_departement');
            $page_data['lang_setting'] = $this->lang->line('setting');
            $page_data['lang_user'] = $this->lang->line('user');
            $page_data['lang_search'] = $this->lang->line('search');
            $page_data['lang_pnl'] = $this->lang->line('pnl');
            $page_data['lang_pnl_category'] = $this->lang->line('pnl_category');
            $page_data['lang_pnl_list'] = $this->lang->line('pnl_list');
            $page_data['lang_budget'] = $this->lang->line('budget');
            $page_data['lang_pnl_budget'] = $this->lang->line('pnl_budget');
            $page_data['lang_expense'] = $this->lang->line('expense');
            $page_data['lang_pnl_expense'] = $this->lang->line('pnl_expense');
            $page_data['lang_category_hotels'] = $this->lang->line('category_hotels');
            $page_data['lang_statistic_dsr'] = $this->lang->line('statistic_dsr');            

            $page_data['lang_input_success'] = $this->lang->line('input_success');
            $page_data['lang_success_input_data'] = $this->lang->line('success_input_data');
            $page_data['lang_delete_success'] = $this->lang->line('delete_success');
            $page_data['lang_delete_data'] = $this->lang->line('delete_data');
            $page_data['lang_delete_confirm'] = $this->lang->line('delete_confirm');
            $page_data['lang_success_delete_data'] = $this->lang->line('success_delete_data');
            $page_data['lang_update_success'] = $this->lang->line('update_success');
            $page_data['lang_success_update_data'] = $this->lang->line('success_update_data'); 
            $page_data['lang_cancel_data'] = $this->lang->line('cancel_data');
            $page_data['lang_cancel_confirm'] = $this->lang->line('cancel_confirm'); 
            $page_data['lang_submit'] = $this->lang->line('submit');
            $page_data['lang_close'] = $this->lang->line('close');
            
            $page_data['lang_order'] = $this->lang->line('order');
            $page_data['lang_add_data'] = $this->lang->line('add_data');
            $page_data['lang_date'] = $this->lang->line('date');
            $page_data['lang_description'] = $this->lang->line('description');

            $page_data['lang_status'] = $this->lang->line('status');
            $page_data['lang_search_pnl_category'] = $this->lang->line('search_pnl_category');
            $page_data['lang_add_pnl_category'] = $this->lang->line('add_pnl_category');
            $page_data['lang_edit_pnl_category'] = $this->lang->line('edit_pnl_category');
            $page_data['lang_choose_status'] = $this->lang->line('choose_status');
            $page_data['lang_active'] = $this->lang->line('active');
            $page_data['lang_inactive'] = $this->lang->line('inactive');
            $page_data['lang_month'] = $this->lang->line('month');
            $page_data['lang_year'] = $this->lang->line('year');   
            $page_data['lang_actual_data'] = $this->lang->line('actual_data'); 
            $page_data['lang_add_data_bypnl'] = $this->lang->line('add_data_bypnl'); 
            $page_data['lang_select_month'] = $this->lang->line('select_month'); 
            $page_data['lang_select_year'] = $this->lang->line('select_year');   
            $page_data['lang_choose_pnl_category'] = $this->lang->line('choose_pnl_category');
            $page_data['lang_choose_pnl_list'] = $this->lang->line('choose_pnl_list');
            $page_data['lang_actual'] = $this->lang->line('actual');

            
            $smartreport_pnlcategory = $this->Smartreport_actual_model->get_data_pnlcategory();
            $page_data['smartreport_pnlcategory_data'] = $smartreport_pnlcategory;
            $page_data['yearact'] =$this->input->get('year_actual', TRUE);
            $page_data['monthact'] =$this->input->get('month_actual', TRUE);

            $this->load->view('smartreport/index', $page_data);
        }else{
            redirect('errorpage/error403');
        }
    }

    function insert_actual_pnl(){
        $user_level = $this->session->userdata('user_level');
        if($user_level === '1' || $user_level === '2' || $user_level ==='3' ){
            $idhotels= $this->session->userdata('user_hotel');
            $year_actual = $this->input->post('year_actual');
            $month_actual = $this->input->post('month_actual');
            $idpnl = $_POST['idpnl'];
            $actual_value = $_POST['actual_value'];            
            $date_actual = $year_actual.'-'.$month_actual.'-'.'01';
            $data_actual = array();
            $count_actual = 0;

            foreach($idpnl as $idp ){       
        
                if($idp != ''){
                      $dt_pnl = $this->Smartreport_actual_model->select_pnlbyiddate($idhotels,$idp,$date_actual)->num_rows();
                    if($dt_pnl > 0){
                      $this->Smartreport_actual_model->delete_pnlbyiddate($idhotels,$idp,$date_actual);
                    }
                    array_push($data_actual,array(
                      //'idanalysis'=> $idhotels[$count_anl].str_replace("-","",$date_analysis),
                      'idpnl'=> $idpnl[$count_actual],
                      'actual_value'=>$actual_value[$count_actual],
                      'idhotels'=>$idhotels,
                      'date_actual'=>$date_actual,
                      'date_created' => date("Y-m-d H:i:s")
                      
                    ));
                    $count_actual++;
              }
            }
            
              
            $this->Smartreport_pnl_model->insert_batch_data('smartreport_actual',$data_actual); 
            $this->session->set_flashdata('input_success','message');        
            redirect(site_url('smartreportpnl/actual-pnl'));
        }else{
            redirect('errorpage/error403');
        }
    }

    function get_pnllist(){
		$idpnlcategory = $this->input->post('id',TRUE);
		$data = $this->Smartreport_pnl_model->get_sub_category($idpnlcategory)->result();
		echo json_encode($data);
    }
    
    function add_actual_data_bypnl(){
        $user_level = $this->session->userdata('user_level');
        if($user_level === '1' || $user_level === '2' || $user_level === '3'){
        
        $idhotels= $this->session->userdata('user_hotel');
        $year_actual = $this->input->post('year_actual' , TRUE);
        $month_actual = $this->input->post('month_actual', TRUE);
        $date_actual = $year_actual.'-'.$month_actual.'-'.'01'; 
        $idpnl = $this->input->post('idpnllist', TRUE);      
        $data_actual = $this->Smartreport_actual_model->select_actualpnlbydate($idhotels,$idpnl,$date_actual);
            if($data_actual->num_rows() > 0){
            $data = array(    
                'idpnl'=>$idpnl,
                'actual_value'=>$this->input->post('actual_value', TRUE),
                'date_actual'=>$date_actual);  
                $this->Smartreport_actual_model->update_actualpnlbyid($data_actual->row()->idactual,$data);

            }else{
            $data = array(       
                'idhotels'=> $idhotels,
                'idpnl'=>$idpnl,
                'actual_value'=>$this->input->post('actual_value', TRUE),
                'date_actual'=>$date_actual,
                'date_created' => date("Y-m-d H:i:s")
                );  
                $this->Smartreport_actual_model->insertData('smartreport_actual',$data);
            }
            $this->session->set_flashdata('input_success','message');        
            redirect(site_url('smartreportpnl/actual-pnl'));
        }else{
            redirect('errorpage/error403');
        } 
    }

}