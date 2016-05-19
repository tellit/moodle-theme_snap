// This code provided by Steve McComb
// The intention is that it hides and unhides headings in the breadcrumb
// when 'fixheadertotopofpage' is set
// Tested when 'breadcrumbsinnav' is also set.

$(document).ready(function () {
    // Empty array for storing <h2> & <h4> objects.
    
    var titleObjects = [];
    $("#region-main h2, #region-main h4").each(
        function() {
            // add each <h2> or <h4> object to the array.
            titleObjects.push( $(this) )  
        }
    );
    theBigTitleText = "";
    
    // If at least one object found, make theBigTitle the first one found.
    if (titleObjects.length != 0) {
        theBigTitle = titleObjects[0]; 
        theBigTitleText = theBigTitle.text().trim()
    }; 

    // First item in the breadcrumb trail with the same title.
    var theBreadcrumbTitle = $('.breadcrumb li:contains("'+ theBigTitleText +'"):first'); 

    // vertical position of theBigTitle.
    var theTop = theBigTitle.offset().top - 25 - parseFloat(theBigTitle.css('marginTop').replace(/auto/, 100)); 
    
    // Switch.
    var linkVisible = false; 

    // When there is a scroll event...
    $(window).scroll(function (event) {

        // The current scroll amount
        var yPos = $(this).scrollTop();

        // If big title goes off screen fade in breadcrumb title
        if ((yPos >= theTop) && !linkVisible) {
            theBreadcrumbTitle.css('opacity', '0').css('display', 'inline-block').animate({opacity: '1'}, 150);
            
            // Once faded in, don't do anything until the big title comes back into view
            linkVisible = true;
            return;
        }
        // If big title comes on screen, fade out breadcrumb title
        if ((yPos < theTop) && linkVisible) {

            theBreadcrumbTitle.animate({opacity: '0'}, 150, function() { 
                theBreadcrumbTitle.hide() 
            });

            // once faded out, don't do anything until the big title goes out of view.
            linkVisible = false;
            return;
        }
    });
});