%skip           space                   [ \t]+
%skip           endl                    \v+

// URL
%token          http                    http://

// COMMENT
%skip           slash                   //[^\v$]*
%skip           block_comment           /\*(.|\n)*?\*/

// KEYWORD
%token          when                    when
%token          at_fontface             @font-face
%token          at_media                @media

// DECLARATION
%token          at_charset              @charset
%token          at_namespace            @namespace
%token          at_importonce           @import-once
%token          at_import               @import

// VARIABLE
%token          at                      @                   -> variable
%token          variable:brace_         {
%token          variable:_brace         }
%token          variable:at             @
%token          variable:name           [a-z0-9A-Z\-_]+      -> default

// STRING
%token          stringInQuote           ("|'|`)(.*?)\1

// FUNCTION
%token          parenthesis_            \(
%token          comma                   ,+
%token          _parenthesis            \)

// GENERIC
%token          brace_                  {+
%token          _brace                  }+
%token          colon                   :+
%token          equal                   =+
%token          child                   >+
%token          semicolon               ;+
%token          string                  [^@'"\(\);,{}:\v]+

#root:
    (
        function()
      | declaration()
      | variable()
      | ruleset()
    )*

// PARSES RULES
string:
    (
        parens()
      | function()
      | variable()
      | <stringInQuote>
      | <http>? <string>
     )+

separator:
   <comma>

value:
    string() (separator() string())*

#function:
    <string> parens() ::semicolon::?

#parens:
    ::parenthesis_:: value()? ::_parenthesis::

selector:
    (
        <comma>
      | <colon>
      | <child>
      | <at_fontface>
      | <at_media>
      | <string>
    )+

declaration:
    (
        ::at_charset:: #charset
      | ::at_import:: #import
      | ::at_importonce:: #importonce
      | ::at_namespace:: #namespace
    ) string() when()? ::semicolon::

#variableRelative:
    ::at:: <name>

variableInterpolation:
    ::brace_:: <name> ::_brace::

variable:
    ::at:: ( variableRelative() | variableInterpolation() | rule() | <name> #variable )::semicolon::?

rule:
   (<string> #rule | <name> #set) (::colon:: | <equal>) value()

instruction:
    (
        declaration()
      | variable()
      | rule()
      | ruleset()
    )*
when:
    <when> parens()

#ruleset:
    selector() when()?  ::brace_::  instruction() (::semicolon:: instruction())* ::_brace:: ::semicolon::?
