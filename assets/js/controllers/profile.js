class Profile{

    static init(){        
        Profile.get_user();
      }    

      static get_user(){

        RestClient.get("api/"+ AUtils.parse_jwt(window.localStorage.getItem("token")).r +"/profile", function(data){
            $('.profile-full-name').html(data.name);
            $('.profile-email').html(data.email);
            $('.profile-role').html(data.role);
        });
      }

}