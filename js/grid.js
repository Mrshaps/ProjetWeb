function viewport() {
  //alert( window.innerHeight +" "+ window.outerWidth /2)
	var body = document.body,
	html = document.documentElement,
  //height = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight, window.innerHeight, window.outerWidth);
	height =  window.outerWidth /1.8;
	width = Math.max(body.scrollWidth, body.offsetWidth, html.clientWidth, html.scrollWidth, html.offsetWidth);
	return [window.innerWidth, height];
}

function tailleCorpt() {
  var corpt = document.getElementById('corpt');
  height = Math.max(corpt.scrollHeight, corpt.offsetHeight);
  width = Math.max(corpt.scrollWidth, corpt.offsetWidth);
  return [width, height];
}

function drawGrid() {
	var dimensions = viewport(),
	columns = dimensions[0] / 80,
	rows = dimensions[1] / 80,
	grid = document.getElementById('grid'),
	table = document.createElement('table'),
	row,
	cell,
	i = 0,
	j = 0;  
	grid.innerHTML = "";
  grid.style.width = dimensions[0] + 'px';
  grid.style.height = dimensions[1] + 'px';
  for (i = 0; i < rows; i++) {
    row = document.createElement('tr');
    for (j = 0; j < columns; j++) {      
      cell = document.createElement('td');
      cell.style['-webkit-animation-delay'] = Math.random().toFixed(2) + "s";
      cell.style['-webkit-animation-duration'] = Math.random().toFixed(1) * 20 + "s";
      row.appendChild(cell);
		}    
		table.appendChild(row);
	}  			
  grid.appendChild(table);
}

window.onresize = function() {
	drawGrid();  
}
$(document).ready(function(){
  drawGrid();
});