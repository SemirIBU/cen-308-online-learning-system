class Profile{

    static init(){        
        Profile.get_user();
      }    
    
    
    
      static get_user(){

        RestClient.get("api/"+ AUtils.parse_jwt(window.localStorage.getItem("token")).r +"/account", function(data){
            $('.welcome-message').html(data.name);
        });
      }

}