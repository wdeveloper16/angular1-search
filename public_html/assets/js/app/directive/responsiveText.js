/**
 * Created by azizk on 2/8/2017.
 */
app.directive('responsiveText', ['$window', function ($window) {
    return {
        link: link,
        restrict: 'E',
        scope: {
            textData: '='
        }
    };
    function link(scope, element, attrs) {
        scope.width = $window.innerWidth;
        var that = element;
        element = element[0];
        var text = scope.textData;
        function refactorText (){
            if (typeof text != 'undefined') {
                var c = 0;
                that.text('');
                for (var i = 0; i < text.length; i++) {
                    var newNode = document.createElement('span');
                    newNode.appendChild(document.createTextNode(text.charAt(i)));
                    element.appendChild(newNode);
                }
                var startPoint = $(element).offset().top;
                var endPoint = $(element).innerHeight() + $(element).offset().top;
                var visibleText = '';
                for (var sp = 0; sp < $(element).children('span').length; sp++){
                    var newNode = $($(element).children('span')[sp]);
                    if ($(newNode).offset().top < endPoint) {
                        visibleText += $($(element).children('span')[sp]).html();
                        c++;
                    }
                }
                visibleText = visibleText.substring(0, visibleText.length - 2);
                var lastWord = visibleText.lastIndexOf(" ");
                visibleText = visibleText.substring(0, lastWord);
                visibleText += '...';
                $(element).html(visibleText);
            }
        }
        if( $(element).hasClass('videoResponsive') &&  scope.width < 581){
            refactorText();
        }else if(scope.width < 1025){
            refactorText();
        }
        angular.element($window).bind('resize', function(){
            scope.width = $window.innerWidth;
            if( $(element).hasClass('videoResponsive') &&  scope.width < 581){
                refactorText();
            }else if(scope.width < 1025){
                refactorText();
            }
        });
    }
}]);
