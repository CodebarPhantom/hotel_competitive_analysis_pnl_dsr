<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/tables/datatables/datatables.min.js"></script> 
<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/tables/datatables/extensions/fixed_columns.min.js"></script>
<script src="<?php echo base_url();?>assets/backend/global_assets/js/demo_pages/datatables_extension_fixed_columns.js"></script>

<style>

.customEryan{
	font-size: 11px;
	width: 100%;  
}
</style>

<script type="text/javascript">
$(document).ready(function(){ 	
     $('.daterange-single').daterangepicker({ 
        singleDatePicker: true,
        locale: {
            format: 'DD-MM-YYYY'
        }
    });	
});
</script> 

<?php


							
   $date =  $dateToView;	
   $peryear = substr($dateToView,0,4);
   $permonth= substr($dateToView,5,2);
   $perdate = substr($dateToView,8,2);	

	$startdate_ytd = $peryear.'-01-'.'01';
	$enddate_ytd = $dateToView;
	$startdate_mtd = $peryear.'-'.$permonth.'-'.'01';
	$enddate_mtd = $dateToView;  

//$d=strtotime("-1 Day"); 
$graphYear = $peryear;
$graphMonth =  $permonth;
$graphDate =  $perdate;
//$graphAllDate = date('Y-m-d');
$lastmtd = $graphMonth - 1;

$dateDailyGraph = $peryear.'-'.$permonth.'-';
//$startdate_ytd = $graphYear.'-01-'.'01';
//$enddate_ytd =  date("Y-m-d", $d);

//$startdate_mtd = $graphYear.'-'.$graphMonth.'-'.'01';
//$enddate_mtd = date("Y-m-d"); 

$enddate_mtd = $dateToView;
$dashboardDate = $perdate;
$monthObj  = DateTime::createFromFormat('!m', $graphMonth); 

// Baris Occupancy
// room inventory
$ri_mtd = 0; $ri_ytd = 0; 
//room sold
$rs_today = 0; $rs_mtd = 0; $rs_ytd=0;
//occupancy 
$occ_ytd = 0; $occ_mtd =0;

//average room rate
$arr_mtd = 0; $arr_ytd=0; $trr_ytd= 0;
//fnb
$fnb_today = 0; $fnb_mtd = 0; $fnb_ytd = 0;
//other
$oth_today = 0; $oth_mtd = 0; $oth_ytd = 0;
//room out of order
$outoforder_today = 0; $outoforder_mtd = 0; $outoforder_ytd = 0;

//room out of order
$dt_outofordermtd = $this->Smartreport_dsr_model->select_outofordermtd_perhotel($startdate_mtd,$enddate_mtd,$idhotel_dashboard);
$outoforder_mtd = $dt_outofordermtd->OUTOFORDER_MTD;
$dt_outoforderytd = $this->Smartreport_dsr_model->select_outoforderytd_perhotel($startdate_ytd,$enddate_ytd,$idhotel_dashboard);                                  
$outoforder_ytd = $dt_outoforderytd->OUTOFORDER_YTD;


$dt_rsmtd = $this->Smartreport_hca_model->select_rsmtd_perhotel($startdate_mtd,$enddate_mtd,$idhotel_dashboard);
$rs_mtd = $dt_rsmtd->RS_MTD;
$dt_rsytd = $this->Smartreport_hca_model->select_rsytd_perhotel($startdate_ytd,$enddate_ytd,$idhotel_dashboard);
$rs_ytd += $dt_rsytd->RS_YTD;

$dt_trrmtd = $this->Smartreport_hca_model->select_trrmtd_perhotel($startdate_mtd,$enddate_mtd,$idhotel_dashboard);
$trr_mtd = $dt_trrmtd->TRR_MTD;
$dt_trrytd = $this->Smartreport_hca_model->select_trrytd_perhotel($startdate_ytd,$enddate_ytd,$idhotel_dashboard);
$trr_ytd = $dt_trrytd->TRR_YTD;

$dt_analystoday = $this->Smartreport_hca_model->select_competitoranalysisondate_perhotel($idhotel_dashboard,$enddate_mtd);
    if($dt_analystoday != NULL){
        $rs_today = $dt_analystoday->room_sold;
        //$arr_today = $dt_analystoday->avg_roomrate;
    }

//echo $rs_today;

/*$ri_ytd  = $this->Smartreport_hca_model->select_RIYTD_perhotel($startdate_ytd,$enddate_ytd,$idhotel_dashboard);
if($rs_ytd != 0 && $ri_ytd->RI_YTD != 0){
		$occ_ytd = ($rs_ytd / $ri_ytd->RI_YTD) * 100;
	}*/

