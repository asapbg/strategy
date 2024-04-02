(function( factory ) {
	if ( typeof define === "function" && define.amd ) {
		define( ["jquery", "../jquery.validate"], factory );
	} else if (typeof module === "object" && module.exports) {
		module.exports = factory( require( "jquery" ) );
	} else {
		factory( jQuery );
	}
}(function( $ ) {

/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: BG (Bulgarian; български език)
 */
$.extend( $.validator.messages, {
	required: "Полето е задължително.",
	remote: "Моля, въведете правилната стойност.",
	email: "Моля, въведете валиден email.",
	url: "Моля, въведете валидно URL.",
	date: "Моля, въведете валидна дата.",
	dateISO: "Моля, въведете валидна дата (ISO).",
	number: "Моля, въведете валиден номер.",
	digits: "Моля, въведете само цифри.",
	creditcard: "Моля, въведете валиден номер на кредитна карта.",
	equalTo: "Моля, въведете същата стойност отново.",
	extension: "Моля, въведете стойност с валидно разширение.",
	maxlength: $.validator.format( "Моля, въведете не повече от {0} символа." ),
	minlength: $.validator.format( "Моля, въведете поне {0} символа." ),
	rangelength: $.validator.format( "Моля, въведете стойност с дължина между {0} и {1} символа." ),
	range: $.validator.format( "Моля, въведете стойност между {0} и {1}." ),
	max: $.validator.format( "Моля, въведете стойност по-малка или равна на {0}." ),
	min: $.validator.format( "Моля, въведете стойност по-голяма или равна на {0}." ),
    oneUpperLetter: "Полето трябва да съдържа поне една голяма буква.",
    oneLowerLetter: "Полето трябва да съдържа поне една малка буква.",
    oneDigit: "Полето трябва да съдържа поне една цифра.",
    oneSpecialCharacter: "Полето трябва да съдържа поне един специален символ",
    betweenLength: "Полето трябва да е с дължина между 8 и 16 символа.",
    noDigits: "Не може да съдържа цифри.",
    noSpecialCharacters: "Не може да съдържа специални символи @#$%&!_=*№}{\\]\\[^:,.().",
    allowedSymbolsPassword: "Използвайте латинкси букви, цифри и специални символи: @#$%&!_=№*}{\\]\\[^:,.().",
} );
return $;
}));
