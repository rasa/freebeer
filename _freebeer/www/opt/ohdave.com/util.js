/*

$CVSHeader

Source: http://www.ohdave.com/rsa/../util.js

*/

var HIGHLIGHTED_COLOR = "#d1f5d1";
var lastUnhighlightedColors = new Array();

var isNav = false;
var isIE = false;

if (parseInt(navigator.appVersion) >= 4) {
	if (navigator.appName == "Netscape") {
		isNav = true;
	}
	else {
		isIE = true;
	}
}

function handleNavResize()
{
	location.reload();
	return false;
}

if (isNav) {
	window.captureEvents(Event.RESIZE);
	window.onresize = handleNavResize;
}

function highlightCellAnyColor(cellID, color)
{
	if (document.all) {
		var cell = document.all[cellID];
		if (cell != null) {
			lastUnhighlightedColors[cellID] = cell.style.backgroundColor;
			cell.style.backgroundColor = color;
		}
	}
	else if (document.layers) {
		var cell = document.layers[cellID];
		if (cell != null) {
			lastUnhighlightedColors[cellID] = cell.bgColor;
			cell.bgColor = color;
		}
	}
}

function highlightCell(cellID)
{
	highlightCellAnyColor(cellID, HIGHLIGHTED_COLOR);
}

function unhighlightCell(cellID)
{
	if (document.all) {
		var cell = document.all[cellID];
		if (cell != null) {
			cell.style.backgroundColor = lastUnhighlightedColors[cellID];
		}
	}
	else if (document.layers) {
		var cell = document.layers[cellID];
		if (cell != null) {
			cell.bgColor = lastUnhighlightedColors[cellID];
		}
	}
}

function setLayerVisibility(id, isVisible)
{
	var layer;
	if (document.all) {
		layer = document.all[id].style;
	}
	else if (document.layers) {
		layer = document.layers[id];
	}
	layer.visibility = isVisible ? "visible" : "hidden";
}

function showLayer(id)
{
	setLayerVisibility(id, true);
}

function hideLayer(id)
{
	setLayerVisibility(id, false);
}

