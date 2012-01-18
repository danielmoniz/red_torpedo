<style type="text/css">
    #battlefield { border: 1px black solid; }
</style>

<canvas id="battlefield" width="1000" height="600">  
  current stock price: $3.15 +0.15  
</canvas>

<script type="text/javascript">
    
    init();
    
    var hex = new Object();
    hex.radius = 40;
    var board = new Object();
    board.width = 13;
    board.height = 9;

    function init() {
        var battlefield = new CanvasState(document.getElementById('battlefield'));
        
    }
    
    function CanvasState(canvas) {
        
    }
    
    
    hex.piece = 20;
    
    var curr = new Object();
    curr.x = 45;
    curr.y = 45;
    function draw() {
        /* get the HTML object itself (not the jquery object or array). */
        var battlefield = $("#battlefield").get(0);
//        var battlefield = document.getElementById('battlefield');
//        if (battlefield.getContext()) {
            var context = battlefield.getContext("2d");
            
//            context.fillStyle = "rgb(200, 0, 0)";
//            context.fillRect(10, 10, 55, 50);
            
            context.beginPath(); // begin custom shape
            context.moveTo(curr.x, curr.y);
            
            for (var i=0;i<board.height; i++) {
                for (var j=0-Math.floor(i/2); j<board.width-Math.floor(i/2); j++) {
                    drawHexagon(
                            context, curr.x + j*Math.sqrt(3)*hex.radius + i*Math.sqrt(3)*hex.radius/2, 
                            curr.y + i*(3/2)*hex.radius, 
                            hex.radius
                        );
                }
            }
//        }
    }
    
    function drawHexagon(context, x, y, radius) {
        drawPolygon(context, x, y, radius, 6);
    }
    
    function drawPolygon(context, x, y, r, numSides) {
        var angChange = degreesToRadians(360.0/numSides);
        var newX = x;
        var newY = y + r;
        var firstAngle = -Math.PI/2;
        context.moveTo(newX, newY);
        
        for(var i=1; i<numSides+1; i++) {
            angle = firstAngle + i*angChange;
            newX = x + r*Math.cos(angle);
            newY = y - r*Math.sin(angle); // account for y-reversal
            context.lineTo(newX, newY);
        }
//        context.closePath();
        context.strokeStyle = '#0000FF';
        context.lineWidth = 3;
        context.stroke();

    }
    
    function degreesToRadians(angle) {
        return angle%360 * (Math.PI/180.0);
    }
    
    $(document).ready(function() {
        draw();
        $("body").click(function(event) {
            console.log(event.pageX, event.pageY);
        });
    });
</script>