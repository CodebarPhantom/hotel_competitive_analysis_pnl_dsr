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
	if ($dateToView == NULL){
		$dateToView = date("Y");
	}
	$url_year = $dateToView;
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
$total_room_revenue = $this->Smartreport_actual_model->get_total_actual( "4", $user_ho, $dateToView); //4 adalah idpnl Room
$occupied_room = $this->Smartreport_actual_model->get_total_actual( "7", $user_ho, $dateToView); //7 adalah idpnl occupied room / room sold

function cal_days_in_year($dateToView){
	$days=0; 
	for($month=1;$month<=12;$month++){ 
			$days = $days + cal_days_in_month(CAL_GREGORIAN,$month,$dateToView);
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
                                            <div class="col-sm-5">
												<label><?php echo $lang_year ?></label>
												<select name="year_actual" class="form-control" required>
													<?php
														for($i=date('Y'); $i>=2018; $i--) {
														$selected = '';
														if ($dateToView == $i) $selected = ' selected="selected"';
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
									<thead>
										<tr style="vertical-align: middle; text-align: center">
											<th rowspan="2"><?php echo $lang_description; ?></th>											
											<th colspan="2">Summary</th>											
											<th rowspan="2">January</th>
											<th rowspan="2">February</th>
											<th rowspan="2">March</th>
											<th rowspan="2">April</th>
											<th rowspan="2">May</th>
											<th rowspan="2">June</th>
											<th rowspan="2">July</th>
											<th rowspan="2">August</th>
											<th rowspan="2">September</th>
											<th rowspan="2">October</th>
											<th rowspan="2">November</th>
											<th rowspan="2">December</th>												
										</tr>
										<tr>
											<td>Year To Date</td>
											<td>(%)</td>
										</tr>
									</thead>
									<tbody>
											<tr>
												<td colspan="3"><strong>STATISCTIC</strong></td>
												<td style="display: none;"></td>
												<td style="display: none;"></td>
												<td colspan="12"></td>
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

											<tr>
												<td>&emsp;&emsp;Number of Days</td>
												<td class="rata-kanan"><?php echo cal_days_in_year($dateToView); ?></td>
												<td></td>
												<?php for($month= 1; $month<=12; $month++ ){ ?>
													<?php $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$month, $dateToView);?>
													<td class="rata-kanan"><?php echo $dayInMonth; ?></td>
												<?php }  ?>												
											</tr>

											<tr>
												<td>&emsp;&emsp;Number of Rooms Available</td>
												<td class="rata-kanan"><?php echo number_format(cal_days_in_year($dateToView)* $total_rooms->total_rooms,0); ?></td>
												<td></td>
												<?php for($month= 1; $month<=12; $month++ ){
														   $dayInMonth = cal_days_in_month(CAL_GREGORIAN,$month, $dateToView);														   
														   $room_available = $dayInMonth * $total_rooms->total_rooms;
														   ?>
												<td class="rata-kanan">
													<?php if ($dayInMonth !=0 && $total_rooms->total_rooms !=0){
													echo number_format($room_available,0);} ?>
												</td>
												<?php }  ?>
											</tr>

											<tr>
												<td>&emsp;&emsp;% of Occupancy</td>
												<td class="rata-kanan"><?php echo number_format($occupied_room->TOTAL_ACTUAL/(cal_days_in_year($dateToView)* $total_rooms->total_rooms)*100,2).'%'; ?></td>
												<td></td>
												<?php for($month= 1; $month<=12; $month++ ){ ?>													
												<td class="rata-kanan"><?php 
																$actual_roomsold = $this->Smartreport_actual_model->get_data_actualroomsold($user_ho, $month, $dateToView);
																$dayInMonth = cal_days_in_month(CAL_GREGORIAN,$month, $dateToView);
																$occupancy= ($actual_roomsold->ACTUALROOMSOLD / ($dayInMonth * $total_rooms->total_rooms))*100;
																echo number_format($occupancy,2).'%';?>
													</td>  
												<?php } ?> 
											</tr>
										<?php foreach ($smartreport_pnlcategory_data as $smartreport_pnlcategory){
												/* Terlalu Dinamis parah, PNL Statistic sudah hilang karena sudah jadi header diatas IDPNLCATEGORY 1 itu adalah STATISTIC*/
												//$dateToView itu ada year
												$smartreport_pnllist_data = $this->Smartreport_actual_model->select_pnllist_percategory($smartreport_pnlcategory->idpnlcategory);
												$grandtotal_pnlcategory = $this->Smartreport_actual_model->get_grandtotal_pnlcategory($smartreport_pnlcategory->idpnlcategory, $user_ho, $dateToView); ?>
											<tr >
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> colspan="3"><strong><?php echo $smartreport_pnlcategory->pnl_category;?></strong></td>	
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> style="display: none;"></td>    
												<td <?php if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?> colspan="12"></td>
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
													  $total_actual = $this->Smartreport_actual_model->get_total_actual( $smartreport_pnllist->idpnl, $user_ho, $dateToView);?>
                                                        <tr>															
															<td>&emsp;&emsp;<?= $smartreport_pnllist->pnl_name;?></td>
															<td class="rata-kanan" ><?php if($smartreport_pnllist->idpnl == 1){ //idpnl 1 ada average room rate cara menghitungnya beda sendiri																			
																			if($total_room_revenue->TOTAL_ACTUAL!=0 && $occupied_room->TOTAL_ACTUAL !=0){
																			echo number_format($total_room_revenue->TOTAL_ACTUAL/$occupied_room->TOTAL_ACTUAL,0);
																			}
																		}else{																			 
																			echo number_format($total_actual->TOTAL_ACTUAL);
																		}?>																
															</td>
															<td class="rata-kanan"><?php if($smartreport_pnllist->idpnlcategory != 1){
																	if($total_actual->TOTAL_ACTUAL !=0 && $grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY !=0 ){
																		echo number_format(($total_actual->TOTAL_ACTUAL/$grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY)*100,2).'%';
																	}
																}?>
															</td>
															<?php for($month= 1; $month<=12; $month++ ){ ?>																
															<td class="rata-kanan">
																<?php $actual_data = $this->Smartreport_actual_model->get_data_actual( $smartreport_pnllist->idpnl, $user_ho, $month, $dateToView);
																echo number_format($actual_data->ACTUAL,0);?>
															</td>  
													  		<?php } ?>                                           
                                                        </tr>
												<?php } ?>
												<tr>
													<td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";} ?>"><strong><?php echo "TOTAL ".$smartreport_pnlcategory->pnl_category;?></strong></td>
													<td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>><?php echo number_format($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY,0);?></td>
													<td <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}?>><?php if($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY != 0){
																	echo number_format(($grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY/$grandtotal_pnlcategory->GRANDTOTAL_PNLCATEGORY)*100,2).'%';}?>
													</td>
													
													<?php for($month= 1; $month<=12; $month++ ){ ?>					
													<td  <?php  if ($smartreport_pnlcategory->idpnlcategory == 1) {echo "class='hidden'";}else{echo "class='rata-kanan'";}?>> 
														 <?php $total_pnlcategorybymonth = $this->Smartreport_actual_model->get_total_pnlcategorybymonth($smartreport_pnlcategory->idpnlcategory, $user_ho, $month, $dateToView); 
														 echo number_format($total_pnlcategorybymonth->TOTAL_PNLCATEGORYBYMONTH,0); ?>														
													</td>
													<?php } ?> 
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