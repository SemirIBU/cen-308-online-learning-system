class Profile{

    static init(){        
        Profile.get_user();
      }    

      static get_user(){

        RestClient.get("api/"+ AUtils.parse_jwt(window.localStorage.getItem("token")).r +"/profile", function(data){
            $('.profile-full-name').html(data.name);
            $('.profile-id').html(data.id);
            $('.profile-email').html(data.email);
            $('.profile-role').html(data.role);
            $('.profile-country').html(data.country);
            $('.profile-city').html(data.city);
            $('.profile-address').html(data.address);
            $('.profile-zip-code').html(data.zip_code);
            $('.profile-phone').html(data.phone);
        });
      }

}