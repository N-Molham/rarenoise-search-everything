/**
 * Created by Nabeel on 2016-02-02.
 */
!function(a,b,c){b(function(){
// setup button click
b("#rnse_indexes_setup").on("click",function(a){
// set loading status
var c=b(a.currentTarget).prop("disabled",!0).append('<span class="spinner" style="visibility: visible;"></span>');b.post(ajaxurl,{action:"setup_search_fulltext"},function(a){alert(a.data)},"json").always(function(a){return function(){
// clear loading status
a.prop("disabled",!1).find(".spinner").remove()}}(c))})})}(window,jQuery);