class Course {
  static init() {
    
    $("#add-course").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        var data = AUtils.form2json($(form));
        $("#courses-data-table").hide();
          Course.add(data);        
      },
    });
    $("#edit-course").validate({
      submitHandler: function (form, event) {
        event.preventDefault();
        var data = AUtils.form2json($(form));
        $("#courses-data-table").hide();
          Course.update(data);        
      },
    });
    AUtils.role_based_elements();
    Course.get_all();
  }

  static get_all() {
    if(AUtils.parse_jwt(localStorage.getItem('token'))['r']=='student'){
      $("#students-data-table").DataTable({
        processing: true,
        serverSide: true,
        bDestroy: true,
        pagingType: "simple",
        preDrawCallback: function (settings) {
          if (settings.aoData.length < settings._iDisplayLength) {
            settings._iRecordsTotal = 0;
            settings._iRecordsDisplay = 0;
          } else {
            settings._iRecordsTotal = 100000000;
            settings._iRecordsDisplay = 1000000000;
          }
        },
        responsive: true,
        language: {
          zeroRecords: "Nothing found - sorry",
          info: "Showing page _PAGE_",
          infoEmpty: "No records available",
          infoFiltered: "",
        },
        ajax: {
          url: "api/student/courses",
          type: "GET",
          beforeSend: function (xhr) {
            xhr.setRequestHeader("Authentication", localStorage.getItem("token"));
          },
          dataSrc: function (resp) {
            return resp;
          },
          data: function (d) {
            d.offset = d.start;
            d.limit = d.length;
            d.order = encodeURIComponent(
              (d.order[0].dir == "asc" ? "-" : "+") +
                d.columns[d.order[0].column].data
            );
            delete d.start;
            delete d.length;
            delete d.columns;
            delete d.draw;
          },
        },
        columns: [          
          {
            data: "id",
            render: function (data, type, row, meta) {
              return (
                '<div class="course-id-field" style="min-width: 60px;"><span class="badge">' +
                data +
                '</span><div><a class="pull-left" style="font-size: 15px; cursor: pointer;" onclick="Course.unenrol(' +
                data +
                ')"><i class="unenrol-icon"></i></a></div>  </div>'
              );
            },
          },
          { data: "name" },
          { data: "description" },
        ],
      });
      $("#students-data-table").show();
      $("#available-courses-data-table").DataTable({
        processing: true,
        serverSide: true,
        bDestroy: true,
        pagingType: "simple",
        preDrawCallback: function (settings) {
          if (settings.aoData.length < settings._iDisplayLength) {
            settings._iRecordsTotal = 0;
            settings._iRecordsDisplay = 0;
          } else {
            settings._iRecordsTotal = 100000000;
            settings._iRecordsDisplay = 1000000000;
          }
        },
        responsive: true,
        language: {
          zeroRecords: "Nothing found - sorry",
          info: "Showing page _PAGE_",
          infoEmpty: "No records available",
          infoFiltered: "",
        },
        ajax: {
          url: "api/student/available",
          type: "GET",
          beforeSend: function (xhr) {
            xhr.setRequestHeader("Authentication", localStorage.getItem("token"));
          },
          dataSrc: function (resp) {
            return resp;
          },
          data: function (d) {
            d.offset = d.start;
            d.limit = d.length;
            d.order = encodeURIComponent(
              (d.order[0].dir == "asc" ? "-" : "+") +
                d.columns[d.order[0].column].data
            );
            delete d.start;
            delete d.length;
            delete d.columns;
            delete d.draw;
          },
        },
        columns: [          
          {
            data: "id",
            render: function (data, type, row, meta) {
              return (
                '<div class="course-id-field" style="min-width: 60px;"><span class="badge">' +
                data +
                '</span><div><a class="pull-left" style="font-size: 15px; cursor: pointer;" onclick="Course.enrol(' +
                data +
                ')"><i class="enrol-icon"></i></a></div></div>'
              );
            },
          },
          { data: "name" },
          { data: "description" },
        ],
      });
      $("#available-courses-data-table").show();
    }else
    $("#courses-data-table").DataTable({
      processing: true,
      serverSide: true,
      bDestroy: true,
      pagingType: "simple",
      preDrawCallback: function (settings) {
        if (settings.aoData.length < settings._iDisplayLength) {
          settings._iRecordsTotal = 0;
          settings._iRecordsDisplay = 0;
        } else {
          settings._iRecordsTotal = 100000000;
          settings._iRecordsDisplay = 1000000000;
        }
      },
      responsive: true,
      language: {
        zeroRecords: "Nothing found - sorry",
        info: "Showing page _PAGE_",
        infoEmpty: "No records available",
        infoFiltered: "",
      },
      ajax: {
        url: "api/admin/courses",
        type: "GET",
        beforeSend: function (xhr) {
          xhr.setRequestHeader("Authentication", localStorage.getItem("token"));
        },
        dataSrc: function (resp) {
          return resp;
        },
        data: function (d) {
          d.offset = d.start;
          d.limit = d.length;
          d.order = encodeURIComponent(
            (d.order[0].dir == "asc" ? "-" : "+") +
              d.columns[d.order[0].column].data
          );
          delete d.start;
          delete d.length;
          delete d.columns;
          delete d.draw;
        },
      },
      columns: [
        {
          data: "id",
          render: function (data, type, row, meta) {
            return (
              '<div class="course-id-field" style="min-width: 60px;"> <span class="badge">' +
              data +
              '</span><div><a class="pull-right" style="font-size: 15px; cursor: pointer;" data-target="#edit-course-modal"  onclick="Course.pre_edit(' +
              data +
              ')"><i class="edit-icon"></i></a><a class="pull-left" style="font-size: 15px; cursor: pointer;" onclick="Course.delete(' +
              data +
              ')"><i class="delete-icon"></i></a></div>  </div>'
            );
          },
        },
        { data: "name" },
        { data: "description" },
      ],
    });
    $("#courses-data-table").show();

  }

  static add(course) {
    RestClient.post("api/admin/courses", course, function (data) {
      toastr.success("Course has been added");
      $("#courses-data-table").hide();
      Course.get_all();
      $("#add-course").trigger("reset");
      $("#add-course-modal").modal("hide");
    });
  }

  static update(course) {
    RestClient.put("api/admin/courses/" + course.id, course, function (data) {
      toastr.success("Course has been updated");
      Course.get_all();
      $("#edit-course").trigger("reset");
      $("#edit-course *[name='id']").val("");
      $("#edit-course-modal").modal("hide");
    });
  }

  static pre_edit(id) {
    RestClient.get("api/admin/courses/" + id, function (data) {
      AUtils.json2form("#edit-course", data);
      $("#edit-course-modal").modal("show");
      
    });
  }
  static delete(id) {
    RestClient.delete("api/admin/courses/" + id, function (data) {
      toastr.success("Course has been deleted");
      $("#courses-data-table").hide();
      Course.get_all();
    });
  }
  static enrol(courseid) {
    RestClient.post("api/student/enrol/"+ courseid, false,function (data) {
      toastr.success("You have enrolled the course");
      $("#available-courses-data-table").hide();
      Course.get_all();
    });
  }
  static unenrol(courseid) {
    RestClient.delete("api/student/unenrol/"+ courseid, function (data) {
      toastr.success("You have unenrolled the course");
      $("#students-data-table").hide();
      Course.get_all();
    });
  }
}
