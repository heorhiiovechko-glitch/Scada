/* #########

	Form Validation

#############
*/
function FormValid(Valid_Arr,Form1){
	var Split_Arr = Valid_Arr.split(",");

	for(i = 0;i < Split_Arr.length; i++){
		var Field_Val = $("#"+Split_Arr[i]).val();
		var Field_Type = $("#"+Split_Arr[i]).get(0).type;
		if(Field_Type == 'text' || Field_Type == 'password' || Field_Type == 'textarea' ){
				if(Field_Val == ''){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Please enter a value.');
					$("#Main_Error_Div").show('slow');
					var E = 1;
				}
		}
		
		if(Field_Type == 'select-one'){
				if(Field_Val == '0'){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Select the Values');
					var E = 1;
				}
		}

	}

	if(E == 1){
			return false;
	}
}



/* #########

	Ajax Function For Login

#############
*/


function Ajax_Func_Login(Valid_Arr,Url,Img_Div,Output_Div){

	var Split_Arr = Valid_Arr.split(",");

	for(i = 0;i < Split_Arr.length; i++){
		var Field_Val = $("#"+Split_Arr[i]).val();
		var Field_Type = $("#"+Split_Arr[i]).get(0).type;
		if(Field_Type == 'text' || Field_Type == 'password' || Field_Type == 'textarea' ){
				if(Field_Val == ''){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Please enter a value.');
					$("#Main_Error_Div").show('slow');
					var E = 1;
				}
		}
		if(Field_Type == 'select-one'){
				if(Field_Val == '0'){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Select the Values');
					var E = 1;
				}
		}

	}

	if(E == 1){
			return false;
	}
	else{/*
		if(Url != ''){
			document.getElementById(Img_Div).style.display= 'block';
	
			var xmlhttp;
			if (window.XMLHttpRequest){
			  xmlhttp=new XMLHttpRequest();
			}
			else if (window.ActiveXObject){
			  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			else{
			  alert("Your browser does not support XMLHTTP!");
			}
			xmlhttp.onreadystatechange=function(){
				if(xmlhttp.readyState==4){
					if(xmlhttp.responseText.length == '13'){
						window.location = 'http://localhost/P/home.php';
					}
					else{
						document.getElementById(Img_Div).style.display= 'none';
						document.getElementById(Output_Div).style.display= 'block';
						document.getElementById(Output_Div).innerHTML=xmlhttp.responseText;
					}
				}
			}
			var Old_Email = $("#Old_Email").val();
			var Email = $("#Email").val();
			Url1 = Url+"?Old_Email="+Old_Email+"&Email="+Email;alert(Url1);
			xmlhttp.open("GET",Url1,true);
			xmlhttp.send(null);
		}*/
	}
		
}


/* #########

	Ajax Function For Forget Password

#############
*/


function Ajax_Func_Forget_Password(Valid_Arr,Url,Img_Div,Output_Div){

	var Split_Arr = Valid_Arr.split(",");

	for(i = 0;i < Split_Arr.length; i++){
		var Field_Val = $("#"+Split_Arr[i]).val();
		var Field_Type = $("#"+Split_Arr[i]).get(0).type;
		if(Field_Type == 'text' || Field_Type == 'password' || Field_Type == 'textarea' ){
				if(Field_Val == ''){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Please enter a value.');
					$("#Main_Error_Div").show('slow');
					var E = 1;
				}
		}
		if(Field_Type == 'select-one'){
				if(Field_Val == '0'){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Select the Values');
					var E = 1;
				}
		}

	}

	if(E == 1){
			return false;
	}
	else{
		document.getElementById(Img_Div).style.display= 'block';

		var xmlhttp;
		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject){
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else{
		  alert("Your browser does not support XMLHTTP!");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4){
				document.getElementById(Img_Div).style.display= 'none';
				document.getElementById(Output_Div).style.display= 'block';
				document.getElementById(Output_Div).innerHTML=xmlhttp.responseText;
			}
		}
		var Email = $("#Email").val();
		Url1 = Url+"?Email="+Email;
		xmlhttp.open("GET",Url1,true);
		xmlhttp.send(null);
	}
		
}


/* #########

	Ajax Function

#############
*/


