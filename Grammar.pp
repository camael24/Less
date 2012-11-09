%skip   space                   \s
%token _variable                @
%token variable_                :                       
%token semicolon                ;                       
%token id                       \w+
%token hash               		#

define_variable:
		::_variable:: <id> ::variable_:: ::hash:: <color> ::semicolon::
	
