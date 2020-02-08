var arrTodo = [
	/* [type, id, class, parent] */
	['div', 'task', 'task divider-color', 'todoList', true, false, false],
	['div', 'taskIcon', 'taskIcon light-primary-color', 'task', true, false, true],
	['div', 'taskContent', 'taskContent default-primary-color', 'task', true, false, true]
];

function processTodoPhoto(result, initial) {

	var width = document.getElementById('summaryPhoto').offsetWidth;
	var height = document.getElementById('summaryPhoto').offsetHeight;

	$('#summaryPhoto').css('background-image', 'url(data:image/jpeg;base64,' + result.photo + ')');
}

function generateTodoList() {

	var el;

	// Loop through each day of the week
	for (var i = 0; i < 20; i++) {

		// Loop through each element in our array
		for (var j = 0; j < arrTodo.length; j++) {

			// Determine the type, id, classname, and parent
			var elType = arrTodo[j][0];
			var elID = (arrTodo[j][4] ? arrTodo[j][1] + i : arrTodo[j][1]);
			var elClass = (arrTodo[j][5] ? arrTodo[j][2] + i : arrTodo[j][2]);
			var elParent = (arrTodo[j][6] ? arrTodo[j][3] + i : arrTodo[j][3]);

			// Create the element based off the properties from our array
			el = generateElement(elType, elID, elClass, elParent);
		}
	}
}

function setDivSizes() {

	// Grab the top of the footer, and the height of the summary top bar
	var footerTop = document.getElementById('footer').offsetTop;
	var summaryHeight = document.getElementById('summary').offsetHeight;
	var todoHeight = footerTop - summaryHeight;

	// Set the todoContainer height and the todoList height
	document.getElementById('todoContainer').style.height = todoHeight;
	document.getElementById('todoList').style.maxHeight = todoHeight;
}

$(document).ready(function() {

	// Initialize our todoList div's
	generateTodoList();

	// Set the height of the todo list based off of the screen size
	setDivSizes();

	window.onresize = function(event) {
		setDivSizes();
	};
});
