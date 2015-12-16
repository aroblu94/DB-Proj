function str_replace (search, replace, subject, count) {
    // Replaces all occurrences of search in haystack with replace  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/str_replace    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'    var i = 0,
        j = 0,
        temp = '',
        repl = '',
        sl = 0,        fl = 0,
        f = [].concat(search),
        r = [].concat(replace),
        s = subject,
        ra = r instanceof Array,        sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    } 
    for (i = 0, sl = s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }        for (j = 0, fl = f.length; j < fl; j++) {
            temp = s[i] + '';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {                this.window[count] += (temp.length - s[i].length) / f[j].length;
            }
        }
    }
    return sa ? s : s[0];}
    
    
    
    
    
function ansi2ita(ansi) {
		var temp=new Array();
		ansi=str_replace(" ","/",ansi);
		ansi=str_replace("-","/",ansi);
		ansi=str_replace(".","/",ansi);
		temp=ansi.split('/');
		
		ita=temp[2]+'/'+temp[1]+'/'+temp[0];
		return ita;
		
}

function ita2ansi(ita) {
		var temp=new Array();
		ita=str_replace(" ","/",ita);
		ita=str_replace("-","/",ita);
		ita=str_replace(".","/",ita);
		temp=ita.split('/');
		
		ansi=temp[2]+'/'+temp[1]+'/'+temp[0];
		return ansi;
		
}  

function srv_qta(quale,verso,classe){
	
	attuale=$(quale).prevAll('input').val();	
	
	
	if(verso=='plus' & attuale<10)
		attuale++;
	else if(verso=='minus' &attuale>0)
		attuale--;

	if(attuale>0){
		$("." + classe).css("opacity","1");
		$("#" + classe).val(1);
	}
	else{
		$("." + classe).css("opacity","0.3");
		$("#" + classe).val(0);		
	}
	$(quale).prevAll('input').val(attuale);
}



///quale valore e radio 

function cecca(quale){
	
	attuale=$("#" + quale + "_img").attr("src");
	
	if(arguments[1]){
		$("." + arguments[1]).val(0);
		$("." + arguments[1]).attr("src","img/chk_off.gif");
	}

	
	if(attuale=="img/chk_off.gif"){
		$("#" + quale + "_img").attr("src","img/chk_on.gif");
		$("#" + quale).val(1);
		$("." + quale).css("opacity","1");
	}
	else{
		
		$("#" + quale + "_img").attr("src","img/chk_off.gif");
		$("#" + quale).val(0);
		$("." + quale).css("opacity","0.3");	
	}
	
}



function cecca_new(quale,valore){
	
	attuale=$("#" + quale + valore +"_img").attr("src");
	var campo=quale;
	
	if(arguments[2]){
		$("." + arguments[2]).val(0);
		$("." + arguments[2]).attr("src","img/chk_off.gif");
		var campo=arguments[2];
	}
	
	

	
	if(attuale=="img/chk_off.gif"){
		$("#" + quale + valore + "_img").attr("src","img/chk_on.gif");
		$("#" + campo).val(valore);
		
	}
	else{
		if(attuale=="img/chk_on.gif" & arguments[2]!=''){

			$("#" + quale + valore + "_img").attr("src","img/chk_on.gif");
			$("#" + campo).val(valore);
			

		}
		else{
			$("#" + quale + valore + "_img").attr("src","img/chk_off.gif");
			if(!arguments[2]){
				$("#" + campo).val(0);
			}
		}


		
			
	}
	
}


