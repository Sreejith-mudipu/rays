function request(page,data,d)
{
    var xmlc = new XMLHttpRequest();
    xmlc.onreadystatechange = function()
    {
        if(xmlc.readyState == 4 && xmlc.status == 200){
            document.getElementById(d).innerHTML = xmlc.responseText;
        }
    };
    xmlc.open("POST",page,true);
    xmlc.send(data);
}

function moduledisp(evt,module) {
 request(module+'.php?','','pgshow');
}

function findSelection(field) {
    var test = document.getElementsByName(field);
    var sizes = test.length;
    for (i=0; i < sizes; i++) {
            if (test[i].checked==true) {
            return test[i].value;
        }
    }
}


function checkId(obj) {
	if (document.getElementById(obj)) { return true; } else { return false; }
}


function TableRowAdd(tableName,rowName,TotRowCnt) {
	
	var tbody = document.getElementById(tableName).tBodies[0];
	var row = document.getElementById(rowName);

	//get no of rows
	var counter=1;
	var oRows = tbody.getElementsByTagName('tr');
	counter = oRows.length-2; //(less 2 heading,subheading) 	
	
	var newRow = row.cloneNode(true);
	newRow.id = '';
	var newCell = newRow.childNodes;

	for ( var i=0; i<newCell.length; i++ )
	{
		if ( newCell[i].nodeName.toLowerCase() == 'td' )
		{
			var newFields = newCell[i].childNodes;
		
			for ( var j=0; j<newFields.length; j++ )
			{				
				var theName = newFields[j].name;
				
				if (theName)					
				{	
					newFields[j].name =  theName.replace(/^(R)\d{1,2}(\[a-z])?/,"$1"+counter+"$2"); 
					newFields[j].id= newFields[j].name;
					newFields[j].value = '';
				}
			}
		}
	}
	document.getElementById(TotRowCnt).value=counter;
	var insertHere = document.getElementById('writetr');
	tbody.insertBefore(newRow,insertHere);
}

//---------------createusr.php---------------
function createusrsv()
{
    var u = document.getElementById("txtcusr").value;
    var p = document.getElementById("txtcpwd").value;   
    var data = new FormData();
    data.append('z', 'sv');
    data.append('u', u);
    data.append('p', p);
    request('createusr.php?',data,'divSV');
    starttimer();
}

  function starttimer()  
  {
        setTimeout(function() {
        if(checkId("divALRT")){
         document.getElementById("divALRT").style.display = 'none';
                    }
            }, 3000);
  }
  
  
function usrdelete(id)
{
    var data = new FormData();
    data.append('z', 'dl');
    data.append('id', id);
    
    request('createusr.php?',data,'divSV');
}

