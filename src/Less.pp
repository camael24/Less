%skip           space                   [ \t]+
%skip           endl                    \v+

// URL
%token          http                    http://

// COMMENT
%skip           slash                   //[^\v]*
%skip           block_comment           /\*(.|\n)*?\*/


// DECLARATION
%token          at_charset              @charset
%token          at_namespace            @namespace
%token          at_importonce           @import-once
%token          at_import               @import


// VARIABLE
%token          at                      @                   -> variable
%token          variable:at             @
%token          variable:name           [a-z0-9A-Z\-]+      -> default

// STRING
%token          quote_                  ("|')+              -> string
%token          string:string           [^"']+
%token          string:_quote           ("|')+              -> default

// FUNCTION
%token          parenthesis_            \(
%token          comma                   ,+
%token          _parenthesis            \)




// GENERIC
%token          brace_                  {+
%token          _brace                  }+
%token          colon                   :+
%token          child                   >+
%token          semicolon               ;+
%token          string                  [^'"(\);,{}:\v]+






// PRIMARY RULES
#root:
    (
       function()
      | declaration()
      | getVariable()
      | ruleset()
    )+

// PARSES RULES
string:
    (getVariable() | function()  | stringInQuote() | <http>? <string>)+

stringInQuote:
    ::quote_:: <string>? ::_quote::

#function:
    <string>? ::parenthesis_:: (::comma:: | <colon> |  function() | string())* ::_parenthesis:: ::semicolon::?

selector:
    (<comma> | <colon> | <child> | string())*
// LESS RULES

declaration:
    ( ::at_charset:: #charset | ::at_import:: #import | ::at_importonce:: #importonce | ::at_namespace:: #namespace) string() ::semicolon::

#getVariable:
    ::at:: (::at:: #getVariableRelative)? <name> (::colon:: (string() | <comma>)* #setVariable)?  ::semicolon::?

#rule:
    <string> ::colon:: (::comma:: | string())* ::semicolon::?

#ruleset:
    selector() ::brace_:: ( function() | rule() | ruleset() | getVariable())* ::_brace:: ::semicolon::?
