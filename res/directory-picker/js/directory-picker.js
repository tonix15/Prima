/**
 * Directory Picker jQuery Plugin
 * @author Voislav Jovanović <voislavj@gmail.com>
 */
(function(){
    $.fn.extend({
        directory_picker: function(options) {
            var options = $.extend(true, {
                path:   "",
                    width:  400,
                    height: 240,
                    script: null
            }, options);
                   
            if(options.script){ DirectoryPick($(this), options); }
        }
    });
})(jQuery);

function DirectoryPick(input, options) {
    var trigger = $('<a class="directory-picker-trigger" href="javascript:void(0);">select</a>');
    trigger.click(function(){ DirectoryPickShow(input, options); });
    input.after(trigger);
}

function DirectoryPickShow(input, options) {
    var content = $('<div class="directory-picker-content" />');    
    content.html("loading...");    
    content.dialog({
        title: input.val(),
        modal: true,
        width: options.width,
        height: options.height,
        buttons: {
            "Select": function() {
                var dir = $('.directory-picker-content a.selected');
                if(dir.length) {
                    var path = $('.directory-picker-content').attr("base") + "/" + dir.attr("title");
                    input.val(path);
                    $( this ).dialog( "close" );
                }
            },
            "Cancel": function() { $( this ).dialog( "close" ); }
        }
    });
       
    var filter = $('<input type="text" id="directory-picker-filter" />');
    filter.keypress(function(){
        var to = $(this).attr("timeout");
        if(to) { clearTimeout(to); }
    });
    filter.keyup(function(e) {
        $(this).attr("timeout", setTimeout(function(){
            var val = filter.val();
            content.find("a").each(function(){
                if($(this).attr("title").indexOf(val) >= 0) { $(this).show(); }
				else{ $(this).hide(); }
            });
        }, 800));
    });
    $('.ui-dialog-buttonpane').append('<label for="directory-picker-filter">Search:</label>')
    $('.ui-dialog-buttonpane').append(filter);
    
    var parent = "";
    var tmp = input.val().replace(/\\/g, "/").split(/\//);
    if(input.val().length && tmp.length>0) {
        tmp.pop();
        parent = tmp.join("/");
    }
    parent = parent.replace(/^"|#$/g, "");
    DirectoryPickLoad(parent, options, input);
}

function DirectoryPickLoad(path, options, input) {
    var content = $('.directory-picker-content');
    path = path.replace(/\/$/, "");
    $.ajax({
        url: options.script,
        type: "get",
        data: $.extend(options, {path: path}),
        beforeSend: function() { content.html("Loading..."); },
        complete: function(req) {
            if(req.status == 200) {
                var data = eval("(" + req.responseText + ")");
                
                var title = data.base;
                if(title.length>30) { title = "..." + title.substr(title.length-30) ; }
                $('.ui-dialog-title').html(title);
                content.attr("base", data.base);
                
                content.empty();
                var full, short;
                for(x in data.children) {
                    full = data.children[x].name;
                    short = data.children[x].name;
                    if(short.length>10) { short = short.substr(0,10) + "..."; }
                    content.append('<a class="type-'+data.children[x].type+'" title="'+ full + '" href="javascript:void(0)">' + short  + '</a>')
                }
                content.children("a").click(function(){
                    $(this).parent().children("a").removeClass("selected");
                    $(this).addClass("selected");
                });
                content.children("a").dblclick(function(){
                    if($(this).hasClass("type-dir") || $(this).hasClass("type-dir-up")) { DirectoryPickLoad(data.base + "/" + $(this).attr("title"), options, input); }
					else{
                        var path = content.attr("base") + "/" + $(this).attr("title");
                        input.val(path);
                        content.dialog("close");
                    }
                });
            }else{ content.html(req.status + ". " + req.statusText); }
        }
    });
}
