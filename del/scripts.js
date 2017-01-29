window.onload = function () {
  
    var slideout = new Slideout({
        'panel': document.getElementById('panel-slideout'),
        'menu': document.getElementById('menu-slideout'),
        'padding': 256,
        'tolerance': 0,
        'touch': false,
        'side': 'right'
    });

    // Toggle button
    document.querySelector('#button-slideout').addEventListener('click', function() {
        
        // Slides the column menu
        slideout.toggle();

        // Gets the scroll up button
        var scrollUpButton = document.getElementById('goup');

        // Checks if open hides the scroll up button. If slideout is close show the scroll up button
        if(slideout.isOpen()){
            //http://callmenick.com/post/add-remove-classes-with-javascript-property-classlist
            //scrollUpButton.classList.remove("visible-xs");
            scrollUpButton.style.display = 'none';
        }else{
            //scrollUpButton.classList.add("visible-xs");
            scrollUpButton.style.display = 'block';
        }
    });

};

( function( $ ) {
    //http://stackoverflow.com/questions/3898130/check-if-a-user-has-scrolled-to-the-bottom
    $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() > $(document).height() - 50) {
            //console.log("near bottom!");
            $('#goup').css('bottom','60px');
        }else{
            $('#goup').css('bottom','10px');
        }
    });

} ) ( jQuery );


/*
https://css-tricks.com/snippets/javascript/showhide-element/
*/
function toggle_visibility(id) {

    var e = document.getElementById(id);

    //console.log(e);
    //console.log(e.style.display);

    if(e.style.display == 'block')
        e.style.display = 'none';
    else
        e.style.display = 'block';
}  