function Ajax_Func_Current_Location(Url,Img_Div,Output_Div){
	
	var xmlhttp;
	if (window.XMLHttpRequest){
	  xmlhttp=new XMLHttpRequest();
	}
	else if (window.ActiveXObject){
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	else{
	  alert("Your browser does not support XMLHTTP!");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4){//alert(xmlhttp.responseText);
			//document.getElementById(Img_Div).style.display= 'none';
			document.getElementById('Current_Location').style.display= 'block';
			
			//document.getElementById(Output_Div).innerHTML=xmlhttp.responseText;
		}
	}
	
	xmlhttp.open("GET",Url,true);
	xmlhttp.send(null);
}


/* #########

	Tooltip Function

#############
*/

function logged_box_func_show(){
	$("#shareit-box").show('slow');
}

function logged_box_func_hide(){
	$("#shareit-box").hide('slow');
}



/* #########

	History Location Ajax

#############
*/

function History_Location_ajax(IMEI,Start_Datetime,End_Datetime,Url){

	//document.getElementById('History_Location_Summary_Ajax').style.display = 'block';
	var xmlhttp;
	if (window.XMLHttpRequest){
	  xmlhttp=new XMLHttpRequest();
	}
	else if (window.ActiveXObject){
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	else{
	  alert("Your browser does not support XMLHTTP!");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4){
			//document.getElementById(Img_Div).style.display= 'none';
			//document.getElementById('History_Location_Summary_Ajax').style.display= 'none';
			document.getElementById('History_Location_Info').innerHTML= xmlhttp.responseText;
			document.getElementById('History_Location_Info').style.display= 'block';
			
		}
	}
	Url1 = Url+"?IMEI="+IMEI+"&Start_Date="+Start_Datetime+"&End_Date="+End_Datetime;
	xmlhttp.open("GET",Url1,true);
	xmlhttp.send(null);
}


/* #########

	Check Dates

#############
*/

function check_dates(days){

	t1= $("#inputDate").val();
	t2=$("#inputDate1").val();
	
   //Total time for one day
	var one_day=1000*60*60*24; 
	
	var x=t1.split("-");     
	var y=t2.split("-");
  //date format(Fullyear,month,date) 

	var date1=new Date(x[2],(x[1]-1),x[0]);
	var date2=new Date(y[2],(y[1]-1),y[0])
	var month1=x[1]-1;
	var month2=y[1]-1;
    //Calculate difference between the two dates, and convert to days
    _Diff=Math.ceil((date2.getTime()-date1.getTime())/(one_day)); 
	if(_Diff >= days){
		document.getElementById('Report_Sel_Info').innerHTML= 'Selection range cannot be process for more than '+days+' days.Kindly check again.';
		document.getElementById('Report_Sel_Info').style.display= 'block';
		return  false;
	}
	return true;
}



/* #########

	Hide all Vehicle div

#############
*/
function hide_allvehiclediv(get_val){
	$("#"+get_val).hide();
}
function show_allvehiclediv(get_val){
	$("#"+get_val).show();
}


/* #########

	Ajax Function For Change Email Address

#############
*/


function Ajax_Func_Change_Email(Valid_Arr,Url,Img_Div,Output_Div){

	var Split_Arr = Valid_Arr.split(",");

	for(i = 0;i < Split_Arr.length; i++){
		var Field_Val = $("#"+Split_Arr[i]).val();
		var Field_Type = $("#"+Split_Arr[i]).get(0).type;
		if(Field_Type == 'text' || Field_Type == 'password' || Field_Type == 'textarea' ){
			if(Field_Val == ''){
				$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
				$("#"+Split_Arr[i]+"v").show('animate');
				$("#"+Split_Arr[i]+"v").html('Please enter a value.');
				$("#Main_Error_Div").show('slow');
				var E = 1;
			}
			
			if(Field_Val != ''){
				var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
				if(!emailReg.test(Field_Val)) {
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Enter the Correct Email Address');
					var E = 1;
				}				
			}
		}
		
		if(Field_Type == 'select-one'){
				if(Field_Val == '0'){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Select the Values');
					var E = 1;
				}
		}

	}

	if(E == 1){
			return false;
	}
	else{
		document.getElementById(Img_Div).style.display= 'block';

		var xmlhttp;
		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject){
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else{
		  alert("Your browser does not support XMLHTTP!");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4){
				document.getElementById(Img_Div).style.display= 'none';
				document.getElementById('Change_Email_Div').style.display= 'none';
				document.getElementById(Output_Div).style.display= 'block';
				document.getElementById(Output_Div).innerHTML=xmlhttp.responseText;
			}
		}
		var Old_Email = $("#Old_Email").val();
		var Email = $("#Email").val();
		Url1 = Url+"?Old_Email="+Old_Email+"&Email="+Email;
		xmlhttp.open("GET",Url1,true);
		xmlhttp.send(null);
	}
		
}