function checkedRadio(div){
//returns value of checked radiobutton in div
if(!div) {return;}
div = typeof div === "string" ? document.getElementById(div) : div;
var elms = div.getElementsByTagName("*");
 for(var i = 0, maxI = elms.length; i < maxI; ++i) 
 {	 
	var elm = elms[i];
	switch(elm.type) {
        case "radio": 
        if(elm.checked==true) { return(elm.value);}          
        }
 }

}
//Student Detail save
function sdsave(id)
{
    var bt      = document.getElementById("txtbatch").value;
    var stno    = document.getElementById("txtstno").value;
    var rlno    = document.getElementById("txtrollno").value;
    var c       = document.getElementById("txtclass").value;     
    var sn      = document.getElementById("txtname").value;   
    var nty     = document.getElementById("txtnlty").value; 
    var rlg     = document.getElementById("txtrelg").value; 
    var sx      = checkedRadio("divGN");
    var doj     = document.getElementById("dtdoj").value;     
    var dob     = document.getElementById("dtdob").value;     
    var pob     = document.getElementById("txtpob").value;     
    var bg      = document.getElementById("txtblood").value; 
    var adhr    = document.getElementById("txtadhar").value; 
    var cst     = document.getElementById("txtcste").value;     
    var sbcst   = document.getElementById("txtsubcste").value; 
    
    var fn      = document.getElementById("txtfname").value;     
    var fi      = document.getElementById("txtfincome").value;     
    var fq      = document.getElementById("txtfqual").value;     
    var fo      = document.getElementById("txtfoccptn").value;     
    var fc      = document.getElementById("txtfcont").value;     
    var fe      = document.getElementById("txtfemail").value;  
    
    var mn      = document.getElementById("txtmname").value;
    var mi      = document.getElementById("txtmincome").value;
    var mq      = document.getElementById("txtmqual").value;
    var mo      = document.getElementById("txtmoccptn").value;
    var mc      = document.getElementById("txtmcont").value;
    var me      = document.getElementById("txtmmail").value;
    
    var gn      = document.getElementById("txtgname").value;
    var gc      = document.getElementById("txtgcont").value;
    var mt      = document.getElementById("txtmtongue").value;
    var ol      = document.getElementById("txtolang").value;
    var ps      = checkedRadio("divES");
    var pn      = document.getElementById("txteschlname").value;
    var sd      = document.getElementById("txtsibdetail").value;
    var ss      = document.getElementById("txtsibschool").value;
    var sa      = document.getElementById("txtsibage").value;
    var ad      = document.getElementById("txtalrg").value;
    var ic      = document.getElementById("txtinocult").value;
    var pa      = document.getElementById("txtpadd").value;
    var ca      = document.getElementById("txtcadd").value;
   
    var data = new FormData();
    data.append('bt', bt);
    data.append('stno', stno);
    data.append('rlno', rlno);
    data.append('c', c);
    data.append('sn', sn);
    data.append('nty', nty);
    data.append('rlg', rlg);
    data.append('sx', sx);
    data.append('doj', doj);
    data.append('dob', dob);
    data.append('pob', pob);
    data.append('bg', bg);
    data.append('adhr', adhr);
    data.append('cst', cst);
    data.append('sbcst', sbcst);
    data.append('fn', fn);
    data.append('fi', fi);
    data.append('fq', fq);
    data.append('fo', fo);
    data.append('fc', fc);
    data.append('fe', fe);
    data.append('mn', mn);
    data.append('mi', mi);
    data.append('mq', mq);
    data.append('mo', mo);
    data.append('mc', mc);
    data.append('me', me);
    data.append('gn', gn);
    data.append('gc', gc);
    data.append('mt', mt);
    data.append('ol', ol);
    data.append('ps', ps);
    data.append('pn', pn);
    data.append('sd', sd);
    data.append('ps', ps);
    data.append('ss', ss);
    data.append('sa', sa);
    data.append('ad', ad);
    data.append('ic', ic);
    data.append('pa', pa);
    data.append('ca', ca);
    data.append('id', id);
    data.append('z', 'sv');
    
    request('menu.php?',data,'pgshow');
    starttimer();    
}


function srchstudent(pg)
{
    var cls = document.getElementById("txtclsrch").value;
    var snm = document.getElementById("txtsrchnm").value;
    var data = new FormData();
    data.append('cls', cls);
    data.append('snm', snm);
    data.append('z', 'srch');    
    request(pg+'.php?',data,'divSR');
}

function clearsrchdiv()
{
    document.getElementById("divSR").innerHTML = '';
}
function editstudent(stno)
{
    var t = 0;
    if(checkId("divFTYP")){
        t   = checkedRadio("divFTYP");
    }
    var pg  = 'menu';
    var div = 'pgshow';
    if(t == 1 || t == 2 || t == 3) {     
        pg  = 'srchstdfees';
        div = 'divSR';
    }
    var data = new FormData();
    data.append('stno', stno);
    data.append('t', t);
    request(pg+'.php?',data,div);
}

function uploadwindow(id,sdb)
{
    if(id == '' || id == undefined){
        alert("Sorry you cannot upload image before adding students!");
        return;
    }
    window.open('imguploadpg.php?id='+id+'&sdb='+sdb,'ephoto','height=300,width=600,top=200,left=400,resizable=false');
}

function genreport()
{
    window.open('reports/receipt.php?','ephoto','height=300,width=600,top=200,left=400,resizable=false');    
}


function feescompadd(cnt,stno,t)
{
    cnt = cnt+1;
    request('srchstdfees.php?&stno='+stno+'&cnt='+cnt+'&t='+t,'divSR');
}

