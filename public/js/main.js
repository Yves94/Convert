var searchTag = []; // Tableau contenant les mots recherchés
var filterTag = []; // Tableau contenant les filtres demandé
var permutSize = 816; // Taille limite du changement d'interface

$(function() {
	/*\
	|*|---------------------------------------- Lors d'une connexion/déconnexion
	\*/

	if (localStorage.getItem('login') && localStorage.getItem('login') == 1) {
		localStorage.removeItem('login');
		notification('Vous êtes maintenant connecté');
		ChangeUrl(app_name + ' - Accueil', getDomainUrl());
	}

	if (localStorage.getItem('logout') && localStorage.getItem('logout') == 1) {
		localStorage.removeItem('logout');
		notification('Vous êtes maintenant déconnecté');
	}

	/*\
	|*|---------------------------------------- Initialisation
	\*/

	if ($('.unit').length) {
		localStorage.setItem('activeCategory', $.trim($('title').html().split('-')[1]));
		localStorage.setItem('activeCategoryUri', document.location.toString().split('/')[3]);
		$('[data-category^="' + localStorage.getItem('activeCategoryUri') + '"]').addClass('active');
	}

	/*\
	|*|---------------------------------------- Cookies
	\*/

	if (localStorage.getItem('convertapp_cookie_rules') && localStorage.getItem('convertapp_cookie_rules') == 1) {
		$('#cookies').hide();
	}

	$('#cookies button').click(function() {
		localStorage.setItem('convertapp_cookie_rules', 1);
		$('#cookies').hide();
	});

	/*\
	|*|---------------------------------------- Champ de recherche
	\*/

	// Agrandissement du champ de recherche au passage de la souris si une catégorie est active
	$('#search').hover(function() {
		if (localStorage.getItem('activeCategory')) {
			$('#search input').css('width', '120px');
		}
	}, function() {
		if (!$('#search input').is(':focus') && $('#search input').val() == '' && $('#tagList span').length == 0) {
			$('#search input').attr('style', '');
		}
	});

	// Indique à l'utilisateur qu'il n'a pas sélectionner de catégorie pour faire sa recherche
	$('#search').click(function() {
		if (!localStorage.getItem('activeCategory')) {
			notification('Veuillez sélectionner une catégorie', 1);
		}
	});

	// Fixe la taille du champ de recherche
	$('#search input').focus(function() {
		if (localStorage.getItem('activeCategory')) {
			$('#search input').css('width', '120px');
		}
	});

	// Diminution du champ lors d'un click hors zone de recherche
	$('#search input').blur(function() {
		if ($('#search input').val() == '' && $('#tagList span').length == 0) {
			$('#search input').attr('style', '');
		}
	});

	/*\
	|*|---------------------------------------- Boutons
	\*/

	// Lors d'un click sur le logo
	$('#logo').click(function() {
		ChangeUrl(app_name + ' - Accueil', getDomainUrl());
		disactiveCategory();
	});

	// Lors d'un click sur un bouton
	$('button').click(function() {
		if ($(this).data('title')) {
			ChangeUrl(app_name + ' - ' + $(this).data('title'), getDomainUrl() + $(this).data('url'));
			disactiveCategory();
		}
	});

	// Nombres significatifs
	$('#significativeNumber').change(function() {
		var value = $(this).val();
		$('#container').load(document.location.toString() + ' #container > *', { sNumber: value }, function() {
			notification(value + ' chiffre(s) significatifs');
		});
	});

	// Bouton de déconnexion
	$('#logout').click(function() {
		$.post(document.location.toString(), { logout: 1 }, function(data) {
			localStorage.setItem('logout', 1);
			location.reload();
		});
	});

	// Lors d'un click sur le bouton description d'une mesure
	$(document).on('click', '.btnDescription', function() {
		$(this).parent().parent().next().toggle('fast');
	});

	$(document).on('change', '[id^="mesure"] .calcul input', function() { calcul($(this)); });
	$(document).on('click', '[id^="mesure"] .btnCalcul', function() { calcul($(this)); });

	// Lors d'un click sur une catégorie
	$('.nameCategory').click(function() {
		disactiveCategory();
		$(this).addClass('active');
		// Supprimme la box des filtres si active
		$('#filtreBox').remove();

		localStorage.setItem('activeCategoryUri', $(this).data('category'));
		localStorage.setItem('activeCategory', $(this).find('.libelleCategorie').html());

		ChangeUrl(app_name + ' - ' + localStorage.getItem('activeCategory'), getDomainUrl() + '/' + localStorage.getItem('activeCategoryUri'));
	});

	/*\
	|*|---------------------------------------- Evenenements
	\*/

	$('#filtreTools').click(function() {
		loadFilter();
	});

	// Evènement sur le footer lorsque l'écran est de taille réduite
	$('#footer').click(function() {
		if ($(window).width() < permutSize) {
			alertBox('Information', $('#footer span').html());
		}
	});

	// Protection adresse mail
	$('a[href^="mailto:"]').each(function() {
		this.href = this.href.replace('(at)', '@').replace(/\(dot\)/g, '.');
		this.innerHTML = this.href.replace('mailto:', '');
	});

	// Liens avec les tooltips
	bindTooltip();
});

