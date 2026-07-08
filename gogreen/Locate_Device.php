<?php
	
	####################################
	#
	#	// For Text fields
	#	$Form_Fields[]= array($Element_Type,$Field_Type,$Field_Heading,$Field_Name,$Field_Value,$Manditary,$JValid,$Extra,$Class_Name,$Calender,$Editor,$Diff,$Diff_Column_Name);
	#	// Example Data
	#	$Form_Fields[]= array(1,1,Username,User_Name,'',*,E|N,'Txt_box','Style="color:red;"','Cal','E','F|T','Hid');
	#	  $Form_Fields[] = array('1','1','Filter','search','','','E|N|0','','Filter_box','','');
	#	$Element_Type 1 => 'text','password','radio',checkbox','submit','button','hidden','file','textarea'
	#	$Element_Type 2 => Dropdown - Select
	
	#	$Field_Type 1 => 'text',
	#	$Field_Type 2 => 'password',
	#	$Field_Type 3 => 'radio',
	#	$Field_Type 4 => 'checkbox',
	#	$Field_Type 5 => 'submit',
	#	$Field_Type 6 => 'button',
	#	$Field_Type 7 => 'hidden',
	#	$Field_Type 8 => 'file'
	
	#	$Manditary = '*' or ''
	#	$JValid = 'E|N' or 'E' or 'N'
	#	$Extra = anything you can add for that field ex : style='color:#33333' or onlick = jfunc()
	#	$Calender = 'Cal' or 'Cal_T' [Cal - Calender without time  ---  Cal_T - Calender with time ]
	#	$Editor = 'E' or '' [ Html text Editor  ]
	#	$Combo_Title = Dropdown Title
	#	$Match = which value we want to match and will selected too.
	#	$Diff = 'F' or 'T'  [F - From Date to search ] [T - To Date to search ]
	#	$Diff_Column_Name = which field should to search the date difference data
	#	
	#	// List fields
	#	$List_Fields[]= array($Field_Heading,$Field_Name,$Content_Limit,$Date_Format,$Array_Display);

	#   $Date_Format = 1 - 2010-01-01 10:10:10 (yyyy-mm-dd hh:ii:ss)
	#   $Date_Format = 2 - 01-01-2010 10:10:10 (yyyy-mm-dd hh:ii:ss)
	#   $Date_Format = 3 - 01-01-2010 (yyyy-mm-dd)
	#   $Date_Format = 4 - 10:10:10 (hh:ii:ss)
	#
	#	// For Select Event
	#	$Form_Fields[$No]= array($Element_Type,$Field_Type,$Field_Heading,$Field_Name,$Field_Value,$Manditary,$JValid,$Class_Name,$Extra,$Combo_Title,$Match);
	#	// For Select Example
	# 	$Form_Fields[] = array('1','3','Status','Sel',$Status_Array,'','1','','selectcls','onchange="Filter_Func(this.id,this.value,'.$Title_Head1.')"','-----Status-----','');
	#
	#
	################################################

		include("Header.php");
		$Title_Head = str_replace('.php','',basename($_SERVER['SCRIPT_NAME']));
		$Title_Head1 = "'$Title_Head'";
		$Submit_Txt = $Title_Head."_Submit";
		$List_Page = str_replace('Add','List',$Title_Head);
		$Edit_Page = str_replace('List','Edit',$Title_Head);
		$View_Page = str_replace('List','View',$Title_Head);
		if(empty($_COOKIE[$Cook_Name])){
			header("Location: index.php");
			exit;
		}
?>

