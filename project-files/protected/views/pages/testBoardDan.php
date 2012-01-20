<style type="text/css">
    #battlefield { border: 1px black solid; }
</style>

<canvas id="battlefield" width="1000" height="600">  
    current stock price: $3.15 +0.15  
</canvas>

<script type="text/javascript">
    
    var modelHex = new Object();
    modelHex.radius = 40;
    var board = new Object();
    board.width = 13;
    board.height = 9;
    var battlefield;
    var context;
    
    init();

    function init() {
        var battlefield = new CanvasState(document.getElementById('battlefield'));
        
        battlefield.draw(); // draw initial board state
    }
    
    function CanvasState(canvas) {
        this.canvas = canvas;
        this.width = canvas.width;
        this.height = canvas.height;
        this.context = canvas.getContext("2d");
        
        this.hexes = [];
        this.selection = null;
        this.selectionColor = "#CC0000";
        
        var mystate = this;
        
        canvas.addEventListener('mousedown', function(event) {
            var mouse = mystate.getMouse(event);
            
            var matchingHexes = [];
            for (var i = 0; i < mystate.hexes.length; i++) {
                //                if (mystate.hexes[i].contains(mouse.x, mouse.y)) {
                matchingHexes.push(mystate.hexes[i]);
                //                }
            }
            console.log(matchingHexes);
            // get the single matching hex
            var hex = findClosestHex(mouse.x, mouse.y, matchingHexes);
            this.selection = hex;
            
            drawHexagon(context, hex.centre.x, hex.centre.y, hex.radius, true);
        });
        
        this.addHex = function(hex) {
            this.hexes.push(hex);
            // make canvas invalid so that it renders again
        }
    }
    
    CanvasState.prototype.draw = function() {
        console.log("draw");
        var curr = new Object();
        this.hexes = [];
        curr.x = 45;
        curr.y = 45;
        /* get the HTML object itself (not the jquery object or array). */
            
        //            context.fillStyle = "rgb(200, 0, 0)";
        //            context.fillRect(10, 10, 55, 50);
            
        context.beginPath(); // begin custom shape
        context.moveTo(curr.x, curr.y);
            
        for (var i=0;i<board.height; i++) {
            for (var j=0-Math.floor(i/2); j<board.width-Math.floor(i/2); j++) {
                var hexX = curr.x + j*Math.sqrt(3)*modelHex.radius + i*Math.sqrt(3)*modelHex.radius/2;
                var hexY = curr.y + i*(3/2)*modelHex.radius;
                
                drawHexagon(context, hexX, hexY, modelHex.radius);
                this.hexes.push(new Hex(hexX, hexY, modelHex.radius));
            }
        }
    }
    
    /**
     * Note: currently defining a hexagon to be a circle. Can then calculate 
     * which hex a given click is closest to if it is within the radius of 
     * multiple hexes.
     */
    function Hex(centreX, centreY, radius) {
        this.centre.x = centreX;
        this.centre.y = centreY;
        this.radius = radius;
        this.fill = fill || "#FFFFFF";
    }
    
    /**
     * May have to return a value based on the position (offset) of the canvas.
     */
    CanvasState.prototype.getMouse = function(event) {
        var element = this.canvas, offsetX = 0, offsetY = 0, mx, my;
        
        mx = event.pageX;
        my = event.pageY;
        
        // We return a simple javascript object (a hash) with x and y defined
        return {x:mx, y:my};
    }
    
    Hex.prototype.contains = function(x, y) {
        var d = Math.sqrt((this.centre.x - x, this.centre.y - y));
        return d < this.radius;
    }
    
    /**
     * Given a list of hexes and a point, returns the hex which has its centre 
     * closest to the given point.
     */
    function findClosestHex(x, y, hexList) {
        var smallestDistance = null;
        var bestHex = null;
        for(hex in hexList) {
            var distance = Math.sqrt((x-hex.centre.x)^2, (y-hex.centre.y)^2);
            if (smallestDistance == null || distance < smallestDistance) {
                smallestDistance = distance;
                bestHex = hex;
            }
        }
        
        return bestHex;
    }
    
    function drawHexagon(context, x, y, radius, toFill) {
        drawPolygon(context, x, y, radius, 6, toFill);
    }
    
    function drawPolygon(context, x, y, r, numSides, toFill) {
        var angChange = degreesToRadians(360.0/numSides);
        var newX = x;
        var newY = y + r;
        var firstAngle = -Math.PI/2;
        context.moveTo(newX, newY);
        if (toFill)
            context.setFillType = context.selectionColor;
        
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
//    $("body").click(function(event) {
//        console.log(event.pageX, event.pageY);
//    });
});
</script>