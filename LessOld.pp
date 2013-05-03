%skip       space               [ \t]
%skip       cr                  \r+
%skip       lf                  \n+

// String
%token      quote_              ["']                -> string
%token      string:string       [^"']+
%token      string:_quote       ["']                -> default


// Comment
%token      comment             //                  -> comment
%skip       comment:space       [ \t]+
%token      comment:string      [^\v]+
%token      comment:endl        \v+                 -> default



// Import
%token      importonce          @import-once        -> import
%token      import              @import             -> import
%token      import:space        [ \t]+
%token      import:string       [^ ;]+
%token      import:semicolon    ;                   -> default

// Variables
%token      arobase             @

// Rest
%token      string              [^;:{}\v]+
%token      brace_              {
%token      _brace              }
%token      semicolon           ;+
%token      colon               :
%token      tiret               -



// Rules
#root:
    (
        comment()
      | import()
      | getVariable()
      | setVariable()
      | mixin()
      | endl()
    )+

endl:
    ::cr:: | ::lf::

string:
    ::quote_:: <string> ::_quote::

name:
    <string>

value:
    (getVariable() | string() | <string>)

#identifier:
    (<arobase>)? <string> (::colon:: <string>)?

#comment:
    (
        ::comment:: (<string>)? ::endl::
    )

#import:
    (::import:: | ::importonce::) (::space:: <string>)+  ::semicolon::+

#getVariable:
    ::arobase:: (<arobase>)? ( <string> (::semicolon:: | endl())? ) | ::brace_:: <string> ::_brace::

#setVariable:
    ::arobase:: name() ::colon:: value()+ ::semicolon::+

#pair:
    name() ::colon:: value() (::semicolon:: | endl())?

#definition:
    <string> (::semicolon:: | endl())

#mixin:
    identifier() ::brace_::
    (
        import()
      | pair()
      | definition()
      | mixin()
      | comment()
      | endl()
      | setVariable()
    )+ ::_brace::