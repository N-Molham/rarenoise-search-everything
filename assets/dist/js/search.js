!function(a){"use strict";/**
	 * @param {Object} item
	 * @param {String} template
	 *
	 * @return {String}
	 */
function b(b,c){for(var d in b)b.hasOwnProperty(d)&&(c=c.replace(new RegExp("{"+d+"}","g"),a.isArray(b[d])?b[d].join(", "):b[d]));return c}a(function(){
// vars
var c,d=a("body"),e=a("html,body");d.on("click",".uk-navbar-flip a",function(b){setTimeout(function(b){var c=rnse_search.is_mobile?a(b).closest(".uk-navbar-flip").siblings(".tm-search-bar").find("input.search-everything-input"):a(b).closest(".tm-navbar").find("input.search-everything-input:visible");c.length&&(c.focus(),d.addClass("search-everything-overlay-open"),e.css({overflow:"hidden"}))}(b.currentTarget),50)}),d.on("click",".tm-search-bar .uk-float-right a[data-uk-toggle]",function(){d.hasClass("search-everything-overlay-open")&&(d.removeClass("search-everything-overlay-open"),e.css({overflow:""}))}),a(".search-everything-input").each(function(d,e){
// vars
var f=a(e),g=f.siblings(".search-everything-results"),h=g.find("section.search-everything-result");
// Search input typing handler
f.typeWatch({captureLength:2,wait:500,callback:function(d,e,f){return function(g){c&&
// clear previous request
c.abort(),e.addClass("is-loading"),d.addClass("is-loading").siblings("div.loading-indicator").removeClass("uk-hidden"),
// fetch the form
c=a.post(wc_cart_fragments_params.ajax_url,{action:"search_everything",query:g,where:"posts,artists,releases"},function(a){
// walk through results parts
for(var c in a.data)
// skip non-property 
if(a.data.hasOwnProperty(c)){var d=a.data[c],e=f.filter("."+c+"-result");if(0!==e.length){var g=e.find("ul.results-section-list").empty();if(d.length){
// results found
e.addClass("has-results");for(var h=[],i=g.data("template"),j=0;j<d.length;j++)h.push(b(d[j],i));g.html(h.join(""))}else
// found nothing
g.html(g.data("no-results")),e.removeClass("has-results")}}}).always(function(){e.removeClass("is-loading uk-hidden"),d.removeClass("is-loading").siblings("div.loading-indicator").addClass("uk-hidden")})}}(f,g,h)})})}),/*
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
return this.each(function(){d(this)})}}(jQuery);