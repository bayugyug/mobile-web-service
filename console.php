<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="en">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/tools.js"></script>
    <title>Mobile Webservice Console</title>
<style>
	.wbreak {
	    word-wrap: break-word;
	    -moz-hyphens:auto; 
	    -webkit-hyphens:auto; 
	    -o-hyphens:auto; 
	    word-wrap: break-word;
    	    overflow-wrap: break-word;
	    text-overflow: ellipsis;
	    width: 720px; 
	}
</style>
</head>

<body>

<div class="container">
  
    
	<div class="jumbotron">
    <h1>Mobile Webservice  API Console</h1>
    </div>
	<div class="table-responsive">
    <table style="width:800px;" class='table table-hover'>
        <tr>
            <td style="width:35%;" class="success">
				<strong>
				Choose API:
				  </strong>
				</td>
            <td style="width:65%;" class="success">
                <select name="cmbAPIType" id="cmbAPIType" style="width:100%;" class="form-control" >
                    <option value="">Select API</option>
					<option value="">- - - - - - </option>
					<option value="/mobile-webservice/index.php/ldap/restapi/signin"     >01. LDAP Sign-in User</option>
					<option value="/mobile-webservice/index.php/ldap/restapi/search"     >02. LDAP Search User</option>
					<option value="/mobile-webservice/index.php/ldap/restapi/changepass" >05. LDAP User Change Password</option>
					<option value="/mobile-webservice/index.php/ldap/restapi/sid"        >09. LDAP User Session (by SID SessionId)</option>
					<option value="/mobile-webservice/index.php/ldap/restapi/signout"    >10. LDAP Sign-out User</option>
					<option value="/mobile-webservice/index.php/ldap/restapi/resetpass"  >11. LDAP User Reset Password</option>
					<option value="/mobile-webservice/index.php/ldap/restapi/encryptword">12. LDAP Utils (Encrypt)</option>
					<option value="/mobile-webservice/index.php/ldap/restapi/decryptword">13. LDAP Utils (Decrypt)</option>
					<option value="/mobile-webservice/index.php/ldap/restapi/changemail" >14. LDAP User Change Email</option>
					<option value="">- - - - - - </option>
					<option value="/mobile-webservice/index.php/websvc/newsfeed/visaguidance" >15. VISA Guidance</option>
					<option value="">- - - - - - </option>
					<option value="/mobile-webservice/index.php/websvc/traveltips/default"      >16. TRAVEL Tips</option>
					<option value="/mobile-webservice/index.php/websvc/travelitinerary/default" >17. TRAVEL Itinerary</option>
					<option value="">- - - - - - </option>
					<option value="/mobile-webservice/index.php/websvc/portguide/poi"            >18. PORT GUIDE - Points of Interest (POI)</option>
					<option value="/mobile-webservice/index.php/websvc/portguide/agent"          >19. PORT GUIDE - Port Agent (Port Name)</option>
					<option value="/mobile-webservice/index.php/websvc/portguide/getports1"      >20. PORT GUIDE - Port Information - 1 (menu/title)</option>
					<option value="/mobile-webservice/index.php/websvc/portguide/getports2"      >21. PORT GUIDE - Port Information - 2 (menu/parent)</option>
					<option value="/mobile-webservice/index.php/websvc/portguide/getport"        >22. PORT GUIDE - Port </option>
					<option value="/mobile-webservice/index.php/websvc/portguide/getportdetails" >23. PORT GUIDE - Port Details</option>
					<option value="">- - - - - - </option>
					<option value="/mobile-webservice/index.php/websvc/latestnews/news1"         >24. LATEST NEWS - 1</option>
					<option value="/mobile-webservice/index.php/websvc/latestnews/news2"         >25. LATEST NEWS - 2</option>
					<option value="/mobile-webservice/index.php/websvc/latestnews/news3"         >26. LATEST NEWS - 3</option>
					<option value="">- - - - - - </option>
					<option value="/mobile-webservice/index.php/websvc/inbox/messages1"          >27. INBOX MESSAGES - 1</option>
					<option value="/mobile-webservice/index.php/websvc/inbox/messages2"          >28. INBOX MESSAGES - 2</option>
					<option value="/mobile-webservice/index.php/websvc/inbox/flagread"           >29. INBOX MESSAGES - (Set Flag Read)</option>
					<option value="">- - - - - - </option>
					<option value="/mobile-webservice/index.php/websvc/email/scheduler"          >30. EMAIL MESSAGES - Scheduler </option>
					<option value="/mobile-webservice/index.php/websvc/email/techsupport"        >31. EMAIL MESSAGES - Tech Support </option>
					<option value="">- - - - - - </option>					
			   </select>
            </td>
        </tr>

        <tr>
            <td class="success">
			  <strong>
			API URL:
			  </strong>
			</td>
            <td class="success"><input type="text" id="txtURL" value="" readonly="true" style="width:100%;" class="form-control" /></td>
        </tr>

        <tr>
            <td class="success">
				  <strong>
				POST Params (Key / Value)
				  </strong>
				</td>
            <td id="tdParams" class="success">
                &nbsp;
            </td>
        </tr>

        <tr>
            <td class="success">&nbsp;</td>
            <td align="left" valign="top" class="success">
					
					    <a href="#" class="btn btn-info btn-lg" id="cmbPost">
							<span class="glyphicon glyphicon-search">
							</span> POST API CALL
						</a>
				
				</td>
        </tr>
        <tr>
            <th class="success" colspan="2" align="center" valign="top">
				  <strong>
					Response:
				  </strong>
				  </th>
        </tr>
        <tr>
            <td colspan="2" align="left" valign="top" class="bg-info">
				<div id="txtResponse"  class="wbreak" >
               &nbsp;
			   </div>
            </td>
        </tr>


		</table>
	</div>
</div>
</body>
</html>