/* #########

	Ajax Function For Change Email Address

#############
*/


function Ajax_Func_Change_Pass(Valid_Arr,Url,Img_Div,Output_Div){

	var Split_Arr = Valid_Arr.split(",");

	for(i = 0;i < Split_Arr.length; i++){
		var Field_Val = $("#"+Split_Arr[i]).val();
		var Field_Type = $("#"+Split_Arr[i]).get(0).type;
		if(Field_Type == 'text' || Field_Type == 'password' || Field_Type == 'textarea' ){
			if(Field_Val == ''){
				$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
				$("#"+Split_Arr[i]+"v").show('animate');
				$("#"+Split_Arr[i]+"v").html('Please enter a value.');
				$("#Main_Error_Div").show('slow');
				var E = 1;
			}
			
		}
		
		if(Field_Type == 'select-one'){
				if(Field_Val == '0'){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					$("#"+Split_Arr[i]+"v").show('animate');
					$("#"+Split_Arr[i]+"v").html('Select the Values');
					var E = 1;
				}
		}

	}

	if(E == 1){
			return false;
	}
	else{
		document.getElementById(Img_Div).style.display= 'block';

		var xmlhttp;
		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject){
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else{
		  alert("Your browser does not support XMLHTTP!");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4){
				$("#"+Img_Div).hide();
				$("#Change_Pass_Div").hide();
				$("#"+Output_Div).show();
				document.getElementById(Output_Div).innerHTML=xmlhttp.responseText;
			}
		}
		var Old_Pass = $("#Old_Pass").val();
		var Pass = $("#Pass").val();
		Url1 = Url+"?Old_Pass="+Old_Pass+"&Pass="+Pass;
		xmlhttp.open("GET",Url1,true);
		xmlhttp.send(null);
	}
		
}


/* #########

	Ajax Function For Vehicle List

#############
*/

