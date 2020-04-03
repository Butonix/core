// http://projects.sergiodinislopes.pt/flexdatalist/

$(document).ready(function(){
  $('#post-search-input').flexdatalist({
       minLength: 1,
       selectionRequired: true,
       visibleProperties: ["id", "title"],
       valueProperty: 'url',
       searchIn: 'title',
       data: '/wp-json/searchable/posts',
       cache: true,
       cacheLifetime: 120,
       searchByWord: true
  }).on("select:flexdatalist", function(key, entry){
    window.location.href=entry.url;
  });

});