<style>
.form-control:focus {

  box-shadow: inset 1px 2px 4px rgba(0, 0, 0, 0.01), 0px 0px 8px rgba(0, 0, 0, 0.2);
}

.customEryan{
	font-size: 9px;
	width: 100%;  
}

.hidden{
	display: none !important;
}


.header-print {
    display: table-header-group;
}

.rata-kanan{
	vertical-align: middle; 
	text-align: right;
}

</style>

<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/tables/datatables/extensions/fixed_columns.min.js"></script>

<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<!--<script src="<?php //echo base_url();?>assets/backend/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>-->



<script src="<?php echo base_url();?>assets/backend/global_assets/js/demo_pages/datatables_extension_fixed_columns.js"></script>



<script type="text/javascript">
	   $(document).ready(function(){  	
	
        $('.daterange-single').daterangepicker({ 
            singleDatePicker: true,
            locale: {
                format: 'DD-MM-YYYY'
            }
        });
		$('.custom_category').select2({
			//minimumInputLength: 3
		});	
	});
</script> 
<?php

    $lm=strtotime("-1 Month");
    $ly=strtotime("-1 Year");

	if ($yearact == NULL && $monthact == NULL){
        $yearact = date('Y');
        $monthact = date('m');
        $lastmonth = date('m',$lm);
        $lastyear = date('Y',$ly);
	}else if ($monthact == '01'){//jika difilter adalah bulan januari 2019 maka last monthnya adalah desember 2018 tahun sebelumnya
         $lastmonth = '12' ;
         $lastyear = $yearact - 1;
    }else{
        $lastmonth = $monthact - 1;
    }

    $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$monthact, $yearact);
    $dayInMonthLast = cal_days_in_month(CAL_GREGORIAN,$lastmonth, $yearact);
    $startdate_ytd = $yearact.'-01-'.'01';
	$enddate_ytd = $yearact.'-'.$monthact.'-'.$dayInMonth;
    $diffdateytd = date_diff(new DateTime($startdate_ytd), new DateTime($enddate_ytd)); 
   
    $url_year = $yearact;
    $url_monthact = $monthact;
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
    


$total_rooms = $this->Dashboard_model->getDataHotel($user_ho);
$trr_actual_mtd = $this->Smartreport_actual_model->get_total_actual( "4", $user_ho, $monthact, $yearact); //4 adalah idpnl Room
$rs_actual_mtd = $this->Smartreport_actual_model->get_total_actual( "7", $user_ho, $monthact, $yearact); //7 adalah idpnl occupied room / room sold
$trr_budget_mtd = $this->Smartreport_actual_model->get_total_budget( "4", $user_ho, $monthact, $yearact); //4 adalah idpnl Room
$rs_budget_mtd = $this->Smartreport_actual_model->get_total_budget( "7", $user_ho, $monthact, $yearact); //7 adalah idpnl occupied room / room sold

if ($monthact == '01'){ //jika difilter adalah bulan januari 2019 maka last monthnya adalah desember 2018 tahun sebelumnya
    $trr_actual_lastmtd = $this->Smartreport_actual_model->get_total_actual( "4", $user_ho, $lastmonth, $lastyear); //4 adalah idpnl Room
	$rs_actual_lastmtd = $this->Smartreport_actual_model->get_total_actual( "7", $user_ho, $lastmonth, $lastyear); //7 adalah idpnl occupied room / room sold
	$trr_budget_lastmtd = $this->Smartreport_actual_model->get_total_budget( "4", $user_ho, $lastmonth, $lastyear); //4 adalah idpnl Room
    $rs_budget_lastmtd = $this->Smartreport_actual_model->get_total_budget( "7", $user_ho, $lastmonth, $lastyear); //7 adalah idpnl occupied room / room sold
    
}else{
    $trr_actual_lastmtd = $this->Smartreport_actual_model->get_total_actual( "4", $user_ho, $lastmonth, $yearact); //4 adalah idpnl Room
	$rs_actual_lastmtd = $this->Smartreport_actual_model->get_total_actual( "7", $user_ho, $lastmonth, $yearact); //7 adalah idpnl occupied room / room sold
	$trr_budget_lastmtd = $this->Smartreport_actual_model->get_total_budget( "4", $user_ho, $lastmonth, $yearact); //4 adalah idpnl Room
    $rs_budget_lastmtd = $this->Smartreport_actual_model->get_total_budget( "7", $user_ho, $lastmonth, $yearact); //7 adalah idpnl occupied room / room sold
}