function Show_Vehicle_List(Url,Img_Div,Output_Div){

	$("#vehicle_ajax_img1").show();
	var xmlhttp;
	if (window.XMLHttpRequest){
	  xmlhttp=new XMLHttpRequest();
	}
	else if (window.ActiveXObject){
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	else{
	  alert("Your browser does not support XMLHTTP!");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4){
			$("#vehicle_ajax_img1").hide();
			$("#"+Output_Div).show();
			document.getElementById(Output_Div).innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET",Url,true);
	xmlhttp.send(null);
}

/* #########

	Ajax Function For Vehicle List

#############
*/

function Poi_Creation(){
	
	var POI_Name_Geo = $("#POI_Name_Geo").val();
	if(POI_Name_Geo == ''){
		$("#POI_Name_Geo").css('background-color', '#F6DDD8');
		//$("#"+Split_Arr[i]+"v").show('animate');
		//$("#"+Split_Arr[i]+"v").html('Please enter a value.');
		//$("#Main_Error_Div").show('slow');
		var E = 1;
	}

	if(E == 1){
			return false;
	}
	else{
		$("#poi_creation").hide();
		$("#poi-ajax").show();
		var xmlhttp;
		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject){
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else{
		  alert("Your browser does not support XMLHTTP!");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4){
				$("#poi-ajax").hide();
				$("#poi_thankyou").show();
				document.getElementById('Output_txt').innerHTML=xmlhttp.responseText;
			}
		}
		var Point = $("#Point").val();
		var POI_Name = $("#POI_Name_Geo").val();
		Url = 'poi-creation-ajax.php?Point='+Point+'&POI_Name='+POI_Name;
		xmlhttp.open("GET",Url,true);
		xmlhttp.send(null);
	}
}

/* #########

	Create New POI 

#############
*/

function Create_New_POI(){
	
	$("#Creat_POI_Window").toggle('slow');
}

function New_POI_Creation(Valid_Arr){
	
	var Split_Arr = Valid_Arr.split(",");

	for(i = 0;i < Split_Arr.length; i++){
		var Field_Val = $("#"+Split_Arr[i]).val();
		var Field_Type = $("#"+Split_Arr[i]).get(0).type;
		if(Field_Type == 'text' || Field_Type == 'password' || Field_Type == 'textarea' ){
			if(Field_Val == ''){
				$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
				$("#"+Split_Arr[i]+"v").show('animate');
				$("#"+Split_Arr[i]+"v").html('Please enter a value.');
				//$("#Main_Error_Div").show('slow');
				var E = 1;
			}
			
		}
		
		if(Field_Type == 'select-one'){
				if(Field_Val == '0'){
					$("#"+Split_Arr[i]).css('background-color', '#F6DDD8');
					//$("#"+Split_Arr[i]+"v").show('animate');
					//$("#"+Split_Arr[i]+"v").html('Select the Values');
					var E = 1;
				}
		}

	}

	if(E == 1){
			return false;
	}
	else{
		$("#Creat_POI_Window").hide();
		$("#Creat_POI_Window1").show();
		$("#poi-ajax1").show();
		var xmlhttp;
		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject){
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else{
		  alert("Your browser does not support XMLHTTP!");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4){
				$("#poi-ajax1").hide();
				document.getElementById('New_Poi_Output_txt').innerHTML=xmlhttp.responseText;
				//setTimeout("document.getElementById('Creat_POI_Window1').style.display='none'",2000);
				setTimeout("$('#Creat_POI_Window1').hide('slow');",2000);
			}
		}
		var Latitude = $("#Latitude").val();
		var Longitude = $("#Longitude").val();
		var POI_Name = $("#POI_Name").val();
		var Radius = $("#Radius").val();
		Url = 'poi-creation-ajax1.php?Latitude='+Latitude+'&Longitude='+Longitude+'&POI_Name='+POI_Name+'&Radius='+Radius;
		xmlhttp.open("GET",Url,true);
		xmlhttp.send(null);
	}
}

function hide_New_Poi_txt(){
		$("#hide_New_Poi_txt").hide('slow');
}




/* #########

	Right side tab

#############
*/


function Production_Total_Ajax(From_Date,To_Date){
	var Get_Val = document.getElementById('production').value;
	var From_Date = document.getElementById('inputDate').value;
	var To_Date = document.getElementById('inputDate1').value;
	document.getElementById('Ajax_Img').style.display='block';
		
	var xmlhttp;
	if (window.XMLHttpRequest){
	  xmlhttp=new XMLHttpRequest();
	}
	else if (window.ActiveXObject){
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	else{
	  alert("Your browser does not support XMLHTTP!");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4){//alert(xmlhttp.responseText);
			document.getElementById('Output_Div').innerHTML=xmlhttp.responseText;
			document.getElementById('Output_Div').style.display='block';
			document.getElementById('Ajax_Img').style.display='none';
		}
	}
	Url = "channel2_ajax.php?p="+Get_Val+"&fd="+From_Date+"&td="+To_Date;
	xmlhttp.open("GET",Url,true);
	xmlhttp.send(null);
}

/* #########

	Open_PowerCurve

#############
*/

function Open_PowerCurve(IMEI,From,To, DType, HType,PL){
	window.parent.location = 'Power_Curve_Chart_Type'+DType+'_'+HType+'.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+PL;
}


/* ########

	open_PowerWindspeed
#########

*/

function Open_PowerWindspeedCurve(IMEI,From,To,Len){
	window.parent.location = 'Power_Windspeed_Chart.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+Len;
}

function Open_PowerWindspeedCurveRenom(IMEI,From,To,Len){
	window.parent.location = 'Power_Windspeed_Chart_Renom.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+Len;
}
function Open_PowerWindspeedCurveSwami(IMEI,From,To,Len){
	window.parent.location = 'Power_Windspeed_Chart_Swami.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+Len;
}