<div id="admin_div">
		<div id="admin_left">
   		<p class="headings"><?=$Title_Head?></p>

			<center>
			<?
                //Getting Record_Index ID from URL
                if(isset($_REQUEST['IMEI'])){

                // Include Files
                include_once("./Lib/Includes.php");
                // Select query
                $Sel_Query = "SELECT * FROM DEVICE_DATA where IMEI = '".$_REQUEST['IMEI']."' order by Record_Index desc limit 1";
                $Sel_Result = mysql_query($Sel_Query);
                $Count_Lat = mysql_num_rows($Sel_Result);
                if($Count_Lat == 0){
                    $Err_Msg =  "There is no records corresponding date and time you given";
                }
                if($Count_Lat > 0){
                    $iconctr =0;
                    while ($row=mysql_fetch_array($Sel_Result)){
                        $IMEI = $row['IMEI'];
                        $Latitude = $row['Latitude'];
                        $Longitude = $row['Longitude'];
                        $Date_Stamp = $row['Date_Stamp'];
                        $Time_Stamp = $row['Time_Stamp'];
                        $Location_Summary = $row['Location_Summary'];
                    }	
                } 
            ?>
		<table border="1" cellpadding="5" cellspacing="3" width="90%" style="margin:10px;background-color:#E3E3E3; border-collapse:collapse; border-color:#CCCCCC">
        	<tr>	
            	<td><b>IMEI</b></td>
            	<td><?=$IMEI?></td>
            	<td><b>Date</b></td>
            	<td><?=date("d-M-Y H:i:s",strtotime($Date_Stamp))?></td>
            </tr>
        	<tr>	
            	<td><b>Location</b></td>
            	<td><?=$Location_Summary?></td>
            	<td></td>
            	<td></td>
            </tr>
        </table>
                
            <script type="text/javascript" src="./js/google-map.js"></script> 
            <script type="text/javascript"> 
              var infowindow;
            (function () {
             
              google.maps.Map.prototype.markers = new Array();
                
              google.maps.Map.prototype.addMarker = function(marker) {
                this.markers[this.markers.length] = marker;
              };
                
              google.maps.Map.prototype.getMarkers = function() {
                return this.markers
              };
                
              google.maps.Map.prototype.clearMarkers = function() {
                if(infowindow) {
                  infowindow.close();
                }
                
                for(var i=0; i<this.markers.length; i++){
                  this.markers[i].set_map(null);
                }
              };
            })();
              
            
              function initialize() {
                var latlng = new google.maps.LatLng(<?=$Latitude?>, <?=$Longitude?>);
                var myOptions = {
                  zoom: 12,
                  center: latlng,
                  mapTypeId: google.maps.MapTypeId.ROADMAP
                  //mapTypeId: google.maps.MapTypeId.SATELLITE
                };
                map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                var a = new Array();
            <?
                $Map_Name = "<div><table cellpadding='3' cellspacing='3' style='font-size:12px; width:250px;float:left;'><tr><td align='left' valign='top' width='50px' colspan='2' style='color:red;'><b>Current Location</b></td></tr><tr><td align='left' valign='top' width='50px'><b>Date</b> </td><td align='left' valign='top'>". date("d-M-Y H:i:s", strtotime($Date_Stamp))." </td></tr><tr><td align='left' valign='top'><b>Location</b></td><td align='left' valign='top'>".$Location_Summary."</td></tr></table></div>";	
            ?>
                var t =  new Object();
            
                t.name = "<?=$Map_Name?>" <?="\n"?>
                t.lat =  <?=$Latitude."\n"?>
                t.lng =  <?=$Longitude."\n"?>
                a[<?=$iconctr?>] = t;
            
                 for (var i = 0; i < a.length; i++) {
                    var latlng = new google.maps.LatLng(a[i].lat, a[i].lng);
                    map.addMarker(createMarker(a[i].name,latlng));
                 }
                console.log(map.getMarkers());    
                
              }
              
              function createMarker(name, latlng) {
                var marker = new google.maps.Marker({position: latlng, map: map});
                //google.maps.event.addListener(marker, "click", function() {
                  if (infowindow) infowindow.close();
                  infowindow = new google.maps.InfoWindow({content: name});
                  infowindow.open(map, marker);
                //});
                return marker;
              }
                   
            </script> 
            </head> 
            <body onLoad="initialize()"> 
            <center>
             <div id="map_canvas" style="width:90%; height:500px;"></div> 
             <div id="sense" style="float:left;width:160px;height:240px;padding-left:10px;"> 
            
             </div> 
             <br style="clear: both;"/> 
            </center>
            </body> 
            
            <?
            }
            ?>
			</center>

</div> 
		</div>		
				<div style="clear:both"></div>
		</div>

<?php include("Footer.php"); ?>	
