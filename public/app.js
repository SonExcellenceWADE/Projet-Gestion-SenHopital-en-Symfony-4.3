/* var  $medecin_service = $('$medecin_service')
var $token = $('#post_token')

$medecin_service.change(function(){
    
    var $form = $(this).closest('form')
    var data = {}
    data[$token.attr('name')] = $token.val()
    data[$medecin_service.attr('name')] = $medecin_service.val()

    $.post($form.attr('action'), data).then(function(response){
       $('#medecin_specialite').replaceWith(

        $(response).find('#medecin_specialite')
       );
    })
}) */


$("#medecin_service").change((e)=>{
    var serviceSelector = $(e.target).closest('#medecin_service');

    $.ajax({
        url: "/service/specialites",
        type: 'GET',
        dataType: 'JSON',
        data: {
            id: serviceSelector.val()
        },
        success: (specialites) => {
            var specialiteSelect = $('#medecin_specialite');

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

