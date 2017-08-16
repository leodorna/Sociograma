function desmarcaMembros(){
	esconde_grupos();
	checkboxes=document.getElementsByName('checkboxMembros[]');
	for(var i=0,n=checkboxes.length;i<n;i++){
		checkboxes[i].checked=false;
	}
}
function marcaMembros(){
	esconde_grupos();
	marcaAlunos();
	marcaMonitores();
	marcaProfessores();
}
function marcaAlunos(){
	esconde_grupos()
	checkboxes=document.getElementsByName('checkboxMembros[]');
	for(var i=0,n=checkboxes.length;i<n;i++){
		if(checkboxes[i].classList.contains('A'))
			checkboxes[i].checked=true;
	}
}
function marcaMonitores(){
	esconde_grupos()
	checkboxes=document.getElementsByName('checkboxMembros[]');
	for(var i=0,n=checkboxes.length;i<n;i++){
		if(checkboxes[i].classList.contains('M'))
			checkboxes[i].checked=true;
	}
}
function marcaProfessores(){
	esconde_grupos();
	checkboxes=document.getElementsByName('checkboxMembros[]');
	for(var i=0,n=checkboxes.length;i<n;i++){
		if(checkboxes[i].classList.contains('P'))
			checkboxes[i].checked=true;
	}
}
function mostra_grupos() {
	checkboxes=document.getElementsByName('checkboxMembros[]');
	for(var i=0,n=checkboxes.length;i<n;i++){
		checkboxes[i].checked=false;
	}
	
	document.getElementById('div_alunos').style.display = 'none';
	document.getElementById('div_grupos').style.display = 'block';
}
function esconde_grupos() {
	document.getElementById('div_alunos').style.display = 'block';
	document.getElementById('div_grupos').style.display = 'none';
	
}