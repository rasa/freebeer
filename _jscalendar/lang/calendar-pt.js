// ** I18N

// Calendar PT language
// Author: Elcio Ferreira, <elciof@icqmail.com>
// Encoding: utf-8
// Distributed under the same terms as the calendar itself.


// full day names
Calendar._DN = new Array
("Domingo",
 "Segunda-feria",
 "Tera-feira",
 "Quarta-feira",
 "Quinta-feira",
 "Sexta-feira",
 "Sbado",
 "Domingo");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN_len = 3

// full month names
Calendar._MN = new Array
("Janeiro",
 "Fevereiro",
 "Maro",
 "Abril",
 "Maio",
 "Junho",
 "Julho",
 "Agosto",
 "Setembro",
 "Outubro",
 "Novembro",
 "Dezembro");


// short month names
Calendar._SMN_len =3

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Sobre o calendrio";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2003\n" + // don't translate this this ;-)
"Para a ltima verso, visite: http://dynarch.com/mishoo/calendar.epl\n" +
"Distribudo sob a GNU LGPL.  Veja http://gnu.org/licenses/lgpl.html para mais detalhes." +
"\n\n" +
"Seleo de data:\n" +
"- Use os botes \xab, \xbb para selecionar o ano\n" +
"- Use os botes " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " para selcionar o ms\n" +
"- Segure o boto do mouse em qualquer dos botes acima para selecionar de uma lista.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Seleo de hora:\n" +
"- Clique em qualquer uma das partes da hora para aumentar\n" +
"- Clique segurando SHIFT para diminuir\n" +
"- Clique e arraste para selecionar rapidamente.";

Calendar._TT["PREV_YEAR"] = "Ano anterior (segure para lista)";
Calendar._TT["PREV_MONTH"] = "Ms anterior (segure para lista)";
Calendar._TT["GO_TODAY"] = "Ir para hoje";
Calendar._TT["NEXT_MONTH"] = "Prximo ms (segure para lista)";
Calendar._TT["NEXT_YEAR"] = "Prximo ano (segure para lista)";
Calendar._TT["SEL_DATE"] = "Selecionar data";
Calendar._TT["DRAG_TO_MOVE"] = "Segure para mover";
Calendar._TT["PART_TODAY"] = " (hoje)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Mostrar %s primeiro";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Fechar";
Calendar._TT["TODAY"] = "Hoje";
Calendar._TT["TIME_PART"] = "(Shift-)Clique ou arraste para mudar";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d/%m/%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%a, %e de %b";

Calendar._TT["WK"] = "sem";
Calendar._TT["TIME"] = "Hora:";

/*
// ** I18N


// tooltips
Calendar._TT = {};
Calendar._TT["TOGGLE"] = "Trocar o primeiro dia da semana";
Calendar._TT["PREV_YEAR"] = "Ano Anterior (mantenha para menu)";
Calendar._TT["PREV_MONTH"] = "Ms Anterior (mantenha para menu)";
Calendar._TT["GO_TODAY"] = "Ir para hoje";
Calendar._TT["NEXT_MONTH"] = "Prximo Ms (mantenha para menu)";
Calendar._TT["NEXT_YEAR"] = "Prximo Ano (mantenha para menu)";
Calendar._TT["SEL_DATE"] = "Escolha Data";
Calendar._TT["DRAG_TO_MOVE"] = "Arraste para mover";
Calendar._TT["PART_TODAY"] = " (hoje)";
Calendar._TT["MON_FIRST"] = "Mostrar Segunda primeiro";
Calendar._TT["SUN_FIRST"] = "Mostrar Domingo primeiro";
Calendar._TT["CLOSE"] = "Fechar";
Calendar._TT["TODAY"] = "Hoje";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "a-mm-dd";
Calendar._TT["TT_DATE_FORMAT"] = "D, M d";

Calendar._TT["WK"] = "sm";
*/