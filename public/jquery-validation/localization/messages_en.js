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
 * Locale: UK (Ukrainian; українська мова)
 */
$.extend( $.validator.messages, {
    required: "This field is required.",
    remote: "Please fix this field.",
    email: "Please enter a valid email address.",
    url: "Please enter a valid URL.",
    date: "Please enter a valid date.",
    dateISO: "Please enter a valid date (ISO).",
    number: "Please enter a valid number.",
    digits: "Please enter only digits.",
    equalTo: "Please enter the same value again.",
    maxlength: $.validator.format( "Please enter no more than {0} characters." ),
    minlength: $.validator.format( "Please enter at least {0} characters." ),
    rangelength: $.validator.format( "Please enter a value between {0} and {1} characters long." ),
    range: $.validator.format( "Please enter a value between {0} and {1}." ),
    max: $.validator.format( "Please enter a value less than or equal to {0}." ),
    min: $.validator.format( "Please enter a value greater than or equal to {0}." ),
    step: $.validator.format( "Please enter a multiple of {0}." ),
    oneUpperLetter: "Password must contain at least one uppercase.",
    oneLowerLetter: "Password must contain at least one lowercase.",
    oneDigit: "Password must contain at least one digit.",
    oneSpecialCharacter: "Password must contain special characters from @#$%&!_=*-.",
    betweenLength: "Password must be between 8 to 16 characters long.",
    noDigits: "Digits are not allowed in the field",
    noSpecialCharacters: "Special characters are not allowed in the field @#$%&!_=*№}{\\]\\[^:,.().",
    allowedSymbolsPassword: "Only latin letters, numbers and special symbols @#$%&!_=№*}{\\]\\[^:,.() are allowed.",
} );
return $;
}));
