$("#kl_restaurationbundle_adresslivraison_codePostal").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "https://api-adresse.data.gouv.fr/search/?postcode="+$("input[name='kl_restaurationbundle_adresslivraison[codePostal]']").val(),
            data: { q: request.term },
            dataType: "json",
            success: function (data) {
                var postcodes = [];
                response($.map(data.features, function (item) {
                    // Ici on est obligé d'ajouter les CP dans un array pour ne pas avoir plusieurs fois le même
                    if ($.inArray(item.properties.postcode, postcodes) == -1) {
                        postcodes.push(item.properties.postcode);
                        return { label: item.properties.postcode + " - " + item.properties.city + " - " + item.properties.context,
                                 city: item.properties.city,
                                 country: item.properties.context,
                                 latitude: item.geometry.coordinates[0],
                                 longitude: item.geometry.coordinates[1],
                                 value: item.properties.postcode
                        };
                    }
                }));
            }
        });
    },
    // On remplit aussi la ville
    select: function(event, ui) {
        $('#kl_restaurationbundle_adresslivraison_ville').val(ui.item.city);
        $('#kl_restaurationbundle_adresslivraison_pays').val(ui.item.country);
        $('#kl_restaurationbundle_adresslivraison_latitude').val(ui.item.latitude);
        $('#kl_restaurationbundle_adresslivraison_longitude').val(ui.item.longitude);
    }
});
$("#kl_restaurationbundle_adresslivraison_ville").autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "https://api-adresse.data.gouv.fr/search/?city="+$("input[name='kl_restaurationbundle_adresslivraison[ville]']").val(),
            data: { q: request.term },
            dataType: "json",
            success: function (data) {
                var cities = [];
                response($.map(data.features, function (item) {
                    // Ici on est obligé d'ajouter les villes dans un array pour ne pas avoir plusieurs fois la même
                    if ($.inArray(item.properties.postcode, cities) == -1) {
                        cities.push(item.properties.postcode);
                        return { label: item.properties.postcode + " - " + item.properties.city + " - " + item.properties.context,
                                 postcode: item.properties.postcode,
                                 country: item.properties.context,
                                 latitude: item.geometry.coordinates[0],
                                 longitude: item.geometry.coordinates[1],
                                 value: item.properties.city
                        };
                    }
                }));
            }
        });
    },
    // On remplit aussi le CP
    select: function(event, ui) {
        $('#kl_restaurationbundle_adresslivraison_codePostal').val(ui.item.postcode);
        $('#kl_restaurationbundle_adresslivraison_pays').val(ui.item.country);
        $('#kl_restaurationbundle_adresslivraison_latitude').val(ui.item.latitude);
        $('#kl_restaurationbundle_adresslivraison_longitude').val(ui.item.longitude);
    }
});
