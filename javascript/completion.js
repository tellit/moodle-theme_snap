function scrollIn(selector) {
    $(selector).show().animate({right: "20px", opacity: 1}, 1000);
}    
    
function scrollOut(selector) {
    $(selector).animate({right: "-600px", opacity: 0.5}, 200, function(){ $(selector).hide()});
}

function popCompletion() {            
    $(".next_activity_overlay").fadeTo("slow", 0, function() {
        $(this).hide();
    });
   
    TweenLite.to($("#darkBackground"),  0,      {display:"block"});
    TweenLite.to($("#darkBackground"),  0.3,    {background:"rgba(0,0,0,0.4)", force3D:true});
    TweenLite.to($("#alertBox"),        0,      {left:"calc(50% - 150px)", top:"calc(50% - 150px)", delay:"0.2"});
    TweenLite.to($("#alertBox"),        0,      {display:"block", opacity: 1, delay:"0.2"});                                 
    TweenLite.to($("#alertBox"),        0,      {display:"block", scale:0.2, opacity: 0, delay:"0.2"});
    TweenLite.to($("#alertBox"),        0.3,    {opacity: 1, force3D:true, delay:"0.2"});
    TweenLite.to($("#alertBox"),        0.3,    {scale:1, scale:1, force3D:true, delay:"0.2"});
    TweenLite.to($("#darkBackground"),  0.2,    {backgroundColor: "rgba(0,0,0,0)", force3D:true, delay:"2"});
    TweenLite.to($("#darkBackground"),  0.2,    {display: "none", force3D:true, delay:"2"});
    TweenLite.to($("#alertBox"),        0.2,    {opacity: 0, display:"none", force3D:true, delay:"2", onComplete:slideNextActivity});

}

function slideNextActivity() {
    scrollIn('#activitycompletemodal');
}

function loadCompletion() {
    var type = 'completion';
    var container = $('#completion-region');
    try {
        $.ajax({
              type: "GET",
              async:  true,
              url: M.cfg.wwwroot + '/theme/snap/rest.php?action=get_' + type +'&contextid=' + M.theme_snap.mod.context.id, // M.cfg.context,
              success: function(data) {
                  $('.completion-region').attr('data-content-loaded', '1');
                  $('.completion-region').html(data.html);
                  M.theme_snap.core.addPopCompletion();
              }
        });
    } catch(err) {
        console.write(err);
    }
    
    //bind to the control again
    bindHsuforumCompletion();   
}

function bindHsuforumCompletion() {
     $(".hsuforum-reply").submit(function(event) {
        setTimeout(
            function() {
                loadCompletion();
            }, 3000
        );
        
    });   
}