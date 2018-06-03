
$(document).ready(function(){
  tritonServiceAjax(
    "signup"
    ,"test"
    , {name:"Juanita", last_name: "Gallardo Pe\ña"}
    , function (result){
        console.log("v3:::" + result); /* Result: {success: true, message: '', posts: [{...}{...}{...}...}] */
      }
  );
});
