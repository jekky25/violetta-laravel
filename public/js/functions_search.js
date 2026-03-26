function initSelect()
{
	document.getElementById('region').disabled = (document.getElementById('country').value==0)?true:false;
	document.getElementById('city').disabled = (document.getElementById('region').value==0)?true:false;
}

function requestError (sid, msg)
{
	sid.options.length = 0;
	sid.disabled = true;
	sid.options[sid.options.length] = new Option(msg, 0, false, false);
}

initSelect();
var xmlHttp = false;

try {
 	xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
 	try {
		xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (e2) {
		xmlHttp = false;
	}
}

if (!xmlHttp && typeof XMLHttpRequest != 'undefined') {
	xmlHttp = new XMLHttpRequest();
}

if (!xmlHttp) {
	sidco = document.getElementById("country");
	sidr = document.getElementById("region");
	sidc = document.getElementById("city");
	requestError (sidco, 'XMLHttpRequest error');
	requestError (sidr, 'XMLHttpRequest error');
	requestError (sidc, 'XMLHttpRequest error');
}

function disableSelect(fs)
{
	sidr = document.getElementById("region");
	sidc = document.getElementById("city");
	switch (fs)
	{
		case('reg'):
			sidr.options.length = sidc.options.length = 0;
			sidr.disabled = sidc.disabled = true;
			sidr.options[sidr.options.length] = new Option("--- не имеет значения ---", 0, false, false);
			sidc.options[sidc.options.length] = new Option("--- не имеет значения ---", 0, false, false);
			break;
		case('cities'):
			sidc.options.length = 0;
			sidc.disabled = true;
			sidc.options[sidc.options.length] = new Option("--- не имеет значения ---", 0, false, false);
	}
}

function updateSelect(selectId, optValue, fs)
{
  if (!xmlHttp)
    return false;

  if (optValue == 0)
  {
    disableSelect(fs);
    return false;
  }

  if (selectId == 'region')
  {
    disableSelect('cities');
  }
  sid = document.getElementById(selectId);
  sid.options.length = 0;
  sid.disabled = true;
  sid.options[sid.options.length] = new Option("Подождите, идет загрузка...", 0, false, false);
  if (selectId == 'region') {
	var url = '../../../api/regions/' + optValue + '/';
	getRegions(url);
  } else 
  {
  	var url = '../../../api/cities/' + optValue + '/';
	getCities(url);
  }
}

async function getCities(url) {
	try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Ошибка запроса');
        }
		
		const data = await response.json();
		sid.options.length = 0;
		sid.options[sid.options.length] = new Option("--- не имеет значения ---", 0, false, false);
		for(var i=0; i<data.length; i++)
		{
			sid.options[sid.options.length] = new Option(data[i].name, data[i].id, false, false);
		}
		sid.disabled = false;
	} catch (error) {
        console.error(error);
    }
}

async function getRegions(url) {
    try {
        const response = await fetch(url);

        if (!response.ok) {
            throw new Error('Ошибка запроса');
        }

        const data = await response.json();
		sid.options.length = 0;
		sid.options[sid.options.length] = new Option("--- не имеет значения ---", 0, false, false);
		for(var i=0; i<data.length; i++)
		{
			sid.options[sid.options.length] = new Option(data[i].name, data[i].id, false, false);
		}

		sid.disabled = false;
    } catch (error) {
        console.error(error);
    }
}

function find_otsil()
{

  document.getElementById("otsil").value = 'Подождите, идет поиск...';
  document.getElementById("otsil").disabled = true;
  document.anketa.submit();
}
