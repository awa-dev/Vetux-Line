ecutable File  8 lines (8 sloc)  300 Bytes

$("#new_edit_utilisateur").on('submit', function(){
    if($("#utilisateur_password").val() != $("#verifpass").val()) {
        //implémntez votre code
        alert("Les deux mots de passe saisis sont différents");
        alert("Merci de renouveler l'opération");
        return false;
    }
})