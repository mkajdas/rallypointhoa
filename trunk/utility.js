function getStyleObject(objectId) {
    // cross-browser function to get an object's style object given its id
    if(document.getElementById && document.getElementById(objectId)) {
	// W3C DOM
	return document.getElementById(objectId).style;
    } else if (document.all && document.all(objectId)) {
	// MSIE 4 DOM
	return document.all(objectId).style;
    } else if (document.layers && document.layers[objectId]) {
	// NN 4 DOM.. note: this won't find nested layers
	return document.layers[objectId];
    } else {
	return false;
    }
} // getStyleObject

function changeObjectVisibility(objectId, newVisibility) {
    // get a reference to the cross-browser style object and make sure the object exists
    var styleObject = getStyleObject(objectId);
    if(styleObject) {
	styleObject.visibility = newVisibility;
	return true;
    } else {
	// we couldn't find the object, so we can't change its visibility
	return false;
    }
} // changeObjectVisibility

function moveObject(objectId, newXCoordinate, newYCoordinate) {
    // get a reference to the cross-browser style object and make sure the object exists
    var styleObject = getStyleObject(objectId);
    if(styleObject) {
	styleObject.left = newXCoordinate;
	styleObject.top = newYCoordinate;
	return true;
    } else {
	// we couldn't find the object, so we can't very well move it
	return false;
    }
} // moveObject

function changeDiv(the_div,the_change)
{
  var the_style = getStyleObject(the_div);
  if (the_style != false)
  {
    the_style.display = the_change;
  }
}

function isChecked(object) {
    if (object.checked) return true;
    else return false;
}

function changebuiler(builder)
{
	if(isChecked(builder) != false){
		changeDiv('licensebox', 'none');	

	}else{
		changeDiv('licensebox', 'block');

	}
}

function userdropdown(oSelect)
{
var iSel = oSelect.selectedIndex;
var oOptions = oSelect.options;

	if(oOptions[iSel].value==0){
		 effect_slide_down_user = Effect.SlideDown('d1');	
	}else{
		 changeDiv('d1', 'none');	
	}
}


function addtoemail(oSelect)
{
var iSel = oSelect.selectedIndex;
var oOptions = oSelect.options;

	if(oOptions[iSel].value==0){
	}else{
		if(document.getElementById('emailtosendto').value.length>0){
			document.getElementById('emailtosendto').value=document.getElementById('emailtosendto').value + "," + oOptions[iSel].value;
		}else{
			document.getElementById('emailtosendto').value=oOptions[iSel].value;
		}
	}
}




function myVoid() { ; }
