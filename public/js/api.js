var list = [];

$(function() {
	$('.ca_line').each(function(i, v) {
		list[i] = $(this).attr('id');
	});
	display(1, list[0]);
});

// Lors d'un changement de valeur sur une mesure
$(document).on('change', '.ca_valueConvert input', function() {
	// Récupération de l'ID de la mesure
	var id = $(this).parent().parent().attr('id');
	// Test si le champ n'est pas vide
	if (!$(this).val()) {
		alert('Champ vide, veuillez renseigner une valeur');
		$(this).val('1');
		return;
	}
	// Re-chargement de toutes les mesures avec la nouvelle valeur (Catégorie courante, nouvelle valeur, unité modifiée)
	display($(this).val(), id);
});

function display(number, id) {

	// Test si la valeur saisi est bien un nombre
	if (!$.isNumeric(number)) {
		number = number.replace(/\s/g, '');
		if (!$.isNumeric(number)) {
			alert('Erreur, veuillez saisir uniquement des chiffres !');
			return;
		}
	}

	$.ajax({
		url: 'http://fw.dev/apiconvert',
		data: {
			list: list,
			number: number,
			id: id
		},
		type: 'post',
		success: function(data) {
			console.log(data);
			if (data) {
				data = $.parseJSON(data);
				$.each(data, function(i, v) {
					$('.ca_line').eq(i).find('input').val(v);
				});
			} else { $('#ca_container').html('<div>Aucune donnée trouvée !</div>'); }
		},
		error: function() {
			alert("Erreur, une erreur est survenue !\nVerifiez votre connexion internet.\nSi le problème persiste, veuillez contacter\ncontact@myconverter.net");
		}
	});	
}