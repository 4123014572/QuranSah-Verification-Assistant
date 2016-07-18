
/*
Tipue Search 5.0
Copyright (c) 2015 Tipue
Tipue Search is released under the MIT License
http://www.tipue.com/search
*/


(function($) {

     $.fn.tipuesearch = function(options) {

          var set = $.extend( {
          
               'show'                   : 10,
               'newWindow'              : false,
               'showURL'                : true,
               'showTitleCount'         : true,
               'minimumLength'          : 3,
               'descriptiveWords'       : 25,
               'highlightTerms'         : true,
               'highlightEveryTerm'     : false,
               'mode'                   : 'static',
               'liveDescription'        : '*',
               'liveContent'            : '*',
               'contentLocation'        : 'tipuesearch/tipuesearch_content.json',
               'debug'                  : false
          
          }, options);
          
          return this.each(function() {

               var tipuesearch_in = {
                    pages: []
               };
               $.ajaxSetup({
                    async: false
               });
               var tipuesearch_t_c = 0;

               if (set.mode == 'live')
               {
                    for (var i = 0; i < tipuesearch_pages.length; i++)
                    {
                         $.get(tipuesearch_pages[i])
                              .done(function(html)
                              {
                                   var cont = $(set.liveContent, html).text();
                                   cont = cont.replace(/\s+/g, ' ');
                                   var desc = $(set.liveDescription, html).text();
                                   desc = desc.replace(/\s+/g, ' ');
                                                                      
                                   var t_1 = html.toLowerCase().indexOf('<title>');
                                   var t_2 = html.toLowerCase().indexOf('</title>', t_1 + 7);
                                   if (t_1 != -1 && t_2 != -1)
                                   {
                                        var tit = html.slice(t_1 + 7, t_2);
                                   }
                                   else
                                   {
                                        var tit = tipuesearch_string_1;
                                   }

                                   tipuesearch_in.pages.push(
                                   {
                                        "title": tit,
                                        "text": desc,
                                        "tags": cont,
                                        "url": tipuesearch_pages[i] 
                                   });    
                              });
                    }
               }
               
               if (set.mode == 'json')
               {
                    $.getJSON(set.contentLocation)
                         .done(function(json)
                         {
                              tipuesearch_in = $.extend({}, json);
                         });
               }

               if (set.mode == 'static')
               {
                    tipuesearch_in = $.extend({}, tipuesearch);
               }                              
               
               var tipue_search_w = '';
               if (set.newWindow)
               {
                    tipue_search_w = ' target="_blank"';      
               }

               function getURLP(name)
               {
                    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search)||[,""])[1].replace(/\+/g, '%20')) || null;
               }
               if (getURLP('q'))
               {
                    $('#tipue_search_input').val(getURLP('q'));
                    getTipueSearch(0, true);
               }               
               
               $(this).keyup(function(event)
               {
                    if(event.keyCode == '13')
                    {
                         getTipueSearch(0, true);
                    }
               });
               

               function getTipueSearch(start, replace)
               {
                    $('#tipue_search_content').hide();
                    $('#tipue_search_content').html('<div class="tipue_search_spinner"><div class="tipue_search_rect1"></div><div class="tipue_search_rect2"></div><div class="rect3"></div></div>');
                    $('#tipue_search_content').show();
                    
                    var out = '';
                    var results = '';
                    var show_replace = false;
                    var show_stop = false;
                    var standard = true;
                    var c = 0;
                    found = [];
                    
                    var d = $('#tipue_search_input').val();
                    d = $.trim(d);
                    
                    alert(d);

                    
                    $('#tipue_search_content').hide();
                    $('#tipue_search_content').html(out);
                    $('#tipue_search_content').slideDown(200);
                    
                    //coisox
                    //=================================================================================
                    $('.tipue_search_content_text').each(function() {
                        var aya = $(this).find('highlight').next('aya').text();
                        $(this).prev().prev().find('#match').text(aya);
                    });
                    //=================================================================================
                    
                    $('#tipue_search_replaced').click(function()
                    {
                         getTipueSearch(0, false);
                    });                
               
                    $('.tipue_search_foot_box').click(function()
                    {
                         var id_v = $(this).attr('id');
                         var id_a = id_v.split('_');
                    
                         getTipueSearch(parseInt(id_a[0]), id_a[1]);
                    });                                                       
               }          
          
          });
     };
   
})(jQuery);
