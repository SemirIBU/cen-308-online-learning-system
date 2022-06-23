class Profile {
  static init() {
    Profile.get_user();
  }

  static get_user() {
    Profile.get_profile_image();
    RestClient.get(
      "api/" +
        AUtils.parse_jwt(window.localStorage.getItem("token")).r +
        "/profile",
      function (data) {
        $(".profile-full-name").html(data.first_name + " " + data.last_name);
        $(".profile-first-name").html(data.first_name);
        $(".profile-last-name").html(data.last_name);
        $(".profile-id").html(data.id);
        $(".profile-email").html(data.email);
        $(".profile-role").html(data.role);
        $(".profile-country").html(data.country);
        $(".profile-city").html(data.city);
        $(".profile-address").html(data.address);
        $(".profile-zip-code").html(data.zip_code);
        $(".profile-phone").html(data.phone);
      }
    );
  }

  static upload_profile_image(event) {
    console.log(event.files);

    var f = event.files[0];

    var reader = new FileReader();
    reader.onload = (function (theFile) {
      return function (e) {
        var upload = {
          name: f.name,
          content: e.target.result.split(",")[1],
        };
        $.ajax({
          url: "api/student/profile-picture",
          type: "POST",
          data: JSON.stringify(upload),
          contentType: "application/json",
          beforeSend: function (xhr) {
            xhr.setRequestHeader(
              "Authentication",
              localStorage.getItem("token")
            );
          },
          success: function (data) {
            toastr.success("You have changed your profile image");
            console.log(data);

            $("#profile-image").attr(
              "src",
              data.url + "?t=" + new Date().getTime()
            );
          },
          error: function (jqXHR, textStatus, errorThrown) {
            toastr.error(jqXHR.responseJSON.message);
          },
        });
      };
    })(f);
    reader.readAsDataURL(f);
  }
  static get_profile_image() {
    RestClient.get(
      "api/student/profile-picture",
      function (data) {
        if (data.url != 0) {
          $("#profile-image").attr(
            "src",
            data.url + "?t=" + new Date().getTime()
          );
        }
      },
      function (data) {
        console.log("Error");
      }
    );
  }
}
