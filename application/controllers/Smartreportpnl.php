<?php defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Smartreportpnl extends CI_Controller{

    private $contoller_name;
    private $function_name;

  function __construct(){
    parent::__construct();
    if($this->session->userdata('logged_in') !== TRUE){
          redirect('errorpage/error403');
    }
        $this->contoller_name = $this->router->class;
        $this->function_name = $this->router->method;
        $this->load->model('Smartreport_users_model');
        $this->load->model('Smartreport_city_model');
        $this->load->model('Smartreport_hotels_model');
        $this->load->model('Smartreport_departement_model');
        $this->load->model('Smartreport_dsr_model');
        $this->load->model('Smartreport_hca_model');
        $this->load->model('Smartreport_pnl_model');
        $this->load->model('Smartreport_actual_model');
        $this->load->model('Dashboard_model');
        $this->load->model('Rolespermissions_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->load->library('session');
        $this->load->library('uploadfile');
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->helper('mydate');
        $this->load->helper('text');
        $this->load->library('pdfgenerator');      
    }

    function pnl_category(){
        $user_level = $this->session->userdata('user_level');
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
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
            $page_data['lang_dashboard_hotel'] = $this->lang->line('dashboard_hotel');
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
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
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
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
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
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
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
                $page_data['lang_dashboard_hotel'] = $this->lang->line('dashboard_hotel');
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
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
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
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
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
        $userHotelForBudget = $this->session->userdata('user_hotel');
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
            $page_data['page_name'] = 'budget_pnl';
            //$getyear_budget = strtotime($this->input->get('year_budget', TRUE));
           // $year_budget = date("Y", $getyear_budget);
           
           $getidhotel_custom = $this->input->get('idhotelcustom', TRUE);
            if($getidhotel_custom == NULL){
                $getidhotel_custom = $userHotelForBudget; 
            } 

            $page_data['lang_dashboard'] = $this->lang->line('dashboard');
            $page_data['lang_dashboard_hotel'] = $this->lang->line('dashboard_hotel');
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
            $page_data['lang_choose_hotels'] = $this->lang->line('choose_hotels');    
            
            $smartreport_pnlcategory = $this->Smartreport_pnl_model->get_data_pnlcategory();
            $page_data['smartreport_pnlcategory_data'] = $smartreport_pnlcategory;
            $page_data['dateToView'] =$this->input->get('year_budget', TRUE);

            $page_data['idhotel_custom'] = $getidhotel_custom;

            $this->load->view('smartreport/index', $page_data);
        }else{
            redirect('errorpage/error403');
        }
    }

    function budget_pnl_export(){
        $user_level = $this->session->userdata('user_level');
        $userHotelForBudget = $this->session->userdata('user_hotel');
        $dateToView = $this->input->get('year_budget', TRUE);
        $getidhotel_custom = $this->input->get('idhotelcustom', TRUE);
        $username = $this->session->userdata('user_name');

        if($getidhotel_custom == NULL){
            $getidhotel_custom = $userHotelForBudget; 
        }

        if ($dateToView == NULL){
            $dateToView = date("Y");
        }
        //$url_year = $dateToView;
       /*$url_date = '';
       $url_date = $date_analysis;
                                           
       $date =  $dateToView;	
       $peryear = substr($dateToView,0,4);
       $permonth= substr($dateToView,5,2);
       $perdate = substr($dateToView,8,2);	
    
        $startdate_ytd = $peryear.'-01-'.'01';
        $enddate_ytd = $dateToView;
        $startdate_mtd = $peryear.'-'.$permonth.'-'.'01';
        $enddate_mtd = $dateToView;  */

        $pnlCategoryResult = $this->db                                        
                                    ->from("smartreport_pnlcategory")
                                    ->where("pnlcategory_status = 'active'")
                                    ->order_by("pnlcategory_order", "ASC")	
                                    ->get();
        $pnlCategoryRows = $pnlCategoryResult->num_rows();

    
        $data_hotels = $this->Dashboard_model->getDataHotel($getidhotel_custom);
        $total_room_revenue = $this->Smartreport_pnl_model->get_total_budget( "4", $getidhotel_custom, $dateToView); //4 adalah idpnl Room
        $occupied_room = $this->Smartreport_pnl_model->get_total_budget( "7", $getidhotel_custom, $dateToView); //7 adalah idpnl occupied room / room sold
        
        function cal_days_in_year($dateToView){
            $days=0; 
            for($month=1;$month<=12;$month++){ 
                    $days = $days + cal_days_in_month(CAL_GREGORIAN,$month,$dateToView);
                }
            return $days;
            }


        
        //$smartreport_pnlcategory = $this->Smartreport_pnl_model->get_data_pnlcategory();
        //$page_data['smartreport_pnlcategory_data'] = $smartreport_pnlcategory;
        //$page_data['dateToView'] = $this->input->get('year_budget', TRUE);
        //$page_data['idhotel_custom'] = $getidhotel_custom;

        
        $excelBudget = new Spreadsheet();
        // Settingan awal file excel
        $excelBudget->getProperties()->setCreator('Eryan Fauzan')
        ->setLastModifiedBy('Eryan Fauzan')
        ->setTitle("Budget PNL Kagum Hotels")
        ->setSubject("Budget PNL Kagum Hotels")
        ->setDescription("Created By Eryan Fauzan")
        ->setKeywords("Budget PNL Kagum Hotels");

        $border_thin = array(
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '0000000'],
        );
        $styleDefaultBorder = array(
          'borders'=>array(
            'allBorders'=>$border_thin
          ),
        );

        $style_alignCenter_header = array(
          'font' => array(
            'bold'=> true,
            'size'=>(16)
          ),
          'alignment' => array(
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
          ),
          'borders' => array(
            'allBorders'=>$border_thin
          )
          
        );  

        $style_alignCenter_subHeader = array(
          'font' => array(
            'bold'=> true,
            'color'=>['argb'=>'ffffffff']
          ),
          'alignment' => array(
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
          ),
          'borders' => array(
            'allBorders'=>$border_thin
          ),
          'fill'=>array(
              'fillType'=>\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
              'color'=>['rgb'=>'3498db']
          )     
          
        );

        $style_alignRight_text = array(      
          'alignment' => array(
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT       
          ),
          'borders' => array(
            'allBorders'=>$border_thin
          ),
          'numberFormat'=>array(
              'formatCode'=>'#,##0'
          )
          
        );

        $ee = 5;
        $ff = 6;
        $gg = 7;
        $hh = 8;
        //$ii = 8;



        $sheet = $excelBudget->getActiveSheet();
        $sheet->mergeCells('A1:O1');
        $sheet->setCellValue('A1', "$data_hotels->hotels_name". " Budget PNL Kagum Hotels ".$dateToView);
        $sheet->getStyle('A1:O1')->applyFromArray($style_alignCenter_header);
        $sheet->getStyle('A2:O3')->applyFromArray($style_alignCenter_subHeader);
        $sheet->getProtection()->setSheet(true);
        $sheet->getProtection()->setPassword('EryanFauzan');
        $sheet->getProtection()->setFormatCells(true);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);
        $sheet->getColumnDimension('N')->setAutoSize(true);
        $sheet->getColumnDimension('O')->setAutoSize(true);

        $sheet->mergeCells('A2:A3');
        $sheet->setCellValue('A2', 'Description');        

        $sheet->mergeCells('B2:C2');
        $sheet->setCellValue('B2', 'Summary');   

        $sheet->setCellValue('B3', 'Year to Date');
        $sheet->setCellValue('C3', '(%)');

        $sheet->mergeCells('D2:D3');
        $sheet->setCellValue('D2', 'January'); 
        $sheet->mergeCells('E2:E3');
        $sheet->setCellValue('E2', 'February');
        $sheet->mergeCells('F2:F3');
        $sheet->setCellValue('F2', 'March');
        $sheet->mergeCells('G2:G3');
        $sheet->setCellValue('G2', 'April');
        $sheet->mergeCells('H2:H3');
        $sheet->setCellValue('H2', 'May');
        $sheet->mergeCells('I2:I3');
        $sheet->setCellValue('I2', 'June');
        $sheet->mergeCells('J2:J3');
        $sheet->setCellValue('J2', 'July');
        $sheet->mergeCells('K2:K3');
        $sheet->setCellValue('K2', 'August');
        $sheet->mergeCells('L2:L3');
        $sheet->setCellValue('L2', 'September');
        $sheet->mergeCells('M2:M3');
        $sheet->setCellValue('M2', 'October');
        $sheet->mergeCells('N2:N3');
        $sheet->setCellValue('N2', 'November');
        $sheet->mergeCells('O2:O3');
        $sheet->setCellValue('O2', 'Desember'); 
        
        $sheet->mergeCells('A4:O4');
        $sheet->setCellValue('A4', 'STATISTIC');
        $sheet->getStyle('A4:O4')->applyFromArray($style_alignCenter_subHeader); 

        /*-------------------------Number of Days---------------------*/
        $sheet->setCellValue('A5', 'Number of Days');
        $sheet->setCellValue('B5', cal_days_in_year($dateToView));        
        $letterD = "D";
        for($month= 1; $month<=12; $month++ ){ 
            $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$month, $dateToView);
            $sheet->setCellValue("$letterD$ee",$dayInMonth);            
            $letterD ++; 
         }         
        $sheet->getStyle('A5')->applyFromArray($styleDefaultBorder);
        $sheet->getStyle('B5:O5')->applyFromArray($style_alignRight_text);

        /*-------------------------Number of Rooms Available---------------------*/
        $sheet->setCellValue('A6', 'Number of Rooms Available');
        $sheet->setCellValue('B6', cal_days_in_year($dateToView)* $data_hotels->total_rooms);        
        $letterD = "D";
        for($month= 1; $month<=12; $month++ ){ 
            $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$month, $dateToView);														   
            $room_available = $dayInMonth * $data_hotels->total_rooms;
            if ($dayInMonth !=0 && $data_hotels->total_rooms !=0){
                $sheet->setCellValue("$letterD$ff",$room_available);                
            }else{
                $sheet->setCellValue("$letterD$ff",0); 
            }                        
            $letterD ++; 
         }         
        $sheet->getStyle('A6')->applyFromArray($styleDefaultBorder);
        $sheet->getStyle('B6:O6')->applyFromArray($style_alignRight_text);

        /*-------------------------% of Occupancy---------------------*/
        $sheet->setCellValue('A7', '% of Occupancy');
        if($data_hotels->total_rooms != 0){ 
            $sheet->setCellValue('B7', number_format($occupied_room->TOTAL_BUDGET/(cal_days_in_year($dateToView)* $data_hotels->total_rooms)*100,2).'%');             
        }else{
            $sheet->setCellValue('B7', 0);
        }                
        $letterD = "D";
        for($month= 1; $month<=12; $month++ ){ 
            if($data_hotels->total_rooms != 0){
                $budget_roomsold = $this->Smartreport_pnl_model->get_data_budgetroomsold($getidhotel_custom, $month, $dateToView);
                $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$month, $dateToView);
                $occupancy = ($budget_roomsold->BUDGETROOMSOLD / ($dayInMonth * $data_hotels->total_rooms))*100;
                $sheet->setCellValue("$letterD$gg", number_format($occupancy,2).'%');
                
            }else{
                $sheet->setCellValue("$letterD$gg", 0);
            }                       
            $letterD ++; 
         }         
        $sheet->getStyle('A7')->applyFromArray($styleDefaultBorder);
        $sheet->getStyle('B7:O7')->applyFromArray($style_alignRight_text);

        if($pnlCategoryRows > 0){
            $pnlCategoryData = $pnlCategoryResult->result();

            for($pnlCategory=0; $pnlCategory<count($pnlCategoryData); $pnlCategory++){

                $smartreport_pnllist = $this->Smartreport_pnl_model->select_pnllist_percategory($pnlCategoryData[$pnlCategory]->idpnlcategory);
                $grandtotal_pnlcategory = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory($pnlCategoryData[$pnlCategory]->idpnlcategory, $getidhotel_custom, $dateToView);
                $grandtotal_totalsales = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('2', $getidhotel_custom, $dateToView);                
                
               /*PNL STATISTIC ID 1*/

                if($pnlCategoryData[$pnlCategory]->idpnlcategory == 1){
                    for($pnlList=0; $pnlList<count($smartreport_pnllist); $pnlList++){
                        $total_budget = $this->Smartreport_pnl_model->get_total_budget( $smartreport_pnllist[$pnlList]->idpnl, $getidhotel_custom, $dateToView);
                        $sheet->setCellValue("A$hh", $smartreport_pnllist[$pnlList]->pnl_name); 
                        if($smartreport_pnllist[$pnlList]->idpnl == 1){ //idpnl 1 ada average room rate cara menghitungnya beda sendiri
                            if($total_room_revenue->TOTAL_BUDGET!=0 && $occupied_room->TOTAL_BUDGET !=0){
                                $sheet->setCellValue("B$hh", ($total_room_revenue->TOTAL_BUDGET/$occupied_room->TOTAL_BUDGET)); 
                                $letterD='D';
                                for($month=1; $month<='12'; $month++ ){ 															
                                    $budget_data = $this->Smartreport_pnl_model->get_data_budget( $smartreport_pnllist[$pnlList]->idpnl, $getidhotel_custom, $month, $dateToView);
                                    $sheet->setCellValue("$letterD$hh", ($budget_data->BUDGET));
                                    $letterD++;
                                }                               
                            }else{
                                $sheet->setCellValue("B$hh", '0');                                
                            }
                        }else{																			 
                            $sheet->setCellValue("B$hh",($total_budget->TOTAL_BUDGET));
                            $letterD='D';
                            for($month=1; $month<='12'; $month++ ){ 															
                                $budget_data = $this->Smartreport_pnl_model->get_data_budget( $smartreport_pnllist[$pnlList]->idpnl, $getidhotel_custom, $month, $dateToView);
                                $sheet->setCellValue("$letterD$hh", ($budget_data->BUDGET));
                                $letterD++;
                            }                           
                        }  
                        $sheet->getStyle("A$hh")->applyFromArray($styleDefaultBorder);
                        $sheet->getStyle("B$hh:O$hh")->applyFromArray($style_alignRight_text);
                        $hh++;
                    }
                }else{
                    $sheet->setCellValue("A$hh", $pnlCategoryData[$pnlCategory]->pnl_category);
                    $sheet->mergeCells("A$hh:O$hh");
                    $sheet->getStyle("A$hh:O$hh")->applyFromArray($style_alignCenter_subHeader); 
                    $ii = $hh+1;
                    for($pnlList=0; $pnlList<count($smartreport_pnllist); $pnlList++){
                        $total_budget = $this->Smartreport_pnl_model->get_total_budget( $smartreport_pnllist[$pnlList]->idpnl, $getidhotel_custom, $dateToView);
                        $sheet->setCellValue("A$ii", $smartreport_pnllist[$pnlList]->pnl_name); 
                        if( $total_budget->TOTAL_BUDGET != 0){
                            $sheet->setCellValue("B$ii",$total_budget->TOTAL_BUDGET);
                        }else{
                            $sheet->setCellValue("B$ii",0);
                        }
                        

                        if($smartreport_pnllist[$pnlList]->idpnlcategory !=1 && $smartreport_pnllist[$pnlList]->idpnlcategory !=2 && $smartreport_pnllist[$pnlList]->idpnlcategory !=6 ){
                            if($total_budget->TOTAL_BUDGET !=0 && $grandtotal_totalsales->GRANDTOTAL_PNLCATEGORY !=0 ){
                                $sheet->setCellValue("C$ii",number_format(($total_budget->TOTAL_BUDGET/$grandtotal_totalsales->GRANDTOTAL_PNLCATEGORY)*100,2).'%');
                            }else{
                                $sheet->setCellValue("C$ii",'0%');
                            }
                        }else if($smartreport_pnllist[$pnlList]->idpnlcategory == 2){
                            if($total_budget->TOTAL_BUDGET !=0 && $grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY !=0 ){
                                $sheet->setCellValue("C$ii", number_format(($total_budget->TOTAL_BUDGET/$grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY)*100,2).'%');                               
                            }else{
                                $sheet->setCellValue("C$ii", '0%');
                            }
                        }else if ($smartreport_pnllist[$pnlList]->idpnlcategory == 6) {
                            
                            $total_room_sales = $this->Smartreport_pnl_model->get_total_budget('4', $getidhotel_custom, $dateToView);
                            $total_fnb_sales = $this->Smartreport_pnl_model->get_total_budget('3', $getidhotel_custom, $dateToView);
                            $total_laundry_sales = $this->Smartreport_pnl_model->get_total_budget('5', $getidhotel_custom, $dateToView);
                            $total_business_sales = $this->Smartreport_pnl_model->get_total_budget('6', $getidhotel_custom, $dateToView);
                            $total_sport_sales = $this->Smartreport_pnl_model->get_total_budget('24', $getidhotel_custom, $dateToView);
                            $total_spa_sales = $this->Smartreport_pnl_model->get_total_budget('25', $getidhotel_custom, $dateToView);

                            $total_room_profit = $this->Smartreport_pnl_model->get_total_budget('14', $getidhotel_custom, $dateToView);
                            $total_fnb_profit = $this->Smartreport_pnl_model->get_total_budget('15', $getidhotel_custom, $dateToView);
                            $total_laundry_profit = $this->Smartreport_pnl_model->get_total_budget('26', $getidhotel_custom, $dateToView);
                            $total_business_profit = $this->Smartreport_pnl_model->get_total_budget('16', $getidhotel_custom, $dateToView);
                            $total_sport_profit = $this->Smartreport_pnl_model->get_total_budget('27', $getidhotel_custom, $dateToView);
                            $total_spa_profit = $this->Smartreport_pnl_model->get_total_budget('28', $getidhotel_custom, $dateToView);

                            if ($smartreport_pnllist[$pnlList]->idpnl == 14){
                                if ($total_room_profit->TOTAL_BUDGET !=0 && $total_room_sales->TOTAL_BUDGET !=0){
                                    $sheet->setCellValue("C$ii", number_format(($total_room_profit->TOTAL_BUDGET / $total_room_sales->TOTAL_BUDGET)*100,2).'%');
                                }else{
                                    $sheet->setCellValue("C$ii", '0%');
                                }
                            }else if ($smartreport_pnllist[$pnlList]->idpnl == 15){
                                if ($total_fnb_profit->TOTAL_BUDGET !=0 && $total_fnb_sales->TOTAL_BUDGET !=0){
                                    $sheet->setCellValue("C$ii", number_format(($total_fnb_profit->TOTAL_BUDGET / $total_fnb_sales->TOTAL_BUDGET)*100,2).'%');
                                }else{
                                    $sheet->setCellValue("C$ii", '0%');
                                }
                            }else if ($smartreport_pnllist[$pnlList]->idpnl == 26){
                                if ($total_laundry_profit->TOTAL_BUDGET !=0 && $total_laundry_sales->TOTAL_BUDGET !=0){
                                    $sheet->setCellValue("C$ii",number_format(($total_laundry_profit->TOTAL_BUDGET / $total_laundry_sales->TOTAL_BUDGET)*100,2).'%');
                                }else{
                                    $sheet->setCellValue("C$ii",'0%');
                                }
                            }else if ($smartreport_pnllist[$pnlList]->idpnl == 16){
                                if ($total_business_profit->TOTAL_BUDGET !=0 && $total_business_sales->TOTAL_BUDGET !=0){
                                    $sheet->setCellValue("C$ii", number_format(($total_business_profit->TOTAL_BUDGET / $total_business_sales->TOTAL_BUDGET)*100,2).'%');
                                }else{
                                    $sheet->setCellValue("C$ii", '0%');
                                }
                            }else if ($smartreport_pnllist[$pnlList]->idpnl == 27){
                                if ($total_sport_profit->TOTAL_BUDGET !=0 && $total_sport_sales->TOTAL_BUDGET !=0){
                                    $sheet->setCellValue("C$ii", number_format(($total_sport_profit->TOTAL_BUDGET / $total_sport_sales->TOTAL_BUDGET)*100,2).'%');
                                }else{
                                    $sheet->setCellValue("C$ii",'0%');
                                }
                            }else if ($smartreport_pnllist[$pnlList]->idpnl == 28){
                                if ($total_spa_profit->TOTAL_BUDGET !=0 && $total_spa_sales->TOTAL_BUDGET !=0){
                                    $sheet->setCellValue("C$ii", number_format(($total_spa_profit->TOTAL_BUDGET / $total_spa_sales->TOTAL_BUDGET)*100,2).'%');
                                }else{
                                    $sheet->setCellValue("C$ii",'0%');
                                }
                            }                            
                        }                        
                        $jj = $ii+1;
                        if($smartreport_pnllist[$pnlList]->idpnlcategory == 2){
                            if($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY !=0 ){
                                $sheet->setCellValue("C$jj", number_format(($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY/$grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY)*100,2).'%');
                            }else{
                                $sheet->setCellValue("C$jj", '0%');
                            }
                        }else if($smartreport_pnllist[$pnlList]->idpnlcategory !=2 && $smartreport_pnllist[$pnlList]->idpnlcategory !=1){
                            if($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY !=0 && $grandtotal_totalsales->GRANDTOTAL_PNLCATEGORY !=0 ){
                                $sheet->setCellValue("C$jj", number_format(($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY/$grandtotal_totalsales->GRANDTOTAL_PNLCATEGORY)*100,2).'%');
                            }else{
                                $sheet->setCellValue("C$jj", '0%');
                            }
                        }

                        $letterD='D';
                        for($month=1; $month<='12'; $month++ ){ 															
                            $budget_data = $this->Smartreport_pnl_model->get_data_budget( $smartreport_pnllist[$pnlList]->idpnl, $getidhotel_custom, $month, $dateToView);
                            $sheet->setCellValue("$letterD$ii", $budget_data->BUDGET);
                            $letterD++;
                        } 
                        $sheet->getStyle("A$ii")->applyFromArray($styleDefaultBorder);
                        $sheet->getStyle("B$ii:O$ii")->applyFromArray($style_alignRight_text);
                        $ii++;
                    }                    
                    $sheet->setCellValue("A$ii", "TOTAL ".$pnlCategoryData[$pnlCategory]->pnl_category);
                    if($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY != 0){
                        $sheet->setCellValue("B$ii", $grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY);
                    }else{
                        $sheet->setCellValue("B$ii", 0);
                    }
                   

                    $letterD='D';
                    for($month= 1; $month<=12; $month++ ){ 				
                        if ($pnlCategoryData[$pnlCategory]->idpnlcategory != 1){                            
                            $total_pnlcategorybymonth = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth($pnlCategoryData[$pnlCategory]->idpnlcategory, $getidhotel_custom, $month, $dateToView); 
                            if($total_pnlcategorybymonth->TOTAL_PNLCATEGORYBYMONTH != 0){
                                $sheet->setCellValue("$letterD$ii", $total_pnlcategorybymonth->TOTAL_PNLCATEGORYBYMONTH);
                            }else{
                                $sheet->setCellValue("$letterD$ii", 0);
                            }
                            
                        } 
                        $letterD++; 
                    }                  
                   
                    $sheet->getStyle("A$ii")->applyFromArray($styleDefaultBorder);
                    $sheet->getStyle("B$ii:O$ii")->applyFromArray($style_alignRight_text);
                    $hh=$ii+1;
                }
            }

            $sheet->getStyle("A$hh")->applyFromArray($styleDefaultBorder);
            $sheet->setCellValue("A$hh", "TOTAL UNDISTRIBUTED EXPENSE");
            $sheet->getStyle("B$hh:O$hh")->applyFromArray($style_alignRight_text);
            $grandtotal_und_payroll = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('7', $getidhotel_custom, $dateToView);
            $grandtotal_und_opr_exp = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('8', $getidhotel_custom, $dateToView);
            $grandtotal_und_exp = $grandtotal_und_payroll->GRANDTOTAL_PNLCATEGORY + $grandtotal_und_opr_exp->GRANDTOTAL_PNLCATEGORY;
            $sheet->setCellValue("B$hh", $grandtotal_und_exp);
            if($grandtotal_und_exp !=0 && $grandtotal_totalsales->GRANDTOTAL_PNLCATEGORY != 0){
                $sheet->setCellValue("C$hh", number_format(($grandtotal_und_exp/$grandtotal_totalsales->GRANDTOTAL_PNLCATEGORY)*100,2).'%');
            }else{
                $sheet->setCellValue("C$hh", '0%');
            }
            $letterD = 'D';
            for($month= 1; $month<=12; $month++ ){ 
                $total_und_payroll = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('7', $getidhotel_custom, $month, $dateToView); 
                $total_und_opr_exp = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('8', $getidhotel_custom, $month, $dateToView); 
                $total_und_exp =  $total_und_payroll->TOTAL_PNLCATEGORYBYMONTH + $total_und_opr_exp->TOTAL_PNLCATEGORYBYMONTH;
                ($total_und_exp != 0 ? $sheet->setCellValue("$letterD$hh",$total_und_exp) : $sheet->setCellValue("$letterD$hh",0));
                $letterD++;
            
            } 

            $kk = $hh+1;
            $sheet->getStyle("A$kk")->applyFromArray($styleDefaultBorder);
            $sheet->setCellValue("A$kk", "GROSS OPERATING PROFIT");
            $sheet->getStyle("B$kk:O$kk")->applyFromArray($style_alignRight_text);
            $grandtotal_dept_profit = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('6', $getidhotel_custom, $dateToView);
            $grandtotal_und_payroll = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('7', $getidhotel_custom, $dateToView);
            $grandtotal_und_opr_exp = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('8', $getidhotel_custom, $dateToView);
            $grandtotal_gross_opr_profit = $grandtotal_dept_profit->GRANDTOTAL_PNLCATEGORY - ($grandtotal_und_payroll->GRANDTOTAL_PNLCATEGORY + $grandtotal_und_opr_exp->GRANDTOTAL_PNLCATEGORY);	
            $sheet->setCellValue("B$kk", $grandtotal_gross_opr_profit);	
            ($grandtotal_gross_opr_profit != 0 && $grandtotal_totalsales->GRANDTOTAL_PNLCATEGORY != 0 
                ? $sheet->setCellValue("C$kk", number_format(($grandtotal_gross_opr_profit/$grandtotal_totalsales->GRANDTOTAL_PNLCATEGORY)*100,2).'%') 
                : $sheet->setCellValue("C$kk", '0%'));
            $letterD = 'D';
            for($month= 1; $month<=12; $month++ ){ 
                $total_dept_profit = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('6', $getidhotel_custom, $month, $dateToView); 
				$total_und_payroll = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('7', $getidhotel_custom, $month, $dateToView); 
				$total_und_opr_exp = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('8', $getidhotel_custom, $month, $dateToView);
                $gross_opr_profit = $total_dept_profit->TOTAL_PNLCATEGORYBYMONTH - ($total_und_payroll->TOTAL_PNLCATEGORYBYMONTH + $total_und_opr_exp->TOTAL_PNLCATEGORYBYMONTH);
               
                ($gross_opr_profit != 0 ? $sheet->setCellValue("$letterD$kk",$gross_opr_profit) : $sheet->setCellValue("$letterD$kk",0));
                $letterD++;
            
            } 
            
            $ll = $hh+2;
            $sheet->getStyle("A$ll")->applyFromArray($styleDefaultBorder);
            $sheet->setCellValue("A$ll", "G.O.P. %");
            $sheet->getStyle("B$ll:O$ll")->applyFromArray($style_alignRight_text);
            $grandtotal_total_sales = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('2', $getidhotel_custom, $dateToView);
            $grandtotal_dept_profit = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('6', $getidhotel_custom, $dateToView);
            $grandtotal_und_payroll = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('7', $getidhotel_custom, $dateToView);
            $grandtotal_und_opr_exp = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('8', $getidhotel_custom, $dateToView);
            $grandtotal_gross_opr_profit = $grandtotal_dept_profit->GRANDTOTAL_PNLCATEGORY - ($grandtotal_und_payroll->GRANDTOTAL_PNLCATEGORY + $grandtotal_und_opr_exp->GRANDTOTAL_PNLCATEGORY);	
            if($grandtotal_gross_opr_profit != 0 && $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY !=0){
                $grandtotal_gop = ($grandtotal_gross_opr_profit / $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY)*100;	
            }else{
                $grandtotal_gop = 0;
            }	
            $sheet->setCellValue("C$ll", number_format($grandtotal_gop,2).'%');	            
            $letterD = 'D';
            for($month= 1; $month<=12; $month++ ){ 
                $total_sales = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('2', $getidhotel_custom, $month, $dateToView); 
                $total_dept_profit = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('6', $getidhotel_custom, $month, $dateToView); 
                $total_und_payroll = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('7', $getidhotel_custom, $month, $dateToView); 
                $total_und_opr_exp = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('8', $getidhotel_custom, $month, $dateToView);                
                if($total_dept_profit->TOTAL_PNLCATEGORYBYMONTH != 0 && $total_und_payroll->TOTAL_PNLCATEGORYBYMONTH !=0 && $total_und_opr_exp->TOTAL_PNLCATEGORYBYMONTH != 0 && $total_sales->TOTAL_PNLCATEGORYBYMONTH != 0 ){
                    $gross_opr_profit = $total_dept_profit->TOTAL_PNLCATEGORYBYMONTH - ($total_und_payroll->TOTAL_PNLCATEGORYBYMONTH + $total_und_opr_exp->TOTAL_PNLCATEGORYBYMONTH);
                    $gop = ($gross_opr_profit / $total_sales->TOTAL_PNLCATEGORYBYMONTH )*100;
                    $sheet->setCellValue("$letterD$ll", number_format($gop ,2).'%');
                }else{
                    $sheet->setCellValue("$letterD$ll",'0%');
                }
                $letterD++;
            
            } 

            $mm = $hh+3;
            $sheet->getStyle("A$mm")->applyFromArray($styleDefaultBorder);
            $sheet->setCellValue("A$mm", "PAYROLL");
            $sheet->getStyle("B$mm:O$mm")->applyFromArray($style_alignRight_text);
            $grandtotal_total_sales = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('2', $getidhotel_custom, $dateToView);
            $grandtotal_payroll_rel = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('4', $getidhotel_custom, $dateToView);
            $grandtotal_und_payroll = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('7', $getidhotel_custom, $dateToView);
            if($grandtotal_payroll_rel->GRANDTOTAL_PNLCATEGORY != 0 && $grandtotal_und_payroll->GRANDTOTAL_PNLCATEGORY != 0 && $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY !=0){
                $grandtotal_all_payroll = (($grandtotal_payroll_rel->GRANDTOTAL_PNLCATEGORY + $grandtotal_und_payroll->GRANDTOTAL_PNLCATEGORY)  / $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY)*100;
            }else{
                $grandtotal_all_payroll = 0;
            }	
            $sheet->setCellValue("C$mm", number_format($grandtotal_all_payroll,2).'%');	            
            $letterD = 'D';
            for($month= 1; $month<=12; $month++ ){ 
                $total_sales = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('2', $getidhotel_custom, $month, $dateToView); 
                $total_payroll_rel = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('4', $getidhotel_custom, $month, $dateToView);  
                $total_und_payroll = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('7', $getidhotel_custom, $month, $dateToView); 
                if($total_payroll_rel->TOTAL_PNLCATEGORYBYMONTH !=0 && $total_und_payroll->TOTAL_PNLCATEGORYBYMONTH !=0 && $total_sales->TOTAL_PNLCATEGORYBYMONTH !=0){
                    $total_all_payroll = (($total_payroll_rel->TOTAL_PNLCATEGORYBYMONTH + $total_und_payroll->TOTAL_PNLCATEGORYBYMONTH)  / $total_sales->TOTAL_PNLCATEGORYBYMONTH)*100;															
                    $sheet->setCellValue("$letterD$mm", number_format($total_all_payroll,2).'%');
                }else{
                    $sheet->setCellValue("$letterD$mm", '0%');
                }
                $letterD++;
            
            } 

            $nn = $hh+4;
            $sheet->getStyle("A$nn")->applyFromArray($styleDefaultBorder);
            $sheet->setCellValue("A$nn", "ENERGY COST");
            $sheet->getStyle("B$nn:O$nn")->applyFromArray($style_alignRight_text);
            $grandtotal_total_sales = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('2', $getidhotel_custom, $dateToView);
			$grandtotal_energy_cost = $this->Smartreport_pnl_model->get_total_budget('22', $getidhotel_custom, $dateToView);
            if($grandtotal_energy_cost->TOTAL_BUDGET != 0 && $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY != 0 ){
                $grandtotal_budget_energycost = ($grandtotal_energy_cost->TOTAL_BUDGET / $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY)*100;	
            }else{
                $grandtotal_budget_energycost = 0;
            }	
            $sheet->setCellValue("C$nn", number_format($grandtotal_budget_energycost,2).'%');	            
            $letterD = 'D';
            for($month= 1; $month<=12; $month++ ){ 
                $total_sales = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('2', $getidhotel_custom, $month, $dateToView); 
                $energy_cost = $this->Smartreport_pnl_model->get_data_budget('22', $getidhotel_custom, $month, $dateToView);
                if($energy_cost->BUDGET !=0 && $total_sales->TOTAL_PNLCATEGORYBYMONTH !=0 ){
                    $budget_energycost = ($energy_cost->BUDGET / $total_sales->TOTAL_PNLCATEGORYBYMONTH)*100;
                    $sheet->setCellValue("$letterD$nn",  number_format($budget_energycost,2).'%');
                }else{
                    $sheet->setCellValue("$letterD$nn",  '0%');
                }
                $letterD++;
            
            } 

            $oo = $hh+5;
            $sheet->getStyle("A$oo")->applyFromArray($styleDefaultBorder);
            $sheet->setCellValue("A$oo", "EXPENSE");
            $sheet->getStyle("B$oo:O$oo")->applyFromArray($style_alignRight_text);
            $grandtotal_total_sales = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('2', $getidhotel_custom, $dateToView);
            $grandtotal_other_expense = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('5', $getidhotel_custom, $dateToView);
            $grandtotal_ang_und_exp = $this->Smartreport_pnl_model->get_total_budget('20', $getidhotel_custom, $dateToView);
            $grandtotal_pomec_und_exp = $this->Smartreport_pnl_model->get_total_budget('21', $getidhotel_custom, $dateToView);
            $grandtotal_snm_und_exp = $this->Smartreport_pnl_model->get_total_budget('23', $getidhotel_custom, $dateToView);
            if($grandtotal_ang_und_exp->TOTAL_BUDGET !=0 && $grandtotal_pomec_und_exp->TOTAL_BUDGET !=0 && $grandtotal_snm_und_exp->TOTAL_BUDGET !=0 &&  $grandtotal_other_expense->GRANDTOTAL_PNLCATEGORY !=0 && $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY !=0 ){
                $grandtotal_budget_expense = (($grandtotal_ang_und_exp->TOTAL_BUDGET + $grandtotal_pomec_und_exp->TOTAL_BUDGET + $grandtotal_snm_und_exp->TOTAL_BUDGET + $grandtotal_other_expense->GRANDTOTAL_PNLCATEGORY)/$grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY)*100;
            }else{
                $grandtotal_budget_expense = 0;
            }	
            $sheet->setCellValue("C$oo", number_format($grandtotal_budget_expense,2).'%');	            
            $letterD = 'D';
            for($month= 1; $month<=12; $month++ ){ 
                $total_sales = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('2', $getidhotel_custom, $month, $dateToView); 
                $other_expense = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('5', $getidhotel_custom, $month, $dateToView); 
                $ang_und_exp = $this->Smartreport_pnl_model->get_data_budget('20', $getidhotel_custom, $month, $dateToView);
                $pomec_und_exp = $this->Smartreport_pnl_model->get_data_budget('21', $getidhotel_custom, $month, $dateToView);
                $snm_und_exp = $this->Smartreport_pnl_model->get_data_budget('23', $getidhotel_custom, $month, $dateToView);
                if($ang_und_exp->BUDGET !=0 && $pomec_und_exp->BUDGET !=0 && $snm_und_exp->BUDGET !=0 && $other_expense->TOTAL_PNLCATEGORYBYMONTH !=0 && $total_sales->TOTAL_PNLCATEGORYBYMONTH !=0){
                    $budget_expense = (($ang_und_exp->BUDGET + $pomec_und_exp->BUDGET + $snm_und_exp->BUDGET + $other_expense->TOTAL_PNLCATEGORYBYMONTH)/$total_sales->TOTAL_PNLCATEGORYBYMONTH)*100;
                    $sheet->setCellValue("$letterD$oo",number_format($budget_expense,2).'%');
                }else{
                    $sheet->setCellValue("$letterD$oo",'0%');
                }
                $letterD++;
            
            }
            
            $pp = $hh+6;
            $sheet->getStyle("A$pp")->applyFromArray($styleDefaultBorder);
            $sheet->setCellValue("A$pp", "COST OF SALES");
            $sheet->getStyle("B$pp:O$pp")->applyFromArray($style_alignRight_text);
            $grandtotal_total_sales = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('2', $getidhotel_custom, $dateToView);
            $grandtotal_cost_of_sales = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('3', $getidhotel_custom, $dateToView);
            if($grandtotal_cost_of_sales->GRANDTOTAL_PNLCATEGORY !=0 && $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY !=0){
                $grandtotal_budget_cos = ($grandtotal_cost_of_sales->GRANDTOTAL_PNLCATEGORY / $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY)*100;
            }else{
                $grandtotal_budget_cos = 0;
            }	
            $sheet->setCellValue("C$pp", number_format($grandtotal_budget_cos,2).'%');	            
            $letterD = 'D';
            for($month= 1; $month<=12; $month++ ){ 
                $total_sales = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('2', $getidhotel_custom, $month, $dateToView); 
                $cost_of_sales = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('3', $getidhotel_custom, $month, $dateToView); 
                if($cost_of_sales->TOTAL_PNLCATEGORYBYMONTH !=0 && $total_sales->TOTAL_PNLCATEGORYBYMONTH !=0){
                    $budget_cos = ($cost_of_sales->TOTAL_PNLCATEGORYBYMONTH / $total_sales->TOTAL_PNLCATEGORYBYMONTH)*100;
                    $sheet->setCellValue("$letterD$pp", number_format($budget_cos,2).'%');
                }else{
                    $sheet->setCellValue("$letterD$pp", '0%');
                }
                $letterD++;            
            }
            
            $qq = $hh+7;
            $sheet->getStyle("A$qq")->applyFromArray($styleDefaultBorder);
            $sheet->setCellValue("A$qq", "MARKETING EXPENSE");
            $sheet->getStyle("B$qq:O$qq")->applyFromArray($style_alignRight_text);
            $grandtotal_total_sales = $this->Smartreport_pnl_model->get_grandtotal_pnlcategory('2', $getidhotel_custom, $dateToView);
            $grandtotal_snm_und_exp = $this->Smartreport_pnl_model->get_total_budget('23', $getidhotel_custom, $dateToView);
            if($grandtotal_snm_und_exp->TOTAL_BUDGET != 0 && $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY != 0){
                $grandtotal_budget_salesmarketing = ($grandtotal_snm_und_exp->TOTAL_BUDGET / $grandtotal_total_sales->GRANDTOTAL_PNLCATEGORY)*100;
            }else{
                $grandtotal_budget_salesmarketing = 0;
            }	
            $sheet->setCellValue("C$qq", number_format($grandtotal_budget_salesmarketing,2).'%');	            
            $letterD = 'D';
            for($month= 1; $month<=12; $month++ ){ 
                $total_sales = $this->Smartreport_pnl_model->get_total_pnlcategorybymonth('2', $getidhotel_custom, $month, $dateToView); 														
                $snm_und_exp = $this->Smartreport_pnl_model->get_data_budget('23', $getidhotel_custom, $month, $dateToView);
                if($snm_und_exp->BUDGET != 0 && $total_sales->TOTAL_PNLCATEGORYBYMONTH !=0){
                    $budget_salesmarketing = ($snm_und_exp->BUDGET / $total_sales->TOTAL_PNLCATEGORYBYMONTH)*100;
                    $sheet->setCellValue("$letterD$qq", number_format($budget_salesmarketing,2).'%');
                }else{
                    $sheet->setCellValue("$letterD$qq", '0%');
                }
                $letterD++;            
            } 

            $rr=$hh+8;
            $sheet->mergeCells("A$rr:O$rr");
            $sheet->setCellValue("A$rr", "Downloaded by ".$username." ". date("d F Y H:i"));

        }

        $writer = new Xlsx($excelBudget);
        
        $filename = $data_hotels->hotels_name." Budget PNL Kagum Hotels ".$dateToView;
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
    }

    function insert_budget_pnl(){
        $user_level = $this->session->userdata('user_level');
        $idhotels= $this->session->userdata('user_hotel');
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
            
            $getidhotel_custom = $this->input->post('idhotelcustom', TRUE);
            if($getidhotel_custom == NULL || $getidhotel_custom == '' ){
                $getidhotel_custom = $idhotels; 
            } 
            
            $year_budget = $this->input->post('year_budget');
            $month_budget = $this->input->post('month_budget');
            $idpnl = $_POST['idpnl'];
            $budget_value = $_POST['budget_value'];            
            $date_budget = $year_budget.'-'.$month_budget.'-'.'01';
            $data_budget = array();
            $count_budget = 0;

            foreach($idpnl as $idp ){       
        
                if($idp != ''){
                      $dt_pnl = $this->Smartreport_pnl_model->select_pnlbyiddate($getidhotel_custom,$idp,$date_budget)->num_rows();
                    if($dt_pnl > 0){
                      $this->Smartreport_pnl_model->delete_pnlbyiddate($getidhotel_custom,$idp,$date_budget);
                    }
                    array_push($data_budget,array(
                      //'idanalysis'=> $idhotels[$count_anl].str_replace("-","",$date_analysis),
                      'idpnl'=> $idpnl[$count_budget],
                      'budget_value'=>$budget_value[$count_budget],
                      'idhotels'=>$getidhotel_custom,
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
        $idhotels= $this->session->userdata('user_hotel');
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
        
        $getidhotel_custom = $this->input->post('idhotelcustom', TRUE);
        if($getidhotel_custom == NULL || $getidhotel_custom == '' ){
            $getidhotel_custom = $idhotels; 
        } 
        
        $year_budget = $this->input->post('year_budget' , TRUE);
        $month_budget = $this->input->post('month_budget', TRUE);
        $date_budget = $year_budget.'-'.$month_budget.'-'.'01'; 
        $idpnl = $this->input->post('idpnllist', TRUE);      
        $data_budget = $this->Smartreport_pnl_model->select_budgetpnlbydate($getidhotel_custom,$idpnl,$date_budget);
            if($data_budget->num_rows() > 0){
            $data = array(    
                'idpnl'=>$idpnl,
                'budget_value'=>$this->input->post('budget_value', TRUE),
                'date_budget'=>$date_budget);  
                $this->Smartreport_pnl_model->update_budgetpnlbyid($data_budget->row()->idbudget,$data);

            }else{
            $data = array(       
                'idhotels'=> $getidhotel_custom,
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

    function budget_pnlpdf(){
        //$user_level = $this->session->userdata('user_level');        
        //$check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        //if($check_permission->num_rows() == 1){
          
            $userHotelForBudget = $this->session->userdata('user_hotel');
            $get_yearbudget = $this->input->get('year_budget', TRUE);
            $get_idhotelcustom = $this->input->get('idhotelcustom', TRUE);
            if($get_idhotelcustom == NULL){
               $get_idhotelcustom = $userHotelForBudget; 
            } 

            $page_data['lang_pnl_budget'] = $this->lang->line('pnl_budget');
            $smartreport_pnlcategory = $this->Smartreport_pnl_model->get_data_pnlcategory();
            $page_data['smartreport_pnlcategory_data'] = $smartreport_pnlcategory;
            $page_data['dateToView'] = $get_yearbudget;
            $page_data['idhotel_custom'] = $get_idhotelcustom;
               
            
         
    
          $this->pdfgenerator->setPaper('A4', 'landscape');
          $this->pdfgenerator->filename = "Report Budget.pdf";
          $this->pdfgenerator->load_view('smartreport/pdf_budgetpnl', $page_data);
          //$this->load->view('smartreport/pdf_budgetpnl',$page_data);
       // }else{
       //     redirect('errorpage/error403');
        //}
    }

    function actual_pnl(){
        $user_level = $this->session->userdata('user_level');
        $userHotelForActual = $this->session->userdata('user_hotel');
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);    
        if($check_permission->num_rows() == 1){
            $page_data['page_name'] = 'actual_pnl';
            //$getyear_budget = strtotime($this->input->get('year_budget', TRUE));
           // $year_budget = date("Y", $getyear_budget);  
           
           $getidhotel_custom = $this->input->get('idhotelcustom', TRUE);
            if($getidhotel_custom == NULL){
                $getidhotel_custom = $userHotelForActual; 
            } 

            $page_data['lang_dashboard'] = $this->lang->line('dashboard');
            $page_data['lang_dashboard_hotel'] = $this->lang->line('dashboard_hotel');
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

            $page_data['lang_choose_hotels'] = $this->lang->line('choose_hotels'); 

            
            $smartreport_pnlcategory = $this->Smartreport_actual_model->get_data_pnlcategory();
            $page_data['smartreport_pnlcategory_data'] = $smartreport_pnlcategory;
            $page_data['yearact'] =$this->input->get('year_actual', TRUE);
            $page_data['monthact'] =$this->input->get('month_actual', TRUE);

            $page_data['idhotel_custom'] = $getidhotel_custom;

            $this->load->view('smartreport/index', $page_data);
        }else{
            redirect('errorpage/error403');
        }
    }

    function insert_actual_pnl(){
        $user_level = $this->session->userdata('user_level');
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);  
        $idhotels= $this->session->userdata('user_hotel');  
        if($check_permission->num_rows() == 1){

            $getidhotel_custom = $this->input->post('idhotelcustom', TRUE);
            if($getidhotel_custom == NULL || $getidhotel_custom == '' ){
                $getidhotel_custom = $idhotels; 
            } 

            
            $year_actual = $this->input->post('year_actual');
            $month_actual = $this->input->post('month_actual');
            $idpnl = $_POST['idpnl'];
            $actual_value = $_POST['actual_value'];            
            $date_actual = $year_actual.'-'.$month_actual.'-'.'01';
            $data_actual = array();
            $count_actual = 0;

            

            foreach($idpnl as $idp ){       
        
                if($idp != ''){
                      $dt_pnl = $this->Smartreport_actual_model->select_pnlbyiddate($getidhotel_custom,$idp,$date_actual)->num_rows();
                    if($dt_pnl > 0){
                      $this->Smartreport_actual_model->delete_pnlbyiddate($getidhotel_custom,$idp,$date_actual);
                    }
                    array_push($data_actual,array(
                      //'idanalysis'=> $idhotels[$count_anl].str_replace("-","",$date_analysis),
                      'idpnl'=> $idpnl[$count_actual],
                      'actual_value'=>$actual_value[$count_actual],
                      'idhotels'=>$getidhotel_custom,
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
    
    
    function add_actual_data_bypnl(){
        $user_level = $this->session->userdata('user_level');
        $check_permission =  $this->Rolespermissions_model->check_permissions($this->contoller_name,$this->function_name,$user_level);  
        $idhotels= $this->session->userdata('user_hotel');  
        if($check_permission->num_rows() == 1){

        $getidhotel_custom = $this->input->post('idhotelcustom', TRUE);
        if($getidhotel_custom == NULL || $getidhotel_custom == '' ){
            $getidhotel_custom = $idhotels; 
        } 
        
        $idhotels= $this->session->userdata('user_hotel');
        $year_actual = $this->input->post('year_actual' , TRUE);
        $month_actual = $this->input->post('month_actual', TRUE);
        $date_actual = $year_actual.'-'.$month_actual.'-'.'01'; 
        $idpnl = $this->input->post('idpnllist', TRUE);      
        $data_actual = $this->Smartreport_actual_model->select_actualpnlbydate($getidhotel_custom,$idpnl,$date_actual);
            if($data_actual->num_rows() > 0){
            $data = array(    
                'idpnl'=>$idpnl,
                'actual_value'=>$this->input->post('actual_value', TRUE),
                'date_actual'=>$date_actual);  
                $this->Smartreport_actual_model->update_actualpnlbyid($data_actual->row()->idactual,$data);

            }else{
            $data = array(       
                'idhotels'=> $getidhotel_custom,
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
    
/* FUNCTION AJAX*/
    function get_pnllist(){
		$idpnlcategory = $this->input->post('id',TRUE);
		$data = $this->Smartreport_pnl_model->get_sub_category($idpnlcategory)->result();
		echo json_encode($data);
    }

}