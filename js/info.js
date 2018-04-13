/*This file contains event handlers for click events and form-submit events*/

//create product list(dictionary) for name retrieval via id, product-category list for category retrieval via id
//and category list for category name retrieval via id --- then show orders
//productsNameDict, productsCategoryDict, categoriesNameDict

//update the list of Orderexportfiles available for download
function updateExportList(){
    $.ajax({
        type: 'POST',
        url: 'ajax/info_updateDownloadsList.php'
    }).done(function(response){
        //alert(response);
        var files = JSON.parse(response);
        var listnode = $("#listDownloadFiles");
        listnode.empty();
        listnode.html("<h3>Downloads</h3>")
        for(i = files.length-1; i>=0;i--){
            var filename = files[i];
            if(!(filename=="." || filename=="..")) {
                listnode.append("<a href='downloads/"+filename+"' download><li>" + filename + "</li></a>");
            }
        }
        $(messages).text("Erfolgreiches Update!");
    }).fail(function(data){
        // Set the message text.
        if (data.responseText !== '') {
            $(messages).text(data.responseText);
        } else {
            $(messages).text('Fehler, Update der Downloadfiles konnte nicht durchgeführt werden.');
        }
    });
}
updateExportList();

//main function for click event handlers
var main = function(){

}
$(document).ready(main);

