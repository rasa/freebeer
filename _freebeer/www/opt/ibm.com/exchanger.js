// $CVSHeader: _freebeer/www/opt/ibm.com/exchanger.js,v 1.1.1.1 2004/03/03 22:48:41 ross Exp $
// Source: http://www-106.ibm.com/developerworks/web/library/wa-exrel/?dwzone=web
//The browser detection function. 
//This function can be used for other purposes also.

function UserAgent() 
{
  var b=navigator.appName.toUpperCase();

  if (b=="NETSCAPE") this.b="ns";
  else if (b=="MICROSOFT INTERNET EXPLORER") this.b="ie";
  else if (b=="OPERA") this.b="op";
  else this.b=b;

  this.version=navigator.appVersion;
  this.v=parseInt(this.version);

  this.ns=(this.b=="ns" && this.v>=4);
  this.ns4=(this.b=="ns" && this.v==4);
  this.ns5=(this.b=="ns" && this.v==5);

  this.ie=(this.b=="ie" && this.v>=4);
  this.ie4=(this.version.indexOf('MSIE 4')>0);
  this.ie5=(this.version.indexOf('MSIE 5')>0);
  this.ie55=(this.version.indexOf('MSIE 5.5')>0);
  this.ie6=(this.version.indexOf('MSIE 6')>0);

  this.op = (this.b=="op");
  this.op4 = (this.b=="op" && this.v==4);
  this.op5 = (this.b=="op" && this.v==5);
}

at=new UserAgent();

//if you want to create the frame or layer dynamically, do not
//specify a name, do something like this, new exchanger();

function exchanger(name)
{
  //hold the dynamically created iframe or layer
  this.lyr = null;

  //to remember if the iframe or layer is created dynamically.
  this.isDynamic = false;

  this.name=name||"";
  this.fakeid=0;

  if (name == null || name=="")
  {
    this.isDynamic = true;
    this.create();
  }
  else
  {
    this.name=name;
    if (at.ns4)
    {
      this.lyr = window.document.layers[this.name];
    }
  }
}

//this function should not be called directly
exchanger.prototype.create=function()
{
  if (at.ns4) 
  {
    this.lyr=new Layer(0);
    this.visibility = "hide";
  }
  else if (at.ie || at.ns5) 
  {
    this.lyr=document.createElement("IFRAME");
    this.lyr.width=0;
    this.lyr.height=0;
    this.lyr.marginWidth=0;
    this.lyr.marginHeight=0;
    this.lyr.frameBorder=0;
    this.lyr.style.visibility="hidden";
    this.lyr.style.position="absolute";
    this.lyr.src="";
    this.name="tongIFrame"+window.frames.length;
    //this will make IE work.
    this.lyr.setAttribute("id",this.name);
    //this will make netscape work.
    this.lyr.setAttribute("name",this.name);
    document.body.appendChild(this.lyr);
  }
}

exchanger.prototype.sendData=function(url)
{
  this.fakeid += 1;
  var newurl = "";
  if (url.indexOf("?") >= 0)
    newurl = url + "&fakeId" + this.fakeid;
  else
    newurl = url + "?fakeId" + this.fakeid;

  if (this.isDynamic||at.ns4)
    this.lyr.src=newurl;
  else
  {
    if (at.ie || at.ns5 || at.op)
    {
      window.frames[this.name].document.location.replace(newurl);
    }
  }
}


exchanger.prototype.retrieveData=function(varName)
{
	var rv;
	var e;

	if (at.ns4) {
		e = "this.lyr." + varName;
	} else if (at.ie || at.ns5 || at.op) {
		e = "window.frames['" + this.name + "']." + varName;
	} else {
		return rv;
	}

	var dt = new Date();
	var now = dt.getTime();
	var timeout = now + 0;

	do {
		rv = eval(e);
		if (typeof(rv) != 'undefined') {
			break;
		}
		dt = new Date();
		now = dt.getTime();
	} while (now < timeout);

	return rv;
}