// Ouverture de l'espace filtre
function loadFilter() {
	category = localStorage.getItem('activeCategoryUri') || 0;

	if ($(window).width() < permutSize) {

		if (!$('#filtreBox').length) {
			// Si les filtres ne sont pas encore chargé pour une catégorie choisi

			var area = $('#boxAlert').clone().attr('id', 'filtreBox').css('display', 'none');
			$('#header').after(area);

			var div = $('<div id="filtre">');
			div.load(getDomainUrl() + '/filtre #container > *', { category: category }, function(data) {
				if (!data || !category) {
					notification('Aucune catégorie sélectionnée', 1);
				} else {
					alertBox('Filtres', $(this), 'filtreBox');
				}
			});
		} else {
			// Dans le cas ou la box des filtres est déjà chargé dans le DOM
			$('#filtreBox').openBox();
		}
		return false;
	}

	if (!$('#filtre').length) {

		var area = $('#hideScroll').clone().html('').attr('id', 'filtre').css('display', 'none');
		$('#header').after(area);
		
		
		$('#filtre').load(getDomainUrl() + '/filtre #container > *', { category: category }, function(data) {
			if (!data || !category) {
				notification('Aucune catégorie sélectionnée', 1);
				$('#filtre').remove();
			} else {
				$('#hideScroll').hide();
				$('#filtre').show();
			}
		});

	} else {
		if ($('#filtre').is(":visible")) {
			$('#filtre').hide();
			$('#hideScroll').show();
		} else {
			$('#filtre').show();
			$('#hideScroll').hide();
		}
	}
}

// Applique les filtres sélectionnés
$(document).on('click', '.filtreLibelle', function() {
	var check = $(this).find('span');
	if (check.is(":visible")) {
		check.hide('fast');
		filterTag.splice($.inArray($(this).text(), filterTag), 1);
	} else {
		check.show('fast');
		filterTag.push($(this).text());
	}

	filterTag.toString();

	if (filterTag == '') {
		$('[id^=mesure]').css('display', 'block');
	} else {
		$('[id^=mesure]').css('display', 'none').each(function() {
			var self = $(this);
			if ($(this).find('.filtreObj').length) {
				var filtre = $(this).find('.filtreObj').toArray();

				$.each(filtre, function(i, v) {
					filtre[i] = $(v).html();
				});

				$.each(filtre, function(i, v) {
					if (filterTag.indexOf(v) >= 0) {
						self.css('display', 'block'); 
					}
				});
			}
		});
	}
	striped();
});


// Dans le cas d'un retour à la page précédente
$(window).on('popstate', function(event) {
	ChangeUrl(event.originalEvent.state.Page, event.originalEvent.state.Url);
});

