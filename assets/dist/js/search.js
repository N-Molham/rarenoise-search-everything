!function(a,b){"use strict";/**
	 * @param {Object} item
	 * @param {String} template
	 *
	 * @return {String}
	 */
function c(b,c){for(var d in b)b.hasOwnProperty(d)&&(c=c.replace(new RegExp("{"+d+"}","g"),a.isArray(b[d])?b[d].join(", "):b[d]));return c}a(function(){
// vars
var d,e=a("body"),f=a("#search-everything-overlay");if(!(f.length<1)){var g=f.find("input.search-everything-input"),h=f.find(".search-everything-results"),i=h.find("section.search-everything-result"),j=f.find("div.loading-indicator");e.on("click rarenoise-click",".search-everything-trigger",function(b){var c=a(b.currentTarget),d=c.data("search-action");switch(c.data("preventDefault")&&b.preventDefault(),d){case"open":e.addClass("search-everything-overlay-open"),g.focus();break;case"close":e.removeClass("search-everything-overlay-open")}e.triggerHandler("search-everything-trigger",c,d)}),"#search-overlay"===b.location.hash&&e.find(".search-everything-trigger[data-search-action=open]:first").trigger("click"),
// Search input typing handler
g.typeWatch({captureLength:2,wait:500,callback:function(b,e,f,g){return function(h){d&&
// clear previous request
d.abort(),e.addClass("is-loading"),b.addClass("is-loading"),g.removeClass("uk-hidden"),
// fetch the form
d=a.post(wc_cart_fragments_params.ajax_url,{action:"search_everything",query:h,where:"posts,artists,releases"},function(a){
// walk through results parts
for(var b in a.data)
// skip non-property 
if(a.data.hasOwnProperty(b)){var d=a.data[b],e=f.filter("."+b+"-result");if(0!==e.length){var g=e.find("ul.results-section-list").empty();if(d.length){
// results found
e.addClass("has-results");for(var h=[],i=g.data("template"),j=0;j<d.length;j++)h.push(c(d[j],i));g.html(h.join(""))}else
// found nothing
g.html(g.data("no-results")),e.removeClass("has-results")}}}).always(function(){e.removeClass("is-loading uk-hidden"),b.removeClass("is-loading"),g.addClass("uk-hidden")})}}(g,h,i,j)})}}),/*
	* TypeWatch 3
	* 
	* Dual licensed under the MIT and GPL licenses:
	* http://www.opensource.org/licenses/mit-license.php
	* http://www.gnu.org/licenses/gpl.html
	*/
a.fn.typeWatch=function(b){function c(a,b){var c=a.$el.val();
// If has capture length and has changed value
// Or override and has capture length or allowSubmit option is true
// Or capture length is zero and changed value
(c.length>=f.captureLength&&c!==a.text||b&&(c.length>=f.captureLength||f.allowSubmit)||0===c.length&&a.text)&&(a.text=c,a.cb.call(a.el,c))}function d(b){var d=(b.type||b.nodeName).toUpperCase();if(a.inArray(d,f.inputTypes)>=0){
// Allocate timer element
var e={timer:null,text:a(b).val(),cb:f.callback,el:b,$el:a(b),type:d,wait:f.wait};
// Set focus action (highlight)
f.highlight&&e.$el.on("focus",function(a){a.currentTarget.select()}),
// Key watcher / clear and reset the timer
e.$el.on("keydown paste cut input",function(a){var b=e.wait,f=!1;
// If enter key is pressed and not a TEXTAREA
"undefined"!=typeof a.keyCode&&13===a.keyCode&&"TEXTAREA"!==d&&(b=1,f=!0);var g=function(){c(e,f)};
// Clear timer
clearTimeout(e.timer),e.timer=setTimeout(g,b)})}}
// The default input types that are supported
var e=["TEXT","TEXTAREA","TEL","SEARCH","URL","EMAIL","DATETIME","DATE","MONTH","WEEK","TIME","DATETIME-LOCAL"],f=a.extend({wait:750,callback:function(){},highlight:!0,captureLength:2,allowSubmit:!1,inputTypes:e},b);
// Watch each element
return this.each(function(){d(this)})}}(jQuery,window);