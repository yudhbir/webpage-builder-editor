/**
 * CSS Inline Transform v0.1
 * http://tikku.com/css-inline-transformer-simplified
 * 
 * Copyright 2010-2012, Nirvana Tikku
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * https://github.com/jquery/jquery/blob/master/MIT-LICENSE.txt
 * 
 * This tool leverages the jQuery library.
 * 
 * Compatibility only tested with FireFox 3.5+, Chrome 5+
 * 
 * @author Nirvana Tikku
 * @dependent jQuery 1.4
 * @date Wed Mar 31 14:58:04 2010 -0500
 * @updated Sat Mar 10 16:21:20 2012 -0500
 * 
 */
(function(){
    
    //
    // private methods
    //
    /**
     * @param stylesArray - the array of string 
     *          "{name}:{value};" pairs that are to be broken down
     *
     */
    function createCSSRuleObject (stylesArray) {
        var cssObj = {};
        for(_s in stylesArray){
            var S = stylesArray[_s].split(":");
            if(S[0].trim()==""||S[1].trim()=="")continue;
            cssObj[S[0].trim()] = S[1].trim();
        }
        return cssObj;
    }

    /**
     * @param $out - the tmp html content
     * 
     */
    function interpretAppendedStylesheet ($out) { 
        var stylesheet = $out[0].styleSheets[0]; // first stylesheet
        for(r in stylesheet.cssRules){
            try{
                var rule = stylesheet.cssRules[r]; 
                if(!isNaN(rule))break; // make sure the rule exists
                var $destObj = $out.find(rule.selectorText);
                var obj = rule.cssText.replace(rule.selectorText, '');
                obj = obj.replace('{','').replace('}',''); // clean up the { and }'s
                var styles = obj.split(";"); // separate each 
                $destObj.css(createCSSRuleObject(styles)); // do the inline styling
            } catch (e) { }
        }
    };
    
    
    function isPatternRelevant (newHTML, pattern, relevantPatterns) {
        if( newHTML.indexOf(pattern) > -1 )
            relevantPatterns.push(new RegExp(pattern,"i"));
    };

    /**
     * The main method - inlinify
     *  this utilizes two text areas and a div for final output -  
     *      (1) css input textarea for the css to apply
     *      (2) html content for the css to apply TO
     */
    function inlinify (input) {
        var tmpWindow = window.open("", "tmpHtml", "width=0,height=0");
        window.blur(); // re focus on main window
        var tmpDoc = tmpWindow.document; // create a window that we can use 
        var $tmpDoc = jQuery(tmpDoc); // jquerify the temp window 

        tmpDoc.write(input); // write the HTML out to a new window doc
        interpretAppendedStylesheet($tmpDoc); // apply styles to the document just created
        $tmpDoc.find("style").remove(); // sanitize all style tags present prior to the transformation
    
        var newHTML = $tmpDoc.find("html").html();
        tmpWindow.close();
        
        var relevantPatterns = [];
        isPatternRelevant(newHTML, "href=\"", relevantPatterns);
        isPatternRelevant(newHTML, "src=\"", relevantPatterns);
        return sanitize( newHTML, relevantPatterns );
    };
    
    function sanitize(html, patterns){
        var ret = html;
        for(var i=0; i<patterns.length; i++){
            ret = san(ret, patterns[i])
        }  
        return ret;
    };

    /**
     * This method will take HTML and a PATTERN and essentially
     * sanitize the following chars within the HTML with that 
     * pattern through a filter: 
     *      Currently this only applies to &amp;' -> &
     */
    function san(html, pattern){
    
        var ret = "";
        var remainingString;
        var hrefIndex;
        for(var i=0; i<html.length; i++){
            remainingString = html.substring(i);
            hrefIndex = remainingString.search(pattern);
            if( hrefIndex === 0 ){
                // actually sanitize the pattern, i.e. href="[sanitize-candidate]"
                // must be encapsulated within quotes, "
               (function(){
                   // get the start of what we will sanitize
                   var startIndex = remainingString.indexOf("\"");
                   // and the end 
                   var endIndex = remainingString.indexOf("\"",startIndex+1);
                   // get the data to sanitize
                   var newHREF = html.substring(i+startIndex+1, i+endIndex+1);
                   // here we actually perform the replacement
                   newHREF = newHREF.replace(/&amp;/g, '&');
                   // add the pattern + the new data + a closing quote
                   var regExpStartLen = "/".length;
                   var regExpFlagsLen = "/i".length;
                   ret += String(pattern).substring( regExpStartLen, String(pattern).length - regExpFlagsLen)
                        + newHREF;
                   i += endIndex;
               })();
               continue;
            } else { 
                // if we have another href, copy everything until that href index
                if( hrefIndex > 0 ) {
                    ret += html.substring(i, hrefIndex);
                    i = hrefIndex-1;
                } else { 
                    // otherwise just add the remaining chars and stop trying to sanitize
                    ret += html.substring(i);
                    break;
                }
            }
        }
        return ret;
    
    };
    
    //
    // public methods
    //
    doInline = function(input) {
        return inlinify(input);
    }
    
})();