function admfeesv(stno)
{
    var cls  = document.getElementById('txtclsrch').value;     
    var comp = ''; var amt = ''; var fcomp = '';
    var rcount = document.getElementById('feeTabRowCnt').value;     
    for(var i=0;i<=rcount;i++){
        if(document.getElementById("R"+i+"C3").value != 1)
       {
            comp = document.getElementById("R"+i+"C1").value;
            amt  = document.getElementById("R"+i+"C2").value;
            if(comp != '' && amt > 0){
                 fcomp += comp+','+amt+'^';
             }
        }
    }
    if(fcomp == ''){ alert("Kindly enter the amount!"); return; }
    fcomp = fcomp.slice(0,-1);
    var data = new FormData();
    data.append('stno', stno);
    data.append('cls', cls);
    data.append('fcomp', fcomp);
    data.append('z', '4');    
    request('srchstdfees.php?',data,'divSR');
    starttimer();
}             

function yrfeesv(stno)
{
    var fy   = document.getElementById('txtfyr').value;
    var cls  = document.getElementById('txtclsrch').value;     
    var comp = ''; var amt = ''; var fcomp = '';
    var rcount = document.getElementById('yrfeestable').rows.length;   
    for(var i=1;i<rcount;i++)
    {
        comp = document.getElementById("R"+i+"C1").value;
        amt  = document.getElementById("R"+i+"C2").value;
        if(comp != '' && amt > 0){
            fcomp += comp+','+amt+'^';
        }
    }
    if(fcomp == ''){ alert("Kindly enter the amount!"); return; }
    fcomp = fcomp.slice(0,-1);
    var data = new FormData();
    data.append('stno', stno);
    data.append('feeyr', fy);
    data.append('cls', cls);
    data.append('fcomp', fcomp);
    data.append('z', '5');    
    request('srchstdfees.php?',data,'divSR');
    starttimer();
}  

function yrfeechng(stno,t)
{
    var y = document.getElementById('txtfyr').value;
     var data = new FormData();
    data.append('stno', stno);
    data.append('y', y);
    data.append('t', t);    
    request('srchstdfees.php?',data,'divSR');
}


function daycaresv(stno)
{
    var fy   = document.getElementById('txtfyr').value;
    var cls  = document.getElementById('txtclsrch').value;     
    var comp = ''; var amt = ''; var fcomp = '';
    for(var i=1;i<=12;i++)
    {
       if(document.getElementById("txtf"+i).value != 1)
       {
            comp = document.getElementById("txtc"+i).value;
            amt  = document.getElementById("txtf"+i).value;
            if(comp != '' && amt > 0){
                 fcomp += comp+','+amt+'^';
             }
       }
    }
    if(fcomp == ''){ alert("Kindly enter the amount!"); return; }
    fcomp = fcomp.slice(0,-1);
    var data = new FormData();
    data.append('stno', stno);
    data.append('feeyr', fy);
    data.append('cls', cls);
    data.append('fcomp', fcomp);
    data.append('z','6');    
    request('srchstdfees.php?',data,'divSR');
    starttimer();
}  



function feeprint(stno,typ,yr,rno,rdt)
{
    window.open('reports/feercpt.php?&stno='+stno+'&typ='+typ+'&yr='+yr+'&rno='+rno+'&rdt='+rdt,'mywindow','width=400,height=200,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,copyhistory=yes,resizable=yes');
}

//---------------define.php---------------
function createcodesv()
{
    var m = document.getElementById("lstmaster").value;
    var c = document.getElementById("txtcode").value;
    var n = document.getElementById("txtname").value;   
    var data = new FormData();
    data.append('z', 'sv');
    data.append('m', m);
    data.append('c', c);
    data.append('n', n);
    request('define.php?',data,'divSV');
    starttimer();
}

function masterdisplay()
{
    var m = document.getElementById("lstmaster").value;
    var data = new FormData();
    data.append('m', m);
    data.append('z', 'dsp');
    request('define.php?',data,'divSV');  
}

function codedelete(id)
{
    var m = document.getElementById("lstmaster").value;
    var data = new FormData();
    data.append('z', 'dl');
    data.append('id', id);
    data.append('m', m);
    request('define.php?',data,'divSV');
}

function viewfees(stno)
{
    var fy   = document.getElementById('txtfyr').value;
    var cls  = document.getElementById('txtclsrch').value;   
    window.open('stfeesview.php?&stno='+stno+'&feeyr='+fy+'&cls='+cls+'&z=fees','mywindow','width=400,height=200,toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,copyhistory=yes,resizable=yes');
}