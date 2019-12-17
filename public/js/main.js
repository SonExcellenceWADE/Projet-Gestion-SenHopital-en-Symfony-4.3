/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

$("#medecin_service").change((e)=>{
    var serviceSelector = $(e.target).closest('#medecin_service');

    $.ajax({
        url: "/service/specialites/",
        type: "GET",
        dataType: "JSON",
        data: {
            serviceid: serviceSelector.val()
        },
        success: (specialites) => {
            var specialiteSelect = $('#medecin_specialites');

            specialiteSelect.html('');

          $.each(specialites, function (key, specialite) {
            specialiteSelect.append('<option value="' + specialite.id + '">' + specialite.libelle + '</option>');
        });

            //console.log(specialiteSelect);
        },
        error: function (err) {
            alert("An error ocurred while loading data ...");
        }
    })
})