//Baris Average Room Rate
if($rs_mtd != 0 && $rs_ytd != 0){
	$arr_mtd = $trr_mtd / $rs_mtd;
	
}
if($trr_ytd != 0 && $trr_ytd != 0){
$arr_ytd = $trr_ytd /$rs_ytd;
}

//untuk yang bukan administrator
if($idhotel_dashboard == NULL){
	$idhotel_dashboard = $user_ho;
}
//echo $idhotel_dashboard;

//data dari table budget
$days_this_month = cal_days_in_month(CAL_GREGORIAN,$graphMonth,$graphYear);
$budget_arr =  $this->Smartreport_pnl_model->get_arr_budget($idhotel_dashboard, $graphMonth,$graphYear);

$budget_rooms =  $this->Smartreport_pnl_model->get_rooms_budget($idhotel_dashboard, $graphMonth,$graphYear);
$budget_roomsytd = $this->Smartreport_pnl_model->get_rooms_budgetytd($idhotel_dashboard, $lastmtd, $graphYear);
$getbudget_roomsnow = $budget_rooms->BUDGET_ROOMS/$days_this_month;
$getbudget_roomsmtd = ($budget_rooms->BUDGET_ROOMS/$days_this_month)*$dashboardDate;
$getbudget_roomsytd = $budget_roomsytd->BUDGET_ROOMSYTD+($budget_rooms->BUDGET_ROOMS/$days_this_month)*$dashboardDate;

$budget_fnb =  $this->Smartreport_pnl_model->get_fnb_budget($idhotel_dashboard, $graphMonth,$graphYear);
$budget_fnbytd = $this->Smartreport_pnl_model->get_fnb_budgetytd($idhotel_dashboard, $lastmtd, $graphYear);
$getbudget_fnbnow = $budget_fnb->BUDGET_FNB/$days_this_month;
$getbudget_fnbmtd = ($budget_fnb->BUDGET_FNB/$days_this_month)*$dashboardDate;
$getbudget_fnbytd = $budget_fnbytd->BUDGET_FNBYTD+($budget_fnb->BUDGET_FNB/$days_this_month)*$dashboardDate;

$budget_other =  $this->Smartreport_pnl_model->get_other_budget($idhotel_dashboard, $graphMonth,$graphYear);
$budget_otherytd = $this->Smartreport_pnl_model->get_other_budgetytd($idhotel_dashboard, $lastmtd, $graphYear);
$getbudget_othernow = $budget_other->BUDGET_OTHER/$days_this_month; 
$getbudget_othermtd = ($budget_other->BUDGET_OTHER/$days_this_month)*$dashboardDate;
$getbudget_otherytd = $budget_otherytd->BUDGET_OTHERYTD+($budget_other->BUDGET_OTHER/$days_this_month)*$dashboardDate;

$budget_laundry =  $this->Smartreport_pnl_model->get_laundry_budget($idhotel_dashboard, $graphMonth,$graphYear);
$budget_laundryytd = $this->Smartreport_pnl_model->get_laundry_budgetytd($idhotel_dashboard, $lastmtd, $graphYear);
$getbudget_laundrynow = $budget_laundry->BUDGET_LAUNDRY/$days_this_month; 
$getbudget_laundrymtd = ($budget_laundry->BUDGET_LAUNDRY/$days_this_month)*$dashboardDate;
$getbudget_laundryytd = $budget_laundryytd->BUDGET_LAUNDRYYTD+($budget_laundry->BUDGET_LAUNDRY/$days_this_month)*$dashboardDate; 

$budget_sport = $this->Smartreport_pnl_model->get_sport_budget($idhotel_dashboard, $graphMonth,$graphYear);
$budget_sportytd = $this->Smartreport_pnl_model->get_sport_budgetytd($idhotel_dashboard, $lastmtd, $graphYear);
$getbudget_sportnow = $budget_sport->BUDGET_SPORT/$days_this_month; 
$getbudget_sportmtd = ($budget_sport->BUDGET_SPORT/$days_this_month)*$dashboardDate;
$getbudget_sportytd = $budget_sportytd->BUDGET_SPORTYTD+($budget_sport->BUDGET_SPORT/$days_this_month)*$dashboardDate; 

