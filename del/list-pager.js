var ListPager = (function (window) {
    
    // Instance stores a reference to the Singleton
    var instance;
    
    // Singleton
    function init() {

        var listContainer       = null;
        var offsetContainer     = null;
        var listPager           = null; // Main container, it will have all elements after render

        // Default variables
        var defaults = {
            pagerContent    : 'numeric',
            pagerJump       : 3, // Shows 1 page result item every #
        }

        /*
        Checks if the list container and offset container are created.
        If they are already created, the pager is created.
        */
        function ready(){
            if(getListContainer() != null && getOffsetContainer() != null){
                createPager();
            }
        }

        /*
        Creates all pager items with its listener
        */
        function createPager(){
            // Creates main container
            listPager = document.createElement("DIV");
            listPager.setAttribute("id", "listPager");
            listPager.classList.add("visible-xs");

            document.body.appendChild(listPager);

            // UL container
            var ulContainer = document.createElement("UL");
            listPager.appendChild(ulContainer);

            // Adds the pager elements
            var totalElements = getListElements();
            for(i = 0; i < totalElements.length; i++){

                // Pager jump
                if(i % defaults.pagerJump == 0){

                    // Creates the item pager container
                    var itemContainer = document.createElement("LI"); 
                    ulContainer.appendChild(itemContainer);

                    // Creates a link to add in the LI
                    var itemLink = document.createElement("A");
                    itemLink.setAttribute('eid', i); // Element id
                    itemContainer.appendChild(itemLink);

                    // Adds some text to the link with the id
                    var linkText = document.createTextNode(i);
                    itemLink.appendChild(linkText);
                    
                    // Creates a link listener
                    //itemLink.addEventListener('click', gotoElement);
                    var that = this;
                    itemLink.addEventListener("click", function(){
                        gotoElement(this);
                    }, false);

                }
            }            
        }

        /*
        Scrolls the targeting element to some position
        */
        function gotoElement(itemLink){

            // Gets the id position
            var epos = itemLink.getAttribute('eid');

            // Gets the children from the list container
            var children = listContainer.children; 

            // Gets the element the link is targeting
            var elementTarget = children[epos];
            
            // Gets the offset of the element
            var divOffset = offset(elementTarget);

            // Gets the how much we have to scroll
            var scrollPos = divOffset.top - offsetContainer.offsetHeight;

            // Scrolls to some position
            window.scrollTo(0, scrollPos);
        }

        /*
        Gets the offset of an element
        //http://stackoverflow.com/questions/15615552/get-div-height-with-plain-javascript
        */
        function offset(el) {
            var rect = el.getBoundingClientRect(),
            scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
            scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            return { top: rect.top + scrollTop, left: rect.left + scrollLeft }
        }

        function getPagerItemElements(){
            
            var elementContainer = document.getElementById("listPager").getElementsByTagName('UL')[0];
            var output = [];
            
            for(i = 0; i < elementContainer.children.length; i++){
                var eid = elementContainer.children[i].getElementsByTagName('A')[0].getAttribute("eid");
                output[eid] = elementContainer.children[i].getElementsByTagName('A')[0];
            }

            return output;
        }        

        function doScroll(){

            // Gets all element objects
            var pagerItemElements = getPagerItemElements();

            window.addEventListener("scroll", function(event) {
                
                var top = this.scrollY,
                    left =this.scrollX;

                var listItemContainer = getListElements();

                var itemFound = false;
                var listContainerLength = listItemContainer.length - 1;

                for(i = listContainerLength; i >= 0; i--){

                    
                    if(i % defaults.pagerJump == 0){
                        
                        var offsetResult = offset(listItemContainer[i]);
                        
                        //console.log(this.pageYOffset + " - " + offsetResult.top);
                        
                        pagerItemElements[i].style.backgroundColor = "#cdcdcd";

                        if(this.pageYOffset+250 >= offsetResult.top && !itemFound){
                        
                            itemFound = true;
                            pagerItemElements[i].style.backgroundColor = "#349aff";
                            //pagerItemElements[i].classList.add("listPagerActive");
                        }

                    }
                }
                
            }, false);            
        }        

        /*
        Sets the list container
        */
        function setListContainer(element){
            listContainer = element;
        }

        /*
        Gets the list container
        */
        function getListContainer(){
            return listContainer;
        }

        /*
        Sets the offset container
        */
        function setOffsetContainer(element){
            offsetContainer = element;
        }

        /*
        Gets the offset container
        */
        function getOffsetContainer(){
            return offsetContainer;
        }    

        /*
        Gets all elements from the list container
        */
        function getListElements (){
            return listContainer.children;
        }

        /*
        Shows the pager
        */
        function show(){
            listPager.style.display = 'block';
            doScroll();
        }

        /*
        Hides the pager
        */ 
        function hide(){
            listPager.style.display = 'none';
        }          
        
        //Public methods
        return {
            setListContainer    : setListContainer,
            getListContainer    : getListContainer,
            setOffsetContainer  : setOffsetContainer,
            getOffsetContainer  : getOffsetContainer,
            getListElements     : getListElements,
            show                : show,
            hide                : hide,
            ready               : ready,
        };

    };  
    
    return {
        // Get the Singleton instance if one exists
        // or create one if it doesn't
        getInstance: function () {

            if ( !instance ) {
                instance = init();
            }
            return instance;
        }
    };  
})(window);
