document.addEventListener('DOMContentLoaded',function() {
	
	// Category Choice Page Variables
	var categorySelect = document.querySelector('select[name="category"]');
	var startButton = document.querySelector('.start');
	var questionCountSelect = document.querySelector('select[name="number_of_questions"]');
	
	// Question Page Variables
	var choices = document.getElementsByName('choice');
	var answerSubmit = document.getElementById('submitAnswer');
	
	function getInfo(method, file, async, func) {
		var xhr ;
		
		if(window.XMLHttpRequest) {
			xhr = new XMLHttpRequest();
		} else { 
			xhr = new ActiveXObject("Microsoft.XMLHTTP");
		}
		
		xhr.open(method, file, async);
		
		xhr.onreadystatechange = function() {
			if(this.readyState === 4 && this.status === 200) {
				//document.getElementById('number_of_questions').innerHTML = '<option value="none" selected disabled>Select One...</option>';
				
//				func();
				
//				this.func = func();
				
				var divisor = Math.floor(this.responseText / 5);
				
				if(divisor !== 0) {
					for(var i = 0; i <= divisor; i++) {

						if(i === 0) {
							document.getElementById('number_of_questions').innerHTML = '<option value="none" selected disabled>' + i * 5 + '</option>';
						} else {
							document.getElementById('number_of_questions').innerHTML += '<option value="' + i * 5 + '">' + i * 5 + '</option>';
						}
					}
				} else {
					document.getElementById('number_of_questions').innerHTML += '<option value="' + this.responseText + '">' + this.responseText + '</option>';
				}

			}
		}
		xhr.send();	
	}
	
	function getQuestions(method, file, async, func) {
		
		var xhr = new XMLHttpRequest();
		xhr.open(method, file, async);

		xhr.onreadystatechange = function() {
			if(this.readyState === 4 && this.status === 200) {
				
				
			}
		}
		xhr.send();	

	}
	
	
//	function showQuestionCount() {
//		var divisor = Math.floor(this.responseText / 5);
//				
//		if(divisor !== 0) {
//			for(var i = 0; i <= divisor; i++) {
//
//				if(i === 0) {
//					document.getElementById('number_of_questions').innerHTML += '<option value="none" selected disabled>' + i * 5 + '</option>';
//				} else {
//					document.getElementById('number_of_questions').innerHTML += '<option value="' + i * 5 + '">' + i * 5 + '</option>';
//				}
//			}
//		} else {
//			document.getElementById('number_of_questions').innerHTML += '<option value="' + this.responseText + '">' + this.responseText + '</option>';
//		}
//	}
	
	if(categorySelect != null) {

		categorySelect.addEventListener('change', function() {
			getInfo('GET', './includes/queries.php?category=' + this.value, true);

			var totalTestTime = document.querySelector('select[name="number_of_questions"]').value;
			totalTestTime = totalTestTime == 'none' ? 0 : totalTestTime;
			document.getElementById('time').innerText = (totalTestTime/2) + " Minutes";

		});
		questionCountSelect.addEventListener('change', function() {
			var totalTestTime = this.value;
			document.getElementById('time').innerText = (totalTestTime/2) + " Minutes";
		});

		startButton.addEventListener('click', function(e) {
			var category = document.querySelector('select[name="category"]');
			var questions = document.querySelector('select[name="number_of_questions"]');

			if(category.value == "none" || questions.value == "none") {
				e.preventDefault();
			}
		});
	}
	if(choices.length !== 0) {
		
//		function submitAnswer(arr) {
//			for(let i = 0; i < choices.length; i++) {
//				choices[i].onclick = function() {
//					answerSubmit.removeEventListener('click', prevDef);
//				};
//				if(choices[i].checked) {
////					answerSubmit.removeEventListener('click', submitAnswer);
//					return true;
//				}
//			}
//			return false;
//		}
//		
//		var submit = submitAnswer(choices);
//		console.log(submit);
//		
//		if(!submit) {
//			answerSubmit.addEventListener('click', function prevDef(e) {
//				e.preventDefault();
//			})
//		} else {
//			answerSubmit.removeEventListener('click', prevDef);			
//		}
		
		
		
		answerSubmit.addEventListener('click', function submitAnswer(e) {
			
			for(let i = 0; i < choices.length; i++) {
				choices[i].onclick = function() {
					answerSubmit.removeEventListener('click', submitAnswer);
				};
				if(choices[i].checked) {
//					answerSubmit.removeEventListener('click', submitAnswer);
					return true;
				}
				e.preventDefault();
//				return false;
			};
		});
	}
},false);