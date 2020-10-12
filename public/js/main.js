var url= 'http://localhost:8888/master-php/proyecto-laravel/public/';
window.addEventListener("load", function () {
    //$('body').css('background','red'); -> test jQuery

    $('.btn-like').css('cursor', 'pointer');
    $('.btn-dislike').css('cursor', 'pointer');

    //like button
    function like() {
        $('.btn-like').unbind('click').click(function () {
            //console.log('like');
            $(this).addClass('btn-dislike').removeClass('btn-like');
            $(this).attr('src', url+'img/heart-red.png');
            
            $.ajax({
                url: url+'/like/'+$(this).data('id'),
                type: 'GET',
                success: function(response){
                    if(response.like){
                        console.log("has dado like a la publicacion de la imagen");
                    }else{
                        console.log("error al dar like");
                    }
                }
            });
            
            
            dislike();
        })
    }
    like();

    //dislike button
    function dislike() {
        $('.btn-dislike').unbind('click').click(function () {
            //console.log('dislike');
            $(this).addClass('btn-like').removeClass('btn-dislike');
            $(this).attr('src', url+'img/heart-black.png');
            
            $.ajax({
                url: url+'/dislike/'+$(this).data('id'),
                type: 'GET',
                success: function(response){
                    if(response.like){
                        console.log("has dado dislike a la publicacion de la imagen");
                    }else{
                        console.log("error al dar dislike");
                    }
                }
            });
            
            like();
        })
    }
    dislike();
    
    //for search functionality
    $('#buscador').submit(function(e){
        //e.preventDefault(); // to stop execution
        $(this).attr('action',url+'gente/'+$('#buscador #search').val());
    });
    
});