function Open_PowerWindspeedCurveRenomByDay(IMEI,From,To,Len){
	window.parent.location = 'Power_Windspeed_Chart_RenomByDay.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+Len;
}
function Open_PowerWindspeedCurveSwamiByDay(IMEI,From,To,Len){
	window.parent.location = 'Power_Windspeed_Chart_SwamiByDay.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+Len;
}

function Open_PowerWindspeedCurveSendan(IMEI,From,To,Len){
	window.parent.location = 'Power_Windspeed_Chart_Sendan.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+Len;
}
function Open_PowerWindspeedCurveClarion(IMEI,From,To,Len){
	window.parent.location = 'Power_Windspeed_Chart_Clarion.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+Len;
}

function Open_PowerWindspeedCurveSendanByDay(IMEI,From,To,Len){
	window.parent.location = 'Power_Windspeed_Chart_SendanByDay.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+Len;
}

function Open_PowerWindspeedCurveMonth(IMEI,MonthYear,Len){
	window.parent.location = 'Power_Windspeed_Chart_Monthly.php?c1='+IMEI+'&Year='+MonthYear+'&l='+Len;
}
/* #########

	Open_PowerCurveDay

#############
*/

function Open_PowerCurveDay(IMEI,From,To, DType, HType,PL){
	window.parent.location = 'Power_Windspeed_Chart_type7.php?c1='+IMEI+'&From='+From+'&To='+To+'&l='+PL;
}
function Open_PowerWindspeedRefCurve(Turbine_No,From,To,Len){
	window.parent.location = 'PowerWind_Ref_Curve.php?c1='+Turbine_No+'&From='+From+'&To='+To+'&l='+Len;
}


/* #########

	New Window Open

#############
*/

function New_Win_Open(IMEI,Url,Leng){
	window.parent.location = Url+"?c1="+IMEI+"&l="+Leng;
}

/* #########

	New Window Open1

#############
*/

function New_Win_Open1(IMEI,Url,Year,Length){
	window.parent.location = Url+"?c1="+IMEI+"&Year="+Year+"&l="+Length;
}
function New_Win_Open2(IMEI,Url,From,To,Length,FType){
	window.parent.location = Url+"?c1="+IMEI+"&From="+From+"&To="+To+"&l="+Length+"&FType="+FType;
}
function New_Win_Open3(IMEI,Url,MonthYear,Length){
	window.parent.location = Url+"?c1="+IMEI+"&Year="+MonthYear+"&l="+Length;
}


/* #########

	For_Service_Report

#############
*/

function For_Service_Report(Get_Val){
	/* if(Get_Val == 12 || Get_Val == 15){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
	}	
	else if(Get_Val == 18){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'block';
		
	}else{
		document.getElementById('inputDate').style.display = 'block';
		document.getElementById('inputDate1').style.display = 'block';		
		document.getElementById('InputYearDiv').style.display = 'none';
	} */

	 if(Get_Val == 27 || Get_Val == 37 || Get_Val == 38){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'block';
	}
	else if(Get_Val == 35 || Get_Val == 36){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'block';
	}	
	else if(Get_Val == 12){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'block';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}else if(Get_Val == 13 || Get_Val == 50 ){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'block';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}else{
		document.getElementById('inputDate').style.display = 'block';
		document.getElementById('inputDate1').style.display = 'block';		
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}

}

/* #########

	For_Service_Report

#############
*/

function For_Service_Report_Type1(Get_Val){
	/*if(Get_Val == 17){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'block';
		
	}else{
		document.getElementById('inputDate').style.display = 'block';
		document.getElementById('inputDate1').style.display = 'block';		
		document.getElementById('InputYearDiv').style.display = 'none';
	}*/
	if(Get_Val == 27){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'block';
	}	
	else if(Get_Val == 12){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'block';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}else if(Get_Val == 35 || Get_Val == 36){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'block';
	}else if(Get_Val == 13 || Get_Val == 40){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'block';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}else{
		document.getElementById('inputDate').style.display = 'block';
		document.getElementById('inputDate1').style.display = 'block';		
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}
}

/* #########

	For_Service_Report Type 6

#############
*/

