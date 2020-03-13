$('#add-image').click(function(){

    alert("fail");
    // je recupere le numero des futurs champs que je vais creer
    const index = +$('#widgets-counter').val();

    // je recupere le prototype des entrees 
    const template = $('#annonce_pics').data('prototype').replace(/_name_/g, index);

   // j'injecte ce code au sein de la div
    $('#annonce_pics').append(template);

    $('#widgets-counter').val(index + 1);

    // manage delete button
    handleDeleteButtons();
});

// Creating button delete function
function handleDeleteButtons(){
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        $(target).remove();
    });
}
// Creating Counter function for bug fix
function updateCounter() {
    const count = +$('#annonce_pics div.form-group').length;
    $('#widgets-counter').val(count);
}
    //Call UpdateCounter function
    updateCounter();
    //Call delete button function
    handleDeleteButtons();
