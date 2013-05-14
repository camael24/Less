%skip           space                   [ \t]+
%skip           endl                    \v+

// URL
%token          http                    http://

// COMMENT
%token          slash                   //[^\v]*
%token          block_comment           /\*(.|\n)*?\*/


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
%token          semicolon               ;+
%token          string                  [^"(\);,{}:\v]+






// PRIMARY RULES
#root:
    (
        comment()
      | function()
      | declaration()
      | getVariable()
      | ruleset()
    )+

// PARSES RULES
string:
    (getVariable() | function()  | stringInQuote() | <http>? <string>)+

stringInQuote:
    ::quote_:: <string>* ::_quote::

args:
    (::comma:: | <colon> | comment() | function() | string())*

#function:
    <string>? ::parenthesis_:: args()? ::_parenthesis:: (::semicolon:: #functionCall)?

selector:
    <colon>? string() (<comma> | <colon> | comment() | string())*
// LESS RULES

declaration:
    ( ::at_charset:: #charset | ::at_import:: #import | ::at_importonce:: #importonce | ::at_namespace:: #namespace) string() ::semicolon::

#getVariable:
    ::at:: (::at:: #getVariableRelative)? <name> (::colon:: (string() | <comma>)* #setVariable)?  comment()* ::semicolon::? comment()*

#rule:
    <string> ::colon:: (<comma> | comment() | string())* comment()* ::semicolon::? comment()*

#ruleset:
    selector() ::brace_:: (comment() | function() | rule() | ruleset() | getVariable() | <string> ::semicolon::?)* ::_brace:: (::semicolon:: | comment())*

comment:
    (<slash> #commentLine | <block_comment>+ #commentBlock)