$budget_spa = $this->Smartreport_pnl_model->get_spa_budget($idhotel_dashboard, $graphMonth,$graphYear);
$budget_spaytd = $this->Smartreport_pnl_model->get_spa_budgetytd($idhotel_dashboard, $lastmtd, $graphYear);
$getbudget_spanow = $budget_spa->BUDGET_SPA/$days_this_month; 
$getbudget_spamtd = ($budget_spa->BUDGET_SPA/$days_this_month)*$dashboardDate;
$getbudget_spaytd = $budget_spaytd->BUDGET_SPAYTD+($budget_spa->BUDGET_SPA/$days_this_month)*$dashboardDate; 

$budget_roomsold = $this->Smartreport_pnl_model->get_roomsold_budget($idhotel_dashboard, $graphMonth,$graphYear);
$budget_roomsoldytd = $this->Smartreport_pnl_model->get_roomsold_budgetytd($idhotel_dashboard, $lastmtd, $graphYear);
$getbudget_roomsoldytd = $budget_roomsoldytd->BUDGET_ROOMSOLDYTD+($budget_roomsold->BUDGET_ROOMSOLD/$days_this_month)*$dashboardDate;

$dt_fnbmtd = $this->Smartreport_dsr_model->select_fnbmtd_perhotel($startdate_mtd,$enddate_mtd,$idhotel_dashboard);
$fnb_mtd = $dt_fnbmtd->FNB_MTD; 
$dt_othmtd = $this->Smartreport_dsr_model->select_othmtd_perhotel($startdate_mtd,$enddate_mtd,$idhotel_dashboard);
$oth_mtd = $dt_othmtd->OTH_MTD;

?>


<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/notifications/pnotify.min.js"></script>
<script src="<?php echo base_url();?>assets/backend/global_assets/js/plugins/visualization/echarts/echarts.min.js"></script>




