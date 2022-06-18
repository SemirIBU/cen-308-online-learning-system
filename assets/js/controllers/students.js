class Student {
  static init() {
    AUtils.role_based_elements();
    Student.get_all();
  }

  static get_all() {
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
        url: "api/admin/students",
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
          d.search = d.search.value;
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
      columns: [{ data: "id" }, { data: "name" }, { data: "email" }],
    });

  }
}
