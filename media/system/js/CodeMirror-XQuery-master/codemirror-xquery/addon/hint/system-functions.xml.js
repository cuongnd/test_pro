CodeMirror.XQuery.defineModule({
    "prefix": "fn",
    "prefixRequired": false,
    "namespace": "http://www.w3.org/2005/xpath-functions",
    "functions": [{
        "name": "abs",
        "params": [{"name": "arg", "as": "numeric?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the absolute value of <code>$arg</code> . If <code>$arg</code> is negative returns <code>-$arg</code> otherwisereturns <code>$arg</code> . If type of <code>$arg</code> is one ofthe four numeric types <code>xs:float</code> , <code>xs:double</code> , <code>xs:decimal</code> or <code>xs:integer</code> the type of the result is the same as thetype of <code>$arg</code> . If the type of <code>$arg</code> is atype derived from one of the numeric types, the result is aninstance of the base numeric type.</p> "
    }, {
        "name": "adjust-date-to-timezone",
        "params": [{"name": "arg", "as": "xs:date?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Adjusts an <code>xs:date</code> value to a specifictimezone, or to no timezone at all. If <code>$timezone</code> isthe empty sequence, returns an <code>xs:date</code> without atimezone. Otherwise, returns an <code>xs:date</code> with atimezone. For purposes of timezone adjustment, an <code>xs:date</code> is treated as an <code>xs:dateTime</code> withtime <code>00:00:00</code> .</p> "
    }, {
        "name": "adjust-date-to-timezone",
        "params": [{"name": "arg", "as": "xs:date?"}, {"name": "timezone", "as": "xs:dayTimeDuration?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Adjusts an <code>xs:date</code> value to a specifictimezone, or to no timezone at all. If <code>$timezone</code> isthe empty sequence, returns an <code>xs:date</code> without atimezone. Otherwise, returns an <code>xs:date</code> with atimezone. For purposes of timezone adjustment, an <code>xs:date</code> is treated as an <code>xs:dateTime</code> withtime <code>00:00:00</code> .</p> "
    }, {
        "name": "adjust-dateTime-to-timezone",
        "params": [{"name": "arg", "as": "xs:dateTime?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Adjusts an <code>xs:dateTime</code> value to a specifictimezone, or to no timezone at all. If <code>$timezone</code> isthe empty sequence, returns an <code>xs:dateTime</code> without atimezone. Otherwise, returns an <code>xs:dateTime</code> with atimezone.</p> "
    }, {
        "name": "adjust-dateTime-to-timezone",
        "params": [{"name": "arg", "as": "xs:dateTime?"}, {"name": "timezone", "as": "xs:dayTimeDuration?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Adjusts an <code>xs:dateTime</code> value to a specifictimezone, or to no timezone at all. If <code>$timezone</code> isthe empty sequence, returns an <code>xs:dateTime</code> without atimezone. Otherwise, returns an <code>xs:dateTime</code> with atimezone.</p> "
    }, {
        "name": "adjust-time-to-timezone",
        "params": [{"name": "arg", "as": "xs:time?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Adjusts an <code>xs:time</code> value to a specifictimezone, or to no timezone at all. If <code>$timezone</code> isthe empty sequence, returns an <code>xs:time</code> without atimezone. Otherwise, returns an <code>xs:time</code> with atimezone.</p> "
    }, {
        "name": "adjust-time-to-timezone",
        "params": [{"name": "arg", "as": "xs:time?"}, {"name": "timezone", "as": "xs:dayTimeDuration?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Adjusts an <code>xs:time</code> value to a specifictimezone, or to no timezone at all. If <code>$timezone</code> isthe empty sequence, returns an <code>xs:time</code> without atimezone. Otherwise, returns an <code>xs:time</code> with atimezone.</p> "
    }, {
        "name": "avg",
        "params": [{"name": "arg", "as": "xs:anyAtomicType*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the average of the values in the input sequence <code>$arg</code> , that is, the sum of the values divided by thenumber of values.</p> "
    }, {
        "name": "base-uri",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of the base-uri URI property for <code>$arg</code> as defined by the accessor function <code>dm:base-uri()</code> for that kind of node in <a>Section 5.2base-uri Accessor</a>  <sup> <small>DM</small> </sup> . If <code>$arg</code> is not specified, the behavior is identical tocalling the function with the context item ( <code>.</code> ) asargument. The following errors may be raised: if the context itemis undefined [ <a>err:XPDY0002</a> ] <sup> <small>XP</small> </sup> ;if the context item is not a node [ <a>err:XPTY0004</a> ] <sup> <small>XP</small> </sup> .</p> "
    }, {
        "name": "base-uri",
        "params": [{"name": "arg", "as": "node()?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of the base-uri URI property for <code>$arg</code> as defined by the accessor function <code>dm:base-uri()</code> for that kind of node in <a>Section 5.2base-uri Accessor</a>  <sup> <small>DM</small> </sup> . If <code>$arg</code> is not specified, the behavior is identical tocalling the function with the context item ( <code>.</code> ) asargument. The following errors may be raised: if the context itemis undefined [ <a>err:XPDY0002</a> ] <sup> <small>XP</small> </sup> ;if the context item is not a node [ <a>err:XPTY0004</a> ] <sup> <small>XP</small> </sup> .</p> "
    }, {
        "name": "boolean",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Computes the effective boolean value of the sequence <code>$arg</code> . See <a>Section 2.4.3 EffectiveBoolean Value</a>  <sup> <small>XP</small> </sup> </p> "
    }, {
        "name": "ceiling",
        "params": [{"name": "arg", "as": "numeric?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the smallest (closest to negative infinity)number with no fractional part that is not less than the value of <code>$arg</code> . If type of <code>$arg</code> is one of the fournumeric types <code>xs:float</code> , <code>xs:double</code> , <code>xs:decimal</code> or <code>xs:integer</code> the type of theresult is the same as the type of <code>$arg</code> . If the type of <code>$arg</code> is a type derived from one of the numeric types,the result is an instance of the base numeric type.</p> "
    }, {
        "name": "codepoint-equal",
        "params": [{"name": "comparand1", "as": "xs:string?"}, {"name": "comparand2", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns <code>true</code> or <code>false</code> depending on whether the value of <code>$comparand1</code> is equalto the value of <code>$comparand2</code> , according to the Unicodecode point collation( <code>http://www.w3.org/2005/xpath-functions/collation/codepoint</code> ).</p> "
    }, {
        "name": "codepoints-to-string",
        "params": [{"name": "arg", "as": "xs:integer*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Creates an <code>xs:string</code> from a sequence of <a>[The Unicode Standard]</a> code points. Returnsthe zero-length string if <code>$arg</code> is the empty sequence.If any of the code points in <code>$arg</code> is not a legal XMLcharacter, an error is raised [ <a>err:FOCH0001</a> ].</p> "
    }, {
        "name": "collection",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> This function takes an <code>xs:string</code> asargument and returns a sequence of nodes obtained by interpreting <code>$arg</code> as an <code>xs:anyURI</code> and resolving itaccording to the mapping specified in <b>Available collections</b> described in <a>Section C.2 Dynamic ContextComponents</a>  <sup> <small>XP</small> </sup> . If <b>Availablecollections</b> provides a mapping from this string to a sequenceof nodes, the function returns that sequence. If <b>Availablecollections</b> maps the string to an empty sequence, then thefunction returns an empty sequence. If <b>Available collections</b> provides no mapping for the string, an error is raised [ <a>err:FODC0004</a> ]. If <code>$arg</code> is not specified, the function returns thesequence of the nodes in the default collection in the dynamiccontext. See <a>Section C.2 Dynamic ContextComponents</a>  <sup> <small>XP</small> </sup> . If the value of thedefault collection is undefined an error is raised [ <a>err:FODC0002</a> ].</p> "
    }, {
        "name": "collection",
        "params": [{"name": "arg", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> This function takes an <code>xs:string</code> asargument and returns a sequence of nodes obtained by interpreting <code>$arg</code> as an <code>xs:anyURI</code> and resolving itaccording to the mapping specified in <b>Available collections</b> described in <a>Section C.2 Dynamic ContextComponents</a>  <sup> <small>XP</small> </sup> . If <b>Availablecollections</b> provides a mapping from this string to a sequenceof nodes, the function returns that sequence. If <b>Availablecollections</b> maps the string to an empty sequence, then thefunction returns an empty sequence. If <b>Available collections</b> provides no mapping for the string, an error is raised [ <a>err:FODC0004</a> ]. If <code>$arg</code> is not specified, the function returns thesequence of the nodes in the default collection in the dynamiccontext. See <a>Section C.2 Dynamic ContextComponents</a>  <sup> <small>XP</small> </sup> . If the value of thedefault collection is undefined an error is raised [ <a>err:FODC0002</a> ].</p> "
    }, {
        "name": "compare",
        "params": [{"name": "comparand1", "as": "xs:string?"}, {"name": "comparand2", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns -1, 0, or 1, depending on whether the value ofthe <code>$comparand1</code> is respectively less than, equal to,or greater than the value of <code>$comparand2</code> , according tothe rules of the collation that is used.</p> "
    }, {
        "name": "compare",
        "params": [{"name": "comparand1", "as": "xs:string?"}, {
            "name": "comparand2",
            "as": "xs:string?"
        }, {"name": "collation", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns -1, 0, or 1, depending on whether the value ofthe <code>$comparand1</code> is respectively less than, equal to,or greater than the value of <code>$comparand2</code> , according tothe rules of the collation that is used.</p> "
    }, {
        "name": "concat",
        "params": [{"name": "arg1", "as": "xs:anyAtomicType?"}, {"name": "arg2", "as": "xs:anyAtomicType?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Accepts two or more <code>xs:anyAtomicType</code> arguments and casts them to <code>xs:string</code> . Returns the <code>xs:string</code> that is the concatenation of the values ofits arguments after conversion. If any of the arguments is theempty sequence, the argument is treated as the zero-lengthstring.</p> "
    }, {
        "name": "contains",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:boolean</code> indicating whetheror not the value of <code>$arg1</code> contains (at the beginning,at the end, or anywhere within) at least one sequence of collationunits that provides a minimal match to the collation units in thevalue of <code>$arg2</code> , according to the collation that isused.</p> "
    }, {
        "name": "contains",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}, {
            "name": "collation",
            "as": "xs:string"
        }],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:boolean</code> indicating whetheror not the value of <code>$arg1</code> contains (at the beginning,at the end, or anywhere within) at least one sequence of collationunits that provides a minimal match to the collation units in thevalue of <code>$arg2</code> , according to the collation that isused.</p> "
    }, {
        "name": "count",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the number of items in the value of <code>$arg</code> .</p> "
    }, {
        "name": "current-date",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns <code>xs:date(fn:current-dateTime())</code> .This is an <code>xs:date</code> (with timezone) that is current atsome time during the evaluation of a query or transformation inwhich <code>fn:current-date()</code> is executed. This function is <a> <span>·</span> stable <span>·</span> </a> . The preciseinstant during the query or transformation represented by the valueof <code>fn:current-date()</code> is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "current-dateTime",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the current dateTime (with timezone) from thedynamic context. (See <a>Section C.2 Dynamic ContextComponents</a>  <sup> <small>XP</small> </sup> .) This is an <code>xs:dateTime</code> that is current at some time during theevaluation of a query or transformation in which <code>fn:current-dateTime()</code> is executed. This function is <a> <span>·</span> stable <span>·</span> </a> . The preciseinstant during the query or transformation represented by the valueof <code>fn:current-dateTime()</code> is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "current-time",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns <code>xs:time(fn:current-dateTime())</code> .This is an <code>xs:time</code> (with timezone) that is current atsome time during the evaluation of a query or transformation inwhich <code>fn:current-time()</code> is executed. This function is <a> <span>·</span> stable <span>·</span> </a> . The preciseinstant during the query or transformation represented by the valueof <code>fn:current-time()</code> is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "data",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br>  <code>fn:data</code> takes a sequence of items andreturns a sequence of atomic values.</p> "
    }, {
        "name": "dateTime",
        "params": [{"name": "arg1", "as": "xs:date?"}, {"name": "arg2", "as": "xs:time?"}]
    }, {
        "name": "day-from-date",
        "params": [{"name": "arg", "as": "xs:date?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> between 1 and 31,both inclusive, representing the day component in the localizedvalue of <code>$arg</code> .</p> "
    }, {
        "name": "day-from-dateTime",
        "params": [{"name": "arg", "as": "xs:dateTime?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> between 1 and 31,both inclusive, representing the day component in the localizedvalue of <code>$arg</code> .</p> "
    }, {
        "name": "days-from-duration",
        "params": [{"name": "arg", "as": "xs:duration?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> representing thedays component in the value of <code>$arg</code> . The result isobtained by casting <code>$arg</code> to an <code>xs:dayTimeDuration</code> (see <a> <b>17.1.4 Casting to durationtypes</b> </a> ) and then computing the days component as describedin <a> <b>10.3.2.3 Canonicalrepresentation</b> </a> .</p> "
    }, {
        "name": "deep-equal",
        "params": [{"name": "parameter1", "as": "item()*"}, {"name": "parameter2", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> This function assesses whether two sequences aredeep-equal to each other. To be deep-equal, they must contain itemsthat are pairwise deep-equal; and for two items to be deep-equal,they must either be atomic values that compare equal, or nodes ofthe same kind, with the same name, whose children are deep-equal.This is defined in more detail below. The <code>$collation</code> argument identifies a collation which is used at all levels ofrecursion when strings are compared (but not when names arecompared), according to the rules in <a> <b>7.3.1Collations</b> </a> .</p> "
    }, {
        "name": "deep-equal",
        "params": [{"name": "parameter1", "as": "item()*"}, {
            "name": "parameter2",
            "as": "item()*"
        }, {"name": "collation", "as": "string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> This function assesses whether two sequences aredeep-equal to each other. To be deep-equal, they must contain itemsthat are pairwise deep-equal; and for two items to be deep-equal,they must either be atomic values that compare equal, or nodes ofthe same kind, with the same name, whose children are deep-equal.This is defined in more detail below. The <code>$collation</code> argument identifies a collation which is used at all levels ofrecursion when strings are compared (but not when names arecompared), according to the rules in <a> <b>7.3.1Collations</b> </a> .</p> "
    }, {
        "name": "default-collation",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of the default collation propertyfrom the static context. Components of the static context arediscussed in <a>SectionC.1 Static Context Components</a>  <sup> <small>XP</small> </sup> .</p> "
    }, {
        "name": "distinct-values",
        "params": [{"name": "arg", "as": "xs:anyAtomicType*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the sequence that results from removing from <code>$arg</code> all but one of a set of values that are <code>eq</code> to one other. Values of type <code>xs:untypedAtomic</code> are compared as if they were of type <code>xs:string</code> . Values that cannot be compared, i.e. the <code>eq</code> operator is not defined for their types, areconsidered to be distinct. The order in which the sequence ofvalues is returned is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "distinct-values",
        "params": [{"name": "arg", "as": "xs:anyAtomicType*"}, {"name": "collation", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the sequence that results from removing from <code>$arg</code> all but one of a set of values that are <code>eq</code> to one other. Values of type <code>xs:untypedAtomic</code> are compared as if they were of type <code>xs:string</code> . Values that cannot be compared, i.e. the <code>eq</code> operator is not defined for their types, areconsidered to be distinct. The order in which the sequence ofvalues is returned is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "doc",
        "params": [{"name": "uri", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Retrieves a document using an <code>xs:anyURI</code> ,which may include a fragment identifier, supplied as an <code>xs:string</code> . If <code>$uri</code> is not a valid <code>xs:anyURI</code> , an error is raised [ <a>err:FODC0005</a> ]. If it is a relative URIReference, it is resolved relative to the value of the base URIproperty from the static context. The resulting absolute URIReference is promoted to an <code>xs:string</code> . If the <b>Available documents</b> discussed in <a>Section 2.1.2 DynamicContext</a>  <sup> <small>XP</small> </sup> provides a mapping fromthis string to a document node, the function returns that documentnode. If the <b>Available documents</b> provides no mapping for thestring, an error is raised [ <a>err:FODC0005</a> ].</p> "
    }, {
        "name": "doc-available",
        "params": [{"name": "uri", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> If <a> <code>fn:doc($uri)</code> </a> returns a document node, this function returns <code>true</code> .If <code>$uri</code> is not a valid <code>xs:anyURI</code> , anerror is raised [ <a>err:FODC0005</a> ]. Otherwise, this function returns <code>false</code> .</p> "
    }, {
        "name": "document-uri",
        "params": [{"name": "arg", "as": "node()?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of the document-uri property for <code>$arg</code> as defined by the <code>dm:document-uri</code> accessor function defined in <a>Section6.1.2 Accessors</a>  <sup> <small>DM</small> </sup> .</p> "
    }, {
        "name": "empty",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> If the value of <code>$arg</code> is the emptysequence, the function returns <code>true</code> ; otherwise, thefunction returns <code>false</code> .</p> "
    }, {
        "name": "encode-for-uri",
        "params": [{"name": "uri-part", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> This function encodes reserved characters in an <code>xs:string</code> that is intended to be used in the pathsegment of a URI. It is invertible but not idempotent. Thisfunction applies the URI escaping rules defined in section 2 of <a>[RFC 3986]</a> to the <code>xs:string</code> supplied as <code>$uri-part</code> . The effect of the function isto escape reserved characters. Each such character in the string isreplaced with its percent-encoded form as described in <a>[RFC 3986]</a> .</p> "
    }, {
        "name": "ends-with",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:boolean</code> indicating whetheror not the value of <code>$arg1</code> ends with a sequence ofcollation units that provides a minimal match to the collationunits of <code>$arg2</code> according to the collation that isused.</p> "
    }, {
        "name": "ends-with",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}, {
            "name": "collation",
            "as": "xs:string"
        }],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:boolean</code> indicating whetheror not the value of <code>$arg1</code> ends with a sequence ofcollation units that provides a minimal match to the collationunits of <code>$arg2</code> according to the collation that isused.</p> "
    }, {
        "name": "error",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> The <code>fn:error</code> function raises an error.While this function never returns a value, an error is returned tothe external processing environment as an <code>xs:anyURI</code> oran <code>xs:QName</code> . The error <code>xs:anyURI</code> isderived from the error <code>xs:QName</code> . An error <code>xs:QName</code> with namespace URI NS and local part LP willbe returned as the <code>xs:anyURI</code> NS#LP. The method bywhich the <code>xs:anyURI</code> or <code>xs:QName</code> isreturned to the external processing environment is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "error",
        "params": [{"name": "error", "as": "xs:QName"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The <code>fn:error</code> function raises an error.While this function never returns a value, an error is returned tothe external processing environment as an <code>xs:anyURI</code> oran <code>xs:QName</code> . The error <code>xs:anyURI</code> isderived from the error <code>xs:QName</code> . An error <code>xs:QName</code> with namespace URI NS and local part LP willbe returned as the <code>xs:anyURI</code> NS#LP. The method bywhich the <code>xs:anyURI</code> or <code>xs:QName</code> isreturned to the external processing environment is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "error",
        "params": [{"name": "error", "as": "xs:QName?"}, {"name": "description", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The <code>fn:error</code> function raises an error.While this function never returns a value, an error is returned tothe external processing environment as an <code>xs:anyURI</code> oran <code>xs:QName</code> . The error <code>xs:anyURI</code> isderived from the error <code>xs:QName</code> . An error <code>xs:QName</code> with namespace URI NS and local part LP willbe returned as the <code>xs:anyURI</code> NS#LP. The method bywhich the <code>xs:anyURI</code> or <code>xs:QName</code> isreturned to the external processing environment is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "error",
        "params": [{"name": "error", "as": "xs:QName?"}, {
            "name": "description",
            "as": "xs:string"
        }, {"name": "error-object", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The <code>fn:error</code> function raises an error.While this function never returns a value, an error is returned tothe external processing environment as an <code>xs:anyURI</code> oran <code>xs:QName</code> . The error <code>xs:anyURI</code> isderived from the error <code>xs:QName</code> . An error <code>xs:QName</code> with namespace URI NS and local part LP willbe returned as the <code>xs:anyURI</code> NS#LP. The method bywhich the <code>xs:anyURI</code> or <code>xs:QName</code> isreturned to the external processing environment is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "escape-html-uri",
        "params": [{"name": "uri", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> This function escapes all characters except printablecharacters of the US-ASCII coded character set, specifically theoctets ranging from 32 to 126 (decimal). The effect of the functionis to escape a URI in the manner html user agents handle attributevalues that expect URIs. Each character in <code>$uri</code> to beescaped is replaced by an escape sequence, which is formed byencoding the character as a sequence of octets in UTF-8, and thenrepresenting each of these octets in the form %HH, where HH is thehexadecimal representation of the octet. This function must alwaysgenerate hexadecimal values using the upper-case letters A-F.</p> "
    }, {
        "name": "exactly-one",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns <code>$arg</code> if it contains exactly oneitem. Otherwise, raises an error [ <a>err:FORG0005</a> ].</p> "
    }, {
        "name": "exists",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> If the value of <code>$arg</code> is not the emptysequence, the function returns <code>true</code> ; otherwise, thefunction returns <code>false</code> .</p> "
    }, {
        "name": "false",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the <code>xs:boolean</code> value <code>false</code> . Equivalent to <code>xs:boolean(\"0\")</code> .</p> "
    }, {
        "name": "floor",
        "params": [{"name": "arg", "as": "numeric?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the largest (closest to positive infinity)number with no fractional part that is not greater than the valueof <code>$arg</code> . If type of <code>$arg</code> is one of thefour numeric types <code>xs:float</code> , <code>xs:double</code> , <code>xs:decimal</code> or <code>xs:integer</code> the type of theresult is the same as the type of <code>$arg</code> . If the type of <code>$arg</code> is a type derived from one of the numeric types,the result is an instance of the base numeric type.</p> "
    }, {
        "name": "hours-from-dateTime",
        "params": [{"name": "arg", "as": "xs:dateTime?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> between 0 and 23,both inclusive, representing the hours component in the localizedvalue of <code>$arg</code> .</p> "
    }, {
        "name": "hours-from-duration",
        "params": [{"name": "arg", "as": "xs:duration?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> representing thehours component in the value of <code>$arg</code> . The result isobtained by casting <code>$arg</code> to an <code>xs:dayTimeDuration</code> (see <a> <b>17.1.4 Casting to durationtypes</b> </a> ) and then computing the hours component as describedin <a> <b>10.3.2.3 Canonicalrepresentation</b> </a> .</p> "
    }, {
        "name": "hours-from-time",
        "params": [{"name": "arg", "as": "xs:time?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> between 0 and 23,both inclusive, representing the value of the hours component inthe localized value of <code>$arg</code> .</p> "
    }, {
        "name": "id",
        "params": [{"name": "arg", "as": "xs:string*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the sequence of element nodes that have an <code>ID</code> value matching the value of one or more of the <code>IDREF</code> values supplied in <code>$arg</code> .</p> "
    }, {
        "name": "id",
        "params": [{"name": "arg", "as": "xs:string*"}, {"name": "node", "as": "node()"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the sequence of element nodes that have an <code>ID</code> value matching the value of one or more of the <code>IDREF</code> values supplied in <code>$arg</code> .</p> "
    }, {
        "name": "idref",
        "params": [{"name": "arg", "as": "xs:string*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the sequence of element or attribute nodes withan <code>IDREF</code> value matching the value of one or more ofthe <code>ID</code> values supplied in <code>$arg</code> .</p> "
    }, {
        "name": "idref",
        "params": [{"name": "arg", "as": "xs:string*"}, {"name": "node", "as": "node()"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the sequence of element or attribute nodes withan <code>IDREF</code> value matching the value of one or more ofthe <code>ID</code> values supplied in <code>$arg</code> .</p> "
    }, {
        "name": "implicit-timezone",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of the implicit timezone propertyfrom the dynamic context. Components of the dynamic context arediscussed in <a>Section C.2 Dynamic ContextComponents</a>  <sup> <small>XP</small> </sup> .</p> "
    }, {
        "name": "in-scope-prefixes",
        "params": [{"name": "element", "as": "element()"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the prefixes of the in-scope namespaces for <code>$element</code> . For namespaces that have a prefix, itreturns the prefix as an <code>xs:NCName</code> . For the defaultnamespace, which has no prefix, it returns the zero-lengthstring.</p> "
    }, {
        "name": "index-of",
        "params": [{"name": "seqParam", "as": "xs:anyAtomicType*"}, {"name": "srchParam", "as": "xs:anyAtomicType"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns a sequence of positive integers giving thepositions within the sequence <code>$seqParam</code> of items thatare equal to <code>$srchParam</code> .</p> "
    }, {
        "name": "index-of",
        "params": [{"name": "seqParam", "as": "xs:anyAtomicType*"}, {
            "name": "srchParam",
            "as": "xs:anyAtomicType"
        }, {"name": "collation", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns a sequence of positive integers giving thepositions within the sequence <code>$seqParam</code> of items thatare equal to <code>$srchParam</code> .</p> "
    }, {
        "name": "insert-before",
        "params": [{"name": "target", "as": "item()*"}, {"name": "position", "as": "xs:integer"}, {
            "name": "inserts",
            "as": "item()*"
        }],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns a new sequence constructed from the value of <code>$target</code> with the value of <code>$inserts</code> inserted at the position specified by the value of <code>$position</code> . (The value of <code>$target</code> is notaffected by the sequence construction.)</p> "
    }, {
        "name": "iri-to-uri",
        "params": [{"name": "iri", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> This function converts an <code>xs:string</code> containing an IRI into a URI according to the rules spelled out inSection 3.1 of <a>[RFC 3987]</a> . It is idempotentbut not invertible.</p> "
    }, {
        "name": "lang",
        "params": [{"name": "testlang", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> This function tests whether the language of <code>$node</code> , or the context item if the second argument isomitted, as specified by <code>xml:lang</code> attributes is thesame as, or is a sublanguage of, the language specified by <code>$testlang</code> . The behavior of the function if the secondargument is omitted is exactly the same as if the context item( <code>.</code> ) had been passed as the second argument. Thelanguage of the argument node, or the context item if the secondargument is omitted, is determined by the value of the <code>xml:lang</code> attribute on the node, or, if the node has nosuch attribute, by the value of the <code>xml:lang</code> attributeon the nearest ancestor of the node that has an <code>xml:lang</code> attribute. If there is no such ancestor, thenthe function returns <code>false</code> </p> "
    }, {
        "name": "lang",
        "params": [{"name": "testlang", "as": "xs:string?"}, {"name": "node", "as": "node()"}],
        "doc": " <p> <b>Summary:</b>  <br></br> This function tests whether the language of <code>$node</code> , or the context item if the second argument isomitted, as specified by <code>xml:lang</code> attributes is thesame as, or is a sublanguage of, the language specified by <code>$testlang</code> . The behavior of the function if the secondargument is omitted is exactly the same as if the context item( <code>.</code> ) had been passed as the second argument. Thelanguage of the argument node, or the context item if the secondargument is omitted, is determined by the value of the <code>xml:lang</code> attribute on the node, or, if the node has nosuch attribute, by the value of the <code>xml:lang</code> attributeon the nearest ancestor of the node that has an <code>xml:lang</code> attribute. If there is no such ancestor, thenthe function returns <code>false</code> </p> "
    }, {
        "name": "last",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the context size from the dynamic context. (See <a>Section C.2 Dynamic ContextComponents</a>  <sup> <small>XP</small> </sup> .) If the context item isundefined, an error is raised: [ <a>err:XPDY0002</a> ] <sup> <small>XP</small> </sup> .</p> "
    }, {
        "name": "local-name",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the local part of the name of <code>$arg</code> as an <code>xs:string</code> that will either be the zero-lengthstring or will have the lexical form of an <code>xs:NCName</code> .</p> "
    }, {
        "name": "local-name",
        "params": [{"name": "arg", "as": "node()?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the local part of the name of <code>$arg</code> as an <code>xs:string</code> that will either be the zero-lengthstring or will have the lexical form of an <code>xs:NCName</code> .</p> "
    }, {
        "name": "local-name-from-QName",
        "params": [{"name": "arg", "as": "xs:QName?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:NCName</code> representing thelocal part of <code>$arg</code> . If <code>$arg</code> is the emptysequence, returns the empty sequence.</p> "
    }, {
        "name": "lower-case",
        "params": [{"name": "arg", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of <code>$arg</code> aftertranslating every character to its lower-case correspondent asdefined in the appropriate case mappings section in the Unicodestandard <a>[The Unicode Standard]</a> . Forversions of Unicode beginning with the 2.1.8 update, onlylocale-insensitive case mappings should be applied. Beginning withversion 3.2.0 (and likely future versions) of Unicode, precisemappings are described in default case operations, which are fullcase mappings in the absence of tailoring for particular languagesand environments. Every upper-case character that does not have alower-case correspondent, as well as every lower-case character, isincluded in the returned value in its original form.</p> "
    }, {
        "name": "matches",
        "params": [{"name": "input", "as": "xs:string?"}, {"name": "pattern", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The function returns <code>true</code> if <code>$input</code> matches the regular expression supplied as <code>$pattern</code> as influenced by the value of <code>$flags</code> , if present; otherwise, it returns <code>false</code> .</p> "
    }, {
        "name": "matches",
        "params": [{"name": "input", "as": "xs:string?"}, {"name": "pattern", "as": "xs:string"}, {
            "name": "flags",
            "as": "xs:string"
        }],
        "doc": " <p> <b>Summary:</b>  <br></br> The function returns <code>true</code> if <code>$input</code> matches the regular expression supplied as <code>$pattern</code> as influenced by the value of <code>$flags</code> , if present; otherwise, it returns <code>false</code> .</p> "
    }, {
        "name": "max",
        "params": [{"name": "arg", "as": "xs:anyAtomicType*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Selects an item from the input sequence <code>$arg</code> whose value is greater than or equal to the valueof every other item in the input sequence. If there are two or moresuch items, then the specific item whose value is returned is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "max",
        "params": [{"name": "arg", "as": "xs:anyAtomicType*"}, {"name": "collation", "as": "string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Selects an item from the input sequence <code>$arg</code> whose value is greater than or equal to the valueof every other item in the input sequence. If there are two or moresuch items, then the specific item whose value is returned is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "min",
        "params": [{"name": "arg", "as": "xs:anyAtomicType*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> selects an item from the input sequence <code>$arg</code> whose value is less than or equal to the value ofevery other item in the input sequence. If there are two or moresuch items, then the specific item whose value is returned is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "min",
        "params": [{"name": "arg", "as": "xs:anyAtomicType*"}, {"name": "collation", "as": "string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> selects an item from the input sequence <code>$arg</code> whose value is less than or equal to the value ofevery other item in the input sequence. If there are two or moresuch items, then the specific item whose value is returned is <a> <span>·</span> implementation dependent <span>·</span> </a> .</p> "
    }, {
        "name": "minutes-from-dateTime",
        "params": [{"name": "arg", "as": "xs:dateTime?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> value between 0 and59, both inclusive, representing the minute component in thelocalized value of <code>$arg</code> .</p> "
    }, {
        "name": "minutes-from-duration",
        "params": [{"name": "arg", "as": "xs:duration?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> representing theminutes component in the value of <code>$arg</code> . The result isobtained by casting <code>$arg</code> to an <code>xs:dayTimeDuration</code> (see <a> <b>17.1.4 Casting to durationtypes</b> </a> ) and then computing the minutes component asdescribed in <a> <b>10.3.2.3Canonical representation</b> </a> .</p> "
    }, {
        "name": "minutes-from-time",
        "params": [{"name": "arg", "as": "xs:time?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> value between 0 and59, both inclusive, representing the value of the minutes componentin the localized value of <code>$arg</code> .</p> "
    }, {
        "name": "month-from-date",
        "params": [{"name": "arg", "as": "xs:date?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> between 1 and 12,both inclusive, representing the month component in the localizedvalue of <code>$arg</code> .</p> "
    }, {
        "name": "month-from-dateTime",
        "params": [{"name": "arg", "as": "xs:dateTime?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> between 1 and 12,both inclusive, representing the month component in the localizedvalue of <code>$arg</code> .</p> "
    }, {
        "name": "months-from-duration",
        "params": [{"name": "arg", "as": "xs:duration?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> representing themonths component in the value of <code>$arg</code> . The result isobtained by casting <code>$arg</code> to an <code>xs:yearMonthDuration</code> (see <a> <b>17.1.4 Casting to durationtypes</b> </a> ) and then computing the months component as describedin <a> <b>10.3.1.3 Canonicalrepresentation</b> </a> .</p> "
    }, {
        "name": "name",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the name of a node, as an <code>xs:string</code> that is either the zero-length string, orhas the lexical form of an <code>xs:QName</code> .</p> "
    }, {
        "name": "name",
        "params": [{"name": "arg", "as": "node()?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the name of a node, as an <code>xs:string</code> that is either the zero-length string, orhas the lexical form of an <code>xs:QName</code> .</p> "
    }, {
        "name": "namespace-uri",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the namespace URI of the <code>xs:QName</code> of <code>$arg</code> .</p> "
    }, {
        "name": "namespace-uri",
        "params": [{"name": "arg", "as": "node()?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the namespace URI of the <code>xs:QName</code> of <code>$arg</code> .</p> "
    }, {
        "name": "namespace-uri-for-prefix",
        "params": [{"name": "prefix", "as": "xs:string?"}, {"name": "element", "as": "element()"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the namespace URI of one of the in-scopenamespaces for <code>$element</code> , identified by its namespaceprefix.</p> "
    }, {
        "name": "namespace-uri-from-QName",
        "params": [{"name": "arg", "as": "xs:QName?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the namespace URI for <code>$arg</code> as an <code>xs:string</code> . If <code>$arg</code> is the empty sequence,the empty sequence is returned. If <code>$arg</code> is in nonamespace, the zero-length string is returned.</p> "
    }, {
        "name": "nilled",
        "params": [{"name": "arg", "as": "node()?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:boolean</code> indicating whetherthe argument node is \"nilled\". If the argument is not an elementnode, returns the empty sequence. If the argument is the emptysequence, returns the empty sequence.</p> "
    }, {
        "name": "node-name",
        "params": [{"name": "arg", "as": "node()?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an expanded-QName for node kinds that can havenames. For other kinds of nodes it returns the empty sequence. If <code>$arg</code> is the empty sequence, the empty sequence isreturned.</p> "
    }, {
        "name": "normalize-space",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of <code>$arg</code> with whitespacenormalized by stripping leading and trailing whitespace andreplacing sequences of one or more than one whitespace characterwith a single space, <code>#x20</code> .</p> "
    }, {
        "name": "normalize-space",
        "params": [{"name": "arg", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of <code>$arg</code> with whitespacenormalized by stripping leading and trailing whitespace andreplacing sequences of one or more than one whitespace characterwith a single space, <code>#x20</code> .</p> "
    }, {
        "name": "normalize-unicode",
        "params": [{"name": "arg", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of <code>$arg</code> normalizedaccording to the normalization criteria for a normalization formidentified by the value of <code>$normalizationForm</code> . Theeffective value of the <code>$normalizationForm</code> is computedby removing leading and trailing blanks, if present, and convertingto upper case.</p> "
    }, {
        "name": "normalize-unicode",
        "params": [{"name": "arg", "as": "xs:string?"}, {"name": "normalizationForm", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of <code>$arg</code> normalizedaccording to the normalization criteria for a normalization formidentified by the value of <code>$normalizationForm</code> . Theeffective value of the <code>$normalizationForm</code> is computedby removing leading and trailing blanks, if present, and convertingto upper case.</p> "
    }, {
        "name": "not",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br>  <code>$arg</code> is first reduced to an effectiveboolean value by applying the <a> <code>fn:boolean()</code> </a> function. Returns <code>true</code> if the effective boolean value is <code>false</code> , and <code>false</code> if the effective booleanvalue is <code>true</code> .</p> "
    }, {
        "name": "number",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value indicated by <code>$arg</code> or, if <code>$arg</code> is not specified, the context item afteratomization, converted to an <code>xs:double</code> </p> "
    }, {
        "name": "number",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value indicated by <code>$arg</code> or, if <code>$arg</code> is not specified, the context item afteratomization, converted to an <code>xs:double</code> </p> "
    }, {
        "name": "one-or-more",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns <code>$arg</code> if it contains one or moreitems. Otherwise, raises an error [ <a>err:FORG0004</a> ].</p> "
    }, {
        "name": "position",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the context position from the dynamic context.(See <a>Section C.2 Dynamic ContextComponents</a>  <sup> <small>XP</small> </sup> .) If the context item isundefined, an error is raised: [ <a>err:XPDY0002</a> ] <sup> <small>XP</small> </sup> .</p> "
    }, {
        "name": "prefix-from-QName",
        "params": [{"name": "arg", "as": "xs:QName?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:NCName</code> representing theprefix of <code>$arg</code> . The empty sequence is returned if <code>$arg</code> is the empty sequence or if the value of <code>$arg</code> contains no prefix.</p> "
    }, {
        "name": "QName",
        "params": [{"name": "paramURI", "as": "xs:string?"}, {"name": "paramQName", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:QName</code> with the namespace URIgiven in <code>$paramURI</code> . If <code>$paramURI</code> is thezero-length string or the empty sequence, it represents \"nonamespace\"; in this case, if the value of <code>$paramQName</code> contains a colon ( <code>:</code> ), an error is raised [ <a>err:FOCA0002</a> ]. The prefix(or absence of a prefix) in <code>$paramQName</code> is retained inthe returned <code>xs:QName</code> value. The local name in theresult is taken from the local part of <code>$paramQName</code> .</p> "
    }, {
        "name": "remove",
        "params": [{"name": "target", "as": "item()*"}, {"name": "position", "as": "xs:integer"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns a new sequence constructed from the value of <code>$target</code> with the item at the position specified by thevalue of <code>$position</code> removed.</p> "
    }, {
        "name": "replace",
        "params": [{"name": "input", "as": "xs:string?"}, {
            "name": "pattern",
            "as": "xs:string"
        }, {"name": "replacement", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The function returns the <code>xs:string</code> that isobtained by replacing each non-overlapping substring of <code>$input</code> that matches the given <code>$pattern</code> with an occurrence of the <code>$replacement</code> string.</p> "
    }, {
        "name": "replace",
        "params": [{"name": "input", "as": "xs:string?"}, {
            "name": "pattern",
            "as": "xs:string"
        }, {"name": "replacement", "as": "xs:string"}, {"name": "flags", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The function returns the <code>xs:string</code> that isobtained by replacing each non-overlapping substring of <code>$input</code> that matches the given <code>$pattern</code> with an occurrence of the <code>$replacement</code> string.</p> "
    }, {
        "name": "resolve-QName",
        "params": [{"name": "qname", "as": "xs:string?"}, {"name": "element", "as": "element()"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:QName</code> value (that is, anexpanded-QName) by taking an <code>xs:string</code> that has thelexical form of an <code>xs:QName</code> (a string in the form\"prefix:local-name\" or \"local-name\") and resolving it using thein-scope namespaces for a given element.</p> "
    }, {
        "name": "resolve-uri",
        "params": [{"name": "relative", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The purpose of this function is to enable a relativeURI to be resolved against an absolute URI.</p> "
    }, {
        "name": "resolve-uri",
        "params": [{"name": "relative", "as": "xs:string?"}, {"name": "base", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The purpose of this function is to enable a relativeURI to be resolved against an absolute URI.</p> "
    }, {
        "name": "reverse",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Reverses the order of items in a sequence. If <code>$arg</code> is the empty sequence, the empty sequence isreturned.</p> "
    }, {
        "name": "root",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the root of the tree to which <code>$arg</code> belongs. This will usually, but not necessarily, be a documentnode.</p> "
    }, {
        "name": "root",
        "params": [{"name": "arg", "as": "node()?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the root of the tree to which <code>$arg</code> belongs. This will usually, but not necessarily, be a documentnode.</p> "
    }, {
        "name": "round",
        "params": [{"name": "arg", "as": "numeric?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the number with no fractional part that isclosest to the argument. If there are two such numbers, then theone that is closest to positive infinity is returned. If type of <code>$arg</code> is one of the four numeric types <code>xs:float</code> , <code>xs:double</code> , <code>xs:decimal</code> or <code>xs:integer</code> the type of theresult is the same as the type of <code>$arg</code> . If the type of <code>$arg</code> is a type derived from one of the numeric types,the result is an instance of the base numeric type.</p> "
    }, {
        "name": "round-half-to-even",
        "params": [{"name": "arg", "as": "numeric?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The value returned is the nearest (that is, numericallyclosest) value to <code>$arg</code> that is a multiple of ten tothe power of minus <code>$precision</code> . If two such values areequally near (e.g. if the fractional part in <code>$arg</code> isexactly .500...), the function returns the one whose leastsignificant digit is even.</p> "
    }, {
        "name": "round-half-to-even",
        "params": [{"name": "arg", "as": "numeric?"}, {"name": "precision", "as": "xs:integer"}],
        "doc": " <p> <b>Summary:</b>  <br></br> The value returned is the nearest (that is, numericallyclosest) value to <code>$arg</code> that is a multiple of ten tothe power of minus <code>$precision</code> . If two such values areequally near (e.g. if the fractional part in <code>$arg</code> isexactly .500...), the function returns the one whose leastsignificant digit is even.</p> "
    }, {
        "name": "seconds-from-dateTime",
        "params": [{"name": "arg", "as": "xs:dateTime?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:decimal</code> value greater thanor equal to zero and less than 60, representing the seconds andfractional seconds in the localized value of <code>$arg</code> .</p> "
    }, {
        "name": "seconds-from-duration",
        "params": [{"name": "arg", "as": "xs:duration?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:decimal</code> representing theseconds component in the value of <code>$arg</code> . The result isobtained by casting <code>$arg</code> to an <code>xs:dayTimeDuration</code> (see <a> <b>17.1.4 Casting to durationtypes</b> </a> ) and then computing the seconds component asdescribed in <a> <b>10.3.2.3Canonical representation</b> </a> .</p> "
    }, {
        "name": "seconds-from-time",
        "params": [{"name": "arg", "as": "xs:time?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:decimal</code> value greater thanor equal to zero and less than 60, representing the seconds andfractional seconds in the localized value of <code>$arg</code> .</p> "
    }, {
        "name": "starts-with",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:boolean</code> indicating whetheror not the value of <code>$arg1</code> starts with a sequence ofcollation units that provides a minimal match to the collationunits of <code>$arg2</code> according to the collation that isused.</p> "
    }, {
        "name": "starts-with",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}, {
            "name": "collation",
            "as": "xs:string"
        }],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:boolean</code> indicating whetheror not the value of <code>$arg1</code> starts with a sequence ofcollation units that provides a minimal match to the collationunits of <code>$arg2</code> according to the collation that isused.</p> "
    }, {
        "name": "static-base-uri",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of the Base URI property from thestatic context. If the Base URI property is undefined, the emptysequence is returned. Components of the static context arediscussed in <a>SectionC.1 Static Context Components</a>  <sup> <small>XP</small> </sup> .</p> "
    }, {
        "name": "string",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of <code>$arg</code> represented as a <code>xs:string</code> . If no argument is supplied, the contextitem ( <code>.</code> ) is used as the default argument. The behaviorof the function if the argument is omitted is exactly the same asif the context item had been passed as the argument.</p> "
    }, {
        "name": "string",
        "params": [{"name": "arg", "as": "item()?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of <code>$arg</code> represented as a <code>xs:string</code> . If no argument is supplied, the contextitem ( <code>.</code> ) is used as the default argument. The behaviorof the function if the argument is omitted is exactly the same asif the context item had been passed as the argument.</p> "
    }, {
        "name": "string-join",
        "params": [{"name": "arg1", "as": "xs:string*"}, {"name": "arg2", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns a <code>xs:string</code> created byconcatenating the members of the <code>$arg1</code> sequence using <code>$arg2</code> as a separator. If the value of <code>$arg2</code> is the zero-length string, then the members of <code>$arg1</code> are concatenated without a separator.</p> "
    }, {
        "name": "string-length",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> equal to the lengthin characters of the value of <code>$arg</code> .</p> "
    }, {
        "name": "string-length",
        "params": [{"name": "arg", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> equal to the lengthin characters of the value of <code>$arg</code> .</p> "
    }, {
        "name": "string-to-codepoints",
        "params": [{"name": "arg", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the sequence of <a>[TheUnicode Standard]</a> code points that constitute an <code>xs:string</code> . If <code>$arg</code> is a zero-lengthstring or the empty sequence, the empty sequence is returned.</p> "
    }, {
        "name": "subsequence",
        "params": [{"name": "sourceSeq", "as": "item()*"}, {"name": "startingLoc", "as": "xs:double"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the contiguous sequence of items in the valueof <code>$sourceSeq</code> beginning at the position indicated bythe value of <code>$startingLoc</code> and continuing for thenumber of items indicated by the value of <code>$length</code> .</p> "
    }, {
        "name": "subsequence",
        "params": [{"name": "sourceSeq", "as": "item()*"}, {
            "name": "startingLoc",
            "as": "xs:double"
        }, {"name": "length", "as": "xs:double"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the contiguous sequence of items in the valueof <code>$sourceSeq</code> beginning at the position indicated bythe value of <code>$startingLoc</code> and continuing for thenumber of items indicated by the value of <code>$length</code> .</p> "
    }, {
        "name": "substring",
        "params": [{"name": "sourceString", "as": "xs:string?"}, {"name": "startingLoc", "as": "xs:double"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the portion of the value of <code>$sourceString</code> beginning at the position indicated bythe value of <code>$startingLoc</code> and continuing for thenumber of characters indicated by the value of <code>$length</code> . The characters returned do not extend beyond <code>$sourceString</code> . If <code>$startingLoc</code> is zero ornegative, only those characters in positions greater than zero arereturned.</p> "
    }, {
        "name": "substring",
        "params": [{"name": "sourceString", "as": "xs:string?"}, {
            "name": "startingLoc",
            "as": "xs:double"
        }, {"name": "length", "as": "xs:double"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the portion of the value of <code>$sourceString</code> beginning at the position indicated bythe value of <code>$startingLoc</code> and continuing for thenumber of characters indicated by the value of <code>$length</code> . The characters returned do not extend beyond <code>$sourceString</code> . If <code>$startingLoc</code> is zero ornegative, only those characters in positions greater than zero arereturned.</p> "
    }, {
        "name": "substring-after",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the substring of the value of <code>$arg1</code> that follows in the value of <code>$arg1</code> the first occurrence of a sequence of collation units that providesa minimal match to the collation units of <code>$arg2</code> according to the collation that is used.</p> "
    }, {
        "name": "substring-after",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}, {
            "name": "collation",
            "as": "xs:string"
        }],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the substring of the value of <code>$arg1</code> that follows in the value of <code>$arg1</code> the first occurrence of a sequence of collation units that providesa minimal match to the collation units of <code>$arg2</code> according to the collation that is used.</p> "
    }, {
        "name": "substring-before",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the substring of the value of <code>$arg1</code> that precedes in the value of <code>$arg1</code> the first occurrence of a sequence of collation units that providesa minimal match to the collation units of <code>$arg2</code> according to the collation that is used.</p> "
    }, {
        "name": "substring-before",
        "params": [{"name": "arg1", "as": "xs:string?"}, {"name": "arg2", "as": "xs:string?"}, {
            "name": "collation",
            "as": "xs:string"
        }],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the substring of the value of <code>$arg1</code> that precedes in the value of <code>$arg1</code> the first occurrence of a sequence of collation units that providesa minimal match to the collation units of <code>$arg2</code> according to the collation that is used.</p> "
    }, {
        "name": "sum",
        "params": [{"name": "arg", "as": "xs:anyAtomicType*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns a value obtained by adding together the valuesin <code>$arg</code> . If <code>$zero</code> is not specified, thenthe value returned for an empty sequence is the <code>xs:integer</code> value 0. If <code>$zero</code> isspecified, then the value returned for an empty sequence is <code>$zero</code> .</p> "
    }, {
        "name": "sum",
        "params": [{"name": "arg", "as": "xs:anyAtomicType*"}, {"name": "zero", "as": "xs:anyAtomicType?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns a value obtained by adding together the valuesin <code>$arg</code> . If <code>$zero</code> is not specified, thenthe value returned for an empty sequence is the <code>xs:integer</code> value 0. If <code>$zero</code> isspecified, then the value returned for an empty sequence is <code>$zero</code> .</p> "
    }, {
        "name": "timezone-from-date",
        "params": [{"name": "arg", "as": "xs:date?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the timezone component of <code>$arg</code> ifany. If <code>$arg</code> has a timezone component, then the resultis an <code>xs:dayTimeDuration</code> that indicates deviation fromUTC; its value may range from +14:00 to -14:00 hours, bothinclusive. Otherwise, the result is the empty sequence.</p> "
    }, {
        "name": "timezone-from-dateTime",
        "params": [{"name": "arg", "as": "xs:dateTime?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the timezone component of <code>$arg</code> ifany. If <code>$arg</code> has a timezone component, then the resultis an <code>xs:dayTimeDuration</code> that indicates deviation fromUTC; its value may range from +14:00 to -14:00 hours, bothinclusive. Otherwise, the result is the empty sequence.</p> "
    }, {
        "name": "timezone-from-time",
        "params": [{"name": "arg", "as": "xs:time?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the timezone component of <code>$arg</code> ifany. If <code>$arg</code> has a timezone component, then the resultis an <code>xs:dayTimeDuration</code> that indicates deviation fromUTC; its value may range from +14:00 to -14:00 hours, bothinclusive. Otherwise, the result is the empty sequence.</p> "
    }, {
        "name": "tokenize",
        "params": [{"name": "input", "as": "xs:string?"}, {"name": "pattern", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> This function breaks the <code>$input</code> stringinto a sequence of strings, treating any substring that matches <code>$pattern</code> as a separator. The separators themselves arenot returned.</p> "
    }, {
        "name": "tokenize",
        "params": [{"name": "input", "as": "xs:string?"}, {"name": "pattern", "as": "xs:string"}, {
            "name": "flags",
            "as": "xs:string"
        }],
        "doc": " <p> <b>Summary:</b>  <br></br> This function breaks the <code>$input</code> stringinto a sequence of strings, treating any substring that matches <code>$pattern</code> as a separator. The separators themselves arenot returned.</p> "
    }, {
        "name": "trace",
        "params": [{"name": "value", "as": "item()*"}, {"name": "label", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Provides an execution trace intended to be used indebugging queries.</p> "
    }, {
        "name": "translate",
        "params": [{"name": "arg", "as": "xs:string?"}, {
            "name": "mapString",
            "as": "xs:string"
        }, {"name": "transString", "as": "xs:string"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of <code>$arg</code> modified so thatevery character in the value of <code>$arg</code> that occurs atsome position <em>N</em> in the value of <code>$mapString</code> has been replaced by the character that occurs at position <em>N</em> in the value of <code>$transString</code> .</p> "
    }, {
        "name": "true",
        "params": [],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the <code>xs:boolean</code> value <code>true</code> . Equivalent to <code>xs:boolean(\"1\")</code> .</p> "
    }, {
        "name": "unordered",
        "params": [{"name": "sourceSeq", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the items of <code>$sourceSeq</code> in an <a> <span>·</span> implementation dependent <span>·</span> </a> order.</p> "
    }, {
        "name": "upper-case",
        "params": [{"name": "arg", "as": "xs:string?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns the value of <code>$arg</code> aftertranslating every character to its upper-case correspondent asdefined in the appropriate case mappings section in the Unicodestandard <a>[The Unicode Standard]</a> . Forversions of Unicode beginning with the 2.1.8 update, onlylocale-insensitive case mappings should be applied. Beginning withversion 3.2.0 (and likely future versions) of Unicode, precisemappings are described in default case operations, which are fullcase mappings in the absence of tailoring for particular languagesand environments. Every lower-case character that does not have anupper-case correspondent, as well as every upper-case character, isincluded in the returned value in its original form.</p> "
    }, {
        "name": "year-from-date",
        "params": [{"name": "arg", "as": "xs:date?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> representing theyear in the localized value of <code>$arg</code> . The value may benegative.</p> "
    }, {
        "name": "year-from-dateTime",
        "params": [{"name": "arg", "as": "xs:dateTime?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> representing theyear component in the localized value of <code>$arg</code> . Theresult may be negative.</p> "
    }, {
        "name": "years-from-duration",
        "params": [{"name": "arg", "as": "xs:duration?"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns an <code>xs:integer</code> representing theyears component in the value of <code>$arg</code> . The result isobtained by casting <code>$arg</code> to an <code>xs:yearMonthDuration</code> (see <a> <b>17.1.4 Casting to durationtypes</b> </a> ) and then computing the years component as describedin <a> <b>10.3.1.3 Canonicalrepresentation</b> </a> .</p> "
    }, {
        "name": "zero-or-one",
        "params": [{"name": "arg", "as": "item()*"}],
        "doc": " <p> <b>Summary:</b>  <br></br> Returns <code>$arg</code> if it contains zero or oneitems. Otherwise, raises an error [ <a>err:FORG0003</a> ].</p> "
    }]
});
CodeMirror.XQuery.defineModule({
    "prefix": "xs",
    "namespace": "http://www.w3.org/2001/XMLSchema",
    "functions": [{"name": "anyURI", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "base64Binary",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "boolean", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "byte",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "date", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "dateTime",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "dayTimeDuration", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "decimal",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "double", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "duration",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "ENTITY", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "float",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "gDay", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "gMonth",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "gMonthDay", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "gYear",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "gYearMonth", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "hexBinary",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "int", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "integer",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "language", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "long",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "Name", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "NCName",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {
        "name": "negativeInteger",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {
        "name": "nonNegativeInteger",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {
        "name": "nonPositiveInteger",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "normalizedString", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "positiveInteger",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "QName", "params": [{"name": "arg", "as": "xs:anyAtomicType"}]}, {
        "name": "short",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "string", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "time",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "token", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "unsignedByte",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "unsignedInt", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "unsignedLong",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "unsignedShort", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}, {
        "name": "untypedAtomic",
        "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]
    }, {"name": "yearMonthDuration", "params": [{"name": "arg", "as": "xs:anyAtomicType?"}]}]
});