// Lors d'un changement de valeur sur une mesure
function calcul(self) {

	var libelle = self.closest('[id^="mesure"]').find('.unit').html();
	var uri = self.closest('[id^="mesure"]').data('uri');
	var value = self.closest('[id^="mesure"]').find('.value').val().replace(/ /g, '');
	var variables = '';

	if (self.closest('[id^="mesure"]').find('.variable').length) {
		var inputs = self.closest('[id^="mesure"]').find('.variable input');
		var key;

		$.each(inputs, function(index, value) {
			key = $(value).data('uri').toLowerCase();
			value = $(value).val() || 1;
			variables += '&' + key + '=' + value;
		});
	}

	ChangeUrl(app_name + ' - ' + localStorage.getItem('activeCategory') + ' - ' + libelle, getDomainUrl() + '/' + localStorage.getItem('activeCategoryUri') + '/' + uri + '?value=' + value + variables);
}

// Permet d'envoyer un formulaire de façon asynchrone
$(document).on('submit', 'form', function(event) {
	event.preventDefault();
	var id = $(this).attr('id');
	$.ajax({
		url: $(this).attr('action'),
		data: $(this).serialize(),
		type: $(this).attr('method'),
		success: function(data) {
			data = $.parseJSON(data);
			window['form_' + id](data);
		},
		error: function() {
			alertBox('Erreur', 'Une erreur est survenue');
		}
	});
});

// Résultat d'une connexion
function form_connect(data) {
	if (data['error']) {
		notification(data['error'], 1);
	} else {
		localStorage.setItem('login', 1);
		location.reload();
	}
}

function form_register(data) {
	if (data['error']) {
		notification(data['error'], 1);
	} else {
		ChangeUrl(app_name + ' - Connexion', getDomainUrl() + '/connexion');
		notification('Votre compte a été créé avec succès');
	}
}

function disactiveCategory() {
	// Aucune catégorie active
	localStorage.removeItem('activeCategory');
	localStorage.removeItem('activeCategoryUri');

	// Enlève le css sur la catégorie active
	$('.active').removeClass('active');
	// Détache les filtres
	$('#filtre').remove();
	$('#hideScroll').show();
}

function striped() {
	$('.striped').removeClass('striped');
	$('[id^=mesure]:visible:even').addClass('striped');
}

/*\
|*|---------------------------------------- Gestion des URL
\*/

function ChangeUrl(page, url) {
	// Chargement de la page
	$('#container').load(url + ' #container > *', function() {
		$('title').html(page);
		// Ajout la page en historique
		if (typeof (history.pushState) != 'undefined') {
			var obj = { Page: page, Url: url };
			history.pushState(obj, obj.Page, obj.Url);
		} else {
			alertBox('Problème d\'historisation. Votre navigateur ne supporte pas HTML5');
		}
	});
}

function getDomainUrl() {
	var url = document.location.toString();
	url = url.split('/');
	url = url[0] + '//' + url[2];
	return url;
}

/*\
|*|---------------------------------------- Champ de recherche
\*/

// Permet de d'ajouter des mots à une recherche
$(document).on('keydown', '#search input', function (event) {
	var text = $(this).val().toLowerCase();
	
	if (event.keyCode == 13 && text != '') {
		searchTag.push(text);
		$('#tagList').append('<span class="tag">' + text + '</span>');
		$(this).css('padding-left', '+=' + parseInt($('.tag').last().outerWidth(true)));
		$(this).val('');
	} else if (event.keyCode == 8 && text.length == 0) {
		searchTag.pop();
		$(this).css('padding-left', '-=' + parseInt($('.tag').last().outerWidth(true)));
		$('#tagList span:last-child').remove();
	}
});