<!-- Page header -->
        <div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><i class="icon-arrow-left52 mr-2"></i> <span class="font-weight-semibold">Home</span> - Dashboard</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<div class="header-elements d-none">
						<form action="<?php echo base_url()?>smartreport/dashboard" method="post" accept-charset="utf-8">
						<div class="d-flex justify-content-center">						
							<div class="form-group">
								<div class="input-group">
									<span class="input-group-prepend">
										<span class="input-group-text"><i class="icon-calendar22"></i></span>
									</span>
									<input type="text" data-mask="99-99-9999" name="date_dashboard" class="form-control daterange-single" value="<?php echo $date_dashboard; ?>" required  />
									<?php if($user_le === '1' ){ ?>
									<select name="idhoteldashboard" class="form-control" required autocomplete="off">
										<option value=""><?php echo $lang_choose_hotels; ?></option>
												<?php
													$hotel = $idhotel_dashboard;
													$hotelData = $this->Smartreport_hotels_model->getDataParent('smartreport_hotels', 'idhotels','PARENT', 'ASC');
													for ($p = 0; $p < count($hotelData); ++$p) {
														$idhotel = $hotelData[$p]->idhotels;
														$hotelname = $hotelData[$p]->hotels_name;?>
														<option  value="<?php echo $idhotel; ?>"  <?php if ($hotel == $idhotel) {
																echo 'selected="selected"';
															} ?> >
															<?php echo $hotelname; ?>
														</option>
												<?php
														unset($idhotel);
														unset($hotelname);
													}
												?>
									</select>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">											
								<button type="submit" class="btn bg-teal-400 "><?php echo $lang_search; ?></button>
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="">
				<!-- Nightingale roses -->
				<div class="row">
					<div class="col-md-6">
						<!-- Nightingale roses (hidden labels) -->
						<div class="card">
								<div class="card-header header-elements-sm-inline">
									<h6 class="card-title"><strong>Summary</strong></h6>								
								</div>

								<div class="card-body d-md-flex align-items-md-center justify-content-md-between flex-md-wrap">
									<div class="d-flex align-items-center mb-3 mb-md-0">
										<!--<div id="tickets-status"></div>-->
										<div class="ml-3">
											<span class="badge badge-mark border-success mr-1"></span> <span class="text-muted">
												<?php echo date($dashboardDate).' '. $monthObj->format('F').' '. $graphYear;?></span>
										</div>
									</div>

									<div class="d-flex align-items-center mb-3 mb-md-0">
										<a href="#" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon">
											<i class="icon-store2"></i>
										</a>
										<div class="ml-3">
											<h5 class="font-weight-semibold mb-0"><?php echo $rs_mtd;?></h5>
											<span class="text-muted">Room Sold</span>
										</div>
									</div>

									<div class="d-flex align-items-center mb-3 mb-md-0">
										<a href="#" class="btn bg-transparent border-indigo-400 text-indigo-400 rounded-round border-2 btn-icon">
											<i class="icon-cash4"></i>
										</a>
										<div class="ml-3">
											<h5 class="font-weight-semibold mb-0">Rp.<?php echo number_format($trr_mtd+$fnb_mtd+$oth_mtd,0);?></h5>
											<span class="text-muted">Revenue This Month</span>
										</div>
									</div>
								</div>

								<div class="table-responsive"  style=" min-height: 305px;">
									<table class="table table-bordered table-togglable table-hover table-sm customEryan datatable-nobutton-1column text-nowrap">
										<thead style="vertical-align: middle; text-align: center">
											<tr>
												<th rowspan="2">Report</th>
												<th colspan="2">Today</th>
												<th colspan="2">MTD</th>
												<th colspan="2">YTD</th>
											</tr>
											<tr>
												<th>Actual</th>
												<th>Budget</th>												
												<th>Actual</th>
												<th>Budget</th>												
												<th>Actual</th>	
												<th>Budget</th>												
											</tr>
										</thead>
										
										<tbody>
											
											<?php 
											
											foreach ($getHotelByUser_data->result() as $getHotelByUser){ 
												$dt_dsrtoday = $this->Smartreport_dsr_model->select_dsrondate_perhotel($getHotelByUser->idhotels,$dateToView);
												if($dt_dsrtoday != NULL){
													$fnb_today = $dt_dsrtoday->sales_fnb;
													$guest_today = $dt_dsrtoday->numberofguest;
													$oth_today = $dt_dsrtoday->sales_other;
													$outoforder_today = $dt_dsrtoday->sales_outoforder;
												}?>
											<tr>										
												<td>
													<a href="#" class="text-default table-border-double">
														<div class="font-weight-300">Occupancy</div>														
													</a>
												</td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php 	$ri_today =  $getHotelByUser->total_rooms - $outoforder_today;
																	if($rs_today != 0 && $ri_today != 0){
																		$occ_today = ($rs_today / $ri_today) * 100;
																	}else{
																		$occ_today = 0;
																	}
																	echo number_format($occ_today,2).'%';?>
														</div>														
													</a>
												</td>
												<td><?php  if($getHotelByUser->total_rooms != 0){echo number_format((($budget_roomsold->BUDGET_ROOMSOLD/$days_this_month)/($getHotelByUser->total_rooms))*100,2).'%';}    ?></td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php $ri_mtd = $getHotelByUser->total_rooms * $dashboardDate;
																	if($rs_mtd != 0 && $ri_mtd != 0){
																		$occ_mtd = ($rs_mtd / ($ri_mtd - $outoforder_mtd)) * 100;
																	}
															echo number_format($occ_mtd,2).'%';?>
														</div>														
													</a>
												</td>
												<td><?php if($getHotelByUser->total_rooms != 0){ echo number_format(((($budget_roomsold->BUDGET_ROOMSOLD/$days_this_month)*$dashboardDate)/($getHotelByUser->total_rooms * $dashboardDate))*100,2).'%';} ?></td>

												<td>
													<a href="#" class="text-default">
													<?php
														$diffdateytd= date_diff(new DateTime($startdate_ytd), new DateTime($enddate_ytd)); 
														$ri_ytd = $getHotelByUser->total_rooms * ($diffdateytd->days + 1);

														if($rs_ytd != 0 && $ri_ytd != 0){
															$occ_ytd = ($rs_ytd / ($ri_ytd - $outoforder_ytd)) * 100;
														}
														?>
														<div class="font-weight-300"><?php echo number_format($occ_ytd,2).'%'; ?></div>														
													</a>
												</td>
												<td><?php if($getHotelByUser->total_rooms != 0){echo number_format((($budget_roomsoldytd->BUDGET_ROOMSOLDYTD+($budget_roomsold->BUDGET_ROOMSOLD/$days_this_month)*$dashboardDate)/$ri_ytd)*100,2).'%';} ?></td>
											</tr>

											<tr>										
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">Average Room Rate</div>														
													</a>
												</td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php $arrDailyData = $this->Smartreport_hca_model->getDailyArrForGraphById($getHotelByUser->idhotels,$enddate_mtd);
															echo  number_format($arrDailyData->graph_ArrDaily);?>
														</div>														
													</a>
												</td>
												<td><?php echo number_format($budget_arr->BUDGET_ARR,0); ?></td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php echo  number_format($arr_mtd);?>
														</div>														
													</a>
												</td>
												<td><?php echo number_format($budget_arr->BUDGET_ARR,0); ?></td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300"><?php echo number_format($arr_ytd); ?></div>														
													</a>
												</td>
												<td><?php if($getHotelByUser->total_rooms != 0 && $getbudget_roomsytd != 0 && $getbudget_roomsoldytd !=0 ){ echo number_format($getbudget_roomsytd/$getbudget_roomsoldytd);}else{echo 0;} ?></td>
											</tr>

											<tr>										
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">Room Revenue</div>														
													</a>
												</td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php $trrDailyData = $this->Smartreport_hca_model->getDailyTrrForGraphById($getHotelByUser->idhotels,$enddate_mtd);
															echo  number_format($trrDailyData->graph_TrrDaily);?>
														</div>														
													</a>
												</td>
												<td><?php echo number_format($getbudget_roomsnow,0); ?></td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php echo  number_format($trr_mtd);?>
														</div>														
													</a>
												</td>
												<td><?php echo number_format($getbudget_roomsmtd,0);?></td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300"><?php echo number_format($trr_ytd); ?></div>														
													</a>
												</td>
												<td><?php echo number_format($getbudget_roomsytd);  ?></td>
											</tr>

											<tr>										
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">Food & Beverage</div>														
													</a>
												</td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php echo  number_format($fnb_today);?>
														</div>														
													</a>
												</td>
												<td><?php echo number_format($getbudget_fnbnow ,0); ?></td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php echo number_format($fnb_mtd,0); ?>
														</div>														
													</a>
												</td>
												<td><?php echo number_format($getbudget_fnbmtd ,0); ?></td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php $dt_fnbytd = $this->Smartreport_dsr_model->select_fnbytd_perhotel($startdate_ytd,$enddate_ytd,$idhotel_dashboard);                                  
                                   								   echo $fnb_ytd = number_format($dt_fnbytd->FNB_YTD); ?></div>														
													</a>
												</td>
												<td><?php echo  number_format($getbudget_fnbytd); ?></td>
											</tr>

											<tr>										
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">Others</div>														
													</a>
												</td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php echo number_format($oth_today);;?>
														</div>														
													</a>
												</td>
												<td><?php echo number_format($getbudget_laundrynow+$getbudget_othernow+$getbudget_sportnow+$getbudget_spanow);?></td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php echo number_format($oth_mtd);?>
														</div>														
													</a>
												</td>
												<td><?php echo number_format($getbudget_laundrymtd+$getbudget_othermtd+$getbudget_sportmtd+$getbudget_spamtd);?></td>
												<td>
													<a href="#" class="text-default">
														<div class="font-weight-300">
															<?php $dt_othytd = $this->Smartreport_dsr_model->select_othytd_perhotel($startdate_ytd,$enddate_ytd,$idhotel_dashboard);                                  
                                   								echo $oth_ytd = number_format($dt_othytd->OTH_YTD); ?></div>														
													</a>
												</td>
												<td><?php echo number_format($getbudget_laundryytd+$getbudget_otherytd+$getbudget_sportytd+$getbudget_spaytd);?></td>
											</tr>
											<?php } ?>
										</tbody>
										
									</table>
								</div>
							</div>
						<!-- /nightingale roses (hidden labels) -->

					</div>

					<div class="col-md-6">

						<!-- Nightingale roses (hidden labels) -->
						<div class="card">
							

							<div class="card-body">
								<div class="chart-container">
									<div class="chart" style="min-width: 250px; height: 410px;" id="mpi_MTD"></div>
								</div>
							</div>
						</div>
						<!-- /nightingale roses (hidden labels) -->

					</div>
				</div>
				<!-- /nightingale roses -->
				<!-- Basic columns -->
				<div class="card">
					

					<div class="card-body">
						<div class="chart-container">
							<div class="chart" style="min-width: 350px; height: 400px;" id="occDaily"></div>
						</div>
					</div>
				</div>


				<div class="card">
					

					<div class="card-body">
						<div class="chart-container">
							<div class="chart" style="min-width: 350px; height: 400px;" id="arrDaily"></div>
						</div>
					</div>
				</div>

				<div class="card">
					

					<div class="card-body">
						<div class="chart-container">
							<div class="chart" style="min-width: 350px; height: 400px;" id="revparDaily"></div>
						</div>
					</div>
				</div>
				<!-- /basic columns -->



				<!-- Dashboard content -->
				
				<!-- /dashboard content -->

			</div>
			<!-- /content area -->
			<?php include 'dashboard_graph.php';?>