function For_Service_Report_Type6(Get_Val){
	 if(Get_Val == 20 || Get_Val == 27 || Get_Val == 37 || Get_Val == 38){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'block';
	}	
	else if(Get_Val == 12){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'block';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}else if(Get_Val == 35 || Get_Val == 36){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'block';
	}else if(Get_Val == 13 || Get_Val == 46){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'block';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}else{
		document.getElementById('inputDate').style.display = 'block';
		document.getElementById('inputDate1').style.display = 'block';		
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}
}

/* #########

	For_Service_Report Type 9

#############
*/

function For_Service_Report_Type9(Get_Val){
	 if(Get_Val == 5){
		document.getElementById('inputDate').style.display = 'none';
		document.getElementById('inputDate1').style.display = 'none';
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'block';
	} else{
		document.getElementById('inputDate').style.display = 'block';
		document.getElementById('inputDate1').style.display = 'block';		
		document.getElementById('InputYearDiv').style.display = 'none';
		document.getElementById('InputYearDiv1').style.display = 'none';
		document.getElementById('InputMonthYearDiv').style.display = 'none';
	}
}



/* #########

	Radio Button Enable

#############
*/

function Radio_Box(Get_Val,Get_Val1){
	if(Get_Val1 == 'Yes'){
		document.getElementById(Get_Val).style.display = 'block';
	}	
	else if(Get_Val1 == 'No'){
		document.getElementById(Get_Val).style.display = 'none';
	}	
}



/* #########

	Item_Code

#############
*/

function Item_Code(Get_Val,Get_Id){
		var xmlhttp;
		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject){
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else{
		  alert("Your browser does not support XMLHTTP!");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4){
				if(xmlhttp.responseText == 1){
					document.getElementById('Yes-Box-Others['+Get_Id+']').style.display='block';			
				}
				else{
					document.getElementById('Item_Desc['+Get_Id+']').innerHTML=xmlhttp.responseText;			
				}	
			}
		}
		Url = 'Item_Code_Ajax.php?Get_Val='+Get_Val;
		xmlhttp.open("GET",Url,true);
		xmlhttp.send(null);

}


/* #########

	Check Two dates for Service Report

#############
*/

function Check_TwoDates(){
		FD =document.getElementById('Row18').value;
		FT = document.getElementById('Row19').value;
		TD =document.getElementById('Row20').value;
		TT = document.getElementById('Row21').value;
		
		var xmlhttp;
		if (window.XMLHttpRequest){
		  xmlhttp=new XMLHttpRequest();
		}
		else if (window.ActiveXObject){
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		else{
		  alert("Your browser does not support XMLHTTP!");
		}
		xmlhttp.onreadystatechange=function(){
			if(xmlhttp.readyState==4){
				document.getElementById('Row22').value=xmlhttp.responseText;			
			}
		}
		Url = "Service_And_BreadDown_Report_Excel_Type2_Ajax.php?FD="+FD+"&FT="+FT+"&TD="+TD+"&TT="+TT;
		xmlhttp.open("GET",Url,true);
		xmlhttp.send(null);

}


/* #########

	Gen_Export_Calc

#############
*/

function Gen_Export_Calc(IMEI,RType){
	
	//document.getElementById('History_Location_Summary_Ajax').style.display = 'block';
	var xmlhttp;
	if (window.XMLHttpRequest){
	  xmlhttp=new XMLHttpRequest();
	}
	else if (window.ActiveXObject){
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  	}
	else{
	  alert("Your browser does not support XMLHTTP!");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState==4){
			if(RType == 'Month'){
				document.getElementById('GEM_Div1').style.display= "none";			
				document.getElementById('GEM_Div2').style.display= "none";			
				document.getElementById('GEM_Div_Month').innerHTML= xmlhttp.responseText;			
			}
			if(RType == 'Year'){
				document.getElementById('GEY_Div1').style.display= "none";			
				document.getElementById('GEY_Div_Year').innerHTML= xmlhttp.responseText;			
			}
		}
	}
	if(RType == 'Month'){
		Url = "Gen_Export_Month.php?IMEI="+IMEI;
	}
	else if(RType == 'Year'){	
		Url = "Gen_Export_Year.php?IMEI="+IMEI;
	}
	xmlhttp.open("GET",Url,true);
	xmlhttp.send(null);
}