// Fonction de recherche
$(document).on('keyup', '#search input', function () {

	var keyWord = $(this).val().toLowerCase() || searchTag[searchTag.length - 1] || '';
	$('[id^=mesure]').css('display', 'none').each(function() {
		var mesure = $(this).find('.unit').html().toLowerCase();
		var self = $(this);
		$.each(searchTag, function(i, value) {
			if (mesure.indexOf(value) > -1) {
				self.css('display', 'block');
			}
		})
		if (mesure.indexOf(keyWord) > -1) {
			self.css('display', 'block');
		}
	});

	$('#noResult').remove();
	if ($('[id^=mesure]:visible').length == 0) {
		$('#container').append('<div id="noResult" style="text-align: center;margin-top: 50px;color: #1a2530;"><span class="ion-sad-outline" style="font-size: 100px;"></span><p style="font-size:30px;margin:0;">Aucun résultat</p></div>');
	} else {
		striped();
	}
});

/*\
|*|---------------------------------------- Notification
\*/
var myTime;
// Fonction d'appel des messages
function notification(msg, error, override) {
	var newMsg = override || 0;
    if ($('#boxMsg').css('display') == 'none') {
    	if (error) { $('#boxMsg').addClass('error'); }
    	$('#boxMsg').html(msg);
        $('#boxMsg').show(0, function() {
        	$('#boxMsg').addClass('in');
        });
        myTime = setTimeout(function() { notification(msg, error, 1); }, 4000);
    } else if (newMsg) {
    	$('#boxMsg').removeClass('in');
        setTimeout(function() {
        	$('#boxMsg').hide();
        	$('#boxMsg').removeClass('error');
        }, 500);
    } else {
    	clearTimeout(myTime);
    	if (error) { $('#boxMsg').addClass('error'); }
    	$('#boxMsg').html(msg);
    	myTime = setTimeout(function() { notification(msg, error, 1); }, 4000);
    }
}

/*\
|*|---------------------------------------- Ajax Loader
\*/

// Déclenché lors d'un appel Ajax, permettant d'indiquer un chargement
$(document).on({
	ajaxStart: function() { $('#ajaxLoader').css('display', 'block'); },
	ajaxStop: function() { $('#ajaxLoader').css('display', 'none'); bindTooltip(); }    
});

/*\
|*|---------------------------------------- Gestion des fenêtres modales Alert et Confirmation
\*/

// Fait appel à une fenêtre modale de type Confirm
function confirmBox(title, body, result) {
	$('#boxConfirm .box-title').html(title);
	$('#boxConfirm .box-body').html(body);
	$('#boxConfirm').openBox();
	$('#boxConfirm .btn-success').click(function() {
		result();
	});
	$('#boxConfirm input').click(function() {
		$('#boxConfirm').closeBox();
		result = function() {};
	});
}

// Fait appel à une fenêtre modale de type Alert
function alertBox(title, body, box) {
	box = box || 'boxAlert';
	$('#' + box + ' .box-title').html(title);
	$('#' + box + ' .box-body').html(body);
	$('#' + box).openBox();
	$('#' + box + ' .input').click(function() {
		$('#' + box).closeBox();
	});
}

// Ouverture d'une fenêtre modale
$.fn.openBox = function() {
	$(this).slideDown('fast');
	$('#blackBack').addClass('activeBack');
}

// Fermeture d'une fenêtre modale
$.fn.closeBox = function() {
	$(this).slideUp('fast');
	$('#blackBack').removeClass('activeBack');
}

/*\
|*|---------------------------------------- Gestion des Tooltips
\*/

// Lien avec les tooltips
function bindTooltip() {
	// Appel du tooltip
	$('[data-tooltip]').unbind();
	$('[data-tooltip]').bind({
		mousemove: changeTooltipPosition,
		mouseenter: showTooltip,
		mouseleave: hideTooltip
	});
}

var showTooltip = function(event) {
	$('div.tooltip').remove();
	$('<div class="tooltip">' + $(this).data('tooltip') + '</div>').appendTo('body');
	changeTooltipPosition(event);
}

var changeTooltipPosition = function(event) {
	var midWidth = $('div.tooltip').width() / 2;
	var tooltipX = event.pageX - midWidth;
	var tooltipY = event.pageY + 20;
	$('div.tooltip').css({ top: tooltipY, left: tooltipX });
}

var hideTooltip = function() {
	$('div.tooltip').remove();
}