$trr_actual_ytd = $this->Smartreport_actual_model->get_total_actualytd( "4", $user_ho, $startdate_ytd, $enddate_ytd); //4 adalah idpnl Room
$rs_actual_ytd = $this->Smartreport_actual_model->get_total_actualytd( "7", $user_ho, $startdate_ytd, $enddate_ytd); //7 adalah idpnl occupied room / room sold
$trr_budget_ytd = $this->Smartreport_actual_model->get_total_budgetytd( "4", $user_ho, $startdate_ytd, $enddate_ytd); //4 adalah idpnl Room
$rs_budget_ytd = $this->Smartreport_actual_model->get_total_budgetytd( "7", $user_ho, $startdate_ytd, $enddate_ytd); //7 adalah idpnl occupied room / room sold



function cal_days_in_year($yearact){
	$days=0; 
	for($month=1;$month<=12;$month++){ 
			$days = $days + cal_days_in_month(CAL_GREGORIAN,$month,$yearact);
		}
	return $days;
	}
	
?>
<!-- Page header -->
        <div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold"><?php echo $lang_pnl; ?></span> - <?php echo $lang_pnl_expense; ?></h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<div class="header-elements d-none">
						
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="card">
				<div class="card-header header-elements-inline">
					<h6 class="card-title"><strong><?php  $hotel = $this->Dashboard_model->getDataHotel($user_ho); echo $hotel->hotels_name .' - '.$lang_pnl_expense; ?></strong></h6>
					<div class="header-elements">
						<div class="list-icons">
				            <a class="list-icons-item" data-action="collapse"></a>
				            
				        </div>
			        </div>
				</div>
				
				<div class="card-body">
					<ul class="nav nav-tabs nav-tabs-highlight justify-content-end">
						<li class="nav-item"><a href="#right-pnl1" class="nav-link active" data-toggle="tab"><i class="icon-stats-dots mr-2"></i><?php echo $lang_actual_data?></a></li>
					    <li class="nav-item"><a href="#right-pnl2" class="nav-link" data-toggle="tab"><i class="icon-stack-plus mr-2"></i><?php echo $lang_add_data?></a></li>
					</ul>

				    <div class="tab-content">
						
						<div class="tab-pane fade show active" id="right-pnl1">
							<form action="<?php echo base_url()?>smartreportpnl/actual_pnl" method="get" accept-charset="utf-8" enctype="multipart/form-data">		
								<div class="col-md-5">	
									<div class="form-group">
										<div class="row">	
                                        <div class="col-sm-5">
												<label><?php echo $lang_month; ?></label>
													<select name="month_actual" class="form-control" required>
														<option <?php if ($monthact === '01') {echo 'selected="selected"';} ?> value="01">January</option>
														<option <?php if ($monthact === '02') {echo 'selected="selected"';} ?> value="02">February</option>
														<option <?php if ($monthact === '03') {echo 'selected="selected"';} ?> value="03">March</option>
														<option <?php if ($monthact === '04') {echo 'selected="selected"';} ?> value="04">April</option>
														<option <?php if ($monthact === '05') {echo 'selected="selected"';} ?> value="05">May</option>
														<option <?php if ($monthact === '06') {echo 'selected="selected"';} ?> value="06">June</option>
														<option <?php if ($monthact === '07') {echo 'selected="selected"';} ?> value="07">July</option>
														<option <?php if ($monthact === '08') {echo 'selected="selected"';} ?> value="08">August</option>
														<option <?php if ($monthact === '09') {echo 'selected="selected"';} ?> value="09">September</option>
														<option <?php if ($monthact === '10') {echo 'selected="selected"';} ?> value="10">October</option>
														<option <?php if ($monthact === '11') {echo 'selected="selected"';} ?> value="11">November</option>
														<option <?php if ($monthact === '12') {echo 'selected="selected"';} ?> value="12">December</option>
													</select>
											</div>										
                                            <div class="col-sm-5">
												<label><?php echo $lang_year ?></label>
												<select name="year_actual" class="form-control" required>
													<?php
														for($i=date('Y'); $i>=2018; $i--) {
														$selected = '';
														if ($yearact == $i) $selected = ' selected="selected"';
														print('<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n");
													}?>
												</select>  
											</div>

											<div class="col-sm-1">
												<div class="form-group">
													<label>&emsp;</label><br/>
													<button type="submit" class="btn bg-teal-400 "><?php echo $lang_search; ?></button>
												</div>
											</div>
                                            
                                        </div>
                                    </div> 
								</div>
							</form>	
							<div class="table-responsive">
								<table class="table table-bordered text-nowrap table-hover customEryan datatable-nobutton-1column">
									<thead style="vertical-align: middle; text-align: center">
										<tr >
											<th rowspan="2"><?php echo $lang_description; ?></th>											
											<th colspan="5">MTD</th>											
											<th colspan="5">LAST MONTH</th>
											<th colspan="5">YTD</th>																						
										</tr>
										<tr>
											<td>Actual</td>
                                            <td>(%)</td>
                                            <td>Budget</td>
                                            <td>(%)</td>
                                            <td>Variance</td>

                                            <td>Actual</td>
                                            <td>(%)</td>
                                            <td>Budget</td>
                                            <td>(%)</td>
                                            <td>Variance</td>

                                            <td>Actual</td>
                                            <td>(%)</td>
                                            <td>Budget</td>
                                            <td>(%)</td>
                                            <td>Variance</td>
										</tr>
									</thead>
									<tbody>
											<tr>
												<td><strong>STATISCTIC</strong></td>
												<td colspan="15"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>	
												<td style="display: none;"></td>
												<td style="display: none;"></td>	
                                                <td style="display: none;"></td>
                                                <td style="display: none;"></td>
											</tr>
											
											<!--Number of Days-->
											<tr>
                                                <td>&emsp;&emsp;Number of Days</td>
                                                
												<td class="rata-kanan"><?= $dayInMonth; ?></td>
												<td></td>												
                                                <td class="rata-kanan"><?= $dayInMonth; ?></td>
                                                <td></td>	
                                                <td></td>	

                                                <td class="rata-kanan"><?= $dayInMonthLast; ?></td>
												<td></td>												
                                                <td class="rata-kanan"><?= $dayInMonthLast; ?></td>
                                                <td></td>	
                                                <td></td>	

                                                <td class="rata-kanan"><?= $diffdateytd->days + 1 ;?></td>
												<td></td>												
                                                <td class="rata-kanan"><?= $diffdateytd->days + 1 ;?></td>
                                                <td></td>	
                                                <td></td>	
																							
											</tr>
											
											<!--Number of Rooms Available-->
											<tr>
                                                <td>&emsp;&emsp;Number of Rooms Available</td>
                                                
												<td class="rata-kanan"><?= $dayInMonth * $total_rooms->total_rooms; ?></td>
												<td></td>												
                                                <td class="rata-kanan"><?= $dayInMonth * $total_rooms->total_rooms; ?></td>
                                                <td></td>	
                                                <td></td>	

                                                <td class="rata-kanan"><?= $dayInMonthLast * $total_rooms->total_rooms; ?></td>
												<td></td>												
                                                <td class="rata-kanan"><?= $dayInMonthLast * $total_rooms->total_rooms; ?></td>
                                                <td></td>	
                                                <td></td>		

                                                <td class="rata-kanan"><?= ($diffdateytd->days + 1) * $total_rooms->total_rooms; ?></td>
												<td></td>												
                                                <td class="rata-kanan"><?=($diffdateytd->days + 1) * $total_rooms->total_rooms;?></td>
                                                <td></td>	
                                                <td></td>	

												
											</tr>
											
											<!--Occupancy-->
											<tr>
                                                <td>&emsp;&emsp;% of Occupancy</td>
                                                
												<td class="rata-kanan"><?php $occupancy_actual_mtd = ($rs_actual_mtd->TOTAL_ACTUAL / ($dayInMonth * $total_rooms->total_rooms))*100;
																echo number_format($occupancy_actual_mtd,2).'%';?></td>
												<td></td>																								
                                                <td class="rata-kanan"><?php $occupancy_budget_mtd = ($rs_budget_mtd->TOTAL_BUDGET / ($dayInMonth * $total_rooms->total_rooms))*100;
																echo number_format($occupancy_budget_mtd,2).'%';?> </td> 
                                                <td></td>
                                                <td></td> 

                                                <td class="rata-kanan"><?php $occupancy_actual_lastmtd = ($rs_actual_lastmtd->TOTAL_ACTUAL / ($dayInMonthLast * $total_rooms->total_rooms))*100;
																echo number_format($occupancy_actual_lastmtd,2).'%';?></td>
												<td></td>																								
                                                <td class="rata-kanan"><?php $occupancy_budget_lastmtd = ($rs_budget_lastmtd->TOTAL_BUDGET / ($dayInMonthLast * $total_rooms->total_rooms))*100;
																echo number_format($occupancy_budget_lastmtd,2).'%';?></td> 
                                                <td></td>
                                                <td></td> 

                                                <td class="rata-kanan"><?php $occupancy_actual_ytd = ($rs_actual_ytd->TOTAL_ACTUAL / (($diffdateytd->days + 1) * $total_rooms->total_rooms))*100;
																echo number_format($occupancy_actual_ytd,2).'%';?></td>
												<td></td>																								
                                                <td class="rata-kanan"><?php $occupancy_budget_ytd = ($rs_budget_ytd->TOTAL_BUDGET / (($diffdateytd->days + 1) * $total_rooms->total_rooms))*100;
																echo number_format($occupancy_budget_ytd,2).'%';?></td> 
                                                <td></td>
                                                <td></td> 
												
											</tr>
										<?php foreach ($smartreport_pnlcategory_data as $smartreport_pnlcategory){
												/* Terlalu Dinamis parah, PNL Statistic sudah hilang karena sudah jadi header diatas IDPNLCATEGORY 1 itu adalah STATISTIC*/
												//$yearact itu ada year
												$smartreport_pnllist_data = $this->Smartreport_actual_model->select_pnllist_percategory($smartreport_pnlcategory->idpnlcategory);
												$grandtotal_pnlcategory = $this->Smartreport_actual_model->get_grandtotal_pnlcategory($smartreport_pnlcategory->idpnlcategory, $user_ho, $monthact, $yearact);
												$grandtotal_pnlcategorybudget = $this->Smartreport_actual_model->get_grandtotal_pnlcategorybudget($smartreport_pnlcategory->idpnlcategory, $user_ho, $monthact, $yearact);
                                                if($monthact == '01'){ //jika difilter adalah bulan januari 2019 maka last monthnya adalah desember 2018 tahun sebelumnya
													$grandtotal_pnlcategorylastmtd = $this->Smartreport_actual_model->get_grandtotal_pnlcategory($smartreport_pnlcategory->idpnlcategory, $user_ho, $lastmonth, $lastyear); 
													$grandtotal_pnlcategorybudgetlastmtd = $this->Smartreport_actual_model->get_grandtotal_pnlcategorybudget($smartreport_pnlcategory->idpnlcategory, $user_ho, $lastmonth, $lastyear);                                                        
                                                }else{
													$grandtotal_pnlcategorylastmtd = $this->Smartreport_actual_model->get_grandtotal_pnlcategory($smartreport_pnlcategory->idpnlcategory, $user_ho, $lastmonth, $yearact);   
													$grandtotal_pnlcategorybudgetlastmtd = $this->Smartreport_actual_model->get_grandtotal_pnlcategorybudget($smartreport_pnlcategory->idpnlcategory, $user_ho, $lastmonth, $yearact);   
                                                } 
												$grandtotal_pnlcategoryytd = $this->Smartreport_actual_model->get_grandtotal_pnlcategoryytd($smartreport_pnlcategory->idpnlcategory, $user_ho,$startdate_ytd, $enddate_ytd);
												$grandtotal_pnlcategorybudgetytd = $this->Smartreport_actual_model->get_grandtotal_pnlcategorybudgetytd($smartreport_pnlcategory->idpnlcategory, $user_ho,$startdate_ytd, $enddate_ytd);?>
                                                
											<tr >
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> ><strong><?php echo $smartreport_pnlcategory->pnl_category;?></strong></td>	
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> colspan="15"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>    
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>	
                                                <td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td> 
                                                <td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>	
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>                                												
											</tr>		
												<?php foreach ($smartreport_pnllist_data as $smartreport_pnllist ){
														$total_actual_mtd = $this->Smartreport_actual_model->get_total_actual( $smartreport_pnllist->idpnl, $user_ho, $monthact, $yearact);
														$total_budget_mtd = $this->Smartreport_actual_model->get_total_budget( $smartreport_pnllist->idpnl, $user_ho, $monthact, $yearact);
                                                        if($monthact == '01'){ //jika difilter adalah bulan januari 2019 maka last monthnya adalah desember 2018 tahun sebelumnya
															$total_actual_lastmtd = $this->Smartreport_actual_model->get_total_actual( $smartreport_pnllist->idpnl, $user_ho, $lastmonth, $lastyear);
															$total_budget_lastmtd = $this->Smartreport_actual_model->get_total_budget( $smartreport_pnllist->idpnl, $user_ho, $lastmonth, $lastyear);                                                        
                                                        }else{
															$total_actual_lastmtd = $this->Smartreport_actual_model->get_total_actual( $smartreport_pnllist->idpnl, $user_ho, $lastmonth, $yearact);
															$total_budget_lastmtd = $this->Smartreport_actual_model->get_total_budget( $smartreport_pnllist->idpnl, $user_ho, $lastmonth, $yearact);   
                                                        }
														$total_actual_ytd = $this->Smartreport_actual_model->get_total_actualytd( $smartreport_pnllist->idpnl, $user_ho, $startdate_ytd, $enddate_ytd);
														$total_budget_ytd = $this->Smartreport_actual_model->get_total_budgetytd( $smartreport_pnllist->idpnl, $user_ho, $startdate_ytd, $enddate_ytd);?>
                                                      
                                                        <tr>															
                                                            <td>&emsp;&emsp;<?= $smartreport_pnllist->pnl_name;?></td>
                                                            <!--START MTD-->
															<td class="rata-kanan">
                                                                <?php if($smartreport_pnllist->idpnl == 1){ //idpnl 1 ada average room rate cara menghitungnya beda sendiri																			
																			if($trr_actual_mtd->TOTAL_ACTUAL!=0 && $rs_actual_mtd->TOTAL_ACTUAL !=0){
																			echo number_format($trr_actual_mtd->TOTAL_ACTUAL/$rs_actual_mtd->TOTAL_ACTUAL,0);
																			}
																		}else{																			 
																			echo number_format($total_actual_mtd->TOTAL_ACTUAL);
                                                                        }?>	
                                                            </td>
															<td class="rata-kanan">
                                                                <?php if($smartreport_pnllist->idpnlcategory != 1){
																	if($total_actual_mtd->TOTAL_ACTUAL !=0 && $grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY !=0 ){
																		echo number_format(($total_actual_mtd->TOTAL_ACTUAL/$grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY)*100,2).'%';
																	}
																}?>
                                                            </td>													
                                                            <td class="rata-kanan"><?php if($smartreport_pnllist->idpnl == 1){ //idpnl 1 ada average room rate cara menghitungnya beda sendiri																			
																			if($trr_budget_mtd->TOTAL_BUDGET!=0 && $rs_budget_mtd->TOTAL_BUDGET !=0){
																			echo number_format($trr_budget_mtd->TOTAL_BUDGET/$rs_budget_mtd->TOTAL_BUDGET,0);
																			}
																		}else{																			 
																			echo number_format($total_budget_mtd->TOTAL_BUDGET);
																		}?>
															</td> 
                                                            <td class="rata-kanan"><?php if($smartreport_pnllist->idpnlcategory != 1){
																	if($total_budget_mtd->TOTAL_BUDGET !=0 && $grandtotal_pnlcategorybudget->GRANDTOTAL_PNLCATEGORY !=0 ){
																		echo number_format(($total_budget_mtd->TOTAL_BUDGET/$grandtotal_pnlcategorybudget->GRANDTOTAL_PNLCATEGORY)*100,2).'%';
																	}
																}?></td> 
															<td class="rata-kanan">Variance</td>
															<!--END MTD-->
															
															<!--START LAST MONTH-->
                                                            <td class="rata-kanan">
                                                                <?php if($smartreport_pnllist->idpnl == 1){ //idpnl 1 ada average room rate cara menghitungnya beda sendiri																			
																			if($trr_actual_lastmtd->TOTAL_ACTUAL!=0 && $rs_actual_lastmtd->TOTAL_ACTUAL !=0){
																			echo number_format($trr_actual_lastmtd->TOTAL_ACTUAL/$rs_actual_lastmtd->TOTAL_ACTUAL,0);
																			}
																		}else{																			 
																			echo number_format($total_actual_lastmtd->TOTAL_ACTUAL);
                                                                        }?>	
                                                            </td>
															<td class="rata-kanan">
                                                                <?php if($smartreport_pnllist->idpnlcategory != 1){
																	if($total_actual_lastmtd->TOTAL_ACTUAL !=0 && $grandtotal_pnlcategorylastmtd->GRANDTOTAL_PNLCATEGORY !=0 ){
																		echo number_format(($total_actual_lastmtd->TOTAL_ACTUAL/$grandtotal_pnlcategorylastmtd->GRANDTOTAL_PNLCATEGORY)*100,2).'%';
																	}
																}?>
                                                            </td>													
                                                            <td class="rata-kanan"><?php if($smartreport_pnllist->idpnl == 1){ //idpnl 1 ada average room rate cara menghitungnya beda sendiri																			
																			if($trr_budget_lastmtd->TOTAL_BUDGET!=0 && $rs_budget_lastmtd->TOTAL_BUDGET !=0){
																			echo number_format($trr_budget_lastmtd->TOTAL_BUDGET/$rs_budget_lastmtd->TOTAL_BUDGET,0);
																			}
																		}else{																			 
																			echo number_format($total_budget_lastmtd->TOTAL_BUDGET);
                                                                        }?>	</td> 
                                                            <td class="rata-kanan"></td> 
															<td class="rata-kanan">Variance LAST MONTH</td>
															<!--END LAST MONTH-->

															<!--START YTD-->
                                                            <td class="rata-kanan">
                                                                        <?php if($smartreport_pnllist->idpnl == 1){ //idpnl 1 ada average room rate cara menghitungnya beda sendiri																			
																			if($trr_actual_ytd->TOTAL_ACTUAL!=0 && $rs_actual_ytd->TOTAL_ACTUAL !=0){
																			echo number_format($trr_actual_ytd->TOTAL_ACTUAL/$rs_actual_ytd->TOTAL_ACTUAL,0);
																			}
																		}else{																			 
																			echo number_format($total_actual_ytd->TOTAL_ACTUAL);
                                                                        }?>	
                                                            </td>
															<td class="rata-kanan">
                                                                <?php if($smartreport_pnllist->idpnlcategory != 1){
																	if($total_actual_ytd->TOTAL_ACTUAL !=0 && $grandtotal_pnlcategoryytd->GRANDTOTAL_PNLCATEGORY !=0 ){
																		echo number_format(($total_actual_ytd->TOTAL_ACTUAL/$grandtotal_pnlcategoryytd->GRANDTOTAL_PNLCATEGORY)*100,2).'%';
																	}
																}?>
                                                            </td>													
                                                            <td class="rata-kanan"><?php if($smartreport_pnllist->idpnl == 1){ //idpnl 1 ada average room rate cara menghitungnya beda sendiri																			
																			if($trr_budget_ytd->TOTAL_BUDGET!=0 && $rs_budget_ytd->TOTAL_BUDGET !=0){
																			echo number_format($trr_budget_ytd->TOTAL_BUDGET/$rs_budget_ytd->TOTAL_BUDGET,0);
																			}
																		}else{																			 
																			echo number_format($total_budget_ytd->TOTAL_BUDGET);
																		}?>	
															</td> 
                                                            <td class="rata-kanan">
																<?php if($smartreport_pnllist->idpnlcategory != 1){
																	if($total_budget_ytd->TOTAL_BUDGET !=0 && $grandtotal_pnlcategorybudgetytd->GRANDTOTAL_PNLCATEGORY !=0 ){
																		echo number_format(($total_budget_ytd->TOTAL_BUDGET/$grandtotal_pnlcategorybudgetytd->GRANDTOTAL_PNLCATEGORY)*100,2).'%';
																	}
																}?>
															</td> 
															<td class="rata-kanan">Variance</td>
															<!--END YTD-->
													  		                                        
                                                        </tr>
												<?php } ?>
												<tr>
													<td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?>"><strong><?php echo "TOTAL ".$smartreport_pnlcategory->pnl_category;?></strong></td>
                                                    
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php echo number_format($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY,0);?></td>
													<td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php if($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY != 0){echo number_format(($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY/$grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY)*100,2).'%';}?></td>	
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php echo number_format($grandtotal_pnlcategorybudget->GRANDTOTAL_PNLCATEGORY,0);?></td>
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php if($grandtotal_pnlcategorybudget->GRANDTOTAL_PNLCATEGORY != 0){echo number_format(($grandtotal_pnlcategorybudget->GRANDTOTAL_PNLCATEGORY/$grandtotal_pnlcategorybudget->GRANDTOTAL_PNLCATEGORY)*100,2).'%';}?></td>	
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>>Total Variance MTD</td>	

                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php echo number_format($grandtotal_pnlcategorylastmtd->GRANDTOTAL_PNLCATEGORY,0);?></td>
													<td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php if($grandtotal_pnlcategorylastmtd->GRANDTOTAL_PNLCATEGORY != 0){echo number_format(($grandtotal_pnlcategorylastmtd->GRANDTOTAL_PNLCATEGORY/$grandtotal_pnlcategorylastmtd->GRANDTOTAL_PNLCATEGORY)*100,2).'%';}?></td>	
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php echo number_format($grandtotal_pnlcategorybudgetlastmtd->GRANDTOTAL_PNLCATEGORY,0);?></td>
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php if($grandtotal_pnlcategorybudgetlastmtd->GRANDTOTAL_PNLCATEGORY != 0){echo number_format(($grandtotal_pnlcategorybudgetlastmtd->GRANDTOTAL_PNLCATEGORY/$grandtotal_pnlcategorybudgetlastmtd->GRANDTOTAL_PNLCATEGORY)*100,2).'%';}?></td>	
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>>Total Variance LAST MONTH</td>	

                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php echo number_format($grandtotal_pnlcategoryytd->GRANDTOTAL_PNLCATEGORY,0);?></td>
													<td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php if($grandtotal_pnlcategoryytd->GRANDTOTAL_PNLCATEGORY != 0){echo number_format(($grandtotal_pnlcategoryytd->GRANDTOTAL_PNLCATEGORY/$grandtotal_pnlcategoryytd->GRANDTOTAL_PNLCATEGORY)*100,2).'%';}?></td>	
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php echo number_format($grandtotal_pnlcategorybudgetytd->GRANDTOTAL_PNLCATEGORY,0);?></td>
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php if($grandtotal_pnlcategorybudgetytd->GRANDTOTAL_PNLCATEGORY != 0){echo number_format(($grandtotal_pnlcategorybudgetytd->GRANDTOTAL_PNLCATEGORY/$grandtotal_pnlcategorybudgetytd->GRANDTOTAL_PNLCATEGORY)*100,2).'%';}?></td>	
                                                    <td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>>Total Variance YTD</td>	
													
												</tr>	
											<?php } ?>			
									</tbody>
								</table>
							</div>
						</div>

						<div class="tab-pane fade" id="right-pnl2">							
							<form action="<?php echo base_url()?>smartreportpnl/insert_actual_pnl" method="post" accept-charset="utf-8" enctype="multipart/form-data">								
								<div class="col-md-5">	
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<label><?php echo $lang_month; ?></label>
													<select name="month_actual" class="form-control" required>
														<option value="01">January</option>
														<option value="02">February</option>
														<option value="03">March</option>
														<option value="04">April</option>
														<option value="05">May</option>
														<option value="06">June</option>
														<option value="07">July</option>
														<option value="08">August</option>
														<option value="09">September</option>
														<option value="10">October</option>
														<option value="11">November</option>
														<option value="12">December</option>
													</select>
											</div>
                                            <div class="col-sm-6">
												<label><?php echo $lang_year ?></label>
												<select name="year_actual" class="form-control" required>
													<?php
														for($i=date('Y'); $i>=2018; $i--) {
														$selected = '';
														if ($tahun == $i) $selected = ' selected="selected"';
														print('<option value="'.$i.'"'.$selected.'>'.$i.'</option>'."\n");
													}?>
												</select>  
											</div>
                                            
                                        </div>
                                    </div> 
								</div>	
								<div class="table-responsive">
									<table class="table text-nowrap table-hover">
										<thead>
											<tr>
												<th><?php echo $lang_description; ?></th>
												<th></th>												
											</tr>
										</thead>
										
										<tbody>										
										<?php
											foreach ($smartreport_pnlcategory_data as $smartreport_pnlcategory){?>
											<tr>
                                                <td><strong><?= $smartreport_pnlcategory->pnl_category;?></strong></td>	    
                                                <td>&nbsp;</td>                                            												
                                            </tr>
                                                <?php $smartreport_pnllist_data = $this->Smartreport_actual_model->select_pnllist_percategory($smartreport_pnlcategory->idpnlcategory);
                                                      foreach ($smartreport_pnllist_data as $smartreport_pnllist ){?>
                                                        <tr>
                                                            <td>&emsp;&emsp;<?= $smartreport_pnllist->pnl_name;?></td>
                                                            <td>
																<input type="hidden" name="idpnl[]" value="<?php echo $smartreport_pnllist->idpnl;?>">
																<input type="text" oninput="this.value = this.value.replace(/[^\d]/, '').replace(/(\..*)\./g, '$1');" name="actual_value[]" class="form-control" required>
															</td>                                             
                                                        </tr>
                                                <?php }?>
											<?php } ?>
										</tbody>
									</table>
									<div class="text-center">
										<button type="submit" class="btn bg-teal-400" ><?php echo $lang_submit;?></button>
									</div>
								
								</div>
							</form>
						</div>
						
					</div>
				</div>
			</div>
			<!-- /content area -->