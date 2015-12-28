/**
 * Created by r1 on 28.12.2015.
 */
$('#addToCart').click(function(){
    $.get("orders/order", { user_id: "John", time: "2pm" } );
});
