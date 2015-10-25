$(function() {

	var DOMAIN = 'http://10.8.0.54';
	var params = {}

	//DOMAIN     = 'http://127.0.0.1';
	
	//list remote urls
	params["/mobile-webservice/index.php/ldap/restapi/signin"]     = ["user", "pass"]; 
	params["/mobile-webservice/index.php/ldap/restapi/search"]     = ["user"]; 
	params["/mobile-webservice/index.php/ldap/restapi/list"]       = ["company"]; 
	params["/mobile-webservice/index.php/ldap/restapi/modify"]     = ["user", "firstname", "middlename", "lastname","description"]; 
	params["/mobile-webservice/index.php/ldap/restapi/changepass"] = ["user", "pass", "newpass"]; 
	params["/mobile-webservice/index.php/ldap/restapi/add"]        = ["user", "pass", "email","firstname", "middlename", "lastname","description","company"]; 
	params["/mobile-webservice/index.php/ldap/restapi/memberof"]   = ["user"]; 
	params["/mobile-webservice/index.php/ldap/restapi/session"]    = ["user", "company"]; 
	params["/mobile-webservice/index.php/ldap/restapi/sid"]        = ["sid"]; 
	params["/mobile-webservice/index.php/ldap/restapi/signout"]    = ["user"]; 
	params["/mobile-webservice/index.php/ldap/restapi/resetpass"]  = ["user", "pass"]; 
	params["/mobile-webservice/index.php/ldap/restapi/encryptword"]= ["word"]; 
	params["/mobile-webservice/index.php/ldap/restapi/decryptword"]= ["word"]; 
	
	params["/mobile-webservice/index.php/websvc/newsfeed/visaguidance"]= ["visa_type","nationality_code","page","result_per_page"]; 
	params["/mobile-webservice/index.php/websvc/newsfeed/encryptword"] = ["word"]; 
	params["/mobile-webservice/index.php/websvc/newsfeed/decryptword"] = ["word"]; 
	

	params["/mobile-webservice/index.php/websvc/homescreen/brands"]    = ["word"]; 
	params["/mobile-webservice/index.php/websvc/homescreen/traveltips"]= ["word"]; 
	params["/mobile-webservice/index.php/websvc/homescreen/news"]      = ["word"]; 
	
	params["/mobile-webservice/index.php/websvc/messages/default"]     = ["word"]; 
	
	params["/mobile-webservice/index.php/websvc/docmgmt/default"]      = ["word"]; 
	
	params["/mobile-webservice/index.php/websvc/assignment/confirm"]   = ["word"]; 
	params["/mobile-webservice/index.php/websvc/assignment/decline"]   = ["word"]; 
	params["/mobile-webservice/index.php/websvc/assignment/docadd"]    = ["word"]; 
	params["/mobile-webservice/index.php/websvc/assignment/docupd"]    = ["word"]; 
	params["/mobile-webservice/index.php/websvc/assignment/docdel"]    = ["word"]; 
	
	params["/mobile-webservice/index.php/websvc/crewassist/getphones"]           = ["word"]; 
	params["/mobile-webservice/index.php/websvc/crewassist/getlocations"]        = ["word"]; 
	params["/mobile-webservice/index.php/websvc/crewassist/sendmsg2techassist"]  = ["word"]; 
	params["/mobile-webservice/index.php/websvc/crewassist/sendmsg2scheduler"]   = ["word"]; 
	params["/mobile-webservice/index.php/websvc/crewassist/livechat"]            = ["word"]; 

	
	params["/mobile-webservice/index.php/websvc/privacyterms/policy"]            = ["word"]; 
	params["/mobile-webservice/index.php/websvc/privacyterms/terms"]             = ["word"]; 

	
	params["/mobile-webservice/index.php/websvc/portguide/information"]       = ["word"]; 
	params["/mobile-webservice/index.php/websvc/portguide/weather"]           = ["word"]; 
	params["/mobile-webservice/index.php/websvc/portguide/transportation"]    = ["word"]; 
	params["/mobile-webservice/index.php/websvc/portguide/interest"]          = ["word"]; 

		 
	params["/mobile-webservice/index.php/websvc/flightdetails/flight"]         = ["word"]; 
	params["/mobile-webservice/index.php/websvc/flightdetails/hotel"]          = ["word"]; 
	params["/mobile-webservice/index.php/websvc/flightdetails/transportation"] = ["word"]; 
	
	params["/mobile-webservice/index.php/websvc/profile/personal"]             = ["word"]; 
	params["/mobile-webservice/index.php/websvc/profile/passport"]             = ["word"]; 
	params["/mobile-webservice/index.php/websvc/profile/contactinfo"]          = ["word"]; 
	params["/mobile-webservice/index.php/websvc/profile/parentsinfo"]          = ["word"]; 
	params["/mobile-webservice/index.php/websvc/profile/emergency"]            = ["word"]; 
	params["/mobile-webservice/index.php/websvc/profile/beneficiary"]          = ["word"]; 
	
	
	params["/mobile-webservice/index.php/websvc/qualifications/education"]     = ["word"]; 
	params["/mobile-webservice/index.php/websvc/qualifications/language"]      = ["word"]; 
	params["/mobile-webservice/index.php/websvc/qualifications/experience"]    = ["word"]; 
	params["/mobile-webservice/index.php/websvc/qualifications/skills"]        = ["word"]; 
	params["/mobile-webservice/index.php/websvc/qualifications/references"]    = ["word"]; 
	params["/mobile-webservice/index.php/websvc/qualifications/certificates"]  = ["word"]; 
	

	params["/mobile-webservice/index.php/websvc/trainings/default"]            = ["word"]; 
	

	params["/mobile-webservice/index.php/websvc/applications/default"]         = ["word"]; 
	
					
	$('#cmbAPIType').val(0);
	$('#tdParams').html('&nbsp');
	$('#tdResponse').html('&nbsp');
	$('#txtResponse').html('&nbsp');

	
	function generateParams(key)
	{
		var html = "<table cellspacing='5' cellpadding='5' style='width:90%'>";
		for (var i = 0; i < params[key].length; i++)
		{

			html += "<tr>";
			html += "<td style='width:50%;'>" + params[key][i] + "</td>";
			html += "<td><input type='text' value='' style='width:95%;' class='form-control'  /></td>";
			html += "</tr>";

		}
		html += '</table>';
		return html;
	}

    function getData()
    {
        var data = {}
        $('#tdParams tr').each(function (idx, elem) {
            var key = '';
            var val = '';
            $('td', this).each (function (indx, el2) {
                if (indx == 0)
                    key = $(el2).html();
                else
                    val = $('input', el2).val();
            });

            data[key] = val;
        });

        return data;
    }

    $('#cmbAPIType').change(function (e) {

		$('#tdResponse').html('&nbsp');
		$('#txtResponse').html('&nbsp');

        if ($(this).val() != "")
        {
            $('#tdParams').html(generateParams($(this).val()));
            $('#txtURL').val(DOMAIN + $(this).val());
            //display the post params
            $('#tdResponse').html('&nbsp');
        }
        else
        {
            $('#tdParams').html('&nbsp;');
            $('#txtResponse').html('&nbsp');
            $('#txtURL').val("");
        }
    });

    $('#cmbPost').click(function (e) {
		
		$('#tdResponse').html('&nbsp');
		$('#txtResponse').html('&nbsp');
        if ($('#txtURL').val() != "")
        {
            var xparams = getData();
            $.ajax({
				  type : "POST",
				  url  : $('#txtURL').val(),
				  data : xparams,// serializes the form's elements.
				  //dataType: "json"
				  }).done(function (xhrResponse) {
						$('#txtResponse').html('<p class="wbreak">'+xhrResponse+'</p>');
						console.log(xhrResponse);
				  }).fail(function (xhrResponse, textStatus) {
						$('#txtResponse').html('<p class="wbreak">'+xhrResponse+'</p>');
						console.log(xhrResponse);
				  }); 
        }

    });
});