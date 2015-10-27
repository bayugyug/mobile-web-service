$(function() {

	var DOMAIN = 'http://10.8.0.54';
	var params = {}


	//list remote urls
	params["/mobile-webservice/index.php/ldap/restapi/signin"]     = ["user", "pass"]; 
	params["/mobile-webservice/index.php/ldap/restapi/search"]     = ["user"]; 
	params["/mobile-webservice/index.php/ldap/restapi/list"]       = ["company"]; 
	params["/mobile-webservice/index.php/ldap/restapi/modify"]     = ["user", "firstname", "middlename", "lastname","description","active"]; 
	params["/mobile-webservice/index.php/ldap/restapi/changepass"] = ["user", "pass", "newpass"]; 
	params["/mobile-webservice/index.php/ldap/restapi/add"]        = ["user", "pass", "email","firstname", "middlename", "lastname","description","company"]; 
	params["/mobile-webservice/index.php/ldap/restapi/memberof"]   = ["user"]; 
	params["/mobile-webservice/index.php/ldap/restapi/session"]    = ["user", "company"]; 
	params["/mobile-webservice/index.php/ldap/restapi/sid"]        = ["sid"]; 
	params["/mobile-webservice/index.php/ldap/restapi/signout"]    = ["user", "company"]; 
	params["/mobile-webservice/index.php/ldap/restapi/resetpass"]  = ["user", "pass"]; 
	params["/mobile-webservice/index.php/ldap/restapi/encryptword"]= ["word"]; 
	params["/mobile-webservice/index.php/ldap/restapi/decryptword"]= ["word"]; 
	params["/mobile-webservice/index.php/ldap/restapi/changemail"] = ["user", "email"];
	
	
	//visaguide
	params["/mobile-webservice/index.php/websvc/newsfeed/visaguidance"] = ["country","nationality","page", "batch"];
	
	//traveltips
	//-- tips
	params["/mobile-webservice/index.php/websvc/traveltips/default"]         = ["title","introtext","maintext","page", "batch"];
	//-- itinerary
	params["/mobile-webservice/index.php/websvc/travelitinerary/default"]    = ["port_code","page", "batch"];
	
	//port-guides 
	//-- point of interest
	params["/mobile-webservice/index.php/websvc/portguide/poi"]              = ["port","page", "batch"];
	params["/mobile-webservice/index.php/websvc/portguide/agent"]            = ["port","page", "batch"];
	params["/mobile-webservice/index.php/websvc/portguide/getports1"]        = ["menu","title", "page", "batch"];
	params["/mobile-webservice/index.php/websvc/portguide/getports2"]        = ["menu","parent","page", "batch"];
	params["/mobile-webservice/index.php/websvc/portguide/getport"]          = ["page", "batch"];
	params["/mobile-webservice/index.php/websvc/portguide/getportdetails"]   = ["title","page", "batch"];
	
	//latest-news
	params["/mobile-webservice/index.php/websvc/latestnews/news1"]           = ["page", "batch"];
	params["/mobile-webservice/index.php/websvc/latestnews/news2"]           = ["id",   "page", "batch"];
	params["/mobile-webservice/index.php/websvc/latestnews/news3"]           = ["page", "batch"];
	
	//inbox
	params["/mobile-webservice/index.php/websvc/inbox/messages1"]            = ["user","lastMsgId","page", "batch"];
	params["/mobile-webservice/index.php/websvc/inbox/messages2"]            = ["user","page", "batch"];
	params["/mobile-webservice/index.php/websvc/inbox/flagread"]             = ["id"];
	
	//email
	params["/mobile-webservice/index.php/websvc/email/scheduler"]            = ["employeeid","employeeemail","name","scheduleremail","subject","message"];
	params["/mobile-webservice/index.php/websvc/email/techsupport"]          = ["employeeid","employeeemail","name","scheduleremail","subject","message"];
	
	
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
		$('#txtResponse').html('<div class="alert alert-warning" style="width:95%">Please wait while we are retrieving from API server  <span class="glyphicon glyphicon-download-alt"></span></div>');
		
        if ($('#txtURL').val() != "")
        {
            var params = getData();
            $.post( $('#txtURL').val(), params)
              .done(function( resp ) {
                  $('#txtResponse').html('<p class="wbreak">'+escapeHtml(resp)+'</p>');
				  console.log(resp);
            }).fail(function( resp ) {
                  $('#txtResponse').html('<p class="wbreak" style="color:red;font-size:1.2em;">'+JSON.stringify(resp)+'</p>');
				  console.log(resp);
            });
        }
		else
		{
			$('#txtResponse').html('&nbsp');
		}

    });
	
	
	var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': '&quot;',
    "'": '&#39;',
    "/": '&#x2F;'
	};

  function escapeHtml(string) {
    return String(string).replace(/[&<>"'\/]/g, function (s) {
      return entityMap[s];
    });
  }
	
});